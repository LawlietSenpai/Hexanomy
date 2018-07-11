<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use View;
use Validator;
use Illuminate\Support\Facades\Input;
use Redirect;
use Hash;
use Session;
use App\Model\Animal;
use JasperPHP\JasperPHP;
use Response;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function animal_index()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $animal = Animal::where('animalname','like','%'.$search.'%')
        ->orderBy('animalname')
        ->paginate(20);       
 
        return View('animal_index', ['animal' => $animal]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function animal_create()
    {
        return View::make('animal_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function animal_create_process()
    {
        $rules = array(
            'animalname'        => 'required',
            'sciencename'       => 'required', 
            'class'             => 'required',
            'characteristic'    => 'required', 
            'dnaseq'            => 'required',
            'molecularstruc'    => 'required'
            );

        $file = array('molecularstruc' => Input::file('molecularstruc'));
        // setting up rules
        $rules = array('molecularstruc' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->messages();

            return Redirect::to('animal_create')
            ->withErrors($validator);
        }
        else
        {
            if (Input::file('molecularstruc')->isValid()) {
                $destinationPath = '../upload/'; // upload path
                $extension = Input::file('molecularstruc')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image

                $path = Input::file('molecularstruc')->move($destinationPath, $fileName); // uploading file to given path

            $add = new Animal;
            $add->animalname        = Input::get('animalname');
            $add->sciencename       = Input::get('sciencename');
            $add->class             = Input::get('class');
            $add->characteristic    = Input::get('characteristic');
            $add->dnaseq            = Input::get('dnaseq');
            $add->molecularstruc    = $path;
        
            $add->save();

            Session::flash('message', 'Successful created animal!');
            return Redirect::to('animal_create');
        }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function animal_show($id)
    {
        $animal = Animal::find($id);

        return View::make('animal_show',array('animal' => $animal));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function animal_edit($id)
    {
        $animal = Animal::find($id);

        return View::make('animal_edit',array('animal' => $animal));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function animal_edit_process($id)
    {
        $rules_edit = array(
            'animalname'        => 'required',
            'sciencename'       => 'required',
            'class'             => 'required',
            'characteristic'    => 'required',
            'dnaseq'            => 'required',
            'molecularstruc'    => 'required',
            );

        $file = array('molecularstruc' => Input::file('molecularstruc'));
        // setting up rules
        $rules = array('molecularstruc' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);

        $validator = Validator::make(Input::all(),$rules_edit);
        
        if($validator->fails())
        {
            $messages = $validator->messages();

            return Redirect::to('animal_edit/'.$id)
                ->withErrors($validator);
        }
        else
        {
            if (Input::file('molecularstruc')->isValid()) {
                $destinationPath = '../upload/'; // upload path
                $extension = Input::file('molecularstruc')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image

                $path = Input::file('molecularstruc')->move($destinationPath, $fileName); // uploading file to given path

            $edit                   = Animal::find($id);
            $edit->animalname       = Input::get('animalname');
            $edit->sciencename      = Input::get('sciencename');
            $edit->class            = Input::get('class');
            $edit->characteristic   = Input::get('characteristic');
            $edit->dnaseq           = Input::get('dnaseq');
            $edit->molecularstruc   = $path;

            $edit->save();


            Session::flash('message', 'Succesfully updated animal!');
            return Redirect::to('animal_edit/'.$id);
        }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function animal_delete($id)
    {
        $animal = Animal::where('id',$id) ->delete();

        Session::flash('message', 'Succesfully delete animal!');
        return Redirect::to('animal_index');
    }

    /*Jasper Print Function*/
    public function cetak_laporanAnimal()
    {

        $btn_pdf = Input::get('btnpdf');
        $btn_xls = Input::get('btnxls');

        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();
        $filename = "animal1";
        $reportPath = "/views/laporan";

        $resource_path = $reportPath . "/" . "$filename.jasper";
        $public_path = "laporan/$filename";
        $pdf = "laporan/$filename.pdf";
        $xls = "laporan/$filename.xls";


        if(isset($btn_pdf))
        {
            $flag = "pdf";
        }
        if(isset($btn_xls))
        {
            $flag = "xls";
        }

        \JasperPHP::process(
            resource_path(). $resource_path,
            public_path()."/". $public_path,
            array("pdf", "xls"),
            array(),
            $database
            )->execute();

        return \View::make('report_index',
            array('report_title'=> 'LAPORAN SENARAI EDIT','pdf' => $pdf , 'xls' => $xls, 'flag' => $flag));
    }

    public function cetak_singleAnimal($id)
    {                                                   
        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();

        $filename = "specificAnimal";
        $resources_path = "/views/laporan/$filename.jasper";
        $public_path = "/laporan/$filename";
        $pdf = "laporan/$filename.pdf";

        $flag = "pdf";

        \JasperPHP::process(
            resource_path(). $resources_path,
            public_path() . $public_path,
            array("pdf"),
            array('id' => $id),   
            $database
        )->execute();

        return \View::make( 'report_index',array('report_title' => 'SENARAI PELAJAR BERDAFTAR', 'pdf' => $pdf, 'flag'=>$flag));        
    }

    public function search_data()
    {
        $search = Input::get('search');

        $sql2 = Animal::select('id','name')
        ->where('name', '=', $search)
        ->get();
        return View::make('searchanimal', array('sql2' => $sql2));
    }

    public function sorting_animal()
    {
        $sort = Input::get('sortanimal');
        $animal = Animal::select('id','animalname','sciencename')
        ->orderBy($sort)
        ->get();
        return View::make('animal_index', array('animal' => $animal));
    }
}

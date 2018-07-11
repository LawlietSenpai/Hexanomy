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
use App\Model\Plant;
use JasperPHP\JasperPHP;
use Response;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function plant_index()
    {
        

        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $plant = Plant::where('plantname','like','%'.$search.'%')
        ->orderBy('plantname')
        ->paginate(20);
 
        return View('plant_index', ['plant' => $plant]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function plant_create()
    {
        return View::make('plant_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function plant_create_process()
    {
        $rules = array(
            'plantname'         => 'required',
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

            return Redirect::to('plant_create')
            ->withErrors($validator);
        }
        else
        {
            if (Input::file('molecularstruc')->isValid()) {
                $destinationPath = '../upload/'; // upload path
                $extension = Input::file('molecularstruc')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image

                $path = Input::file('molecularstruc')->move($destinationPath, $fileName); // uploading file to given path

            $add = new Plant;
            $add->plantname         = Input::get('plantname');
            $add->sciencename       = Input::get('sciencename');
            $add->class             = Input::get('class');
            $add->characteristic    = Input::get('characteristic');
            $add->dnaseq            = Input::get('dnaseq');
            $add->molecularstruc    = $path;
        
            $add->save();

            Session::flash('message', 'Successful created plant!');
            return Redirect::to('plant_create');
        }
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function plant_show($id)
    {
        $plant = Plant::find($id);

        return View::make('plant_show',array('plant' => $plant));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function plant_edit($id)
    {
        $plant = Plant::find($id);

        return View::make('plant_edit',array('plant' => $plant));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function plant_edit_process($id)
    {
        $rules_edit = array(
            'plantname'         => 'required',
            'class'             => 'required',
            'sciencename'       => 'required',
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

            return Redirect::to('plant_edit/'.$id)
                ->withErrors($validator);
        }
        else
        {
            if (Input::file('molecularstruc')->isValid()) {
                $destinationPath = '../upload/'; // upload path
                $extension = Input::file('molecularstruc')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image

                $path = Input::file('molecularstruc')->move($destinationPath, $fileName); // uploading file to given path
                
            $edit                   = Plant::find($id);
            $edit->plantname        = Input::get('plantname');
            $edit->sciencename      = Input::get('sciencename');
            $edit->class            = Input::get('class');
            $edit->characteristic   = Input::get('characteristic');
            $edit->dnaseq           = Input::get('dnaseq');
            $edit->molecularstruc   = $path;

            $edit->save();

            Session::flash('message', 'Succesfully updated plant!');
            return Redirect::to('plant_edit/'.$id);
        }
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function plant_delete($id)
    {
        $plant = Plant::where('id',$id) ->delete();

        Session::flash('message', 'Succesfully delete plant!');
        return Redirect::to('plant_index');
    }

    public function getUploadForm() {
        return View::make('molecularstruc/plant_index');
    }
 
    public function postUpload() {
        $file = Input::file('molecularstruc');
        $input = array('molecularstruc' => $file);
        $rules = array(
            'molecularstruc' => 'molecularstruc'
        );
        $validator = Validator::make($input, $rules);
        if ( $validator->fails() )
        {
            return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
 
        }
        else {
            $destinationPath = 'plant_create';
            $filename = $file->getClientOriginalName();
            Input::file('molecularstruc')->move($destinationPath, $filename);
            return Response::json(['success' => true, 'file' => asset($destinationPath.$filename)]);
        }
 
    } 

    /*Jasper Print Function*/
    public function cetak_laporanPlant()
    {

        $btn_pdf = Input::get('btnpdf');
        $btn_xls = Input::get('btnxls');

        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();
        $filename = "plant";
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

    public function cetak_singlePlant($id)
    {                                                   
        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();

        $filename = "specificPlant";
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

        $sql2 = Plant::select('id','plantname')
        ->where('plantname', '=', $search)
        ->get();
        return View::make('searchplant', array('sql2' => $sql2));
    }

    public function sorting_plant()
    {
        $sort = Input::get('sortplant');
        $plant = Plant::select('id','plantname','sciencename')
        ->orderBy($sort)
        ->get();
        return View::make('plant_index', array('plant' => $plant));
    }
}

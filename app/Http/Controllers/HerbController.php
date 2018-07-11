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
use App\Model\Domain;
use App\Model\Kingdom;
use App\Model\Subkingdom;
use App\Model\Species;
use App\Model\Phylum;
use App\Model\Subphylum;
use App\Model\Infraphylum;
use App\Model\Orders;
use App\Model\Kelas;
use App\Model\Family;
use App\Model\Genus;
use JasperPHP\JasperPHP;
use Response;

class HerbController extends Controller
{
    public function herb_index()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $species = Species::where('scientificname','like','%'.$search.'%')
        ->orderBy('scientificname')
        ->paginate(1000);
 
        return View('herb_index', ['species' => $species]);
    }    

    public function herb_kingdom()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $kingdom = Kingdom::where('kingdom_name','like','%'.$search.'%')
        ->orderBy('kingdom_name')
        ->paginate(1000);
 
        return View('herb_kingdom', ['kingdom' => $kingdom]);
    }

    public function herb_kelas()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $kelas = Kelas::where('class_name','like','%'.$search.'%')
        ->orderBy('class_name')
        ->paginate(1000);
 
        return View('herb_kelas', ['kelas' => $kelas]);
    }

        public function herb_genus()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $genus = Genus::where('genus_name','like','%'.$search.'%')
        ->orderBy('genus_name')
        ->paginate(1000);
 
        return View('herb_genus', ['genus' => $genus]);
    }

         public function herb_family()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $family = Family::where('family_name','like','%'.$search.'%')
        ->orderBy('family_name')
        ->paginate(1000);
 
        return View('herb_family', ['family' => $family]);
    }

    
         public function herb_order()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $orders = Orders::where('order_name','like','%'.$search.'%')
        ->orderBy('order_name')
        ->paginate(1000);
 
        return View('herb_order', ['orders' => $orders]);
    }
    
         public function herb_infraphylum()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $infraphylum = Infraphylum::where('infraphylum_name','like','%'.$search.'%')
        ->orderBy('infraphylum_name')
        ->paginate(1000);
 
        return View('herb_infraphylum', ['infraphylum' => $infraphylum]);
    }

        public function herb_subphylum()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $subphylum = Subphylum::where('subphylum_name','like','%'.$search.'%')
        ->orderBy('subphylum_name')
        ->paginate(1000);
 
        return View('herb_subphylum', ['subphylum' => $subphylum]);
    }

        public function herb_subkingdom()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $subkingdom = Subkingdom::where('subkingdom_name','like','%'.$search.'%')
        ->orderBy('subkingdom_name')
        ->paginate(1000);
 
        return View('herb_subkingdom', ['subkingdom' => $subkingdom]);
    }

        public function herb_domain()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $domain = Domain::where('domain_name','like','%'.$search.'%')
        ->orderBy('domain_name')
        ->paginate(1000);
 
        return View('herb_domain', ['domain' => $domain]);
    }
    
         public function herb_phylum()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $phylum = Phylum::where('phylum_name','like','%'.$search.'%')
        ->orderBy('phylum_name')
        ->paginate(1000);
 
        return View('herb_phylum', ['phylum' => $phylum]);
    }
    
    public function herb_create()
    {
        $phylum = Phylum::selectRaw("id,phylum_name")->get();
        $subphylum = Subphylum::selectRaw("id,subphylum_name")->get();
        $infraphylum = Infraphylum::selectRaw("id,infraphylum_name")->get();
        $orders = Orders::selectRaw("id,order_name")->get();
        $kelas = Kelas::selectRaw("id,class_name")->get();
        $family = Family::selectRaw("id,family_name")->get();
        $genus = Genus::selectRaw("id,genus_name")->get();
        $kingdom = Kingdom::selectRaw("id,kingdom_name")->get();
        $subkingdom = Subkingdom::selectRaw("id,subkingdom_name")->get();
        $domain = Domain::selectRaw("id,domain_name")->get();

        return View::make('herb_create', [
            'phylum'=>$phylum, 
            'orders'=> $orders, 
            'kelas'=> $kelas,
            'family'=> $family,
            'subphylum'=> $subphylum,
            'infraphylum'=> $infraphylum,
            'genus'=> $genus,
            'kingdom'=> $kingdom,
            'subkingdom'=> $subkingdom,
            'domain'=> $domain 
            ]);
    }

    public function herb_create_process()
    {
        $rules = array(            
            'scientificname'          => 'required', 
            'common'                  => 'required',
            'domain_id'               => 'required', 
            'kingdom_id'              => 'required',
            'subkingdom_id'           => 'required',
            'phylum_id'               => 'required',
            'subphylum_id'            => 'required',
            'infraphylum_id'          => 'required',
            'kelas_id'                => 'required',
            'order_id'                => 'required',
            'family_id'               => 'required',
            'genus_id'                => 'required',
            'image'                   => 'required'
            );

        $file = array('image' => Input::file('image'));
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->messages();

            return Redirect::to('herb_create')
            ->withErrors($validator);
        }
        else
        {
            if (Input::file('image')->isValid()) {
                $destinationPath = '../upload/'; // upload path
                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image

                $path = Input::file('image')->move($destinationPath, $fileName); // uploading file to given path

            $add = new Species;            
            $add->scientificname       = Input::get('scientificname');
            $add->common               = Input::get('common');
            $add->domain_id            = Input::get('domain');
            $add->kingdom_id           = Input::get('kingdom');
            $add->subkingdom_id        = Input::get('subkingdom');
            $add->phylum_id            = Input::get('phylum');
            $add->subphylum_id         = Input::get('subphylum');
            $add->infraphylum_id       = Input::get('infraphylum');
            $add->kelas_id             = Input::get('kelas');
            $add->order_id             = Input::get('orders');
            $add->family_id            = Input::get('family');
            $add->genus_id             = Input::get('genus');
            $add->image                = $path;
        
            $add->save();

            Session::flash('message', 'Successfully created data!');

            return Redirect::to('herb_create');        
        }
        }
    }
    
    public function herb_show($id)
    {
        $species = \DB::table(\DB::raw("species a join phylum b on a.phylum_id = b.id 
            join orders c on a.order_id = c.id
            join kelas d on a.kelas_id = d.id
            join family e on a.family_id = e.id
            join genus f on a.genus_id = f.id
            join subphylum g on a.subphylum_id = g.id
            join infraphylum h on a.infraphylum_id = h.id
            join domain i on a.domain_id = i.id
            join kingdom j on a.kingdom_id = j.id
            join subkingdom k on a.subkingdom_id = k.id
            "))        

        ->whereRaw("a.id = $id")->first();

        return View::make('herb_show',array('species' => $species));
    }

    public function herb_edit($id)
    {

       $species = \DB::table(\DB::raw("species a join phylum b on a.phylum_id = b.id 
        join orders c on a.order_id = c.id
        join kelas d on a.kelas_id = d.id
        join family e on a.family_id = e.id
        join genus f on a.genus_id = f.id
        join subphylum g on a.subphylum_id = g.id
        join infraphylum h on a.infraphylum_id = h.id
        join domain i on a.domain_id = i.id
        join kingdom j on a.kingdom_id = j.id
        join subkingdom k on a.subkingdom_id = k.id
        "))
       ->selectRaw('a.id,a.scientificname,a.common,a.domain_id,a.order_id,a.kelas_id,a.family_id,a.genus_id,a.subphylum_id,a.phylum_id,a.infraphylum_id,a.kingdom_id,a.subkingdom_id')
       ->whereRaw("a.id = $id")->first();

       $phylum = Phylum::select('id','phylum_name')->get();
       $orders = Orders::select('id', 'order_name')->get();
       $kelas = Kelas::select('id', 'class_name')->get();
       $family = Family::select('id', 'family_name')->get();
       $genus = Genus::select('id', 'genus_name')->get();
       $subphylum = Subphylum::select('id', 'subphylum_name')->get();
       $infraphylum = Infraphylum::select('id', 'infraphylum_name')->get();
       $kingdom = Kingdom::selectRaw("id,kingdom_name")->get();
       $subkingdom = Subkingdom::selectRaw("id,subkingdom_name")->get();
       $domain = Domain::selectRaw("id,domain_name")->get();

       return View::make('herb_edit',array('species' => $species, 
        'phylum'=>$phylum, 
        'orders'=>$orders, 
        'kelas'=>$kelas,
        'family'=>$family,
        'genus'=>$genus,
        'subphylum'=>$subphylum,
        'infraphylum'=>$infraphylum,
        'kingdom'=> $kingdom,
        'subkingdom'=> $subkingdom,
        'domain'=> $domain
        ));
   }
    
    public function herb_edit_process($id)
    {
        $rules_edit = array(
            'scientificname'       => 'required', 
            'common'               => 'required',
            // 'domain_id'            => 'required', 
            // 'kingdom_id'           => 'required',
            // 'subkingdom_id'        => 'required',
            // 'phylum_id'            => 'required',
            // 'subphylum_id'         => 'required',
            // 'infraphylum_id'       => 'required',
            // 'kelas_id'             => 'required',
            // 'order_id'             => 'required',
            // 'family_id'            => 'required',
            // 'genus_id'             => 'required',
            'image'                => 'required'
            );

        $file = array('image' => Input::file('image'));
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);

        $validator = Validator::make(Input::all(),$rules_edit);
        
        if($validator->fails())
        {
            $messages = $validator->messages();

            return Redirect::to('herb_edit/'.$id)
                ->withErrors($validator);
        }
        else
        {
            if (Input::file('image')->isValid()) {
                $destinationPath = '../upload/'; // upload path
                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image

                $path = Input::file('image')->move($destinationPath, $fileName); // uploading file to given path

            $edit                    = Species::find($id);
            $edit->scientificname    = Input::get('scientificname');
            $edit->common            = Input::get('common');
            $edit->domain_id         = Input::get('domain');
            $edit->kingdom_id        = Input::get('kingdom');
            $edit->subkingdom_id     = Input::get('subkingdom');
            $edit->phylum_id         = Input::get('phylum');
            $edit->subphylum_id      = Input::get('subphylum');
            $edit->infraphylum_id    = Input::get('infraphylum');
            $edit->kelas_id          = Input::get('kelas');
            $edit->order_id          = Input::get('order');
            $edit->family_id         = Input::get('family');
            $edit->genus_id          = Input::get('genus');
            $edit->image             = $path;
            $edit->save();

            Session::flash('message', 'Succesfully updated data!');
            return Redirect::to('herb_edit/'.$id);
        }
        }
    }

    public function herb_delete($id)
    {
        $species = Species::where('id',$id) ->delete();

        Session::flash('message', 'Succesfully deleted data!');
        return Redirect::to('herb_index');
    }

    public function cetak_herbs()
    {

        $btn_pdf = Input::get('btnpdf');
        $btn_xls = Input::get('btnxls');

        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();
        $filename = "report";
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

    public function species_print($id)
    {

        $flag = "pdf";
        

        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();
        $filename = "report_species";
        $reportPath = "/views/laporan";

        $resource_path = $reportPath . "/" . "$filename.jasper";
        $public_path = "laporan/$filename";
        $pdf = "laporan/$filename.pdf";
        
        

        \JasperPHP::process(
            resource_path(). $resource_path,
            public_path()."/". $public_path,
            array("pdf", "xls"),
            array('idS'=>$id),
            $database
            )->execute();

            return \View::make('report_index',
            array('report_title'=> 'LAPORAN SENARAI EDIT','pdf' => $pdf , 'flag' => $flag));
    }
}
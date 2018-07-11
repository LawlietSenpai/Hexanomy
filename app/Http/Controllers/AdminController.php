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
use App\User;
use JasperPHP\JasperPHP;
use Response;
use Charts;
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


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function home()
    {
        // $chart = Charts::database(species::all(), 'bar', 'highcharts')
        //     ->title('Species')
        //     ->elementLabel("Total")
        //     ->responsive(false)
        //     ->groupBy('common')
        //     ->colors(['#90ED7D', '#7CB5EC']);

        $chart = Charts::database(genus::all(), 'pie', 'highcharts')
            ->title('GENUS')
            ->elementLabel("Total")
            ->responsive(false)
            ->groupBy('genus_name');

        $chart2 = Charts::database(species::all(), 'pie', 'highcharts')
            ->title('SPECIES')
            ->elementLabel("Total")
            ->responsive(false)
            ->groupBy('common');

        $chart3 = Charts::database(orders::all(), 'pie', 'highcharts')
            ->title('ORDERS')
            ->elementLabel("Total")
            ->responsive(false)
            ->groupBy('order_name');

        $chart4 = Charts::database(family::all(), 'pie', 'highcharts')
            ->title('FAMILY')
            ->elementLabel("Total")
            ->responsive(false)
            ->groupBy('family_name');

        $count = \DB::table(\DB::raw('species a left join phylum b on a.phylum_id = b.id'))
        ->selectRaw("count(a.id) as count_species, count(b.id) as count_phylum")->first();
// dd($count);
        return view('home', ['chart' => $chart, 'chart2' => $chart2, 'chart3' => $chart3, 'chart4' => $chart4, 'count'=>$count]);
        // return View::make('home');
    }
    
    public function admin_index()
    {
        $search = \Request::get('search'); //<-- we use global request to get the param of URI
 
        $users = User::where('name','like','%'.$search.'%')
        ->orderBy('name')
        ->paginate(20);

        return View('admin_index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function admin_create()
    {
        return View::make('admin_create');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function admin_show($id)
    {
        $users = User::find($id);

        return View::make('admin_show',array('users' => $users));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function admin_delete($id)
    {
        $users = User::where('id',$id) ->delete();

        Session::flash('message', 'Succesfully delete admin!');
        return Redirect::to('admin_index');
    }

    public function search_data()
    {
        $search = Input::get('search');

        $sql2 = User::select('id','name','phone')
        ->where('name', '=', $search)
        ->get();
        return View::make('searchuser', array('sql2' => $sql2));
    }

    public function cetak_laporanUser()
    {

        $btn_pdf = Input::get('btnpdf');
        $btn_xls = Input::get('btnxls');

        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();
        $filename = "user";
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

    public function cetak_singleUser($id)
    {                                                   
        // dd($id);
        $database = \Config::get('database.connections.generic');
        $jasper = new JasperPHP();

        $filename = "specificUser";
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

    public function sorting_user()
    {
        $sort = Input::get('sortuser');
        $users = User::select('id','name','email')
        ->orderBy($sort)
        ->get();
        return View::make('admin_index', array('users' => $users));
    }   
}

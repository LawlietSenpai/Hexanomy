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
use Charts;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');        
    }

    public function home_client()
    {
        return view('home_client');
    }

    public function user_view()
    {
        return view('user_view');
    }    

}

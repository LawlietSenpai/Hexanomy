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

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Responsews
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    // public function information_index()
    // {
    //     $animal = Animal::all();
    //     $plant = Plant::all();
    //     return View::make('information_index', array('animal' => $animal), array('plant' => $plant));
    // }

    public function user_view()
    {
        $species = Species::all();
        return View::make('user_view', array('species' => $species));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function user_viewinfo($id)
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

        return View::make('user_viewinfo',array('species' => $species));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

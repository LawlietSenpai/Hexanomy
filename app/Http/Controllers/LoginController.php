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
use App\Model\Admin;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
    public function login_initial()
    {
        return View::make('login_initial');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login_initial_process()
    {
        $rules = array(
            'firstname'        => 'required', //wajib diisi
            'lastname'         => 'required',
            'email'            => 'required|email|unique:admin', //wajib diisi|name|x sama dgn yg lain|nama table
            'password'         => 'required',
            'password_confirm' => 'required|same:password'
            );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->messages();

            return Redirect::to('login_initial')
            ->withErrors($validator)
            ->withInput(Input::except('password', 'password_confirm'));
        }
        else
        {
            $add = new Admin;
            $add->firstname    = Input::get('firstname');
            $add->lastname     = Input::get('lastname');
            $add->email        = Input::get('email');
            $add->password     = Hash::make(Input::get('password'));

            $add->save();

            Session::flash('message', 'Successful created admin!');
            return Redirect::to('login_initial');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
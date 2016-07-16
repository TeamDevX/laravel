<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;


class Front extends Controller
{
    //

    public function login() {
    	return view('login', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));

}
    public function register() {

    if (Request::isMethod('post')) {
        User::create([
                    'name' => Request::get('name'),
                    'email' => Request::get('email'),
                    'password' => bcrypt(Request::get('password')),
        ]);
    } 
    
    return Redirect::away('login');
}


public function authenticate() {
    if (Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password')])) {
        return redirect()->intended('myaccount');
        //return view('myaccount', array('title' => 'Srinu', 'description' => '', 'page' => 'home'));

    } else {
        return view('login', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }
}

public function logout() {
    Auth::logout();
    
    return Redirect::away('login');
}

public function myaccount() {
    $id = Auth::user()->id;
	$currentuser = User::find($id);
	$data = array('title' => $currentuser->name);  
   	return view('myaccount')->with($data);
}

}

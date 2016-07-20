<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;


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
public function profile_form() {
    $id = Auth::user()->id;    
	$currentuser = User::find($id);
	$data = array('title' => $currentuser->name);        
   	return view('profile')->with($data);
}
public function edit_profile() {
    $id = Auth::user()->id;
    $user = User::findOrFail($id);

    $user->name = Request::get('name');

    $user->password = bcrypt(Request::get('password'));
    
    $user->save();
    $data = array('title' => $user->name, 'message'=>'User has been updated!'); 
    return view('welcome', $data);
	  
   	
}

//////////////////////////////////////// API /////////////////////////////

public function apilogin() {
	if (Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password')])) {
		return Response::json(compact('token'));	
    } else {
        return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
    }
}
public function apiregister() {	
    try {
        $user = User::create(['name' => Request::get('name'),'email' => Request::get('email'), 'password' => bcrypt(Request::get('password'))]);
				if ($token = Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password')])) {
				return Response::json(compact('token'));	
			} else {
				return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
			}

		} catch (Exception $e) {
			return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
    }
}

public function apicsrf() {
	return Response::json(csrf_token());
}

public function apilogout() {	
	return Response::json(Auth::logout());
}

public function apiuserstatus() {
   	return Response::json(Auth::check());
}



public function apigetapks(){
	    $id = Auth::user()->id;
	$currentuser = User::find($id);
	$data = array('title' => $currentuser->name);  

	return Response::json($data);
}

}

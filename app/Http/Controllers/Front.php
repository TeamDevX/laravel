<?php

namespace App\Http\Controllers;
use App\Classes\ProcessAPK;
use App\User;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;
use session;


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
		if(User::where('email', '=', Request::get('email'))->exists()) {
			return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
		}else{
			$user = User::create(['name' => Request::get('name'),'email' => Request::get('email'), 'password' => bcrypt(Request::get('password'))]);
			if($token = Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password')])) {				
				return Response::json(compact('token'), HttpResponse::HTTP_OK);	
			}else{
				return Response::json(['error' => 'Your token is not valid.'], HttpResponse::HTTP_CONFLICT);
			}
		}
	}catch (Exception $e) {
		return $e->getMessage();
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
public function apiUserEmailCheck() {
   	if(User::where('email', '=', Request::get('email'))->exists()) {
		return Response::json(false, HttpResponse::HTTP_CONFLICT);
	}else{
		return Response::json(true, HttpResponse::HTTP_OK);
	}
}



public function apigetapks(){
	$id = Auth::user()->id;
	if($id){
		$apks_list = DB::table('apk')->where('userid', '=', $id)->get();
	}
	return Response::json($apks_list);
}
public function apimyaccount() {
	return Response::json(csrf_token());
}

public function apkUpload(){
	$id = Auth::user()->id;
	if($id){
		$filename = $_FILES["files"]["name"][0];
		$filetype = $_FILES["files"]["type"][0];
		$filesize = $_FILES["files"]["size"][0];
		$filetmpname = $_FILES["files"]["tmp_name"][0];
		$target_dir = "/var/www/vhosts/adsinapk.com/httpdocs/dev/uploads/";	
		$imageFileType = pathinfo(basename($filename),PATHINFO_EXTENSION);
		$status_message = '';
		if($imageFileType != 'apk'){			
			$status_message .= "Sorry, only APK files are allowed.";					
		}else if($filesize > 150000000) {
			$status_message .= "Sorry, your file is too large.";				
		}else{			
			$apk_success_id = DB::table('apk')->insertGetId(['userid' => $id, 'file' => basename($filename, ".apk"), 'type' => $filetype, 'size' => $filesize, 'created_date' => date('Y-m-d h:m:s')]);	
			if($apk_success_id){
				if (!is_dir($target_dir.$id)) {					
					if(!mkdir($target_dir.$id, 0777, true)) {					
					$status_message .= "Failed to create directory with userid.";
					}								
					$apk_dir = $target_dir.$id.'/'.$apk_success_id.'.'.$imageFileType;
				}else{
					$apk_dir = $target_dir.$id.'/'.$apk_success_id.'.'.$imageFileType;
				}
				$uploaded = move_uploaded_file($filetmpname, $apk_dir);
				if($uploaded){
					$processAPKClass = new ProcessAPK();
					$apk_response = $processAPKClass->processApk($apk_dir,$apk_success_id,$id);
					$status_message .= "The file ". basename( $filename). " has been uploaded.";					
					return Response::json(['status'=> $status_message, 'apkid'=> $apk_success_id, 'apk_response'=> $apk_response]);
				}else{
					$status_message .= "Sorry, The file ". basename( $filename). " has not been uploaded.";
				}
			}else{
				$status_message .= "Sorry, there was an error inserting into database.";
			}		 
		}	
	    return Response::json(['status'=> $status_message]);
	}else{
		return Response::json(['error' => 'User is not authorized.'], HttpResponse::HTTP_UNAUTHORIZED);
	}
}

}

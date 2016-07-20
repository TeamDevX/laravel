<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
    

});

// Authentication routes...
Route::get('auth/login', 'Front@login');
Route::post('auth/login', 'Front@authenticate');
Route::get('auth/logout', 'Front@logout');

// Registration routes...
Route::post('/register', 'Front@register');

Route::get('/checkout', [
    'middleware' => 'auth',
    'uses' => 'Front@checkout'
]);

Route::get('/myaccount', [
    'middleware' => 'auth',
    'uses' => 'Front@myaccount'
]);
Route::get('/myaccount/edit', [
    'middleware' => 'auth',
    'uses' => 'Front@profile_form'
]);
Route::post('/myaccount', [
    'middleware' => 'auth',
    'uses' => 'Front@edit_profile'
]);

//////////////////////////////////

Route::get('/v1/login', 'Front@apilogin');
Route::get('/v1/register', 'Front@apiregister');
Route::get('/v1/csrf', 'Front@apicsrf');

Route::post('/v1/login', 'Front@apilogin');
Route::post('/v1/register', 'Front@apiregister');


<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'api'], function(){

  // login and signin user
  Route::post('login', 'API\UserController@login');
  Route::post('register', 'API\UserController@register');

  // all routes must have token
  Route::group(['middleware' => 'auth'], function(){

    //users
    Route::post('/user', 'API\UserController@show');

    //events


  });

});

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

  // Login and signin user
  Route::post('login', 'API\UserController@login');
  Route::post('register', 'API\UserController@register');

  
  Route::get('events/titles', 'API\EventController@titleOptions');
  Route::post('events', 'API\EventController@createEvent');



  // All routes that must have auth token
  Route::group(['middleware' => 'auth:api'], function(){

    // Users
    Route::post('/user', 'API\UserController@show');

    // Events

    Route::prefix('events')->group(function () {

      // Event helpers
      Route::post('/images', 'API\EventController@fetchDefaultImages');

      Route::post('/update', 'API\EventController@startUpdateEvent');
      Route::post('/upload/event-image', 'API\EventController@uploadEventImage');
      Route::get('/{id}','API\EventController@getEvent');

    });

  });

});

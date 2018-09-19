<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

  // Health check
  Route::get('/health', 'API\HealthCheckController@healthCheck');

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

      Route::get('/{id}/supply','API\EventController@getEventSupply');
      // Event helpers
      Route::post('/images', 'API\EventController@fetchDefaultImages');
      
      Route::post('/update', 'API\EventController@startUpdateEvent');
      Route::post('/upload/event-image', 'API\EventController@uploadEventImage');
      Route::get('/user','API\EventController@getEvents');

      Route::get('/{id}/notifications','API\EventController@getEventNotifications');
      Route::get('/{id}','API\EventController@getEvent');


    });

  });

});

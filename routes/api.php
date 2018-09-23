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

  // Regular login and sign in user
  Route::post('login', 'API\UserController@login');
  Route::post('register', 'API\UserController@register');

  // Google and Facebook login
  Route::post('auth/social', 'Auth\LoginController@socialSignIn');

  Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
  Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

  // Start app routes
  Route::get('events/titles', 'API\EventController@titleOptions');
  Route::post('events', 'API\EventController@createEvent');

  // All routes that must have auth token
  Route::group(['middleware' => 'auth:api'], function(){

    // Users
    Route::post('/user', 'API\UserController@show');

    // Events
    Route::prefix('events')->group(function () {

      Route::get('/apps','API\EventController@getApps');
      Route::post('/images', 'API\EventController@fetchDefaultImages');
      Route::post('/update', 'API\EventController@startUpdateEvent');
      Route::post('{event_id}/upload/event-image', 'API\EventController@uploadEventImage');
      Route::get('/user','API\EventController@getEvents');


      Route::prefix('{event_id}')->group(function () {


        Route::get('/','API\EventController@getEvent');

        Route::get('/apps','API\EventController@getEventApps');
        
        
        Route::get('/friends','API\EventFriendsController@getEventFriends');
        
        
        Route::get('/notifications','API\EventController@getEventNotifications');
        Route::get('/supply','API\EventController@getEventSupply');

      });

    });

  });

});

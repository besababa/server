<?php

Route::group(['middleware' => 'api'], function(){

  // Health check
  Route::get('/health', 'API\HealthCheckController@healthCheck');

  // Regular login and sign in user
  Route::post('login', 'API\UserController@login');
  Route::post('register', 'API\UserController@register');

  // Google and Facebook login
  Route::post('auth/social', 'Auth\LoginController@socialSignIn');

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
      Route::post('/upload/event-image', 'API\EventController@eventImageToCarousel');
      Route::post('/update/event-image/{event_id}', 'API\EventController@updateEventImage');
      Route::post('/update', 'API\EventController@startUpdateEvent');

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

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller {

  public function titleOptions(Request $request) {
      // TODO:
      return response()->json('titleOptions', 200);
  }

  public function fetchDefaultImages(Request $request) {
      // TODO:
      return response()->json('fetchDefaultImages', 200);
  }

  public function createEvent(Request $request) {
      // TODO:
    return response()->json('createEvent', 200);
  }

  public function startUpdateEvent(Request $request) {
      // TODO:
      return response()->json('startUpdateEvent', 200);
  }

  public function uploadEventImage(Request $request) {
      // TODO:
      return response()->json('uploadEventImage', 200);
  }

  public function getEvent(Request $request) {
      // TODO:
      return response()->json('getEvent', 200);
  }

}

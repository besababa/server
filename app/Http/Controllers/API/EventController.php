<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EventController extends Controller {

  public function titleOptions(Request $request) {
      $now = \Carbon\Carbon::now()->format('dm');
      $title = DB::table('on_this_day')->where('current_day',$now)
      ->where('status',1)->value('title');
      return response()->json(['titles' => [$title]], 200);
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

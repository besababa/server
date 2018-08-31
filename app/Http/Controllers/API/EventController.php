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
      $images = [];
      $request = $request->only('title');
      $title = $request['title'];
      $giphy = new \rfreebern\Giphy(env('GPH'));
      $results = $giphy->search($title, $limit = 5);

      if($results->data){
        foreach ($results->data as $gif) {
            $images[] = [
              'url' => $gif->images->fixed_height->url
            ];
        }
      }

      return response()->json(['images' => $images], 200);
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

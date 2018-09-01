<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

use App\Models\Event;
use App\Models\User;


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

      $validator = Validator::make($request->all(), [
          'title' => 'required|max:255',
      ]);

      if ($validator->fails()) {
          return response()->json($validator->errors());
      }

      $user = auth()->guard('api')->user();

      if(!$user){

          $time = strtotime('now').rand(111,999999);
          $name = 'anonymous.'.$time;
          $user = User::create([
              'name'=>$name,
              'email'=>$name."@besababa.com",
              'password'=>bcrypt($name),
              'status'=>0
          ]);
      }

      $data = $request->only('title');

      $token = $user->createToken('auth')->accessToken;

      $event = Event::create([
          'title'=>$data['title'],
          'user_id'=>$user->id
      ]);

      return response()->json(['event'=>$event,'token'=>$token],200);
  }

  public function startUpdateEvent(Request $request) {

      $data = $request->only(['id','title','image','start_date','description']);

      $roles = [
          'id' => 'required|alpha_num',
          'title' => 'required|max:255',
      ];

      if(!empty($data['image'])){
          $roles['image'] = 'string|min:5|max:255';
      }
      if(!empty($data['start_date'])){
          $roles['start_date'] = 'date:';
      }
      if(!empty($data['description'])){
          $roles['description'] = 'string';
      }

      $validator = Validator::make($data, $roles);

      if ($validator->fails()) {
          return response()->json($validator->errors(),400);
      }

      $user = auth()->guard('api')->user();

      if(!$user){
          return response()->json(['error'=>'Invalid token'], 401);
      }

      $event = Event::where('id',$data['id'])->where('user_id',$user->id)->first();

      if(!$event){
          return response()->json(['error'=>'Event not found'], 404);
      }

      $result = $event->update($data);

      if(!$result){
          return response()->json(['error'=>' Internal Server Error'], 500);
      }

      $token =  $user->createToken('auth')->accessToken;

      return response()->json(['event'=>$event,'token'=>$token], 200);
  }

  public function uploadEventImage(Request $request) {
      // TODO:
      return response()->json('uploadEventImage', 200);
  }

  public function getEvent($id) {

      $user = auth()->guard('api')->user();

      $event = Event::where('id',$id)->where('user_id',$user->id)->first();

      if(!$event){
          return response()->json(['error'=>'Event not found'], 404);
      }
      return response()->json(compact('event'), 200);
  }

}

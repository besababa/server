<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;

use App\Models\Event;
use App\Models\EventFriend;
use App\Models\User;


class EventController extends Controller {

    public function titleOptions() {
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

      EventFriend::create([
          'user_id'=>$user->id,
          'event_id'=>$event->id,
          'permission'=>1,
          'status'=>1
      ]);

      return response()->json(['event'=>$event,'token'=>$token],200);
    }

    public function eventImageToCarousel(Request $request) {

        $validator = Validator::make($request->only(['eventImage']), [
            'eventImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $file = Storage::disk('spaces')
            ->putFile('temp', $request->file('eventImage'), 'public');

        $url = env('SPACES_ORIGIN_URL') .$file;
        return response()->json(['url' => $url], 200);
    }

    public function updateEventImage(Request $request,$event_id) {

        $validator = Validator::make($request->only(['eventImage']), [
            'eventImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $user = auth()->guard('api')->user();
        $event = Event::where('id',$event_id)->where('user_id',$user->id)->first();
        if(!$event){
            return response()->json(['message' => 'Forbidden' ], 403);
        }
        
        $file = Storage::disk('spaces')
            ->putFile('event-image', $request->file('eventImage'), 'public');

        $newEventImage = env('SPACES_ORIGIN_URL') .$file;

        $oldEventImage = str_replace(env('SPACES_ORIGIN_URL'),'',$event->image);

        Storage::disk('spaces')->delete($oldEventImage);

        $event->update(['image'=>$newEventImage]);

        return response()->json(['url' => $newEventImage], 200);

    }

    public function startUpdateEvent(Request $request) {

      $data = $request->only(['id','title','image','start_date','description']);

      $roles = [
          'id' => 'required|alpha_num',
          'title' => 'required|max:255',
      ];

      if(!empty($data['image'])){
          $roles['image'] = 'string|min:5|max:255';

          // user upload image from device
          $userUploadedImage = (strpos($data['image'], env('SPACES_ORIGIN_URL').'temp') !== false) ? true : false;
      }

      if(!empty($data['start_date'])){
          $data['start_date'] = date("Y-m-d H:i:s", strtotime($data['start_date']));
          $roles['start_date'] = 'date';
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

      if(!empty($userUploadedImage)) {

          $oldImage = str_replace(env('SPACES_ORIGIN_URL'),'',$data['image']);
          $newImage = str_replace('temp','/event-image',$oldImage);

          $exists = Storage::disk('spaces')->exists($oldImage);

          if(!$exists){
              return response()->json(
                  ['error'=>"Image doesn't exists please upload a new one again"], 422);
          }

          $response = Storage::disk('spaces')->move($oldImage, $newImage);

          if(!$response){
              return response()->json(
                  ['error'=>'User image not properly saved please upload a new one again'], 422);
          }

          $data['image'] = env('SPACES_ORIGIN_URL') . ltrim($newImage, '/');
      }

      $result = $event->update($data);

      if(!$result){
          return response()->json(['error'=>' Internal Server Error'], 500);
      }

      return response()->json($event, 200);
    }

    public function getEvent($id) {

      $user = auth()->guard('api')->user();

      $event = Event::where('id',$id)->where('user_id',$user->id)->first();

      if(!$event){
          return response()->json(['error'=>'Event not found'], 404);
      }

      $event->start_date = (!empty( $event->start_date))?date("d/m/Y", strtotime($event->start_date)):null;

      return response()->json($event, 200);
    }

    public function getEvents(){

        $user = auth()->guard('api')->user();

        $events = EventFriend::select('events.id','events.title','events.image','events.start_date','events.end_date','events.description')
            ->join('events','events.id','=','event_friends.event_id')
            ->where('event_friends.user_id',$user->id)
            ->whereNotNull('events.title')
            ->whereNotNull('events.image')
            ->orderBy('events.id','desc')
            ->get();

        $events = ($events)?$events->toArray():[];

        return response()->json($events, 200);
    }

    public function getEventApps($event_id){

        $apps = [
            ['title'=>'Albums','image'=>'http://www.jmkxyy.com/icon-gallery/icon-gallery-13.jpg'],
            ['title'=>'Games','image'=>'https://aarp.cdn.arkadiumhosted.com/4.0-aarp/Content/Images/default/600x600_gameicon.jpg']
        ];
        return response()->json($apps, 200);
    }

    public function getApps(){

        $apps = [
            ['title'=>'Albums','image'=>'http://www.jmkxyy.com/icon-gallery/icon-gallery-13.jpg'],
            ['title'=>'Games','image'=>'https://aarp.cdn.arkadiumhosted.com/4.0-aarp/Content/Images/default/600x600_gameicon.jpg']
        ];

        return response()->json($apps, 200);

    }

    public function getEventSupply($id){
        return response()->json([], 200);
    }

    public function getEventNotifications(){
        return response()->json([], 200);
    }

}

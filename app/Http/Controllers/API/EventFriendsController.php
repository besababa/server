<?php

namespace App\Http\Controllers\API;

use App\EventFriends;
use App\Http\Controllers\Controller;
use App\Models\EventFriend;
use Illuminate\Http\Request;

class EventFriendsController extends Controller
{

    
    public function getEventFriends($event_id){

        $user = auth()->guard('api')->user();

        $eventFriend = EventFriend::where('id',$event_id)
            ->where('user_id',$user->id)
            ->where('status',1)->first();

        if(!$eventFriend){
            return response()->json(['error'=>'Invalid token'], 401);
        }
        $friends = EventFriend::join('users','users.id','=','event_friends.user_id')
            ->where('event_friends.event_id','=',$event_id)
            ->where('event_friends.user_id','!=',$event_id)
            ->get();

        $friends = ($friends)?$friends->toArray():[];

        return response()->json($friends, 200);

    }
}

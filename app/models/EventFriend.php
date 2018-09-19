<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFriend extends Model
{
    protected $fillable = ['id','user_id','event_id','permission','status'];


    public function events()
    {
        return $this->hasMany('App\Model\Events');
    }

}

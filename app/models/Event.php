<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = ['id','user_id','published','image','title', 'start_date', 'end_date','description'];


    public function friends()
    {
        return $this->belongsToMany('App\Model\EventFriend');
    }
}

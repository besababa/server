<?php

namespace App;

use Zizaco\Entrust\EntrustRole;
use App\RoleUser;
class Role extends EntrustRole
{

    protected $fillable = ['name','display_name','description'];
    protected $hidden = array( 'created_at','updated_at');

    public $timestamps=false;

    public function roleUser(){

        return $this->hasOne('App\RoleUser');
    }
}

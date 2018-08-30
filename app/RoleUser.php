<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RoleUser extends Model {

    protected $fillable = ['user_id','role.id'];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public static function createRoleUser(User $user,$role_ids){

        $role_ids = explode(',',$role_ids);

        $roles = Role::whereIn('id', $role_ids)->get();

        foreach ($roles as $role) {
            $user->attachRole($role);
        }

    }
}

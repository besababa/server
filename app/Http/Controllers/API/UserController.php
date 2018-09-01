<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller {

    /**
     * login a user
     * @return \Illuminate\Http\Response
    */
    public function login(){

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

            $user = Auth::user();
            $token =  $user->createToken('auth')->accessToken;

            return response()->json(['token' => $token], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Register a new user
     * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'passwordCopy' => 'required|same:password',
        ]);

        if ($validator->fails())
            return response()->json(['error'=>$validator->errors()], 401);

        $input = $request->only(['name','email','password']);

        $user = User::where('email',$input['email'])->first();

        if($user ){

            return response()->json(['error'=>'User already exist'], 401);
        }

        $input['password'] = bcrypt($input['password']);

        $user = auth()->guard('api')->user();

        // if the user is anonymous or he is already active create new user
        // we don't want to update user that existing and active
        if(!$user || $user->status){
            $user = new User();
        }

        $user->email = $input['email'];
        $user->name = $input['name'];
        $user->password = $input['password'];
        $user->save();

        $success['token'] =  $user->createToken('auth')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], 200);
    }

    /**
     * show user details
     * @return \Illuminate\Http\Response
    */
    public function show()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use GuzzleHttp\Psr7\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Http\Request;
use Validator;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {}



    public function socialSignIn(Request $request){


        $params = $request->only(['email','id','image','name','provider','token']);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'token' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['error'=>$validator->errors()], 401);

        $input = $request->only(['name','email','password']);

        $user = User::where('email',$input['email'])->first();

        $providerIdField = $params['provider'].'_id';

        if(!$user ){
            $user = new User();
            $user->email = $params['email'];
            $user->name = $params['name'];
            $user->name = $params['name'];
            $user->$providerIdField = $params['id'];
            $user->remember_token = $params['token'];
            $user->pic_path = $params['image'];
            $user->status = 1;
            $user->save();
        }

        
        $success['token'] =  $user->createToken('auth')->accessToken;
        $success['name'] =  $user->name;
        $success['image'] =  $user->pic_path;
        $success['email'] =  $user->email;

        return response()->json($success, 200);

    }
    /**
     * Redirect the user to the provider authentication page.
     * @var string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from provider.
     * @var string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        // $user->token;

        return response()->json($user, 200);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebookProvider()
    {
      return Socialite::driver('facebook')->scopes(["manage_pages", "publish_pages", "pages_show_list"])->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderFacebookCallback()
    {

        $auth_user = Socialite::driver('facebook')->user();

        //check if user exists and log the user in
        $user = User::where('email', $auth_user->email)->first();
        if($user){
          if(Auth::loginUsingId($user->id)){
            return redirect()->to('home');
          }
        }

        // else sign the user up
        $userSignup = User::updateOrCreate([
          'name' => $auth_user->user['name'],
          'email' => $auth_user->email,
          // 'gender' => $auth_user->user['gender'],
          'password' => bcrypt('1234'),
          'token' => $auth_user->token
        ]);

        // log the user in
        if($userSignup){
          if(Auth::loginUsingId($user->id)){
            return redirect()->to('home');
          }
        }
    }
}
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
      return Socialite::driver('facebook')->scopes(["manage_pages", "user_posts", "pages_show_list, user_birthday, user_gender, user_link"])->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderFacebookCallback()
    {
      $auth_user = Socialite::driver('facebook')->user();
      $user = User::where('email', $auth_user->email)->first();

      if(is_null($user)){ //user does not exist, register user in db
        $userSignup = User::updateOrCreate([
          'name' => $auth_user->user['name'],
          'email' => $auth_user->email,
          'password' => bcrypt('1234'),
          'token' => $auth_user->token
        ]);

        // log the user in
        if($userSignup){
          $user = User::where('email', $auth_user->email)->first();
          if(Auth::loginUsingId($user->id)){
            return redirect()->to('app');
          }
        }
      } else { //user exists, log them in
        if($user){
          if(Auth::loginUsingId($user->id)){
            return redirect()->to('app');
          }
        }
      }
    }
}
<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback');

Route::group(['middleware' => [
    'auth'
]], function(){
    Route::get('/user', 'GraphController@retrieveUserProfile');
});

Route::get('/auth', function () {
    //Perform a check whether the user is authenticated or not
    if(!Auth::check())
    {
      //If they are not, forcefully login the user with id=1
      $user = App\User::find(1);
      Auth::login($user);
    }
    return Auth::user();
});

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['middleware' => 'auth'], function() {
    Route::post('actions/set', 'ActionController@setAction');
    Route::get('action-status', 'ActionController@getPokemonActionStatus');
    Route::get('likes', 'ActionController@getLikes');
    Route::get('hates', 'ActionController@getHates');
    Route::get('favorites', 'ActionController@getFavorites');
    Route::get('other-users', 'UserController@getAllOtherUsers');
    Route::get('user-profile', 'UserController@getUserProfile');
    Route::post('update-profile', 'UserController@setUserProfile');
});
Route::get('likes/{id}', 'ActionController@getLikesByUserId');
Route::get('hates/{id}', 'ActionController@getHatesByUserId');
Route::get('users', 'ActionController@getAllUsers');


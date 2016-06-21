<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::get('/', ['as' => 'guest', 'uses' => 'GuestController@index']);

Route::post('guest/ajax/setstatus', ['as' => 'set_guest_status', 'uses' => 'GuestController@setStatus']);

Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::get('/authentication', ['as' => 'authentication', 'uses' => 'LoginController@authentication']);
Route::get('/login', ['as' => 'login', 'uses' => 'LoginController@login']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

Route::get('/test', ['as' => 'test', 'uses' => 'LoginController@test']);
Route::get('/friends', ['as' => 'friends', 'uses' => 'FriendsController@index']);

Route::get('/likes/ajax', ['as' => 'likes_ajax', 'uses' => 'LikesController@getAjaxLikes']);
Route::get('/likes', ['as' => 'likes', 'uses' => 'LikesController@show']);

Route::get('/audio/ajax', ['as' => 'audio_ajax', 'uses' => 'AudioController@getAjaxGenres']);
Route::get('/audio', ['as' => 'audio', 'uses' => 'AudioController@show']);
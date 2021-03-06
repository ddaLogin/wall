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

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/log-in', ['as' => 'login', 'uses' => 'HomeController@login']);
Route::get('/log-out', ['as' => 'logout', 'uses' => 'HomeController@logout']);
Route::get('/sign-up', ['as' => 'signup', 'uses' => 'HomeController@signup']);
Route::post('/authenticate', ['as' => 'authenticate', 'uses' => 'HomeController@authenticate']);
Route::post('/registration', ['as' => 'registration', 'uses' => 'HomeController@registration']);
Route::get('/search', ['as' => 'search', 'uses' => 'HomeController@search']);

Route::group(['middleware' => 'auth'], function (){
    Route::get('/post/create', ['as' => 'post.create', 'uses' => 'PostController@create']);
    Route::post('/post/store', ['as' => 'post.store', 'uses' => 'PostController@store']);
    Route::get('/post/edit/{post}', ['as' => 'post.edit', 'uses' => 'PostController@edit'])->where('post', '[0-9]+');
    Route::post('/post/update/{post}', ['as' => 'post.update', 'uses' => 'PostController@update'])->where('post', '[0-9]+');

    Route::post('/like/toggle', ['as' => 'like.toggle', 'uses' => 'LikeController@toggle']);

    Route::post('/subscription/toggle', ['as' => 'subscription.toggle', 'uses' => 'SubscriptionController@toggle']);

    Route::get('/{user}/subscriptions', ['as' => 'user.subscriptions', 'uses' => 'UserController@subscriptions']);
    Route::get('/settings', ['as' => 'user.settings', 'uses' => 'UserController@settings']);
    Route::post('/settings/change/mail', ['as' => 'user.settings.change.mail', 'uses' => 'UserController@changeMail']);
    Route::post('/settings/change/password', ['as' => 'user.settings.change.password', 'uses' => 'UserController@changePassword']);
    Route::get('/feed', ['as' => 'user.feed', 'uses' => 'UserController@feed']);
    Route::get('/notifications', ['as' => 'user.notifications', 'uses' => 'UserController@notifications']);

    Route::post('/file/avatar', ['as' => 'file.avatar', 'uses' => 'FileController@avatar']);

    Route::get('/room/create', ['as' => 'room.create', 'uses' => 'RoomController@create']);
    Route::get('/room/{room}', ['as' => 'room.join', 'uses' => 'RoomController@join']);
    Route::get('/room/{room}/invite/{user}', ['as' => 'room.invite', 'uses' => 'RoomController@invite']);
});

Route::get('/post/{post}', ['as' => 'post.show', 'uses' => 'PostController@show'])->where('post', '[0-9]+');
Route::get('/{nickname}', ['as' => 'user.wall', 'uses' => 'UserController@wall']);
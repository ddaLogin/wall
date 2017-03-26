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

Route::group(['middleware' => 'auth'], function (){
    Route::get('/post/create', ['as' => 'post.create', 'uses' => 'PostController@create']);
    Route::post('/post/store', ['as' => 'post.store', 'uses' => 'PostController@store']);
    Route::get('/post/edit/{post}', ['as' => 'post.edit', 'uses' => 'PostController@edit']);
    Route::post('/post/update/{post}', ['as' => 'post.update', 'uses' => 'PostController@update']);

});

Route::get('/{nickname}', ['as' => 'user.wall', 'uses' => 'UserController@wall']);
<?php


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


Route::group(['namespace'=>'Api'],function(){
    //注册登录
    Route::post('users/login','UserController@login')->name('login');
    Route::post('users/create','UserController@create')->name('register');
    //刷新token
    Route::post('refresh_token','UserController@refreshToken')->name('refresh.token');

    Route::post('users/show','UserController@show')->name('users.show');
    Route::post('users/update','UserController@update')->name('users.update');

});


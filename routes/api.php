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
    Route::post('users/login','UsersController@login')->name('login');
    Route::post('users/create','UsersController@create')->name('register');
    //刷新token
    Route::post('refresh_token','UsersController@refreshToken')->name('refresh.token');
    //查看和更新
    Route::resource('users','UsersController',['only'=>['show','update']]);

});


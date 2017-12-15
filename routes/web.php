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

Route::group(['middleware' => ['web', 'wechat.oauth', 'ws.oauth']], function () {

    Route::get('/home', 'Frontend\HomeController@show')->name('home');
    Route::get('/box/{box}', 'Frontend\BoxController@show')->name('box');
    Route::get('/showuploadimg', 'Frontend\BoxController@showuploadimg')->name('showuploadimg');
    Route::get('/showuploadvoice', 'Frontend\BoxController@showuploadvoice')->name('showuploadvoice');
    Route::get('/showuploadtext', 'Frontend\BoxController@showuploadtext')->name('showuploadtext');
    Route::get('/showuploadvideo', 'Frontend\BoxController@showuploadvideo')->name('showuploadvideo');
    Route::post('/uploadimg', 'Frontend\BoxController@uploadimg')->name('uploadimg');
    Route::get('/uploadvoice', 'Frontend\BoxController@uploadvoice')->name('uploadvoice');
    Route::post('/uploadtext', 'Frontend\BoxController@uploadtext')->name('uploadtext');
    Route::post('/uploadvideo', 'Frontend\BoxController@uploadvideo')->name('uploadvideo');

    // Route::get('/user', function () {
    //     $user = session('wechat.oauth_user'); // 拿到授权用户资料
    //
    //     dd($user);
    // });
});

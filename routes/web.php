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

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/home', 'Frontend\HomeController@show')->name('home');
    Route::get('/showuploadimg', 'Frontend\HomeController@showuploadimg')->name('showuploadimg');
    Route::get('/showuploadvoice', 'Frontend\HomeController@showuploadvoice')->name('showuploadvoice');
    Route::get('/showuploadtext', 'Frontend\HomeController@showuploadtext')->name('showuploadtext');
    Route::get('/showuploadvideo', 'Frontend\HomeController@showuploadvideo')->name('showuploadvideo');
    Route::get('/uploadimg', 'Frontend\HomeController@uploadimg')->name('uploadimg');
    Route::get('/uploadvoice', 'Frontend\HomeController@uploadvoice')->name('uploadvoice');
    Route::post('/uploadtext', 'Frontend\HomeController@uploadtext')->name('uploadtext');
    Route::post('/uploadvideo', 'Frontend\HomeController@uploadvideo')->name('uploadvideo');

    // Route::get('/user', function () {
    //     $user = session('wechat.oauth_user'); // 拿到授权用户资料
    //
    //     dd($user);
    // });
});

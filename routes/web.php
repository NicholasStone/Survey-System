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

Route::name('third-party:')
    ->namespace('Auth\ThirdParty')
    ->prefix('/oauth')
    ->group(function () {
    Route::get('/wechat', 'WeChatController@wechat')->name('wechat');
    Route::get('/wechat/callback', 'WeChatController@callback')->name('wechat.callback');
});
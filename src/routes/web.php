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
Auth::routes();

Route::get('/', 'InstaController@home');
Route::get('/home', 'InstaController@home');
Route::post('/home', 'InstaController@upload');
Route::post('/delete','InstaController@delete');
Route::post('/good','InstaController@good');
Route::get('/beforetweet', 'InstaController@beforetweet');

Route::post('/favoritesuser', 'InstaController@favoritesuser');
Route::post('/profile', 'InstaController@profile');//aタグはgetでしか渡せない?
Route::get('/login', 'InstaController@login');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

//Route::get('/home', 'HomeController@index')->name('home');//ver6.Xに従ってDLしたらなんか追加された

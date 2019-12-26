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

Route::get('/home', 'InstaController@home');
Route::post('/home', 'InstaController@upload');
Route::post('/delete','InstaController@delete');
Route::post('/good','InstaController@good');
Route::get('/beforetweet', 'InstaController@beforetweet');

Route::post('/favoritesuser', 'InstaController@favoritesuser');
Route::post('/profile', 'InstaController@profile');//aタグはgetでしか渡せない?
Route::get('/login', 'InstaController@login');
Route::get('/logout', 'Auth\LoginController@logout');

Route::post('login/github', 'Auth\LoginController@redirectToProvider');
Route::post('login/github/callback', 'Auth\LoginController@handleProviderCallback');

//Route::get('/', function () {return view('welcome');});
//Route::get('/user', 'UserController@index');
//Route::get('/takadakota', 'TakadaController@index');
//Route::get('/bbs', 'BbsController@index');
//Route::post('/bbs', 'BbsController@create');
//Route::get('github', 'Github\GithubController@top');
//Route::post('github/issue', 'Github\GithubController@createIssue');

//Route::post('user', 'User\UserController@updateUser');
//Route::get('/', 'HomeController@index');
//Route::post('/upload', 'HomeController@upload');

//Route::get('/home', 'HomeController@index')->name('home');//ver6.Xに従ってDLしたらなんか追加された

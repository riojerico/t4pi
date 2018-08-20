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

// Route::resource('api/items', 'ItemsController');
// Route::resource('api/participant', 'WinsController');
// //Route::resource('api/user/wins', 'UsersController');
// Route::get('api/user/wins/{api}', 'UsersController@wins');
// Route::get('api/user/wins/{wins}/{api}', 'UsersController@wins_detail');
//
// route::get('test', 'FunctionController@createID');
// route::get('test/{api}', 'FunctionController@key');
// Route::resource('user', 'UserAccountController');

// GENERAL
route::post('general/create-oten', 'FunctionController@create_otenuser');
route::put('general/update-oten/{pilihan}/{kodeORuname}', 'FunctionController@update_otenuser');
########-------------------------
// for MAP
route::get('user/{api}', 'UserAccountController@show');
route::post('user/{api}', 'UserAccountController@store');
route::get('wincheck/{wins}/{api}', 'WincheckController@show');

//See My Trees
route::post('smt/register', 'seemytreesController@Register');
route::get('smt/list-user', 'seemytreesController@ListUser');

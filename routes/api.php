<?php

use Illuminate\Http\Request;

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
Route::post('register',     'UserController@register');
Route::post('login',        'UserController@login');
Route::post('logout',       'UserController@logout');


Route::get('email/unique/{value}', 'UserController@check_email_unique');
Route::get('phone/unique/{value}', 'UserController@check_phone_unique');


// Route::group(['middleware' => 'jwt'], function () {

    Route::get('user/list',         'UserController@listUser');
    Route::post('user/add',         'UserController@add');
    Route::post('user/edit',        'UserController@edit');
    Route::post('user/delete',      'UserController@delete');

    Route::get('daily/list',         'DailyController@listDaily');
    Route::get('daily/list-all',     'DailyController@list_all');
    Route::post('daily/add',         'DailyController@add');
    Route::post('daily/edit',        'DailyController@edit');
    Route::delete('daily/delete/{id}', 'DailyController@delete');

    Route::get('chonso/list',         'ChonsoController@list');
    Route::post('chonso/add',         'ChonsoController@add');
    Route::delete('chonso/delete/{id}', 'ChonsoController@delete');

    Route::get('nhapso/list',         'ChonsoController@list_nhapso');
    Route::post('nhapso/add',         'ChonsoController@add_nhapso');
    Route::delete('nhapso/delete/{id}', 'ChonsoController@delete_nhapso');

    Route::get('giaoso/list',         'ChonsoController@list_giaoso');
    Route::post('giaoso/add',         'ChonsoController@add_giaoso');
    Route::delete('giaoso/delete/{id}', 'ChonsoController@delete_giaoso');
// });

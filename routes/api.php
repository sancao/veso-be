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

//Route::post('add-queue',  'ApiController@addQueue');
//Route::post('upload-s3',  'ApiController@uploadS3');



// Route::group(['middleware' => 'jwt'], function () {

    Route::get('user/list',         'UserController@listUser');
    Route::post('user/add',         'UserController@add');
    Route::post('user/edit',        'UserController@edit');
    //Route::post('user/delete',      'UserController@delete');
    Route::post('user/update-role', 'UserController@updateRole');

    Route::get('role/list',         'RoleController@listData');
    Route::post('role/add',         'RoleController@add');
    Route::post('role/edit',        'RoleController@edit');
    Route::post('role/delete',      'RoleController@delete');
    Route::post('role/update-module', 'RoleController@updateModule');

    Route::get('module/list',       'ModuleController@listModule');
    Route::post('module/add',       'ModuleController@add');
    Route::post('module/edit',      'ModuleController@edit');
    Route::post('module/delete',    'ModuleController@delete');

    Route::get('daily/list',         'DailyController@listDaily');
    Route::get('daily/list-all',     'DailyController@list_all');
    Route::post('daily/add',         'DailyController@add');
    Route::post('daily/edit',        'DailyController@edit');
    Route::delete('daily/delete/{id}', 'DailyController@delete');

    Route::get('chonso/list',         'ChonsoController@list');
    Route::post('chonso/add',         'ChonsoController@add');
    Route::delete('chonso/delete/{id}', 'ChonsoController@delete');

    Route::get('email/unique/{value}', 'UserController@check_email_unique');
    Route::get('phone/unique/{value}', 'UserController@check_phone_unique');
// });

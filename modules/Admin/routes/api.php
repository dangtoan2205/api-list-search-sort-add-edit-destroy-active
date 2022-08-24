<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:admin-api', 'check.permission'], 'prefix' => 'admins'], function () {
    Route::apiResource('admin', 'AdminController');
    Route::post('logout', 'AdminController@logout');
    Route::get('me', 'AdminController@getProfile');
});

Route::group(['middleware' => ['guest:admin-api'], 'prefix' => 'admins'], function () {
    Route::post('login', 'AdminController@login')->name('admins/login');
});

Route::apiResource('process', 'ProcessController');
Route::post('sort','ProcessController@sort');
Route::get('search/{process}','ProcessController@search');


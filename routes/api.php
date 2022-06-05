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

Route::namespace('App\Http\Controllers\Api')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('refresh-token', 'AuthController@refreshToken');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });
    });

    Route::group(['prefix' => 'employee'], function () {
        Route::post('login', 'AuthController@loginEmployee');
        Route::post('register', 'AuthController@registerEmployee');
        Route::post('refresh-token', 'AuthController@refreshTokenForEmployee');

        Route::group(['middleware' => 'auth:api-employee'], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('info', 'AuthController@employee');
        });
    });

    Route::group(['prefix' => 'manager'], function () {
        Route::post('login', 'AuthController@loginManager');
        Route::post('register', 'AuthController@registerManager');
        Route::post('refresh-token', 'AuthController@refreshTokenForManager');

        Route::group(['middleware' => 'auth:api-manager'], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('info', 'AuthController@manager');
        });
    });
});

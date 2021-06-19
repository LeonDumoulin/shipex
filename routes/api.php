<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {

    Route::get('login',function(){
        return 'hamada' ;
    })->name('admin.login');
    
    Route::group(['prefix' => 'user','middleware' => 'guest:api'],function(){
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
    });

    Route::group(['middleware' => 'guest:driver'],function(){
        Route::post('driver/register', 'AuthController@registerDriver');
        Route::post('driver/login', 'AuthController@loginDriver');
    });  

    Route::group(['prefix' => 'user','middleware' => 'auth:api'],function(){
        Route::post('driver/addrate', 'DriverController@AddRate');
        Route::get('driver/index', 'DriverController@index');
        Route::post('update', 'UserController@update');
        Route::post('make-order', 'OrderController@makeOrder');
    });



    Route::group(['prefix' => 'driver','middleware' => 'auth:driver'],function(){
        Route::post('update', 'DriverController@update');
        Route::post('accept-order', 'OrderController@driverAcceptOrder');
        Route::post('done-order', 'OrderController@doneOrder');
    });


});

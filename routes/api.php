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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'],function (){
    //新建订单
    Route::post('newOrder','OrderController@newOrder');
//获取用户所有订单
    Route::get('getOrders','OrderController@getOrders');
    //订单查询
    Route::get('queryOrder','OrderController@queryOrder');

});


Route::group(['middleware' => 'auth:api'],function (){
    //获取所有小区
    Route::get('getUnits','UnitController@getUnits');
});



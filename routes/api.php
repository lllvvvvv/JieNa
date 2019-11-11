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

Route::group(['middleware' => 'tokenType'],function (){
    Route::get('getUserToken','AlipayController@userInfo');
});

Route::get('decrypt','AlipayController@decrypt');

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
    //获取所有小区
    Route::get('getUnits','UnitController@getUnits');
    //生成箱体
//    Route::post('addBoxes','BoxesController');
    //还箱完成
    Route::post('confirmReceipt','AdminController@confirmReceipt');
    //通知管理员上门
    Route::post('uploadAddress','OrderController@uploadAddress');
    //获取上门订单列表
    Route::get('deliveryList','AdminController@deliveryList');
    //用户拿到箱子，开始计时
    Route::get('timingBegins','AdminController@timingBegins');
    //管理员拿到箱子,结束订单
    Route::get('confirmReceipt','AdminController@confirmReceipt');
    //获取回收订单列表
    Route::get('retrieveList','AdminController@retrieveList');

});

//支付宝接口
Route::get('AliUserToken','AlipayController@userInfo')->name('AliToken');


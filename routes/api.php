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
//判断用户是支付宝还是微信
Route::group(['middleware' => 'tokenType'],function (){
    Route::post('getUserToken','AlipayController@userInfo');
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
    Route::post('queryOrder','OrderController@queryOrder');
    //获取所有小区
    Route::get('getUnits','UnitController@getUnits');
    //通知管理员上门
    Route::post('uploadAddress','OrderController@uploadAddress');
    //小区列表
    Route::get('unitList','UnitController@unitList');
    //批量添加箱体
    Route::post('addBoxes','BoxController@addBoxes');
//    //完成订单
//    Route::post('finishOrder','OrderController@finishOrder');

    //用户买箱
    Route::post('buyBox','OrderController@buyBox');

    //用户买箱确认
    Route::post('confirmPurchase','OrderController@confirmPurchase');

    //下单确认成功，分配箱体给订单
    Route::post('distributionBox','OrderController@distributionBox');
    //获取Qrcode
    Route::get('getQrcode','OrderController@getQrcode');

    //获取已完成订单
    Route::get('getFinishOrders','OrderController@getFinishOrders');
    //新建搬家订单
    Route::post('newMoveOrder','MoveController@newMoveOrder');
    //搬家订单确认支付
    Route::get('verifyMovePay','MoveController@verifyMovePay');
    //获取所有搬家订单
    Route::get('getMoveOrder','MoveController@getMoveOrder');
    //测试repository
    Route::get('repository','PublicityController@test');

    Route::post('newHouseKeepOrder','HouseKeepsController@create');

    Route::post('houseKeepVerfiyPay','HouseKeepsController@VerifyPay');



    Route::get('getHouseKeep','HouseKeepsController@hkList');

});

//手动解冻


//加密信息解码，生成用户
Route::post('getUserPhone','AlipayController@getUserPhone');
Route::post('notify','AlipayController@notify');
Route::get('freeze','AlipayController@freeze');
//管理员注册
Route::post('register','AdminController@register');
//管理员登陆
Route::post('adminLogin','AdminController@login');
//为推广人员生成专属二维码
Route::post('publicityQrCode','PublicityController@publicityQrCode');

//支付宝接口
Route::get('AliUserToken','AlipayController@userInfo')->name('AliToken');
//获取用户手机号
Route::post('getUserPhone','AlipayController@getUserPhone');
//资金冻结
Route::get('freeze','AlipayController@freeze');

Route::post('/test','TestController@test');

//后台接口
Route::group(['middleware' => 'auth:admin','prefix' => 'back'],function (){
    //获取上门订单列表
    Route::get('deliveryList','AdminController@deliveryList');
    //获取回收订单列表
    Route::get('retrieveList','AdminController@retrieveList');
    //用户拿到箱子，开始计时
    Route::get('timingBegins','AdminController@timingBegins');
    //获得所有租箱订
    Route::get('getAllOrders','OrderController@getAllOrders');
    //订单查询
    Route::post('queryOrder','OrderController@queryOrder');
    //获得所有搬家订单
    Route::get('getAllMoveOrders','MoveController@getAllMoveOrders');
    //管理员还箱
    Route::post('confirmReceipt','AdminController@confirmReceipt');
    //获取所有买箱订单
    Route::get('getAll1BoxOrders','BoxController@getAllBoxOrders');
    //获取版本号
    Route::get('/getAppVersion','AlipayController@getVersion');
    //管理员获取取箱订单
    Route::get('/getBoxOrderList','OrderController@getBoxOrderList');
    //管理员获取回收订单
    Route::get('backBoxOrderList','OrderController@backBoxOrderList');
    //历史搬家订单
    Route::get('moveHistoryOrderList','MoveController@historyList');
    //历史租箱订单
    Route::get('boxHistoryOrderList','OrderController@historyList');
    //历史保洁订单
//    Route::get('houseKeepHistoryOrderList','Hou');
    //未完成订单
    Route::get('undoneOrderList','AdminController@undoneList');

    Route::get('houseKeepList','HouseKeepsController@index');//所有家政订单

});

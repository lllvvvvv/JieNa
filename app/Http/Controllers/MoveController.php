<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\NewMoveOrderRequest;
use App\MoveOrders;
use App\OrdersFlow;
use App\Services\AlipayService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Mockery\VerificationDirector;

class MoveController extends Controller
{
    //新建搬家订单
    public function newMoveOrder(NewMoveOrderRequest $request)
    {
        $dateTime = $request->dateTime;
        $moveOrder = MoveOrders::create(['moveno'=>Helpers::generateMoveNO(),
            'begin_address' => $request->begin_address,
            'finish_address' => $request->finish_address,
            'phone' => $request->phone,
            'user_id' => $request->user()->id,
            'driver_id' => 1,
            'order_type' => 1,
            'appointment'=> $dateTime,
            'car_type' => $request->car_type,
            'price' => 5
            ]);
        //新建未付款订单
        $pay = new AlipayService();
        $result = $pay->MovePay($moveOrder->moveno,5,$request->user()->ali_uid);
        return response()->json(['code' => 200,'result' => $result,'moveOrder'=>$moveOrder->moveno]);
    }

    public function verifyMovePay(Request $request)
    {
        $this->validate($request,['moveOrder' => 'required']);
        $moveOrder = MoveOrders::where('moveno','=',$request->moveOrder)->first()->update(['order_type'=>2]);
        SmsService::sendSMS(17798521228,['orderType'=>'搬家']);
        return response()->json(['code' => 200,'message' => '状态更新成功']);
    }

    public function getMoveOrder(Request $request)
    {
        $user = $request->user()->id;
        $moveOrders = MoveOrders::where('user_id',$user)->get();
        return response()->json(['code'=>200,'orders'=>$moveOrders]);
    }

    public function getAllMoveOrders(Request $request)
    {
        $moveOrders = MoveOrders::all();
        return response()->json(['code'=>200,'moveOrders' => $moveOrders]);
    }

    public function historyList(Request $request)
    {
        $request->begin ? $begin = $request->begin : $begin = 0;
        $request->end ? $end = $request->end : $end = now();
        $list = MoveOrders::time($begin,$end);
        return response()->json(['code'=>200,'data'=> $list]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Notify;
use App\Order;
use App\OrdersFlow;
use App\Services\AlipayService;
use App\Services\BoxService;
use App\Services\PriceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yansongda\Pay\Log;

class AdminController extends Controller
{
    //送货上门订单列表
    public function  deliveryList(Request $request)
    {
        $unit_id = $user = $request->user()->Admin()->first()->unit_id;
        $orders = DB::table('orders')->where('unit_id',$unit_id)->where('status',1)->get();
        $result = $orders->map(function ($order){
            $boxes = new BoxService();
            $boxes = $boxes->Boxes($order->id);
            return ['billno'=>$order->billno,
                'arrive_time'=>$order->arrive_time,
                'arrive_address'=>$order->arrive_address,
                'boxes' =>$boxes
            ];
        });
        return response()->json(['data'=>$result]);
    }

    //上门回收箱子列表
    public function retrieveList(Request $request)
    {
        $unit_id = $user = $request->user()->Admin()->first()->unit_id;
        $orders = DB::table('orders')->where('unit_id',$unit_id)->where('status',4)->get();
        $result = $orders->map(function ($order){
            $boxes = new BoxService();
            $boxes = $boxes->Boxes($order->id);
            return ['billno'=>$order->billno,
                'home_address'=>$order->home_address,
                'boxes' =>$boxes
            ];
        });
        return response()->json(['data'=>$result]);
    }

    //开始计时间
    public function timingBegins(Request $request)
    {
        $admin_id = $request->user()->Admin()->first()->id;
        $order = Order::where('billno',$request->orderId)->update(['status'=>3,
            'admin_id'=>$admin_id,
            'get_time'=>now()]);
        return response()->json(['code'=>200,'message'=>'开始计时']);
    }

    //确认收箱
    public function confirmReceipt(Request $request)
    {
        //更改订单状态
        //解除箱子关系
        $user = $request->user()->Admin()->first();
        $order = Order::where('billno',$request->orderId);
        $order->update(['admin_id' => $user->id,'status'=>5,'pay_time'=>Carbon::now()]);
        $order = $order->first();
        $flow_id = OrdersFlow::where('billno',$order->billno)->where('type',1)->first()->flow_id;
        $notify = json_decode(Notify::where('flow_id',$flow_id)->first()->content);
        $price = new PriceService();
        $price = $price->getPrice($order->billno);
        $pay = new AlipayService();
        if ($price>0) {
            $result = $pay->pay($order->billno, $notify, $price);
        }
        else {
            $result = $pay->unfreeze($order->billno,$notify);
        }
        $boxes = $order->Boxes()->where('status',1)->get();
        foreach ($boxes as $box) {
            $box->Order()->dissociate();
            $box->unit_id = $user->unit_id;
            $box->status = 0;
            $box->save();
        }
        //收钱

        return response()->json(['code'=>200,'message'=>'还箱完成','result'=>$result]);
    }


}

<?php

namespace App\Http\Controllers;

use App\Box;
use App\Helpers\Helpers;
use App\Order;
use App\Services\BoxService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    //order状态  1:送货上门 2:自提 3:已提未支付 4:待上门回收 5:支付完成 6:废订单

    public function newOrder(Request $request)
    {
        //生成二维码，传 user_id boxcount unit_id参数
        //读取unit剩下的箱子，随机分配箱子
        //管理员扫描二维码 获取管理员token 修改 Order:: status admin_id 开始计时间

        $user_id = $request->user()->id;

        $order = new Order();
        $order->user_id = $user_id;
        $order->billno = Helpers::generateBillNo();
        $order->status = $request->status;
        $order->arrive_time = $request->arriveTime;
        $order->save();
        $boxes = new BoxService();
        $boxes = $boxes->UnitBoxes($request->boxCount,$request->unitId);
        foreach ($boxes as $parm)
        {
            $box = Box::find($parm)->Order()->associate ($order);
            $box->save();
        }
        return response()->json(['下单成功']);
    }

    public function getOrders(Request $request)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('user_id',$user_id)->get(['billno','status']);
        return $orders;

    }

    //传参用户token 订单billno
    public function queryOrder(Request $request)
    {
        $order = Order::where('billno',$request->billNo)->first();
        $user = $order->User()->first();
        $box_count = $order->Boxes->count();
        return response()->json(['billNo' =>$order->billno, 'boxCount'=>$box_count,'userName'=>$user->name,'arriveAdress'=>$order->home_address,'phone'=>$user->phone]);
    }



}

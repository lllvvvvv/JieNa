<?php

namespace App\Http\Controllers;

use App\Box;
use App\Helpers\Helpers;
use App\Order;
use App\Services\BoxService;
use App\Services\PriceService;
use App\User;
use Cassandra\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function MongoDB\BSON\toJSON;
use function Symfony\Component\Console\Tests\Command\createClosure;

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
        $order->arrive_address = $request->arriveAddress;
        $order->arrive_time = $request->arriveTime;
        $order->unit_id = $request->unitId;
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
        $orders = Order::where('user_id',$user_id)->get();
        $result = $orders->map(function ($value){
            $price = new PriceService();
            $hour = $price->timeDifference($value->get_time);
            $price = $price->getPrice($value->billno) * $hour;
            return ['orderId'=>$value->billno,
                'box'=>DB::table('boxes')->select('box_type',DB::raw('count(*) as box_count'))
                ->where('order_id',$value->id)
                ->groupBy('box_type')
                ->get(),
                'get_time' => $value->get_time,'createTime'=>$value->created_at->toDateTimeString(),
                'pay_time' => $value->pay_time,
                'status' => $value->status,
                'price'=> $price];
        });
        return response()->json($result);
    }

    //传参用户token 订单billno
    public function queryOrder(Request $request)
    {
        $order = Order::where('billno',$request->billNo)->first();
        $user = $order->User()->first();
        $box_count = $order->Boxes->count();
        return response()->json(['billNo' =>$order->billno, 'boxCount'=>$box_count,'userName'=>$user->name,'arriveAdress'=>$order->home_address,'phone'=>$user->phone]);
    }

    public function finishOrder(Request $request)
    {
        $user = $request->user()->Admin()->first();
        $order = Order::where('billno','=',$request->orderId);
        $order->update(['admin_id'=>$user->id]);
        $boxes = $order->first()->Boxes()->get();
        foreach ($boxes as $box) {
            $box->Order()->dissociate();
            $box->unit_id = $user->unit_id;
            $box->status = 0;
            $box->save();
        }
        return response()->json(['code'=>200,'message'=>'还箱完成,订单完成']);
    }

    public function uploadAddress(Request $request)
    {
        $order = Order::where('billno','=',$request->orderId)->update(['home_address'=>$request->address,'status'=>4]);
        return response()->json([
            'code'=>200,
            'message'=>'order status update'
        ]);
    }

}

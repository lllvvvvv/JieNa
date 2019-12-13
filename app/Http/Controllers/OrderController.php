<?php

namespace App\Http\Controllers;

use App\Box;
use App\Helpers\Helpers;
use App\Http\Requests\NewOrderRequest;
use App\Order;
use App\OrdersFlow;
use App\Services\AlipayService;
use App\Services\BoxService;
use App\Services\PriceService;
use App\User;
use Cassandra\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function MongoDB\BSON\toJSON;
use function Symfony\Component\Console\Tests\Command\createClosure;

class OrderController extends Controller
{
    //order状态  1:送货上门 2:自提 3:已提未支付 4:待上门回收 5:支付完成 6:废订单

    public function newOrder(NewOrderRequest $request)
    {
        if ($request->boxes==null)
        {
            return response()->json(['code'=>'500','message'=>'箱体参数不全']);
        }
        $user_id = $request->user()->id;
        $order = Order::create(['user_id'=>$user_id,'billno'=>Helpers::generateBillNo(),
            'status'=>$request->status,
            'arrive_address'=>$request->arriveAddress,
            'arrive_time'=>$request->arriveTime,
            'unit_id'=>$request->unitId,
            'boxes'=>collect($request->boxes)->toJson()]);
        $boxes = new BoxService();
        $enough = $boxes->BoxCount($request->unitId,$request->boxes);
        if ($enough=='error')
        {
            return response()->json(['code'=>'JN001','message'=>'下单失败箱子不够']);
        }
        $rentbox = $boxes->RentBoxes($request->unitId,$request->boxes,$order->id);

        $freeze = new AlipayService();
        $result = $freeze->freeze($order->billno);
        return response()->json(['code'=>200,'message'=>'下单成功','ali'=>$result]);
    }



    public function getOrders(Request $request)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('user_id',$user_id)->orderBy('updated_at','DESC')->get();
        $result = $orders->map(function ($value){
            $price = new PriceService();
            $hour = $price->timeCount($value->get_time);
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
        $order = DB::table('orders')->where('billno',$request->billNo)->get();
        $order = $order->map(function ($order){
            $boxes = new BoxService();
            $boxes = $boxes->Boxes($order->id);
            $price = new PriceService();
            $hour = $price->timeCount($order->get_time);
            $this->price = $price->getPrice($order->billno) * $hour;
            $order->boxes = $boxes;
            $order->price = $this->price;
            return $order;
        });
//        DB::table('orders')->where('billno',$request->billNo)->update(['price'=>$this->price]);
        return response()->json(['data'=>$order]);
    }

    public function finishOrder(Request $request)
    {
        $user = $request->user()->Admin()->first();
        $order = Order::where('billno','=',$request->orderId);
        $order->update(['admin_id'=>$user->id,'status'=>5]);
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

    public function buyBox(Request $request)
    {
        $order = Order::where('billno',$request->orderId)->first();
        $require = $request->boxes;
        $price = PriceService::getBoxDeposit($request->boxes);
        $pay = new AlipayService();;
        $result = $pay->buyBox($request->orderId,$price,$request->user()->ali_uid);

        foreach ($require as $arr)
        {
            $boxes = $order->Boxes()->where('box_type',$arr['box_type'])
                ->take($arr['box_count'])
                ->update(['status'=>2,'buyer'=>$request->user()->id]);
        }
        return response()->json(['code'=>200,'message'=>$result]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Box;
use App\Helpers\Helpers;
use App\Http\Requests\BuyBoxRequest;
use App\Http\Requests\NewOrderRequest;
use App\Notify;
use App\Order;
use App\OrdersFlow;
use App\Services\AlipayService;
use App\Services\BoxService;
use App\Services\PriceService;
use App\Services\SmsService;
use App\Unit;
use App\User;
use Cassandra\Date;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function MongoDB\BSON\toJSON;
use function Symfony\Component\Console\Tests\Command\createClosure;

class OrderController extends Controller
{
    //order状态  1:送货上门 2:自提 3:已提未支付 4:待上门回收 5:支付完成 6:废订单

    public function newOrder(NewOrderRequest $request)
    {
        $publicity_id = null;
        if ($request->boxes==null)
        {
            return response()->json(['code'=>'500','message'=>'箱体参数不全']);
        }
        $unit = Unit::where('name','=',$request->unitName)->first();
        $unitId = $unit ? $unit->id : 1;            //如果没这个小区,从公司配送
        $user_id = $request->user()->id;
        $boxes = new BoxService();
        $enough = $boxes->BoxCount( $unitId,$request->boxes);
        if ($enough=='error')
        {
            return response()->json(['code'=>'JN001','message'=>'下单失败箱子不够']);
        }
         $user = User::where('id',$user_id)->first();
        if ($user->publicity_id != null && Order::where('user_id',$user_id)->first()==null)
        {
            $publicity_id = $user->publicity_id;
        }
        $order = Order::create(['user_id'=>$user_id,'billno'=>Helpers::generateBillNo(),
            'status'=>$request->status,
            'arrive_address'=>$request->arriveAddress,
            'arrive_time'=>$request->arriveTime,
            'unit_id'=>$unitId,
            'publicity_id' => $publicity_id,
            'boxes'=>collect($request->boxes)->toJson()]);
        $freeze = new AlipayService();
        $result = $freeze->freeze($order->billno);
        return response()->json(['code'=>200,'message'=>'下单成功','ali'=>$result,'id'=>$order->id,'unitId'=>$unitId]);
    }

    public function distributionBox(Request $request)
    {
        SmsService::sendSMS(17798521228,['orderType'=>'租箱']);
        $boxes = new BoxService();
        Order::where('id',$request->id)->update(['freeze'=>1]);
        $enough = $boxes->BoxCount($request->unitId,$request->boxes);
        if ($enough=='error')
        {
            return response()->json(['code'=>'JN001','message'=>'下单失败箱子不够']);
        }
        $rentbox = $boxes->RentBoxes($request->unitId,$request->boxes,$request->id);
        return response()->json(['code'=>200,'message'=>'分配成功']);
    }



    public function getOrders(Request $request)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('user_id',$user_id)->where('freeze',1)->whereNotIn('status',[7,5,8])->orderBy('updated_at','DESC')->get();
        $result = $orders->map(function ($value){
            $price = new PriceService();
            $hour = $price->timeCount($value->get_time);
            $price = $price->getPrice($value->billno);
            return ['orderId'=>$value->billno,
                'box'=>DB::table( 'boxes')->select('box_type',DB::raw('count(*) as box_count'))
                ->where('order_id',$value->id)
                ->where('status',1)
                ->groupBy('box_type')
                ->get(),
                'get_time' => $value->get_time,'createTime'=>$value->created_at->toDateTimeString(),
                'pay_time' => $value->pay_time,
                'status' => $value->status,
                'price'=> $price];
        });
        return response()->json($result);
    }


    public function getFinishOrders(Request $request)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('user_id',$user_id)->where('status',5)->orderBy('updated_at','DESC')->get();
        $result = $orders->map(function ($value){
            $price = new PriceService();
            $hour = $price->timeCount($value->get_time);
            $price = $price->getPrice($value->billno);
            return ['orderId'=>$value->billno,
                'box'=>json_decode($value->boxes),
                'get_time' => $value->get_time,'createTime'=>$value->created_at->toDateTimeString(),
                'pay_time' => $value->pay_time,
                'status' => $value->status,
                'price'=> $value->price];
        });
        return response()->json($result);

    }

    //传参用户token 订单billno
    public function queryOrder(Request $request)
    {
        $this->validate($request, [
            'billNo' => 'required'
        ]);
        $order = DB::table('orders')->where('billno',$request->billNo)->get();
        $order = $order->map(function ($order){
            $boxes = new BoxService();
            $boxes = $boxes->Boxes($order->id);
            $price = new PriceService();
            $hour = $price->timeCount($order->get_time);
            $this->price = $price->getPrice($order->billno) * $hour;
            $order->boxes = $boxes;
            $order->price = $this->price;
            $user = User::where('id',$order->user_id)->first();
            $order->name = $user->name;
            $order->phone = $user->phone;
            return $order;
        });
//        DB::table('orders')->where('billno',$request->billNo)->update(['price'=>$this->price]);
        return response()->json(['data'=>$order]);
    }

    public function finishOrder(Request $request)
    {
        $this->validate($request, [
           'orderId' => 'required'
        ]);
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
        $this->validate($request, [
            'orderId' => 'required',
            'address' => 'required',
        ]);
        $order = Order::where('billno','=',$request->orderId)->update(['home_address'=>$request->address,'status'=>4]);
        return response()->json([
            'code'=>200,
            'message'=>'order status update'
        ]);
    }

    public function buyBox(BuyBoxRequest $request)
    {
        $order = Order::where('billno',$request->orderId)->first();
        $price = PriceService::getBoxDeposit($request->boxes);
        $pay = new AlipayService();;
        $result = $pay->buyBox($request->orderId,$price,$request->user()['ali_uid']);
        return response()->json(['code'=>200,'message'=>$result]);
    }

    public function confirmPurchase(Request $request)
    {
        $this->validate($request, [
            'orderId' => 'required',
            'boxes' => 'required'
        ]);
        $order = Order::where('billno',$request->orderId)->first();
        $require = $request->boxes;
        foreach ($require as $arr)
        {
            Log::info($arr['box_count']);
            $boxes = $order->Boxes()->where('box_type',$arr['box_type'])
                ->where('status',1)
                ->take($arr['box_count'])
                ->update(['status'=>2,'buyer'=>$request->user()->id]);
        }
        if ($order->Boxes()->where('status',1)->count()==0)
        {
            //解冻订单
            $flow_id = OrdersFlow::where('billno',$order->billno)->where('type',1)->first()->flow_id;
            $notify = json_decode(Notify::where('flow_id',$flow_id)->first()->content);
            $unfreeze = new AlipayService();
            $unfreeze->unfreeze($order->billno,$notify);
            $order->update(['status'=>8]);
        }
        SmsService::sendSMS(17798521228,['orderType'=>'买箱']);
        return response()->json(['code'=>200,'message'=>'箱子交易成功']);

    }

    public function getQrcode(Request $request)
    {

        $data = QrCode::format('png')->size(300)->color(0,0,0)->backgroundColor(255,255,255)->generate($request->data);
        $url = base64_encode($data);
        return response()->json(['data'=>$url]);
    }

    public function getAllOrders(Request $request)
    {
        $order = Order::all();
        return response()->json(['code'=>200,'orders' => $order]);
    }

}

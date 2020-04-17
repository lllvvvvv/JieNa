<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Criteria\MyCriteria;
use App\Helpers\Helpers;
use App\Notify;
use App\Order;
use App\OrdersFlow;
use App\Repositories\AdminRepository;
use App\Services\AlipayService;
use App\Services\BoxService;
use App\Services\PriceService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yansongda\Pay\Log;

class AdminController extends Controller
{
    /**
     * @var AdminRepository
     */
    protected $repository;
    //送货上门订单列表

    public function __construct(AdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function  deliveryList(Request $request)
    {
        $unit_id = $user = $request->user('admin')->unit_id;
        $orders = DB::table('orders')->where('unit_id',$unit_id)->where('status',1)->where('freeze',1)->get();
        $result = $orders->map(function ($order){
            $user = User::where('id',$order->user_id)->first();
            $boxes = new BoxService();
            $boxes = $boxes->Boxes($order->id);
            return ['billno'=>$order->billno,
                'arrive_time'=>$order->arrive_time,
                'arrive_address'=>$order->arrive_address,
                'boxes' =>$boxes,
                'phone' => $user->phone,
                'name' => $user->name,
            ];
        });
        return response()->json(['data'=>$result]);
    }

    //上门回收箱子列表
    public function retrieveList(Request $request)
    {
        $unit_id = $user = $request->user()->unit_id;
        $orders = DB::table('orders')->where('unit_id',$unit_id)->where('status',4)->get();
        $result = $orders->map(function ($order){
            $user = User::where('id',$order->user_id)->first();
            $boxes = new BoxService();
            $boxes = $boxes->Boxes($order->id);
            return ['billno'=>$order->billno,
                'home_address'=>$order->home_address,
                'boxes' =>$boxes,
                'phone' => $user->phone,
                'name' => $user->name,
            ];
        });
        return response()->json(['data'=>$result]);
    }

    //开始计时间
    public function timingBegins(Request $request)
    {
        $this->validate($request,[
           'orderId' => 'required',
        ]);
        $admin_id = $request->user()->id;
        $order = Order::where('billno',$request->orderId)->update(['status'=>3,
            'admin_id'=>$admin_id,
            'first_admin' => $admin_id,
            'get_time'=>now()]);
        return response()->json(['code'=>200,'message'=>'开始计时']);
    }

    //确认收箱
    public function confirmReceipt(Request $request)
    {
        //更改订单状态
        //解除箱子关系
        $this->validate($request, [
            'orderId' => 'required'
        ]);
        $user = $request->user();
        $order = Order::where('billno',$request->orderId);
        $order->first()->home_address ? $back_status = 2 : $back_status = 1;
        $order->update(['admin_id' => $user->id,'status'=>5,'pay_time'=>Carbon::now(),'back_status' => $back_status]);
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

    public function register(Request $request)
    {
        $this->validate($request,['name' => 'required',
            'password' => 'required',
            'unitId' => 'required',
            ]);
        $uniqid = md5(uniqid(microtime(true),true));
        $token = substr($uniqid,0,30);
        $password = password_hash($request->password,PASSWORD_BCRYPT);
        $admin = Admin::create(['admin_type' => 1,
            'unit_id' => $request->unitId,
            'api_token' =>$token,
            'password' => $password,
            'name' => $request->name]);
        return response()->json(['code' => 200,'message' => '管理员注册成功']);
    }

    public function login(Request $request)
    {
        $this->validate($request,['name'=>'required',
            'password'=>'required']);
        $admin = Admin::where('name','=',$request->name)->first();
        if ($admin == null)
        {
            return response()->json(['code' => 422,'message' => '用户名错误']);
        }
        $result = password_verify($request->password,$admin->password);
        if ($result)
        {
            return response(['code' => 200,'token' => $admin->api_token,'admin_type' => $admin->admin_type]);
        }
        else
        {
            return response(['code' => 422,'message' => '密码错误']);
        }
    }

    public function auth(Request $request)
    {
        return response('asrfasdf');
    }

    public function test(Request $request)
    {
        $admin = $this->repository->all();
        return response()->json([$admin]);
    }

    public function undoneList(Request $request)
    {

    }


}

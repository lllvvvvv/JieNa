<?php

namespace App\Http\Controllers;

use App\Notify;
use App\Services\AlipayService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlipayController extends Controller
{
    public function __construct()
    {
    }

    public function userInfo(Request $request)
    {
        $this->validate($request, [
           'code' => 'required',
           'phone'=> 'required',
           'uid' => 'required'
        ]);
        $info = new AlipayService();
        $info = $info->aliUserInfo($request->code,$request->phone,$request->uid);
        return response()->json($info);
    }

    public function getUserPhone(Request $request)
    {
        $this->validate($request, [
           'res' => 'required'
        ]);
        $pay = new AlipayService();
        $phone = $pay->decryptData($request->res);
        $phone = json_decode($phone)->mobile;
        if (User::where('phone',$phone)->first()!= null)
        {
            $user = User::where('phone',$phone)->first();
            return response()->json(['code'=>200,'data'=>$user]);
        }

        $uniqid = md5(uniqid(microtime(true),true));
        $token = substr($uniqid,0,30);
        $user = new User;
        $user->phone = $phone;
        $user->api_token = $token;
        $user->save();

        return response()->json(['code'=>200,'data'=>$user]);
    }

    public function freeze(Request $request)
    {
        $pay = new AlipayService();
        $pay->freeze($request);

    }

    public function notify(Request $request)
    {

        if ($request->notify_type == 'fund_auth_freeze')
        {
            $notify = Notify::create(['flow_id'=>$request->out_request_no,'notify_type'=>$request->notify_type,'content'=>json_encode($request->all())]);
            return 'success';
        }
        if ($request->notify_type == 'trade_status_sync')
        {
            $notify = Notify::create(['flow_id'=>$request->out_trade_no,'notify_type'=>$request->notify_type,'content'=>json_encode($request->all())]);
            return 'success';
        }
        if ($request->notify_type == 'fund_auth_unfreeze')
        {
            $notify = Notify::create(['flow_id'=>$request->out_request_no,'notify_type'=>$request->notify_type,'content'=>json_encode($request->all())]);
            return 'success';
        }
    }


}

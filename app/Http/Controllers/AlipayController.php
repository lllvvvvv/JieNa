<?php

namespace App\Http\Controllers;

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

        $info = new AlipayService();
        $info = $info->aliUserInfo($request->code,$request->phone,$request->uid);
        return response()->json($info);
    }

    public function getUserPhone(Request $request)
    {
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
        Log::info($request);
//        Log::info($request->auth_no);
        return $request;
    }


}

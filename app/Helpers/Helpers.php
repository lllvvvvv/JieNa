<?php
namespace App\Helpers;



use App\Order;
use Illuminate\Support\Facades\DB;

class Helpers{
    public static function userId($token)
    {
        $user_id = DB::table('users')->where('api_token', $token)->first()->id;
        if ($user_id)
        {
            return $user_id;
        }
        else
        {
            return null;
        }
    }

    public static function generateBillNo()
    {
        return 'JYB' . date("YmdHis");
    }

    public static function generateFlowNo()
    {
        return 'FLOW' . date("YmdHis");
    }

    //获取箱子
    public static function getBoxes($order)
    {
        $boxes = json_decode(Order::where('billno',$order)->first()->boxes);
        $result = array();
        foreach ($boxes as $box)
        {
            array_push($result,json_decode(json_encode($box), true));
        }
        return $result;
    }


}

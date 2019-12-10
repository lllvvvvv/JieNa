<?php
namespace App\Helpers;



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


}

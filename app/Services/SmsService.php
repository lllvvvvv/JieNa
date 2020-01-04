<?php
namespace App\Services;

use Mrgoon\AliSms\AliSms;

class SmsService
{
    public static function sendSMS($mobile,$data,$templateCode='SMS_181851977')
    {
        $aliSms = new AliSms();
        $response = $aliSms->sendSms($mobile,$templateCode, $data);
        if($response->Message == 'OK'){
            return true;
        }else {
            return false;
        }
    }
}

<?php
namespace App\Helpers;



use Illuminate\Support\Facades\DB;

class Helpers{
    public static function userId($token)
    {
        $user_id = DB::table('users')->where('remember_token', $token)->first()->id;
        if ($user_id)
        {
            return $user_id;
        }
        else
        {
            return null;
        }
    }
}

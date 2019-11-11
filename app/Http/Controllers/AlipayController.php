<?php

namespace App\Http\Controllers;

use App\Services\AlipayService;
use Illuminate\Http\Request;

class AlipayController extends Controller
{
    public function __construct()
    {
    }

    public function userInfo(Request $request)
    {

        $info = new AlipayService();
        $info = $info->aliUserInfo($request->code);
        return response()->json($info);
    }

    public function decrypt(Request $request)
    {
        $result = new AlipayService();
        $result = $result->decryptData($request->res);
        return $result;
    }
}

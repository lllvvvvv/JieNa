<?php

namespace App\Http\Controllers;

use App\Box;
use App\Helpers\Helpers;
use App\Order;
use App\Services\BoxService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        //生成二维码，传 user_id boxcount unit_id参数
        //读取unit剩下的箱子，随机分配箱子
        //管理员扫描二维码 获取管理员token 修改 Order:: status admin_id 开始计时间

        $user_id = Helpers::userId($request->token);
        $order = new Order();
        $order->user_id = $user_id;
        $order->save();
        $boxes = new BoxService();
        $boxes = $boxes->UnitBoxes($request->boxCount,$request->unitId);
        foreach ($boxes as $parm)
        {
            $box = Box::find($parm)->Order()->associate ($order);
            $box->save();
        }
    }
}

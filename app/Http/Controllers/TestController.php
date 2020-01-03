<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Notify;
use App\Order;
use App\OrdersFlow;
use App\Services\AlipayService;
use App\Services\BoxService;
use App\Services\PriceService;
use App\Unit;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function test(Request $request)
    {
//        $data = Order::find(1)->Boxes()->update('');
//        dd($data);
//        dd( Order::find(1)->Boxs()->get()->price);
//        $boxes = Unit::find(1)->Boxes()->get();
//        dd($boxes);
//        $boxCount = new BoxService();
//        $boxCount->UnitBoxes(2,1);
//        dd($boxCount);
//        $test = new PriceService();
//        $test->timeDifference(null);
//        return response()->file(storage_path(). '/app/aliKey/file.txt');
//        $boxes = Helpers::getBoxes($request->order);
//        $price = PriceService::getBoxDeposit($boxes);

//        $order = 'JYB20191211173715';

//        $flow_id = OrdersFlow::where('billno',$order)->where('type',1)->first()->flow_id;
//        $notify = json_decode(Notify::where('flow_id',$flow_id)->first()->content);

    }

}

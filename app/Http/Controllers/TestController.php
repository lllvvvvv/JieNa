<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Order;
use App\Services\AlipayService;
use App\Services\BoxService;
use App\Services\PriceService;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
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
        $unit = Order::where('billno',$request->order)->first()->Unit()->first();
        dd($unit->address);
    }
}

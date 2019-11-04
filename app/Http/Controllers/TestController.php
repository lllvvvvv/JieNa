<?php

namespace App\Http\Controllers;

use App\Order;
use App\Services\BoxService;
use App\Unit;
use App\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
//        $data = Order::find(1)->Boxes()->update('');
//        dd($data);
//        dd( Order::find(1)->Boxs()->get()->price);
//        $boxes = Unit::find(1)->Boxes()->get();
//        dd($boxes);
        $boxCount = new BoxService();
        $boxCount->UnitBoxes(2,1);
//        dd($boxCount);
    }
}

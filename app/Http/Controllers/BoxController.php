<?php

namespace App\Http\Controllers;

use App\Box;
use App\OrdersFlow;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BoxController extends Controller
{
    public function getUnits()
    {
        $unit = Unit::find()->all();
        return response()->json([$unit]);
    }

    public function addBoxes(Request $request)
    {
        $this->validate($request, [
           'unit_id' => 'required',
           'count' => 'required' ,
           'box_type' => 'required'
        ]);
        $unit_id = $request->unit_id;
        $count = $request->count;
        $box_type = $request->box_type;
        while ($count>0)
        {
            DB::table('boxes')->insert(['unit_id'=>$unit_id,'box_type'=>$box_type,'status'=>0]);
            $count--;
        };
        return response()->json(['code' => 200,'message' => '生成箱体完成']);
    }
    public function getAllBoxOrders(Request $request)
    {
        $boxOrders = OrdersFlow::where('type',4)->get();
        return response()->json(['code' => 200,$boxOrders => $boxOrders]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Box;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BoxController extends Controller
{
    public function getUnits()
    {
        $unit = Unit::find()->all();
        dd($unit);
    }

    public function addBoxes(Request $request)
    {
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
    public function eee(Request $request)
    {

    }
}

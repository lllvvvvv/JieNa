<?php
namespace App\Services;

use App\Box;
use App\Unit;
use Illuminate\Support\Facades\DB;
use Yansongda\Pay\Log;

class BoxService
{
    //判断箱子数量是否够
    public function BoxCount($unit_id,$boxes)
    {
        foreach ($boxes as $box) {
            $result = Unit::find($unit_id, 'id')->Boxes()->where('status', 0)->where('box_type', $box['box_type'])->get();
            if ($result->count() < $box['box_count'])
            {
                Log::info($result->count());
                return 'error';
            }
        }
    }

    public function RentBoxes($unit_id,$boxes,$order_id)
    {
        foreach ($boxes as $box)
        {
            $result = DB::table('boxes')->where('unit_id',$unit_id)
                ->where('status',0)
                ->where('box_type',$box['box_type'])
                ->take($box['box_count'])
                ->update(['order_id'=>$order_id,'status'=>1]);
        }
    }

    public function Boxes($order_id)
    {
        $result = DB::table('boxes')->select('box_type',DB::raw('count(*) as box_count'))
            ->where('order_id',$order_id)
            ->groupBy('box_type')
            ->get();
        return $result;
    }



}


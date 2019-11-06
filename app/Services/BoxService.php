<?php
namespace App\Services;

use App\Box;
use App\Unit;

class BoxService
{
    //根据需要更改箱子状态
    public function UnitBoxes($amount,$unit_id)
    {
        $boxes = Unit::find($unit_id,'id')->Boxes()->where('status',0)->get();
        $boxId = array();
        foreach ($boxes as $box)
        {
            if ($amount == 0)
            {
                break;
            }
            $amount--;
            array_push($boxId,$box->id);
            $box->update(['status'=>1,'unit_id'=>null]);
        }
        return $boxId;
    }


}


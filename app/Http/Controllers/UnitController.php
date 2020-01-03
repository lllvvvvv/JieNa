<?php

namespace App\Http\Controllers;

use App\Services\BoxService;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function getUnits()
    {
        $units = Unit::all();
        $result = $units->map(function ($value){

            return ['name'=>$value->name,
                'id'=>$value->id,
                'boxes'=>DB::table('boxes')
                    ->join('box_type','boxes.box_type','=','box_type.box_type')
                    ->where('unit_id',$value->id)
                    ->where('boxes.status',0)
                    ->select('boxes.box_type',DB::raw('count(*) as box_count'),'deposit')
                    ->groupBy('box_type')
                    ->get()
                ];
        });
        return response()->json(['data'=>$result]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function getUnits()
    {
        $units = Unit::all();
        $result = $units->map(function ($value){
            return ['unityName'=>$value->name,
                'box'=>DB::table('boxes')->select('box_type',DB::raw('count(*) as box_count'))
                    ->where('unit_id',$value->id)
                    ->groupBy('box_type')
                    ->get()];
        });
        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function getUnits()
    {
        $units = Unit::all();
        $info = array();
        foreach ($units as $unit)
        {
            $info[$unit->name] = [['type'=>1,'count' =>$unit->Boxes()->where('box_type',1)->count()],
                ['type'=>2,'count' =>$unit->Boxes()->where('box_type',2)->count()]];
        }
        return response()->json($info);
    }
}

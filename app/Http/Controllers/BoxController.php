<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function getUnits()
    {
        $unit = Unit::find()->all();
        dd($unit);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        \View::addExtension('html', 'php');
        return view()->file(public_path() . '/homePage/index.html');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home extends Controller
{

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend/pages/homepage');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class Home extends Controller
{

    /**
     * Show the homepage
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend/pages/homepage');
    }
}

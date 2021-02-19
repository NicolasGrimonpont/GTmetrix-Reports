<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

class Monitoring extends Controller
{
    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        // Get monitored website
        if ($domains = $this->getMonitoredWebsite($id)) {

            return view('frontend/pages/website', compact('domains'));
        }
        abort(404);
    }



    /**
     * Get all monitored website
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function getMonitoredWebsite($id)
    {
        return DB::table('monitoring')->where('site_id', $id)->get();
    }
}

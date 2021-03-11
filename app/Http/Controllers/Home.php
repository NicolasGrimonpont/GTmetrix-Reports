<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Home extends Controller
{

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $companies = $this->getCompaniesFromDatabase();

        return view('frontend/pages/homepage', compact('companies'));
    }


    /**
     * Get company information from database
     *
     * @return Illuminate\Support\Facades\DB
     */
    private function getCompaniesFromDatabase()
    {
        return DB::table('companies')->get();
    }
}

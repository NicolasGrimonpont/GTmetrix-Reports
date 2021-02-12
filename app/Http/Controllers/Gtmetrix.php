<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class Gtmetrix extends Controller
{
    /**
     * Show the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $site = 'http://www.example.com/';

        // Send request to GTmetrix API
        if ($result = $this->gtmetrixRequest($site)) {

            if (!$this->addResultToDatabase($site, $result)) {

                Log::error("Error to record the datas on the database");
            }
        } else {

            Log::warning("The GTmetrix request don't work properly");
        }

        return view('frontend/pages/homepage');
    }


    /**
     * Request GTmetrix API
     */
    public function gtmetrixRequest($site)
    {
        $client = new GTMetrixClient();
        $client->setUsername(env('GT_USERNAME', ''));
        $client->setAPIKey(env('GT_APIKEY', ''));

        $client->getLocations();
        $client->getBrowsers();
        $test = $client->startTest($site);

        //Wait for result
        while (
            $test->getState() != GTMetrixTest::STATE_COMPLETED &&
            $test->getState() != GTMetrixTest::STATE_ERROR
        ) {
            $client->getTestStatus($test);
            sleep(5);
        }

        return $test;
    }

    /**
     * Add result to database
     */
    public function addResultToDatabase($site, $result)
    {
        $ressources = $result->getResources();

        return DB::table('sites')
            ->updateOrInsert(
                [
                    'site' => $site
                ],
                [
                    'gt_id' => $result->getId(),
                    'poll_state_url' => $result->getpollStateUrl(),
                    'state' => $result->getstate(),
                    'report_url' => $result->getreportUrl(),
                    'pagespeed_score' => $result->getpagespeedScore(),
                    'yslow_score' => $result->getyslowScore(),
                    'html_bytes' => $result->gethtmlBytes(),
                    'html_load_time' => $result->gethtmlLoadTime(),
                    'page_bytes' => $result->getpageBytes(),
                    'page_load_time' => $result->getpageLoadTime(),
                    'page_elements' => $result->getpageElements(),
                    'redirect_duration' => $result->getredirectDuration(),
                    'connect_duration' => $result->getconnectDuration(),
                    'backend_duration' => $result->getbackendDuration(),
                    'first_paint_time' => $result->getfirstPaintTime(),
                    'dom_interactive_time' => $result->getdomInteractiveTime(),
                    'dom_content_loaded_time' => $result->getdomInteractiveTime(),
                    'dom_content_loaded_duration' => $result->getdomContentLoadedDuration(),
                    'onload_time' => $result->getonloadTime(),
                    'onload_duration' => $result->getonloadDuration(),
                    'fully_loaded_time' => $result->getfullyLoadedTime(),
                    'rum_speed_index' => $result->getrumSpeedIndex(),
                    'report_pdf' => $ressources['report_pdf'],
                    'pagespeed' => $ressources['pagespeed'],
                    'har' => $ressources['har'],
                    'pagespeed_files' => $ressources['pagespeed_files'],
                    'report_pdf_full' => $ressources['report_pdf_full'],
                    'yslow' => $ressources['yslow'],
                    'screenshot' => $ressources['screenshot'],
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );
    }
}

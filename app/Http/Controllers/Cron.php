<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class Cron extends Controller
{

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function schedule()
    {
        // Get API Datas
        if ($api_credentials = $this->getApiKey()) {

            $gt_credentials = json_decode($api_credentials->value);
            $gt_email = $gt_credentials->email;
            $gt_api = $gt_credentials->gt_api;

            // Check if datas for API are empty
            if (!empty($gt_email) && !empty($gt_api)) {

                // Decrypt GTmetrix API key
                if ($gt_api = $this->decryptApiKey($gt_credentials->gt_api)) {

                    // Get all monitored domains
                    if ($sites = $this->getMonitoredDomains()) {

                        foreach ($sites as $site) {

                            // Make gtmetrix call for each domain
                            if ($result = $this->gtmetrixApi($site, $gt_email, $gt_api)) {

                                // Add result datas requested to database
                                if (!$this->addResultToDatabase($site, $result)) {
                                    Log::error("Cron task: Error to record the datas on the database");
                                }
                            } else {
                                Log::error("Cron task: The GTmetrix request don't work properly");
                            }
                        }
                    }
                } else {
                    Log::error("Cron task: Error decrypting the API key");
                }
            } else {
                Log::error("Cron task: Issue with API credentials");
            }
        } else {
            Log::error("Cron task: Error getting the API key");
        }
    }



    /**
     * Get GTmetrix API key from database
     *
     * @return $api_key
     */
    public function getApiKey()
    {
        return DB::table('settings')->where('attribute', 'gt_credentials')->first();
    }



    /**
     * Decrypt GTmetrix API key
     *
     * @param  $gt_api encrypted
     * @return $api_key decrypted
     */
    public function decryptApiKey($gt_api)
    {
        try {
            $gt_api = Crypt::decryptString($gt_api);
        } catch (DecryptException $e) {

            report($e);
            $gt_api = null;
        }
        return $gt_api;
    }



    /**
     * Get all monitored domains
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function getMonitoredDomains()
    {
        return DB::table('sites')->where('monitoring', true)->get();
    }



    /**
     * Request to GTmetrix API
     *
     * @param  \Illuminate\Http\Request
     * @param  string $gt_email
     * @param  string $gt_api
     * @return Entrecore\GTMetrixClient\GTMetrixClient
     * @return Entrecore\GTMetrixClient\GTMetrixTest
     */
    public function gtmetrixApi($site, $gt_email, $gt_api)
    {
        try {
            $client = new GTMetrixClient();
            $client->setUsername($gt_email);
            $client->setAPIKey($gt_api);

            $client->getLocations();
            $client->getBrowsers();
            $test = $client->startTest(trim($site->site), '4');

            //Wait for result
            while (
                $test->getState() != GTMetrixTest::STATE_COMPLETED &&
                $test->getState() != GTMetrixTest::STATE_ERROR
            ) {
                $client->getTestStatus($test);
                sleep(5);
            }
        } catch (\Throwable $e) {

            // Catch error but continue executing
            report($e);
            return false;
        }
        return $test;
    }



    /**
     * Add result to database
     *
     * @param  \Illuminate\Http\Request  $site
     * @param  Entrecore\GTMetrixClient\GTMetrixClient  $result
     * @return Illuminate\Support\Facades\DB
     */
    public function addResultToDatabase($site, $result)
    {
        $ressources = $result->getResources();

        return DB::table('monitoring')
            ->insert(
                [
                    'site' => trim($site->site),
                    'site_id' => $site->id,
                    'gt_id' => is_null($result->getId()),
                    'poll_state_url' => $result->getpollStateUrl(),
                    'state' => $result->getstate(),
                    'error' => (!empty($result->getError())) ? $result->getError() : null,
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
                    'report_pdf' => (!empty($ressources)) ? $ressources['report_pdf'] : null,
                    'pagespeed' => (!empty($ressources)) ? $ressources['pagespeed'] : null,
                    'har' => (!empty($ressources)) ? $ressources['har'] : null,
                    'pagespeed_files' => (!empty($ressources)) ? $ressources['pagespeed_files'] : null,
                    'report_pdf_full' => (!empty($ressources)) ? $ressources['report_pdf_full'] : null,
                    'yslow' => (!empty($ressources)) ? $ressources['yslow'] : null,
                    'screenshot' => (!empty($ressources)) ? $ressources['screenshot'] : null,
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );
    }
}

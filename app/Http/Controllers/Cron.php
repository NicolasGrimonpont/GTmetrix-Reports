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
        // Get all monitored domains
        if ($sites = $this->getMonitoredDomains()) {

            foreach ($sites as $site) {

                // Get the company of this website
                if ($company = $this->getCompany($site->company_id)) {

                    // Check if datas for API are empty
                    if (!empty($company->gt_email) && !empty($company->gt_api)) {

                        // Decrypt GTmetrix API key
                        if ($company->gt_api = $this->decryptApiKey($company->gt_api)) {

                            // Make gtmetrix call for this domain
                            if ($result = $this->gtmetrixApi($site, $company)) {

                                // Add result datas requested to database
                                if (!$this->addResultToDatabase($site, $result)) {
                                    Log::error("Cron task: Error to record the datas on the database");
                                }
                            } else {
                                Log::error("Cron task: The GTmetrix request don't work properly");
                            }
                        } else {
                            Log::error("Cron task: Error decrypting the API key");
                        }
                    }
                }
            }
        }
    }



    /**
     * Get Company from database
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function getCompany($company_id)
    {
        return DB::table('companies')->where('id', $company_id)->first();
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
    public function gtmetrixApi($site, $company)
    {
        try {
            $client = new GTMetrixClient();
            $client->setUsername($company->gt_email);
            $client->setAPIKey($company->gt_api);

            $client->getLocations();
            $client->getBrowsers();
            $test = $client->startTest(trim($site->site), $company->gt_location ?? '4');

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
                    'company_id' => $site->company_id,
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

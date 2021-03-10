<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class Reports extends Controller
{

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get user datas
        $user = Auth::user();

        // Get company information from database
        if ($company = $this->getCompany($user->company_id)) {

            // Get tested domains from the database
            $domains = $this->getDomainsFromDatabase($company->id);
        }



        return view('frontend/pages/reports', compact('domains'));
    }


    /**
     * Upload a file to get the list of domains
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // Get user datas
        $user = Auth::user();

        // Get company information from database
        if ($company = $this->getCompany($user->company_id)) {

            // Check if datas for API are empty
            if (empty($company->gt_email) || empty($company->gt_api)) {
                return response()->json(['error' => 'Issue with API credentials, please verify your credentials on your company']);
            }

            // Decrypt GTmetrix API key
            if (!$company->gt_api = $this->decryptApiKey($company->gt_api)) {
                Log::error("Error decrypting the API key");
                return response()->json(['error' => 'Issue with API credentials, please contact an admin']);
            }
        }

        // File validation
        $request->validate([
            'file' => 'required|mimes:txt|max:2048'
        ]);

        // Get content of file (all domains)
        $sites = file($request->file('file')->path());

        foreach ($sites as $site) {

            // Make gtmetrix call for each domain
            if ($result = $this->gtmetrixApi($site, $company->gt_email, $company->gt_api)) {

                // Add result datas requested to database
                if (!$this->addResultToDatabase($site, $company->id, $result)) {
                    Log::error("Error to record the datas on the database");
                    return response()->json(['error' => 'Issue with your request']);
                }
            } else {
                Log::error("The GTmetrix request don't work properly");
                return response()->json(['error' => 'Issue when requesting GTmetrix API']);
            }
        }
        return response()->json('success');
    }


    /**
     * Get company datas from database
     *
     * @param  $company_id
     * @return Illuminate\Support\Facades\DB
     */
    public function getCompany($company_id)
    {
        return DB::table('company')->where('id', $company_id)->first();
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
     * Get domains from databse
     *
     * @param  int $company_id
     * @return Illuminate\Support\Facades\DB
     */
    public function getDomainsFromDatabase($company_id)
    {
        return DB::table('sites')->where('company_id', $company_id)->get();
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
            $test = $client->startTest(trim($site), '4');

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
     * @param  \Illuminate\Http\Request  $company_id
     * @param  Entrecore\GTMetrixClient\GTMetrixClient  $result
     * @return Illuminate\Support\Facades\DB
     */
    public function addResultToDatabase($site, $company_id, $result)
    {
        $ressources = $result->getResources();

        return DB::table('sites')->updateOrInsert(
            [
                'site' => trim($site),
                'company_id' => $company_id
            ],
            [
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

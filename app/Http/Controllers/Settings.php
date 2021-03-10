<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Entrecore\GTMetrixClient\GTMetrixClient;

class Settings extends Controller
{
    /************************* Company settings *************************/

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function company()
    {
        // Get user datas
        $user = Auth::user();

        // Create empty  object of company
        $company = new Settings();
        $company->gt_email = null;
        $company->gt_api = null;
        $company->gt_location = null;
        $company->gt_config = null;

        // If the user have a company
        if (isset($user->company_id)) {

            // Get company datas from databse
            if ($company = $this->getCompanyFromDatabase($user->company_id)) {

                // Get Gtmetrix decrypted API key
                if ($company->gt_api) {
                    try {
                        $company->gt_api = Crypt::decryptString($company->gt_api);
                    } catch (DecryptException $e) {
                        report($e);
                        $company->gt_api = null;
                    }
                }

                // Get GTmetrix configuration (locations, browsers)
                if ($company->gt_email && $company->gt_api) {
                    $company->gt_config = $this->getApiConfig($company->gt_email, $company->gt_api);
                }
            }
        }

        return view('frontend/pages/settings/company', compact('company', 'user'));
    }


    /**
     * Get company information from database
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function getCompanyFromDatabase($id)
    {
        return DB::table('company')->where('id', $id)->first();
    }


    /**
     * Receive post value from from form
     *
     * @param  \Illuminate\Http\Request
     */
    public function companyFormValidation(Request $request)
    {
        // Get user datas
        $user = Auth::user();

        // Validate the data & unicity of company name excepted for this user
        $validator = Validator::make($request->all(), [
            'company_name' => [
                'required', 'string', 'max:100',
                Rule::unique('company', 'name')->ignore($user->company_id),
            ],
            'gt_email' => 'nullable|email|max:255',
            'gt_api' => 'nullable|max:255',
            'gt_location' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Update the company of the user or create a new one
        if (isset($user->company_id)) {

            // Update the company previously created by the user
            if ($this->updateCompanyDatasToDatabase($request->all(), $user->company_id)) {

                return back()->with('success', 'Company updated.');
            }
        } else {

            // Create a new company
            if ($company_id = $this->insertCompanyDatasToDatabase($request->all())) {

                // Update the user profile with the new company id
                if ($this->updateUserCompanyIdToDatabse($company_id)) {

                    return back()->with('success', 'Company updated.');
                } else {

                    Log::error("The user profile has not been updated with the new company");
                }
            }
        }
    }


    /**
     * Insert company info to the database and get the id
     *
     * @param  \Illuminate\Http\Request $data
     * @return Illuminate\Support\Facades\DB
     */
    public function insertCompanyDatasToDatabase($data)
    {
        return DB::table('company')->insertGetId(
            [
                'name' => $data['company_name'],
                'gt_email' => $data['gt_email'],
                'gt_api' => Crypt::encryptString($data['gt_api']),
                'gt_location' => $data['gt_location'],
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }



    /**
     * Update company info to the database
     *
     * @param  \Illuminate\Http\Request $data
     * @param  integer $id
     * @return Illuminate\Support\Facades\DB
     */
    public function updateCompanyDatasToDatabase($data, $company_id)
    {
        return DB::table('company')->where('id', $company_id)->update([
            'name' => $data['company_name'],
            'gt_email' => $data['gt_email'],
            'gt_api' => Crypt::encryptString($data['gt_api']),
            'gt_location' => $data['gt_location'],
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }



    /**
     * Update user info with the new company id
     *
     * @param  \Illuminate\Http\Request
     * @return Illuminate\Support\Facades\DB
     */
    public function updateUserCompanyIdToDatabse($company_id)
    {
        return DB::table('users')->where('id', Auth::id())->update(['company_id' => $company_id]);
    }



    /**
     * Request to GTmetrix API
     *
     * @param  \Illuminate\Http\Request
     * @param  string $gt_email
     * @param  string $gt_api
     * @return Entrecore\GTMetrixClient\GTMetrixClient
     */
    public function getApiConfig($gt_email, $gt_api)
    {
        try {
            $client = new GTMetrixClient();
            $client->setUsername($gt_email);
            $client->setAPIKey($gt_api);

            $config['locations'] = $client->getLocations();
            $config['browsers'] = $client->getBrowsers();
        } catch (\Throwable $e) {

            // Catch error but continue executing
            report($e);
            return false;
        }
        return $config;
    }



    /************************* Websites settings *************************/

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function websites()
    {
        $domains = null;

        // Get user datas
        $user = Auth::user();

        // Get company information from database
        if ($company = $this->getCompany($user->company_id)) {

            // Get tested domains from the database
            $domains = $this->getDomainsFromDatabase($company->id);
        }

        return view('frontend/pages/settings/websites', compact('domains'));
    }


    /**
     * Delete website and all datas related
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function deleteWebsite($id)
    {
        DB::table('sites')->where('id', '=', $id)->delete();
        DB::table('monitoring')->where('site_id', '=', $id)->delete();
        return back();
    }


    /************************* Monitoring settings *************************/

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function monitoring()
    {
        $domains = null;

        // Get user datas
        $user = Auth::user();

        // Get company information from database
        if ($company = $this->getCompany($user->company_id)) {

            // Get tested domains from the database
            $domains = $this->getDomainsFromDatabase($company->id);
        }

        return view('frontend/pages/settings/monitoring', compact('domains'));
    }


    /**
     * Update monitoring data from form processing (ajax)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monitoringFormValidation(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'state' => 'nullable'
        ]);

        // Add the testimonials to the database
        $this->updateMonitoringFromDatabase($validated);

        return response()->json(['msg' => 'success', 'data' => $validated]);
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
     * Update monitoring state on database
     *
     * @param  \Illuminate\Http\Request  $data
     * @return Illuminate\Support\Facades\DB
     */
    public function updateMonitoringFromDatabase($data)
    {
        return DB::table('sites')
            ->where('id', $data['id'])
            ->update([
                'monitoring' => ($data['state'] === 'true') ? false : true
            ]);
    }


    /************************* General settings *************************/

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = Auth::user();

        return view('frontend/pages/settings/settings', compact('user'));
    }


    /**
     * Receive post value from from form
     *
     * @param  \Illuminate\Http\Request
     */
    public function settingFormValidation(Request $request)
    {
        $validated = $request->validate(['name' => 'required|min:3|max:255']);

        // Updating the user datas
        $this->updateUserDatasToDatabse($validated);

        return back()->with('success', 'Profile updated.');
    }


    /**
     * Update user info to the database
     *
     * @param  \Illuminate\Http\Request
     * @return Illuminate\Support\Facades\DB
     */
    public function updateUserDatasToDatabse($data)
    {
        return DB::table('users')->where('id', Auth::id())->update(['name' => $data['name']]);
    }
}

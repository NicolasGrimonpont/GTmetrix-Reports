<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();

        $company = new Settings();
        $company->gt_email = null;
        $company->gt_api = null;
        $company->gt_location = null;

        if ($user->company_id) {

            if ($company = $this->getCompanyFromDatabase($user->company_id)) {

                // Get Gtmetrix settings from database
                if ($company->gt_api) {

                    // Decryption of API key
                    try {
                        $company->gt_api = Crypt::decryptString($company->gt_api);
                    } catch (DecryptException $e) {
                        report($e);
                        $company->gt_api = null;
                    }
                }
            }
        }

        return view('frontend/pages/settings/company', compact('company'));
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
        $validated = $request->validate([
            'gt_email' => 'nullable|email|max:255',
            'gt_api' => 'nullable|max:255',
            'gt_location' => 'nullable|max:255'
        ]);

        $user = Auth::user();

        // Updating company datas
        if ($user->company_id) {
            $this->updateCompanyDatasToDatabse($validated, $user->company_id);
        }

        return back()->with('success', 'Company updated.');
    }


    /**
     * Update company info to the database
     *
     * @param  \Illuminate\Http\Request $data
     * @param  integer $id
     * @return Illuminate\Support\Facades\DB
     */
    public function updateCompanyDatasToDatabse($data, $id)
    {
        return DB::table('company')->where('id', $id)->update([
            'gt_email' => $data['gt_email'],
            'gt_api' => Crypt::encryptString($data['gt_api']),
            'gt_location' => $data['gt_location']
        ]);
    }


    /************************* Websites settings *************************/

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function websites()
    {
        // Get tested domains from the database
        $domains = $this->getDomainsFromDatabase();

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
        // Get tested domains from the database
        $domains = $this->getDomainsFromDatabase();

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
     * @return Illuminate\Support\Facades\DB
     */
    public function getDomainsFromDatabase()
    {
        return DB::table('sites')->get();
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

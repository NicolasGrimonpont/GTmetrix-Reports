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
    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = Auth::user();
        $gt_api = null;
        $gt_email = null;

        // Get API credentials from database
        if ($gt_credentials = $this->getSettingsFromDatabase()) {

            $gt_credentials = json_decode($gt_credentials->value);
            $gt_email = $gt_credentials->email;

            if ($gt_credentials->gt_api) {

                // Decryption of API key
                try {
                    $gt_api = Crypt::decryptString($gt_credentials->gt_api);
                } catch (DecryptException $e) {
                    report($e);
                    $gt_api = null;
                }
            }
        }

        return view('frontend/pages/settings/settings', compact('user', 'gt_api', 'gt_email'));
    }


    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function company()
    {
        // Get tested domains from the database
        $domains = $this->getDomainsFromDatabase();

        return view('frontend/pages/settings/company', compact('domains'));
    }


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
     * Receive post value from from form
     *
     * @param  \Illuminate\Http\Request
     */
    public function settingFormValidation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'gt_email' => 'nullable|email|max:255',
            'gt_api' => 'nullable|max:255'
        ]);

        // Updating the user datas
        $this->updateUserDatasToDatabse($validated);

        // Updating settings datas
        $this->updateSettingsDatasToDatabse($validated);

        return back()->with('success', 'Profile updated.');
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
     * Get API credentials from databse
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function getSettingsFromDatabase()
    {
        return DB::table('settings')->where('attribute', 'gt_credentials')->first();
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


    /**
     * Update settings info to the database
     *
     * @param  \Illuminate\Http\Request
     * @return Illuminate\Support\Facades\DB
     */
    public function updateSettingsDatasToDatabse($data)
    {
        return DB::table('settings')->updateOrInsert(
            ['attribute' => 'gt_credentials',],
            [
                'value' => json_encode([
                    'email' => $data['gt_email'],
                    'gt_api' => Crypt::encryptString($data['gt_api'])
                ])
            ]
        );
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
}

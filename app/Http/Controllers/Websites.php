<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class Websites extends Controller
{
    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function websites($company_id, Request $request)
    {
        // Get tested domains from the database
        $domains = $this->getDomainsFromDatabase($company_id);

        // Get company information from database
        $company = $this->getCompanyFromDatabase($company_id);

        return view('frontend/pages/websites', compact('domains', 'company'));
    }



    /**
     * Add a website
     *
     * @return \Illuminate\View\View
     */
    public function websiteAdd()
    {
        return view('frontend/pages/website');
    }



    /**
     * Edit a company
     *
     * @return \Illuminate\View\View
     */
    public function websiteEdit($id, Request $request)
    {
        $website = $this->getDomainFromDatabase($id);

        return view('frontend/pages/website', compact('website'));
    }



    /**
     * Receive post value form to create a new website
     *
     * @param  \Illuminate\Http\Request
     */
    public function websiteAddFormValidation($company_id, Request $request)
    {
        // Validate the data & unicity of company name excepted for this user
        $validator = Validator::make($request->all(), [
            'site' => 'required|string|max:255|url',
            'kind' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Check if website is using NetSuite
        $result = $this->checkKindOfWebsite($request->site);

        $result['site'] = $request->site;

        // Create a new website
        if ($this->addWebsiteToDatabase($result, $company_id)) {

            return redirect(route('websites.edit', $company_id));
        }

        return back()->with('error', 'An error has occurred.');
    }



    /**
     * Receive post value form to edit a website
     *
     * @param  \Illuminate\Http\Request
     */
    public function websiteEditFormValidation($id, Request $request)
    {
        // Validate the data & unicity of company name excepted for this user
        $validator = Validator::make($request->all(), [
            'site' => 'required|string|max:255|url',
            'kind' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Update a website
        if ($this->updateWebsiteToDatabase($request->all(), $id)) {
            return back()->with('success', 'Website updated.');
        }

        return back()->with('error', 'An error has occurred.');
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
     * Upload a file to add websites from ajax
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function upload($company_id, Request $request)
    {
        // File validation
        $request->validate([
            'file' => 'required|mimes:txt|max:2048'
        ]);

        // Get content of file (all domains)
        $sites = file($request->file('file')->path());

        foreach ($sites as $site) {

            // Check if website is using NetSuite
            $result = $this->checkKindOfWebsite($site);

            $result['site'] = $site;

            // Add domains to databse
            if (!$this->addWebsiteToDatabase($result, $company_id)) {
                return response()->json(['error' => 'Issue with your request']);
            }
        }
        return response()->json('success');
    }



    /**
     * Check kind of website
     *
     * @return Illuminate\Support\Facades\DB
     */
    private function checkKindOfWebsite($site)
    {
        $result = null;

        try {
            $client = new Client([
                'base_uri' => trim($site),
                'timeout'  => 10.0
            ]);

            $response = $client->request('GET');

            $body = $response->getBody()->getContents();

            if (stripos($body, "data-cms-area") && stripos($body, "shopping-layout-content")) {
                $result['kind'] = 'NetSuite';
            }

            if (stripos($body, '<meta name="generator" content="WordPress')) {
                $result['kind'] = 'Wordpress';
            }
        } catch (\Throwable $th) {

            $result['state'] = 'Error';
            $result['error'] = $th->getMessage();
        }
        return $result;
    }



    /**
     * Get company information from database
     *
     * @return Illuminate\Support\Facades\DB
     */
    private function getCompanyFromDatabase($id)
    {
        return DB::table('companies')->where('id', $id)->first();
    }



    /**
     * Get domains from databse
     *
     * @param  int $company_id
     * @return Illuminate\Support\Facades\DB
     */
    private function getDomainsFromDatabase($company_id)
    {
        return DB::table('sites')->where('company_id', $company_id)->get();
    }



    /**
     * Get domain from databse
     *
     * @param  int $company_id
     * @return Illuminate\Support\Facades\DB
     */
    private function getDomainFromDatabase($id)
    {
        return DB::table('sites')->where('id', $id)->first();
    }



    /**
     * Add websites to database
     *
     * @param  \Illuminate\Http\Request  $site
     * @param  \Illuminate\Http\Request  $company_id
     * @return Illuminate\Support\Facades\DB
     */
    private function addWebsiteToDatabase($datas, $company_id)
    {
        return DB::table('sites')->updateOrInsert(
            [
                'site' => trim($datas['site']),
                'company_id' => $company_id
            ],
            [
                'kind' => $datas['kind'] ?? null,
                'state' => $datas['state'] ?? null,
                'error' => $datas['error'] ?? null,
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }



    /**
     * Update website to database
     *
     * @param  \Illuminate\Http\Request  $site
     * @param  \Illuminate\Http\Request  $company_id
     * @return Illuminate\Support\Facades\DB
     */
    private function updateWebsiteToDatabase($datas, $id)
    {
        return DB::table('sites')->where('id', $id)->update(
            [
                'site' => $datas['site'],
                'kind' => $datas['kind']
            ]
        );
    }



    /**
     * Update monitoring state on database
     *
     * @param  \Illuminate\Http\Request  $data
     * @return Illuminate\Support\Facades\DB
     */
    private function updateMonitoringFromDatabase($data)
    {
        return DB::table('sites')
            ->where('id', $data['id'])
            ->update([
                'monitoring' => ($data['state'] === 'true') ? false : true
            ]);
    }



    /**
     * Delete website and all datas related
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function websiteDelete($id)
    {
        DB::table('sites')->where('id', '=', $id)->delete();
        DB::table('monitoring')->where('site_id', '=', $id)->delete();
        return back();
    }
}

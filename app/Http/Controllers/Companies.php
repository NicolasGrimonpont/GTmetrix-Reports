<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Entrecore\GTMetrixClient\GTMetrixClient;

class Companies extends Controller
{
    /**
     * Show the companies view
     *
     * @return \Illuminate\View\View
     */
    public function companies()
    {
        $companies = $this->getCompaniesFromDatabase();

        return view('frontend/pages/companies', compact('companies'));
    }



    /**
     * Show the create a new company
     *
     * @return \Illuminate\View\View
     */
    public function companyCreate()
    {
        return view('frontend/pages/company');
    }



    /**
     * Edit a company
     *
     * @return \Illuminate\View\View
     */
    public function companyEdit($id, Request $request)
    {
        // Get company datas from databse
        if ($company = $this->getCompanyFromDatabase($id)) {

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

            return view('frontend/pages/company', compact('company'));
        }
        abort(404);
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
     * Receive post value form to create a new company
     *
     * @param  \Illuminate\Http\Request
     */
    public function companyCreateFormValidation(Request $request)
    {
        // Validate the data & unicity of company name excepted for this user
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:100|unique:companies,name',
            'company_description' => 'nullable|max:255',
            'gt_email' => 'nullable|email|max:255',
            'gt_api' => 'nullable|max:255',
            'gt_location' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Create a new company
        if ($this->insertCompanyDatasToDatabase($request->all())) {
            return back()->with('success', 'Company created.');
        }

        return back()->with('error', 'An error has occurred.');
    }



    /**
     * Receive post value form to create a new company
     *
     * @param  \Illuminate\Http\Request
     */
    public function companyEditFormValidation($id, Request $request)
    {
        // Validate the data & unicity of company name excepted for this user
        $validator = Validator::make($request->all(), [
            'company_name' => [
                'required', 'string', 'max:100',
                Rule::unique('companies', 'name')->ignore($id),
            ],
            'company_description' => 'nullable|max:255',
            'gt_email' => 'nullable|email|max:255',
            'gt_api' => 'nullable|max:255',
            'gt_location' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Create a new company
        if ($this->updateCompanyDatasToDatabase($request->all(), $id)) {
            return back()->with('success', 'Company updated.');
        }

        return back()->with('error', 'An error has occurred.');
    }



    /**
     * Insert company info to the database and get the id
     *
     * @param  \Illuminate\Http\Request $data
     * @return Illuminate\Support\Facades\DB
     */
    public function insertCompanyDatasToDatabase($data)
    {
        return DB::table('companies')->insertGetId(
            [
                'name' => $data['company_name'],
                'description' => $data['company_description'],
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
        return DB::table('companies')->where('id', $company_id)->update([
            'name' => $data['company_name'],
            'description' => $data['company_description'],
            'gt_email' => $data['gt_email'],
            'gt_api' => Crypt::encryptString($data['gt_api']),
            'gt_location' => $data['gt_location'],
            'updated_at' => \Carbon\Carbon::now()
        ]);
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



    /**
     * Get company information from database
     *
     * @return Illuminate\Support\Facades\DB
     */
    private function getCompaniesFromDatabase()
    {
        return DB::table('companies')->get();
    }



    /**
     * Delete company and all datas related
     *
     * @return Illuminate\Support\Facades\DB
     */
    public function companyDelete($company_id)
    {
        DB::table('companies')->where('id', $company_id)->delete();
        DB::table('sites')->where('company_id', $company_id)->delete();
        DB::table('monitoring')->where('company_id', $company_id)->delete();
        return back();
    }
}

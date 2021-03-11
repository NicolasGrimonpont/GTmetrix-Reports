<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Settings extends Controller
{
    /************************* General settings *************************/

    /**
     * Show the page
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = Auth::user();

        return view('frontend/pages/settings', compact('user'));
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

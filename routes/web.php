<?php

use Illuminate\Support\Facades\Route;

// use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use Illuminate\Http\Request;
// use Illuminate\Auth\Events\PasswordReset;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Password;
// use Illuminate\Support\Str;

use App\Http\Controllers\Home;
use App\Http\Controllers\Monitoring;
use App\Http\Controllers\Reports;
use App\Http\Controllers\Settings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Reports
Route::get('/', [Reports::class, 'index'])->middleware(['auth'])->name('reports');
Route::post('/', [Reports::class, 'upload'])->middleware(['auth'])->name('reports');

// Monitoring
Route::get('/monitoring/{id}', [Monitoring::class, 'index'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('monitoring');

Route::get('/monitoring/delete/{id}', [Monitoring::class, 'deleteWebsite'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('monitoring');

// Settings
Route::get('/settings', [Settings::class, 'settings'])->middleware(['auth'])->middleware(['password.confirm'])->middleware('verified')->name('settings');
Route::post('/settings', [Settings::class, 'settingFormValidation'])->middleware(['auth'])->name('settings');

Route::get('/settings/monitoring', [Settings::class, 'monitoring'])->middleware(['auth'])->name('monitoring');
Route::post('/settings/monitoring', [Settings::class, 'monitoringFormValidation'])->middleware(['auth']);

Route::get('/cron', [Monitoring::class, 'schedule'])->middleware(['auth'])->name('cron');

// Use Breeze's routes instead of customs login routes
require __DIR__ . '/auth.php';


// Custom login routes

// Email verification notice
// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// // Email verification handler
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     return redirect('/home');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// // Resending verification email
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();

//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// Reset password
// Route::get('/forgot-password', function () {
//     return view('auth.forgot-password');
// })->middleware('guest')->name('password.request');

// // Form submission
// Route::post('/forgot-password', function (Request $request) {
//     $request->validate(['email' => 'required|email']);

//     $status = Password::sendResetLink(
//         $request->only('email')
//     );

//     return $status === Password::RESET_LINK_SENT
//         ? back()->with(['status' => __($status)])
//         : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('password.email');

// // Password reset show form
// Route::get('/reset-password/{token}', function ($token) {
//     return view('auth.reset-password', ['token' => $token]);
// })->middleware('guest')->name('password.reset');

// // Password reset form submission
// Route::post('/reset-password', function (Request $request) {
//     $request->validate([
//         'token' => 'required',
//         'email' => 'required|email',
//         'password' => 'required|min:8|confirmed',
//     ]);

//     $status = Password::reset(
//         $request->only('email', 'password', 'password_confirmation', 'token'),
//         function ($user, $password) use ($request) {
//             $user->forceFill([
//                 'password' => Hash::make($password)
//             ])->save();

//             $user->setRememberToken(Str::random(60));

//             event(new PasswordReset($user));
//         }
//     );

//     return $status == Password::PASSWORD_RESET
//         ? redirect()->route('login')->with('status', __($status))
//         : back()->withErrors(['email' => [__($status)]]);
// })->middleware('guest')->name('password.update');

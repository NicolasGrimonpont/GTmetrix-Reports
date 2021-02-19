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

<?php

use Illuminate\Support\Facades\Route;

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
Route::post('/', [Reports::class, 'upload'])->middleware(['auth']);

// Monitoring
Route::get('/monitoring/{id}', [Monitoring::class, 'index'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('monitoring');

Route::get('/monitoring/delete/{id}', [Monitoring::class, 'deleteWebsite'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth']);

// Settings
Route::get('/settings', [Settings::class, 'settings'])->middleware(['auth'])->middleware(['password.confirm'])->middleware('verified')->name('settings');
Route::post('/settings', [Settings::class, 'settingFormValidation'])->middleware(['auth']);

Route::get('/settings/monitoring', [Settings::class, 'monitoring'])->middleware(['auth'])->name('settings.monitoring');
Route::post('/settings/monitoring', [Settings::class, 'monitoringFormValidation'])->middleware(['auth']);

Route::get('/settings/company', [Settings::class, 'company'])->middleware(['auth'])->name('company');
Route::post('/settings/company', [Settings::class, 'companyFormValidation'])->middleware(['auth']);

Route::get('/cron', [Monitoring::class, 'schedule'])->middleware(['auth']);

// Use Breeze's routes instead of customs login routes
require __DIR__ . '/auth.php';

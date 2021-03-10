<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home;
use App\Http\Controllers\Monitoring;
use App\Http\Controllers\Reports;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Cron;

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

// Settings
Route::get('/settings/company', [Settings::class, 'company'])->middleware(['auth'])->name('settings.company');
Route::post('/settings/company', [Settings::class, 'companyFormValidation'])->middleware(['auth']);
Route::get('/settings/company/delete/{id}', [Settings::class, 'deleteWebsite'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth']);

Route::get('/settings/websites', [Settings::class, 'websites'])->middleware(['auth'])->name('settings.websites');

Route::get('/settings/monitoring', [Settings::class, 'monitoring'])->middleware(['auth'])->name('settings.monitoring');
Route::post('/settings/monitoring', [Settings::class, 'monitoringFormValidation'])->middleware(['auth']);

Route::get('/settings', [Settings::class, 'settings'])->middleware(['auth'])->name('settings');
Route::post('/settings', [Settings::class, 'settingFormValidation'])->middleware(['auth']);

// Cron tasks
Route::get('/cron', [Cron::class, 'schedule'])->middleware(['auth']);

// Use Breeze's routes
require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home;
use App\Http\Controllers\Monitoring;
use App\Http\Controllers\Reports;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Companies;
use App\Http\Controllers\Websites;
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

// Home
Route::get('/', [Home::class, 'index'])->middleware(['auth'])->name('home');


// Reports
Route::get('/report/{id}', [Reports::class, 'index'], function ($company_id) {
    return 'company_id ' . $company_id;
})->middleware(['auth'])->name('report');

Route::post('/report/update/{id}', [Reports::class, 'testDomain'])->middleware(['auth'], function ($site_id) {
    return 'site_id ' . $site_id;
});


// Companies
Route::get('/companies', [Companies::class, 'companies'])->middleware(['auth'])->name('companies');

Route::get('/company/create', [Companies::class, 'companyCreate'])->middleware(['auth'])->name('company.create');
Route::post('/company/create', [Companies::class, 'companyCreateFormValidation'])->middleware(['auth']);

Route::get('/company/edit/{id}', [Companies::class, 'companyEdit'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('company.edit');

Route::post('/company/edit/{id}', [Companies::class, 'companyEditFormValidation'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth']);

Route::get('/company/delete/{id}', [Companies::class, 'companyDelete'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('company.delete');


// Websites
Route::get('/websites/edit/{id}', [Websites::class, 'websites'], function ($company_id) {
    return 'company_id ' . $company_id;
})->middleware(['auth'])->name('websites.edit');

Route::post('/websites/edit/{id}', [Websites::class, 'upload'], function ($company_id) {
    return 'company_id ' . $company_id;
})->middleware(['auth']);

Route::post('/websites/update/monitoring', [Websites::class, 'monitoringFormValidation'])->middleware(['auth']);


Route::get('/website/add/{id}', [Websites::class, 'websiteAdd'], function ($company_id) {
    return 'company_id ' . $company_id;
})->middleware(['auth'])->name('website.add');

Route::post('/website/add/{id}', [Websites::class, 'websiteAddFormValidation'], function ($company_id) {
    return 'company_id ' . $company_id;
})->middleware(['auth']);


Route::get('/website/edit/{id}', [Websites::class, 'websiteEdit'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('website.edit');

Route::post('/website/edit/{id}', [Websites::class, 'websiteEditFormValidation'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth']);


Route::get('/website/delete/{id}', [Websites::class, 'websiteDelete'], function ($id) {
    return 'id ' . $id;
})->middleware(['auth'])->name('website.delete');


// Settings
Route::get('/settings', [Settings::class, 'settings'])->middleware(['auth'])->name('settings');
Route::post('/settings', [Settings::class, 'settingFormValidation'])->middleware(['auth']);


// Cron tasks
Route::get('/cron', [Cron::class, 'schedule'])->middleware(['auth']);


// Use Breeze's routes
require __DIR__ . '/auth.php';

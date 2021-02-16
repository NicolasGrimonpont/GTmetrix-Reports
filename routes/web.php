<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home;
use App\Http\Controllers\Gtmetrix;

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

// Homepage
Route::get('/', [Home::class, 'index']);

// Reports
Route::get('/reports', [Gtmetrix::class, 'index'])->middleware(['auth'])->name('reports');
Route::post('/reports', [Gtmetrix::class, 'upload'])->middleware(['auth'])->name('reports');

require __DIR__ . '/auth.php';

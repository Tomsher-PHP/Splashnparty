<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Auth\Events\Login;
use App\Http\Controllers\CcAvenueController;

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

Route::get('/', [AuthController::class, 'showLogin']);



Route::post('/payment/ccavenue/success', [CcAvenueController::class, 'success'])
    ->name('ccavenue.success');

Route::post('/payment/ccavenue/failure', [CcAvenueController::class, 'failure'])
    ->name('ccavenue.failure');


<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::patch('staffs/{staff}/status', [StaffController::class, 'updateStatus'])->name('staffs.update-status');
    Route::resource('staffs', StaffController::class)->except(['show']);

    Route::patch('roles/{role}/status', [RoleController::class, 'updateStatus'])->name('roles.update-status');
    Route::resource('roles', RoleController::class)->except(['show']);

    Route::patch('banners/{banner}/status', [BannerController::class, 'updateStatus'])->name('banners.update-status');
    Route::resource('banners', BannerController::class)->except(['show']);

    Route::patch('client-logos/{clientLogo}/status', [ClientLogoController::class, 'updateStatus'])->name('client-logos.update-status');
    Route::resource('client-logos', ClientLogoController::class)->parameters(['client-logos' => 'clientLogo'])->except(['show']);

    Route::get('general-settings', [GeneralSettingController::class, 'edit'])->name('general-settings.edit');
    Route::put('general-settings', [GeneralSettingController::class, 'update'])->name('general-settings.update');

    // FAQ MODULE
    Route::resource('faqs', FaqController::class)->parameters(['faqs' => 'faq'])->except(['show']);

});

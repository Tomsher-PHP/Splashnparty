<?php

use App\Http\Controllers\Api\BalloonDecorationApiController;
use App\Http\Controllers\Api\BirthdayPackageApiController;
use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\CafeMenuApiController;
use App\Http\Controllers\Api\CakeApiController;
use App\Http\Controllers\Api\FoodMenuApiController;
use App\Http\Controllers\Api\GalleryApiController;
use App\Http\Controllers\Api\GeneralAccessApiController;
use App\Http\Controllers\Api\PackageApiController;
use App\Http\Controllers\Api\PartyExtrasApiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\RentalApiController;
use Illuminate\Support\Facades\Route;


Route::get('/home-page', [PageApiController::class, 'HomePageContent']);
Route::get('/about-us', [PageApiController::class, 'aboutUs']);
Route::get('/contact-us', [PageApiController::class, 'contactUs']);
Route::get('/privacy-policy', [PageApiController::class, 'privacyPolicy']);
Route::get('/terms-and-conditions', [PageApiController::class, 'termsAndConditions']);
Route::get('/refund-policy', [PageApiController::class, 'refundPolicy']);
Route::get('/faqs', [PageApiController::class, 'faqs']);
Route::post('/contact-submit', [ContactApiController::class, 'submitContactForm']);
Route::get('/waterpark', [PageApiController::class, 'waterpark']);
Route::get('/footer', [PageApiController::class, 'footerSettings']);
Route::get('/settings', [PageApiController::class, 'settings']);



Route::get('/image-gallery',[GalleryApiController::class, 'imageGallery']);
Route::get('/video-gallery',[GalleryApiController::class, 'videoGallery']);
Route::get('/outdoor-events',[GalleryApiController::class, 'outdoorEvents']);
Route::get('/cakes',[CakeApiController::class, 'cakes']);
Route::get('/branches',[BranchApiController::class, 'branches']);
Route::get('/rentals',[RentalApiController::class, 'rentals']);
Route::get('/cafe-menus',[CafeMenuApiController::class, 'cafeMenus']);
Route::get('/balloon-decorations',[BalloonDecorationApiController::class, 'balloonDecorations']);
Route::get('/birthday-packages',[BirthdayPackageApiController::class, 'birthdayPackages']);
Route::get('/food-menus',[FoodMenuApiController::class, 'foodMenus']);

Route::get('/party-extras', [PartyExtrasApiController::class, 'partyExtras']);
Route::get('/general-access', [GeneralAccessApiController::class, 'generalAccess']);

Route::get('/packages', [PackageApiController::class, 'index']);
Route::get('/packages/{id}', [PackageApiController::class, 'show']);
Route::post('/packages/get-booking-price', [PackageApiController::class, 'getBookingPrice']);

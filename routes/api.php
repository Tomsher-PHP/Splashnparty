<?php

use App\Http\Controllers\Api\BalloonDecorationApiController;
use App\Http\Controllers\Api\BirthdayPackageApiController;
use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\CafeMenuApiController;
use App\Http\Controllers\Api\CakeApiController;
use App\Http\Controllers\Api\FoodMenuApiController;
use App\Http\Controllers\Api\GalleryApiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Api\RentalApiController;
use Illuminate\Support\Facades\Route;


Route::get('/home-page', [PageApiController::class, 'HomePageContent']);
Route::get('/about-us', [PageApiController::class, 'aboutUs']);
Route::get('/contact-us', [PageApiController::class, 'contactUs']);
Route::get('/privacy-policy', [PageApiController::class, 'privacyPolicy']);
Route::get('/terms-and-conditions', [PageApiController::class, 'termsAndConditions']);
Route::get('/refund-policy', [PageApiController::class, 'refundPolicy']);
Route::get('/faqs', [PageApiController::class, 'faqs']);


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

Route::middleware('auth:sanctum')->group(function () {

});

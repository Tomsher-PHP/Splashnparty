<?php

use App\Http\Controllers\Api\BalloonDecorationApiController;
use App\Http\Controllers\Api\BirthdayPackageApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\CafeMenuApiController;
use App\Http\Controllers\Api\CakeApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\FoodMenuApiController;
use App\Http\Controllers\Api\GalleryApiController;
use App\Http\Controllers\Api\GeneralAccessApiController;
use App\Http\Controllers\Api\NewsletterApiController;
use App\Http\Controllers\Api\NewsUpdateApiController;
use App\Http\Controllers\Api\PackageApiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Api\PartyExtrasApiController;
use App\Http\Controllers\Api\RentalApiController;
use App\Http\Controllers\Api\VisitorApiController;
use Illuminate\Support\Facades\Route;


Route::get('/home-page', [PageApiController::class, 'HomePageContent']);
Route::get('/about-us', [PageApiController::class, 'aboutUs']);
Route::get('/contact-us', [PageApiController::class, 'contactUs']);
Route::get('/privacy-policy', [PageApiController::class, 'privacyPolicy']);
Route::get('/terms-and-conditions', [PageApiController::class, 'termsAndConditions']);
Route::get('/refund-policy', [PageApiController::class, 'refundPolicy']);
Route::get('/faqs', [PageApiController::class, 'faqs']);
Route::post('/contact-submit', [ContactApiController::class, 'submitContactForm']);
Route::post('/newsletter-subscribe', [NewsletterApiController::class, 'subscribe']);
Route::get('/waterpark', [PageApiController::class, 'waterpark']);
Route::get('/footer', [PageApiController::class, 'footerSettings']);
Route::get('/settings', [PageApiController::class, 'settings']);
Route::get('/gallery-categories', [GalleryApiController::class, 'galleryCategories']);
Route::get('/gallery-items', [GalleryApiController::class, 'galleryItems']);



Route::get('/image-gallery',[GalleryApiController::class, 'imageGallery']);
Route::get('/video-gallery',[GalleryApiController::class, 'videoGallery']);
Route::get('/outdoor-events',[GalleryApiController::class, 'outdoorEvents']);
Route::get('/cakes',[CakeApiController::class, 'cakes']);
Route::get('/cake-details',[CakeApiController::class, 'cakeDetails']);
Route::post('/cake-enquiry',[CakeApiController::class, 'submitEnquiry']);
Route::get('/branches',[BranchApiController::class, 'branches']);
Route::get('/rentals',[RentalApiController::class, 'rentals']);
Route::get('/cafe-menu-page',[CafeMenuApiController::class, 'cafeMenuPage']);
Route::get('/cafe-menu-categories',[CafeMenuApiController::class, 'cafeMenuCategories']);
Route::get('/cafe-menu-items',[CafeMenuApiController::class, 'cafeMenuItems']);

Route::get('/balloon-decorations',[BalloonDecorationApiController::class, 'balloonDecorations']);
Route::get('/birthday-packages',[BirthdayPackageApiController::class, 'birthdayPackages']);
Route::get('/food-menus',[FoodMenuApiController::class, 'foodMenus']);

Route::get('/food-menu-page',[FoodMenuApiController::class, 'foodMenuPage']);
Route::get('/food-menu-categories',[FoodMenuApiController::class, 'foodMenuCategories']);
Route::get('/food-menu-items',[FoodMenuApiController::class, 'foodMenuItems']);

Route::get('/party-extras', [PartyExtrasApiController::class, 'partyExtras']);
Route::get('/general-access', [GeneralAccessApiController::class, 'generalAccess']);

Route::get('/packages', [PackageApiController::class, 'index']);
Route::get('/packages/{id}', [PackageApiController::class, 'show']);
Route::post('/packages/get-booking-price', [PackageApiController::class, 'getBookingPrice']);

Route::post('/bookings', [BookingApiController::class, 'store']);

Route::get(
    '/bookings/{id}',
    [BookingApiController::class, 'show']
);

// News and Updates 

// Listing
Route::get(
    '/news-updates',
    [NewsUpdateApiController::class, 'index']
);

// Details 
Route::get('/news-details',[NewsUpdateApiController::class, 'show']);

Route::get('events',[EventApiController::class, 'index']);

Route::get('event-details',[EventApiController::class, 'show']);

// Visitor tracking
Route::post('/visitors', [VisitorApiController::class, 'store']);
Route::get('/visitors/count', [VisitorApiController::class, 'count']);

// API Fallback Route to catch all undefined api paths with any HTTP method
Route::any('{any}', function () {
    return response()->json([
        'success' => false,
        'message' => 'Page Not Found.',
    ], 404);
})->where('any', '.*');

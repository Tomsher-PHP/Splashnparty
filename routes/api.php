<?php

use App\Http\Controllers\Api\BalloonDecorationApiController;
use App\Http\Controllers\Api\BirthdayPackageApiController;
use App\Http\Controllers\Api\BranchApiController;
use App\Http\Controllers\Api\CafeMenuApiController;
use App\Http\Controllers\Api\CakeApiController;
use App\Http\Controllers\Api\FoodMenuApiController;
use App\Http\Controllers\Api\GalleryApiController;
use App\Http\Controllers\Api\RentalApiController;
use App\Models\BalloonDecoration;
use App\Models\BirthdayPackage;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::get(
        '/v1/image-gallery',
        [GalleryApiController::class, 'imageGallery']
    );

    Route::get(
        '/v1/video-gallery',
        [GalleryApiController::class, 'videoGallery']
    );

    Route::get(
        '/v1/outdoor-events',
        [GalleryApiController::class, 'outdoorEvents']
    );

    Route::get(
        '/v1/cakes',
        [CakeApiController::class, 'cakes']
    );

     Route::get(
        '/v1/branches',
        [BranchApiController::class, 'branches']
    );

    Route::get(
        '/v1/cafe-menus',
        [CafeMenuApiController::class, 'cafeMenus']
    );

    Route::get(
        '/v1/rentals',
        [RentalApiController::class, 'rentals']
    );

    Route::get(
        '/v1/balloon-decorations',
        [BalloonDecorationApiController::class, 'balloonDecorations']
    );

    Route::get(
        '/v1/birthday-packages',
        [BirthdayPackageApiController::class, 'birthdayPackages']
    );

    Route::get(
        '/v1/food-menus',
        [FoodMenuApiController::class, 'foodMenus']
    );


});
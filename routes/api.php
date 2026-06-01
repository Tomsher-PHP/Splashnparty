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
use App\Http\Controllers\Api\RentalApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::get(
        '/image-gallery',
        [GalleryApiController::class, 'imageGallery']
    );

    Route::get(
        '/video-gallery',
        [GalleryApiController::class, 'videoGallery']
    );

    Route::get(
        '/outdoor-events',
        [GalleryApiController::class, 'outdoorEvents']
    );

    Route::get(
        '/cakes',
        [CakeApiController::class, 'cakes']
    );

     Route::get(
        '/branches',
        [BranchApiController::class, 'branches']
    );

    Route::get(
        '/cafe-menus',
        [CafeMenuApiController::class, 'cafeMenus']
    );

    Route::get(
        '/rentals',
        [RentalApiController::class, 'rentals']
    );

    Route::get(
        '/balloon-decorations',
        [BalloonDecorationApiController::class, 'balloonDecorations']
    );

    Route::get(
        '/birthday-packages',
        [BirthdayPackageApiController::class, 'birthdayPackages']
    );

    Route::get(
        '/food-menus',
        [FoodMenuApiController::class, 'foodMenus']
    );

    


});

Route::get(
    '/party-extras',
    [PartyExtrasApiController::class, 'partyExtras']
);

Route::get(
    '/general-access',
    [GeneralAccessApiController::class, 'generalAccess']
);

Route::get('/packages', [PackageApiController::class, 'index']);
Route::get('/packages/{id}', [PackageApiController::class, 'show']);
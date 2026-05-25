<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GalleryApiController;

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

});
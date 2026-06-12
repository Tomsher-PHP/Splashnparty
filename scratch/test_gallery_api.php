<?php

// Bootstrapping Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ImageGallery;
use Illuminate\Support\Facades\DB;

// Ensure we have an image gallery
$gallery = ImageGallery::first();
if (!$gallery) {
    // Let's create one temporarily inside a transaction
}

DB::beginTransaction();

try {
    if (!$gallery) {
        $gallery = ImageGallery::create([
            'category_name' => 'Test Image Gallery',
            'slug' => 'test-image-gallery',
            'images' => ['uploads/gallery/uCpdxUVvj6n7tTM3d9dPixVVuhzZQcA8KOKl9jKz.webp'],
            'status' => 1
        ]);
    }

    echo "Testing galleryCategories:\n";
    $request = request();
    $request->merge(['type' => 'image']);
    $controller = new App\Http\Controllers\Api\GalleryApiController();
    $response = $controller->galleryCategories($request);
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        echo json_encode(json_decode($response->getContent(), true), JSON_PRETTY_PRINT) . "\n";
    }

    echo "\nTesting galleryItems:\n";
    $request->merge(['type' => 'image', 'category' => $gallery->slug]);
    $responseItems = $controller->galleryItems($request);
    if ($responseItems instanceof \Illuminate\Http\JsonResponse) {
        echo json_encode(json_decode($responseItems->getContent(), true), JSON_PRETTY_PRINT) . "\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
} finally {
    DB::rollBack();
    echo "Transaction rolled back.\n";
}

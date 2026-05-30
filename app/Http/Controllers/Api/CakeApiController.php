<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cake;

class CakeApiController extends Controller
{
    public function cakes()
{
    $limit = min(
        request('limit', 10),
        50
    );

    $query = Cake::query();

    // TITLE SEARCH
    if ($title = request('title')) {

        $query->where(
            'title',
            'like',
            '%' . $title . '%'
        );
    }

    // PRODUCT CODE SEARCH
    if ($productCode = request('product_code')) {

        $query->where(
            'product_code',
            'like',
            '%' . $productCode . '%'
        );
    }

    // STATUS FILTER
    if (request()->has('status')) {

        $query->where(
            'status',
            request('status')
        );
    }

    $cakes = $query
        ->latest()
        ->paginate($limit);

    // IMAGE URL FIX
    $cakes->getCollection()->transform(function ($cake) {

        $cake->thumbnail_image = $cake->thumbnail_image
            ? asset($cake->thumbnail_image)
            : null;

        $cake->gallery_images = collect($cake->gallery_images)
            ->map(fn ($image) => asset($image))
            ->toArray();

        return $cake;
    });

    return response()->json([

        'success' => true,

        'data' => $cakes

    ]);
}

}
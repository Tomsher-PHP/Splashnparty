<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalCategory;

class RentalApiController extends Controller
{
    public function rentals()
    {
        $query = RentalCategory::where(
            'status',
            1
        );

        // CATEGORY FILTER
        if ($categorySlug = request('category_slug')) {
            $query->where(
                'slug',
                $categorySlug
            );
        }

        if ($categoryId = request('category_id')) {
            $query->where(
                'id',
                $categoryId
            );
        }

        $categories = $query
            ->with([
                'rentalItems' => function ($q) {
                    $q->where(
                        'status',
                        1
                    )
                    ->orderBy(
                        'sort_order'
                    );
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $categories->each(function ($category) {
            $category->rentalItems->transform(
                function ($item) {
                    $item->image = $item->image
                        ? asset($item->image)
                        : null;
                    return $item;
                }
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Rental categories found.',
            'data' => $categories,
            'page_content' => \App\Models\Page::getPageContent('rental-services'),
        ]);
    }
}

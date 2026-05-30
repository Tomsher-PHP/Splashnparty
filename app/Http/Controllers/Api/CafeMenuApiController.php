<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CafeMenuCategory;

class CafeMenuApiController extends Controller
{


    public function cafeMenus()
    {
        $query = CafeMenuCategory::where(
            'status',
            1
        );

        // FILTER BY CATEGORY ID
        if ($categoryId = request('category_id')) {

            $query->where(
                'id',
                $categoryId
            );
        }

        // FILTER BY CATEGORY SLUG
        if ($categorySlug = request('category_slug')) {

            $query->where(
                'slug',
                $categorySlug
            );
        }

        $categories = $query
            ->with([
                'menus' => function ($menuQuery) {

                    $menuQuery
                        ->where('status', 1)
                        ->with('branch:id,title')
                        ->orderBy('sort_order');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $categories->transform(function ($category) {

            $category->menus->transform(function ($menu) {

                $menu->image = $menu->image
                    ? asset($menu->image)
                    : null;

                return $menu;
            });

            return $category;
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodMenu;

class FoodMenuApiController extends Controller
{


public function foodMenus()
{
    $limit = min(
        request('limit', 10),
        50
    );

    $query = FoodMenu::with('branch')
        ->where(
            'status',
            1
        );

    // TITLE FILTER
    if ($title = request('title')) {

        $query->where(
            'title',
            'like',
            '%' . $title . '%'
        );
    }

    // BRANCH FILTER
    if ($branchId = request('branch_id')) {

        $query->where(
            'branch_id',
            $branchId
        );
    }

    // TYPE FILTER (adult / kid)
    if ($type = request('type')) {

        $query->where(
            'type',
            $type
        );
    }

    // FOOD TYPE FILTER (veg / non-veg)
    if ($foodType = request('food_type')) {

        $query->where(
            'food_type',
            $foodType
        );
    }

    $menus = $query
        ->orderBy('sort_order')
        ->paginate($limit);

    $menus->getCollection()->transform(
        function ($item) {

            $item->image = $item->image
                ? asset($item->image)
                : null;

            return $item;
        }
    );

    return response()->json([
        'success' => true,
        'data' => $menus
    ]);
}
}

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

        $query = FoodMenu::where(
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

            $query->whereJsonContains(
                'branch_ids',
                (int) $branchId
            );
        }

        // TYPE FILTER
        if ($type = request('type')) {

            $query->where(
                'type',
                $type
            );
        }

        // FOOD TYPE FILTER
        if ($foodType = request('food_type')) {

            $query->where(
                'food_type',
                $foodType
            );
        }

        $menus = $query
            ->orderBy('sort_order')
            ->paginate($limit);

        // $menus->getCollection()->transform(
        //     function ($item) {

        //         $item->image = $item->image
        //             ? asset($item->image)
        //             : null;

        //         return $item;
        //     }
        // );

        $menus->getCollection()->transform(function ($item) {

            $item->image = $item->image
                ? asset($item->image)
                : null;

            $item->branches = \App\Models\Branch::whereIn(
                'id',
                $item->branch_ids ?? []
            )->get([
                'id',
                'title'
            ]);

            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Food menus retrieved successfully.',
            'data' => $menus,
            'page_content' => ($type ?? '') == 'adult'
                ? \App\Models\Page::getPageContent('adult-platters')
                : \App\Models\Page::getPageContent('kids-meal'),
            
        ]);
    }
}
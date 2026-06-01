<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::where('status', 1);

        // Filters
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->food_type) {
            $query->where('food_type', $request->food_type);
        }

        $packages = $query->orderBy('sort_order')
            ->get()
            ->map(function ($package) {

                return [
                    'id' => $package->id,
                    'branch_id' => $package->branch_id,
                    'title' => $package->title,
                    'food_type' => $package->food_type,

                    'prices' => [
                        'with_food' => [
                            'child_weekday' => $package->child_weekday_price_with_food,
                            'adult_weekday' => $package->adult_weekday_price_with_food,
                            'child_weekend' => $package->child_weekend_price_with_food,
                            'adult_weekend' => $package->adult_weekend_price_with_food,
                        ],
                        'without_food' => [
                            'child_weekday' => $package->child_weekday_price_without_food,
                            'adult_weekday' => $package->adult_weekday_price_without_food,
                            'child_weekend' => $package->child_weekend_price_without_food,
                            'adult_weekend' => $package->adult_weekend_price_without_food,
                        ],
                    ],

                    'free_adult_with_child' => (bool) $package->free_adult_with_child,

                    'start_date' => $package->start_date,
                    'end_date' => $package->end_date,

                    'days' => $package->days ?? [],

                    'status' => (bool) $package->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    public function show($id)
    {
        $package = Package::where('status', 1)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $package->id,
                'branch_id' => $package->branch_id,
                'title' => $package->title,
                'food_type' => $package->food_type,

                'prices' => [
                    'with_food' => [
                        'child_weekday' => $package->child_weekday_price_with_food,
                        'adult_weekday' => $package->adult_weekday_price_with_food,
                        'child_weekend' => $package->child_weekend_price_with_food,
                        'adult_weekend' => $package->adult_weekend_price_with_food,
                    ],
                    'without_food' => [
                        'child_weekday' => $package->child_weekday_price_without_food,
                        'adult_weekday' => $package->adult_weekday_price_without_food,
                        'child_weekend' => $package->child_weekend_price_without_food,
                        'adult_weekend' => $package->adult_weekend_price_without_food,
                    ],
                ],

                'free_adult_with_child' => (bool) $package->free_adult_with_child,

                'start_date' => $package->start_date,
                'end_date' => $package->end_date,

                'days' => $package->days ?? [],
            ]
        ]);
    }
}
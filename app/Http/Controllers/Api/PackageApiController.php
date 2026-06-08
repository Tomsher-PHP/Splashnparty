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
            'message' => 'Package details fetched successfully',
            'data' => $packages,
            'page_content' => \App\Models\Page::getPageContent('packages'),
        ]);
    }

    public function show($id)
    {
        $package = Package::where('status', 1)->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Package details fetched successfully',
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
            ],
            'page_content' => \App\Models\Page::getPageContent('packages'),
        ]);
    }

    public function getBookingPrice(Request $request)
    {
        $request->validate([
            'package_title' => 'required|string',
            'food_type' => 'required|in:with_food,without_food',
            'adult_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:0',
            'booking_date' => 'required|date',
        ]);

        $package = Package::where('title', $request->package_title)
            ->where('status', 1)
            ->first();

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        $bookingDate = \Carbon\Carbon::parse($request->booking_date);

        // Date validation
        if (
            $package->start_date &&
            $bookingDate->lt($package->start_date)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Booking date is before package start date'
            ], 422);
        }

        if (
            $package->end_date &&
            $bookingDate->gt($package->end_date)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Booking date is after package end date'
            ], 422);
        }

        $dayName = $bookingDate->format('l');

        if (
            !empty($package->days) &&
            !in_array($dayName, $package->days)
        ) {
            return response()->json([
                'success' => false,
                'message' => "Package is not available on {$dayName}"
            ], 422);
        }

        $isWeekend = in_array(
            $dayName,
            ['Friday', 'Saturday']
        );

        // Select prices
        if ($request->food_type === 'with_food') {

            $childPrice = $isWeekend
                ? $package->child_weekend_price_with_food
                : $package->child_weekday_price_with_food;

            $adultPrice = $isWeekend
                ? $package->adult_weekend_price_with_food
                : $package->adult_weekday_price_with_food;

        } else {

            $childPrice = $isWeekend
                ? $package->child_weekend_price_without_food
                : $package->child_weekday_price_without_food;

            $adultPrice = $isWeekend
                ? $package->adult_weekend_price_without_food
                : $package->adult_weekday_price_without_food;
        }

        $adultCount = (int) $request->adult_count;
        $childCount = (int) $request->child_count;

        $freeAdults = 0;

        if ($package->free_adult_with_child) {
            $freeAdults = min(
                $adultCount,
                $childCount
            );
        }

        $chargeableAdults = $adultCount - $freeAdults;

        $childTotal = $childCount * $childPrice;
        $adultTotal = $chargeableAdults * $adultPrice;

        $grandTotal = $childTotal + $adultTotal;

        return response()->json([
            'success' => true,
            'message' => 'Booking price calculated successfully',
            'data' => [
                'package_title' => $package->title,
                'food_type' => $request->food_type,
                'booking_date' => $request->booking_date,
                'day' => $dayName,
                'adult_count' => $adultCount,
                'child_count' => $childCount,
                'free_adults' => $freeAdults,
                'chargeable_adults' => $chargeableAdults,
                'adult_price' => $adultPrice,
                'child_price' => $childPrice,
                'adult_total' => $adultTotal,
                'child_total' => $childTotal,
                'grand_total' => $grandTotal,
            ]
        ]);
    }
}
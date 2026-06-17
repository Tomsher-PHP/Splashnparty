<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\SiteSetting;
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

                    'child_count_for_free_adult' => (int) $package->child_count_for_free_adult,

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
            'page_content' => \App\Models\Page::getPageContent('book-a-trip'),
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

                'child_count_for_free_adult' => (int) $package->child_count_for_free_adult,

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
            'package_id' => 'required|integer',
            'food_type' => 'required|in:with_food,without_food',
            'adult_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:0',
            'booking_date' => 'required|date',
        ]);

        try {

            $result = self::calculateBookingPrice($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Booking price calculated successfully',
                'data' => $result,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public static function calculateBookingPrice(array $data)
    {
        $package = Package::where('id', $data['package_id'])
            ->where('status', 1)
            ->first();

        if (!$package) {
            throw new \Exception('Package not found');
        }

        $bookingDate = \Carbon\Carbon::parse($data['booking_date']);

        if (
            $package->start_date &&
            $bookingDate->lt($package->start_date)
        ) {
            throw new \Exception('Booking date is before package start date');
        }

        if (
            $package->end_date &&
            $bookingDate->gt($package->end_date)
        ) {
            throw new \Exception('Booking date is after package end date');
        }

        $dayName = $bookingDate->format('l');

        if (
            !empty($package->days) &&
            !in_array($dayName, $package->days)
        ) {
            throw new \Exception("Package is not available on {$dayName}");
        }

        $isWeekend = in_array($dayName, ['Saturday','Sunday']);

        if ($data['food_type'] === 'with_food') {

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

        $adultCount = (int) $data['adult_count'];
        $childCount = (int) $data['child_count'];

        $freeAdults = 0;

        if ($package->child_count_for_free_adult && $package->child_count_for_free_adult > 0) {
            $freeAdults = min($adultCount, floor($childCount / $package->child_count_for_free_adult));
        }

        $chargeableAdults = $adultCount - $freeAdults;

        $childTotal = $childCount * $childPrice;
        $adultTotal = $chargeableAdults * $adultPrice;

        $subtotal = $childTotal + $adultTotal;


        $vatPercentage = SiteSetting::where('group', 'vat')->where('key', 'vat_percentage')->first()?->value ?? 0;

        $vat = round(($subtotal * $vatPercentage) / 100, 2);

        $totalAmount = $subtotal + $vat;

        return [
            'package_id' => $package->id,
            'branch_id' => $package->branch_id,
            'package_title' => $package->title,

            'food_type' => $data['food_type'],
            'booking_date' => $data['booking_date'],
            'day' => $dayName,

            'adult_count' => $adultCount,
            'child_count' => $childCount,

            'free_adults' => $freeAdults,
            'chargeable_adults' => $chargeableAdults,

            'adult_price' => $adultPrice,
            'child_price' => $childPrice,

            'adult_total' => $adultTotal,
            'child_total' => $childTotal,

            'subtotal' => $subtotal,

            'vat_percentage' => $vatPercentage,
            'vat' => $vat,

            'total_amount' => $totalAmount,
        ];
    }
}
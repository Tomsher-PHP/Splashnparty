<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'food_type' => 'required|in:with_food,without_food',
            'food_preference' => 'required_if:food_type,with_food|nullable|in:veg,non_veg,non-veg',
            'adult_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:0',
            'booking_date' => 'required|date',

            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'emirate' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $data = $request->all();
        $priceData = PackageApiController::calculateBookingPrice([
            'package_id'   => $data['package_id'],
            'food_type'    => $data['food_type'],
            'adult_count'  => $data['adult_count'],
            'child_count'  => $data['child_count'],
            'booking_date' => $data['booking_date'],
        ]);

        $booking = Booking::create([
            'booking_reference' => '',
            'package_id' => $priceData['package_id'],
            'branch_id'  => $priceData['branch_id'],
            'food_type' => $data['food_type'],
            'food_preference' => $data['food_type'] === 'with_food' ? ($data['food_preference'] ?? null) : null,
            'booking_date' => $data['booking_date'],
            'child_count' => $data['child_count'],
            'adult_count' => $data['adult_count'],
            'subtotal'     => $priceData['subtotal'],
            'vat'          => $priceData['vat'],
            'total_amount' => $priceData['total_amount'],
            'contact_name' => $data['contact_name'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'],
            'emirate' => $data['emirate'],
            'address' => $data['address'],
            'remarks' => $data['remarks'] ?? null,
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
        ]);

        $booking->booking_reference = 'BK-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
            'page_content' => null,
        ], 200);
    }

    public function show($id)
    {
        $booking = Booking::with([
            'package',
            'branch'
        ])
        ->where(
            'id',
            $id
        )
        ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $booking,
            'page_content' => \App\Models\Page::getPageContent('bookings'),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingApiController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        DB::beginTransaction();

        try {

            $data = $request->validated();

            $priceData = PackageApiController::calculateBookingPrice([
                'package_id'   => $data['package_id'],
                'food_type'    => $data['food_type'],
                'adult_count'  => $data['adult_count'],
                'child_count'  => $data['child_count'],
                'booking_date' => $data['booking_date'],
            ]);

            $booking = Booking::create([
                'booking_reference' => 'BK-' . strtoupper(Str::random(8)),
                'package_id' => $priceData['package_id'],
                'branch_id'  => $priceData['branch_id'],
                'food_type' => $data['food_type'],
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => [
                    'booking' => $booking,
                    'payment_url' => route(
                        'ccavenue.payment',
                        $booking->id
                    ),
                ]
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
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
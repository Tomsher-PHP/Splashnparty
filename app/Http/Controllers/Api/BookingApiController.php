<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\CcAvenueController;

class BookingApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'food_type' => 'required|in:with_food,without_food',
            'food_preference' => 'required_if:food_type,with_food|nullable|in:veg,non_veg,non-veg',
            'adult_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:1',
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
                'errors' => $validator->errors()
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
            'emirate' => $data['emirate'] ?? null,
            'address' => $data['address'] ?? null,
            'remarks' => $data['remarks'] ?? null,
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
        ]);

        
        $booking->booking_reference = 'SP' . date('Ymd-His'). $booking->id;
        $booking->save();

        // Generate CCAvenue payment payload
        $merchantId = config('services.ccavenue.merchant_id');
        $accessCode = config('services.ccavenue.access_code');
        $workingKey = config('services.ccavenue.working_key');
        $paymentUrl = config('services.ccavenue.payment_url');

        $merchantData = [
            'merchant_id' => $merchantId,
            'order_id' => $booking->booking_reference,
            'currency' => 'AED',
            'amount' => $booking->total_amount,
            'redirect_url' => route('ccavenue.success'),
            'cancel_url' => route('ccavenue.failure'),
            'billing_name' => $booking->contact_name,
            'billing_email' => $booking->email,
            'billing_tel' => $booking->phone,
        ];

        $merchantDataString = '';
        foreach ($merchantData as $key => $value) {
            $merchantDataString .= $key . '=' . $value . '&';
        }
        $merchantDataString = rtrim($merchantDataString, '&');

        $ccController = new CcAvenueController();
        $encRequest = $ccController->encrypt($merchantDataString, $workingKey);

        $url = $paymentUrl.'?command=initiateTransaction&encRequest=' . $encRequest . '&access_code=' . $accessCode;

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
            'bid' => base64_encode($booking->id),
            'payment_url'  => $url,
            'page_content' => null,
        ], 200);
    }

    public function show($id)
    {
        $id = base64_decode($id);
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
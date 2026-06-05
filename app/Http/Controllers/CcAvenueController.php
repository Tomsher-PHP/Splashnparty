<?php

// |This controller is to test the ccavenuue Url, its success and failure url.

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CcAvenueController extends Controller
{
    public function redirectToGateway(Booking $booking)
    {
        $merchantData = [
            'merchant_id' => env('CCAVENUE_MERCHANT_ID'),
            'order_id' => $booking->booking_reference,
            'currency' => 'AED',
            'amount' => $booking->total_amount,

            'redirect_url' => url('/ccavenue/success'),
            'cancel_url' => url('/ccavenue/cancel'),

            'billing_name' => $booking->contact_name,
            'billing_email' => $booking->email,
            'billing_tel' => $booking->phone,
        ];

        dd($merchantData);
    }

    public function success(Request $request)
    {
        $booking = Booking::where(
            'booking_reference',
            $request->order_id
        )->first();

        if ($booking) {

            $booking->update([
                'payment_status' => 'paid'
            ]);

            // Send email here
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function failure(Request $request)
    {
        $booking = Booking::where(
            'booking_reference',
            $request->order_id
        )->first();

        if ($booking) {

            $booking->update([
                'payment_status' => 'failed',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment failed'
        ]);
    }
}
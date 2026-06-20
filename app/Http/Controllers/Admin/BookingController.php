<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class BookingController extends Controller
{

    private function authorizeBookingPermission(
        string $permission
    ): void {

        abort_unless(
            auth()->user()?->can($permission),
            403
        );
    }
    public function index(Request $request)
    {
        $this->authorizeBookingPermission(
            'view_bookings'
        );

        $bookings = Booking::with([
            'package',
            'branch'
        ])
        ->where('payment_status', 'paid')
        ->when($request->filled('booking_reference'), function ($query) use ($request) {
            $query->where('booking_reference', 'like', '%' . $request->booking_reference . '%');
        })
        ->latest()
        ->paginate(20);

        return view(
            'bookings.index',
            compact('bookings')
        );
    }

    public function show(Booking $booking)
    {
        $this->authorizeBookingPermission(
            'view_bookings'
        );

        abort_unless($booking->payment_status === 'paid', 404);

        return view(
            'bookings.show',
            compact('booking')
        );
    }

    public function invoice(Booking $booking)
    {
        $this->authorizeBookingPermission(
            'generate_invoice'
        );

        abort_unless($booking->payment_status === 'paid', 404);

        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        
        $font_family = "'Roboto','sans-serif'";


        set_time_limit(300);


        $pdf = Pdf::loadView('bookings.invoice', [
            'booking' => $booking,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align,
        ]);
        
        return $pdf->download('Invoice-' . $booking->booking_reference . '.pdf');
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid'
        ]);

        $oldStatus = $booking->payment_status;

        $booking->update([
            'payment_status' => $request->payment_status
        ]);

        if ($request->payment_status === 'paid' && $oldStatus !== 'paid') {
            try {
                if ($booking->email) {
                    \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingInvoiceMail($booking));
                }

                $adminEmail = \App\Models\SiteSetting::where('key', 'notification_email')->value('value');
                if ($adminEmail) {
                    \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\BookingInvoiceMail($booking));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error sending manual booking confirmation emails: ' . $e->getMessage());
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Payment status updated successfully.');
    }
}
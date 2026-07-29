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

        $query = Booking::with([
            'package',
            'branch'
        ]);

        // Payment status filter (defaults to 'paid' if not specified or empty, but can be 'unpaid' or 'all')
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        } elseif (!$request->has('payment_status') || $request->payment_status === null || $request->payment_status === '') {
            // Default behavior if not filtered: show paid
            $query->where('payment_status', 'paid');
        } // if payment_status is 'all', we don't apply where('payment_status')

        // Keyword search (Reference, Name, Phone, Email)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('booking_reference', 'like', '%' . $keyword . '%')
                  ->orWhere('contact_name', 'like', '%' . $keyword . '%')
                  ->orWhere('phone', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        // Keep backwards compatibility for direct booking_reference input
        if ($request->filled('booking_reference')) {
            $query->where('booking_reference', 'like', '%' . $request->booking_reference . '%');
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Package filter
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // Reservation Date filters
        if ($request->filled('reservation_date_range')) {
            $dates = explode(' to ', $request->reservation_date_range);
            if (count($dates) == 2) {
                $query->whereDate('booking_date', '>=', $dates[0])
                      ->whereDate('booking_date', '<=', $dates[1]);
            } else {
                $query->whereDate('booking_date', $dates[0]);
            }
        } else {
            if ($request->filled('reservation_start_date')) {
                $query->whereDate('booking_date', '>=', $request->reservation_start_date);
            } elseif ($request->filled('start_date')) {
                $query->whereDate('booking_date', '>=', $request->start_date);
            }

            if ($request->filled('reservation_end_date')) {
                $query->whereDate('booking_date', '<=', $request->reservation_end_date);
            } elseif ($request->filled('end_date')) {
                $query->whereDate('booking_date', '<=', $request->end_date);
            }
        }

        // Booked At filters
        if ($request->filled('booked_date_range')) {
            $dates = explode(' to ', $request->booked_date_range);
            if (count($dates) == 2) {
                $query->whereDate('created_at', '>=', $dates[0])
                      ->whereDate('created_at', '<=', $dates[1]);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        } else {
            if ($request->filled('booked_start_date')) {
                $query->whereDate('created_at', '>=', $request->booked_start_date);
            }

            if ($request->filled('booked_end_date')) {
                $query->whereDate('created_at', '<=', $request->booked_end_date);
            }
        }

        $bookings = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $branches = \App\Models\Branch::where('status', 1)->orderBy('title')->get();
        
        $packagesQuery = \App\Models\Package::where('status', 1);
        if ($request->filled('branch_id')) {
            $packagesQuery->where('branch_id', $request->branch_id);
        }
        $packages = $packagesQuery->orderBy('title')->get();

        session(['last_booking_url' => $request->fullUrl()]);

        return view(
            'bookings.index',
            compact('bookings', 'branches', 'packages')
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
            // Create system notification
            try {
                \App\Models\SystemNotification::create([
                    'title' => 'Booking Marked as Paid',
                    'message' => "Booking reference {$booking->booking_reference} has been marked as paid manually (AED " . number_format($booking->total_amount, 2) . ").",
                    'link' => route('bookings.show', $booking->id),
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error creating system notification: ' . $e->getMessage());
            }

            try {
                if ($booking->email) {
                    \Illuminate\Support\Facades\Mail::to($booking->email)->send(new \App\Mail\BookingInvoiceMail($booking));
                }

                $adminEmail = \App\Models\SiteSetting::where('key', 'notification_email')->value('value');
                if ($adminEmail) {
                    $mail = \Illuminate\Support\Facades\Mail::to($adminEmail);
                    $ccEmails = \App\Models\SiteSetting::getCcEmailsByKey('notification_cc_emails');
                    if (!empty($ccEmails)) {
                        $mail->cc($ccEmails);
                    }
                    $mail->send(new \App\Mail\BookingInvoiceMail($booking));
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
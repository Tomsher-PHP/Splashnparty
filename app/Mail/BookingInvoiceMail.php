<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        $font_family = "'Roboto','sans-serif'";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bookings.invoice', [
            'booking' => $this->booking,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align,
        ]);

        return $this->subject('Booking Confirmation - ' . $this->booking->booking_reference)
            ->view('emails.booking-invoice')
            ->attachData($pdf->output(), 'Invoice-' . $this->booking->booking_reference . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
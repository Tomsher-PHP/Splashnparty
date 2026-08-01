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

        // Resolve Website Logo from general settings
        $logoSetting = \App\Models\SiteSetting::where('key', 'logo')->value('value');
        $logoPath = null;
        if ($logoSetting) {
            $fullPath = storage_path('app/public/' . $logoSetting);
            if (file_exists($fullPath)) {
                $logoPath = $fullPath;
            }
        }

        if (!$logoPath) {
            $logoPath = public_path('assets/images/logo.png');
        }

        // Retrieve social links from the footer menu settings (slug = 'footer')
        $footerPage = \App\Models\Page::where('slug', 'footer')->first();
        $socialLinks = [];
        if ($footerPage && isset($footerPage->content['social_links'])) {
            $socialLinks = $footerPage->content['social_links'];
        }

        // Retrieve active venue rules to be shown in emails
        $rules = \App\Models\Rule::where('status', 1)
            ->where('show_in_email', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->subject('Booking Confirmation - ' . $this->booking->booking_reference)
            ->view('emails.booking-invoice')
            ->with([
                'logoPath' => $logoPath,
                'socialLinks' => $socialLinks,
                'rules' => $rules,
            ])
            ->attachData($pdf->output(), 'Invoice-' . $this->booking->booking_reference . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
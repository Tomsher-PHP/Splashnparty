<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminBookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
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

        return $this->subject('New Ticket Booking Received - Reference: ' . $this->booking->booking_reference)
            ->view('emails.admin-booking-notification')
            ->with([
                'logoPath' => $logoPath,
                'socialLinks' => $socialLinks,
            ]);
    }
}

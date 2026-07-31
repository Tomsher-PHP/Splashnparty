<?php

namespace App\Mail;

use App\Models\RentalEnquiry as RentalEnquiryModel;
use App\Models\SiteSetting;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalEnquiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public RentalEnquiryModel $enquiry;

    /**
     * Create a new message instance.
     */
    public function __construct(RentalEnquiryModel $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $rentalTitle = $this->enquiry->rentalItem ? $this->enquiry->rentalItem->title : 'General Rental Enquiry';
        $subject = 'New Rental Enquiry: ' . $rentalTitle;

        // Resolve Website Logo from general settings
        $logoSetting = SiteSetting::where('key', 'logo')->value('value');
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
        $footerPage = Page::where('slug', 'footer')->first();
        $socialLinks = [];
        if ($footerPage && isset($footerPage->content['social_links'])) {
            $socialLinks = $footerPage->content['social_links'];
        }

        return $this->subject($subject)
                    ->view('emails.rental_enquiry')
                    ->with([
                        'logoPath' => $logoPath,
                        'socialLinks' => $socialLinks,
                    ]);
    }
}

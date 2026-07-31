<?php

namespace App\Mail;

use App\Models\RentalEnquiry as RentalEnquiryModel;
use App\Models\SiteSetting;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class RentalEnquiryThankYouMail extends Mailable implements ShouldQueue
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
        // 1. Resolve Website Logo from general settings
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

        // Create the mailable
        $email = $this->subject('Thank you for your Rental Enquiry')
                      ->view('emails.rental_enquiry_thank_you')
                      ->with([
                          'logoPath' => $logoPath,
                          'socialLinks' => $socialLinks,
                      ]);

        // 2. Resolve Rental Items PDF from general settings
        $pdfSetting = SiteSetting::where('key', 'rental_items_pdf')->value('value');
        $pdfPath = null;
        if ($pdfSetting) {
            $fullPath = storage_path('app/public/' . $pdfSetting);
            if (file_exists($fullPath)) {
                $pdfPath = $fullPath;
            }
        }

        if ($pdfPath) {
            // Attach static PDF file from general settings
            $email->attach($pdfPath, [
                'as' => 'Rental-Items.pdf',
                'mime' => 'application/pdf',
            ]);
        } 

        return $email;
    }
}

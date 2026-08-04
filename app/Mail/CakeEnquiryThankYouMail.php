<?php

namespace App\Mail;

use App\Models\CakeEnquiry as CakeEnquiryModel;
use App\Models\SiteSetting;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CakeEnquiryThankYouMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public CakeEnquiryModel $enquiry;

    /**
     * Create a new message instance.
     */
    public function __construct(CakeEnquiryModel $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Build the message.
     */
    public function build()
    {
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

        return $this->subject('Thank you for your Cake Enquiry')
                    ->view('emails.cake_enquiry_thank_you')
                    ->with([
                        'logoPath' => $logoPath,
                        'socialLinks' => $socialLinks,
                    ]);
    }
}

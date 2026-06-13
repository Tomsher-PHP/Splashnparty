<?php

namespace App\Mail;

use App\Models\CakeEnquiry as CakeEnquiryModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CakeEnquiry extends Mailable implements ShouldQueue
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
        $cakeTitle = $this->enquiry->cake ? $this->enquiry->cake->title : 'General Cake Enquiry';
        $subject = 'New Cake Enquiry: ' . $cakeTitle;

        return $this->subject($subject)
                    ->view('emails.cake_enquiry');
    }
}

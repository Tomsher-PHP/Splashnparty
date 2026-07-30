<?php

namespace App\Mail;

use App\Models\RentalEnquiry as RentalEnquiryModel;
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

        return $this->subject($subject)
                    ->view('emails.rental_enquiry');
    }
}

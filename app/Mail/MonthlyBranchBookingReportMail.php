<?php

namespace App\Mail;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class MonthlyBranchBookingReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $monthDisplay;
    public $branchData;
    public $grandTotals;
    public $pdfContent;
    public $excelContent;

    public function __construct(string $monthDisplay, array $branchData, array $grandTotals, ?string $pdfContent = null, ?string $excelContent = null)
    {
        $this->monthDisplay = $monthDisplay;
        $this->branchData = $branchData;
        $this->grandTotals = $grandTotals;
        $this->pdfContent = $pdfContent;
        $this->excelContent = $excelContent;
    }

    public function build()
    {
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

        $footerPage = Page::where('slug', 'footer')->first();
        $socialLinks = [];
        if ($footerPage && isset($footerPage->content['social_links'])) {
            $socialLinks = $footerPage->content['social_links'];
        }

        $mail = $this->subject('Monthly Branchwise Booking Report - ' . $this->monthDisplay)
            ->view('emails.monthly_branch_booking_report')
            ->with([
                'logoPath' => $logoPath,
                'socialLinks' => $socialLinks,
                'monthDisplay' => $this->monthDisplay,
                'branchData' => $this->branchData,
                'grandTotals' => $this->grandTotals,
            ]);

        // if ($this->pdfContent) {
        //     $pdfFilename = 'Monthly-Branchwise-Booking-Report-' . Str::slug($this->monthDisplay) . '.pdf';
        //     $mail->attachData($this->pdfContent, $pdfFilename, [
        //         'mime' => 'application/pdf',
        //     ]);
        // }

        if ($this->excelContent) {
            $excelFilename = 'Monthly-Branchwise-Booking-Report-' . Str::slug($this->monthDisplay) . '.xlsx';
            $mail->attachData($this->excelContent, $excelFilename, [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        return $mail;
    }
}

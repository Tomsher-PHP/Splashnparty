<?php

namespace App\Mail;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class DailyBranchBookingReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dateDisplay;
    public $branchData;
    public $grandTotals;
    public $excelContent;

    public function __construct(string $dateDisplay, array $branchData, array $grandTotals, ?string $excelContent = null)
    {
        $this->dateDisplay = $dateDisplay;
        $this->branchData = $branchData;
        $this->grandTotals = $grandTotals;
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

        $mail = $this->subject('Daily Branchwise Booking Details - ' . $this->dateDisplay)
            ->view('emails.daily_branch_booking_report')
            ->with([
                'logoPath' => $logoPath,
                'socialLinks' => $socialLinks,
                'dateDisplay' => $this->dateDisplay,
                'branchData' => $this->branchData,
                'grandTotals' => $this->grandTotals,
            ]);

        if ($this->excelContent) {
            $filename = 'Daily-Branchwise-Booking-Report-' . Str::slug($this->dateDisplay) . '.xlsx';
            $mail->attachData($this->excelContent, $filename, [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        return $mail;
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\DailyBranchBookingReportMail;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\SiteSetting;
use App\Services\BookingReportExportService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Test cron Using below command with a previous date
 * php artisan reports:send-daily-branch-bookings --date=2026-09-21
 */
class SendDailyBranchBookingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-daily-branch-bookings {--date= : Target date YYYY-MM-DD (defaults to previous day)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the completed previous-day branchwise booking summary email with multi-sheet XLSX attachment.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateInput = $this->option('date');

        /*
         * If a date is provided manually using --date,
         * use that date.
         *
         * Otherwise, use yesterday because the automatic
         * report runs shortly after midnight and should
         * contain the complete previous day's bookings.
         */
        $targetDate = $dateInput
            ? Carbon::parse($dateInput)
            : Carbon::yesterday();

        $dateFormatted = $targetDate->format('Y-m-d');
        $dateDisplay = $targetDate->format('d M Y (l)');

        $this->info("Generating Daily Branchwise Booking Report for {$dateDisplay}...");

        $branches = Branch::where('status', 1)->orderBy('title')->get();

        $branchData = [];
        $grandTotals = [
            'total_bookings' => 0,
            'paid_bookings' => 0,
            'unpaid_bookings' => 0,
            'total_revenue' => 0.0,
            'total_kids' => 0,
            'total_adults' => 0,
        ];

        foreach ($branches as $branch) {
            /*
             * Get bookings for the target date.
             *
             * Existing business logic is preserved:
             * a booking is included when either its booking_date
             * OR its created_at date matches the report date.
             */
            $bookings = Booking::with('package')
                ->where('branch_id', $branch->id)
                ->where(function ($q) use ($dateFormatted) {
                    $q->whereDate('booking_date', $dateFormatted)
                      ->orWhereDate('created_at', $dateFormatted);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $paidBookings = $bookings->where(
                'payment_status',
                'paid'
            );

            $unpaidBookings = $bookings->where(
                'payment_status',
                '!=',
                'paid'
            );

            $revenue = $paidBookings->sum('total_amount');
            $kids = $bookings->sum('child_count');
            $adults = $bookings->sum('adult_count');

            $branchData[] = [
                'branch' => $branch,
                'bookings' => $bookings,
                'total_count' => $bookings->count(),
                'paid_count' => $paidBookings->count(),
                'unpaid_count' => $unpaidBookings->count(),
                'total_revenue' => $revenue,
                'total_kids' => $kids,
                'total_adults' => $adults,
            ];

            $grandTotals['total_bookings'] += $bookings->count();
            $grandTotals['paid_bookings'] += $paidBookings->count();
            $grandTotals['unpaid_bookings'] += $unpaidBookings->count();
            $grandTotals['total_revenue'] += $revenue;
            $grandTotals['total_kids'] += $kids;
            $grandTotals['total_adults'] += $adults;
        }

        /*
         * Generate Multi-Sheet XLSX.
         * Each branch will have its own sheet.
         */
        $excelContent = BookingReportExportService::generateBranchwiseXlsx(
            $branchData
        );

        /*
         * Get recipient email.
         */
        $recipientEmail = SiteSetting::where('key', 'report_notification_email')->value('value')
            ?: SiteSetting::where(
                'key',
                'notification_email'
            )->value('value');

        if (!$recipientEmail) {
            $this->error(
                'No recipient notification email configured in SiteSettings.'
            );

            Log::error(
                'SendDailyBranchBookingReport: No recipient notification email configured.'
            );

            return Command::FAILURE;
        }

        /*
         * Get CC emails.
         */
        $ccEmails = SiteSetting::getCcEmailsByKey(
            'report_cc_emails'
        );

        if (empty($ccEmails)) {
            $ccEmails = SiteSetting::getCcEmailsByKey(
                'notification_cc_emails'
            );
        }

        try {
            /*
             * Pass the report date to the Mailable.
             *
             * This ensures the email subject/body can use
             * the same date as the report data.
             */
            $mailable = new DailyBranchBookingReportMail(
                $dateDisplay,
                $branchData,
                $grandTotals,
                $excelContent
            );

            $mail = Mail::to($recipientEmail);

            if (!empty($ccEmails)) {
                $mail->cc($ccEmails);
            }

            $mail->send($mailable);

            $this->info(
                "Daily branchwise booking report for {$dateDisplay} "
                . "successfully sent to {$recipientEmail}."
            );

            Log::info(
                "Daily branchwise booking report sent to {$recipientEmail} "
                . "for date {$dateFormatted}."
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error(
                'Failed to send daily booking report: '
                . $e->getMessage()
            );

            Log::error(
                'Failed to send daily booking report: '
                . $e->getMessage(),
                [
                    'exception' => $e,
                ]
            );

            return Command::FAILURE;
        }
    }
}

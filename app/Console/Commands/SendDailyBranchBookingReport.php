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
 * Test cron using a specific booking date:
 *
 * php artisan reports:send-daily-branch-bookings --date=2026-09-21
 */
class SendDailyBranchBookingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-daily-branch-bookings
                            {--date= : Target booking date YYYY-MM-DD (defaults to previous day)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the previous day branchwise booking report based on booking date with multi-sheet XLSX attachment.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*
         * If a date is provided manually using --date,
         * use that date.
         *
         * Otherwise, use yesterday.
         *
         * The automatic cron runs at 12:05 AM and therefore
         * should generate the report for the previous
         * completed calendar day's booking_date.
         *
         * Example:
         *
         * Cron:
         * 2026-09-26 12:05 AM
         *
         * Report date:
         * 2026-09-25
         *
         * Booking:
         * created_at   = 2026-09-21
         * booking_date = 2026-09-25
         *
         * This booking will be included in the
         * 25 Sep report.
         */
        $dateInput = $this->option('date');

        $targetDate = $dateInput
            ? Carbon::parse($dateInput)
            : Carbon::yesterday();

        $dateFormatted = $targetDate->format('Y-m-d');
        $dateDisplay = $targetDate->format('d M Y (l)');

        $this->info(
            "Generating Daily Branchwise Booking Report for {$dateDisplay}..."
        );

        /*
         * Get active branches.
         */
        $branches = Branch::where('status', 1)
            ->orderBy('title')
            ->get();

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
             * Get bookings for the target BOOKING DATE.
             *
             * IMPORTANT:
             * The report is based only on booking_date.
             *
             * Do NOT use created_at to determine whether
             * a booking belongs to this report.
             *
             * Example:
             *
             * created_at   = 2026-09-21
             * booking_date = 2026-09-25
             *
             * This booking belongs to the 25 Sep report,
             * regardless of when it was created.
             */
            $bookings = Booking::with('package')
                ->where('branch_id', $branch->id)
                ->whereDate('booking_date', $dateFormatted)
                ->orderBy('created_at', 'desc')
                ->get();

            /*
             * Separate paid and unpaid bookings.
             */
            $paidBookings = $bookings->where(
                'payment_status',
                'paid'
            );

            $unpaidBookings = $bookings->where(
                'payment_status',
                '!=',
                'paid'
            );

            /*
             * Calculate branch totals.
             */
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

            /*
             * Update grand totals.
             */
            $grandTotals['total_bookings'] += $bookings->count();
            $grandTotals['paid_bookings'] += $paidBookings->count();
            $grandTotals['unpaid_bookings'] += $unpaidBookings->count();
            $grandTotals['total_revenue'] += $revenue;
            $grandTotals['total_kids'] += $kids;
            $grandTotals['total_adults'] += $adults;
        }

        /*
         * Generate multi-sheet XLSX.
         *
         * Each active branch will have its own sheet.
         */
        $excelContent = BookingReportExportService::generateBranchwiseXlsx(
            $branchData
        );

        /*
         * Get recipient email.
         *
         * First use report_notification_email.
         * If it is not configured, fall back to notification_email.
         */
        $recipientEmail = SiteSetting::where(
            'key',
            'report_notification_email'
        )->value('value')
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
         *
         * First use report_cc_emails.
         * If none are configured, fall back to notification_cc_emails.
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
             * Pass the booking date to the Mailable.
             *
             * This ensures the email subject and body
             * use the same booking date as the report data.
             *
             * Example:
             *
             * Daily Branch wise Booking Details -
             * 25 Sep 2026 (Friday)
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

            /*
             * Send the report.
             */
            $mail->send($mailable);

            $this->info(
                "Daily branchwise booking report for {$dateDisplay} "
                . "successfully sent to {$recipientEmail}."
            );

            Log::info(
                "Daily branchwise booking report sent to {$recipientEmail} "
                . "for booking date {$dateFormatted}."
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
                    'booking_date' => $dateFormatted,
                ]
            );

            return Command::FAILURE;
        }
    }
}

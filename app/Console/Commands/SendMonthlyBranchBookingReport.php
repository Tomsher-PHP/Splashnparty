<?php

namespace App\Console\Commands;

use App\Mail\MonthlyBranchBookingReportMail;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\SiteSetting;
use App\Services\BookingReportExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMonthlyBranchBookingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-monthly-branch-bookings {--month= : Target month YYYY-MM (defaults to previous month)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly branchwise booking summary email, PDF, and multi-sheet XLSX report on 1st of every month for previous month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monthInput = $this->option('month');
        if ($monthInput) {
            $targetDate = Carbon::parse($monthInput . '-01');
        } else {
            $targetDate = Carbon::now()->subMonth();
        }

        $startDate = $targetDate->copy()->startOfMonth()->format('Y-m-d 00:00:00');
        $endDate = $targetDate->copy()->endOfMonth()->format('Y-m-d 23:59:59');
        $monthDisplay = $targetDate->format('F Y');

        $this->info("Generating Monthly Branchwise Booking Report for {$monthDisplay} ({$startDate} to {$endDate})...");

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
            $bookings = Booking::with('package')
                ->where('branch_id', $branch->id)
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('booking_date', [substr($startDate, 0, 10), substr($endDate, 0, 10)])
                      ->orWhereBetween('created_at', [$startDate, $endDate]);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $paidBookings = $bookings->where('payment_status', 'paid');
            $unpaidBookings = $bookings->where('payment_status', '!=', 'paid');

            $revenue = $paidBookings->sum('total_amount');
            $kids = $bookings->sum('child_count');
            $adults = $bookings->sum('adult_count');

            // Package breakdown
            $packageBreakdown = [];
            foreach ($paidBookings as $b) {
                $pkgName = $b->package->title ?? 'Custom/Standard';
                if (!isset($packageBreakdown[$pkgName])) {
                    $packageBreakdown[$pkgName] = [
                        'count' => 0,
                        'revenue' => 0.0,
                    ];
                }
                $packageBreakdown[$pkgName]['count']++;
                $packageBreakdown[$pkgName]['revenue'] += $b->total_amount;
            }

            $branchData[] = [
                'branch' => $branch,
                'bookings' => $bookings,
                'package_breakdown' => $packageBreakdown,
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

        // Generate PDF
        $pdfContent = null;
        try {
            set_time_limit(300);
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

            $pdf = Pdf::loadView('emails.pdf.monthly_branch_booking_report_pdf', [
                'monthDisplay' => $monthDisplay,
                'branchData' => $branchData,
                'grandTotals' => $grandTotals,
                'logoPath' => $logoPath,
            ]);
            $pdfContent = $pdf->output();
        } catch (\Exception $e) {
            $this->warn('Could not generate PDF attachment: ' . $e->getMessage());
            Log::warning('SendMonthlyBranchBookingReport: PDF generation warning: ' . $e->getMessage());
        }

        // Generate Multi-Sheet XLSX (Each branch in a separate sheet, no overall summary sheet)
        $excelContent = BookingReportExportService::generateBranchwiseXlsx($branchData);

        // Get recipients
        $recipientEmail = SiteSetting::where('key', 'report_notification_email')->value('value')
            ?: SiteSetting::where('key', 'notification_email')->value('value');

        if (!$recipientEmail) {
            $this->error('No recipient notification email configured in SiteSettings.');
            Log::error('SendMonthlyBranchBookingReport: No recipient notification email configured.');
            return Command::FAILURE;
        }

        $ccEmails = SiteSetting::getCcEmailsByKey('report_cc_emails');
        if (empty($ccEmails)) {
            $ccEmails = SiteSetting::getCcEmailsByKey('notification_cc_emails');
        }

        try {
            $mailable = new MonthlyBranchBookingReportMail($monthDisplay, $branchData, $grandTotals, $pdfContent, $excelContent);
            $mail = Mail::to($recipientEmail);
            if (!empty($ccEmails)) {
                $mail->cc($ccEmails);
            }
            $mail->send($mailable);

            $this->info("Monthly branchwise booking report (with PDF and multi-sheet XLSX attachments) successfully sent to {$recipientEmail}.");
            Log::info("Monthly branchwise booking report sent to {$recipientEmail} for {$monthDisplay}.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send monthly booking report: ' . $e->getMessage());
            Log::error('Failed to send monthly booking report: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return Command::FAILURE;
        }
    }
}

<?php

namespace App\Services;

class BookingReportExportService
{
    /**
     * Generate multi-sheet XLSX report for branches (No overall summary sheet).
     * Each branch is placed in its own sheet tab.
     */
    public static function generateBranchwiseXlsx(array $branchData): string
    {
        $sheetsData = [];

        $headers = [
            'Booking Ref', 'Customer Name', 'Phone', 'Email', 'Package',
            'Visit Date', 'Booked At', 'Kids Count', 'Adults Count',
            'Food Type', 'Food Preference', 'Payment Status', 'Total Amount (AED)'
        ];

        foreach ($branchData as $bData) {
            $branchTitle = $bData['branch']->title ?? ('Branch ' . ($bData['branch']->id ?? ''));

            $rows = [];
            $rows[] = $headers;

            $bookings = $bData['bookings'] ?? collect();

            if ($bookings->count() > 0) {
                foreach ($bookings as $booking) {
                    $rows[] = [
                        (string) ($booking->booking_reference ?? ''),
                        (string) ($booking->contact_name ?? ''),
                        (string) ($booking->phone ?? ''),
                        (string) ($booking->email ?? ''),
                        (string) ($booking->package->title ?? 'N/A'),
                        (string) ($booking->booking_date ? $booking->booking_date->format('Y-m-d') : ''),
                        (string) ($booking->created_at ? $booking->created_at->format('Y-m-d H:i') : ''),
                        (int) $booking->child_count,
                        (int) $booking->adult_count,
                        (string) ucwords(str_replace('_', ' ', $booking->food_type ?? '')),
                        (string) ($booking->food_preference ?? ''),
                        (string) strtoupper($booking->payment_status ?? 'UNPAID'),
                        number_format((float)$booking->total_amount, 2, '.', ''),
                    ];
                }
            }

            // Branch Summary Row
            $rows[] = [
                'BRANCH TOTAL',
                $bookings->count() . ' Bookings',
                '',
                '',
                '',
                '',
                '',
                (int) ($bData['total_kids'] ?? 0),
                (int) ($bData['total_adults'] ?? 0),
                '',
                '',
                'PAID REVENUE:',
                number_format((float)($bData['total_revenue'] ?? 0), 2, '.', ''),
            ];

            $sheetsData[$branchTitle] = $rows;
        }

        return SimpleXlsxWriter::createXlsx($sheetsData);
    }
}

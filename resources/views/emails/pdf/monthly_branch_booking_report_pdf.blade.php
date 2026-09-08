<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Branchwise Booking Report - {{ $monthDisplay }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #E1005C;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .section-heading {
            font-size: 13px;
            font-weight: bold;
            color: #1D4ED8;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 8px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
        }
        .table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .table tfoot td {
            font-weight: bold;
            background-color: #e2e8f0;
            border-top: 2px solid #94a3b8;
        }
        .branch-card {
            margin-top: 15px;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }
        .branch-title {
            background-color: #f1f5f9;
            padding: 8px 10px;
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
        }
        .badge-paid {
            color: #15803d;
            font-weight: bold;
        }
        .badge-unpaid {
            color: #b91c1c;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td style="width: 70%;">
                    <div class="title">Monthly Branchwise Booking Report</div>
                    <div class="subtitle">Reporting Period: <strong>{{ $monthDisplay }}</strong></div>
                </td>
                <td style="width: 30%; text-align: right;">
                    <div style="font-size: 10px; color: #64748b;">Generated: {{ date('d M Y, H:i') }}</div>
                    <div style="font-size: 12px; font-weight: bold; color: #E1005C;">Splash 'n' Party</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Grand Executive Summary -->
    <div class="section-heading">1. Executive Summary</div>
    <table class="table">
        <thead>
            <tr>
                <th>Metric</th>
                <th style="text-align: center;">Total Bookings</th>
                
                <th style="text-align: center;">Total Guests (Kids / Adults)</th>
                <th style="text-align: right;">Total Paid Revenue (AED)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Grand Totals</strong></td>
                <td style="text-align: center;">{{ $grandTotals['total_bookings'] }}</td>
               
                <td style="text-align: center;">{{ $grandTotals['total_kids'] }} Kids / {{ $grandTotals['total_adults'] }} Adults</td>
                <td style="text-align: right; font-weight: bold; color: #E1005C;">AED {{ number_format($grandTotals['total_revenue'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Branchwise Summary Table -->
    <div class="section-heading">2. Branch Performance Comparison</div>
    <table class="table">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th style="text-align: center;">Bookings</th>
                
                <th style="text-align: center;">Kids</th>
                <th style="text-align: center;">Adults</th>
                <th style="text-align: right;">Revenue (AED)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branchData as $bData)
                <tr>
                    <td><strong>{{ $bData['branch']->title }}</strong></td>
                    <td style="text-align: center;">{{ $bData['total_count'] }}</td>
                   
                    <td style="text-align: center;">{{ $bData['total_kids'] }}</td>
                    <td style="text-align: center;">{{ $bData['total_adults'] }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($bData['total_revenue'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td style="text-align: center;">{{ $grandTotals['total_bookings'] }}</td>
                
                <td style="text-align: center;">{{ $grandTotals['total_kids'] }}</td>
                <td style="text-align: center;">{{ $grandTotals['total_adults'] }}</td>
                <td style="text-align: right;">AED {{ number_format($grandTotals['total_revenue'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Detailed Branch Breakdown -->
    {{-- <div class="section-heading">3. Detailed Branch Breakdown</div>
    @foreach($branchData as $bData)
        <div class="branch-card">
            <div class="branch-title">
                {{ $bData['branch']->title }}
                <span style="font-size: 10px; font-weight: normal; float: right; color: #64748b;">
                    Total: {{ $bData['total_count'] }} Bookings | Revenue: AED {{ number_format($bData['total_revenue'], 2) }}
                </span>
            </div>
            @if($bData['bookings']->count() > 0)
                <table class="table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Date</th>
                            <th style="text-align: center;">Guests</th>
                            
                            <th style="text-align: right;">Amount (AED)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bData['bookings'] as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_reference }}</strong></td>
                                <td>{{ $booking->contact_name }} ({{ $booking->phone }})</td>
                                <td>{{ $booking->package->title ?? 'N/A' }}</td>
                                <td>{{ $booking->booking_date ? $booking->booking_date->format('Y-m-d') : 'N/A' }}</td>
                                <td style="text-align: center;">{{ $booking->child_count }} K / {{ $booking->adult_count }} A</td>
                                
                                <td style="text-align: right;">{{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 10px; text-align: center; color: #94a3b8;">No bookings for this branch in {{ $monthDisplay }}.</div>
            @endif
        </div>
    @endforeach --}}

    <div class="footer">
        Splash 'n' Party Confidential Monthly Report &bull; Page Generated automatically
    </div>
</body>
</html>

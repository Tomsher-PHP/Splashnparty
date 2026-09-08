<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Branchwise Booking Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 30px 15px;
        }
        .container {
            max-width: 680px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(90deg, #1D4ED8 0%, #E1005C 100%);
            padding: 25px;
            text-align: center;
        }
        .header img {
            max-height: 90px;
            display: block;
            margin: 0 auto 10px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            margin: 5px 0 0 0;
        }
        .content {
            padding: 30px 25px;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
        }
        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .summary-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
        }
        .summary-table td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .summary-table tfoot td {
            font-weight: 700;
            background-color: #e2e8f0;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
        }
        .pdf-notice {
            background-color: #eff6ff;
            border-left: 4px solid #1D4ED8;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 13px;
            color: #1e40af;
        }
        /* Branch Section */
        .branch-block {
            margin-bottom: 25px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .branch-header {
            background-color: #f1f5f9;
            padding: 12px 16px;
            font-weight: 700;
            font-size: 15px;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-table td {
            padding: 8px 10px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .footer {
            background: linear-gradient(90deg, #1D4ED8 0%, #E1005C 100%);
            padding: 25px;
            text-align: center;
            color: #ffffff;
        }
        .footer p {
            font-size: 12px;
            margin: 4px 0;
            color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                @if(!empty($logoPath) && file_exists($logoPath))
                    <img src="{{ $message->embed($logoPath) }}" alt="Splash 'n' Party Logo">
                @endif
                <h1>Monthly Branchwise Booking Report</h1>
                <p>Performance analytics for <strong>{{ $monthDisplay }}</strong></p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="pdf-notice">
                    📄 <strong>PDF Attachment Included:</strong> A full printable PDF report of branchwise monthly booking performance is attached to this email.
                </div>

                <!-- Stat Cards -->
                <div class="section-title">Monthly Executive Summary</div>
                <table class="summary-table" style="margin-bottom: 25px;">
                    <tbody>
                        <tr>
                            <td style="width:33.33%; text-align:center; background:#f1f5f9; padding:15px; border-radius:8px;">
                                <div style="font-size:22px; font-weight:800; color:#1D4ED8;">{{ $grandTotals['total_bookings'] }}</div>
                                <div style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase;">Total Bookings</div>
                            </td>
                            <td style="width:33.33%; text-align:center; background:#f1f5f9; padding:15px; border-radius:8px;">
                                <div style="font-size:22px; font-weight:800; color:#E1005C;">AED {{ number_format($grandTotals['total_revenue'], 2) }}</div>
                                <div style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase;">Total Paid Revenue</div>
                            </td>
                            <td style="width:33.33%; text-align:center; background:#f1f5f9; padding:15px; border-radius:8px;">
                                <div style="font-size:22px; font-weight:800; color:#0f172a;">{{ $grandTotals['total_kids'] }} / {{ $grandTotals['total_adults'] }}</div>
                                <div style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase;">Kids / Adults Guests</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Branch Overview Table -->
                <div class="section-title">Branchwise Performance Breakdown</div>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th style="text-align: center;">Total Bookings</th>
                            
                            <th style="text-align: center;">Kids / Adults</th>
                            <th style="text-align: right;">Total Revenue (AED)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branchData as $bData)
                            <tr>
                                <td><strong>{{ $bData['branch']->title }}</strong></td>
                                <td style="text-align: center;">{{ $bData['total_count'] }}</td>
                                
                                <td style="text-align: center;">{{ $bData['total_kids'] }} / {{ $bData['total_adults'] }}</td>
                                <td style="text-align: right; font-weight:600;">{{ number_format($bData['total_revenue'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Grand Total</td>
                            <td style="text-align: center;">{{ $grandTotals['total_bookings'] }}</td>
                            
                            <td style="text-align: center;">{{ $grandTotals['total_kids'] }} / {{ $grandTotals['total_adults'] }}</td>
                            <td style="text-align: right;">AED {{ number_format($grandTotals['total_revenue'], 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Package Breakdown per Branch -->
                {{-- <div class="section-title">Branch Package Performance</div>
                @foreach($branchData as $bData)
                    <div class="branch-block">
                        <div class="branch-header">
                            {{ $bData['branch']->title }} - Package Breakdown
                        </div>
                        @if(!empty($bData['package_breakdown']))
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Package Name</th>
                                        
                                        <th style="text-align: right;">Revenue (AED)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bData['package_breakdown'] as $pkgTitle => $pkgStats)
                                        <tr>
                                            <td><strong>{{ $pkgTitle }}</strong></td>
                                            <td style="text-align: right; font-weight:600;">AED {{ number_format($pkgStats['revenue'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div style="padding: 15px; text-align: center; color: #94a3b8; font-size: 13px;">
                                No package bookings recorded for this branch in {{ $monthDisplay }}.
                            </div>
                        @endif
                    </div>
                @endforeach --}}
            </div>

            <!-- Footer -->
            <div class="footer">
                @php
                    $iconMap = [
                        'facebook' => 'https://cdn-icons-png.flaticon.com/512/5968/5968764.png',
                        'twitter' => 'https://cdn-icons-png.flaticon.com/512/3256/3256013.png',
                        'x' => 'https://cdn-icons-png.flaticon.com/512/3256/3256013.png',
                        'instagram' => 'https://cdn-icons-png.flaticon.com/512/174/174855.png',
                        'youtube' => 'https://cdn-icons-png.flaticon.com/512/1384/1384060.png',
                        'whatsapp' => 'https://cdn-icons-png.flaticon.com/512/733/733585.png',
                        'tiktok' => 'https://cdn-icons-png.flaticon.com/512/3046/3046124.png',
                        'linkedin' => 'https://cdn-icons-png.flaticon.com/512/174/174857.png',
                    ];
                @endphp
                @if(!empty($socialLinks) && is_iterable($socialLinks))
                    <div class="social-icons">
                        @foreach($socialLinks as $link)
                            @if(is_array($link) && !empty($link['link']))
                                @php
                                    $nameLower = strtolower($link['name'] ?? '');
                                    $iconUrl = $iconMap[$nameLower] ?? 'https://cdn-icons-png.flaticon.com/512/1006/1006771.png';
                                @endphp
                                <a href="{{ $link['link'] }}" target="_blank" class="social-link">
                                    <img src="{{ $iconUrl }}" alt="{{ $link['name'] ?? 'Social Link' }}">
                                </a>
                            @elseif(is_string($link) && !empty($link))
                                <a href="{{ $link }}" target="_blank" class="social-link">
                                    <img src="https://cdn-icons-png.flaticon.com/512/1006/1006771.png" alt="Social Link">
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
                <p>&copy; {{ date('Y') }} Splash 'n' Party. All rights reserved.</p>
                <p style="font-size:11px; opacity:0.8;">Automated Monthly Branchwise Booking Report</p>
            </div>
        </div>
    </div>
</body>
</html>


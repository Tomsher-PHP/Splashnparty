<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tax Invoice - {{ $booking->booking_reference }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
        }

        /* Header Styling */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .logo-section {
            width: 50%;
            text-align: left;
        }

        .logo-img {
            max-height: 75px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 5px 0;
        }

        .company-details {
            font-size: 10px;
            color: #64748b;
            line-height: 1.4;
        }

        .invoice-title-section {
            width: 50%;
            text-align: right;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 10px 0;
            letter-spacing: 1px;
        }

        /* Meta details table (Invoice #, Date, TRN) */
        .meta-details-table {
            width: 300px;
            float: right;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .meta-details-table td {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .meta-details-table td.label {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            width: 110px;
        }

        .meta-details-table td.value {
            color: #0f172a;
            text-align: right;
        }

        .clearfix {
            clear: both;
        }

        /* Billing / Customer Info Section */
        .billing-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .billing-section td {
            width: 50%;
            padding: 15px;
            vertical-align: top;
        }

        .section-heading {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .info-row {
            margin-bottom: 4px;
            font-size: 10px;
        }

        .info-row span.label {
            font-weight: bold;
            color: #64748b;
            display: inline-block;
            width: 120px;
        }

        .info-row span.value {
            color: #0f172a;
        }

        /* Items Table Styling */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #0f172a;
        }

        .items-table th.align-right {
            text-align: right;
        }

        .items-table th.align-center {
            text-align: center;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: middle;
        }

        .items-table td.align-right {
            text-align: right;
        }

        .items-table td.align-center {
            text-align: center;
        }

        .items-table tr.odd {
            background-color: #f8fafc;
        }

        /* Summary / Total Section */
        .summary-wrapper {
            width: 100%;
            margin-top: 15px;
        }

        .summary-table {
            width: 280px;
            float: right;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .summary-table tr.total-row td {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #0f172a;
        }

        .summary-table td.label {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
        }

        .summary-table td.value {
            text-align: right;
            color: #0f172a;
            font-weight: bold;
        }

        .summary-table tr.total-row td.value {
            color: #ffffff;
        }

        /* Footer Notes */
        .footer-section {
            margin-top: 60px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            color: #94a3b8;
            font-size: 9px;
        }

        .footer-thanks {
            font-size: 11px;
            color: #475569;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <!-- Left: Supplier info -->
            <td class="logo-section">
                @php
                    $logoPath = $generalSettings?->logo
                        ? storage_path('app/public/' . $generalSettings->logo)
                        : public_path('assets/images/logo.png');
                @endphp

                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" class="logo-img">
                @else
                    <div style="font-size: 24px; font-weight: bold; color: #ff4060; margin-bottom: 10px;">
                        {{ $generalSettings->site_name ?? 'Splash N Party' }}
                    </div>
                @endif
                
                <h2 class="company-name">{{ $generalSettings->site_name ?? 'Splash N Party' }}</h2>
                <div class="company-details">
                    {!! nl2br(e($generalSettings->address ?? "")) !!}<br>
                    <strong>Phone:</strong> {{ $generalSettings->phone ?? '' }}<br>
                    <strong>Email:</strong> {{ $generalSettings->email ?? '' }}<br>
                    {{-- <strong>TRN:</strong> {{ $generalSettings->trn ?? '' }} --}}
                </div>
            </td>

            <!-- Right: Tax Invoice title & Metadata -->
            <td class="invoice-title-section">
                <h1 class="invoice-title">RECEIPT </h1>
                
                <table class="meta-details-table">
                    <tr>
                        <td class="label">RECEIPT No:</td>
                        <td class="value">{{ $booking->booking_reference }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date of Issue:</td>
                        <td class="value">{{ $booking->created_at ? $booking->created_at->format('Y-m-d h:i A') : date('Y-m-d h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Booking Date:</td>
                        <td class="value">{{ $booking->booking_date ? $booking->booking_date->format('Y-m-d') : '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Place of Supply:</td>
                        <td class="value">Dubai</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Billing Details Block -->
    <table class="billing-section">
        <tr>
            <!-- Left: Client Info -->
            <td>
                <div class="section-heading">Client Details</div>
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value"><strong>{{ $booking->contact_name }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span>
                    <span class="value">{{ $booking->phone }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $booking->email ?? 'N/A' }}</span>
                </div>
            </td>
            <!-- Right: Booking details -->
            <td>
                <div class="section-heading">Supply Details</div>
                <div class="info-row">
                    <span class="label">Package:</span>
                    <span class="value">{{ optional($booking->package)->title ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Branch:</span>
                    <span class="value">{{ optional($booking->branch)->title ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Emirate:</span>
                    <span class="value">{{ $booking->emirate ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Payment Method:</span>
                    <span class="value">Card</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    @php
        try {
            $priceData = \App\Http\Controllers\Api\PackageApiController::calculateBookingPrice([
                'package_id'   => $booking->package_id,
                'food_type'    => $booking->food_type,
                'adult_count'  => $booking->adult_count,
                'child_count'  => $booking->child_count,
                'booking_date' => $booking->booking_date->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            $priceData = null;
        }

        $vatPercentage = $priceData ? $priceData['vat_percentage'] : 5;
        
        $childCount = $booking->child_count;
        $childPrice = $priceData ? $priceData['child_price'] : ($childCount > 0 ? $booking->subtotal / $childCount : 0);
        $childSubtotal = $childCount * $childPrice;
        $childVat = ($childSubtotal * $vatPercentage) / 100;
        $childTotal = $childSubtotal + $childVat;

        $adultCount = $booking->adult_count;
        $freeAdults = $priceData ? $priceData['free_adults'] : 0;
        $chargeableAdults = $priceData ? $priceData['chargeable_adults'] : $adultCount;
        $adultPrice = $priceData ? $priceData['adult_price'] : 0;
        $adultSubtotal = $chargeableAdults * $adultPrice;
        $adultVat = ($adultSubtotal * $vatPercentage) / 100;
        $adultTotal = $adultSubtotal + $adultVat;
    @endphp

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th class="align-center">Qty</th>
                <th class="align-right">Unit Price</th>
                <th class="align-right">Total (AED)</th>
            </tr>
        </thead>
        <tbody>
            @php $rowNum = 1; @endphp
            
            <!-- Child Tickets Row -->
            @if($childCount > 0)
                <tr class="{{ $rowNum % 2 === 0 ? 'even' : 'odd' }}">
                    <td class="align-center">{{ $rowNum++ }}</td>
                    <td>
                        <strong>Child Entry Ticket - {{ optional($booking->package)->title }}</strong><br>
                        <span style="font-size: 8px; color: #64748b; text-transform: capitalize;">
                            Food preference: {{ str_replace('_', ' ', $booking->food_type) }} 
                            @if($booking->food_type === 'with_food' && $booking->food_preference)
                                ({{ $booking->food_preference }})
                            @endif
                        </span>
                    </td>
                    <td class="align-center">{{ $childCount }}</td>
                    <td class="align-right">AED {{ number_format($childPrice, 2) }}</td>
                    <td class="align-right">AED {{ number_format($childSubtotal, 2) }}</td>
                </tr>
            @endif

            <!-- Adult Tickets Row -->
            @if($adultCount > 0)
                <tr class="{{ $rowNum % 2 === 0 ? 'even' : 'odd' }}">
                    <td class="align-center">{{ $rowNum++ }}</td>
                    <td>
                        <strong>Adult Entry Ticket - {{ optional($booking->package)->title }}</strong><br>
                        <span style="font-size: 8px; color: #64748b;">
                            Includes {{ $adultCount }} Adult(s) ({{ $freeAdults }} free, {{ $chargeableAdults }} chargeable)
                        </span>
                    </td>
                    <td class="align-center">{{ $chargeableAdults }}</td>
                    <td class="align-right">AED {{ number_format($adultPrice, 2) }}</td>
        
                    <td class="align-right">AED {{ number_format($adultSubtotal , 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Totals Summary Block -->
    <div class="summary-wrapper">
        <table class="summary-table">
            <tr>
                <td class="label">Gross Subtotal (Incl. VAT):</td>
                <td class="value">AED {{ number_format(($booking->subtotal+$booking->vat), 2) }}</td>
            </tr>
            {{-- <tr>
                <td class="label">VAT Amount ({{ $vatPercentage }}%):</td>
                <td class="value">AED {{ number_format($booking->vat, 2) }}</td>
            </tr> --}}
            <tr class="total-row">
                <td class="label" style="background-color:#0f172a; color:#fff;">Total Amount Payable:</td>
                <td class="value">AED {{ number_format($booking->total_amount, 2) }}</td>
            </tr>
        </table>
        <div class="clearfix"></div>
    </div>

    <!-- Footer notes -->
    <div class="footer-section">
        <div class="footer-thanks">Thank you for booking with us!</div>
        <div>This is a computer-generated Tax Invoice and does not require a physical signature.</div>
        <div style="margin-top: 5px;">Splash N Party Recreational Playground LLC</div>
        {{-- <div style="margin-top: 5px;">Splash N Party | TRN: {{ $generalSettings->trn ?? '100346387000003' }}</div> --}}
    </div>
</div>

</body>
</html>

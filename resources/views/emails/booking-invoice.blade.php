<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #1e293b;
            background-image: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 35px 30px;
            text-align: center;
            border-bottom: 3px solid #ff4060;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #94a3b8;
            font-size: 14px;
            margin: 10px 0 0 0;
        }
        .content {
            padding: 35px 30px;
        }
        .welcome-text {
            font-size: 16px;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 25px;
            color: #1e293b;
        }
        .badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #047857;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 20px;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            display: table-cell;
            width: 35%;
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
        }
        .detail-value {
            display: table-cell;
            color: #1e293b;
            font-size: 13px;
            text-align: right;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .price-table td {
            padding: 8px 0;
            font-size: 13px;
            color: #475569;
        }
        .price-table tr.total-row td {
            border-top: 1px solid #cbd5e1;
            padding-top: 12px;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .alert-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .alert-box p {
            margin: 0;
            font-size: 13px;
            color: #1d4ed8;
            line-height: 1.5;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 10px 0;
            line-height: 1.5;
        }
        .footer p:last-child {
            margin-bottom: 0;
        }
        .footer a {
            color: #ff4060;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>BOOKING CONFIRMED</h1>
            <p>Reference: {{ $booking->booking_reference }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="badge">Payment Successful</div>
            
            <p class="welcome-text">
                Dear <strong>{{ $booking->contact_name }}</strong>,<br><br>
                Thank you for choosing us! Your booking is confirmed, and your payment has been processed successfully. Please find the details of your upcoming visit below.
            </p>

            <div class="alert-box">
                <p>
                    <strong>Note:</strong> Your official Tax Invoice has been generated and attached to this email as a PDF. Please keep it for your records.
                </p>
            </div>

            <!-- Booking Details Card -->
            <div class="card">
                <h3 class="card-title">Event & Visit Details</h3>
                
                <div class="detail-row">
                    <div class="detail-label">Date of Visit:</div>
                    <div class="detail-value">
                        <strong>
                            {{ $booking->booking_date ? $booking->booking_date->format('l, F j, Y') : 'N/A' }}
                        </strong>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Package:</div>
                    <div class="detail-value">{{ optional($booking->package)->title ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Branch / Location:</div>
                    <div class="detail-value">{{ optional($booking->branch)->title ?? 'N/A' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Food Type:</div>
                    <div class="detail-value" style="text-transform: capitalize;">
                        {{ str_replace('_', ' ', $booking->food_type) }}
                        @if($booking->food_type === 'with_food' && $booking->food_preference)
                            ({{ $booking->food_preference }})
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Guests:</div>
                    <div class="detail-value">
                        {{ $booking->adult_count }} Adult(s), {{ $booking->child_count }} Child(ren)
                    </div>
                </div>
            </div>

            <!-- Price Card -->
            <div class="card">
                <h3 class="card-title">Payment Summary</h3>
                <table class="price-table">
                    <tr>
                        <td>Gross Subtotal (Excl. VAT):</td>
                        <td style="text-align: right;">AED {{ number_format($booking->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>VAT Amount:</td>
                        <td style="text-align: right;">AED {{ number_format($booking->vat, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Paid (Incl. VAT):</td>
                        <td style="text-align: right;">AED {{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
            
            <p style="font-size: 13px; line-height: 1.5; color: #475569; margin-bottom: 0;">
                If you have any questions or need to make changes to your booking, please don't hesitate to reach out to our support team.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>{{ $generalSettings->site_name ?? 'Splash N Party' }}</strong><br>
                {!! nl2br(e($generalSettings->address ?? "Al Safa 2, Street 8A, Villa 24\nJumeirah, Dubai, UAE")) !!}
            </p>
            <p>
                Phone: {{ $generalSettings->phone ?? '+971 4 388 3008' }} | Email: <a href="mailto:{{ $generalSettings->email ?? 'info@splashnparty.ae' }}">{{ $generalSettings->email ?? 'info@splashnparty.ae' }}</a>
            </p>
            <p style="font-size: 10px; color: #94a3b8; margin-top: 15px;">
                &copy; {{ date('Y') }} {{ $generalSettings->site_name ?? 'Splash N Party' }}. All rights reserved.
            </p>
        </div>
    </div>
</div>

</body>
</html>
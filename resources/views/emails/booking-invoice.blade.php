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
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(90deg, #E1005C 0%, #1D4ED8 100%);
            padding: 15px 25px;
            text-align: center;
        }
        .header img {
            max-height: 120px;
            display: block;
            margin: 0 auto;
        }
        .content {
            padding: 40px 35px;
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
        
        /* Rule styles matching design reference */
        .rule-divider {
            border-top: 2px solid #E1005C;
            margin: 30px 0 15px 0;
        }
        .rule-title {
            font-size: 14px;
            font-weight: 700;
            color: #1d4ed8; /* deep blue */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .rule-box {
            background-color: #fff5f7; /* light pink/base tint */
            border: 1px solid #fecdd3; /* soft pink border */
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 25px;
        }
        .rule-box p {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .rule-box p:last-child {
            margin-bottom: 0;
        }
        .rule-box ul, .rule-box ol {
            margin: 0;
            padding-left: 20px;
        }
        .rule-box li {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        .rule-box li:last-child {
            margin-bottom: 0;
        }

        /* Footer styles matching thankyou mails */
        .footer {
            background: linear-gradient(90deg, #E1005C 0%, #1D4ED8 100%);
            padding: 35px 30px;
            text-align: center;
            color: #ffffff;
        }
        .social-icons {
            margin-bottom: 25px;
        }
        .social-link {
            display: inline-block;
            width: 36px;
            height: 36px;
            border-radius: 18px;
            background-color: #ffffff;
            text-align: center;
            margin: 0 6px;
            vertical-align: middle;
        }
        .social-link img {
            margin-top: 9px;
            border: 0;
            display: inline-block;
        }
        .footer-address {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            max-width: 480px;
            margin: 0 auto 10px auto;
        }
        .footer-contact {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            margin: 0 auto;
        }
        .footer-contact a {
            color: #ffffff;
            text-decoration: underline;
        }
        .footer-copyright {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ $message->embed($logoPath) }}" alt="Splash N Party Logo">
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 8px; text-align: center; text-transform: uppercase; letter-spacing: 0.5px;">Booking Confirmed</h2>
            <p style="font-size: 14px; color: #64748b; margin-top: 0; margin-bottom: 25px; text-align: center; font-weight: 500;">Reference: {{ $booking->booking_reference }}</p>

            <div class="badge">Payment Successful</div>
            
            <p class="welcome-text">
                Dear <strong>{{ $booking->contact_name }}</strong>,<br><br>
                Thank you for choosing us! 
                <br>
                We are pleased to inform you that your booking is confirmed, and your payment has been processed successfully.
                <br>
                Please find the details of your upcoming visit below:
                <br>
                If you have any questions or need to make changes to your booking, please feel free to contact our support team. 

            </p>

            {{-- <div class="alert-box">
                <p>
                    <strong>Note:</strong> Your official Tax Invoice has been generated and attached to this email as a PDF. Please keep it for your records.
                </p>
            </div> --}}

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

                <div class="detail-row">
                    <div class="detail-label">Payment Method:</div>
                    <div class="detail-value">Card</div>
                </div>
            </div>

            <!-- Price Card -->
            <div class="card">
                <h3 class="card-title">Payment Summary</h3>
                <table class="price-table">
                    <tr>
                        <td>Gross Subtotal (Incl. VAT):</td>
                        <td style="text-align: right;">AED {{ number_format(($booking->subtotal+$booking->vat), 2) }}</td>
                    </tr>
                    {{-- <tr>
                        <td>VAT Amount:</td>
                        <td style="text-align: right;">AED {{ number_format($booking->vat, 2) }}</td>
                    </tr> --}}
                    <tr class="total-row">
                        <td>Total Amount Payable:</td>
                        <td style="text-align: right;">AED {{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Venue Rules List -->
            @if(!empty($rules) && $rules->count() > 0)
                @foreach($rules as $rule)
                    <div class="rule-divider"></div>
                    <h3 class="rule-title">{{ $rule->title }}</h3>
                    <div class="rule-box">
                        {!! $rule->content !!}
                    </div>
                @endforeach
            @endif
            
            <p style="font-size: 13px; line-height: 1.5; color: #475569; margin-top: 30px; margin-bottom: 0;">
                If you have any questions or need to make changes to your booking, please don't hesitate to reach out to our support team.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-icons">
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
                @if(!empty($socialLinks))
                    @foreach($socialLinks as $link)
                        @php
                            $nameLower = strtolower($link['name'] ?? '');
                            $iconUrl = $iconMap[$nameLower] ?? 'https://cdn-icons-png.flaticon.com/512/1006/1006771.png';
                        @endphp
                        @if(!empty($link['link']))
                            <a href="{{ $link['link'] }}" class="social-link" target="_blank">
                                <img src="{{ $iconUrl }}" width="18" height="18" alt="{{ $link['name'] ?? 'Social Link' }}">
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
            
            <p class="footer-address">
                <strong>{{ $generalSettings->site_name ?? 'Splash N Party' }}</strong><br>
                {!! nl2br(e($generalSettings->address ?? "Al Safa 2, Street 8A, Villa 24\nJumeirah, Dubai, UAE")) !!}
            </p>
            <p class="footer-contact">
                Phone: {{ $generalSettings->phone ?? '+971 4 388 3008' }} | Email: <a href="mailto:{{ $generalSettings->email ?? 'info@splashnparty.ae' }}">{{ $generalSettings->email ?? 'info@splashnparty.ae' }}</a>
            </p>
            
            <div class="footer-copyright">
                &copy; {{ date('Y') }} {{ $generalSettings->site_name ?? 'Splash N Party' }}. All rights reserved.
            </div>
        </div>
    </div>
</div>

</body>
</html>
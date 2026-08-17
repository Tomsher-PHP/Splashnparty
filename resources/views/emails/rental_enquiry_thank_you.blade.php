<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for your Rental Enquiry</title>
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
        .salutation {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .highlight-bar {
            background-color: #fef3c7;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 25px;
            font-size: 15px;
            font-weight: 700;
            color: #b45309;
        }
        .highlight-text {
            background-color: #fcd34d;
            padding: 2px 6px;
            border-radius: 4px;
            color: #0f172a;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .details-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: middle;
        }
        .details-table td.label {
            color: #94a3b8;
            font-weight: 500;
            width: 40%;
        }
        .details-table td.value {
            color: #0f172a;
            font-weight: 600;
            text-align: left;
        }
        .callout-bar {
            font-size: 16px;
            font-weight: 700;
            color: #c2410c;
            margin-bottom: 30px;
        }
        .divider {
            border-top: 1px dashed #e2e8f0;
            margin: 30px 0;
        }
        .help-section {
            margin-bottom: 10px;
        }
        .help-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
        }
        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .help-list li {
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 500;
        }
        .help-list a {
            color: #475569;
            text-decoration: none;
            display: inline-block;
        }
        .help-list a:hover {
            color: #0f172a;
        }
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
        .footer-note {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            max-width: 480px;
            margin: 0 auto;
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
            <div class="header">
                <img src="{{ $message->embed($logoPath) }}" alt="Splash N Party Logo">
            </div>
            
            <div class="content">
                <div class="salutation">Hi {{ $enquiry->name }}!</div>
                <div class="intro-text">
                    Thank you for contacting us! Your rental enquiry has been received. We have attached a copy of the rental items document for your reference.
                </div>
                
                <div class="highlight-bar">
                    Rental Item : <span style="color: #000 !important;">{{ $enquiry->rentalItem ? $enquiry->rentalItem->title : '' }}</span>
                </div>
                
                
                
                <div class="callout-bar">
                    We will get in touch with you shortly.
                </div>
                
                {{-- <div class="divider"></div>
                
                <div class="help-section">
                    <div class="help-title">Get help</div>
                    <ul class="help-list">
                        <li><a href="mailto:info@splashnparty.ae">→ I have questions about availability</a></li>
                        <li><a href="mailto:info@splashnparty.ae">→ Need to make changes to my enquiry</a></li>
                        <li><a href="mailto:info@splashnparty.ae">→ General inquiries</a></li>
                    </ul>
                </div> --}}
            </div>
            
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
                
                <div class="footer-note">
                    Note: Please do not reply to this email. This is an automated system notification.
                </div>
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} Splash N Party. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>

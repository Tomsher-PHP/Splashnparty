<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Website Contact Enquiry</title>
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
        .field-row {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 0;
        }
        .field-label {
            width: 160px;
            font-weight: 600;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            flex-shrink: 0;
        }
        .field-value {
            flex-grow: 1;
            color: #0f172a;
            font-size: 14px;
            font-weight: 500;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #E1005C;
            border-radius: 8px;
            padding: 24px;
            margin-top: 28px;
            border-top: 1px solid rgba(226, 232, 240, 0.5);
            border-right: 1px solid rgba(226, 232, 240, 0.5);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }
        .message-box h4 {
            margin: 0 0 12px 0;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.75px;
        }
        .message-box p {
            margin: 0;
            color: #0f172a;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
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
                <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 25px; text-align: center;">New Website Contact Submission</h2>

                <div class="field-row">
                    <div class="field-label">Enquiry About</div>
                    <div class="field-value">{{ $data['about'] ?? 'General' }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Customer Name</div>
                    <div class="field-value">{{ $data['full_name'] ?? 'N/A' }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Email Address</div>
                    <div class="field-value">
                        <a href="mailto:{{ $data['email'] }}" style="color: #1D4ED8; text-decoration: none;">{{ $data['email'] ?? 'N/A' }}</a>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">Phone Number</div>
                    <div class="field-value">
                        <a href="tel:{{ $data['phone'] }}" style="color: #1D4ED8; text-decoration: none;">{{ $data['phone'] ?? 'N/A' }}</a>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">Preferred Date</div>
                    <div class="field-value">
                        {{ !empty($data['preferred_date']) ? date('l, d M Y', strtotime($data['preferred_date'])) : 'Not Specified' }}
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">Subject</div>
                    <div class="field-value" style="font-weight: 700; color: #0f172a;">{{ $data['subject'] ?? 'No Subject' }}</div>
                </div>
                
                <div class="message-box">
                    <h4>Submitted Message Content</h4>
                    <p>{{ $data['message'] ?? 'No message text submitted.' }}</p>
                </div>
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

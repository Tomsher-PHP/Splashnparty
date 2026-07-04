<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Website Contact Enquiry</title>
    <style>
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 40px 16px;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 36px 28px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 13px;
            opacity: 0.85;
            font-weight: 500;
        }
        .content {
            padding: 32px 28px;
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
            border-left: 4px solid #7c3aed;
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
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Elegant Linear Gradient Header -->
        <div class="header">
            <h2>Splash N Party</h2>
            <p>New Website Contact Submission</p>
        </div>
        
        <!-- Main Form Data Content -->
        <div class="content">
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
                    <a href="mailto:{{ $data['email'] }}" style="color: #2563eb; text-decoration: none;">{{ $data['email'] ?? 'N/A' }}</a>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label">Phone Number</div>
                <div class="field-value">
                    <a href="tel:{{ $data['phone'] }}" style="color: #2563eb; text-decoration: none;">{{ $data['phone'] ?? 'N/A' }}</a>
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
            
            <!-- Message Content Block -->
            <div class="message-box">
                <h4>Submitted Message Content</h4>
                <p>{{ $data['message'] ?? 'No message text submitted.' }}</p>
            </div>
        </div>
        
        <!-- System Footer -->
        <div class="footer">
            This notification was generated automatically by the Splash N Party web server on {{ date('d M Y, h:i A') }}
        </div>
    </div>
</body>
</html>

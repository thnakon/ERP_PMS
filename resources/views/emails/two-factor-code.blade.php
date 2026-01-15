<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รหัสยืนยันตัวตน</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f2f2f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .header {
            padding: 40px 40px 20px;
            text-align: center;
        }

        .logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #007AFF 0%, #0056B3 100%);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 24px;
        }

        .content {
            padding: 0 40px 40px;
            text-align: center;
            color: #1c1c1e;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        p {
            font-size: 16px;
            line-height: 24px;
            color: #6e6e73;
            margin-bottom: 24px;
        }

        .code-box {
            background: linear-gradient(135deg, #007AFF 0%, #0056B3 100%);
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }

        .code {
            font-size: 36px;
            font-weight: 900;
            letter-spacing: 8px;
            color: #ffffff;
        }

        .footer {
            padding: 24px 40px;
            background-color: #fbfbfd;
            text-align: center;
            border-top: 1px solid #f2f2f7;
        }

        .footer p {
            font-size: 12px;
            color: #86868b;
            margin: 0;
        }

        .warning {
            background-color: #fff3cd;
            border-radius: 12px;
            padding: 16px;
            margin-top: 24px;
            text-align: left;
        }

        .warning p {
            font-size: 14px;
            color: #856404;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">O</div>
        </div>
        <div class="content">
            <h1>🔐 รหัสยืนยันตัวตน</h1>
            <p>สวัสดีคุณ {{ $userName }},</p>
            <p>กรุณาใช้รหัสด้านล่างเพื่อยืนยันตัวตนในการเข้าสู่ระบบ:</p>
            <div class="code-box">
                <div class="code">{{ $code }}</div>
            </div>
            <p>รหัสนี้จะหมดอายุใน <strong>5 นาที</strong></p>
            <div class="warning">
                <p><strong>⚠️ คำเตือน:</strong> อย่าแชร์รหัสนี้กับใคร ทีมงานของเราจะไม่มีวันขอรหัสจากคุณ</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2026 OBOUN ERP. All rights reserved.</p>
        </div>
    </div>
</body>

</html>

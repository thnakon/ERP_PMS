<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รหัสยืนยันการลงทะเบียน</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1c1c1e;
            margin-bottom: 16px;
            text-align: center;
        }

        p {
            color: #8e8e93;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
            text-align: center;
        }

        .code-box {
            background: linear-gradient(135deg, #007AFF, #5856D6);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
        }

        .code {
            font-size: 36px;
            font-weight: 800;
            color: white;
            letter-spacing: 8px;
            font-family: 'SF Mono', Monaco, monospace;
        }

        .note {
            background: #f5f5f7;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .note p {
            margin: 0;
            font-size: 13px;
            color: #8e8e93;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #8e8e93;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <span class="logo-text">Oboun ERP</span>
        </div>

        <h1>สวัสดีคุณ {{ $registrantName }}</h1>

        <p>กรุณาใช้รหัสด้านล่างเพื่อยืนยันการลงทะเบียนของคุณ รหัสนี้จะหมดอายุใน 15 นาที</p>

        <div class="code-box">
            <div class="code">{{ $code }}</div>
        </div>

        <div class="note">
            <p>หากคุณไม่ได้ทำการลงทะเบียน กรุณาเพิกเฉยอีเมลนี้</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Oboun ERP. All rights reserved.</p>
        </div>
    </div>
</body>

</html>

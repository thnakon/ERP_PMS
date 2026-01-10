<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f2f2f7;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
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
            text-decoration: none;
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
            letter-spacing: -0.5px;
        }

        p {
            font-size: 16px;
            line-height: 24px;
            color: #6e6e73;
            margin-bottom: 24px;
        }

        .button {
            display: inline-block;
            background-color: #007AFF;
            color: #ffffff !important;
            padding: 16px 32px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s;
            margin: 20px 0;
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

        .warning-box {
            background-color: #fff9f0;
            border-radius: 16px;
            padding: 20px;
            margin-top: 24px;
            text-align: left;
            border: 1px solid #ffcc0033;
        }

        .warning-box p {
            font-size: 14px;
            margin: 0;
            color: #b38600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">O</div>
        </div>
        <div class="content">
            <h1>Reset Your Password</h1>
            <p>Hi {{ $userName }},</p>
            <p>We received a request to reset the password for your <strong>OBOUN ERP</strong> account. Click the button
                below to choose a new one.</p>

            <a href="{{ $resetUrl }}" class="button">Reset Password</a>

            <p>This password reset link will expire in 60 minutes. If you did not request a password reset, no further
                action is required.</p>

            <div class="warning-box">
                <p><strong>Security Tip:</strong> Never share your password or reset links with anyone. Our support team
                    will never ask for your login credentials.</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2026 OBOUN ERP. All rights reserved.</p>
            <p>Efficient Pharmacy Management Solutions</p>
        </div>
    </div>
</body>

</html>

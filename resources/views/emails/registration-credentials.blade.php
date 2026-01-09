<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลสำหรับเข้าสู่ระบบ</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 520px;
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

        .success-icon {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .success-icon svg {
            width: 40px;
            height: 40px;
            color: #22c55e;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1c1c1e;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            color: #8e8e93;
            font-size: 15px;
            text-align: center;
            margin-bottom: 30px;
        }

        .info-card {
            background: #f5f5f7;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e5e5ea;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #8e8e93;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1c1c1e;
            font-family: 'SF Mono', Monaco, monospace;
        }

        .info-value.password {
            color: #007AFF;
            font-size: 16px;
        }

        .cta-button {
            display: block;
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .warning {
            background: #fef3c7;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .warning p {
            margin: 0;
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }

        .footer {
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

        <div class="success-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1>ยินดีต้อนรับสู่ Oboun ERP</h1>
        <p class="subtitle">การลงทะเบียนสำหรับ "{{ $businessName }}" สำเร็จแล้ว</p>

        <div class="info-card">
            <div class="info-row">
                <span class="info-label">ร้าน/บริษัท</span>
                <span class="info-value">{{ $businessName }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Admin Email</span>
                <span class="info-value">{{ $adminEmail }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Staff Email</span>
                <span class="info-value">{{ $staffEmail }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">รหัสผ่าน</span>
                <span class="info-value password">{{ $password }}</span>
            </div>
        </div>

        <a href="{{ config('app.url') }}/login" class="cta-button">เข้าสู่ระบบ</a>

        <div class="warning">
            <p><strong>⚠️ สำคัญ:</strong> กรุณาเปลี่ยนรหัสผ่านทันทีหลังจากเข้าสู่ระบบครั้งแรก
                เพื่อความปลอดภัยของบัญชีของคุณ</p>
        </div>

        <div class="footer">
            <p>หากมีคำถามหรือต้องการความช่วยเหลือ กรุณาติดต่อทีมสนับสนุนของเราได้ทุกเมื่อ</p>
            <p>&copy; {{ date('Y') }} Oboun ERP. All rights reserved.</p>
        </div>
    </div>
</body>

</html>

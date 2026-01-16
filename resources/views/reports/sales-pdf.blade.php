<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>รายงานยอดขาย</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Sarabun', 'TH Sarabun New', 'DejaVu Sans', sans-serif;
        }

        body {
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007AFF;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #007AFF;
            margin-bottom: 5px;
        }

        .header .period {
            font-size: 14px;
            color: #666;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #007AFF;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #E5E7EB;
        }

        .metrics-grid {
            display: table;
            width: 100%;
        }

        .metric-box {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            border: 1px solid #E5E7EB;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #1F2937;
        }

        .metric-label {
            font-size: 11px;
            color: #6B7280;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }

        th {
            background: #F3F4F6;
            font-weight: bold;
            color: #374151;
        }

        tr:nth-child(even) {
            background: #F9FAFB;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-green {
            color: #10B981;
        }

        .text-red {
            color: #EF4444;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #9CA3AF;
            font-size: 10px;
            border-top: 1px solid #E5E7EB;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>📊 รายงานยอดขาย</h1>
        <div class="period">{{ $startDate }} ถึง {{ $endDate }}</div>
        <div style="font-size: 10px; color: #9CA3AF; margin-top: 5px;">
            สร้างเมื่อ: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">📈 สรุปยอดขาย</div>
        <div class="metrics-grid">
            <div class="metric-box">
                <div class="metric-value">฿{{ number_format($metrics['net_sales'], 2) }}</div>
                <div class="metric-label">ยอดขายสุทธิ</div>
            </div>
            <div class="metric-box">
                <div class="metric-value text-green">฿{{ number_format($metrics['gross_profit'], 2) }}</div>
                <div class="metric-label">กำไรขั้นต้น</div>
            </div>
            <div class="metric-box">
                <div class="metric-value">{{ $metrics['transaction_count'] }}</div>
                <div class="metric-label">รายการขาย</div>
            </div>
            <div class="metric-box">
                <div class="metric-value">฿{{ number_format($metrics['average_basket'], 2) }}</div>
                <div class="metric-label">ยอดเฉลี่ย/บิล</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">🏆 สินค้าขายดี Top 10</div>
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="50">อันดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th class="text-right" width="80">จำนวน</th>
                    <th class="text-right" width="100">ยอดขาย</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topProducts as $index => $product)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td class="text-right">{{ number_format($product['total_quantity']) }}</td>
                        <td class="text-right">฿{{ number_format($product['total_sales'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">📦 ยอดขายตามหมวดหมู่</div>
        <table>
            <thead>
                <tr>
                    <th>หมวดหมู่</th>
                    <th class="text-right" width="80">จำนวน</th>
                    <th class="text-right" width="100">ยอดขาย</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryData as $cat)
                    <tr>
                        <td>{{ $cat['name'] }}</td>
                        <td class="text-right">{{ number_format($cat['total_quantity']) }}</td>
                        <td class="text-right">฿{{ number_format($cat['total_sales'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">👥 ยอดขายตามพนักงาน</div>
        <table>
            <thead>
                <tr>
                    <th>พนักงาน</th>
                    <th class="text-right" width="80">รายการ</th>
                    <th class="text-right" width="100">ยอดขาย</th>
                    <th class="text-right" width="100">เฉลี่ย</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staffSales as $staff)
                    <tr>
                        <td>{{ $staff['name'] }}</td>
                        <td class="text-right">{{ number_format($staff['transaction_count']) }}</td>
                        <td class="text-right">฿{{ number_format($staff['total_sales'], 2) }}</td>
                        <td class="text-right">฿{{ number_format($staff['average_sale'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Oboun ERP - ระบบบริหารจัดการร้านขายยา</p>
        <p>© {{ date('Y') }} Oboun ERP. All Rights Reserved.</p>
    </div>
</body>

</html>

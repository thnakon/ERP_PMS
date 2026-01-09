<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 40px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007AFF;
        }

        .header h1 {
            font-size: 24px;
            color: #007AFF;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .metric-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #007AFF;
        }

        .metric-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .highlight {
            background: #e8f4fd;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #999;
            font-size: 10px;
        }

        @media print {
            body {
                padding: 20px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #007AFF; color: white; border: none; border-radius: 8px; cursor: pointer;">
            🖨️ Print / Save as PDF
        </button>
    </div>

    <div class="header">
        <h1>📊 Sales Report</h1>
        <p>Period: {{ $startDate }} to {{ $endDate }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Key Metrics</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($metrics['net_sales'], 0) }}</div>
                <div class="metric-label">Net Sales</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($metrics['gross_profit'], 0) }}</div>
                <div class="metric-label">Gross Profit</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($metrics['profit_margin'], 1) }}%</div>
                <div class="metric-label">Profit Margin</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($metrics['transaction_count']) }}</div>
                <div class="metric-label">Transactions</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($metrics['average_basket'], 0) }}</div>
                <div class="metric-label">Avg Basket</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($metrics['total_discount'], 0) }}</div>
                <div class="metric-label">Total Discount</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Top 10 Best Sellers</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Product</th>
                    <th class="text-center">Qty Sold</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $index => $product)
                    <tr class="{{ $index < 3 ? 'highlight' : '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td class="text-center">{{ number_format($product['total_quantity']) }}</td>
                        <td class="text-right">฿{{ number_format($product['total_sales'], 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Sales by Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Items Sold</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categoryData as $cat)
                    <tr>
                        <td>{{ $cat['name'] }}</td>
                        <td class="text-center">{{ number_format($cat['total_quantity']) }}</td>
                        <td class="text-right">฿{{ number_format($cat['total_sales'], 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Staff Performance</h2>
        <table>
            <thead>
                <tr>
                    <th>Staff</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Total Sales</th>
                    <th class="text-right">Avg Sale</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffSales as $staff)
                    <tr>
                        <td>{{ $staff['name'] }}</td>
                        <td class="text-center">{{ number_format($staff['transaction_count']) }}</td>
                        <td class="text-right">฿{{ number_format($staff['total_sales'], 0) }}</td>
                        <td class="text-right">฿{{ number_format($staff['average_sale'], 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y, H:i') }} | Oboun ERP</p>
    </div>
</body>

</html>

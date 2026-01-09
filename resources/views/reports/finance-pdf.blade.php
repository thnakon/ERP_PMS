<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Report - {{ $startDate }} to {{ $endDate }}</title>
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
            border-bottom: 2px solid #6366F1;
        }

        .header h1 {
            font-size: 24px;
            color: #6366F1;
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
            color: #6366F1;
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

        .summary-row td {
            font-weight: bold;
            background: #f8fafa;
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
            style="padding: 10px 20px; background: #6366F1; color: white; border: none; border-radius: 8px; cursor: pointer;">
            🖨️ Print / Save as PDF
        </button>
    </div>

    <div class="header">
        <h1>💰 Finance Report</h1>
        <p>Period: {{ $startDate }} to {{ $endDate }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Profit & Loss Statement</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($pnl['net_revenue'], 2) }}</div>
                <div class="metric-label">Net Revenue</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($pnl['cogs'], 2) }}</div>
                <div class="metric-label">Cost of Goods Sold</div>
            </div>
            <div class="metric-card" style="background: #f5f3ff;">
                <div class="metric-value" style="color: #7C3AED;">฿{{ number_format($pnl['gross_profit'], 2) }}</div>
                <div class="metric-label">Gross Profit</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($pnl['gross_margin'], 1) }}%</div>
                <div class="metric-label">Gross Margin</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($pnl['transaction_count']) }}</div>
                <div class="metric-label">Transactions</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($pnl['avg_transaction'], 2) }}</div>
                <div class="metric-label">Avg Transaction</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gross Revenue</td>
                    <td class="text-right">฿{{ number_format($pnl['gross_revenue'], 2) }}</td>
                </tr>
                <tr>
                    <td>Discounts</td>
                    <td class="text-right" style="color: #EF4444;">-฿{{ number_format($pnl['total_discount'], 2) }}
                    </td>
                </tr>
                <tr class="summary-row">
                    <td>Net Revenue</td>
                    <td class="text-right">฿{{ number_format($pnl['net_revenue'], 2) }}</td>
                </tr>
                <tr>
                    <td>Cost of Goods Sold (COGS)</td>
                    <td class="text-right" style="color: #EF4444;">-฿{{ number_format($pnl['cogs'], 2) }}</td>
                </tr>
                <tr class="summary-row" style="background: #eef2ff;">
                    <td>Gross Profit</td>
                    <td class="text-right" style="color: #6366F1;">฿{{ number_format($pnl['gross_profit'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Tax Report (VAT 7%)</h2>
        <table>
            <thead>
                <tr>
                    <th>Tax Category</th>
                    <th class="text-right">Sales Amount</th>
                    <th class="text-right">Tax Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Taxable Sales (7% VAT Included)</td>
                    <td class="text-right">฿{{ number_format($taxReport['taxable_sales'], 2) }}</td>
                    <td class="text-right">฿{{ number_format($taxReport['vat_amount'], 2) }}</td>
                </tr>
                <tr>
                    <td>Exempt Sales (VAT 0%)</td>
                    <td class="text-right">฿{{ number_format($taxReport['exempt_sales'], 2) }}</td>
                    <td class="text-right">฿0.00</td>
                </tr>
                <tr class="summary-row">
                    <td>Total Output VAT</td>
                    <td></td>
                    <td class="text-right">฿{{ number_format($taxReport['total_output_vat'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Payment Methods</h2>
        <table>
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentMethods as $method)
                    <tr>
                        <td>{{ $method['label'] }}</td>
                        <td class="text-center">{{ number_format($method['count']) }}</td>
                        <td class="text-right">฿{{ number_format($method['amount'], 2) }}</td>
                        <td class="text-right">{{ number_format($method['percentage'], 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y, H:i') }} | Oboun ERP</p>
    </div>
</body>

</html>

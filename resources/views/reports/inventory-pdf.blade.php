<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report - {{ $startDate }} to {{ $endDate }}</title>
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
            border-bottom: 2px solid #34C759;
        }

        .header h1 {
            font-size: 24px;
            color: #34C759;
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
            color: #34C759;
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
            background: #f0fdf4;
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
            style="padding: 10px 20px; background: #34C759; color: white; border: none; border-radius: 8px; cursor: pointer;">
            🖨️ Print / Save as PDF
        </button>
    </div>

    <div class="header">
        <h1>📦 Inventory Report</h1>
        <p>Period: {{ $startDate }} to {{ $endDate }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Stock Valuation</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($valuation['total_cost_value'], 2) }}</div>
                <div class="metric-label">Total Cost Value</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($valuation['total_retail_value'], 2) }}</div>
                <div class="metric-label">Total Retail Value</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($valuation['potential_profit'], 2) }}</div>
                <div class="metric-label">Potential Profit</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($valuation['total_products']) }}</div>
                <div class="metric-label">Total Stock Items</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($valuation['total_skus']) }}</div>
                <div class="metric-label">Total SKUs</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($valuation['profit_margin'], 1) }}%</div>
                <div class="metric-label">Average Margin</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Risk Analysis</h2>
        <div class="metrics-grid">
            <div class="metric-card" style="background: #fef2f2;">
                <div class="metric-value" style="color: #EF4444;">
                    ฿{{ number_format($riskAnalysis['expired_value'], 2) }}</div>
                <div class="metric-label">Expired Value ({{ $riskAnalysis['expired_count'] }} items)</div>
            </div>
            <div class="metric-card" style="background: #fff7ed;">
                <div class="metric-value" style="color: #F97316;">
                    ฿{{ number_format($riskAnalysis['near_expiry_3m_value'], 2) }}</div>
                <div class="metric-label">Near Expiry 3M ({{ $riskAnalysis['near_expiry_3m_count'] }} items)</div>
            </div>
            <div class="metric-card" style="background: #fffbeb;">
                <div class="metric-value" style="color: #F59E0B;">
                    ฿{{ number_format($riskAnalysis['near_expiry_6m_value'], 2) }}</div>
                <div class="metric-label">Near Expiry 6M ({{ $riskAnalysis['near_expiry_6m_count'] }} items)</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Efficiency Metrics</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">{{ number_format($efficiency['turnover_rate'], 2) }}x</div>
                <div class="metric-label">Inv. Turnover Rate</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($efficiency['dsi'], 1) }} Days</div>
                <div class="metric-label">Days Sales of Inv. (DSI)</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">฿{{ number_format($efficiency['cogs'], 2) }}</div>
                <div class="metric-label">COGS for Period</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Stock by Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Products</th>
                    <th class="text-center">Stock Qty</th>
                    <th class="text-right">Cost Value</th>
                    <th class="text-right">Retail Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryBreakdown as $cat)
                    <tr>
                        <td>{{ $cat['name'] }}</td>
                        <td class="text-center">{{ number_format($cat['product_count']) }}</td>
                        <td class="text-center">{{ number_format($cat['total_stock']) }}</td>
                        <td class="text-right">฿{{ number_format($cat['cost_value'], 2) }}</td>
                        <td class="text-right">฿{{ number_format($cat['retail_value'], 2) }}</td>
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

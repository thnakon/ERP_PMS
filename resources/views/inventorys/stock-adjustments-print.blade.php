<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Adjustment Log</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f7;
            font-weight: 600;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .badge-addition {
            color: #34c759;
            font-weight: bold;
        }

        .badge-subtraction {
            color: #ff3b30;
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <h1>Stock Adjustment Log</h1>
        <p>Generated on: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>User</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($adjustments as $adj)
                <tr>
                    <td>{{ $adj->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $adj->product->name ?? 'Unknown' }}</td>
                    <td class="badge-{{ $adj->type }}">{{ ucfirst($adj->type) }}</td>
                    <td>{{ $adj->quantity }}</td>
                    <td>{{ $adj->reason }}</td>
                    <td>{{ $adj->user->name ?? 'Unknown' }}</td>
                    <td>{{ $adj->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

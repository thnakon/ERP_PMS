<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('barcode.print_preview') }} - {{ config('app.name') }}</title>
    <style>
        @page {
            margin: 5mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .no-print {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .no-print button {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .print-btn {
            background: #8b5cf6;
            color: white;
        }

        .print-btn:hover {
            background: #7c3aed;
        }

        .back-btn {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn:hover {
            background: #d1d5db;
        }

        .labels-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        .label {
            border: 1px dashed #ccc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5px;
            background: white;
            page-break-inside: avoid;
        }

        /* Small label: 25x15mm */
        .label-small {
            width: 25mm;
            height: 15mm;
        }

        .label-small .label-name {
            font-size: 6px;
            font-weight: bold;
            text-align: center;
            line-height: 1.1;
            max-height: 12px;
            overflow: hidden;
        }

        .label-small .label-price {
            font-size: 8px;
            font-weight: bold;
            color: #22c55e;
        }

        .label-small .label-sku {
            font-size: 5px;
            color: #666;
        }

        .label-small .label-barcode {
            font-size: 6px;
            font-family: 'Courier New', monospace;
        }

        /* Medium label: 40x25mm */
        .label-medium {
            width: 40mm;
            height: 25mm;
        }

        .label-medium .label-name {
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            max-height: 20px;
            overflow: hidden;
        }

        .label-medium .label-price {
            font-size: 12px;
            font-weight: bold;
            color: #22c55e;
            margin: 2px 0;
        }

        .label-medium .label-sku {
            font-size: 6px;
            color: #666;
        }

        .label-medium .label-barcode {
            font-size: 8px;
            font-family: 'Courier New', monospace;
        }

        .label-medium .barcode-img {
            height: 18px;
        }

        /* Large label: 50x30mm */
        .label-large {
            width: 50mm;
            height: 30mm;
        }

        .label-large .label-name {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            max-height: 24px;
            overflow: hidden;
        }

        .label-large .label-price {
            font-size: 14px;
            font-weight: bold;
            color: #22c55e;
            margin: 3px 0;
        }

        .label-large .label-sku {
            font-size: 7px;
            color: #666;
        }

        .label-large .label-barcode {
            font-size: 9px;
            font-family: 'Courier New', monospace;
        }

        .label-large .barcode-img {
            height: 22px;
        }

        .barcode-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .barcode-img {
            height: 15px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .labels-container {
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }

            .label {
                border: 1px solid #ddd;
            }
        }
    </style>
    {{-- JsBarcode for generating barcodes --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>

<body>
    <div class="no-print">
        <div>
            <h1 style="font-size: 20px; font-weight: bold; color: #1f2937;">{{ __('barcode.print_preview') }}</h1>
            <p style="color: #6b7280; font-size: 14px;">{{ count($labels) }} {{ __('barcode.total_labels') }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('barcode.labels') }}" class="back-btn">← {{ __('barcode.back_to_labels') }}</a>
            <button onclick="window.print()" class="print-btn">🖨️ {{ __('barcode.print_now') }}</button>
        </div>
    </div>

    <div class="labels-container">
        @foreach ($labels as $label)
            <div class="label label-{{ $labelSize }}">
                <div class="label-name">{{ $label['name'] }}</div>

                @if ($showPrice)
                    <div class="label-price">฿{{ number_format($label['unit_price'], 0) }}</div>
                @endif

                @if ($showSku && $label['sku'])
                    <div class="label-sku">{{ $label['sku'] }}</div>
                @endif

                @if ($showBarcode && ($label['barcode'] || $label['sku']))
                    <div class="barcode-container">
                        <svg class="barcode-img" id="barcode-{{ $loop->index }}"></svg>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($labels as $label)
                @if ($showBarcode && ($label['barcode'] || $label['sku']))
                    try {
                        JsBarcode("#barcode-{{ $loop->index }}", "{{ $label['barcode'] ?: $label['sku'] }}", {
                            format: "CODE128",
                            width: 1,
                            height: {{ $labelSize === 'small' ? 12 : ($labelSize === 'medium' ? 18 : 22) }},
                            displayValue: true,
                            fontSize: {{ $labelSize === 'small' ? 6 : ($labelSize === 'medium' ? 8 : 9) }},
                            margin: 0
                        });
                    } catch (e) {
                        console.error('Barcode error:', e);
                    }
                @endif
            @endforeach
        });
    </script>
</body>

</html>

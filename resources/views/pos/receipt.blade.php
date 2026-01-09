<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            background: #f5f5f5;
            padding: 20px;
        }

        .receipt {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            background: white;
            padding: 10mm;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .receipt-logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .receipt-logo img {
            max-width: 60px;
            max-height: 60px;
            filter: grayscale(100%) contrast(1.2);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 10px;
            color: #666;
        }

        .header-message {
            font-size: 10px;
            font-style: italic;
            margin-top: 8px;
            color: #666;
        }

        .receipt-info {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .receipt-items {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .item-row {
            margin-bottom: 5px;
        }

        .item-name {
            margin-bottom: 2px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #666;
        }

        .receipt-totals {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #000;
        }

        .receipt-footer {
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .barcode {
            text-align: center;
            margin: 10px 0;
            font-family: 'Libre Barcode 39', cursive;
            font-size: 40px;
            line-height: 1;
            white-space: nowrap;
            overflow: hidden;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt {
                box-shadow: none;
                width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        {{-- Logo --}}
        @if (($receiptSettings['receipt_show_logo'] ?? true) && !empty($storeSettings['store_logo']))
            <div class="receipt-logo">
                <img src="{{ Storage::url($storeSettings['store_logo']) }}" alt="Logo">
            </div>
        @endif

        {{-- Header --}}
        <div class="receipt-header">
            <div class="store-name">{{ $storeSettings['store_name'] ?? config('app.name', 'Pharmacy') }}</div>
            @if ($receiptSettings['receipt_show_store_info'] ?? true)
                <div class="store-info">
                    @if (!empty($storeSettings['store_address']))
                        {{ $storeSettings['store_address'] }}<br>
                    @endif
                    @if (!empty($storeSettings['store_phone']))
                        Tel: {{ $storeSettings['store_phone'] }}<br>
                    @endif
                    @if (!empty($storeSettings['store_tax_id']))
                        Tax ID: {{ $storeSettings['store_tax_id'] }}
                    @endif
                </div>
            @endif
            @if (!empty($receiptSettings['receipt_header']))
                <div class="header-message">{{ $receiptSettings['receipt_header'] }}</div>
            @endif
        </div>

        {{-- Order Info --}}
        <div class="receipt-info">
            <div class="receipt-row">
                <span>เลขที่:</span>
                <span>{{ $order->order_number }}</span>
            </div>
            <div class="receipt-row">
                <span>วันที่:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="receipt-row">
                <span>พนักงาน:</span>
                <span>{{ $order->user?->name ?? 'N/A' }}</span>
            </div>
            @if ($order->customer)
                <div class="receipt-row">
                    <span>ลูกค้า:</span>
                    <span>{{ $order->customer->name }}</span>
                </div>
            @endif
        </div>

        {{-- Items --}}
        <div class="receipt-items">
            @foreach ($order->items as $item)
                <div class="item-row">
                    <div class="item-name">{{ $item->product_name }}</div>
                    <div class="item-details">
                        <span>{{ $item->quantity }} x ฿{{ number_format($item->unit_price, 2) }}</span>
                        <span>฿{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="receipt-totals">
            <div class="total-row">
                <span>รวม:</span>
                <span>฿{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if (($order->discount_amount ?? 0) > 0 || ($order->discount ?? 0) > 0)
                <div class="total-row">
                    <span>ส่วนลด:</span>
                    <span>-฿{{ number_format($order->discount_amount ?: $order->discount, 2) }}</span>
                </div>
            @endif
            @if ($receiptSettings['receipt_show_tax'] ?? true)
                @if (($order->vat_amount ?? 0) > 0 || ($order->tax ?? 0) > 0)
                    <div class="total-row">
                        <span>VAT (7%):</span>
                        <span>฿{{ number_format($order->vat_amount ?: $order->tax, 2) }}</span>
                    </div>
                @endif
            @endif
            <div class="total-row grand-total">
                <span>ยอดสุทธิ:</span>
                <span>฿{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="total-row" style="margin-top: 5px;">
                <span>{{ strtoupper($order->payment_method) }}:</span>
                <span>฿{{ number_format($order->amount_paid, 2) }}</span>
            </div>
            @if (($order->change_amount ?? 0) > 0)
                <div class="total-row">
                    <span>เงินทอน:</span>
                    <span>฿{{ number_format($order->change_amount, 2) }}</span>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="receipt-footer">
            <div class="thank-you">{{ $receiptSettings['receipt_thank_you'] ?? 'ขอบคุณครับ!' }}</div>
            <div>{{ $receiptSettings['receipt_return_policy'] ?? 'สามารถคืนสินค้าได้ภายใน 7 วัน พร้อมใบเสร็จ' }}</div>
            @if (!empty($receiptSettings['receipt_footer']))
                <div style="margin-top: 5px;">{{ $receiptSettings['receipt_footer'] }}</div>
            @endif
            @if ($receiptSettings['receipt_show_barcode'] ?? true)
                <div class="barcode">*{{ $order->order_number }}*</div>
            @endif
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 30px; font-size: 14px; cursor: pointer; background: #007AFF; color: white; border: none; border-radius: 8px;">
            <span style="margin-right: 8px;">🖨️</span> พิมพ์ใบเสร็จ
        </button>
        <button onclick="window.close()"
            style="padding: 10px 30px; font-size: 14px; cursor: pointer; background: #8E8E93; color: white; border: none; border-radius: 8px; margin-left: 10px;">
            ปิด
        </button>
    </div>
</body>

</html>

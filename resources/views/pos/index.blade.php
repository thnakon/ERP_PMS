@extends('layouts.pos')

@section('title', __('pos.title'))

@push('styles')
    <style>
        /* POS Layout - Full Screen, No Sidebar */
        .pos-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 16px;
            height: calc(100vh - 120px);
            min-height: 550px;
        }

        /* Larger screens - wider cart */
        @media (min-width: 1280px) {
            .pos-container {
                grid-template-columns: 1fr 420px;
                gap: 20px;
                height: calc(100vh - 110px);
            }
        }

        @media (min-width: 1536px) {
            .pos-container {
                grid-template-columns: 1fr 480px;
                gap: 24px;
                height: calc(100vh - 100px);
            }
        }

        @media (min-width: 1920px) {
            .pos-container {
                grid-template-columns: 1fr 520px;
                gap: 28px;
            }
        }

        /* Product Grid */
        .pos-products {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .pos-search-bar {
            padding: 16px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #E5E7EB;
        }

        .pos-search-wrapper {
            position: relative;
            width: 100%;
        }

        .pos-search-input {
            width: 100%;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 14px 52px 14px 48px;
            font-size: 15px;
            outline: none;
            transition: all 0.2s;
        }

        .pos-search-input:focus {
            border-color: var(--ios-blue);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .pos-search-input::placeholder {
            color: #9CA3AF;
        }

        .pos-search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 20px;
            pointer-events: none;
        }

        .pos-scan-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: #f1f5f9;
            color: #6B7280;
            border: none;
            border-radius: 10px;
            width: 36px;
            height: 36px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
        }

        .pos-scan-btn:hover {
            background: var(--ios-blue);
            color: white;
        }

        .pos-scan-btn.active {
            background: #ef4444;
            color: white;
        }

        /* Camera Preview */
        .pos-camera-container {
            position: relative;
            width: 180px;
            height: 120px;
            background: #111;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pos-camera-viewport {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
        }

        .pos-camera-viewport video,
        .pos-camera-viewport canvas {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            min-width: 100% !important;
            min-height: 100% !important;
            width: auto !important;
            height: auto !important;
            object-fit: cover !important;
        }

        .pos-camera-viewport canvas.drawingBuffer {
            display: none !important;
        }

        .pos-camera-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 5;
        }

        .pos-camera-frame {
            width: 100px;
            height: 60px;
            border: 2px solid #22c55e;
            border-radius: 6px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
        }

        .pos-camera-close {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 24px;
            height: 24px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .pos-camera-close:hover {
            background: #ef4444;
        }

        .pos-category-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 12px;
        }

        .pos-category-select {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 10px 32px 10px 12px;
            font-size: 14px;
            color: #374151;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 256 256'%3E%3Cpath fill='%236B7280' d='M213.66 101.66l-80 80a8 8 0 0 1-11.32 0l-80-80a8 8 0 0 1 11.32-11.32L128 164.69l74.34-74.35a8 8 0 0 1 11.32 11.32Z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            min-width: 150px;
            transition: all 0.2s;
        }

        .pos-category-select:focus {
            border-color: var(--ios-blue);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .pos-category-select:hover {
            border-color: #9CA3AF;
        }

        /* Receipt Preview */
        .receipt-preview {
            background: #fafafa;
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .receipt-paper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .receipt-store-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .receipt-store-info {
            color: #666;
            font-size: 10px;
        }

        .receipt-info {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .receipt-items {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .receipt-item {
            margin-bottom: 8px;
        }

        .receipt-item-name {
            font-weight: 500;
        }

        .receipt-item-details {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 11px;
        }

        .receipt-totals {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .receipt-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .receipt-total-row.grand {
            font-size: 15px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 6px;
            margin-top: 6px;
        }

        .receipt-footer {
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .receipt-thank-you {
            font-size: 13px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }

        /* Payment Tabs */
        .payment-tab-active {
            background: linear-gradient(135deg, #007AFF, #0055FF);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
        }

        /* Card Tap Animation */
        .card-tap-animation {
            animation: cardPulse 2s ease-in-out infinite;
        }

        @keyframes cardPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 6px 30px rgba(139, 92, 246, 0.5);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .pos-product-grid {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            align-content: start;
        }

        .pos-product-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .pos-product-card:hover {
            background: #f1f5f9;
            border-color: var(--ios-blue);
            transform: translateY(-2px);
        }

        .pos-product-card.out-of-stock {
            opacity: 0.5;
            pointer-events: none;
        }

        .pos-product-image {
            width: 100%;
            aspect-ratio: 1;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #CBD5E1;
            margin-bottom: 8px;
            overflow: hidden;
        }

        .pos-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pos-product-name {
            font-weight: 600;
            font-size: 13px;
            color: #1F2937;
            line-height: 1.3;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pos-product-price {
            font-weight: 700;
            font-size: 15px;
            color: var(--ios-blue);
        }

        .pos-product-stock {
            font-size: 11px;
            color: #6B7280;
            margin-top: 4px;
        }

        /* Cart Panel */
        .pos-cart {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .pos-cart-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
        }

        .pos-cart-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .pos-cart-subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .pos-customer-select {
            padding: 12px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #E5E7EB;
        }

        .pos-customer-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: white;
            border: 1px dashed #CBD5E1;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pos-customer-btn:hover {
            border-color: var(--ios-blue);
            background: #EFF6FF;
        }

        .pos-customer-btn.has-customer {
            border-style: solid;
            border-color: var(--ios-green);
            background: #ECFDF5;
        }

        .pos-cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
        }

        .pos-cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 14px;
            margin-bottom: 8px;
        }

        .pos-cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .pos-cart-item-name {
            font-weight: 600;
            font-size: 14px;
            color: #1F2937;
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pos-cart-item-price {
            font-size: 13px;
            color: #6B7280;
        }

        .pos-cart-item-qty {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border-radius: 10px;
            padding: 4px;
        }

        .pos-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: none;
            background: #f1f5f9;
            color: #374151;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .pos-qty-btn:hover {
            background: var(--ios-blue);
            color: white;
        }

        .pos-qty-value {
            min-width: 28px;
            text-align: center;
            font-weight: 700;
            font-size: 14px;
        }

        .pos-cart-item-total {
            font-weight: 700;
            font-size: 15px;
            color: #1F2937;
            min-width: 70px;
            text-align: right;
        }

        .pos-cart-item-remove {
            color: #EF4444;
            cursor: pointer;
            padding: 4px;
        }

        .pos-cart-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
            padding: 40px;
        }

        .pos-cart-empty i {
            font-size: 48px;
            margin-bottom: 12px;
        }

        /* Cart Summary */
        .pos-cart-summary {
            padding: 16px;
            background: #f8fafc;
            border-top: 1px solid #E5E7EB;
        }

        .pos-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            color: #6B7280;
        }

        .pos-summary-row.total {
            font-size: 20px;
            font-weight: 700;
            color: #1F2937;
            padding-top: 8px;
            border-top: 1px dashed #CBD5E1;
        }

        /* Payment Section */
        .pos-payment {
            padding: 16px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        .pos-payment-methods {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .pos-payment-method {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 10px 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.95);
            cursor: pointer;
            transition: all 0.2s;
            font-size: 11px;
        }

        .pos-payment-method:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .pos-payment-method.active {
            background: var(--ios-blue);
            border-color: white;
            color: white;
        }

        .pos-payment-method i {
            font-size: 22px;
        }

        .pos-pay-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #34D399 0%, #10B981 100%);
            color: white;
            font-size: 17px;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        }

        .pos-pay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(16, 185, 129, 0.4);
        }

        .pos-pay-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Allergy Alert */
        .pos-allergy-alert {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 24px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            z-index: 1000;
        }

        .pos-allergy-alert-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            color: #EF4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* Quick Action Buttons */
        .pos-quick-actions {
            display: flex;
            gap: 8px;
        }

        .pos-quick-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pos-quick-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .pos-quick-btn.danger {
            background: rgba(239, 68, 68, 0.3);
        }

        .pos-quick-btn.danger:hover {
            background: rgba(239, 68, 68, 0.5);
        }
    </style>
@endpush

@section('page-title')
    {{ __('pos.title') }}
@endsection

@section('header-actions')
    <div class="flex items-center gap-4">
        {{-- Quick Stats --}}
        <div class="hidden md:flex items-center gap-4 mr-2">
            <div class="text-center px-3">
                <div class="text-lg font-bold text-gray-900">฿{{ number_format($todayStats['sales'], 2) }}</div>
                <div class="text-[10px] text-gray-500 uppercase tracking-wide">{{ __('pos.today_sales') }}</div>
            </div>
            <div class="w-px h-8 bg-gray-200"></div>
            <div class="text-center px-3">
                <div class="text-lg font-bold text-gray-900">{{ $todayStats['transactions'] }}</div>
                <div class="text-[10px] text-gray-500 uppercase tracking-wide">{{ __('pos.transactions') }}</div>
            </div>
            <div class="w-px h-8 bg-gray-200"></div>
            <div class="text-center px-3">
                <div class="text-lg font-bold text-gray-900">{{ $todayStats['items_sold'] }}</div>
                <div class="text-[10px] text-gray-500 uppercase tracking-wide">{{ __('pos.items_sold') }}</div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <button onclick="showHeldOrders()"
            class="px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-xl text-sm font-medium flex items-center gap-2 transition">
            <i class="ph ph-pause-circle"></i>
            <span class="hidden sm:inline">{{ __('pos.held_orders') }}</span>
            (<span id="heldCount">0</span>)
        </button>
        <button onclick="showRecentSales()"
            class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl text-sm font-medium flex items-center gap-2 transition">
            <i class="ph ph-receipt"></i>
            <span class="hidden sm:inline">{{ __('pos.recent_sales') }}</span>
        </button>

        @if ($currentShift)
            <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-medium flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="hidden sm:inline">{{ __('pos.shift_open') }}</span>
            </span>
        @endif
        <button onclick="showShiftModal()"
            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph ph-clock-clockwise"></i>
            <span class="hidden sm:inline">{{ __('pos.manage_shift') }}</span>
        </button>
    </div>
@endsection

@section('content')

    {{-- Main POS Layout --}}
    <div class="pos-container">
        {{-- Left: Products --}}
        <div class="pos-products">
            <div class="pos-search-bar" style="display: flex; align-items: center; gap: 12px;">
                <div class="pos-search-wrapper" style="flex: 1;">
                    <i class="ph ph-magnifying-glass pos-search-icon"></i>
                    <input type="text" id="productSearch" class="pos-search-input"
                        placeholder="{{ __('pos.search_product') }}">
                    <button class="pos-scan-btn" id="posScanBtn" onclick="togglePosCamera()" title="{{ __('pos.scan') }}">
                        <i class="ph ph-barcode" id="posScanIcon"></i>
                    </button>
                </div>
                {{-- Camera Preview Box --}}
                <div id="posCameraContainer" class="pos-camera-container" style="display: none;">
                    <div id="posCameraViewport" class="pos-camera-viewport"></div>
                    <div class="pos-camera-overlay">
                        <div class="pos-camera-frame"></div>
                    </div>
                    <button type="button" class="pos-camera-close" onclick="stopPosCamera()" title="Close">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
                <div class="pos-category-filter">
                    <select id="categoryFilter" class="pos-category-select" onchange="filterByCategory()">
                        <option value="">{{ __('pos.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->localized_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pos-product-grid" id="productGrid">
                @foreach ($products as $product)
                    <div class="pos-product-card {{ $product->stock_qty <= 0 ? 'out-of-stock' : '' }}"
                        data-category="{{ $product->category_id }}"
                        onclick="addToCart({{ json_encode([
                            'id' => $product->id,
                            'name' => $product->name,
                            'name_th' => $product->name_th,
                            'sku' => $product->sku,
                            'unit_price' => $product->unit_price,
                            'member_price' => $product->member_price,
                            'stock_qty' => $product->stock_qty,
                            'requires_prescription' => $product->requires_prescription,
                            'image_path' => $product->image_path,
                            'category_id' => $product->category_id,
                        ]) }})">
                        <div class="pos-product-image">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <i class="ph ph-pill"></i>
                            @endif
                        </div>
                        <div class="pos-product-name">{{ $product->name }}</div>
                        <div class="pos-product-price">฿{{ number_format($product->unit_price, 2) }}</div>
                        <div class="pos-product-stock">
                            @if ($product->stock_qty > 0)
                                {{ __('pos.stock') }}: {{ $product->stock_qty }}
                            @else
                                {{ __('pos.out_of_stock') }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Cart --}}
        <div class="pos-cart">
            <div class="pos-cart-header">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="pos-cart-title">{{ __('pos.current_sale') }}</div>
                        <div class="pos-cart-subtitle" id="orderNumber">{{ __('pos.new_order') }}</div>
                    </div>
                    <div class="pos-quick-actions">
                        <button class="pos-quick-btn" onclick="holdCurrentOrder()">
                            <i class="ph ph-pause"></i>
                            {{ __('pos.hold') }}
                        </button>
                        <button class="pos-quick-btn danger" onclick="clearCart()">
                            <i class="ph ph-trash"></i>
                            {{ __('pos.clear') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Customer Selection --}}
            <div class="pos-customer-select">
                <button class="pos-customer-btn" id="customerBtn" onclick="showCustomerSearch()">
                    <i class="ph ph-user-circle text-2xl text-gray-400"></i>
                    <div class="text-left flex-1">
                        <div class="text-sm font-medium text-gray-600" id="customerName">{{ __('pos.select_customer') }}
                        </div>
                        <div class="text-xs text-gray-400" id="customerInfo">{{ __('pos.walk_in_customer') }}</div>
                    </div>
                    <i class="ph ph-caret-right text-gray-400"></i>
                </button>
            </div>

            {{-- Cart Items --}}
            <div class="pos-cart-items" id="cartItems">
                <div class="pos-cart-empty" id="cartEmpty">
                    <i class="ph ph-shopping-cart"></i>
                    <div>{{ __('pos.empty_cart') }}</div>
                    <div class="text-sm mt-1">{{ __('pos.add_products') }}</div>
                </div>
            </div>

            {{-- Cart Summary --}}
            <div class="pos-cart-summary" id="cartSummary" style="display: none;">
                <div class="pos-summary-row">
                    <span>{{ __('pos.subtotal') }}</span>
                    <span id="subtotalAmount">฿0.00</span>
                </div>
                <div class="pos-summary-row" id="discountRow" style="display: none;">
                    <span>{{ __('pos.discount') }}</span>
                    <span id="discountAmount" class="text-red-500">-฿0.00</span>
                </div>
                <div class="pos-summary-row" id="vatRow">
                    <span>{{ __('pos.vat') }} (7%)</span>
                    <span id="vatAmount">฿0.00</span>
                </div>
                <div class="pos-summary-row total">
                    <span>{{ __('pos.total') }}</span>
                    <span id="totalAmount">฿0.00</span>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="pos-payment">
                <div class="pos-payment-methods">
                    <button class="pos-payment-method active" data-method="cash" onclick="selectPayment('cash')">
                        <i class="ph ph-money"></i>
                        {{ __('pos.cash') }}
                    </button>
                    <button class="pos-payment-method" data-method="card" onclick="selectPayment('card')">
                        <i class="ph ph-credit-card"></i>
                        {{ __('pos.card') }}
                    </button>
                    <button class="pos-payment-method" data-method="transfer" onclick="selectPayment('transfer')">
                        <i class="ph ph-bank"></i>
                        {{ __('pos.transfer') }}
                    </button>
                    <button class="pos-payment-method" data-method="qr" onclick="selectPayment('qr')">
                        <i class="ph ph-qr-code"></i>
                        {{ __('pos.qr') }}
                    </button>
                </div>
                <button class="pos-pay-btn" id="payBtn" onclick="processPayment()" disabled>
                    <i class="ph-bold ph-credit-card"></i>
                    {{ __('pos.pay_now') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Shift Modal --}}
    <div id="shiftModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="closeShiftModal()"></div>
    <div id="shiftModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 24rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('pos.manage_shift') }}</h2>
            <button onclick="closeShiftModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            @if ($currentShift)
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ph ph-clock text-3xl text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-lg">{{ __('pos.shift_active') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('pos.opened_at') }}:
                        {{ $currentShift->opened_at->format('H:i') }}</p>
                </div>
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between p-3 bg-gray-50 rounded-xl">
                        <span>{{ __('pos.opening_balance') }}</span>
                        <span class="font-semibold">฿{{ number_format($currentShift->opening_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between p-3 bg-gray-50 rounded-xl">
                        <span>{{ __('pos.expected_cash') }}</span>
                        <span class="font-semibold">฿{{ number_format($currentShift->expected_cash, 2) }}</span>
                    </div>
                    <div class="flex justify-between p-3 bg-gray-50 rounded-xl">
                        <span>{{ __('pos.total_sales') }}</span>
                        <span
                            class="font-semibold text-green-600">฿{{ number_format($currentShift->total_sales, 2) }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('pos.closing_balance') }}</label>
                    <input type="number" id="closingBalance" class="form-input" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('pos.notes') }}</label>
                    <textarea id="shiftNotes" class="form-input" rows="2"></textarea>
                </div>
            @else
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ph ph-clock-clockwise text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="font-bold text-lg">{{ __('pos.no_active_shift') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('pos.start_shift_message') }}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('pos.opening_balance') }}</label>
                    <input type="number" id="openingBalance" class="form-input" step="0.01" placeholder="0.00"
                        value="1000">
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeShiftModal()"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('cancel') }}
            </button>
            @if ($currentShift)
                <button type="button" onclick="closeShift()"
                    class="px-6 py-2 bg-red-500 hover:brightness-110 text-white text-sm font-medium rounded-xl transition">
                    {{ __('pos.close_shift') }}
                </button>
            @else
                <button type="button" onclick="openShift()"
                    class="px-6 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition">
                    {{ __('pos.open_shift') }}
                </button>
            @endif
        </div>
    </div>

    {{-- Customer Search Modal --}}
    <div id="customerModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="closeCustomerModal()">
    </div>
    <div id="customerModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 28rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('pos.select_customer') }}</h2>
            <button onclick="closeCustomerModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            <div class="form-group mb-4">
                <input type="text" id="customerSearchInput" class="form-input"
                    placeholder="{{ __('pos.search_customer') }}" oninput="searchCustomers(this.value)">
            </div>
            <div id="customerResults" class="space-y-2 max-h-64 overflow-y-auto">
                @foreach ($customers as $customer)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition"
                        onclick="selectCustomer({{ json_encode([
                            'id' => $customer->id,
                            'name' => $customer->name,
                            'phone' => $customer->phone,
                            'member_id' => $customer->member_id,
                            'member_type' => $customer->member_type,
                            'points' => $customer->points,
                            'allergies' => $customer->allergies,
                        ]) }})">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="ph ph-user text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-sm">{{ $customer->name }}</div>
                            <div class="text-xs text-gray-500">{{ $customer->phone ?? 'No phone' }} •
                                {{ $customer->member_type ?: 'Regular' }}</div>
                        </div>
                        @if ($customer->allergies)
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">
                                <i class="ph ph-warning"></i> Allergy
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="selectCustomer(null)"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('pos.walk_in') }}
            </button>
        </div>
    </div>

    {{-- Payment Modal --}}
    <div id="paymentModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"></div>
    <div id="paymentModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 28rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('pos.payment') }}</h2>
            <button onclick="closePaymentModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            {{-- Payment Method Tabs --}}
            <div class="flex gap-2 mb-6">
                <button type="button" onclick="setPaymentMethod('cash')" id="paymentTab-cash"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition payment-tab-active">
                    <i class="ph ph-money"></i> {{ __('pos.cash') }}
                </button>
                <button type="button" onclick="setPaymentMethod('card')" id="paymentTab-card"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition bg-gray-100 text-gray-600">
                    <i class="ph ph-credit-card"></i> {{ __('pos.card') }}
                </button>
                <button type="button" onclick="setPaymentMethod('transfer')" id="paymentTab-transfer"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition bg-gray-100 text-gray-600">
                    <i class="ph ph-bank"></i> {{ __('pos.transfer') }}
                </button>
                <button type="button" onclick="setPaymentMethod('qr')" id="paymentTab-qr"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition bg-gray-100 text-gray-600">
                    <i class="ph ph-qr-code"></i> {{ __('pos.qr') }}
                </button>
            </div>

            {{-- Amount Due (always visible) --}}
            <div class="text-center mb-6">
                <div class="text-sm text-gray-500">{{ __('pos.amount_due') }}</div>
                <div class="text-4xl font-bold text-ios-blue" id="paymentTotal">฿0.00</div>
            </div>

            {{-- Cash Payment View --}}
            <div id="cashPaymentView">
                <div class="form-group mb-4">
                    <label class="form-label">{{ __('pos.amount_received') }}</label>
                    <input type="number" id="amountReceived" class="form-input text-center text-2xl font-bold"
                        step="0.01" oninput="calculateChange()">
                </div>
                <div class="flex justify-between p-4 bg-green-50 rounded-xl mb-4">
                    <span class="font-medium text-green-700">{{ __('pos.change') }}</span>
                    <span class="font-bold text-green-700 text-xl" id="changeAmount">฿0.00</span>
                </div>
                <div class="grid grid-cols-4 gap-2" id="quickAmounts">
                    <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-sm"
                        onclick="setAmount(20)">฿20</button>
                    <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-sm"
                        onclick="setAmount(50)">฿50</button>
                    <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-sm"
                        onclick="setAmount(100)">฿100</button>
                    <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-sm"
                        onclick="setAmount(500)">฿500</button>
                    <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-sm"
                        onclick="setAmount(1000)">฿1000</button>
                    <button
                        class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-medium text-sm col-span-3"
                        onclick="setExactAmount()">{{ __('pos.exact') }}</button>
                </div>
            </div>

            {{-- Card Payment View --}}
            <div id="cardPaymentView" class="hidden">
                {{-- Order Summary --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <div class="text-xs font-semibold text-gray-500 mb-2">{{ __('pos.order_summary') }}</div>
                    <div id="cardOrderSummary" class="space-y-1 text-sm max-h-32 overflow-y-auto">
                        {{-- Items will be injected here --}}
                    </div>
                    <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-bold">
                        <span>{{ __('pos.total') }}</span>
                        <span id="cardTotalAmount">฿0.00</span>
                    </div>
                </div>

                {{-- Tap Card Animation --}}
                <div class="text-center py-6">
                    <div class="relative inline-block">
                        <div
                            class="w-24 h-24 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md card-tap-animation">
                            <i class="ph ph-contactless-payment text-indigo-600 text-4xl"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full animate-ping"></div>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-1">{{ __('pos.tap_card') }}</p>
                    <p class="text-sm text-gray-500">{{ __('pos.tap_card_desc') }}</p>
                </div>

                {{-- Card Status --}}
                <div id="cardStatus" class="text-center py-3 rounded-xl bg-blue-50 text-blue-700">
                    <i class="ph ph-spinner animate-spin mr-2"></i>
                    <span>{{ __('pos.waiting_for_card') }}</span>
                </div>
            </div>

            {{-- Transfer Payment View --}}
            <div id="transferPaymentView" class="hidden">
                <div class="text-center py-6">
                    <div
                        class="w-20 h-20 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="ph ph-qr-code text-emerald-600 text-4xl"></i>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-1">{{ __('pos.scan_qr') }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ __('pos.scan_qr_desc') }}</p>

                    {{-- QR Payment Image --}}
                    <div class="inline-block bg-white p-2 rounded-2xl shadow-sm border border-gray-100 mb-4">
                        <img src="{{ asset('assets/images/qr-payment.jpg') }}" alt="Thai QR Payment"
                            class="w-64 h-auto rounded-xl object-contain mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closePaymentModal()"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('cancel') }}
            </button>
            <button type="button" onclick="confirmPayment()" id="confirmPaymentBtn"
                class="px-6 py-2 bg-green-500 hover:brightness-110 text-white text-sm font-medium rounded-xl transition flex items-center gap-2">
                <i class="ph-bold ph-check"></i>
                {{ __('pos.confirm_payment') }}
            </button>
        </div>
    </div>

    {{-- Allergy Alert Modal --}}
    <div id="allergyModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"></div>
    <div id="allergyModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 28rem;">
        <div class="modal-content text-center">
            <div class="pos-allergy-alert-icon">
                <i class="ph-fill ph-warning"></i>
            </div>
            <h2 class="text-xl font-bold text-red-600 mb-2">{{ __('pos.allergy_warning') }}</h2>
            <p class="text-gray-600 mb-4" id="allergyMessage"></p>
            <div id="allergyDetails" class="bg-red-50 p-4 rounded-xl text-left mb-4">
                {{-- Allergy details will be inserted here --}}
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="cancelAllergyProduct()"
                class="px-5 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-xl transition flex-1">
                <i class="ph ph-x"></i>
                {{ __('pos.cancel_product') }}
            </button>
            <button type="button" onclick="proceedWithAllergy()"
                class="px-6 py-2 bg-yellow-500 hover:brightness-110 text-white text-sm font-medium rounded-xl transition flex-1">
                <i class="ph ph-check"></i>
                {{ __('pos.proceed_anyway') }}
            </button>
        </div>
    </div>

    {{-- Receipt Preview Modal --}}
    <div id="receiptModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"></div>
    <div id="receiptModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 400px;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('pos.receipt_preview') }}</h2>
            <button onclick="closeReceiptModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content" style="padding: 0;">
            <div class="receipt-preview" id="receiptPreview">
                {{-- Receipt content will be inserted here --}}
            </div>
        </div>
        <div class="modal-footer" style="flex-direction: column; gap: 8px;">
            <button type="button" onclick="printReceipt()"
                class="w-full px-6 py-3 bg-ios-blue hover:brightness-110 text-white text-sm font-semibold rounded-xl transition flex items-center justify-center gap-2">
                <i class="ph-bold ph-printer"></i>
                {{ __('pos.print_receipt') }}
            </button>
            <button type="button" onclick="closeReceiptModal(true)"
                class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('pos.skip_receipt') }}
            </button>
        </div>
    </div>

    {{-- Held Orders Modal --}}
    <div id="heldOrdersModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="closeHeldOrdersModal()"></div>
    <div id="heldOrdersModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('pos.held_orders') }}</h2>
            <button onclick="closeHeldOrdersModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            <div id="heldOrdersList" class="space-y-3">
                {{-- Held orders will be loaded here --}}
                <div class="text-center py-8 text-gray-400">
                    <i class="ph ph-circle-notch animate-spin text-3xl mb-2"></i>
                    <p>Loading held orders...</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeHeldOrdersModal()"
                class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                {{ __('close') }}
            </button>
        </div>
    </div>

    {{-- Recent Sales Modal --}}
    <div id="recentSalesModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="closeRecentSalesModal()"></div>
    <div id="recentSalesModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('pos.recent_sales') }}</h2>
            <button onclick="closeRecentSalesModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            <div id="recentSalesList" class="space-y-3">
                {{-- Recent sales will be loaded here --}}
                <div class="text-center py-8 text-gray-400">
                    <i class="ph ph-circle-notch animate-spin text-3xl mb-2"></i>
                    <p>Loading recent sales...</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeRecentSalesModal()"
                class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                {{ __('close') }}
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- QuaggaJS for barcode scanning --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        // POS-specific Toast duration override (Force 1s)
        (function() {
            function overrideToast() {
                if (typeof window.showToast === 'function') {
                    const originalShowToast = window.showToast;
                    window.showToast = function(message, type = 'info', duration) {
                        // ALWAYS use 1000ms regardless of what is passed
                        originalShowToast(message, type, 1000);
                    };
                    console.log('POS Toast duration override active (1s)');
                } else {
                    setTimeout(overrideToast, 50);
                }
            }
            overrideToast();
        })();

        // Cart State
        let cart = [];
        let selectedCustomer = null;
        let selectedPaymentMethod = 'cash';
        let pendingAllergyProduct = null;

        // Sound Effects
        const successSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3');
        successSound.volume = 0.5;

        function playSuccessSound() {
            successSound.currentTime = 0;
            successSound.play().catch(e => console.log('Audio play failed:', e));
        }

        const scanSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3');
        scanSound.volume = 0.5;

        function playScanSound() {
            scanSound.currentTime = 0;
            scanSound.play().catch(e => console.log('Audio play failed:', e));
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Product search
            const searchInput = document.getElementById('productSearch');
            let searchTimeout;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => searchProducts(e.target.value), 300);
            });

            // Update held orders count
            updateHeldCount();

            // ===============================================
            // KEYBOARD SHORTCUTS
            // ===============================================
            document.addEventListener('keydown', function(e) {
                // Skip if typing in input/textarea
                const isTyping = ['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName);

                switch (e.key) {
                    case 'F1':
                        e.preventDefault();
                        showKeyboardShortcutsHelp();
                        break;
                    case 'F2':
                        e.preventDefault();
                        document.getElementById('productSearch').focus();
                        break;
                    case 'F3':
                        e.preventDefault();
                        document.getElementById('customerSearch')?.focus();
                        break;
                    case 'F4':
                        e.preventDefault();
                        toggleBarcodeScanner();
                        break;
                    case 'F8':
                        e.preventDefault();
                        if (cart.length > 0) holdOrder();
                        break;
                    case 'F10':
                        e.preventDefault();
                        if (cart.length > 0) showCheckoutModal();
                        break;
                    case 'Escape':
                        e.preventDefault();
                        // Close any open modals
                        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                            if (!modal.classList.contains('hidden')) {
                                modal.classList.add('hidden');
                            }
                        });
                        // Clear search if focused
                        if (document.activeElement.id === 'productSearch') {
                            document.activeElement.value = '';
                            document.activeElement.blur();
                        }
                        break;
                    case '+':
                    case '=':
                        if (!isTyping && cart.length > 0) {
                            e.preventDefault();
                            // Increase last item qty
                            updateQty(cart.length - 1, 1);
                        }
                        break;
                    case '-':
                        if (!isTyping && cart.length > 0) {
                            e.preventDefault();
                            // Decrease last item qty
                            updateQty(cart.length - 1, -1);
                        }
                        break;
                    case 'Delete':
                        if (!isTyping && cart.length > 0) {
                            e.preventDefault();
                            // Remove last item
                            if (confirm('ลบรายการล่าสุด?')) {
                                removeFromCart(cart.length - 1);
                            }
                        }
                        break;
                }
            });
        });

        // Keyboard Shortcuts Help Modal
        function showKeyboardShortcutsHelp() {
            const modal = document.createElement('div');
            modal.id = 'keyboardHelpModal';
            modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center';
            modal.onclick = (e) => {
                if (e.target === modal) modal.remove();
            };
            modal.innerHTML = `
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
                    <div class="bg-gradient-to-r from-ios-blue to-blue-600 text-white p-6">
                        <div class="flex items-center gap-3">
                            <i class="ph-fill ph-keyboard text-3xl"></i>
                            <div>
                                <h3 class="text-xl font-bold">คีย์ลัด (Shortcuts)</h3>
                                <p class="text-white/70 text-sm">เพิ่มความเร็วในการใช้งาน POS</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-3 max-h-[60vh] overflow-y-auto">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">แสดงความช่วยเหลือ</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">F1</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">ค้นหาสินค้า</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">F2</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">ค้นหาลูกค้า</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">F3</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">สแกนบาร์โค้ด</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">F4</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">พักบิล</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">F8</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700 font-bold text-ios-blue">ชำระเงิน</span>
                            <kbd class="px-3 py-1 bg-ios-blue text-white rounded-lg font-mono text-sm font-bold">F10</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">ปิด / ยกเลิก</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">Esc</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">เพิ่มจำนวน</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">+</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">ลดจำนวน</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">-</kbd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">ลบรายการล่าสุด</span>
                            <kbd class="px-3 py-1 bg-gray-100 rounded-lg font-mono text-sm font-bold">Delete</kbd>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <button onclick="this.closest('#keyboardHelpModal').remove()" 
                            class="w-full py-3 bg-gray-200 hover:bg-gray-300 rounded-xl font-bold text-gray-700 transition-colors">
                            ปิด (Esc)
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Search products
        async function searchProducts(query) {
            if (query.length < 1) {
                location.reload(); // Show all products
                return;
            }

            try {
                const response = await fetch(`{{ route('pos.search-products') }}?q=${encodeURIComponent(query)}`);
                const products = await response.json();
                renderProducts(products);
            } catch (error) {
                console.error('Search failed:', error);
            }
        }

        function renderProducts(products) {
            const grid = document.getElementById('productGrid');
            grid.innerHTML = products.map(product => `
                <div class="pos-product-card ${product.stock_qty <= 0 ? 'out-of-stock' : ''}"
                     data-category="${product.category_id || ''}"
                     onclick='addToCart(${JSON.stringify(product)})'>
                    <div class="pos-product-image">
                        ${product.image_path ? `<img src="/storage/${product.image_path}" alt="${product.name}">` : '<i class="ph ph-pill"></i>'}
                    </div>
                    <div class="pos-product-name">${product.name}</div>
                    <div class="pos-product-price">฿${parseFloat(product.unit_price).toFixed(2)}</div>
                    <div class="pos-product-stock">
                        ${product.stock_qty > 0 ? `{{ __('pos.stock') }}: ${product.stock_qty}` : '{{ __('pos.out_of_stock') }}'}
                    </div>
                </div>
            `).join('');
        }

        // Filter by category
        function filterByCategory() {
            const categoryId = document.getElementById('categoryFilter').value;
            const cards = document.querySelectorAll('.pos-product-card');

            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (!categoryId || cardCategory === categoryId) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Add to cart
        function addToCart(product) {
            if (product.stock_qty <= 0) {
                showToast('{{ __('pos.out_of_stock') }}', 'error');
                return;
            }

            // Check allergies if customer selected
            if (selectedCustomer && selectedCustomer.allergies) {
                const allergies = Array.isArray(selectedCustomer.allergies) ? selectedCustomer.allergies : [];
                for (const allergy of allergies) {
                    if (product.name.toLowerCase().includes(allergy.toLowerCase()) ||
                        (product.generic_name && product.generic_name.toLowerCase().includes(allergy.toLowerCase()))) {
                        pendingAllergyProduct = product;
                        showAllergyWarning(allergy, product);
                        return;
                    }
                }
            }

            doAddToCart(product);
        }

        function doAddToCart(product) {
            const existingIndex = cart.findIndex(item => item.id === product.id);

            if (existingIndex > -1) {
                if (cart[existingIndex].quantity >= product.stock_qty) {
                    showToast('{{ __('pos.max_stock_reached') }}', 'warning');
                    return;
                }
                cart[existingIndex].quantity++;
            } else {
                cart.push({
                    ...product,
                    quantity: 1,
                    price: selectedCustomer ? (product.member_price || product.unit_price) : product.unit_price
                });
            }

            updateCartUI();
        }

        // Update cart UI
        function updateCartUI() {
            const cartItems = document.getElementById('cartItems');
            const cartEmpty = document.getElementById('cartEmpty');
            const cartSummary = document.getElementById('cartSummary');
            const payBtn = document.getElementById('payBtn');

            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="pos-cart-empty">
                        <i class="ph ph-shopping-cart"></i>
                        <div>{{ __('pos.empty_cart') }}</div>
                        <div class="text-sm mt-1">{{ __('pos.add_products') }}</div>
                    </div>
                `;
                cartSummary.style.display = 'none';
                payBtn.disabled = true;
                return;
            }

            payBtn.disabled = false;
            cartSummary.style.display = 'block';

            cartItems.innerHTML = cart.map((item, index) => `
                <div class="pos-cart-item">
                    <div class="pos-cart-item-info">
                        <div class="pos-cart-item-name">${item.name}</div>
                        <div class="pos-cart-item-price">฿${parseFloat(item.price).toFixed(2)} × ${item.quantity}</div>
                    </div>
                    <div class="pos-cart-item-qty">
                        <button class="pos-qty-btn" onclick="updateQuantity(${index}, -1)">−</button>
                        <span class="pos-qty-value">${item.quantity}</span>
                        <button class="pos-qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                    <div class="pos-cart-item-total">฿${(item.price * item.quantity).toFixed(2)}</div>
                    <i class="ph ph-x-circle pos-cart-item-remove" onclick="removeFromCart(${index})"></i>
                </div>
            `).join('');

            // Calculate totals
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const vat = subtotal * 0.07;
            const total = subtotal + vat;

            document.getElementById('subtotalAmount').textContent = `฿${subtotal.toFixed(2)}`;
            document.getElementById('vatAmount').textContent = `฿${vat.toFixed(2)}`;
            document.getElementById('totalAmount').textContent = `฿${total.toFixed(2)}`;
        }

        function updateQuantity(index, delta) {
            const newQty = cart[index].quantity + delta;
            if (newQty <= 0) {
                removeFromCart(index);
                return;
            }
            if (newQty > cart[index].stock_qty) {
                showToast('{{ __('pos.max_stock_reached') }}', 'warning');
                return;
            }
            cart[index].quantity = newQty;
            updateCartUI();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        function clearCart() {
            if (cart.length === 0) return;
            if (confirm('{{ __('pos.clear_cart_confirm') }}')) {
                cart = [];
                selectedCustomer = null;
                updateCustomerUI();
                updateCartUI();
            }
        }

        // Customer functions
        function showCustomerSearch() {
            document.getElementById('customerModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('customerModal-panel').classList.remove('modal-panel-hidden');
        }

        function closeCustomerModal() {
            document.getElementById('customerModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('customerModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => document.getElementById('customerModal-backdrop').classList.add('hidden'), 200);
        }

        function selectCustomer(customer) {
            selectedCustomer = customer;
            updateCustomerUI();
            closeCustomerModal();

            // Update prices if member
            if (customer) {
                cart.forEach(item => {
                    item.price = item.member_price || item.unit_price;
                });
                updateCartUI();
            }
        }

        function updateCustomerUI() {
            const btn = document.getElementById('customerBtn');
            const nameEl = document.getElementById('customerName');
            const infoEl = document.getElementById('customerInfo');

            if (selectedCustomer) {
                btn.classList.add('has-customer');
                nameEl.textContent = selectedCustomer.name;
                infoEl.textContent = `${selectedCustomer.phone || ''} • ${selectedCustomer.member_type || 'Member'}`;
            } else {
                btn.classList.remove('has-customer');
                nameEl.textContent = '{{ __('pos.select_customer') }}';
                infoEl.textContent = '{{ __('pos.walk_in_customer') }}';
            }
        }

        // Payment functions
        function selectPayment(method) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.pos-payment-method').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.method === method);
            });
        }

        function setPaymentMethod(method) {
            selectedPaymentMethod = method;

            // Update tabs
            ['cash', 'card', 'transfer', 'qr'].forEach(m => {
                const tab = document.getElementById(`paymentTab-${m}`);
                if (tab) {
                    if (m === method) {
                        tab.classList.add('payment-tab-active');
                        tab.classList.remove('bg-gray-100', 'text-gray-600');
                    } else {
                        tab.classList.remove('payment-tab-active');
                        tab.classList.add('bg-gray-100', 'text-gray-600');
                    }
                }
            });

            // Show/hide views
            document.getElementById('cashPaymentView').classList.toggle('hidden', method !== 'cash');
            document.getElementById('cardPaymentView').classList.toggle('hidden', method !== 'card');
            document.getElementById('transferPaymentView').classList.toggle('hidden', method !== 'transfer' && method !==
                'qr');

            if (method === 'card') {
                prepareCardPayment();
            }
        }

        function processPayment() {
            if (cart.length === 0) {
                showToast('{{ __('pos.empty_cart') }}', 'warning');
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const vat = subtotal * 0.07;
            const total = subtotal + vat;

            document.getElementById('paymentTotal').textContent = `฿${total.toFixed(2)}`;
            document.getElementById('cardTotalAmount').textContent = `฿${total.toFixed(2)}`;
            document.getElementById('amountReceived').value = total.toFixed(2);
            document.getElementById('changeAmount').textContent = '฿0.00';

            // Open modal
            document.getElementById('paymentModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('paymentModal-panel').classList.remove('modal-panel-hidden');

            // Set initial method
            setPaymentMethod(selectedPaymentMethod);
        }

        function prepareCardPayment() {
            const summaryEl = document.getElementById('cardOrderSummary');
            summaryEl.innerHTML = cart.map(item => `
                <div class="flex justify-between items-center">
                    <span class="truncate pr-4">${item.name} x ${item.quantity}</span>
                    <span class="font-medium">฿${(item.price * item.quantity).toFixed(2)}</span>
                </div>
            `).join('');

            // Reset status
            const statusEl = document.getElementById('cardStatus');
            statusEl.className = 'text-center py-3 rounded-xl bg-blue-50 text-blue-700';
            statusEl.innerHTML =
                '<i class="ph ph-spinner animate-spin mr-2"></i><span>{{ __('pos.waiting_for_card') }}</span>';

            // Simulate tap card after 3 seconds for demonstration
            setTimeout(() => {
                if (selectedPaymentMethod === 'card') {
                    simulateCardTap();
                }
            }, 3000);
        }

        function simulateCardTap() {
            const statusEl = document.getElementById('cardStatus');
            statusEl.className = 'text-center py-3 rounded-xl bg-green-50 text-green-700';
            statusEl.innerHTML = '<i class="ph ph-check-circle mr-2"></i><span>{{ __('pos.card_processed') }}</span>';

            // Auto confirm after success
            setTimeout(() => {
                if (selectedPaymentMethod === 'card') {
                    confirmPayment();
                }
            }, 1000);
        }

        function closePaymentModal() {
            document.getElementById('paymentModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('paymentModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => document.getElementById('paymentModal-backdrop').classList.add('hidden'), 200);
        }

        function calculateChange() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal * 1.07;
            const received = parseFloat(document.getElementById('amountReceived').value) || 0;
            const change = Math.max(0, received - total);
            document.getElementById('changeAmount').textContent = `฿${change.toFixed(2)}`;
        }

        function setAmount(amount) {
            document.getElementById('amountReceived').value = amount;
            calculateChange();
        }

        function setExactAmount() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal * 1.07;
            document.getElementById('amountReceived').value = total.toFixed(2);
            calculateChange();
        }

        let lastOrderId = null;
        let lastOrderData = null;

        async function confirmPayment() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal * 1.07;
            let amountReceived = total;

            if (selectedPaymentMethod === 'cash') {
                amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
                if (amountReceived < total) {
                    showToast('{{ __('pos.insufficient_amount') }}', 'error');
                    return;
                }
            }

            const btn = document.getElementById('confirmPaymentBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Processing...';

            // Store cart data for receipt
            const cartSnapshot = [...cart];
            const customerSnapshot = selectedCustomer ? {
                ...selectedCustomer
            } : null;
            const paymentMethod = selectedPaymentMethod;

            try {
                const response = await fetch('{{ route('pos.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: cart.map(item => ({
                            product_id: item.id,
                            quantity: item.quantity
                        })),
                        customer_id: selectedCustomer?.id,
                        payment_method: selectedPaymentMethod,
                        amount_paid: amountReceived,
                        confirmed: true
                    })
                });

                const data = await response.json();

                if (data.success) {
                    playSuccessSound();
                    showToast(data.message, 'success');

                    // Store order data for receipt
                    lastOrderId = data.order.id;
                    lastOrderData = {
                        order: data.order,
                        items: cartSnapshot,
                        customer: customerSnapshot,
                        paymentMethod: paymentMethod,
                        amountPaid: amountReceived,
                        change: Math.max(0, amountReceived - total),
                        subtotal: subtotal,
                        vat: subtotal * 0.07,
                        total: total
                    };

                    // Clear cart
                    cart = [];
                    selectedCustomer = null;
                    updateCustomerUI();
                    updateCartUI();
                    closePaymentModal();

                    // Show receipt preview modal
                    showReceiptPreview(lastOrderData);
                } else if (data.requires_confirmation) {
                    showAllergyConfirmation(data.allergy_warnings);
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('{{ __('pos.payment_failed') }}', 'error');
                console.error(error);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-check"></i> {{ __('pos.confirm_payment') }}';
            }
        }

        // Receipt settings from backend
        const receiptConfig = {
            storeName: @json($storeSettings['store_name'] ?? config('app.name', 'Pharmacy')),
            storeAddress: @json($storeSettings['store_address'] ?? ''),
            storePhone: @json($storeSettings['store_phone'] ?? ''),
            storeTaxId: @json($storeSettings['store_tax_id'] ?? ''),
            storeLogo: @json(!empty($storeSettings['store_logo']) ? Storage::url($storeSettings['store_logo']) : ''),
            showLogo: {{ $receiptSettings['receipt_show_logo'] ?? true ? 'true' : 'false' }},
            showStoreInfo: {{ $receiptSettings['receipt_show_store_info'] ?? true ? 'true' : 'false' }},
            showTax: {{ $receiptSettings['receipt_show_tax'] ?? true ? 'true' : 'false' }},
            header: @json($receiptSettings['receipt_header'] ?? ''),
            footer: @json($receiptSettings['receipt_footer'] ?? ''),
            thankYou: @json($receiptSettings['receipt_thank_you'] ?? 'ขอบคุณครับ!'),
            returnPolicy: @json($receiptSettings['receipt_return_policy'] ?? 'สามารถคืนสินค้าได้ภายใน 7 วัน พร้อมใบเสร็จ'),
        };

        function showReceiptPreview(orderData) {
            const now = new Date();
            const dateStr = now.toLocaleDateString('th-TH', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            const timeStr = now.toLocaleTimeString('th-TH', {
                hour: '2-digit',
                minute: '2-digit'
            });

            let receiptHtml = '<div class="receipt-paper">';

            // Logo
            if (receiptConfig.showLogo && receiptConfig.storeLogo) {
                receiptHtml += `
                    <div style="text-align: center; margin-bottom: 12px;">
                        <img src="${receiptConfig.storeLogo}" alt="Logo" 
                            style="max-width: 60px; max-height: 60px; filter: grayscale(100%) contrast(1.2); display: inline-block;">
                    </div>`;
            }

            // Header
            receiptHtml += '<div class="receipt-header">';
            receiptHtml += `<div class="receipt-store-name">${receiptConfig.storeName}</div>`;

            if (receiptConfig.showStoreInfo) {
                receiptHtml += '<div class="receipt-store-info">';
                if (receiptConfig.storeAddress) receiptHtml += receiptConfig.storeAddress + '<br>';
                if (receiptConfig.storePhone) receiptHtml += 'Tel: ' + receiptConfig.storePhone;
                if (receiptConfig.storeTaxId) receiptHtml += '<br>Tax ID: ' + receiptConfig.storeTaxId;
                receiptHtml += '</div>';
            }

            if (receiptConfig.header) {
                receiptHtml +=
                    `<div style="font-size: 10px; margin-top: 8px; font-style: italic;">${receiptConfig.header}</div>`;
            }
            receiptHtml += '</div>';

            // Order Info
            receiptHtml += '<div class="receipt-info">';
            receiptHtml +=
                `<div class="receipt-row"><span>เลขที่:</span><span>${orderData.order.order_number || '#' + orderData.order.id}</span></div>`;
            receiptHtml += `<div class="receipt-row"><span>วันที่:</span><span>${dateStr} ${timeStr}</span></div>`;
            if (orderData.customer) {
                receiptHtml += `<div class="receipt-row"><span>ลูกค้า:</span><span>${orderData.customer.name}</span></div>`;
            }
            receiptHtml += '</div>';

            // Items
            receiptHtml += '<div class="receipt-items">';
            orderData.items.forEach(item => {
                const itemTotal = (item.quantity * item.price).toFixed(2);
                receiptHtml += `
                    <div class="receipt-item">
                        <div class="receipt-item-name">${item.name}</div>
                        <div class="receipt-item-details">
                            <span>${item.quantity} x ฿${parseFloat(item.price).toFixed(2)}</span>
                            <span>฿${itemTotal}</span>
                        </div>
                    </div>`;
            });
            receiptHtml += '</div>';

            // Totals
            receiptHtml += '<div class="receipt-totals">';
            receiptHtml +=
                `<div class="receipt-total-row"><span>รวม:</span><span>฿${orderData.subtotal.toFixed(2)}</span></div>`;

            if (receiptConfig.showTax) {
                receiptHtml +=
                    `<div class="receipt-total-row"><span>VAT (7%):</span><span>฿${orderData.vat.toFixed(2)}</span></div>`;
            }

            receiptHtml +=
                `<div class="receipt-total-row grand"><span>ยอดสุทธิ:</span><span>฿${orderData.total.toFixed(2)}</span></div>`;
            receiptHtml +=
                `<div class="receipt-total-row" style="margin-top: 8px;"><span>${orderData.paymentMethod.toUpperCase()}:</span><span>฿${orderData.amountPaid.toFixed(2)}</span></div>`;

            if (orderData.change > 0) {
                receiptHtml +=
                    `<div class="receipt-total-row"><span>เงินทอน:</span><span>฿${orderData.change.toFixed(2)}</span></div>`;
            }
            receiptHtml += '</div>';

            // Footer
            receiptHtml += '<div class="receipt-footer">';
            receiptHtml += `<div class="receipt-thank-you">${receiptConfig.thankYou}</div>`;
            receiptHtml += `<div>${receiptConfig.returnPolicy}</div>`;
            if (receiptConfig.footer) {
                receiptHtml += `<div style="margin-top: 4px;">${receiptConfig.footer}</div>`;
            }
            receiptHtml += '</div>';

            receiptHtml += '</div>';

            document.getElementById('receiptPreview').innerHTML = receiptHtml;
            document.getElementById('receiptModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('receiptModal-panel').classList.remove('modal-panel-hidden');
        }

        function closeReceiptModal(skip = false) {
            document.getElementById('receiptModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('receiptModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => {
                document.getElementById('receiptModal-backdrop').classList.add('hidden');
                // Reload after closing
                location.reload();
            }, 200);
        }

        function printReceipt() {
            if (lastOrderId) {
                window.open(`{{ url('pos/receipt') }}/${lastOrderId}`, '_blank');
            }
            closeReceiptModal();
        }

        // Allergy handling
        function showAllergyWarning(allergy, product) {
            document.getElementById('allergyMessage').textContent =
                `Customer ${selectedCustomer.name} is allergic to ${allergy}!`;
            document.getElementById('allergyDetails').innerHTML = `
                <div class="font-semibold text-red-700 mb-2">Product: ${product.name}</div>
                <div class="text-sm text-red-600">This product may contain or be related to: ${allergy}</div>
            `;

            document.getElementById('allergyModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('allergyModal-panel').classList.remove('modal-panel-hidden');
        }

        function cancelAllergyProduct() {
            pendingAllergyProduct = null;
            document.getElementById('allergyModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('allergyModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => document.getElementById('allergyModal-backdrop').classList.add('hidden'), 200);
        }

        function proceedWithAllergy() {
            if (pendingAllergyProduct) {
                doAddToCart(pendingAllergyProduct);
                pendingAllergyProduct = null;
            }
            cancelAllergyProduct();
        }

        // Shift functions
        function showShiftModal() {
            document.getElementById('shiftModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('shiftModal-panel').classList.remove('modal-panel-hidden');
        }

        function closeShiftModal() {
            document.getElementById('shiftModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('shiftModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => document.getElementById('shiftModal-backdrop').classList.add('hidden'), 200);
        }

        async function openShift() {
            const openingBalance = parseFloat(document.getElementById('openingBalance').value) || 0;

            try {
                const response = await fetch('{{ route('pos.open-shift') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        opening_balance: openingBalance
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('Failed to open shift', 'error');
            }
        }

        async function closeShift() {
            const closingBalance = parseFloat(document.getElementById('closingBalance').value) || 0;
            const notes = document.getElementById('shiftNotes').value;

            try {
                const response = await fetch('{{ route('pos.close-shift') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        closing_balance: closingBalance,
                        notes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('Failed to close shift', 'error');
            }
        }

        // Hold orders
        async function holdCurrentOrder() {
            if (cart.length === 0) {
                showToast('{{ __('pos.empty_cart') }}', 'warning');
                return;
            }

            try {
                const response = await fetch('{{ route('pos.hold-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: cart,
                        customer_id: selectedCustomer?.id,
                        notes: ''
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    cart = [];
                    selectedCustomer = null;
                    updateCustomerUI();
                    updateCartUI();
                    updateHeldCount();
                }
            } catch (error) {
                showToast('Failed to hold order', 'error');
            }
        }

        async function updateHeldCount() {
            try {
                const response = await fetch('{{ route('pos.held-orders') }}');
                const data = await response.json();
                document.getElementById('heldCount').textContent = data.orders.length;
            } catch (error) {
                console.error('Failed to get held orders');
            }
        }

        async function showHeldOrders() {
            document.getElementById('heldOrdersModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('heldOrdersModal-panel').classList.remove('modal-panel-hidden');

            const list = document.getElementById('heldOrdersList');
            list.innerHTML = `
                <div class="text-center py-8 text-gray-400">
                    <i class="ph ph-circle-notch animate-spin text-3xl mb-2"></i>
                    <p>Loading held orders...</p>
                </div>
            `;

            try {
                const response = await fetch('{{ route('pos.held-orders') }}');
                const data = await response.json();

                if (data.orders.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-12 text-gray-400">
                            <i class="ph ph-pause-circle text-5xl mb-3 opacity-20"></i>
                            <p>No held orders</p>
                        </div>
                    `;
                    return;
                }

                list.innerHTML = data.orders.map(order => {
                    const date = new Date(order.held_at);
                    const total = order.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                    const customerName = order.customer_id ? `Customer ID: ${order.customer_id}` :
                        'Walk-in Customer';

                    return `
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600">
                                    <i class="ph ph-pause-circle text-2xl"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">${customerName}</div>
                                    <div class="text-xs text-gray-500">${date.toLocaleTimeString()} • ${order.items.length} items</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="font-bold text-gray-900">฿${total.toFixed(2)}</div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="recallOrder(${order.index})" class="p-2 bg-ios-blue text-white rounded-lg hover:brightness-110">
                                        <i class="ph-bold ph-arrow-u-up-left"></i>
                                    </button>
                                    <button onclick="deleteHeldOrder(${order.index})" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                list.innerHTML = '<p class="text-center text-red-500 py-4">Failed to load orders</p>';
            }
        }

        async function recallOrder(index) {
            if (cart.length > 0 && !confirm('Your current cart will be replaced. Continue?')) {
                return;
            }

            try {
                const response = await fetch(`{{ url('pos/recall-order') }}/${index}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    cart = data.order.items;
                    // Try to re-select customer if exists
                    if (data.order.customer_id) {
                        // This might need more logic to fetch customer object, 
                        // but for now we set a partial object
                        selectedCustomer = {
                            id: data.order.customer_id,
                            name: 'Customer #' + data.order.customer_id
                        };
                    } else {
                        selectedCustomer = null;
                    }

                    updateCartUI();
                    updateCustomerUI();
                    closeHeldOrdersModal();
                    showToast('Order resumed', 'success');
                    updateHeldCount();
                }
            } catch (error) {
                showToast('Failed to resume order', 'error');
            }
        }

        async function deleteHeldOrder(index) {
            if (!confirm('Are you sure you want to delete this held order?')) return;

            try {
                const response = await fetch(`{{ url('pos/held-order') }}/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    showToast('Held order deleted', 'success');
                    showHeldOrders(); // Refresh list
                    updateHeldCount();
                }
            } catch (error) {
                showToast('Failed to delete held order', 'error');
            }
        }

        function closeHeldOrdersModal() {
            document.getElementById('heldOrdersModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('heldOrdersModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => document.getElementById('heldOrdersModal-backdrop').classList.add('hidden'), 200);
        }

        async function showRecentSales() {
            document.getElementById('recentSalesModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
            document.getElementById('recentSalesModal-panel').classList.remove('modal-panel-hidden');

            const list = document.getElementById('recentSalesList');
            list.innerHTML = `
                <div class="text-center py-8 text-gray-400">
                    <i class="ph ph-circle-notch animate-spin text-3xl mb-2"></i>
                    <p>Loading recent sales...</p>
                </div>
            `;

            try {
                const response = await fetch('{{ route('pos.recent-sales') }}');
                const data = await response.json();

                if (data.orders.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-12 text-gray-400">
                            <i class="ph ph-receipt text-5xl mb-3 opacity-20"></i>
                            <p>No recent sales</p>
                        </div>
                    `;
                    return;
                }

                list.innerHTML = data.orders.map(order => {
                    const date = new Date(order.completed_at || order.created_at);
                    const customerName = order.customer ? order.customer.name : 'Walk-in Customer';

                    return `
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                                    <i class="ph ph-receipt text-2xl"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">#${order.order_number}</div>
                                    <div class="text-xs text-gray-500">${date.toLocaleTimeString()} • ${customerName}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="font-bold text-gray-900">฿${parseFloat(order.total_amount).toFixed(2)}</div>
                                </div>
                                <button onclick="reprintOrder(${order.id})" class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                                    <i class="ph ph-printer"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                list.innerHTML = '<p class="text-center text-red-500 py-4">Failed to load sales</p>';
            }
        }

        function reprintOrder(id) {
            window.open(`{{ url('pos/receipt') }}/${id}`, '_blank');
        }

        function closeRecentSalesModal() {
            document.getElementById('recentSalesModal-backdrop').classList.add('modal-backdrop-hidden');
            document.getElementById('recentSalesModal-panel').classList.add('modal-panel-hidden');
            setTimeout(() => document.getElementById('recentSalesModal-backdrop').classList.add('hidden'), 200);
        }

        // Barcode scanner (camera-based)
        let posCameraActive = false;
        let isProcessingScan = false;

        function togglePosCamera() {
            if (posCameraActive) {
                stopPosCamera();
            } else {
                startPosCamera();
            }
        }

        function startPosCamera() {
            const container = document.getElementById('posCameraContainer');
            const btn = document.getElementById('posScanBtn');
            const icon = document.getElementById('posScanIcon');

            container.style.display = 'block';
            btn.classList.add('active');
            icon.classList.remove('ph-barcode');
            icon.classList.add('ph-camera-slash');

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#posCameraViewport'),
                    constraints: {
                        facingMode: "environment",
                        width: {
                            min: 320,
                            ideal: 640
                        },
                        height: {
                            min: 240,
                            ideal: 480
                        }
                    },
                },
                locator: {
                    patchSize: "medium",
                    halfSample: true
                },
                numOfWorkers: navigator.hardwareConcurrency || 2,
                decoder: {
                    readers: ["ean_reader", "ean_8_reader", "code_128_reader", "code_39_reader", "upc_reader",
                        "upc_e_reader"
                    ]
                }
            }, function(err) {
                if (err) {
                    console.error(err);
                    showToast('{{ __('barcode.camera_permission') }}', 'error');
                    stopPosCamera();
                    return;
                }
                Quagga.start();
                posCameraActive = true;
            });

            Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                handlePosBarcodeScan(code);
            });
        }

        function stopPosCamera() {
            const container = document.getElementById('posCameraContainer');
            const btn = document.getElementById('posScanBtn');
            const icon = document.getElementById('posScanIcon');

            if (posCameraActive) {
                Quagga.stop();
                Quagga.offDetected();
            }

            container.style.display = 'none';
            btn.classList.remove('active');
            icon.classList.remove('ph-camera-slash');
            icon.classList.add('ph-barcode');
            posCameraActive = false;
        }

        function handlePosBarcodeScan(barcode) {
            if (isProcessingScan) return;
            isProcessingScan = true;

            // Play beep sound
            playScanSound();

            // Lookup the product
            fetch(`{{ route('pos.barcode') }}?barcode=${encodeURIComponent(barcode)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        addToCart(data.product);
                        showToast('{{ __('pos.added') }}: ' + data.product.name, 'success');

                        // Auto-stop camera after successful scan
                        if (posCameraActive) {
                            stopPosCamera();
                        }
                    } else {
                        showToast(data.message || '{{ __('barcode.product_not_found') }}', 'error');
                    }
                })
                .catch(err => {
                    showToast('{{ __('error') }}', 'error');
                })
                .finally(() => {
                    // Lock for 1 second to prevent double scans
                    setTimeout(() => {
                        isProcessingScan = false;
                    }, 1000);
                });
        }

        // Legacy function for backward compatibility
        function startBarcodeScanner() {
            togglePosCamera();
        }

        // Toast helper
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container') || createToastContainer();
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="ph-fill ${type === 'success' ? 'ph-check-circle' : type === 'error' ? 'ph-x-circle' : 'ph-info'} toast-icon"></i>
                <span class="toast-message">${message}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
            return container;
        }
    </script>
@endpush

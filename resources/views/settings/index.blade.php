@extends('layouts.app')

@section('title', __('settings.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('settings') }}
        </p>
        <span>{{ __('settings.title') }}</span>
    </div>
@endsection

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        {{-- Page Description --}}
        <div class="flex items-center gap-4 mb-2">
            <div class="w-14 h-14 rounded-2xl bg-slate-600 flex items-center justify-center shadow-lg">
                <i class="ph-fill ph-gear text-black text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ __('settings.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('settings.subtitle') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Store Info --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-storefront text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('settings.store_info') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('settings.store_info_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Store Name --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.store_name') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-storefront absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 group-focus-within:text-ios-blue"></i>
                            <input type="text" name="store_name" value="{{ $storeSettings['store_name'] ?? '' }}"
                                class="input-ios has-icon" placeholder="{{ __('settings.store_name_placeholder') }}"
                                required>
                        </div>
                    </div>

                    {{-- Store Address --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.store_address') }}</label>
                        <textarea name="store_address" rows="2" class="input-ios resize-none"
                            placeholder="{{ __('settings.store_address_placeholder') }}">{{ $storeSettings['store_address'] ?? '' }}</textarea>
                    </div>

                    {{-- Phone & Email --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.store_phone') }}</label>
                            <div class="relative group">
                                <i
                                    class="ph ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 group-focus-within:text-ios-blue"></i>
                                <input type="text" name="store_phone" value="{{ $storeSettings['store_phone'] ?? '' }}"
                                    class="input-ios has-icon" placeholder="{{ __('settings.store_phone_placeholder') }}">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.store_email') }}</label>
                            <div class="relative group">
                                <i
                                    class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 group-focus-within:text-ios-blue"></i>
                                <input type="email" name="store_email" value="{{ $storeSettings['store_email'] ?? '' }}"
                                    class="input-ios has-icon" placeholder="{{ __('settings.store_email_placeholder') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Tax ID --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.store_tax_id') }}</label>
                        <div class="relative group">
                            <i
                                class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 group-focus-within:text-ios-blue"></i>
                            <input type="text" name="store_tax_id" value="{{ $storeSettings['store_tax_id'] ?? '' }}"
                                class="input-ios has-icon font-mono"
                                placeholder="{{ __('settings.store_tax_id_placeholder') }}">
                        </div>
                    </div>

                    {{-- Store Logo --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.store_logo') }}</label>

                        @if (!empty($storeSettings['store_logo']))
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                                <img src="{{ Storage::url($storeSettings['store_logo']) }}" alt="Store Logo"
                                    class="w-20 h-20 object-contain rounded-xl bg-white border">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ __('settings.current_logo') }}</p>
                                    <label class="inline-flex items-center gap-2 mt-2 cursor-pointer">
                                        <input type="checkbox" name="remove_logo" value="1"
                                            class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500">
                                        <span class="text-sm text-red-600">{{ __('settings.remove_logo') }}</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <label
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-ios-blue hover:bg-blue-50/50 transition">
                                <i class="ph ph-upload-simple text-xl text-gray-400"></i>
                                <span class="text-sm text-gray-500">{{ __('settings.upload_logo') }}</span>
                                <input type="file" name="store_logo" accept="image/jpeg,image/png,image/webp"
                                    class="hidden">
                            </label>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.store_logo_desc') }}</p>
                    </div>

                    {{-- Favicon --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.favicon') }}</label>

                        @if (!empty($storeSettings['store_favicon']))
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                                <img src="{{ Storage::url($storeSettings['store_favicon']) }}" alt="Favicon"
                                    class="w-12 h-12 object-contain rounded-lg bg-white border">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ __('settings.current_favicon') }}</p>
                                    <label class="inline-flex items-center gap-2 mt-2 cursor-pointer">
                                        <input type="checkbox" name="remove_favicon" value="1"
                                            class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-500">
                                        <span class="text-sm text-red-600">{{ __('settings.remove_favicon') }}</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <label
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-ios-blue hover:bg-blue-50/50 transition">
                                <i class="ph ph-browser text-xl text-gray-400"></i>
                                <span class="text-sm text-gray-500">{{ __('settings.upload_favicon') }}</span>
                                <input type="file" name="store_favicon" accept="image/png,image/x-icon,image/ico,.ico"
                                    class="hidden">
                            </label>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.favicon_desc') }}</p>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph ph-check"></i>
                        {{ __('settings.save_settings') }}
                    </button>
                </form>
            </div>

            {{-- Financial Settings --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center">
                        <i class="ph-fill ph-currency-circle-dollar text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('settings.financial') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('settings.financial_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.financial') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- VAT Rate --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.vat_rate') }}</label>
                        <div class="relative">
                            <input type="number" name="vat_rate" value="{{ $financialSettings['vat_rate'] ?? 7 }}"
                                min="0" max="100" step="0.01" class="input-ios pr-12"
                                placeholder="{{ __('settings.vat_rate_placeholder') }}">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">%</span>
                        </div>
                    </div>

                    {{-- Currency --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.currency') }}</label>
                            <select name="currency" class="input-ios">
                                <option value="THB"
                                    {{ ($financialSettings['currency'] ?? 'THB') === 'THB' ? 'selected' : '' }}>THB - Thai
                                    Baht</option>
                                <option value="USD"
                                    {{ ($financialSettings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD - US
                                    Dollar</option>
                                <option value="EUR"
                                    {{ ($financialSettings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR - Euro
                                </option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.currency_symbol') }}</label>
                            <input type="text" name="currency_symbol"
                                value="{{ $financialSettings['currency_symbol'] ?? '฿' }}"
                                class="input-ios text-center text-xl font-bold"
                                placeholder="{{ __('settings.currency_symbol_placeholder') }}" maxlength="5">
                        </div>
                    </div>

                    {{-- Price Includes VAT --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ __('settings.price_includes_vat') }}</p>
                            <p class="text-xs text-gray-500">{{ __('settings.price_includes_vat_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="price_includes_vat" class="sr-only peer"
                                {{ $financialSettings['price_includes_vat'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                            </div>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph ph-check"></i>
                        {{ __('settings.save_settings') }}
                    </button>
                </form>
            </div>

            {{-- Notification Settings --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">
                        <i class="ph-fill ph-bell-ringing text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('settings.notifications') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('settings.notifications_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.notifications') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Low Stock Alert --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('settings.enable_low_stock_alert') }}</p>
                            <p class="text-xs text-gray-500">{{ __('settings.enable_low_stock_alert_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_low_stock_alert" class="sr-only peer"
                                {{ $notificationSettings['enable_low_stock_alert'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                            </div>
                        </label>
                    </div>

                    {{-- Low Stock Threshold --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.low_stock_threshold') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="low_stock_threshold"
                                value="{{ $notificationSettings['low_stock_threshold'] ?? 10 }}" min="1"
                                max="1000" class="input-ios flex-1">
                            <span class="text-sm text-gray-500">{{ __('settings.units') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.low_stock_threshold_desc') }}</p>
                    </div>

                    {{-- Expiry Alert --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('settings.enable_expiry_alert') }}</p>
                            <p class="text-xs text-gray-500">{{ __('settings.enable_expiry_alert_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_expiry_alert" class="sr-only peer"
                                {{ $notificationSettings['enable_expiry_alert'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                            </div>
                        </label>
                    </div>

                    {{-- Expiry Alert Days --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.expiry_alert_days') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="expiry_alert_days"
                                value="{{ $notificationSettings['expiry_alert_days'] ?? 30 }}" min="1"
                                max="365" class="input-ios flex-1">
                            <span class="text-sm text-gray-500">{{ __('settings.days') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.expiry_alert_days_desc') }}</p>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph ph-check"></i>
                        {{ __('settings.save_settings') }}
                    </button>
                </form>
            </div>

            {{-- Loyalty Settings --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-star text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('settings.loyalty') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('settings.loyalty_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.loyalty') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Enable Loyalty --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('settings.loyalty_enabled') }}</p>
                            <p class="text-xs text-gray-500">{{ __('settings.loyalty_enabled_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="loyalty_enabled" class="sr-only peer"
                                {{ $loyaltySettings['loyalty_enabled'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500">
                            </div>
                        </label>
                    </div>

                    {{-- Earn Rate --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.points_earn_rate') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="points_earn_rate"
                                value="{{ $loyaltySettings['points_earn_rate'] ?? 1 }}" min="1" max="100"
                                class="input-ios flex-1">
                            <span class="text-sm text-gray-500">{{ __('settings.points_per_amount') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.points_earn_rate_desc') }}</p>
                    </div>

                    {{-- Redeem Rate --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.points_redeem_rate') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="points_redeem_rate"
                                value="{{ $loyaltySettings['points_redeem_rate'] ?? 100 }}" min="1"
                                max="1000" class="input-ios flex-1">
                            <span class="text-sm text-gray-500">{{ __('settings.points_per_currency') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.points_redeem_rate_desc') }}</p>
                    </div>

                    {{-- Minimum Redeem --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.points_min_redeem') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="points_min_redeem"
                                value="{{ $loyaltySettings['points_min_redeem'] ?? 100 }}" min="1" max="10000"
                                class="input-ios flex-1">
                            <span class="text-sm text-gray-500">{{ __('settings.points') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 ml-1">{{ __('settings.points_min_redeem_desc') }}</p>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph ph-check"></i>
                        {{ __('settings.save_settings') }}
                    </button>
                </form>
            </div>

            {{-- Receipt Settings (Full Width) --}}
            <div class="card-ios p-6 lg:col-span-2">
                <form action="{{ route('settings.receipt') }}" method="POST">
                    @csrf
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-100 flex items-center justify-center">
                                <i class="ph-fill ph-receipt text-cyan-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ __('settings.receipt') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('settings.receipt_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="submit"
                            class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition flex items-center gap-2 text-sm shadow-md">
                            <i class="ph ph-floppy-disk"></i>
                            {{ __('settings.save_settings') }}
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                        {{-- Left Column - Settings --}}
                        <div class="lg:col-span-3 space-y-5">
                            {{-- Header Message --}}
                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.receipt_header') }}</label>
                                <textarea name="receipt_header" rows="2" class="input-ios resize-none"
                                    placeholder="{{ __('settings.receipt_header_placeholder') }}">{{ $receiptSettings['receipt_header'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-400 ml-1">{{ __('settings.receipt_header_desc') }}</p>
                            </div>

                            {{-- Footer Message --}}
                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.receipt_footer') }}</label>
                                <textarea name="receipt_footer" rows="2" class="input-ios resize-none"
                                    placeholder="{{ __('settings.receipt_footer_placeholder') }}">{{ $receiptSettings['receipt_footer'] ?? 'ขอบคุณที่ใช้บริการ' }}</textarea>
                                <p class="text-xs text-gray-400 ml-1">{{ __('settings.receipt_footer_desc') }}</p>
                            </div>

                            {{-- Thank You Message --}}
                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.receipt_thank_you') }}</label>
                                <input type="text" name="receipt_thank_you" class="input-ios"
                                    value="{{ $receiptSettings['receipt_thank_you'] ?? 'ขอบคุณครับ!' }}"
                                    placeholder="{{ __('settings.receipt_thank_you_placeholder') }}">
                            </div>

                            {{-- Return Policy --}}
                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-semibold text-gray-500 ml-1">{{ __('settings.receipt_return_policy') }}</label>
                                <input type="text" name="receipt_return_policy" class="input-ios"
                                    value="{{ $receiptSettings['receipt_return_policy'] ?? 'สามารถคืนสินค้าได้ภายใน 7 วัน พร้อมใบเสร็จ' }}"
                                    placeholder="{{ __('settings.receipt_return_policy_placeholder') }}">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Show Logo --}}
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">
                                            {{ __('settings.receipt_show_logo') }}</p>
                                        <p class="text-xs text-gray-500">{{ __('settings.receipt_show_logo_desc') }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="receipt_show_logo" class="sr-only peer"
                                            {{ $receiptSettings['receipt_show_logo'] ?? true ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500">
                                        </div>
                                    </label>
                                </div>

                                {{-- Show Tax --}}
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">
                                            {{ __('settings.receipt_show_tax') }}</p>
                                        <p class="text-xs text-gray-500">{{ __('settings.receipt_show_tax_desc') }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="receipt_show_tax" class="sr-only peer"
                                            {{ $receiptSettings['receipt_show_tax'] ?? true ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500">
                                        </div>
                                    </label>
                                </div>

                                {{-- Show Barcode --}}
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">
                                            {{ __('settings.receipt_show_barcode') }}</p>
                                        <p class="text-xs text-gray-500">{{ __('settings.receipt_show_barcode_desc') }}
                                        </p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="receipt_show_barcode" class="sr-only peer"
                                            {{ $receiptSettings['receipt_show_barcode'] ?? true ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500">
                                        </div>
                                    </label>
                                </div>

                                {{-- Show Store Info --}}
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">
                                            {{ __('settings.receipt_show_store_info') }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ __('settings.receipt_show_store_info_desc') }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="receipt_show_store_info" class="sr-only peer"
                                            {{ $receiptSettings['receipt_show_store_info'] ?? true ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                                <i class="ph ph-check"></i>
                                {{ __('settings.save_settings') }}
                            </button>
                        </div>

                        {{-- Right Column - Receipt Preview --}}
                        <div class="lg:col-span-2">
                            <div class="sticky top-4">
                                <p class="text-xs font-semibold text-gray-500 mb-3 ml-1">
                                    {{ __('settings.receipt_preview') }}</p>
                                <div class="bg-gray-100 p-4 rounded-2xl">
                                    <div class="bg-white p-5 rounded-xl shadow-sm font-mono text-xs leading-relaxed max-h-[500px] overflow-y-auto"
                                        style="font-family: 'Courier New', monospace;">
                                        {{-- Logo --}}
                                        @if (!empty($storeSettings['store_logo']) && ($receiptSettings['receipt_show_logo'] ?? true))
                                            <div class="flex justify-center mb-3">
                                                <img src="{{ Storage::url($storeSettings['store_logo']) }}"
                                                    alt="Store Logo" class="w-16 h-16 object-contain"
                                                    style="filter: grayscale(100%) contrast(1.2);">
                                            </div>
                                        @endif

                                        {{-- Store Name --}}
                                        <div class="text-center border-b border-dashed border-gray-300 pb-3 mb-3">
                                            <p class="font-bold text-sm">
                                                {{ $storeSettings['store_name'] ?? 'Store Name' }}</p>
                                            @if ($receiptSettings['receipt_show_store_info'] ?? true)
                                                <p class="text-[10px] text-gray-500 mt-1">
                                                    {{ $storeSettings['store_address'] ?? '123 Main Street' }}</p>
                                                <p class="text-[10px] text-gray-500">Tel:
                                                    {{ $storeSettings['store_phone'] ?? '02-xxx-xxxx' }}</p>
                                                @if (!empty($storeSettings['store_tax_id']))
                                                    <p class="text-[10px] text-gray-500">Tax ID:
                                                        {{ $storeSettings['store_tax_id'] }}</p>
                                                @endif
                                            @endif
                                            @if (!empty($receiptSettings['receipt_header']))
                                                <p class="text-[10px] text-gray-600 mt-2 italic">
                                                    {{ $receiptSettings['receipt_header'] }}</p>
                                            @endif
                                        </div>

                                        {{-- Receipt Info --}}
                                        <div class="border-b border-dashed border-gray-300 pb-2 mb-2 text-[11px]">
                                            <div class="flex justify-between"><span>Receipt
                                                    No:</span><span>INV-20260104-001</span></div>
                                            <div class="flex justify-between"><span>Date:</span><span>04/01/2026
                                                    11:00</span></div>
                                            <div class="flex justify-between"><span>Cashier:</span><span>Staff 1</span>
                                            </div>
                                        </div>

                                        {{-- Items --}}
                                        <div class="border-b border-dashed border-gray-300 pb-2 mb-2">
                                            <div class="mb-2">
                                                <p class="font-medium">Paracetamol 500mg</p>
                                                <div class="flex justify-between text-[10px] text-gray-600">
                                                    <span>2 x ฿45.00</span><span>฿90.00</span>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <p class="font-medium">Vitamin C 1000mg</p>
                                                <div class="flex justify-between text-[10px] text-gray-600">
                                                    <span>1 x ฿120.00</span><span>฿120.00</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Totals --}}
                                        <div class="border-b border-dashed border-gray-300 pb-2 mb-2 text-[11px]">
                                            <div class="flex justify-between"><span>Subtotal:</span><span>฿210.00</span>
                                            </div>
                                            @if ($receiptSettings['receipt_show_tax'] ?? true)
                                                <div class="flex justify-between"><span>VAT (7%):</span><span>฿14.70</span>
                                                </div>
                                            @endif
                                            <div
                                                class="flex justify-between font-bold text-sm border-t border-gray-400 pt-1 mt-1">
                                                <span>TOTAL:</span><span>฿224.70</span>
                                            </div>
                                            <div class="flex justify-between mt-1"><span>CASH:</span><span>฿300.00</span>
                                            </div>
                                            <div class="flex justify-between"><span>Change:</span><span>฿75.30</span></div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="text-center text-[10px]">
                                            <p class="font-bold text-sm mb-1">
                                                {{ $receiptSettings['receipt_thank_you'] ?? 'ขอบคุณครับ!' }}</p>
                                            <p class="text-gray-500">
                                                {{ $receiptSettings['receipt_return_policy'] ?? 'สามารถคืนสินค้าได้ภายใน 7 วัน พร้อมใบเสร็จ' }}
                                            </p>
                                            @if (!empty($receiptSettings['receipt_footer']))
                                                <p class="text-gray-500 mt-1">{{ $receiptSettings['receipt_footer'] }}
                                                </p>
                                            @endif
                                            @if ($receiptSettings['receipt_show_barcode'] ?? true)
                                                <div class="mt-3"
                                                    style="font-family: 'Libre Barcode 39', cursive; font-size: 28px; line-height: 1;">
                                                    *INV20260104001*
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 text-center">
                                    {{ __('settings.receipt_preview_note') }}</p>

                                {{-- Save button under preview --}}
                                <button type="submit"
                                    class="w-full mt-4 px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="ph ph-floppy-disk"></i>
                                    {{ __('settings.save_settings') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

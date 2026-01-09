@extends('layouts.app')

@section('title', __('promotions.create_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('promotions.title') }}
        </p>
        <span>{{ __('promotions.create_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('promotions.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph-bold ph-arrow-left"></i>
        {{ __('general.back') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-info text-ios-blue"></i>
                        {{ __('general.basic_info') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.name') }}
                                *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="input-ios w-full @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.name_th') }}</label>
                            <input type="text" name="name_th" value="{{ old('name_th') }}" class="input-ios w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.code') }}</label>
                            <input type="text" name="code" value="{{ old('code') }}"
                                class="input-ios w-full font-mono uppercase @error('code') border-red-500 @enderror"
                                placeholder="e.g. SAVE10">
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.type') }}
                                *</label>
                            <select name="type" id="promotionType" required class="input-ios w-full"
                                onchange="updateTypeFields()">
                                @foreach ($types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('type', 'percentage') === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.description') }}</label>
                            <textarea name="description" rows="3" class="input-ios w-full">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.description') }}
                                (TH)</label>
                            <textarea name="description_th" rows="3" class="input-ios w-full">{{ old('description_th') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Discount Settings --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-percent text-green-500"></i>
                        {{ __('promotions.discount_value') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div id="discountValueField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="discountLabel">{{ __('promotions.discount_value') }}</span> *
                            </label>
                            <div class="relative">
                                <input type="number" name="discount_value" value="{{ old('discount_value', 0) }}"
                                    step="0.01" min="0" required class="input-ios w-full pr-10">
                                <span id="discountUnit"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>

                        <div id="buyQuantityField" class="hidden">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.buy_quantity') }}</label>
                            <input type="number" name="buy_quantity" value="{{ old('buy_quantity', 2) }}" min="1"
                                class="input-ios w-full">
                        </div>

                        <div id="getQuantityField" class="hidden">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.get_quantity') }}</label>
                            <input type="number" name="get_quantity" value="{{ old('get_quantity', 1) }}" min="1"
                                class="input-ios w-full">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.min_purchase') }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">฿</span>
                                <input type="number" name="min_purchase" value="{{ old('min_purchase', 0) }}"
                                    step="0.01" min="0" class="input-ios w-full pl-8">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.max_discount') }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">฿</span>
                                <input type="number" name="max_discount" value="{{ old('max_discount') }}" step="0.01"
                                    min="0" class="input-ios w-full pl-8" placeholder="ไม่จำกัด">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Date & Time --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-calendar text-purple-500"></i>
                        {{ __('promotions.start_date') }} & {{ __('promotions.end_date') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.start_date') }}</label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                class="input-ios w-full">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.end_date') }}</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                                class="input-ios w-full">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.start_time') }}</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}"
                                class="input-ios w-full">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.end_time') }}</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}"
                                class="input-ios w-full">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotions.active_days') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $days = [
                                    0 => __('promotions.sunday'),
                                    1 => __('promotions.monday'),
                                    2 => __('promotions.tuesday'),
                                    3 => __('promotions.wednesday'),
                                    4 => __('promotions.thursday'),
                                    5 => __('promotions.friday'),
                                    6 => __('promotions.saturday'),
                                ];
                            @endphp
                            @foreach ($days as $dayNum => $dayName)
                                <label
                                    class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                    <input type="checkbox" name="active_days[]" value="{{ $dayNum }}"
                                        class="rounded border-gray-300 text-ios-blue focus:ring-ios-blue"
                                        {{ in_array($dayNum, old('active_days', [])) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $dayName }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">ปล่อยว่างเพื่อใช้งานได้ทุกวัน</p>
                    </div>
                </div>

                {{-- Products & Categories --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-package text-orange-500"></i>
                        {{ __('promotions.select_products') }}
                    </h3>

                    <div class="mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="allProductsCheck"
                                class="rounded border-gray-300 text-ios-blue focus:ring-ios-blue"
                                onchange="toggleProductSelection(this)">
                            <span class="text-sm font-medium text-gray-700">{{ __('promotions.all_products') }}</span>
                        </label>
                    </div>

                    <div id="productSelection">
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotions.select_categories') }}</label>
                            <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto p-2 bg-gray-50 rounded-xl">
                                @foreach ($categories as $category)
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg cursor-pointer hover:bg-gray-100 transition border border-gray-200">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            class="rounded border-gray-300 text-ios-blue focus:ring-ios-blue">
                                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2">{{ __('promotions.select_products') }}</label>
                            <div class="max-h-60 overflow-y-auto p-2 bg-gray-50 rounded-xl">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach ($products as $product)
                                        <label
                                            class="flex items-center gap-3 p-2 bg-white rounded-lg cursor-pointer hover:bg-gray-100 transition border border-gray-200">
                                            <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                                class="rounded border-gray-300 text-ios-blue focus:ring-ios-blue">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    ฿{{ number_format($product->unit_price, 2) }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Usage Limits --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-bar text-blue-500"></i>
                        {{ __('promotions.usage_limit') }}
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.usage_limit') }}</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                                class="input-ios w-full" placeholder="ไม่จำกัด">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.per_customer_limit') }}</label>
                            <input type="number" name="per_customer_limit" value="{{ old('per_customer_limit') }}"
                                min="1" class="input-ios w-full" placeholder="ไม่จำกัด">
                        </div>
                    </div>
                </div>

                {{-- Targeting --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-users text-purple-500"></i>
                        {{ __('promotions.select_tier') }}
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('promotions.select_tier') }}</label>
                            <select name="member_tier_id" class="input-ios w-full">
                                <option value="">-- ทุกระดับสมาชิก --</option>
                                @foreach ($tiers as $tier)
                                    <option value="{{ $tier->id }}"
                                        {{ old('member_tier_id') == $tier->id ? 'selected' : '' }}>
                                        {{ $tier->name }} ({{ $tier->discount_percent }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="new_customers_only" value="1"
                                class="rounded border-gray-300 text-ios-blue focus:ring-ios-blue"
                                {{ old('new_customers_only') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ __('promotions.new_customers_only') }}</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="stackable" value="1"
                                class="rounded border-gray-300 text-ios-blue focus:ring-ios-blue"
                                {{ old('stackable') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ __('promotions.stackable') }}</span>
                        </label>
                    </div>
                </div>

                {{-- Status & Image --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-gear text-gray-500"></i>
                        Status
                    </h3>

                    <div class="space-y-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                class="rounded border-gray-300 text-green-500 focus:ring-green-500"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">{{ __('promotions.active') }}</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1"
                                class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500"
                                {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">{{ __('promotions.is_featured') }}</span>
                        </label>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            <input type="file" name="image" accept="image/*" class="input-ios w-full text-sm">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-2xl transition flex items-center justify-center gap-2">
                    <i class="ph-bold ph-check"></i>
                    {{ __('promotions.add_promotion') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function updateTypeFields() {
            const type = document.getElementById('promotionType').value;
            const discountValueField = document.getElementById('discountValueField');
            const buyQuantityField = document.getElementById('buyQuantityField');
            const getQuantityField = document.getElementById('getQuantityField');
            const discountLabel = document.getElementById('discountLabel');
            const discountUnit = document.getElementById('discountUnit');

            // Reset visibility
            discountValueField.classList.remove('hidden');
            buyQuantityField.classList.add('hidden');
            getQuantityField.classList.add('hidden');

            switch (type) {
                case 'percentage':
                    discountLabel.textContent = '{{ __('promotions.discount_value') }} (%)';
                    discountUnit.textContent = '%';
                    break;
                case 'fixed_amount':
                    discountLabel.textContent = '{{ __('promotions.discount_value') }} (฿)';
                    discountUnit.textContent = '฿';
                    break;
                case 'buy_x_get_y':
                    discountValueField.classList.add('hidden');
                    buyQuantityField.classList.remove('hidden');
                    getQuantityField.classList.remove('hidden');
                    break;
                case 'tier_discount':
                    discountValueField.classList.add('hidden');
                    break;
                default:
                    discountLabel.textContent = '{{ __('promotions.discount_value') }}';
                    discountUnit.textContent = '';
            }
        }

        function toggleProductSelection(checkbox) {
            const productSelection = document.getElementById('productSelection');
            productSelection.style.display = checkbox.checked ? 'none' : 'block';
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', updateTypeFields);
    </script>
@endpush

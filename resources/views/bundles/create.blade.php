@extends('layouts.app')

@section('title', __('bundles.create_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('bundles.title') }}
        </p>
        <span>{{ __('bundles.create_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('bundles.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph-bold ph-arrow-left"></i>
        {{ __('general.back') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('bundles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="bundleForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-package text-ios-blue"></i>
                        {{ __('general.basic_info') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.name') }} *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="input-ios w-full @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.name_th') }}</label>
                            <input type="text" name="name_th" value="{{ old('name_th') }}" class="input-ios w-full">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.description') }}</label>
                        <textarea name="description" rows="3" class="input-ios w-full">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- Products Selection --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-shopping-bag text-orange-500"></i>
                        {{ __('bundles.select_products') }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">{{ __('bundles.min_products') }}</p>

                    <div id="bundleProducts" class="space-y-3">
                        {{-- Product rows will be added here --}}
                    </div>

                    <button type="button" onclick="addProduct()" data-no-loading
                        class="mt-4 w-full py-3 border-2 border-dashed border-gray-300 text-gray-600 font-semibold rounded-xl hover:border-ios-blue hover:text-ios-blue transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-plus"></i>
                        {{ __('bundles.add_product') }}
                    </button>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Pricing --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-currency-circle-dollar text-green-500"></i>
                        {{ __('bundles.bundle_price') }}
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.bundle_price') }}
                                *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">฿</span>
                                <input type="number" name="bundle_price" id="bundlePrice"
                                    value="{{ old('bundle_price') }}" step="0.01" min="0" required
                                    class="input-ios w-full pl-8">
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-xl">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">{{ __('bundles.original_price') }}</span>
                                <span id="originalPriceDisplay" class="font-semibold text-gray-400 line-through">฿0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ __('bundles.savings') }}</span>
                                <span id="savingsDisplay" class="font-bold text-green-600">฿0 (0%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Date Range --}}
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-calendar text-purple-500"></i>
                        {{ __('bundles.start_date') }} / {{ __('bundles.end_date') }}
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.start_date') }}</label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                class="input-ios w-full">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.end_date') }}</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                                class="input-ios w-full">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('bundles.stock_limit') }}</label>
                            <input type="number" name="stock_limit" value="{{ old('stock_limit') }}" min="1"
                                class="input-ios w-full" placeholder="ไม่จำกัด">
                        </div>
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
                            <span class="text-sm font-medium text-gray-700">{{ __('bundles.active') }}</span>
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
                    {{ __('bundles.add_bundle') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const products = @json($products);
        let productCount = 0;

        function addProduct() {
            const container = document.getElementById('bundleProducts');
            const index = productCount++;

            const row = document.createElement('div');
            row.className = 'flex gap-3 items-start p-3 bg-gray-50 rounded-xl product-row';
            row.innerHTML = `
            <div class="flex-1">
                <select name="products[${index}][id]" required
                    class="input-ios w-full" onchange="updatePricing()">
                    <option value="">-- เลือกสินค้า --</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.unit_price}">${p.name} (฿${p.unit_price})</option>`).join('')}
                </select>
            </div>
            <div class="w-24">
                <input type="number" name="products[${index}][quantity]" value="1" min="1" required
                    class="input-ios w-full text-center" placeholder="Qty" onchange="updatePricing()">
            </div>
            <button type="button" onclick="removeProduct(this)" class="p-2 text-red-500 hover:bg-red-100 rounded-lg transition">
                <i class="ph-bold ph-trash"></i>
            </button>
        `;
            container.appendChild(row);
        }

        function removeProduct(button) {
            const row = button.closest('.product-row');
            row.remove();
            updatePricing();
        }

        function updatePricing() {
            let originalPrice = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('select');
                const qtyInput = row.querySelector('input[type="number"]');
                const option = select.options[select.selectedIndex];
                if (option && option.dataset.price) {
                    originalPrice += parseFloat(option.dataset.price) * parseInt(qtyInput.value || 1);
                }
            });

            const bundlePrice = parseFloat(document.getElementById('bundlePrice').value) || 0;
            const savings = originalPrice - bundlePrice;
            const savingsPercent = originalPrice > 0 ? (savings / originalPrice * 100).toFixed(1) : 0;

            document.getElementById('originalPriceDisplay').textContent = `฿${originalPrice.toLocaleString()}`;
            document.getElementById('savingsDisplay').textContent = `฿${savings.toLocaleString()} (${savingsPercent}%)`;
            document.getElementById('savingsDisplay').className = savings > 0 ? 'font-bold text-green-600' :
                'font-bold text-red-600';
        }

        // Add initial products
        document.addEventListener('DOMContentLoaded', () => {
            addProduct();
            addProduct();
            document.getElementById('bundlePrice').addEventListener('input', updatePricing);
        });
    </script>
@endpush

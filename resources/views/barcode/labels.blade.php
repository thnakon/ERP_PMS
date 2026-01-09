@extends('layouts.app')

@section('title', __('barcode.labels_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('barcode.page_subtitle') }}
        </p>
        <span>{{ __('barcode.labels_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('barcode.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('back') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('barcode.generate-labels') }}" method="POST" target="_blank" id="labels-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Product Selection --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Search & Add Products --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-package text-ios-blue"></i>
                        {{ __('barcode.select_products') }}
                    </h3>

                    <div class="relative mb-4">
                        <input type="text" id="product-search"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:ring-4 focus:ring-ios-blue/20 outline-none transition-all"
                            placeholder="{{ __('barcode.search_products') }}" oninput="filterProducts(this.value)">
                        <i
                            class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    </div>

                    <div id="products-list" class="max-h-[400px] overflow-y-auto space-y-2">
                        @foreach ($products as $product)
                            <div class="product-item p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition cursor-pointer"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                data-name-th="{{ $product->name_th }}" data-sku="{{ $product->sku }}"
                                data-barcode="{{ $product->barcode }}" data-price="{{ $product->unit_price }}"
                                onclick="addProduct(this)">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                                        <i class="ph-fill ph-pill text-orange-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 font-mono">{{ $product->sku }}
                                            {{ $product->barcode ? '· ' . $product->barcode : '' }}</p>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-green-600">฿{{ number_format($product->unit_price, 0) }}</span>
                                    <button type="button"
                                        class="w-8 h-8 rounded-lg bg-ios-blue/10 text-ios-blue hover:bg-ios-blue hover:text-white transition flex items-center justify-center">
                                        <i class="ph-bold ph-plus"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Selected Products --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="ph-fill ph-check-square text-green-500"></i>
                            {{ __('barcode.selected_products') }}
                        </h3>
                        <span class="text-sm text-gray-500">
                            {{ __('barcode.total_labels') }}: <strong id="total-labels" class="text-ios-blue">0</strong>
                        </span>
                    </div>

                    <div id="selected-products" class="space-y-3">
                        <div id="no-products-selected" class="text-center py-8 text-gray-400">
                            <i class="ph ph-package text-4xl mb-2"></i>
                            <p>{{ __('barcode.no_products_selected') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Settings --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Label Settings --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-gear text-purple-500"></i>
                        {{ __('barcode.label_settings') }}
                    </h3>

                    <div class="space-y-4">
                        {{-- Label Size --}}
                        <div>
                            <label
                                class="text-sm font-medium text-gray-700 mb-2 block">{{ __('barcode.label_size') }}</label>
                            <div class="space-y-2">
                                <label
                                    class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <input type="radio" name="label_size" value="small" class="w-4 h-4 text-ios-blue">
                                    <span class="text-sm font-medium">{{ __('barcode.size_small') }}</span>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <input type="radio" name="label_size" value="medium" checked
                                        class="w-4 h-4 text-ios-blue">
                                    <span class="text-sm font-medium">{{ __('barcode.size_medium') }}</span>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <input type="radio" name="label_size" value="large" class="w-4 h-4 text-ios-blue">
                                    <span class="text-sm font-medium">{{ __('barcode.size_large') }}</span>
                                </label>
                            </div>
                        </div>

                        {{-- Display Options --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-2 block">Display Options</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="show_barcode" value="1" checked class="checkbox-ios">
                                    <span class="text-sm font-medium">{{ __('barcode.show_barcode') }}</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="show_price" value="1" checked class="checkbox-ios">
                                    <span class="text-sm font-medium">{{ __('barcode.show_price') }}</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="show_sku" value="1" checked class="checkbox-ios">
                                    <span class="text-sm font-medium">{{ __('barcode.show_sku') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Print Button --}}
                <button type="submit" id="print-btn" disabled
                    class="w-full px-6 py-4 bg-purple-500 hover:bg-purple-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold rounded-2xl shadow-lg transition flex items-center justify-center gap-3">
                    <i class="ph-bold ph-printer text-xl"></i>
                    {{ __('barcode.print_labels') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let selectedProducts = [];

        function filterProducts(query) {
            const items = document.querySelectorAll('.product-item');
            const lowerQuery = query.toLowerCase();

            items.forEach(item => {
                const name = (item.dataset.name || '').toLowerCase();
                const nameTh = (item.dataset.nameTh || '').toLowerCase();
                const sku = (item.dataset.sku || '').toLowerCase();
                const barcode = (item.dataset.barcode || '').toLowerCase();

                if (name.includes(lowerQuery) || nameTh.includes(lowerQuery) ||
                    sku.includes(lowerQuery) || barcode.includes(lowerQuery)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        function addProduct(element) {
            const id = parseInt(element.dataset.id);
            const existing = selectedProducts.find(p => p.id === id);

            if (existing) {
                existing.quantity++;
            } else {
                selectedProducts.push({
                    id: id,
                    name: element.dataset.name,
                    nameTh: element.dataset.nameTh,
                    sku: element.dataset.sku,
                    barcode: element.dataset.barcode,
                    price: parseFloat(element.dataset.price),
                    quantity: 1
                });
            }

            renderSelectedProducts();
        }

        function removeProduct(id) {
            selectedProducts = selectedProducts.filter(p => p.id !== id);
            renderSelectedProducts();
        }

        function updateQuantity(id, value) {
            const product = selectedProducts.find(p => p.id === id);
            if (product) {
                product.quantity = Math.max(1, Math.min(100, parseInt(value) || 1));
            }
            renderSelectedProducts();
        }

        function renderSelectedProducts() {
            const container = document.getElementById('selected-products');
            const empty = document.getElementById('no-products-selected');
            const printBtn = document.getElementById('print-btn');

            if (selectedProducts.length === 0) {
                container.innerHTML = '';
                container.appendChild(empty);
                empty.classList.remove('hidden');
                printBtn.disabled = true;
                document.getElementById('total-labels').textContent = '0';
                return;
            }

            empty.classList.add('hidden');
            printBtn.disabled = false;

            let totalLabels = 0;

            container.innerHTML = selectedProducts.map((p, index) => {
                totalLabels += p.quantity;
                return `
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                        <input type="hidden" name="products[${index}][id]" value="${p.id}">
                        <input type="hidden" name="products[${index}][quantity]" value="${p.quantity}">
                        <div class="flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">${p.name}</p>
                                <p class="text-xs text-gray-500 font-mono">${p.sku} ${p.barcode ? '· ' + p.barcode : ''}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="updateQuantity(${p.id}, ${p.quantity - 1})" data-no-loading
                                    class="w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition">
                                    <i class="ph-bold ph-minus text-sm"></i>
                                </button>
                                <input type="number" value="${p.quantity}" min="1" max="100"
                                    class="w-16 text-center font-semibold border border-gray-200 rounded-lg py-1"
                                    onchange="updateQuantity(${p.id}, this.value)">
                                <button type="button" onclick="updateQuantity(${p.id}, ${p.quantity + 1})" data-no-loading
                                    class="w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </button>
                            </div>
                            <button type="button" onclick="removeProduct(${p.id})" data-no-loading
                                class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 flex items-center justify-center transition">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            document.getElementById('total-labels').textContent = totalLabels;
        }
    </script>
@endpush

@extends('layouts.app')

@section('title', __('stock_adjustments'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('inventory') }}
        </p>
        <span>{{ __('stock_adjustments') }}</span>
    </div>
@endsection

@section('header-actions')
    <button onclick="StockAdjustmentPage.newAdjustment()" data-no-loading
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('new_adjustment') }}
    </button>
@endsection

@section('content')
    <div>
        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Search + Quick Nav --}}
            <div class="flex items-center gap-2">
                <div class="flex-1 max-w-sm relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" id="adj-search" placeholder="{{ __('search_adj_placeholder') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                </div>
                {{-- Quick Navigation Buttons --}}
                <div class="flex items-center gap-1">
                    <button type="button" onclick="StockAdjustmentPage.goToFirst()" class="quick-nav-btn"
                        title="{{ __('first_item') }}">
                        <i class="ph ph-caret-double-left"></i>
                    </button>
                    <button type="button" onclick="StockAdjustmentPage.goToLatest()" class="quick-nav-btn"
                        title="{{ __('latest_item') }}">
                        <i class="ph ph-caret-double-right"></i>
                    </button>
                </div>
            </div>

            {{-- Right: Filter --}}
            <div class="flex items-center gap-2">
                <select id="adj-type-filter" onchange="StockAdjustmentPage.applyFilter(this.value)"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="all">{{ __('all_types') }}</option>
                    <option value="increase">{{ __('increase') }}</option>
                    <option value="decrease">{{ __('decrease') }}</option>
                    <option value="set">{{ __('set_absolute') }}</option>
                </select>
                <select id="adj-sort" onchange="StockAdjustmentPage.applySorting(this.value)"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="newest">{{ __('sort_newest_first') }}</option>
                    <option value="oldest">{{ __('sort_oldest_first') }}</option>
                </select>
            </div>
        </div>

        <div id="adj-list-container">
            @include('stock_adjustments.partials.list')
        </div>
    </div>

    {{-- New Adjustment Modal --}}
    <div id="adj-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'adj-modal')"></div>
    <div id="adj-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 42rem;">
        <div class="modal-header">
            <h2 id="adj-modal-title" class="modal-title">{{ __('new_adjustment') }}</h2>
            <button type="button" onclick="toggleModal(false, 'adj-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <form id="adj-form" method="POST" action="{{ route('stock-adjustments.store') }}">
            @csrf
            <div class="modal-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Product Selection --}}
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('product') }} <span class="text-red-500">*</span></label>
                        <select name="product_id" id="prod-select" class="form-input" required
                            onchange="StockAdjustmentPage.loadLots(this.value)">
                            <option value="">{{ __('select_product') }}</option>
                            @foreach (\App\Models\Product::orderBy('name')->get() as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lot Selection --}}
                    <div class="form-group">
                        <label class="form-label">{{ __('lot') }}</label>
                        <select name="product_lot_id" id="lot-select" class="form-input">
                            <option value="">{{ __('select_lot_optional') }}</option>
                        </select>
                    </div>

                    {{-- Adj Type --}}
                    <div class="form-group">
                        <label class="form-label">{{ __('adj_type') }} <span class="text-red-500">*</span></label>
                        <select name="type" class="form-input" required>
                            <option value="increase">{{ __('increase') }}</option>
                            <option value="decrease">{{ __('decrease') }}</option>
                            <option value="set">{{ __('set_absolute') }}</option>
                        </select>
                    </div>

                    {{-- Quantity --}}
                    <div class="form-group">
                        <label class="form-label">{{ __('quantity') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" class="form-input" required min="1">
                    </div>

                    {{-- Reason --}}
                    <div class="form-group">
                        <label class="form-label">{{ __('reason') }} <span class="text-red-500">*</span></label>
                        <select name="reason" class="form-input" required>
                            <option value="Damaged">{{ __('reason_damaged') }} (Damaged)</option>
                            <option value="Expired">{{ __('reason_expired') }} (Expired)</option>
                            <option value="Lost/Stolen">{{ __('reason_lost') }} (Lost/Stolen)</option>
                            <option value="Audit Correction">{{ __('reason_audit') }} (Audit Correction)</option>
                            <option value="Internal Use">{{ __('reason_internal') }} (Internal Use)</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="form-group md:col-span-2">
                        <label class="form-label">{{ __('notes') }}</label>
                        <textarea name="notes" class="form-input min-h-[80px]" placeholder="{{ __('adjustment_note_placeholder') }}"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="toggleModal(false, 'adj-modal')"
                    class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                    {{ __('cancel') }}
                </button>
                <button type="submit" data-no-loading
                    class="px-6 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i>
                    {{ __('save_and_approve') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const StockAdjustmentPage = {
            newAdjustment() {
                document.getElementById('adj-form').reset();
                document.getElementById('lot-select').innerHTML =
                    '<option value="">{{ __('select_lot_optional') }}</option>';
                ModalSystem.open('adj-modal');
            },

            async loadLots(productId) {
                const lotSelect = document.getElementById('lot-select');
                lotSelect.innerHTML = '<option value="">{{ __('loading') }}...</option>';

                if (!productId) {
                    lotSelect.innerHTML = '<option value="">{{ __('select_lot_optional') }}</option>';
                    return;
                }

                try {
                    const response = await fetch(`/api/products/${productId}/lots`);
                    const lots = await response.json();

                    let html = '<option value="">{{ __('select_lot_optional') }}</option>';
                    lots.forEach(lot => {
                        html +=
                            `<option value="${lot.id}">Lot: ${lot.lot_number} (Exp: ${lot.expiry_date}) - {{ __('stock') }}: ${lot.quantity}</option>`;
                    });
                    lotSelect.innerHTML = html;
                } catch (error) {
                    console.error('Error loading lots:', error);
                    lotSelect.innerHTML = '<option value="">{{ __('select_lot_optional') }}</option>';
                }
            },

            goToFirst() {
                const url = new URL(window.location);
                url.searchParams.set('sort', 'oldest');
                window.location.href = url.toString();
            },

            goToLatest() {
                const url = new URL(window.location);
                url.searchParams.set('sort', 'newest');
                window.location.href = url.toString();
            },

            applyFilter(value) {
                const url = new URL(window.location);
                if (value === 'all') {
                    url.searchParams.delete('type');
                } else {
                    url.searchParams.set('type', value);
                }
                window.location.href = url.toString();
            },

            applySorting(value) {
                const url = new URL(window.location);
                url.searchParams.set('sort', value);
                window.location.href = url.toString();
            },

            toggleSelectAll(checkbox) {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                this.updateBulkBarVisibility();
            },

            updateBulkBar(checkbox) {
                this.updateBulkBarVisibility();
            },

            updateBulkBarVisibility() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                const bulkBar = document.getElementById('bulk-action-bar');
                if (bulkBar) {
                    if (checked.length > 0) {
                        bulkBar.classList.remove('bulk-action-bar-hidden');
                        document.getElementById('selected-count').textContent = checked.length;
                    } else {
                        bulkBar.classList.add('bulk-action-bar-hidden');
                    }
                }
            }
        };

        // Make available globally
        window.StockAdjustmentPage = StockAdjustmentPage;

        // Form submit loader removed as per user request to avoid overlay blocking
        // document.getElementById('adj-form').addEventListener('submit', function() {
        //     const loader = document.getElementById('pill-loading');
        //     if (loader) loader.classList.add('active');
        // });

        // Set initial filter/sort values from URL
        document.addEventListener('DOMContentLoaded', function() {
            const url = new URL(window.location);
            const type = url.searchParams.get('type');
            const sort = url.searchParams.get('sort');

            if (type) {
                const typeSelect = document.getElementById('adj-type-filter');
                if (typeSelect) typeSelect.value = type;
            }
            if (sort) {
                const sortSelect = document.getElementById('adj-sort');
                if (sortSelect) sortSelect.value = sort;
            }
        });
    </script>
@endpush

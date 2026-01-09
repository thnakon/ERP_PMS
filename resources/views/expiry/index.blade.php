@extends('layouts.app')

@section('title', 'Expiry Management')
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('inventory') }}
        </p>
        <span>Expiry Management</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-3">
        <div class="flex items-center px-4 py-2 bg-gray-100 rounded-xl border border-gray-200 shadow-sm gap-3">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('expiry.total') }}</span>
            <span class="text-lg font-black text-gray-900 leading-none">{{ $stats['total'] }}</span>
        </div>
        <a href="{{ route('expiry.export', ['days' => $days]) }}"
            class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl shadow-sm transition active-scale flex items-center gap-2">
            <i class="ph ph-download-simple"></i>
            {{ __('expiry.export_csv') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="ph-bold ph-calendar-x text-red-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ __('expiry.expired') }}</span>
                </div>
                <h3 id="stat-expired" class="text-xl font-black text-red-600">{{ $stats['expired'] }}</h3>
            </div>

            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-warning-circle text-orange-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">{{ __('expiry.critical') }}</span>
                </div>
                <h3 id="stat-critical" class="text-xl font-black text-orange-600">{{ $stats['critical'] }}</h3>
            </div>

            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="ph-bold ph-warning text-yellow-600 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-yellow-600 uppercase tracking-wider">{{ __('expiry.warning') }}</span>
                </div>
                <h3 id="stat-warning" class="text-xl font-black text-yellow-600">{{ $stats['warning'] }}</h3>
            </div>

            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-info text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('expiry.notice') }}</span>
                </div>
                <h3 id="stat-notice" class="text-xl font-black text-blue-600">{{ $stats['notice'] }}</h3>
            </div>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-2">
            {{-- Left: Search Bar --}}
            <div class="relative w-64 md:w-72">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="expiry-search" placeholder="{{ __('search_placeholder') }}"
                    value="{{ $search }}"
                    class="w-full bg-white border border-gray-200 rounded-full py-2 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm text-sm">
            </div>

            {{-- Center: Status Filters (Segmented Control style) --}}
            <div class="flex bg-gray-100 p-1 rounded-xl">
                <button type="button" onclick="loadExpiry('all')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'all' ? 'bg-white shadow-sm text-ios-blue' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('expiry.all_status') }}
                </button>
                <button type="button" onclick="loadExpiry('expired')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'expired' ? 'bg-white shadow-sm text-red-500 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('expiry.expired_only') }}
                </button>
                <button type="button" onclick="loadExpiry('expiring')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'expiring' ? 'bg-white shadow-sm text-orange-500 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('expiry.expiring_only') }}
                </button>
            </div>

            {{-- Right: Time Filter --}}
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-400 mr-1">{{ __('general.filter') ?? 'Filter' }}:</span>
                <select id="expiry-days-selector"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm transition-all">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>{{ __('expiry.next_7_days') }}</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>{{ __('expiry.next_30_days') }}</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>{{ __('expiry.next_90_days') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div id="selection-header" class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all-stack" class="checkbox-ios" onchange="toggleSelectAll(this)">
                <label for="select-all-stack" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $lots->total() }}</span> {{ __('expiry.expiring_lots') }}
            </div>
        </div>

        {{-- Expiry Stack List --}}
        <div class="stack-container shadow-none space-y-1">
            @forelse($lots as $lot)
                @php
                    $daysLeft = $lot->days_until_expiry;
                    $lotStatus = $lot->expiry_status;
                @endphp
                <div class="stack-item group transition-all duration-300 {{ $lotStatus === 'expired' ? 'border-red-200 bg-red-50/10' : '' }}"
                    id="lot-{{ $lot->id }}">
                    {{-- Checkbox --}}
                    <div class="flex items-center pr-4">
                        <input type="checkbox" value="{{ $lot->id }}" onchange="updateBulkBar(this)"
                            class="row-checkbox checkbox-ios">
                    </div>

                    {{-- Product Info --}}
                    <div class="stack-col stack-main">
                        <span class="stack-label">{{ __('expiry.product') }}</span>
                        <div class="stack-value text-base font-bold text-gray-900">{{ $lot->product->localized_name }}
                        </div>
                        <div class="text-xs text-xs font-medium text-gray-400 mt-0.5">{{ $lot->product->sku }}</div>
                    </div>

                    {{-- Lot & Ref --}}
                    <div class="stack-col stack-data flex-1">
                        <span class="stack-label">{{ __('expiry.lot_number') }}</span>
                        <div class="flex flex-col">
                            <span class="stack-value font-mono text-sm text-gray-700">#{{ $lot->lot_number }}</span>
                            @if ($lot->gr_reference)
                                <span class="text-[10px] text-gray-400 font-medium">Ref: {{ $lot->gr_reference }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Supplier --}}
                    <div class="stack-col stack-data hidden lg:flex" style="flex: 0 0 150px;">
                        <span class="stack-label">{{ __('expiry.supplier') }}</span>
                        <span class="stack-value text-sm text-gray-600 truncate">
                            {{ $lot->supplierRel?->name ?? ($lot->supplier ?? '-') }}
                        </span>
                    </div>

                    {{-- Stock (Centered) --}}
                    <div class="stack-col stack-data flex items-center justify-center" style="flex: 0 0 100px;">
                        <span class="stack-label text-center w-full">{{ __('expiry.quantity') }}</span>
                        <div class="flex justify-center">
                            <div
                                class="px-3 py-1 bg-gray-100 rounded-full text-sm font-bold text-gray-700 border border-gray-200">
                                {{ $lot->quantity }}
                            </div>
                        </div>
                    </div>

                    {{-- Expiry (The Hero Section) --}}
                    <div class="stack-col stack-data" style="flex: 0 0 160px;">
                        <span class="stack-label">{{ __('expiry.expiry_date') }}</span>
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-black {{ $lotStatus === 'expired' ? 'text-red-500' : ($lotStatus === 'critical' ? 'text-orange-500' : 'text-gray-900') }}">
                                {{ $lot->expiry_date->format('d M Y') }}
                            </span>
                            <span
                                class="text-[10px] font-bold uppercase tracking-tight {{ $lotStatus === 'expired' ? 'text-red-500' : 'text-gray-500' }}">
                                @if ($lotStatus === 'expired')
                                    {{ __('expiry.expired') }}
                                @else
                                    {{ $daysLeft }} {{ __('expiry.days_left') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="stack-col stack-data flex items-center justify-end" style="flex: 0 0 100px;">
                        @switch($lotStatus)
                            @case('expired')
                                <span class="badge badge-danger">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('expiry.expired') }}
                                </span>
                            @break

                            @case('critical')
                                <span class="badge badge-danger">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('expiry.critical') }}
                                </span>
                            @break

                            @case('warning')
                                <span class="badge badge-warning">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    {{ __('expiry.warning') }}
                                </span>
                            @break

                            @case('notice')
                                <span class="badge badge-info">
                                    <span class="badge-dot badge-dot-info"></span>
                                    {{ __('expiry.notice') }}
                                </span>
                            @break

                            @default
                                <span class="badge badge-success">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('expiry.good') }}
                                </span>
                        @endswitch
                    </div>

                    {{-- Quick Action Button --}}
                    <div class="stack-actions">
                        @if ($lotStatus === 'expired' || $lotStatus === 'critical')
                            <button type="button"
                                onclick="ExpiryPage.quickAdjust({{ $lot->product_id }}, {{ $lot->id }}, '{{ $lot->product->name }}', '{{ $lot->lot_number }}', {{ $lot->quantity }})"
                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 active-scale"
                                title="{{ __('expiry.adjust_stock') }}">
                                <i class="ph-bold ph-arrow-down"></i>
                                {{ __('expiry.write_off') }}
                            </button>
                        @else
                            <button type="button"
                                onclick="ExpiryPage.quickAdjust({{ $lot->product_id }}, {{ $lot->id }}, '{{ $lot->product->name }}', '{{ $lot->lot_number }}', {{ $lot->quantity }})"
                                class="stack-action-circle hover:bg-gray-100" title="{{ __('expiry.adjust_stock') }}">
                                <i class="ph ph-pencil-simple-line text-gray-400"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                        <i class="ph ph-calendar-check text-4xl mb-3"></i>
                        <p class="font-medium">{{ __('expiry.no_expiring') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm font-medium text-gray-400">
                    {{ __('general.showing') }}
                    <span class="text-gray-900 font-bold">{{ $lots->firstItem() ?? 0 }}</span>
                    - <span class="text-gray-900 font-bold">{{ $lots->lastItem() ?? 0 }}</span>
                    {{ __('general.of') }}
                    <span class="text-gray-900 font-bold">{{ $lots->total() }}</span>
                </div>
                <div class="flex items-center gap-1">
                    {{ $lots->links('pagination.apple') }}
                </div>
            </div>
        </div>

        {{-- Quick Adjustment Modal --}}
        <div id="quick-adj-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
            onclick="toggleModal(false, 'quick-adj-modal')"></div>
        <div id="quick-adj-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 28rem;">
            <div class="modal-header">
                <h2 class="modal-title">{{ __('expiry.quick_adjust') }}</h2>
                <button type="button" onclick="toggleModal(false, 'quick-adj-modal')" class="modal-close-btn">
                    <i class="ph-bold ph-x text-gray-500"></i>
                </button>
            </div>

            <form id="quick-adj-form" method="POST" action="{{ route('stock-adjustments.store') }}">
                @csrf
                <input type="hidden" name="product_id" id="qa-product-id">
                <input type="hidden" name="product_lot_id" id="qa-lot-id">
                <input type="hidden" name="type" value="decrease">

                <div class="modal-content">
                    {{-- Product Info Display --}}
                    <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="ph-bold ph-warning-circle text-red-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900" id="qa-product-name">-</p>
                                <p class="text-sm text-gray-500">Lot: <span id="qa-lot-number">-</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Current Stock --}}
                    <div class="mb-4">
                        <label class="form-label">{{ __('current_stock') }}</label>
                        <div class="text-2xl font-black text-gray-900" id="qa-current-stock">0</div>
                    </div>

                    {{-- Quantity to Remove --}}
                    <div class="mb-4">
                        <label class="form-label">{{ __('expiry.qty_to_remove') }} <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="qa-quantity" class="form-input" required min="1">
                        <p class="text-xs text-gray-400 mt-1">{{ __('expiry.qty_hint') }}</p>
                    </div>

                    {{-- Reason --}}
                    <div class="mb-4">
                        <label class="form-label">{{ __('reason') }} <span class="text-red-500">*</span></label>
                        <select name="reason" class="form-input" required>
                            <option value="Expired" selected>{{ __('reason_expired') }} (Expired)</option>
                            <option value="Damaged">{{ __('reason_damaged') }} (Damaged)</option>
                            <option value="Internal Use">{{ __('reason_internal') }} (Internal Use)</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="form-label">{{ __('notes') }}</label>
                        <textarea name="notes" class="form-input min-h-[60px]" placeholder="{{ __('expiry.notes_placeholder') }}"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="toggleModal(false, 'quick-adj-modal')"
                        class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                        {{ __('cancel') }}
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                        <i class="ph-bold ph-trash"></i>
                        {{ __('expiry.confirm_writeoff') }}
                    </button>
                </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        <script>
            const ExpiryPage = {
                quickAdjust(productId, lotId, productName, lotNumber, currentQty) {
                    document.getElementById('qa-product-id').value = productId;
                    document.getElementById('qa-lot-id').value = lotId;
                    document.getElementById('qa-product-name').textContent = productName;
                    document.getElementById('qa-lot-number').textContent = lotNumber;
                    document.getElementById('qa-current-stock').textContent = currentQty;
                    document.getElementById('qa-quantity').value = currentQty;
                    document.getElementById('qa-quantity').max = currentQty;

                    toggleModal(true, 'quick-adj-modal');
                }
            };

            window.ExpiryPage = ExpiryPage;

            function loadExpiry(status) {
                const days = document.getElementById('expiry-days-selector').value;
                const search = document.getElementById('expiry-search').value;
                window.location.href = `{{ route('expiry.index') }}?days=${days}&status=${status}&search=${search}`;
            }

            // Handle days selector change
            document.getElementById('expiry-days-selector')?.addEventListener('change', function() {
                const status = '{{ $status }}';
                const search = document.getElementById('expiry-search').value;
                window.location.href =
                    `{{ route('expiry.index') }}?days=${this.value}&status=${status}&search=${search}`;
            });

            // Handle search input
            document.getElementById('expiry-search')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const days = document.getElementById('expiry-days-selector').value;
                    const status = '{{ $status }}';
                    window.location.href =
                        `{{ route('expiry.index') }}?days=${days}&status=${status}&search=${this.value}`;
                }
            });
        </script>
    @endpush

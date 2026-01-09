@extends('layouts.app')

@section('title', __('suppliers.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('purchasing') }}
        </p>
        <span>{{ __('suppliers.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <button type="button" onclick="SupplierPage.openModal()" data-no-loading
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('suppliers.add_new') }}
    </button>
@endsection

@section('content')
    <div>
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-ios-blue/10 flex items-center justify-center">
                        <i class="ph-bold ph-buildings text-ios-blue text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('suppliers.total_suppliers') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['active'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('suppliers.active_suppliers') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="flex items-center justify-between gap-4 mb-2">
            {{-- Search --}}
            <div class="relative w-64 md:w-80">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="supplier-search" placeholder="{{ __('search_placeholder') }}"
                    value="{{ request('search') }}"
                    class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
            </div>

            {{-- Filters --}}
            <div class="flex items-center gap-2">
                <select id="status-filter" onchange="SupplierPage.applyFilter()"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>
                        {{ __('suppliers.all_status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ __('suppliers.active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        {{ __('suppliers.inactive') }}</option>
                </select>
                <select id="sort-filter" onchange="SupplierPage.applyFilter()"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>
                        {{ __('suppliers.sort_name') }}</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                        {{ __('suppliers.sort_newest') }}</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                        {{ __('suppliers.sort_oldest') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all" class="checkbox-ios" onchange="toggleSelectAll(this)">
                <label for="select-all" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $suppliers->total() }}</span>
                {{ __('suppliers.total_suppliers') }}
            </div>
        </div>

        {{-- List --}}
        <div class="stack-container shadow-none space-y-1" id="supplier-list">
            @forelse($suppliers as $supplier)
                <div class="stack-item hover:bg-gray-50/50 transition-colors" data-id="{{ $supplier->id }}">
                    {{-- Checkbox --}}
                    <div class="flex items-center pr-4">
                        <input type="checkbox" value="{{ $supplier->id }}" class="row-checkbox checkbox-ios"
                            onchange="updateBulkBar(this)">
                    </div>

                    {{-- Company Name --}}
                    <div class="stack-col stack-main" style="flex: 0 0 200px;">
                        <span class="stack-label">{{ __('suppliers.name') }}</span>
                        <div class="stack-value font-bold">{{ $supplier->name }}</div>
                        @if ($supplier->tax_id)
                            <div class="text-[10px] text-gray-400 font-medium">TAX: {{ $supplier->tax_id }}</div>
                        @endif
                    </div>

                    {{-- Contact --}}
                    <div class="stack-col stack-data flex-1">
                        <span class="stack-label">{{ __('suppliers.contact_person') }}</span>
                        <div class="stack-value text-sm">{{ $supplier->contact_person ?? '-' }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $supplier->mobile ?? ($supplier->phone ?? '-') }}
                        </div>
                    </div>

                    {{-- Trade Terms --}}
                    <div class="stack-col stack-data" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('suppliers.credit_term') }}</span>
                        <div class="stack-value text-sm font-semibold">{{ $supplier->credit_term }}
                            {{ __('suppliers.credit_term_days') }}</div>
                    </div>

                    {{-- PO Count --}}
                    <div class="stack-col stack-data" style="flex: 0 0 80px;">
                        <span class="stack-label">{{ __('suppliers.po_count') }}</span>
                        <div class="stack-value text-sm font-bold text-ios-blue">{{ $supplier->purchase_orders_count }}
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="stack-col stack-data" style="flex: 0 0 80px;">
                        <span class="stack-label">{{ __('status') }}</span>
                        @if ($supplier->is_active)
                            <span class="badge badge-success">
                                <span class="badge-dot badge-dot-success"></span>
                                {{ __('suppliers.active') }}
                            </span>
                        @else
                            <span class="badge badge-gray">
                                <span class="badge-dot badge-dot-gray"></span>
                                {{ __('suppliers.inactive') }}
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="stack-actions">
                        <div class="ios-dropdown">
                            <button type="button" class="stack-action-circle">
                                <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                            </button>
                            <div class="ios-dropdown-menu">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="ios-dropdown-item">
                                    <i class="ph ph-eye ios-dropdown-icon text-ios-blue"></i>
                                    <span>{{ __('view') }}</span>
                                </a>
                                <button type="button" onclick="SupplierPage.editSupplier({{ $supplier->id }})"
                                    data-no-loading class="ios-dropdown-item">
                                    <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                    <span>{{ __('edit') }}</span>
                                </button>
                                <div class="h-px bg-gray-100 my-1"></div>
                                <button type="button"
                                    onclick="SupplierPage.deleteSupplier('{{ route('suppliers.destroy', $supplier) }}')"
                                    class="ios-dropdown-item ios-dropdown-item-danger">
                                    <i class="ph ph-trash ios-dropdown-icon"></i>
                                    <span>{{ __('delete') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                    <i class="ph ph-buildings text-4xl mb-3"></i>
                    <p class="font-medium">{{ __('suppliers.no_suppliers') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($suppliers->hasPages())
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm font-medium text-gray-400">
                    {{ __('general.showing') }}
                    <span class="text-gray-900 font-bold">{{ $suppliers->firstItem() ?? 0 }}</span>
                    - <span class="text-gray-900 font-bold">{{ $suppliers->lastItem() ?? 0 }}</span>
                    {{ __('general.of') }}
                    <span class="text-gray-900 font-bold">{{ $suppliers->total() }}</span>
                </div>
                <div class="flex items-center gap-1">
                    {{ $suppliers->links('pagination.apple') }}
                </div>
            </div>
        @endif
    </div>

    {{-- Add/Edit Modal --}}
    <div id="supplier-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'supplier-modal')"></div>
    <div id="supplier-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 48rem;">
        <div class="modal-header">
            <h2 class="modal-title" id="modal-title">{{ __('suppliers.add_new') }}</h2>
            <button type="button" onclick="toggleModal(false, 'supplier-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <form id="supplier-form" method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <div class="modal-content max-h-[70vh] overflow-y-auto">
                {{-- Company Info --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-buildings text-ios-blue"></i>
                        {{ __('suppliers.company_info') }}
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="form-label">{{ __('suppliers.name') }} <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="inp-name" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.tax_id') }}</label>
                            <input type="text" name="tax_id" id="inp-tax_id" class="form-input"
                                placeholder="0-0000-00000-00-0">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">{{ __('suppliers.address') }}</label>
                            <textarea name="address" id="inp-address" class="form-input min-h-[60px]"></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">{{ __('suppliers.shipping_address') }}</label>
                            <textarea name="shipping_address" id="inp-shipping_address" class="form-input min-h-[60px]"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-user text-ios-blue"></i>
                        {{ __('suppliers.contact_info') }}
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('suppliers.contact_person') }}</label>
                            <input type="text" name="contact_person" id="inp-contact_person" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.phone') }}</label>
                            <input type="text" name="phone" id="inp-phone" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.mobile') }}</label>
                            <input type="text" name="mobile" id="inp-mobile" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.email') }}</label>
                            <input type="email" name="email" id="inp-email" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.line_id') }}</label>
                            <input type="text" name="line_id" id="inp-line_id" class="form-input">
                        </div>
                    </div>
                </div>

                {{-- Trade Terms --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-handshake text-ios-blue"></i>
                        {{ __('suppliers.trade_terms') }}
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">{{ __('suppliers.credit_term') }}</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="credit_term" id="inp-credit_term" class="form-input"
                                    value="30" min="0">
                                <span class="text-sm text-gray-500">{{ __('suppliers.credit_term_days') }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.lead_time') }}</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="lead_time" id="inp-lead_time" class="form-input"
                                    value="3" min="0">
                                <span class="text-sm text-gray-500">{{ __('suppliers.lead_time_days') }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.min_order_qty') }}</label>
                            <input type="number" name="min_order_qty" id="inp-min_order_qty" class="form-input"
                                value="0" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                {{-- Banking --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-bank text-ios-blue"></i>
                        {{ __('suppliers.banking_info') }}
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">{{ __('suppliers.bank_name') }}</label>
                            <input type="text" name="bank_name" id="inp-bank_name" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.bank_account_no') }}</label>
                            <input type="text" name="bank_account_no" id="inp-bank_account_no" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.bank_account_name') }}</label>
                            <input type="text" name="bank_account_name" id="inp-bank_account_name"
                                class="form-input">
                        </div>
                    </div>
                </div>

                {{-- Status & Notes --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-note text-ios-blue"></i>
                        {{ __('suppliers.status_notes') }}
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center gap-3">
                            <label class="apple-toggle">
                                <input type="checkbox" name="is_active" id="inp-is_active" checked>
                                <span class="toggle-track">
                                    <span class="toggle-thumb"></span>
                                </span>
                            </label>
                            <span class="text-sm font-medium text-gray-700">{{ __('suppliers.is_active') }}</span>
                        </div>
                        <div>
                            <label class="form-label">{{ __('suppliers.notes') }}</label>
                            <textarea name="notes" id="inp-notes" class="form-input min-h-[60px]"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="toggleModal(false, 'supplier-modal')"
                    class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                    {{ __('cancel') }}
                </button>
                <button type="submit" data-no-loading
                    class="px-6 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i>
                    {{ __('save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const SupplierPage = {
            openModal() {
                document.getElementById('supplier-form').reset();
                document.getElementById('supplier-form').action = '{{ route('suppliers.store') }}';
                document.getElementById('form-method').value = 'POST';
                document.getElementById('modal-title').textContent = '{{ __('suppliers.add_new') }}';
                document.getElementById('inp-is_active').checked = true;
                toggleModal(true, 'supplier-modal');
            },

            async editSupplier(id) {
                try {
                    const response = await fetch(`/suppliers/${id}`);
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // In real implementation, you'd have a JSON API endpoint
                    // For now, redirect to show page for editing
                    window.location.href = `/suppliers/${id}`;
                } catch (error) {
                    console.error('Error:', error);
                }
            },

            applyFilter() {
                const search = document.getElementById('supplier-search').value;
                const status = document.getElementById('status-filter').value;
                const sort = document.getElementById('sort-filter').value;

                const params = new URLSearchParams();
                if (search) params.set('search', search);
                if (status !== 'all') params.set('status', status);
                if (sort !== 'name') params.set('sort', sort);

                window.location.href = `{{ route('suppliers.index') }}?${params.toString()}`;
            },

            toggleSelectAll(checkbox) {
                // Handled by global toggleSelectAll
            },

            deleteUrl: null,

            deleteSupplier(url) {
                this.deleteUrl = url;
                if (typeof toggleModal === 'function') {
                    toggleModal(true, 'delete-modal');
                }
            },

            executeDelete() {
                if (!this.deleteUrl) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.deleteUrl;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        };

        window.SupplierPage = SupplierPage;

        // Override executeDelete for suppliers page
        const originalExecuteDelete = window.executeDelete;
        window.executeDelete = function() {
            if (SupplierPage.deleteUrl) {
                SupplierPage.executeDelete();
            } else if (typeof originalExecuteDelete === 'function') {
                originalExecuteDelete();
            }
        };

        // Search on Enter
        document.getElementById('supplier-search')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                SupplierPage.applyFilter();
            }
        });
    </script>
@endpush

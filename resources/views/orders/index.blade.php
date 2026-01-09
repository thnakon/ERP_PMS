@extends('layouts.app')

@section('title', __('orders.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('sales_operations') }}
        </p>
        <span>{{ __('orders.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-4">
        {{-- Quick Stats: POS Inspired --}}
        <div class="hidden md:flex items-center gap-4 mr-2">
            <div class="text-center px-4">
                <div class="text-lg font-bold text-gray-900">฿{{ number_format($stats['today_sales'], 2) }}</div>
                <div class="text-[10px] text-gray-500 uppercase tracking-wide">{{ __('orders.today_sales') }}</div>
            </div>

            <div class="w-px h-8 bg-gray-200"></div>

            <div class="text-lg font-bold text-gray-900">{{ number_format($stats['total']) }}</div>
            <div class="text-[10px] text-gray-500 uppercase tracking-wide">{{ __('orders.total_orders') }}</div>
        </div>

        <div class="w-px h-8 bg-gray-200"></div>

        <div class="text-center px-4">
            <div class="text-lg font-bold text-orange-600">{{ number_format($stats['refunded']) }}</div>
            <div class="text-[10px] text-gray-500 uppercase tracking-wide">{{ __('orders.refunded') }}</div>
        </div>
    </div>

    <a href="#"
        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl shadow-sm transition active-scale flex items-center gap-2">
        <i class="ph ph-download-simple"></i>
        <span class="hidden sm:inline">{{ __('orders.export') }}</span>
    </a>
    </div>
@endsection

@section('content')
    <div>
        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-2">
            {{-- Left: Search Bar --}}
            <div class="relative w-64 md:w-80">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="orders-search" placeholder="{{ __('orders.search_placeholder') }}"
                    value="{{ $search }}"
                    class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm text-sm">
            </div>

            {{-- Right: Date and Payment Filters --}}
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-gray-200 shadow-sm">
                    <input type="date" id="date-from" value="{{ $dateFrom }}"
                        class="bg-transparent border-none p-0 text-sm focus:ring-0 outline-none">
                    <span class="text-gray-300">|</span>
                    <input type="date" id="date-to" value="{{ $dateTo }}"
                        class="bg-transparent border-none p-0 text-sm focus:ring-0 outline-none">
                </div>
                <select id="payment-method-selector"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm transition-all">
                    <option value="all" {{ $paymentMethod == 'all' ? 'selected' : '' }}>{{ __('orders.all_payments') }}
                    </option>
                    <option value="cash" {{ $paymentMethod == 'cash' ? 'selected' : '' }}>{{ __('orders.cash') }}
                    </option>
                    <option value="qr" {{ $paymentMethod == 'qr' ? 'selected' : '' }}>{{ __('orders.qr') }}</option>
                    <option value="card" {{ $paymentMethod == 'card' ? 'selected' : '' }}>{{ __('orders.card') }}
                    </option>
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

            {{-- Status Filters (Moved here) --}}
            <div class="flex bg-gray-200/50 p-1 rounded-xl">
                <button type="button" onclick="filterOrders('all')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'all' ? 'bg-white shadow-sm text-ios-blue' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('orders.all') }}
                </button>
                <button type="button" onclick="filterOrders('completed')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'completed' ? 'bg-white shadow-sm text-green-500 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('orders.completed') }}
                </button>
                <button type="button" onclick="filterOrders('refunded')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'refunded' ? 'bg-white shadow-sm text-orange-500 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('orders.refunded') }}
                </button>
                <button type="button" onclick="filterOrders('void')"
                    class="px-4 py-1.5 rounded-lg font-medium text-sm transition {{ $status == 'void' ? 'bg-white shadow-sm text-red-500 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ __('orders.voided') }}
                </button>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $orders->total() }}</span> {{ __('orders.all_orders') }}
            </div>
        </div>

        {{-- Orders Stack List --}}
        <div class="stack-container shadow-none space-y-1">
            @forelse($orders as $order)
                <div class="stack-item group transition-all duration-300 {{ $order->status === 'refunded' ? 'border-orange-200 bg-orange-50/10' : ($order->status === 'void' ? 'border-red-200 bg-red-50/10' : '') }}"
                    id="order-{{ $order->id }}">
                    {{-- Checkbox --}}
                    <div class="flex items-center pr-4">
                        <input type="checkbox" value="{{ $order->id }}" onchange="updateBulkBar(this)"
                            class="row-checkbox checkbox-ios">
                    </div>

                    {{-- Order Number & Date --}}
                    <div class="stack-col stack-main" style="flex: 0 0 160px;">
                        <span class="stack-label">{{ __('orders.order_number') }}</span>
                        <div class="stack-value text-sm font-bold text-gray-900">{{ $order->order_number }}</div>
                        <div class="text-[10px] font-medium text-gray-400 mt-0.5">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                    {{-- Customer --}}
                    <div class="stack-col stack-data flex-1">
                        <span class="stack-label">{{ __('orders.customer') }}</span>
                        <div class="flex flex-col">
                            <span class="stack-value text-sm text-gray-700">
                                {{ $order->customer?->name ?? __('orders.walk_in') }}
                            </span>
                            @if ($order->customer?->phone)
                                <span class="text-[10px] text-gray-400 font-medium">{{ $order->customer->phone }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Cashier --}}
                    <div class="stack-col stack-data hidden lg:flex" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('orders.cashier') }}</span>
                        <span class="stack-value text-sm text-gray-600 truncate">
                            {{ $order->user?->name ?? '-' }}
                        </span>
                    </div>

                    {{-- Items Count (Centered) --}}
                    <div class="stack-col stack-data flex items-center justify-center" style="flex: 0 0 80px;">
                        <span class="stack-label text-center w-full">{{ __('orders.items_count') }}</span>
                        <div class="flex justify-center">
                            <div
                                class="px-3 py-1 bg-gray-100 rounded-full text-sm font-bold text-gray-700 border border-gray-200">
                                {{ $order->items->count() }}
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="stack-col stack-data" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('orders.payment_method') }}</span>
                        <div class="flex items-center gap-1.5">
                            @switch($order->payment_method)
                                @case('cash')
                                    <i class="ph-fill ph-money text-green-500"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ __('orders.cash') }}</span>
                                @break

                                @case('qr')
                                    <i class="ph-fill ph-qr-code text-blue-500"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ __('orders.qr') }}</span>
                                @break

                                @case('card')
                                    <i class="ph-fill ph-credit-card text-purple-500"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ __('orders.card') }}</span>
                                @break

                                @default
                                    <span
                                        class="text-sm font-medium text-gray-500">{{ ucfirst($order->payment_method ?? '-') }}</span>
                            @endswitch
                        </div>
                    </div>

                    {{-- Total Amount --}}
                    <div class="stack-col stack-data" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('orders.grand_total') }}</span>
                        <div class="flex flex-col">
                            <span
                                class="text-base font-black {{ $order->status === 'refunded' ? 'text-orange-500 line-through' : ($order->status === 'void' ? 'text-red-500 line-through' : 'text-gray-900') }}">
                                ฿{{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="stack-col stack-data flex items-center justify-end" style="flex: 0 0 100px;">
                        @switch($order->status)
                            @case('completed')
                                <span class="badge badge-success">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('orders.completed') }}
                                </span>
                            @break

                            @case('refunded')
                                <span class="badge badge-warning">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    {{ __('orders.refunded') }}
                                </span>
                            @break

                            @case('void')
                                <span class="badge badge-danger">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('orders.voided') }}
                                </span>
                            @break
                        @endswitch
                    </div>

                    {{-- Actions Dropdown --}}
                    <div class="stack-actions" onclick="event.stopPropagation()">
                        <div class="ios-dropdown">
                            <button type="button" class="stack-action-circle">
                                <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                            </button>
                            <div class="ios-dropdown-menu">
                                <a href="{{ route('orders.show', $order) }}" class="ios-dropdown-item">
                                    <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                                    <span>{{ __('orders.view_details') }}</span>
                                </a>
                                <a href="{{ route('orders.receipt', $order) }}" target="_blank"
                                    class="ios-dropdown-item">
                                    <i class="ph ph-printer ios-dropdown-icon text-green-500"></i>
                                    <span>{{ __('orders.reprint_receipt') }}</span>
                                </a>
                                @if ($order->status === 'completed')
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <button type="button"
                                        onclick="openRefundModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->total_amount }})"
                                        class="ios-dropdown-item ios-dropdown-item-danger">
                                        <i class="ph ph-arrow-counter-clockwise ios-dropdown-icon"></i>
                                        <span>{{ __('orders.refund') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                        <i class="ph ph-receipt text-4xl mb-3"></i>
                        <p class="font-medium">{{ __('orders.no_orders') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm font-medium text-gray-400">
                    {{ __('general.showing') }}
                    <span class="text-gray-900 font-bold">{{ $orders->firstItem() ?? 0 }}</span>
                    - <span class="text-gray-900 font-bold">{{ $orders->lastItem() ?? 0 }}</span>
                    {{ __('general.of') }}
                    <span class="text-gray-900 font-bold">{{ $orders->total() }}</span>
                </div>
                <div class="flex items-center gap-1">
                    {{ $orders->links('pagination.apple') }}
                </div>
            </div>
        </div>

        {{-- Refund Modal --}}
        <div id="refund-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
            onclick="toggleModal(false, 'refund-modal')"></div>
        <div id="refund-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 28rem;">
            <div class="modal-header">
                <h2 class="modal-title">{{ __('orders.process_refund') }}</h2>
                <button type="button" onclick="toggleModal(false, 'refund-modal')" class="modal-close-btn">
                    <i class="ph-bold ph-x text-gray-500"></i>
                </button>
            </div>

            <form id="refund-form" method="POST">
                @csrf
                <div class="modal-content">
                    {{-- Warning Banner --}}
                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="ph-bold ph-warning-circle text-orange-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900" id="refund-order-number">-</p>
                                <p class="text-sm text-gray-500">{{ __('orders.refund_warning') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Refund Amount --}}
                    <div class="mb-4 text-center">
                        <label class="form-label mb-1">{{ __('orders.refund_amount') }}</label>
                        <div class="text-3xl font-black text-orange-600" id="refund-amount">฿0.00</div>
                    </div>

                    {{-- Reason --}}
                    <div class="mb-4">
                        <label class="form-label mb-2">{{ __('orders.refund_reason') }} <span
                                class="text-red-500">*</span></label>
                        <textarea name="reason" class="form-input min-h-[100px] text-sm" required
                            placeholder="{{ __('orders.enter_reason') }}"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="toggleModal(false, 'refund-modal')"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                        {{ __('cancel') }}
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl transition active-scale flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                        {{ __('orders.confirm_refund') }}
                    </button>
                </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        <script>
            function filterOrders(status) {
                const search = document.getElementById('orders-search').value;
                const dateFrom = document.getElementById('date-from').value;
                const dateTo = document.getElementById('date-to').value;
                const paymentMethod = document.getElementById('payment-method-selector').value;

                const params = new URLSearchParams(window.location.search);
                params.set('status', status);
                if (search) params.set('search', search);
                else params.delete('search');
                if (dateFrom) params.set('date_from', dateFrom);
                if (dateTo) params.set('date_to', dateTo);
                if (paymentMethod !== 'all') params.set('payment_method', paymentMethod);
                else params.delete('payment_method');

                window.location.href = `{{ route('orders.index') }}?${params.toString()}`;
            }

            // Handle search input
            document.getElementById('orders-search')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filterOrders('{{ $status }}');
                }
            });

            // Handle date change
            document.getElementById('date-from')?.addEventListener('change', function() {
                filterOrders('{{ $status }}');
            });
            document.getElementById('date-to')?.addEventListener('change', function() {
                filterOrders('{{ $status }}');
            });

            // Handle payment method change
            document.getElementById('payment-method-selector')?.addEventListener('change', function() {
                filterOrders('{{ $status }}');
            });

            // Refund Modal
            function openRefundModal(orderId, orderNumber, totalAmount) {
                document.getElementById('refund-order-number').textContent = orderNumber;
                document.getElementById('refund-amount').textContent = '฿' + parseFloat(totalAmount).toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });
                document.getElementById('refund-form').action = `/orders/${orderId}/refund`;
                toggleModal(true, 'refund-modal');
            }

            // Handle refund form submission
            document.getElementById('refund-form')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            reason: formData.get('reason')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message || '{{ __('orders.refund_success') }}', 'success');
                            toggleModal(false, 'refund-modal');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(data.message || 'Error processing refund', 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Error processing refund', 'error');
                    });
            });
        </script>
    @endpush

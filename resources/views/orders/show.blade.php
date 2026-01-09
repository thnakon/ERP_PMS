@extends('layouts.app')

@section('title', __('orders.order_details') . ' - ' . $order->order_number)

@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('orders.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            {{ __('orders.back_to_orders') }}
        </a>
        <span>{{ $order->order_number }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('orders.receipt', $order) }}" target="_blank"
            class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl shadow-sm transition active-scale flex items-center gap-2">
            <i class="ph ph-printer"></i>
            {{ __('orders.print_receipt') }}
        </a>
        @if ($order->status === 'completed')
            <button type="button"
                onclick="openRefundModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->total_amount }})"
                class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition active-scale flex items-center gap-2">
                <i class="ph-bold ph-arrow-counter-clockwise"></i>
                {{ __('orders.refund') }}
            </button>
        @endif
    </div>
@endsection

@section('content')
    {{-- Order Status Banner --}}
    @if ($order->status === 'refunded')
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="ph-bold ph-arrow-counter-clockwise text-orange-500 text-2xl"></i>
            </div>
            <div>
                <p class="font-bold text-orange-700">{{ __('orders.order_refunded') }}</p>
                <p class="text-sm text-orange-600">{{ __('orders.refunded_on') }}
                    {{ $order->refunds->first()?->created_at?->format('d M Y, H:i') }}</p>
            </div>
        </div>
    @elseif($order->status === 'void')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="ph-bold ph-x-circle text-red-500 text-2xl"></i>
            </div>
            <div>
                <p class="font-bold text-red-700">{{ __('orders.order_voided') }}</p>
                <p class="text-sm text-red-600">{{ __('orders.this_order_was_voided') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Order Items --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-shopping-cart text-blue-500"></i>
                    {{ __('orders.order_items') }} ({{ $order->items->count() }})
                </h3>

                <div class="space-y-3">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            {{-- Product Image --}}
                            <div
                                class="w-14 h-14 bg-white rounded-xl flex items-center justify-center border border-gray-100 overflow-hidden flex-shrink-0">
                                @if ($item->product?->image_path)
                                    <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                        alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ph ph-pill text-gray-300 text-2xl"></i>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                @if ($item->product?->sku)
                                    <p class="text-xs text-gray-400">SKU: {{ $item->product->sku }}</p>
                                @endif
                            </div>

                            {{-- Quantity --}}
                            <div class="text-center px-4">
                                <span class="text-xs text-gray-400 block">{{ __('orders.qty') }}</span>
                                <span
                                    class="text-lg font-bold text-gray-900 bg-white px-3 py-1 rounded-lg border border-gray-200">{{ $item->quantity }}</span>
                            </div>

                            {{-- Unit Price --}}
                            <div class="text-right w-24">
                                <span class="text-xs text-gray-400 block">{{ __('orders.unit_price') }}</span>
                                <span class="font-medium text-gray-700">฿{{ number_format($item->unit_price, 2) }}</span>
                            </div>

                            {{-- Subtotal --}}
                            <div class="text-right w-28">
                                <span class="text-xs text-gray-400 block">{{ __('orders.subtotal') }}</span>
                                <span class="font-bold text-gray-900">฿{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Refund History (if any) --}}
            @if ($order->refunds && $order->refunds->count() > 0)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <i class="ph ph-arrow-counter-clockwise text-orange-500"></i>
                        {{ __('orders.refund_history') }}
                    </h3>

                    <div class="space-y-3">
                        @foreach ($order->refunds as $refund)
                            <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-orange-700">
                                        ฿{{ number_format($refund->amount, 2) }}
                                    </span>
                                    <span class="text-sm text-orange-600">
                                        {{ $refund->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">{{ __('orders.reason') }}:</span>
                                    {{ $refund->reason }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column - Order Summary --}}
        <div class="space-y-6">
            {{-- Order Summary --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-receipt text-green-500"></i>
                    {{ __('orders.order_summary') }}
                </h3>

                {{-- Status Badge --}}
                <div class="mb-4">
                    @switch($order->status)
                        @case('completed')
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 font-bold rounded-xl">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                {{ __('orders.completed') }}
                            </span>
                        @break

                        @case('refunded')
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 bg-orange-100 text-orange-700 font-bold rounded-xl">
                                <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                                {{ __('orders.refunded') }}
                            </span>
                        @break

                        @case('void')
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 font-bold rounded-xl">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                {{ __('orders.voided') }}
                            </span>
                        @break
                    @endswitch
                </div>

                {{-- Totals --}}
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">{{ __('orders.subtotal') }}</span>
                        <span
                            class="font-medium text-gray-900">฿{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                    </div>
                    @if (($order->discount_amount ?? 0) > 0)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">{{ __('orders.discount') }}</span>
                            <span
                                class="font-medium text-green-600">-฿{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if (($order->vat_amount ?? 0) > 0)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">{{ __('orders.vat') }} (7%)</span>
                            <span class="font-medium text-gray-900">฿{{ number_format($order->vat_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-3 bg-gray-50 rounded-xl px-4 mt-2">
                        <span class="font-bold text-gray-900">{{ __('orders.grand_total') }}</span>
                        <span
                            class="text-xl font-black {{ $order->status === 'refunded' ? 'text-orange-500 line-through' : ($order->status === 'void' ? 'text-red-500 line-through' : 'text-gray-900') }}">
                            ฿{{ number_format($order->total_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-wallet text-purple-500"></i>
                    {{ __('orders.payment_info') }}
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-500">{{ __('orders.payment_method') }}</span>
                        <div class="flex items-center gap-2">
                            @switch($order->payment_method)
                                @case('cash')
                                    <i class="ph-fill ph-money text-green-500 text-lg"></i>
                                    <span class="font-medium text-gray-900">{{ __('orders.cash') }}</span>
                                @break

                                @case('qr')
                                    <i class="ph-fill ph-qr-code text-blue-500 text-lg"></i>
                                    <span class="font-medium text-gray-900">{{ __('orders.qr') }}</span>
                                @break

                                @case('card')
                                    <i class="ph-fill ph-credit-card text-purple-500 text-lg"></i>
                                    <span class="font-medium text-gray-900">{{ __('orders.card') }}</span>
                                @break

                                @default
                                    <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method ?? '-') }}</span>
                            @endswitch
                        </div>
                    </div>
                    <div class="flex justify-between py-2 border-t border-gray-100">
                        <span class="text-gray-500">{{ __('orders.amount_paid') }}</span>
                        <span
                            class="font-medium text-gray-900">฿{{ number_format($order->amount_paid ?? $order->total_amount, 2) }}</span>
                    </div>
                    @if (($order->change_amount ?? 0) > 0)
                        <div class="flex justify-between py-2 border-t border-gray-100">
                            <span class="text-gray-500">{{ __('orders.change') }}</span>
                            <span class="font-medium text-gray-900">฿{{ number_format($order->change_amount, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Customer & Order Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-info text-gray-400"></i>
                    {{ __('orders.order_info') }}
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">{{ __('orders.order_number') }}</span>
                        <span class="font-mono font-medium text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">{{ __('orders.date_time') }}</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">{{ __('orders.customer') }}</span>
                        <span
                            class="font-medium text-gray-900">{{ $order->customer?->name ?? __('orders.walk_in') }}</span>
                    </div>
                    @if ($order->customer?->phone)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">{{ __('orders.phone') }}</span>
                            <span class="font-medium text-gray-900">{{ $order->customer->phone }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">{{ __('orders.cashier') }}</span>
                        <span class="font-medium text-gray-900">{{ $order->user?->name ?? '-' }}</span>
                    </div>
                </div>
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
        function openRefundModal(orderId, orderNumber, totalAmount) {
            document.getElementById('refund-order-number').textContent = orderNumber;
            document.getElementById('refund-amount').textContent = '฿' + parseFloat(totalAmount).toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
            document.getElementById('refund-form').action = `/orders/${orderId}/refund`;
            toggleModal(true, 'refund-modal');
        }

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

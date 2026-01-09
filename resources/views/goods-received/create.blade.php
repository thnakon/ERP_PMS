@extends('layouts.app')

@section('title', __('gr.receive_from_po'))
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('purchase-orders.show', $order) }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            ← {{ $order->po_number }}
        </a>
        <span>{{ __('gr.receive_from_po') }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <form id="gr-form" method="POST" action="{{ route('goods-received.store') }}">
            @csrf
            <input type="hidden" name="purchase_order_id" value="{{ $order->id }}">
            <input type="hidden" name="supplier_id" value="{{ $order->supplier_id }}">

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">{{ __('gr.gr_number') }}</label>
                        <input type="text" class="form-input bg-gray-100" value="{{ $grNumber }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.po_reference') }}</label>
                        <input type="text" class="form-input bg-gray-100" value="{{ $order->po_number }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.supplier') }}</label>
                        <input type="text" class="form-input bg-gray-100" value="{{ $order->supplier->name }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.received_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="received_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="form-label">{{ __('gr.invoice_no') }}</label>
                        <input type="text" name="invoice_no" class="form-input" placeholder="{{ __('gr.invoice_no') }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.notes') }}</label>
                        <input type="text" name="notes" class="form-input" placeholder="{{ __('gr.notes') }}">
                    </div>
                </div>
            </div>

            {{-- Items Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('gr.items') }}</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                <th class="pb-3 w-[25%]">{{ __('gr.product') }}</th>
                                <th class="pb-3 w-[8%] text-center">{{ __('gr.ordered_qty') }}</th>
                                <th class="pb-3 w-[10%] text-center">{{ __('gr.received_qty') }}</th>
                                <th class="pb-3 w-[8%] text-center">{{ __('gr.rejected_qty') }}</th>
                                <th class="pb-3 w-[12%] text-right">{{ __('gr.unit_cost') }}</th>
                                <th class="pb-3 w-[12%]">{{ __('gr.lot_number') }}</th>
                                <th class="pb-3 w-[12%]">{{ __('gr.manufactured_date') }}</th>
                                <th class="pb-3 w-[12%]">{{ __('gr.expiry_date') }}</th>
                                <th class="pb-3 w-[12%] text-right">{{ __('gr.line_total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $i => $item)
                                @if ($item->remaining_qty > 0)
                                    <tr class="border-b border-gray-100 item-row" data-index="{{ $i }}">
                                        <td class="py-3">
                                            <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->product->sku }}</p>
                                            <input type="hidden" name="items[{{ $i }}][product_id]"
                                                value="{{ $item->product_id }}">
                                            <input type="hidden" name="items[{{ $i }}][purchase_order_item_id]"
                                                value="{{ $item->id }}">
                                            <input type="hidden" name="items[{{ $i }}][ordered_qty]"
                                                value="{{ $item->ordered_qty }}">
                                            <input type="hidden" name="items[{{ $i }}][unit_cost]"
                                                value="{{ $item->unit_cost }}">
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="font-medium">{{ number_format($item->ordered_qty, 0) }}</span>
                                            @if ($item->received_qty > 0)
                                                <br><span class="text-xs text-green-600">({{ $item->received_qty }}
                                                    received)</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <input type="number" name="items[{{ $i }}][received_qty]"
                                                class="form-input text-sm text-center qty-input"
                                                value="{{ $item->remaining_qty }}" min="0"
                                                max="{{ $item->remaining_qty }}" step="1"
                                                onchange="GRForm.calculateRow({{ $i }})">
                                        </td>
                                        <td class="py-3">
                                            <input type="number" name="items[{{ $i }}][rejected_qty]"
                                                class="form-input text-sm text-center rejected-input" value="0"
                                                min="0" step="1"
                                                onchange="GRForm.calculateRow({{ $i }})">
                                        </td>
                                        <td class="py-3 text-right font-semibold cost-display">
                                            ฿{{ number_format($item->unit_cost, 2) }}
                                        </td>
                                        <td class="py-3">
                                            <input type="text" name="items[{{ $i }}][lot_number]"
                                                class="form-input text-sm" placeholder="LOT-XXX">
                                        </td>
                                        <td class="py-3">
                                            <input type="date" name="items[{{ $i }}][manufactured_date]"
                                                class="form-input text-sm">
                                        </td>
                                        <td class="py-3">
                                            <input type="date" name="items[{{ $i }}][expiry_date]"
                                                class="form-input text-sm">
                                        </td>
                                        <td class="py-3 text-right font-bold line-total">
                                            ฿{{ number_format($item->remaining_qty * $item->unit_cost, 2) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            <tr>
                                <td colspan="7" class="py-3 text-right font-bold text-gray-900">
                                    {{ __('gr.total_amount') }}</td>
                                <td class="py-3 text-right text-xl font-black text-green-600" id="grand-total">฿0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('purchase-orders.show', $order) }}"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                    {{ __('cancel') }}
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i>
                    {{ __('gr.confirm_receive') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const GRForm = {
            calculateRow(index) {
                const row = document.querySelector(`tr[data-index="${index}"]`);
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const rejected = parseFloat(row.querySelector('.rejected-input').value) || 0;
                const cost = parseFloat(row.querySelector('[name$="[unit_cost]"]').value) || 0;

                const actualQty = Math.max(0, qty - rejected);
                const lineTotal = actualQty * cost;

                row.querySelector('.line-total').textContent =
                    `฿${lineTotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                this.calculateTotal();
            },

            calculateTotal() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach((row, index) => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const rejected = parseFloat(row.querySelector('.rejected-input').value) || 0;
                    const cost = parseFloat(row.querySelector('[name$="[unit_cost]"]').value) || 0;
                    total += Math.max(0, qty - rejected) * cost;
                });

                document.getElementById('grand-total').textContent =
                    `฿${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            }
        };

        window.GRForm = GRForm;

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', function() {
            GRForm.calculateTotal();
        });
    </script>
@endpush

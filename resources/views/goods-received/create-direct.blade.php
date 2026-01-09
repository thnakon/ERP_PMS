@extends('layouts.app')

@section('title', __('gr.receive_direct'))
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('goods-received.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            ← {{ __('gr.title') }}
        </a>
        <span>{{ __('gr.receive_direct') }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <form id="gr-form" method="POST" action="{{ route('goods-received.store') }}">
            @csrf

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">{{ __('gr.gr_number') }}</label>
                        <input type="text" class="form-input bg-gray-100" value="{{ $grNumber }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.supplier') }} <span class="text-red-500">*</span></label>
                        <select name="supplier_id" class="form-input" required>
                            <option value="">{{ __('po.select_supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.received_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="received_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('gr.invoice_no') }}</label>
                        <input type="text" name="invoice_no" class="form-input" placeholder="INV-XXX">
                    </div>
                </div>
            </div>

            {{-- Items Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('gr.items') }}</h3>
                    <button type="button" onclick="GRForm.addItem()" data-no-loading
                        class="btn-primary-sm flex items-center gap-1">
                        <i class="ph-bold ph-plus"></i>
                        {{ __('po.add_item') }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                <th class="pb-3 w-[25%]">{{ __('gr.product') }}</th>
                                <th class="pb-3 w-[10%] text-center">{{ __('gr.received_qty') }}</th>
                                <th class="pb-3 w-[8%] text-center">{{ __('gr.rejected_qty') }}</th>
                                <th class="pb-3 w-[12%] text-right">{{ __('gr.unit_cost') }}</th>
                                <th class="pb-3 w-[15%]">{{ __('gr.lot_number') }}</th>
                                <th class="pb-3 w-[12%]">{{ __('gr.manufactured_date') }}</th>
                                <th class="pb-3 w-[12%]">{{ __('gr.expiry_date') }}</th>
                                <th class="pb-3 w-[12%] text-right">{{ __('gr.line_total') }}</th>
                                <th class="pb-3 w-8"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            {{-- Items added via JS --}}
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            <tr>
                                <td colspan="6" class="py-3 text-right font-bold text-gray-900">
                                    {{ __('gr.total_amount') }}</td>
                                <td class="py-3 text-right text-xl font-black text-green-600" id="grand-total">฿0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="no-items" class="text-center py-8 text-gray-400">
                    <i class="ph ph-package text-4xl mb-2"></i>
                    <p>{{ __('po.no_items') }}</p>
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <label class="form-label">{{ __('gr.notes') }}</label>
                <textarea name="notes" class="form-input min-h-[80px]" placeholder="{{ __('gr.notes') }}..."></textarea>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('goods-received.index') }}"
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
        const products = @json($products);
        let itemIndex = 0;

        const GRForm = {
            addItem() {
                document.getElementById('no-items').style.display = 'none';
                const tbody = document.getElementById('items-body');

                const html = `
                    <tr class="item-row border-b border-gray-100" data-index="${itemIndex}">
                        <td class="py-3">
                            <select name="items[${itemIndex}][product_id]" class="form-input text-sm product-select" required onchange="GRForm.onProductChange(this, ${itemIndex})">
                                <option value="">{{ __('po.select_product') }}</option>
                                ${products.map(p => `<option value="${p.id}" data-cost="${p.cost_price}">${p.name} (${p.sku})</option>`).join('')}
                            </select>
                        </td>
                        <td class="py-3">
                            <input type="number" name="items[${itemIndex}][received_qty]" 
                                class="form-input text-sm text-center qty-input" 
                                value="1" min="0.01" step="0.01" required onchange="GRForm.calculateRow(${itemIndex})">
                        </td>
                        <td class="py-3">
                            <input type="number" name="items[${itemIndex}][rejected_qty]" 
                                class="form-input text-sm text-center rejected-input" 
                                value="0" min="0" step="0.01" onchange="GRForm.calculateRow(${itemIndex})">
                        </td>
                        <td class="py-3 text-right">
                            <input type="number" name="items[${itemIndex}][unit_cost]" 
                                class="form-input text-sm text-right cost-input" 
                                value="0" min="0" step="0.01" required onchange="GRForm.calculateRow(${itemIndex})">
                        </td>
                        <td class="py-3 text-center">
                            <input type="text" name="items[${itemIndex}][lot_number]" 
                                class="form-input text-sm" placeholder="LOT-XXX">
                        </td>
                        <td class="py-3">
                            <input type="date" name="items[${itemIndex}][manufactured_date]" 
                                class="form-input text-sm">
                        </td>
                        <td class="py-3">
                            <input type="date" name="items[${itemIndex}][expiry_date]" 
                                class="form-input text-sm">
                        </td>
                        <td class="py-3 text-right font-bold line-total">
                            ฿0.00
                        </td>
                        <td class="py-3">
                            <button type="button" onclick="GRForm.removeItem(${itemIndex})" class="text-red-500 hover:text-red-700">
                                <i class="ph ph-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                tbody.insertAdjacentHTML('beforeend', html);
                itemIndex++;
            },

            removeItem(index) {
                const row = document.querySelector(`tr[data-index="${index}"]`);
                if (row) {
                    row.remove();
                    this.calculateTotal();
                }

                if (document.querySelectorAll('.item-row').length === 0) {
                    document.getElementById('no-items').style.display = 'block';
                }
            },

            onProductChange(select, index) {
                const option = select.selectedOptions[0];
                const cost = option.dataset.cost || 0;
                const row = document.querySelector(`tr[data-index="${index}"]`);
                row.querySelector('.cost-input').value = cost;
                this.calculateRow(index);
            },

            calculateRow(index) {
                const row = document.querySelector(`tr[data-index="${index}"]`);
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const rejected = parseFloat(row.querySelector('.rejected-input').value) || 0;
                const cost = parseFloat(row.querySelector('.cost-input').value) || 0;

                const actualQty = Math.max(0, qty - rejected);
                const lineTotal = actualQty * cost;

                row.querySelector('.line-total').textContent =
                    `฿${lineTotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                this.calculateTotal();
            },

            calculateTotal() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const rejected = parseFloat(row.querySelector('.rejected-input').value) || 0;
                    const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
                    total += Math.max(0, qty - rejected) * cost;
                });

                document.getElementById('grand-total').textContent =
                    `฿${total.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            }
        };

        window.GRForm = GRForm;
    </script>
@endpush

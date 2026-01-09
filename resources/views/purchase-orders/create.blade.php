@extends('layouts.app')

@section('title', __('po.add_new'))
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('purchase-orders.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            ← {{ __('po.title') }}
        </a>
        <span>{{ __('po.add_new') }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <form id="po-form" method="POST" action="{{ route('purchase-orders.store') }}">
            @csrf

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">{{ __('po.po_number') }}</label>
                        <input type="text" class="form-input bg-gray-100" value="{{ $poNumber }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">{{ __('po.supplier') }} <span class="text-red-500">*</span></label>
                        <select name="supplier_id" id="supplier-select" class="form-input" required>
                            <option value="">{{ __('po.select_supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ request('supplier') == $supplier->id ? 'selected' : '' }}
                                    data-credit="{{ $supplier->credit_term }}" data-lead="{{ $supplier->lead_time }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('po.order_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="order_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('po.expected_date') }}</label>
                        <input type="date" name="expected_date" id="expected-date" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Items Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('po.items') }}</h3>
                    <button type="button" onclick="POForm.addItem()" data-no-loading
                        class="btn-primary-sm flex items-center gap-1">
                        <i class="ph-bold ph-plus"></i>
                        {{ __('po.add_item') }}
                    </button>
                </div>

                <div class="space-y-6">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="pb-3 w-[40%]">{{ __('po.product') }}</th>
                                <th class="pb-3 w-[12%] text-center">{{ __('po.ordered_qty') }}</th>
                                <th class="pb-3 w-[15%] text-right">{{ __('po.unit_cost') }}</th>
                                <th class="pb-3 w-[10%] text-center">{{ __('po.discount') }}</th>
                                <th class="pb-3 w-[15%] text-right">{{ __('po.line_total') }}</th>
                                <th class="pb-3 w-8"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            {{-- Items will be added here --}}
                        </tbody>
                    </table>
                </div>

                <div id="no-items" class="text-center py-8 text-gray-400">
                    <i class="ph ph-package text-4xl mb-2"></i>
                    <p>{{ __('po.no_items') }}</p>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">{{ __('po.notes') }}</label>
                        <textarea name="notes" class="form-input min-h-[100px]" placeholder="{{ __('po.notes') }}..."></textarea>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('po.subtotal') }}</span>
                            <span class="font-semibold" id="subtotal">฿0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('po.vat') }}</span>
                            <span class="font-semibold" id="vat">฿0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ __('po.discount_amount') }}</span>
                            <input type="number" name="discount_amount" id="discount-input"
                                class="w-32 text-right form-input py-1" value="0" min="0" step="0.01"
                                onchange="POForm.calculateTotals()">
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between">
                            <span class="font-bold text-gray-900">{{ __('po.grand_total') }}</span>
                            <span class="text-2xl font-black text-ios-blue" id="grand-total">฿0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('purchase-orders.index') }}"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                    {{ __('cancel') }}
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i>
                    {{ __('po.save_draft') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const products = @json($products);
        let itemIndex = 0;

        const POForm = {
            addItem() {
                document.getElementById('no-items').style.display = 'none';
                const tbody = document.getElementById('items-body');

                const html = `
                    <tr class="item-row border-b border-gray-100" data-index="${itemIndex}">
                        <td class="py-3">
                            <select name="items[${itemIndex}][product_id]" class="form-input text-sm product-select" required onchange="POForm.onProductChange(this, ${itemIndex})">
                                <option value="">{{ __('po.select_product') }}</option>
                                ${products.map(p => `<option value="${p.id}" data-cost="${p.cost_price}">${p.name} (${p.sku})</option>`).join('')}
                            </select>
                        </td>
                        <td class="py-3">
                            <input type="number" name="items[${itemIndex}][ordered_qty]" class="form-input text-sm text-center qty-input" 
                                value="1" min="0.01" step="0.01" required onchange="POForm.calculateRow(${itemIndex})">
                        </td>
                        <td class="py-3">
                            <input type="number" name="items[${itemIndex}][unit_cost]" class="form-input text-sm text-right cost-input" 
                                value="0" min="0" step="0.01" required onchange="POForm.calculateRow(${itemIndex})">
                        </td>
                        <td class="py-3">
                            <input type="number" name="items[${itemIndex}][discount_percent]" class="form-input text-sm text-center discount-input" 
                                value="0" min="0" max="100" step="0.01" onchange="POForm.calculateRow(${itemIndex})">
                        </td>
                        <td class="py-3 text-right">
                            <span class="font-semibold line-total">฿0.00</span>
                        </td>
                        <td class="py-3">
                            <button type="button" onclick="POForm.removeItem(${itemIndex})" class="text-red-500 hover:text-red-700">
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
                    this.calculateTotals();
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
                const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
                const discount = parseFloat(row.querySelector('.discount-input').value) || 0;

                const subtotal = qty * cost;
                const discountAmt = subtotal * (discount / 100);
                const lineTotal = subtotal - discountAmt;

                row.querySelector('.line-total').textContent =
                    `฿${lineTotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                this.calculateTotals();
            },

            calculateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
                    const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
                    const lineSubtotal = qty * cost;
                    subtotal += lineSubtotal - (lineSubtotal * discount / 100);
                });

                const vat = subtotal * 0.07;
                const billDiscount = parseFloat(document.getElementById('discount-input').value) || 0;
                const grandTotal = subtotal + vat - billDiscount;

                document.getElementById('subtotal').textContent =
                    `฿${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                document.getElementById('vat').textContent =
                    `฿${vat.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                document.getElementById('grand-total').textContent =
                    `฿${grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            }
        };

        window.POForm = POForm;

        // Auto-set expected date based on supplier lead time
        document.getElementById('supplier-select')?.addEventListener('change', function() {
            const option = this.selectedOptions[0];
            const leadTime = parseInt(option.dataset.lead) || 0;
            if (leadTime > 0) {
                const orderDate = new Date(document.querySelector('[name="order_date"]').value);
                orderDate.setDate(orderDate.getDate() + leadTime);
                document.getElementById('expected-date').value = orderDate.toISOString().split('T')[0];
            }
        });

        // Validation
        document.getElementById('po-form').addEventListener('submit', function(e) {
            if (document.querySelectorAll('.item-row').length === 0) {
                e.preventDefault();
                alert('{{ __('po.no_items') }}');
            }
        });
    </script>
@endpush

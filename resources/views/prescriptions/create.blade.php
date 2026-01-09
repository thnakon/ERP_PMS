@extends('layouts.app')

@section('title', __('prescriptions.create_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('prescriptions.title') }}
        </p>
        <span>{{ __('prescriptions.create_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('prescriptions.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('back') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('prescriptions.store') }}" method="POST" id="prescription-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Customer & Doctor Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Customer Selection --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-user text-ios-blue"></i>
                        {{ __('prescriptions.customer_info') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('prescriptions.customer') }} <span
                                    class="text-red-500">*</span></label>
                            <select name="customer_id" id="customer_id" required
                                class="form-input bg-gray-50 focus:bg-white">
                                <option value="">{{ __('prescriptions.select_customer') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id', $selectedCustomer?->id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="customer-info" class="hidden p-3 rounded-xl bg-blue-50 text-sm">
                            <p class="font-medium text-gray-900" id="customer-name"></p>
                            <p class="text-gray-500" id="customer-phone"></p>
                            <p class="text-gray-500" id="customer-allergies"></p>
                        </div>
                    </div>
                </div>

                {{-- Doctor Information --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-stethoscope text-green-500"></i>
                        {{ __('prescriptions.doctor_info') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('prescriptions.doctor_name') }} <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="doctor_name" value="{{ old('doctor_name') }}" required
                                class="form-input" placeholder="{{ __('prescriptions.doctor_name') }}">
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.doctor_license') }}</label>
                            <input type="text" name="doctor_license_no" value="{{ old('doctor_license_no') }}"
                                class="form-input" placeholder="{{ __('prescriptions.doctor_license') }}">
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.hospital_clinic') }}</label>
                            <input type="text" name="hospital_clinic" value="{{ old('hospital_clinic') }}"
                                class="form-input" placeholder="{{ __('prescriptions.hospital_clinic') }}">
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.doctor_phone') }}</label>
                            <input type="text" name="doctor_phone" value="{{ old('doctor_phone') }}" class="form-input"
                                placeholder="0XX-XXX-XXXX">
                        </div>
                    </div>
                </div>

                {{-- Prescription Details --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-calendar text-purple-500"></i>
                        {{ __('prescriptions.prescription_details') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('prescriptions.prescription_date') }} <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="prescription_date"
                                value="{{ old('prescription_date', date('Y-m-d')) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.expiry_date') }}</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="form-input">
                            <p class="text-xs text-gray-400 mt-1">{{ __('prescriptions.expiry_date_hint') }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.refill_allowed') }}</label>
                            <select name="refill_allowed" class="form-input">
                                <option value="0">0</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('refill_allowed') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.diagnosis') }}</label>
                            <textarea name="diagnosis" class="form-input min-h-[80px]" placeholder="{{ __('prescriptions.diagnosis') }}">{{ old('diagnosis') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">{{ __('prescriptions.notes') }}</label>
                            <textarea name="notes" class="form-input min-h-[60px]" placeholder="{{ __('prescriptions.notes') }}">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Medications --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Drug Interaction Warning --}}
                <div id="interaction-warning" class="hidden">
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border-2 border-red-300 bg-red-50">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-warning text-red-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-red-700 mb-2">{{ __('prescriptions.interaction_warning') }}</h4>
                                <div id="interaction-list" class="space-y-2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Medications List --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="ph-fill ph-pill text-ios-blue"></i>
                            {{ __('prescriptions.medications') }}
                        </h3>
                        <button type="button" onclick="addMedicationRow()" data-no-loading
                            class="px-4 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-semibold rounded-xl transition flex items-center gap-2">
                            <i class="ph-bold ph-plus"></i>
                            {{ __('prescriptions.add_medication') }}
                        </button>
                    </div>

                    <div id="medications-container" class="space-y-4">
                        {{-- Medication rows will be added here --}}
                    </div>

                    <div id="no-medications" class="text-center py-8 text-gray-400">
                        <i class="ph ph-pill text-4xl mb-2"></i>
                        <p>{{ __('prescriptions.no_medications_yet') }}</p>
                        <button type="button" onclick="addMedicationRow()" data-no-loading
                            class="mt-3 text-ios-blue font-semibold hover:underline">
                            + {{ __('prescriptions.add_first_medication') }}
                        </button>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('prescriptions.total_items') }}</p>
                            <p class="text-2xl font-bold text-gray-900" id="total-items">0</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">{{ __('prescriptions.estimated_total') }}</p>
                            <p class="text-2xl font-bold text-ios-blue" id="total-amount">฿0</p>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('prescriptions.index') }}"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition">
                        {{ __('cancel') }}
                    </a>
                    <button type="submit" data-no-loading
                        class="px-8 py-3 bg-ios-blue hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
                        <i class="ph-bold ph-check-circle"></i>
                        {{ __('prescriptions.save_prescription') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Medication Row Template --}}
    <template id="medication-row-template">
        <div class="medication-row p-4 rounded-xl bg-gray-50 border border-gray-200" data-index="__INDEX__">
            <div class="flex items-start gap-4">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Product Select --}}
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.medicine') }}
                            <span class="text-red-500">*</span></label>
                        <select name="items[__INDEX__][product_id]" required
                            class="product-select form-input bg-white text-sm" onchange="onProductChange(this)">
                            <option value="">-- {{ __('prescriptions.search_medication') }} --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}"
                                    data-stock="{{ $product->stock_qty }}">
                                    {{ $product->name }} ({{ $product->sku }}) -
                                    ฿{{ number_format($product->unit_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quantity & Dosage --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.quantity') }}
                            <span class="text-red-500">*</span></label>
                        <input type="number" name="items[__INDEX__][quantity]" required min="0.01" step="0.01"
                            class="quantity-input form-input bg-white text-sm"
                            placeholder="{{ __('prescriptions.quantity') }}" onchange="updateTotals()">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.dosage') }}
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="items[__INDEX__][dosage]" required
                            class="form-input bg-white text-sm" placeholder="{{ __('prescriptions.dosage') }}">
                    </div>

                    {{-- Frequency & Duration --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.frequency') }}
                            <span class="text-red-500">*</span></label>
                        <select name="items[__INDEX__][frequency]" required class="form-input bg-white text-sm">
                            <option value="">-- {{ __('select') }} --</option>
                            @foreach ($frequencies as $key => $label)
                                <option value="{{ $label }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.duration') }}</label>
                        <input type="text" name="items[__INDEX__][duration]" class="form-input bg-white text-sm"
                            placeholder="{{ __('prescriptions.duration') }}">
                    </div>

                    {{-- Route & Instructions --}}
                    <div>
                        <label
                            class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.route') }}</label>
                        <select name="items[__INDEX__][route]" class="form-input bg-white text-sm">
                            <option value="">-- {{ __('select') }} --</option>
                            @foreach ($routes as $key => $label)
                                <option value="{{ $label }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="text-xs font-semibold text-gray-500 mb-1 block">{{ __('prescriptions.instructions') }}</label>
                        <input type="text" name="items[__INDEX__][instructions]" class="form-input bg-white text-sm"
                            placeholder="{{ __('prescriptions.instructions') }}">
                    </div>
                </div>

                {{-- Remove Button --}}
                <button type="button" onclick="removeMedicationRow(this)" data-no-loading
                    class="w-10 h-10 rounded-xl bg-red-100 hover:bg-red-200 text-red-600 flex items-center justify-center transition flex-shrink-0">
                    <i class="ph-bold ph-trash"></i>
                </button>
            </div>

            {{-- Stock Warning --}}
            <div class="stock-warning hidden mt-2 text-xs text-amber-600 font-medium">
                <i class="ph ph-warning"></i> <span class="stock-warning-text"></span>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        let medicationIndex = 0;

        function addMedicationRow() {
            const container = document.getElementById('medications-container');
            const template = document.getElementById('medication-row-template');
            const html = template.innerHTML.replace(/__INDEX__/g, medicationIndex);

            container.insertAdjacentHTML('beforeend', html);
            medicationIndex++;

            document.getElementById('no-medications').classList.add('hidden');
            updateTotals();
            checkDrugInteractions();
        }

        function removeMedicationRow(button) {
            button.closest('.medication-row').remove();

            const container = document.getElementById('medications-container');
            if (container.children.length === 0) {
                document.getElementById('no-medications').classList.remove('hidden');
            }

            updateTotals();
            checkDrugInteractions();
        }

        function onProductChange(select) {
            const row = select.closest('.medication-row');
            const option = select.options[select.selectedIndex];
            const stock = parseFloat(option.dataset.stock) || 0;
            const stockWarning = row.querySelector('.stock-warning');
            const stockWarningText = row.querySelector('.stock-warning-text');

            if (stock <= 0) {
                stockWarning.classList.remove('hidden');
                stockWarningText.textContent = '{{ __('out_of_stock') }}';
            } else if (stock < 10) {
                stockWarning.classList.remove('hidden');
                stockWarningText.textContent = `{{ __('low_stock') }}: ${stock} {{ __('units') }}`;
            } else {
                stockWarning.classList.add('hidden');
            }

            updateTotals();
            checkDrugInteractions();
        }

        function updateTotals() {
            const rows = document.querySelectorAll('.medication-row');
            let totalItems = 0;
            let totalAmount = 0;

            rows.forEach(row => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const option = select.options[select.selectedIndex];

                if (option && option.value) {
                    const price = parseFloat(option.dataset.price) || 0;
                    const quantity = parseFloat(quantityInput.value) || 0;
                    totalItems++;
                    totalAmount += price * quantity;
                }
            });

            document.getElementById('total-items').textContent = totalItems;
            document.getElementById('total-amount').textContent = '฿' + totalAmount.toLocaleString('th-TH', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        async function checkDrugInteractions() {
            const rows = document.querySelectorAll('.medication-row');
            const productIds = [];

            rows.forEach(row => {
                const select = row.querySelector('.product-select');
                if (select.value) {
                    productIds.push(parseInt(select.value));
                }
            });

            if (productIds.length < 2) {
                document.getElementById('interaction-warning').classList.add('hidden');
                return;
            }

            try {
                const response = await fetch('{{ route('prescriptions.check-interactions') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_ids: productIds
                    })
                });

                const data = await response.json();

                if (data.interactions && data.interactions.length > 0) {
                    const warningDiv = document.getElementById('interaction-warning');
                    const listDiv = document.getElementById('interaction-list');

                    listDiv.innerHTML = data.interactions.map(i => `
                        <div class="p-2 rounded-lg" style="background: ${i.severity_color}20; border-left: 3px solid ${i.severity_color}">
                            <p class="font-semibold text-gray-900">${i.drug_a} + ${i.drug_b}</p>
                            <p class="text-sm text-gray-700">${i.description}</p>
                            ${i.management ? `<p class="text-xs text-gray-500 mt-1">💡 ${i.management}</p>` : ''}
                        </div>
                    `).join('');

                    warningDiv.classList.remove('hidden');
                } else {
                    document.getElementById('interaction-warning').classList.add('hidden');
                }
            } catch (error) {
                console.error('Error checking interactions:', error);
            }
        }

        // Initialize with one medication row
        document.addEventListener('DOMContentLoaded', function() {
            addMedicationRow();
        });
    </script>
@endpush

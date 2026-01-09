@extends('layouts.app')

@section('title', __('controlled_drugs.add_new'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('controlled_drugs.title') }}
        </p>
        <span>{{ __('controlled_drugs.add_new') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('controlled-drugs.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('back') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('controlled-drugs.store') }}" method="POST" id="controlled-drug-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Drug & Purpose --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Drug Selection --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-pill text-red-500"></i>
                        {{ __('controlled_drugs.drug_info') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.drug') }} <span
                                    class="text-red-500">*</span></label>
                            <select name="product_id" id="product_id" required class="form-input bg-gray-50 focus:bg-white">
                                <option value="">-- {{ __('controlled_drugs.select_drug') }} --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-schedule="{{ $product->drug_schedule }}"
                                        {{ old('product_id', $selectedProduct?->id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->drug_schedule_label }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="drug-warning" class="hidden p-3 rounded-xl bg-red-50 border border-red-200">
                            <p class="text-sm font-semibold text-red-700">
                                <i class="ph ph-warning"></i>
                                <span id="drug-warning-text"></span>
                            </p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.quantity') }} <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="quantity" value="{{ old('quantity') }}" required min="0.01"
                                step="0.01" class="form-input" placeholder="0">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.transaction_type') }} <span
                                    class="text-red-500">*</span></label>
                            <select name="transaction_type" required class="form-input">
                                @foreach ($transactionTypes as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('transaction_type', 'sale') == $value ? 'selected' : '' }}>
                                        {{ __('controlled_drugs.trans_' . $value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Purpose --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-clipboard-text text-purple-500"></i>
                        {{ __('controlled_drugs.purpose_section') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.purpose') }}</label>
                            <textarea name="purpose" class="form-input min-h-[80px]" placeholder="{{ __('controlled_drugs.purpose') }}">{{ old('purpose') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.indication') }}</label>
                            <textarea name="indication" class="form-input min-h-[60px]" placeholder="{{ __('controlled_drugs.indication') }}">{{ old('indication') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.notes') }}</label>
                            <textarea name="notes" class="form-input min-h-[60px]" placeholder="{{ __('controlled_drugs.notes') }}">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Middle Column: Recipient Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Recipient Selection --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-user text-ios-blue"></i>
                        {{ __('controlled_drugs.recipient_info') }}
                        <span class="text-xs text-red-500">{{ __('controlled_drugs.recipient_legal_note') }}</span>
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.select_from_customers') }}</label>
                            <select name="customer_id" id="customer_id" class="form-input bg-gray-50 focus:bg-white"
                                onchange="fillCustomerInfo(this)">
                                <option value="">{{ __('controlled_drugs.or_enter_new') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                        data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <div>
                                <label class="form-label">{{ __('controlled_drugs.full_name') }} <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" id="customer_name"
                                    value="{{ old('customer_name') }}" required class="form-input"
                                    placeholder="{{ __('controlled_drugs.full_name') }}">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.id_card_number') }}</label>
                            <input type="text" name="customer_id_card" value="{{ old('customer_id_card') }}"
                                class="form-input" placeholder="X-XXXX-XXXXX-XX-X" maxlength="17">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.phone') }}</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone') }}" class="form-input" placeholder="0XX-XXX-XXXX">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.age') }}</label>
                            <input type="text" name="customer_age" value="{{ old('customer_age') }}" class="form-input"
                                placeholder="e.g. 35">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.address') }}</label>
                            <textarea name="customer_address" id="customer_address" class="form-input min-h-[60px]"
                                placeholder="{{ __('controlled_drugs.address') }}">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Prescription Info & Submit --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Prescription Info --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-prescription text-green-500"></i>
                        {{ __('controlled_drugs.prescription_info') }}
                        <span class="text-xs text-gray-400">{{ __('controlled_drugs.if_applicable') }}</span>
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.prescription_number') }}</label>
                            <input type="text" name="prescription_number" value="{{ old('prescription_number') }}"
                                class="form-input" placeholder="RX-XXXXXXXX">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.doctor_name') }}</label>
                            <input type="text" name="doctor_name" value="{{ old('doctor_name') }}"
                                class="form-input" placeholder="{{ __('controlled_drugs.doctor_name') }}">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.license_number') }}</label>
                            <input type="text" name="doctor_license_no" value="{{ old('doctor_license_no') }}"
                                class="form-input" placeholder="XXXXX">
                        </div>
                        <div>
                            <label class="form-label">{{ __('controlled_drugs.hospital_clinic') }}</label>
                            <input type="text" name="hospital_clinic" value="{{ old('hospital_clinic') }}"
                                class="form-input" placeholder="{{ __('controlled_drugs.hospital_clinic') }}">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm bg-gray-50">
                    <div class="flex items-start gap-3 mb-4">
                        <input type="checkbox" id="confirm_legal" required
                            class="w-5 h-5 rounded border-gray-300 text-ios-blue focus:ring-ios-blue mt-0.5">
                        <label for="confirm_legal" class="text-sm text-gray-700">
                            {{ __('controlled_drugs.legal_confirm') }}
                            <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <button type="submit" data-no-loading
                        class="w-full px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition flex items-center gap-2 justify-center">
                        <i class="ph-bold ph-shield-warning"></i>
                        {{ __('controlled_drugs.submit_log') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function fillCustomerInfo(select) {
            const option = select.options[select.selectedIndex];
            if (option.value) {
                document.getElementById('customer_name').value = option.dataset.name || '';
                document.getElementById('customer_phone').value = option.dataset.phone || '';
                document.getElementById('customer_address').value = option.dataset.address || '';
            }
        }

        document.getElementById('product_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const schedule = option.dataset.schedule;
            const warning = document.getElementById('drug-warning');
            const warningText = document.getElementById('drug-warning-text');

            if (['dangerous', 'specially_controlled', 'narcotic', 'psychotropic'].includes(schedule)) {
                warning.classList.remove('hidden');
                const warningKey = 'drug_warning_' + (schedule === 'specially_controlled' ? 'specially' : schedule);
                warningText.textContent = {
                    'dangerous': '{{ __('controlled_drugs.drug_warning_dangerous') }}',
                    'specially_controlled': '{{ __('controlled_drugs.drug_warning_specially') }}',
                    'narcotic': '{{ __('controlled_drugs.drug_warning_narcotic') }}',
                    'psychotropic': '{{ __('controlled_drugs.drug_warning_psychotropic') }}'
                } [schedule] || '';
            } else {
                warning.classList.add('hidden');
            }
        });
    </script>
@endpush

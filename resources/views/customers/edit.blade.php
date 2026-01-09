@extends('layouts.app')

@section('title', __('customers.edit_customer'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('customers.title') }}
        </p>
        <span>{{ __('customers.edit_customer') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('customers.show', $customer) }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('cancel') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Personal & Contact Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Personal Information --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-user-circle text-ios-blue"></i>
                        {{ __('customers.personal_info') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Full Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                                class="input-ios @error('name') border-red-500 @enderror"
                                placeholder="{{ __('customers.enter_name') }}">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nickname --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.nickname') }}
                            </label>
                            <input type="text" name="nickname" value="{{ old('nickname', $customer->nickname) }}"
                                class="input-ios" placeholder="{{ __('customers.enter_nickname') }}">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.phone') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" required
                                class="input-ios @error('phone') border-red-500 @enderror"
                                placeholder="{{ __('customers.enter_phone') }}">
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Birth Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.birth_date') }}
                            </label>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $customer->birth_date?->format('Y-m-d')) }}" class="input-ios">
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.gender') }}
                            </label>
                            <select name="gender" class="input-ios">
                                <option value="">{{ __('customers.select_gender') }}</option>
                                <option value="male" {{ old('gender', $customer->gender) === 'male' ? 'selected' : '' }}>
                                    {{ __('customers.male') }}</option>
                                <option value="female"
                                    {{ old('gender', $customer->gender) === 'female' ? 'selected' : '' }}>
                                    {{ __('customers.female') }}</option>
                                <option value="other"
                                    {{ old('gender', $customer->gender) === 'other' ? 'selected' : '' }}>
                                    {{ __('customers.other') }}</option>
                            </select>
                        </div>

                        {{-- National ID --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.national_id') }}
                            </label>
                            <input type="text" name="national_id"
                                value="{{ old('national_id', $customer->national_id) }}" class="input-ios"
                                placeholder="{{ __('customers.enter_national_id') }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.email') }}
                            </label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                class="input-ios" placeholder="{{ __('customers.enter_email') }}">
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-map-pin text-green-500"></i>
                        {{ __('customers.contact_info') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Line ID --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.line_id') }}
                            </label>
                            <div class="relative">
                                <i
                                    class="ph-fill ph-chat-circle-text absolute left-4 top-1/2 -translate-y-1/2 text-green-500"></i>
                                <input type="text" name="line_id" value="{{ old('line_id', $customer->line_id) }}"
                                    class="input-ios has-icon pl-14" placeholder="{{ __('customers.enter_line_id') }}">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('customers.address') }}
                            </label>
                            <textarea name="address" rows="3" class="input-ios resize-none"
                                placeholder="{{ __('customers.enter_address') }}">{{ old('address', $customer->address) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Loyalty Stats (Read-only) --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-line-up text-purple-500"></i>
                        {{ __('customers.loyalty_info') }}
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($customer->points_balance ?? 0) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ __('customers.points_balance') }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900">{{ ucfirst($customer->member_tier ?? 'regular') }}
                            </p>
                            <p class="text-xs text-gray-500">{{ __('customers.member_tier') }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($customer->visit_count ?? 0) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ __('customers.visit_count') }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900">฿{{ number_format($customer->total_spent ?? 0) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ __('customers.total_spent') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Medical Records & Notes --}}
            <div class="space-y-6">
                {{-- Drug Safety Alert --}}
                <div class="card-ios p-6 border-2 border-red-200 bg-red-50/50">
                    <h3 class="text-lg font-bold text-red-700 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-warning-octagon"></i>
                        {{ __('customers.drug_safety') }}
                    </h3>

                    {{-- Drug Allergies --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('customers.drug_allergies') }}
                        </label>
                        <div id="allergy-list" class="space-y-2">
                            @if (!empty($customer->drug_allergies))
                                @foreach ($customer->drug_allergies as $index => $allergy)
                                    <div class="allergy-row flex gap-2">
                                        <input type="text" name="drug_allergies[{{ $index }}][drug_name]"
                                            value="{{ is_array($allergy) ? $allergy['drug_name'] ?? '' : $allergy }}"
                                            class="input-ios flex-1" placeholder="{{ __('customers.enter_drug_name') }}">
                                        <input type="text" name="drug_allergies[{{ $index }}][reaction]"
                                            value="{{ is_array($allergy) ? $allergy['reaction'] ?? '' : '' }}"
                                            class="input-ios flex-1" placeholder="{{ __('customers.enter_reaction') }}">
                                        @if ($index > 0)
                                            <button type="button" onclick="this.parentElement.remove()"
                                                class="text-red-500 hover:text-red-700">
                                                <i class="ph-bold ph-x"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="allergy-row flex gap-2">
                                    <input type="text" name="drug_allergies[0][drug_name]" class="input-ios flex-1"
                                        placeholder="{{ __('customers.enter_drug_name') }}">
                                    <input type="text" name="drug_allergies[0][reaction]" class="input-ios flex-1"
                                        placeholder="{{ __('customers.enter_reaction') }}">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addAllergyRow()"
                            class="mt-2 text-sm text-ios-blue font-medium flex items-center gap-1 hover:underline">
                            <i class="ph-bold ph-plus"></i>
                            {{ __('customers.add_allergy') }}
                        </button>
                    </div>

                    {{-- Pregnancy Status --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('customers.pregnancy_status') }}
                        </label>
                        <select name="pregnancy_status" class="input-ios">
                            <option value="none"
                                {{ old('pregnancy_status', $customer->pregnancy_status) === 'none' ? 'selected' : '' }}>
                                {{ __('customers.not_applicable') }}</option>
                            <option value="pregnant"
                                {{ old('pregnancy_status', $customer->pregnancy_status) === 'pregnant' ? 'selected' : '' }}>
                                {{ __('customers.pregnant') }}</option>
                            <option value="breastfeeding"
                                {{ old('pregnancy_status', $customer->pregnancy_status) === 'breastfeeding' ? 'selected' : '' }}>
                                {{ __('customers.breastfeeding') }}</option>
                        </select>
                    </div>

                    {{-- Medical Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('customers.medical_notes') }}
                        </label>
                        <textarea name="medical_notes" rows="3" class="input-ios resize-none"
                            placeholder="{{ __('customers.enter_medical_notes') }}">{{ old('medical_notes', $customer->medical_notes) }}</textarea>
                    </div>
                </div>

                {{-- Chronic Diseases --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-heartbeat text-orange-500"></i>
                        {{ __('customers.chronic_diseases') }}
                    </h3>

                    <div id="disease-list" class="space-y-2">
                        @if (!empty($customer->chronic_diseases))
                            @foreach ($customer->chronic_diseases as $disease)
                                <div class="flex gap-2">
                                    <input type="text" name="chronic_diseases[]" value="{{ $disease }}"
                                        class="input-ios flex-1" placeholder="{{ __('customers.enter_disease') }}">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="ph-bold ph-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <input type="text" name="chronic_diseases[]" class="input-ios"
                                placeholder="{{ __('customers.enter_disease') }}">
                        @endif
                    </div>
                    <button type="button" onclick="addDiseaseRow()"
                        class="mt-2 text-sm text-ios-blue font-medium flex items-center gap-1 hover:underline">
                        <i class="ph-bold ph-plus"></i>
                        {{ __('customers.add_disease') }}
                    </button>
                </div>

                {{-- Notes --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-note text-gray-500"></i>
                        {{ __('customers.notes') }}
                    </h3>
                    <textarea name="notes" rows="4" class="input-ios resize-none"
                        placeholder="{{ __('customers.enter_notes') }}">{{ old('notes', $customer->notes) }}</textarea>
                </div>

                {{-- Active Status --}}
                <div class="card-ios p-6">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="font-medium text-gray-700">{{ __('customers.active_customers') }}</span>
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $customer->is_active) ? 'checked' : '' }} class="toggle-ios">
                    </label>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('customers.show', $customer) }}"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                {{ __('cancel') }}
            </a>
            <button type="submit"
                class="px-6 py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
                <i class="ph-bold ph-check"></i>
                {{ __('save') }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let allergyRowCount = {{ !empty($customer->drug_allergies) ? count($customer->drug_allergies) : 1 }};
        let diseaseRowCount = {{ !empty($customer->chronic_diseases) ? count($customer->chronic_diseases) : 1 }};

        function addAllergyRow() {
            const container = document.getElementById('allergy-list');
            const row = document.createElement('div');
            row.className = 'allergy-row flex gap-2';
            row.innerHTML = `
            <input type="text" name="drug_allergies[${allergyRowCount}][drug_name]"
                class="input-ios flex-1"
                placeholder="{{ __('customers.enter_drug_name') }}">
            <input type="text" name="drug_allergies[${allergyRowCount}][reaction]"
                class="input-ios flex-1"
                placeholder="{{ __('customers.enter_reaction') }}">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                <i class="ph-bold ph-x"></i>
            </button>
        `;
            container.appendChild(row);
            allergyRowCount++;
        }

        function addDiseaseRow() {
            const container = document.getElementById('disease-list');
            const input = document.createElement('div');
            input.className = 'flex gap-2';
            input.innerHTML = `
            <input type="text" name="chronic_diseases[]"
                class="input-ios flex-1"
                placeholder="{{ __('customers.enter_disease') }}">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                <i class="ph-bold ph-x"></i>
            </button>
        `;
            container.appendChild(input);
            diseaseRowCount++;
        }
    </script>
@endpush

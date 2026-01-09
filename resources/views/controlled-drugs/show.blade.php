@extends('layouts.app')

@section('title', $controlledDrug->log_number)
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('controlled_drugs.title') }}
        </p>
        <span>{{ $controlledDrug->log_number }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('controlled-drugs.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('back') }}
    </a>
    @if ($controlledDrug->status === 'pending' && (auth()->user()->isAdmin() || auth()->user()->role === 'pharmacist'))
        <form action="{{ route('controlled-drugs.approve', $controlledDrug) }}" method="POST" class="contents">
            @csrf
            <button type="submit"
                class="px-5 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl shadow-lg transition flex items-center gap-2">
                <i class="ph-bold ph-check-circle"></i>
                {{ __('controlled_drugs.approve') }}
            </button>
        </form>
    @endif
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Status Card --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <div class="text-center mb-4">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-3
                        {{ $controlledDrug->status === 'approved' ? 'bg-green-100' : ($controlledDrug->status === 'pending' ? 'bg-amber-100' : 'bg-red-100') }}">
                        <i
                            class="ph-fill text-3xl
                            {{ $controlledDrug->status === 'approved' ? 'ph-check-circle text-green-600' : ($controlledDrug->status === 'pending' ? 'ph-hourglass text-amber-600' : 'ph-x-circle text-red-600') }}"></i>
                    </div>
                    @if ($controlledDrug->status === 'approved')
                        <span class="badge badge-success">
                            <span class="badge-dot badge-dot-success"></span>
                            {{ __('controlled_drugs.status_approved') }}
                        </span>
                    @elseif($controlledDrug->status === 'pending')
                        <span class="badge badge-warning">
                            <span class="badge-dot badge-dot-warning"></span>
                            {{ __('controlled_drugs.status_pending') }}
                        </span>
                    @elseif($controlledDrug->status === 'rejected')
                        <span class="badge badge-danger">
                            <span class="badge-dot badge-dot-danger"></span>
                            {{ __('controlled_drugs.status_rejected') }}
                        </span>
                    @endif

                    @if ($controlledDrug->approved_at)
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $controlledDrug->approved_at->format('d/m/Y H:i') }}
                        </p>
                        @if ($controlledDrug->approvedBy)
                            <p class="text-xs text-gray-500">{{ __('by') }} {{ $controlledDrug->approvedBy->name }}
                            </p>
                        @endif
                    @endif
                </div>

                @if ($controlledDrug->status === 'rejected' && $controlledDrug->rejection_reason)
                    <div class="p-3 rounded-xl bg-red-50 border border-red-200">
                        <p class="text-xs font-semibold text-red-600 mb-1">{{ __('controlled_drugs.rejection_reason') }}:
                        </p>
                        <p class="text-sm text-red-700">{{ $controlledDrug->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            {{-- Drug Info --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-pill text-red-500"></i>
                    {{ __('controlled_drugs.drug_details') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                            <i class="ph-fill ph-pill text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $controlledDrug->product->name }}</p>
                            <p class="text-sm text-gray-500">SKU: {{ $controlledDrug->product->sku }}</p>
                        </div>
                    </div>
                    <div class="mt-2">{!! $controlledDrug->product->drug_schedule_badge !!}</div>
                    <div class="border-t border-gray-100 pt-3 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.quantity') }}</span>
                            <span class="font-bold text-gray-900">{{ number_format($controlledDrug->quantity, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.transaction_type') }}</span>
                            <span
                                class="font-medium text-gray-900">{{ __('controlled_drugs.trans_' . $controlledDrug->transaction_type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.date') }}</span>
                            <span
                                class="font-medium text-gray-900">{{ $controlledDrug->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.created_by') }}</span>
                            <span class="font-medium text-gray-900">{{ $controlledDrug->createdBy->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Middle Column --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Recipient Info --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-user text-ios-blue"></i>
                    {{ __('controlled_drugs.recipient_details') }}
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('controlled_drugs.full_name') }}</span>
                        <span class="font-bold text-gray-900">{{ $controlledDrug->customer_name }}</span>
                    </div>
                    @if ($controlledDrug->customer_id_card)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.id_card_number') }}</span>
                            <span
                                class="font-medium text-gray-900 font-mono">{{ $controlledDrug->customer_id_card }}</span>
                        </div>
                    @endif
                    @if ($controlledDrug->customer_phone)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.phone') }}</span>
                            <span class="font-medium text-ios-blue">{{ $controlledDrug->customer_phone }}</span>
                        </div>
                    @endif
                    @if ($controlledDrug->customer_age)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('controlled_drugs.age') }}</span>
                            <span class="font-medium text-gray-900">{{ $controlledDrug->customer_age }}</span>
                        </div>
                    @endif
                    @if ($controlledDrug->customer_address)
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-gray-500 block mb-1">{{ __('controlled_drugs.address') }}</span>
                            <p class="text-gray-900">{{ $controlledDrug->customer_address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Purpose --}}
            @if ($controlledDrug->purpose || $controlledDrug->indication)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-clipboard-text text-purple-500"></i>
                        {{ __('controlled_drugs.purpose_section') }}
                    </h3>
                    <div class="space-y-3 text-sm">
                        @if ($controlledDrug->purpose)
                            <div>
                                <span class="text-gray-500 block mb-1">{{ __('controlled_drugs.purpose') }}</span>
                                <p class="text-gray-900">{{ $controlledDrug->purpose }}</p>
                            </div>
                        @endif
                        @if ($controlledDrug->indication)
                            <div class="pt-2 border-t border-gray-100">
                                <span class="text-gray-500 block mb-1">{{ __('controlled_drugs.indication') }}</span>
                                <p class="text-gray-900">{{ $controlledDrug->indication }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Prescription Info --}}
            @if ($controlledDrug->prescription_number || $controlledDrug->doctor_name)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-prescription text-green-500"></i>
                        {{ __('controlled_drugs.prescription_details') }}
                    </h3>
                    <div class="space-y-2 text-sm">
                        @if ($controlledDrug->prescription_number)
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('controlled_drugs.prescription_number') }}</span>
                                <span class="font-bold text-green-600">{{ $controlledDrug->prescription_number }}</span>
                            </div>
                        @endif
                        @if ($controlledDrug->doctor_name)
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('controlled_drugs.doctor_name') }}</span>
                                <span class="font-medium text-gray-900">{{ $controlledDrug->doctor_name }}</span>
                            </div>
                        @endif
                        @if ($controlledDrug->doctor_license_no)
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('controlled_drugs.license_number') }}</span>
                                <span class="font-medium text-gray-900">{{ $controlledDrug->doctor_license_no }}</span>
                            </div>
                        @endif
                        @if ($controlledDrug->hospital_clinic)
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('controlled_drugs.hospital_clinic') }}</span>
                                <span class="font-medium text-gray-900">{{ $controlledDrug->hospital_clinic }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Notes --}}
            @if ($controlledDrug->notes)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-note text-gray-500"></i>
                        {{ __('controlled_drugs.notes') }}
                    </h3>
                    <p class="text-sm text-gray-700">{{ $controlledDrug->notes }}</p>
                </div>
            @endif

            {{-- Rejection Form --}}
            @if ($controlledDrug->status === 'pending' && (auth()->user()->isAdmin() || auth()->user()->role === 'pharmacist'))
                <div
                    class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm bg-red-50 border-2 border-red-200">
                    <h3 class="font-bold text-red-700 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-x-circle"></i>
                        {{ __('controlled_drugs.reject') }}
                    </h3>
                    <form action="{{ route('controlled-drugs.reject', $controlledDrug) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-semibold text-red-700 mb-1 block">
                                    {{ __('controlled_drugs.rejection_reason') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_reason" required class="form-input min-h-[80px]"
                                    placeholder="{{ __('controlled_drugs.rejection_reason_placeholder') }}"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                                {{ __('controlled_drugs.confirm_reject') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

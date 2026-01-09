@extends('layouts.app')

@section('title', __('prescriptions.view_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('prescriptions.title') }}
        </p>
        <span>{{ $prescription->prescription_number }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('prescriptions.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('back') }}
    </a>
    @if ($prescription->status === 'pending')
        <a href="{{ route('prescriptions.edit', $prescription) }}"
            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph ph-pencil-simple"></i>
            {{ __('edit') }}
        </a>
        <form action="{{ route('prescriptions.dispense', $prescription) }}" method="POST" class="contents">
            @csrf
            <button type="submit"
                class="px-5 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl shadow-lg transition flex items-center gap-2">
                <i class="ph-bold ph-pill"></i>
                {{ __('prescriptions.dispense') }}
            </button>
        </form>
    @endif
    @if ($prescription->can_refill)
        <form action="{{ route('prescriptions.refill', $prescription) }}" method="POST" class="contents">
            @csrf
            <button type="submit"
                class="px-5 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl shadow-lg transition flex items-center gap-2">
                <i class="ph-bold ph-arrows-clockwise"></i>
                {{ __('prescriptions.process_refill') }}
            </button>
        </form>
    @endif
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Prescription Status --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <div class="text-center mb-4">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-3
                        {{ $prescription->status === 'dispensed' ? 'bg-green-100' : ($prescription->status === 'pending' ? 'bg-amber-100' : 'bg-gray-100') }}">
                        <i
                            class="ph-fill text-3xl
                            {{ $prescription->status === 'dispensed' ? 'ph-check-circle text-green-600' : ($prescription->status === 'pending' ? 'ph-hourglass text-amber-600' : 'ph-prescription text-gray-600') }}"></i>
                    </div>
                    <div class="mb-2">{!! $prescription->status_badge !!}</div>
                    @if ($prescription->dispensed_at)
                        <p class="text-xs text-gray-500">
                            {{ __('prescriptions.dispensed_at') }} {{ $prescription->dispensed_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>

                @if ($prescription->refill_allowed > 0)
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">{{ __('prescriptions.refill_status') }}</span>
                            <span class="text-sm font-bold text-purple-600">
                                {{ $prescription->refill_count }}/{{ $prescription->refill_allowed }}
                            </span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full transition-all"
                                style="width: {{ ($prescription->refill_count / $prescription->refill_allowed) * 100 }}%">
                            </div>
                        </div>
                        @if ($prescription->next_refill_date)
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="ph ph-calendar"></i>
                                {{ __('prescriptions.next_refill') }}:
                                {{ $prescription->next_refill_date->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Customer Info --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-user text-ios-blue"></i>
                    {{ __('prescriptions.customer_info') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="ph-fill ph-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $prescription->customer->name }}</p>
                            <p class="text-sm text-gray-500">{{ $prescription->customer->phone }}</p>
                        </div>
                    </div>
                    @if ($prescription->customer->allergies)
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200">
                            <p class="text-xs font-semibold text-red-600 mb-1">
                                <i class="ph ph-warning"></i> {{ __('prescriptions.allergies') }}
                            </p>
                            <p class="text-sm text-red-700">{{ $prescription->customer->allergies }}</p>
                        </div>
                    @endif
                    <a href="{{ route('customers.show', $prescription->customer) }}"
                        class="text-sm text-ios-blue hover:underline font-medium flex items-center gap-1">
                        <i class="ph ph-arrow-right"></i>
                        {{ __('prescriptions.view_customer_history') }}
                    </a>
                </div>
            </div>

            {{-- Doctor Info --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-stethoscope text-green-500"></i>
                    {{ __('prescriptions.doctor_info') }}
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('prescriptions.doctor_name') }}</span>
                        <span class="font-medium text-gray-900">{{ $prescription->doctor_name }}</span>
                    </div>
                    @if ($prescription->doctor_license_no)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('prescriptions.doctor_license') }}</span>
                            <span class="font-medium text-gray-900">{{ $prescription->doctor_license_no }}</span>
                        </div>
                    @endif
                    @if ($prescription->hospital_clinic)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('prescriptions.hospital_clinic') }}</span>
                            <span class="font-medium text-gray-900">{{ $prescription->hospital_clinic }}</span>
                        </div>
                    @endif
                    @if ($prescription->doctor_phone)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('prescriptions.doctor_phone') }}</span>
                            <span class="font-medium text-ios-blue">{{ $prescription->doctor_phone }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Prescription Details --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-info text-purple-500"></i>
                    {{ __('prescriptions.prescription_details') }}
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('prescriptions.prescription_date') }}</span>
                        <span
                            class="font-medium text-gray-900">{{ $prescription->prescription_date->format('d/m/Y') }}</span>
                    </div>
                    @if ($prescription->expiry_date)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('prescriptions.valid_until') }}</span>
                            <span class="font-medium {{ $prescription->is_expired ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $prescription->expiry_date->format('d/m/Y') }}
                                @if ($prescription->is_expired)
                                    ({{ __('prescriptions.status_expired') }})
                                @endif
                            </span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('prescriptions.created_by') }}</span>
                        <span class="font-medium text-gray-900">{{ $prescription->user->name }}</span>
                    </div>
                    @if ($prescription->diagnosis)
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-gray-500 block mb-1">{{ __('prescriptions.diagnosis') }}</span>
                            <p class="text-gray-900">{{ $prescription->diagnosis }}</p>
                        </div>
                    @endif
                    @if ($prescription->notes)
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-gray-500 block mb-1">{{ __('prescriptions.notes') }}</span>
                            <p class="text-gray-900">{{ $prescription->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Drug Interaction Warning --}}
            @if (count($interactions) > 0)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border-2 border-red-300 bg-red-50">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <i class="ph-fill ph-warning text-red-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-700 mb-2">{{ __('prescriptions.interaction_warning') }}</h4>
                            <div class="space-y-2">
                                @foreach ($interactions as $interaction)
                                    <div class="p-2 rounded-lg"
                                        style="background: {{ $interaction['severity'] === 'contraindicated' ? '#7f1d1d20' : ($interaction['severity'] === 'major' ? '#ef444420' : ($interaction['severity'] === 'moderate' ? '#f59e0b20' : '#22c55e20')) }}; border-left: 3px solid {{ $interaction['severity'] === 'contraindicated' ? '#7f1d1d' : ($interaction['severity'] === 'major' ? '#ef4444' : ($interaction['severity'] === 'moderate' ? '#f59e0b' : '#22c55e')) }}">
                                        <p class="font-semibold text-gray-900">{{ $interaction['drug_a'] }} +
                                            {{ $interaction['drug_b'] }}</p>
                                        <p class="text-sm text-gray-700">{{ $interaction['description'] }}</p>
                                        @if ($interaction['management'])
                                            <p class="text-xs text-gray-500 mt-1">💡 {{ $interaction['management'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Medications List --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-pill text-ios-blue"></i>
                    {{ __('prescriptions.medications') }} ({{ $prescription->items->count() }})
                </h3>

                <div class="space-y-4">
                    @foreach ($prescription->items as $index => $item)
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-lg bg-ios-blue/10 flex items-center justify-center flex-shrink-0">
                                    <span class="font-bold text-ios-blue">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">{{ number_format($item->quantity, 0) }}
                                                {{ __('prescriptions.unit') }}</p>
                                            <p class="text-sm text-ios-blue font-semibold">
                                                ฿{{ number_format($item->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                                        <div class="p-2 rounded-lg bg-white">
                                            <span
                                                class="text-xs text-gray-500 block">{{ __('prescriptions.dosage') }}</span>
                                            <span class="font-medium text-gray-900">{{ $item->dosage }}</span>
                                        </div>
                                        <div class="p-2 rounded-lg bg-white">
                                            <span
                                                class="text-xs text-gray-500 block">{{ __('prescriptions.frequency') }}</span>
                                            <span class="font-medium text-gray-900">{{ $item->frequency }}</span>
                                        </div>
                                        @if ($item->duration)
                                            <div class="p-2 rounded-lg bg-white">
                                                <span
                                                    class="text-xs text-gray-500 block">{{ __('prescriptions.duration') }}</span>
                                                <span class="font-medium text-gray-900">{{ $item->duration }}</span>
                                            </div>
                                        @endif
                                        @if ($item->route)
                                            <div class="p-2 rounded-lg bg-white">
                                                <span
                                                    class="text-xs text-gray-500 block">{{ __('prescriptions.route') }}</span>
                                                <span class="font-medium text-gray-900">{{ $item->route }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if ($item->instructions)
                                        <div class="mt-2 p-2 rounded-lg bg-amber-50 text-sm">
                                            <i class="ph ph-info text-amber-600"></i>
                                            <span class="text-amber-700">{{ $item->instructions }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Total --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="font-bold text-gray-700">{{ __('prescriptions.estimated_total') }}</span>
                    <span
                        class="text-2xl font-bold text-ios-blue">฿{{ number_format($prescription->total_amount, 2) }}</span>
                </div>
            </div>

            {{-- Customer History --}}
            @if ($customerHistory->count() > 0)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-clock-counter-clockwise text-purple-500"></i>
                        {{ __('prescriptions.previous_prescriptions') }}
                    </h3>

                    <div class="space-y-3">
                        @foreach ($customerHistory as $history)
                            <a href="{{ route('prescriptions.show', $history) }}"
                                class="block p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $history->prescription_number }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $history->prescription_date->format('d/m/Y') }}
                                            - {{ $history->items->count() }} {{ __('prescriptions.items') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        {!! $history->status_badge !!}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

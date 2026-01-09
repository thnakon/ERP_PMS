@extends('layouts.app')

@section('title', __('prescriptions.refill_reminders_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('prescriptions.title') }}
        </p>
        <span>{{ __('prescriptions.refill_reminders_title') }}</span>
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
    <div class="space-y-6">
        {{-- Info Card --}}
        <div
            class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm bg-amber-50 border-2 border-amber-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <i class="ph-bold ph-bell-ringing text-amber-600 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-amber-900 text-lg mb-1">{{ __('prescriptions.refill_reminders_title') }}</h3>
                    <p class="text-amber-700 text-sm">
                        {{ __('prescriptions.reminder_info') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Prescription List --}}
        @if ($prescriptions->count() > 0)
            <div class="space-y-4">
                @foreach ($prescriptions as $prescription)
                    <div
                        class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-6">
                            {{-- Customer Info --}}
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="ph-fill ph-user text-purple-600 text-2xl"></i>
                                </div>
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $prescription->customer->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $prescription->customer->phone }}</p>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('prescriptions.show', $prescription) }}"
                                            class="text-ios-blue font-semibold text-sm hover:underline">
                                            {{ $prescription->prescription_number }}
                                        </a>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ __('prescriptions.dispensed_at') }}
                                            {{ $prescription->dispensed_at?->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Next Refill Date --}}
                                <div class="flex items-center gap-4 mb-4">
                                    @php
                                        $isOverdue = $prescription->next_refill_date?->isPast();
                                        $daysUntil = $prescription->next_refill_date?->diffInDays(now(), false);
                                    @endphp
                                    <div
                                        class="px-3 py-2 rounded-xl {{ $isOverdue ? 'bg-red-100' : 'bg-amber-100' }} flex items-center gap-2">
                                        <i
                                            class="ph-fill ph-calendar {{ $isOverdue ? 'text-red-600' : 'text-amber-600' }}"></i>
                                        <div>
                                            <span
                                                class="text-xs {{ $isOverdue ? 'text-red-600' : 'text-amber-600' }} font-medium">{{ __('prescriptions.due_date') }}</span>
                                            <p class="font-bold {{ $isOverdue ? 'text-red-700' : 'text-amber-700' }}">
                                                {{ $prescription->next_refill_date?->format('d/m/Y') }}
                                                @if ($isOverdue)
                                                    <span class="text-sm">({{ __('prescriptions.overdue') }}
                                                        {{ abs($daysUntil) }} {{ __('days') }})</span>
                                                @else
                                                    <span class="text-sm">({{ __('prescriptions.due_soon') }}
                                                        {{ abs($daysUntil) }} {{ __('days') }})</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 rounded-xl bg-purple-100 flex items-center gap-2">
                                        <i class="ph-fill ph-arrows-clockwise text-purple-600"></i>
                                        <div>
                                            <span
                                                class="text-xs text-purple-600 font-medium">{{ __('prescriptions.refill') }}</span>
                                            <p class="font-bold text-purple-700">
                                                {{ $prescription->refill_count }}/{{ $prescription->refill_allowed }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Medications Summary --}}
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-xs text-gray-500 font-semibold mb-2 uppercase">
                                        {{ __('prescriptions.medications') }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($prescription->items as $item)
                                            <span class="px-2 py-1 bg-white rounded-lg text-sm font-medium text-gray-700">
                                                {{ $item->product->name }} ({{ number_format($item->quantity, 0) }})
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-2 flex-shrink-0">
                                @if ($prescription->customer->phone)
                                    <a href="tel:{{ $prescription->customer->phone }}"
                                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
                                        <i class="ph-bold ph-phone"></i>
                                        {{ __('prescriptions.call_customer') }}
                                    </a>
                                @endif
                                <form action="{{ route('prescriptions.refill', $prescription) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl transition flex items-center gap-2 justify-center">
                                        <i class="ph-bold ph-arrows-clockwise"></i>
                                        {{ __('prescriptions.process_refill') }}
                                    </button>
                                </form>
                                <a href="{{ route('prescriptions.show', $prescription) }}"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2 justify-center">
                                    <i class="ph ph-eye"></i>
                                    {{ __('view') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-12 border border-white shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-4">
                    <i class="ph-fill ph-check-circle text-green-600 text-4xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-xl mb-2">{{ __('prescriptions.no_reminders') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('prescriptions.no_reminders_desc') }}</p>
                <a href="{{ route('prescriptions.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl transition">
                    <i class="ph ph-arrow-left"></i>
                    {{ __('back') }}
                </a>
            </div>
        @endif
    </div>
@endsection

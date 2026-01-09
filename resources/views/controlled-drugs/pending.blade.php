@extends('layouts.app')

@section('title', __('controlled_drugs.pending_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('controlled_drugs.title') }}
        </p>
        <span>{{ __('controlled_drugs.pending_title') }}</span>
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
    <div class="space-y-6">
        {{-- Info --}}
        <div
            class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm bg-amber-50 border-2 border-amber-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <i class="ph-bold ph-hourglass text-amber-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-amber-900 text-lg mb-1">{{ __('controlled_drugs.pending_title') }}</h3>
                    <p class="text-amber-700 text-sm">
                        {{ __('controlled_drugs.pending_info') }}
                    </p>
                </div>
            </div>
        </div>

        @if ($logs->count() > 0)
            <div class="space-y-4">
                @foreach ($logs as $log)
                    @php
                        $borderColor = match ($log->product->drug_schedule ?? 'normal') {
                            'dangerous' => '#f97316',
                            'specially_controlled' => '#ef4444',
                            'narcotic' => '#7f1d1d',
                            'psychotropic' => '#581c87',
                            default => '#E5E7EB',
                        };
                    @endphp
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm border-l-4"
                        style="border-left-color: {{ $borderColor }}">
                        <div class="flex items-start gap-6">
                            {{-- Drug Info --}}
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 rounded-xl bg-red-100 flex items-center justify-center">
                                    <i class="ph-fill ph-pill text-red-600 text-2xl"></i>
                                </div>
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $log->product->name }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            {!! $log->product->drug_schedule_badge !!}
                                            <span class="text-sm text-gray-500">{{ $log->log_number }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-gray-900">{{ number_format($log->quantity, 0) }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ __('controlled_drugs.trans_' . $log->transaction_type) }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="p-3 rounded-xl bg-gray-50">
                                        <p class="text-xs text-gray-500 font-semibold mb-1">
                                            {{ __('controlled_drugs.recipient') }}</p>
                                        <p class="font-medium text-gray-900">{{ $log->customer_name }}</p>
                                        @if ($log->customer_id_card)
                                            <p class="text-xs text-gray-500 font-mono">{{ $log->customer_id_card }}</p>
                                        @endif
                                    </div>
                                    <div class="p-3 rounded-xl bg-gray-50">
                                        <p class="text-xs text-gray-500 font-semibold mb-1">
                                            {{ __('controlled_drugs.created_by') }}</p>
                                        <p class="font-medium text-gray-900">{{ $log->createdBy->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @if ($log->purpose)
                                        <div class="p-3 rounded-xl bg-gray-50">
                                            <p class="text-xs text-gray-500 font-semibold mb-1">
                                                {{ __('controlled_drugs.purpose') }}</p>
                                            <p class="font-medium text-gray-900 text-sm truncate">{{ $log->purpose }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if ($log->doctor_name)
                                    <div class="p-3 rounded-xl bg-green-50 inline-block">
                                        <p class="text-xs text-green-600 font-semibold">
                                            <i class="ph ph-prescription"></i>
                                            {{ __('controlled_drugs.prescription_info') }}:
                                            {{ $log->prescription_number }} - {{ $log->doctor_name }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-2 flex-shrink-0">
                                <form action="{{ route('controlled-drugs.approve', $log) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2 justify-center">
                                        <i class="ph-bold ph-check-circle"></i>
                                        {{ __('controlled_drugs.approve') }}
                                    </button>
                                </form>
                                <a href="{{ route('controlled-drugs.show', $log) }}"
                                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2 justify-center">
                                    <i class="ph ph-eye"></i>
                                    {{ __('controlled_drugs.view_details') }}
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
                <h3 class="font-bold text-gray-900 text-xl mb-2">{{ __('controlled_drugs.no_pending') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('controlled_drugs.no_pending_desc') }}</p>
                <a href="{{ route('controlled-drugs.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl transition">
                    <i class="ph ph-arrow-left"></i>
                    {{ __('controlled_drugs.back_to_main') }}
                </a>
            </div>
        @endif
    </div>
@endsection

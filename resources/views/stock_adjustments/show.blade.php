@extends('layouts.app')

@section('title', $adjustment->adjustment_number)
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('stock-adjustments.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            {{ __('back_to_adjustments') }}
        </a>
        <span>{{ $adjustment->adjustment_number }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Adjustment Header Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $adjustment->adjustment_number }}</h2>
                    <p class="text-gray-500 mt-1">{{ $adjustment->adjusted_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    @if ($adjustment->type === 'increase')
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 font-bold rounded-xl">
                            <i class="ph-bold ph-arrow-circle-up"></i>
                            {{ __('increase') }}
                        </span>
                    @elseif($adjustment->type === 'decrease')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 font-bold rounded-xl">
                            <i class="ph-bold ph-arrow-circle-down"></i>
                            {{ __('decrease') }}
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 font-bold rounded-xl">
                            <i class="ph-bold ph-equals"></i>
                            {{ __('set_absolute') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 bg-white rounded-xl flex items-center justify-center border border-gray-200 overflow-hidden">
                        @if ($adjustment->product->image_path)
                            <img src="{{ asset('storage/' . $adjustment->product->image_path) }}"
                                class="w-full h-full object-cover">
                        @else
                            <i class="ph ph-pill text-gray-300 text-2xl"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-gray-900">{{ $adjustment->product->name }}</h3>
                        <p class="text-sm text-gray-500">SKU: {{ $adjustment->product->sku }}</p>
                        @if ($adjustment->lot)
                            <p class="text-sm text-gray-500">Lot: {{ $adjustment->lot->lot_number }} | Exp:
                                {{ $adjustment->lot->expiry_date->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    <a href="{{ route('products.show', $adjustment->product) }}"
                        class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        {{ __('view') }} {{ __('product') }}
                    </a>
                </div>
            </div>

            {{-- Quantity Changes --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('before') }}</span>
                    <p class="text-2xl font-black text-gray-900 mt-1">{{ $adjustment->before_quantity }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center relative">
                    <div
                        class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow border border-gray-100">
                        <i class="ph ph-arrow-right text-gray-400"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('quantity') }}</span>
                    <p
                        class="text-2xl font-black mt-1 {{ $adjustment->type === 'increase' ? 'text-green-600' : ($adjustment->type === 'decrease' ? 'text-red-600' : 'text-blue-600') }}">
                        @if ($adjustment->type === 'increase')
                            +{{ abs($adjustment->quantity) }}
                        @elseif($adjustment->type === 'decrease')
                            -{{ abs($adjustment->quantity) }}
                        @else
                            {{ $adjustment->quantity }}
                        @endif
                    </p>
                    <div
                        class="absolute top-1/2 right-0 -translate-y-1/2 translate-x-1/2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow border border-gray-100">
                        <i class="ph ph-arrow-right text-gray-400"></i>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('after') }}</span>
                    <p class="text-2xl font-black text-gray-900 mt-1">{{ $adjustment->after_quantity }}</p>
                </div>
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reason') }}</span>
                    <p class="text-gray-900 font-medium mt-1">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-lg text-sm">
                            {{ __($adjustment->reason) ?? $adjustment->reason }}
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('auditor') }}</span>
                    <div class="flex items-center gap-2 mt-1">
                        <div
                            class="w-8 h-8 rounded-full bg-ios-blue/10 flex items-center justify-center text-sm text-ios-blue font-bold">
                            {{ substr($adjustment->user->name, 0, 1) }}
                        </div>
                        <span class="font-medium text-gray-900">{{ $adjustment->user->name }}</span>
                    </div>
                </div>
            </div>

            @if ($adjustment->notes)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('notes') }}</span>
                    <p class="text-gray-700 mt-1">{{ $adjustment->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Back Button --}}
        <div class="flex justify-center">
            <a href="{{ route('stock-adjustments.index') }}"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">

                {{ __('back_to_adjustments') }}
            </a>
        </div>
    </div>
@endsection

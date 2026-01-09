@extends('layouts.app')

@section('title', $product->name)

@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('products.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            {{ __('back_to_products') }}
        </a>
        <span>{{ $product->name }}</span>
    </div>
@endsection

@section('content')

    {{-- Page Sub-Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            @if ($product->name_th)
                <p class="text-gray-500 text-lg">{{ $product->name_th }}</p>
            @endif
            <div class="flex items-center gap-3 mt-2">
                <span
                    class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-lg">
                    <i class="ph ph-barcode"></i>
                    {{ $product->sku }}
                </span>
                @if ($product->barcode)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-lg">
                        {{ $product->barcode }}
                    </span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.edit', $product) }}"
                class="px-4 py-2 bg-ios-blue text-white text-sm font-medium rounded-xl hover:brightness-110 transition flex items-center gap-2">
                <i class="ph ph-pencil"></i>
                {{ __('edit') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Product Image & Basic Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex gap-6">
                    {{-- Product Image --}}
                    <div
                        class="w-40 h-40 bg-gray-100 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <i class="ph ph-image text-gray-300 text-5xl"></i>
                        @endif
                    </div>
                    {{-- Quick Info --}}
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">{{ __('overview') }}
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-400">{{ __('generic_name') }}</span>
                                <p class="font-medium text-gray-900">{{ $product->generic_name ?: '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400">{{ __('category') }}</span>
                                <p class="font-medium text-gray-900">{{ $product->category?->localized_name ?: '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400">{{ __('drug_class') }}</span>
                                <p class="font-medium text-gray-900">{{ $product->drug_class ?: '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400">{{ __('manufacturer') }}</span>
                                <p class="font-medium text-gray-900">{{ $product->manufacturer ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing Section --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-currency-circle-dollar text-blue-500"></i>
                    {{ __('pricing_units') }}
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <span class="text-xs text-blue-600">{{ __('cost_price') }}</span>
                        <p class="text-xl font-bold text-blue-900">฿{{ number_format($product->cost_price ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4">
                        <span class="text-xs text-green-600">{{ __('sell_price') }}</span>
                        <p class="text-xl font-bold text-green-900">฿{{ number_format($product->unit_price, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4">
                        <span class="text-xs text-purple-600">{{ __('member_price') }}</span>
                        <p class="text-xl font-bold text-purple-900">฿{{ number_format($product->member_price ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <span class="text-xs text-gray-600">{{ __('vat_applicable') }}</span>
                        <p class="text-xl font-bold text-gray-900">
                            @if ($product->vat_applicable)
                                <i class="ph-fill ph-check-circle text-green-500"></i>
                            @else
                                <i class="ph-fill ph-x-circle text-gray-400"></i>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-400">{{ __('base_unit') }}</span>
                        <p class="font-medium text-gray-900">{{ $product->base_unit ?: 'pcs' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">{{ __('sell_unit') }}</span>
                        <p class="font-medium text-gray-900">{{ $product->sell_unit ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">{{ __('conversion') }}</span>
                        <p class="font-medium text-gray-900">{{ $product->conversion_factor ?? 1 }}</p>
                    </div>
                </div>
            </div>

            {{-- Clinical Information --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-first-aid-kit text-orange-500"></i>
                    {{ __('clinical_info') }}
                </h3>
                <div class="mb-4">
                    @if ($product->requires_prescription)
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-100 text-orange-700 text-sm font-semibold rounded-lg">
                            <i class="ph ph-prescription"></i>
                            {{ __('requires_prescription') }}
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg">
                            <i class="ph ph-check-circle"></i>
                            {{ __('no_prescription') }}
                        </span>
                    @endif
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-gray-400">{{ __('precautions') }}</span>
                        <p class="text-gray-700 mt-1">{{ $product->precautions ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">{{ __('side_effects') }}</span>
                        <p class="text-gray-700 mt-1">{{ $product->side_effects ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">{{ __('instructions') }}</span>
                        <p class="text-gray-700 mt-1">{{ $product->default_instructions ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Inventory & Meta --}}
        <div class="space-y-6">
            {{-- Stock Status --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-package text-green-500"></i>
                    {{ __('inventory') }}
                </h3>

                {{-- Stock Indicator --}}
                @php
                    $stockClass = 'bg-green-500';
                    $stockText = __('in_stock');
                    if ($product->stock_qty <= 0) {
                        $stockClass = 'bg-red-500';
                        $stockText = __('out_of_stock');
                    } elseif ($product->stock_qty <= $product->min_stock) {
                        $stockClass = 'bg-orange-500';
                        $stockText = __('low_stock');
                    }
                @endphp
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-3 h-3 {{ $stockClass }} rounded-full"></span>
                    <span class="font-semibold text-gray-900">{{ $stockText }}</span>
                </div>

                <div class="text-center py-4 bg-gray-50 rounded-xl mb-4">
                    <p class="text-4xl font-bold text-gray-900">{{ number_format($product->stock_qty) }}</p>
                    <p class="text-sm text-gray-500">{{ __('quantity') }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <span class="text-xs text-gray-400">{{ __('min_stock') }}</span>
                        <p class="font-semibold text-gray-900">{{ $product->min_stock ?? 0 }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <span class="text-xs text-gray-400">{{ __('reorder') }}</span>
                        <p class="font-semibold text-gray-900">{{ $product->reorder_point ?? 0 }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <span class="text-xs text-gray-400">{{ __('max_stock') }}</span>
                        <p class="font-semibold text-gray-900">{{ $product->max_stock ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <span class="text-xs text-gray-400">{{ __('shelf_location') }}</span>
                        <p class="font-semibold text-gray-900">{{ $product->location ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Meta Information --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-info text-gray-400"></i>
                    {{ __('meta_info') }}
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">ID</span>
                        <span class="font-mono text-gray-600">#{{ $product->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">{{ __('created_at') }}</span>
                        <span class="text-gray-600">{{ $product->created_at?->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">{{ __('updated_at') }}</span>
                        <span class="text-gray-600">{{ $product->updated_at?->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', __('bundles.details'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('bundles.title') }}
        </p>
        <span>{{ __('bundles.details') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex gap-2">
        <a href="{{ route('bundles.edit', $bundle) }}"
            class="px-4 py-2 bg-orange-100 text-orange-600 font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph-bold ph-pencil"></i>
            {{ __('edit') }}
        </a>
        <a href="{{ route('bundles.index') }}"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph-bold ph-arrow-left"></i>
            {{ __('general.back') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Bundle Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card-ios overflow-hidden">
                {{-- Hero Header --}}
                <div
                    class="relative h-64 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center">
                    @if ($bundle->image_path)
                        <img src="{{ asset('storage/' . $bundle->image_path) }}"
                            class="w-full h-full object-cover mix-blend-overlay opacity-60">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-8">
                            <h1 class="text-4xl font-black text-white drop-shadow-lg">{{ $bundle->name }}</h1>
                            @if ($bundle->name_th)
                                <h2 class="text-xl font-bold text-white/80 drop-shadow-md">{{ $bundle->name_th }}</h2>
                            @endif
                        </div>
                    @else
                        <i class="ph-fill ph-package text-white/20 text-9xl absolute -right-8 -bottom-8"></i>
                        <div class="relative z-10 text-center px-8">
                            <h1 class="text-4xl font-black text-white">{{ $bundle->name }}</h1>
                            @if ($bundle->name_th)
                                <h2 class="text-xl font-bold text-white/80 mt-1">{{ $bundle->name_th }}</h2>
                            @endif
                        </div>
                    @endif

                    @if ($bundle->isAvailable())
                        <div
                            class="absolute top-6 right-6 px-4 py-2 bg-green-500 text-white text-xs font-black uppercase tracking-widest rounded-full shadow-lg border-2 border-white/30 animate-pulse">
                            Available Now
                        </div>
                    @endif
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Bundle Price</p>
                            <p class="text-4xl font-black text-green-600">฿{{ number_format($bundle->bundle_price, 0) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Original Value
                            </p>
                            <p class="text-2xl font-black text-gray-400 line-through">
                                ฿{{ number_format($bundle->original_price, 0) }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-2xl flex flex-col justify-center">
                            <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1 text-center">Your
                                Savings</p>
                            <p class="text-2xl font-black text-red-600 text-center">
                                ฿{{ number_format($bundle->savings, 0) }} ({{ $bundle->savings_percent }}%)</p>
                        </div>
                    </div>

                    @if ($bundle->description)
                        <div class="mt-8 pt-8 border-t border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">About this bundle
                            </p>
                            <p class="text-gray-700 leading-relaxed">{{ $bundle->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Products in Bundle --}}
            <div class="card-ios p-6">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-list-bullets text-indigo-500"></i>
                    Package Contents ({{ $bundle->products->count() }} Items)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($bundle->products as $product)
                        <div
                            class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-indigo-200 transition-colors">
                            <div
                                class="w-16 h-16 rounded-xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden relative">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="ph-fill ph-package text-gray-200 text-2xl"></i>
                                @endif
                                <div
                                    class="absolute top-1 right-1 bg-indigo-600 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center shadow-md">
                                    {{ $bundle->products->find($product->id)->pivot->quantity }}x
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-xs font-bold text-indigo-600">Unit:
                                    ฿{{ number_format($product->unit_price, 2) }}</p>
                                <p class="text-[10px] text-gray-400 uppercase font-black mt-1">Total:
                                    ฿{{ number_format($product->unit_price * $bundle->products->find($product->id)->pivot->quantity, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            <div class="card-ios p-6">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie text-blue-500"></i>
                    Sales & Stock
                </h3>

                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500 font-bold uppercase text-[10px]">Stock Availability</span>
                            <span class="text-gray-900 font-black">{{ $bundle->remaining_stock }} /
                                {{ $bundle->stock_limit ?: '∞' }}</span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            @php
                                $stockPercent =
                                    $bundle->stock_limit > 0
                                        ? ($bundle->remaining_stock / $bundle->stock_limit) * 100
                                        : 100;
                            @endphp
                            <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full transition-all"
                                style="width: {{ $stockPercent }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Bundles Sold</p>
                            <p class="text-2xl font-black text-blue-600">{{ number_format($bundle->sold_count) }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl border-2 border-green-100 bg-green-50/50">
                            <p class="text-[10px] font-black text-green-500 uppercase mb-1">Revenue Generated</p>
                            <p class="text-2xl font-black text-green-600">
                                ฿{{ number_format($bundle->sold_count * $bundle->bundle_price, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-ios p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-calendar text-purple-500"></i>
                    Availability
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="ph-bold ph-calendar-plus"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Valid From</p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $bundle->start_date ? $bundle->start_date->format('d M Y') : 'Life-time' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                            <i class="ph-bold ph-calendar-x"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Expires On</p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $bundle->end_date ? $bundle->end_date->format('d M Y') : 'Life-time' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

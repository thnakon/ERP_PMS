@extends('layouts.app')

@section('title', __('promotions.details'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('promotions.title') }}
        </p>
        <span>{{ __('promotions.details') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex gap-2">
        <a href="{{ route('promotions.edit', $promotion) }}"
            class="px-4 py-2 bg-orange-100 text-orange-600 font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph-bold ph-pencil"></i>
            {{ __('edit') }}
        </a>
        <a href="{{ route('promotions.index') }}"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph-bold ph-arrow-left"></i>
            {{ __('general.back') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Promotion Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card-ios p-8 relative overflow-hidden">
                @if ($promotion->is_featured)
                    <div class="absolute top-0 right-0 p-4">
                        <i class="ph-fill ph-star text-4xl text-yellow-400"></i>
                    </div>
                @endif

                <div class="flex items-start gap-6">
                    <div
                        class="w-32 h-32 rounded-3xl bg-purple-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                        @if ($promotion->image_path)
                            <img src="{{ asset('storage/' . $promotion->image_path) }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph-fill ph-tag text-purple-600 text-5xl"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                                {{ $promotion->type_label }}
                            </span>
                            @if ($promotion->isCurrentlyActive())
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Active Now
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl font-black text-gray-900 mb-1">{{ $promotion->name }}</h1>
                        @if ($promotion->name_th)
                            <h2 class="text-xl font-bold text-gray-400 mb-4">{{ $promotion->name_th }}</h2>
                        @endif

                        @if ($promotion->code)
                            <div
                                class="inline-block bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl px-6 py-3">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Promo Code
                                </p>
                                <p class="text-2xl font-black text-ios-blue font-mono">{{ $promotion->code }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-100 pt-8">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase mb-1">Discount</p>
                        <p class="text-2xl font-black text-gray-900">
                            @if ($promotion->type === 'percentage')
                                {{ $promotion->discount_value }}% OFF
                            @elseif($promotion->type === 'fixed_amount')
                                ฿{{ number_format($promotion->discount_value, 0) }} OFF
                            @elseif($promotion->type === 'buy_x_get_y')
                                BUY {{ $promotion->buy_quantity }} GET {{ $promotion->get_quantity }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase mb-1">Min. Purchase</p>
                        <p class="text-2xl font-black text-gray-900">
                            {{ $promotion->min_purchase > 0 ? '฿' . number_format($promotion->min_purchase, 0) : 'None' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase mb-1">Max Discount</p>
                        <p class="text-2xl font-black text-gray-900">
                            {{ $promotion->max_discount > 0 ? '฿' . number_format($promotion->max_discount, 0) : 'Unlimited' }}
                        </p>
                    </div>
                </div>

                @if ($promotion->description || $promotion->description_th)
                    <div class="mt-8 p-6 bg-gray-50 rounded-3xl">
                        <p class="text-sm font-bold text-gray-400 uppercase mb-3">Target & Description</p>
                        <div class="space-y-4 text-gray-700 leading-relaxed">
                            @if ($promotion->description)
                                <p>{{ $promotion->description }}</p>
                            @endif
                            @if ($promotion->description_th)
                                <p class="text-gray-500">{{ $promotion->description_th }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Products Included --}}
            @if ($promotion->products->count() > 0 || $promotion->categories->count() > 0)
                <div class="card-ios p-6">
                    <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ph-fill ph-package text-orange-500"></i>
                        Applicable Products & Categories
                    </h3>

                    @if ($promotion->categories->count() > 0)
                        <div class="mb-6">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Categories</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($promotion->categories as $category)
                                    <span
                                        class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold border border-blue-100 italic">
                                        #{{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($promotion->products->count() > 0)
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Individual
                                Products</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($promotion->products as $product)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden">
                                            @if ($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <i class="ph ph-package text-gray-300"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400">฿{{ number_format($product->unit_price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="card-ios p-8 text-center bg-blue-50/50 border-blue-100">
                    <i class="ph-fill ph-sparkle text-3xl text-blue-400 mb-2"></i>
                    <h3 class="text-lg font-black text-blue-700">All Store Products</h3>
                    <p class="text-blue-600/60 text-sm">This promotion applies to everything in your store.</p>
                </div>
            @endif

            {{-- Usage History --}}
            <div class="card-ios p-6">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-history text-blue-500"></i>
                    Recent Usage History
                </h3>

                @if ($promotion->usages->isEmpty())
                    <div class="py-12 text-center text-gray-400">
                        <i class="ph ph-receipt text-4xl mb-2 opacity-20"></i>
                        <p>No usage records found yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr
                                    class="text-left text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    <th class="pb-3">Customer</th>
                                    <th class="pb-3 text-right">Order #</th>
                                    <th class="pb-3 text-right">Discount Given</th>
                                    <th class="pb-3 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($promotion->usages->take(10) as $usage)
                                    <tr>
                                        <td class="py-4">
                                            <div class="font-bold text-gray-900">{{ $usage->customer->name ?? 'Guest' }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 uppercase">
                                                {{ $usage->customer->phone ?? '' }}</div>
                                        </td>
                                        <td class="py-4 text-right">
                                            <span
                                                class="font-mono text-xs text-blue-600 font-bold">#{{ $usage->order_id }}</span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <span
                                                class="font-black text-green-600">฿{{ number_format($usage->discount_amount, 2) }}</span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <span
                                                class="text-xs text-gray-500">{{ $usage->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Stats --}}
        <div class="space-y-6">
            <div class="card-ios p-6">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-line text-blue-500"></i>
                    Performance Status
                </h3>

                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500 font-bold uppercase text-[10px]">Usage Progress</span>
                            <span class="text-gray-900 font-black">{{ $promotion->usage_count }} /
                                {{ $promotion->usage_limit ?: '∞' }}</span>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            @php
                                $percent =
                                    $promotion->usage_limit > 0
                                        ? ($promotion->usage_count / $promotion->usage_limit) * 100
                                        : 0;
                            @endphp
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all"
                                style="width: {{ min($percent, 100) }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Total Discount Given</p>
                            <p class="text-2xl font-black text-green-600">
                                ฿{{ number_format($promotion->usages->sum('discount_amount'), 0) }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Target Members</p>
                            <p class="text-2xl font-black text-purple-600">
                                {{ $promotion->memberTier ? $promotion->memberTier->name : 'All Members' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-ios p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-clock text-orange-500"></i>
                    Schedule
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                            <i class="ph-bold ph-calendar-plus"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Starts</p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $promotion->start_date ? $promotion->start_date->format('d M Y, H:i') : 'Immediate' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                            <i class="ph-bold ph-calendar-x"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Expires</p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $promotion->end_date ? $promotion->end_date->format('d M Y, H:i') : 'Never' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

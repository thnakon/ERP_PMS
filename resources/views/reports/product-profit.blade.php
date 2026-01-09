@extends('layouts.app')

@section('title', __('reports.product_profit_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('reports_analytics') }}
        </p>
        <span>{{ __('reports.product_profit_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <form action="{{ route('reports.product-profit') }}" method="GET" class="flex items-center gap-2">
        <input type="date" name="start_date" value="{{ $startDate }}"
            class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
        <span class="text-gray-400">→</span>
        <input type="date" name="end_date" value="{{ $endDate }}"
            class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
        <button type="submit"
            class="px-4 py-2 bg-ios-blue text-white font-semibold rounded-xl hover:bg-blue-600 transition flex items-center gap-2">
            <i class="ph ph-funnel"></i>
            {{ __('filter') }}
        </button>
    </form>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Total Sales --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #3B82F6, #2563EB);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.total_sales') }}</span>
                    <i class="ph-fill ph-shopping-bag text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($summary['total_sales'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">{{ $summary['product_count'] }}
                    {{ __('reports.products') }}</p>
            </div>

            {{-- Total Cost --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #6B7280, #4B5563);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.total_cost') }}</span>
                    <i class="ph-fill ph-coins text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($summary['total_cost'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">{{ __('reports.cost_of_goods') }}</p>
            </div>

            {{-- Total Profit --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #22C55E, #16A34A);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.gross_profit') }}</span>
                    <i class="ph-fill ph-trend-up text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($summary['total_profit'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ number_format($summary['profit_margin'], 1) }}% {{ __('reports.margin') }}</p>
            </div>

            {{-- Profit/Loss Split --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('reports.profit_loss_split') }}</span>
                    <i class="ph-fill ph-chart-pie text-2xl text-purple-500"></i>
                </div>
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-lg font-bold text-green-600">{{ $summary['profitable_count'] }}</p>
                        <p class="text-xs text-gray-500">{{ __('reports.profitable') }}</p>
                    </div>
                    <div class="w-px h-8 bg-gray-200"></div>
                    <div>
                        <p class="text-lg font-bold text-red-600">{{ $summary['loss_count'] }}</p>
                        <p class="text-xs text-gray-500">{{ __('reports.loss') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Top Profitable Products --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-fill ph-trophy text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('reports.top_profitable') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('reports.highest_margin_products') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($topProfitable as $index => $product)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                                {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-600')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $product->product_name }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($product->total_quantity) }}
                                    {{ __('reports.units_sold') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">฿{{ number_format($product->profit, 0) }}</p>
                                <p class="text-xs text-gray-400">{{ number_format($product->profit_margin, 1) }}%</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-8">{{ __('reports.no_data') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Products with Loss --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="ph-fill ph-trend-down text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('reports.products_with_loss') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('reports.negative_margin_products') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($lossProducts as $product)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                <i class="ph-fill ph-warning text-red-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $product->product_name }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($product->total_quantity) }}
                                    {{ __('reports.units_sold') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">฿{{ number_format($product->profit, 0) }}</p>
                                <p class="text-xs text-gray-400">{{ number_format($product->profit_margin, 1) }}%</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ph-fill ph-check-circle text-4xl text-green-500 mb-2"></i>
                            <p class="text-gray-500">{{ __('reports.no_loss_products') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Category Profit Breakdown --}}
        <div class="card-ios p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <i class="ph-fill ph-chart-pie-slice text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ __('reports.profit_by_category') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('reports.category_profit_analysis') }}</p>
                </div>
            </div>
            @php
                $totalProfit = $categoryProfit->sum('total_profit') ?: 1;
                $categoryColors = [
                    '#22C55E',
                    '#3B82F6',
                    '#8B5CF6',
                    '#F59E0B',
                    '#EF4444',
                    '#06B6D4',
                    '#EC4899',
                    '#14B8A6',
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($categoryProfit->take(8) as $index => $cat)
                    @php
                        $percentage = ($cat['total_profit'] / $totalProfit) * 100;
                        $color = $categoryColors[$index % count($categoryColors)];
                    @endphp
                    <div class="p-4 rounded-xl bg-gray-50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $color }}"></div>
                            <span class="font-medium text-gray-900 truncate">{{ $cat['category'] }}</span>
                        </div>
                        <p class="text-xl font-bold {{ $cat['total_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ฿{{ number_format($cat['total_profit'], 0) }}
                        </p>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span>{{ $cat['product_count'] }} {{ __('reports.products') }}</span>
                            <span>{{ number_format(abs($percentage), 1) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Full Product Table --}}
        <div class="card-ios p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-table text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('reports.all_products_profit') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('reports.detailed_breakdown') }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 font-semibold">#</th>
                            <th class="px-4 py-3 font-semibold">{{ __('product') }}</th>
                            <th class="px-4 py-3 font-semibold">SKU</th>
                            <th class="px-4 py-3 font-semibold">{{ __('category') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.qty_sold') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.sales') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.cost') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.profit') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.margin') }}%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productStats->take(50) as $index => $product)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $product->product_name }}</p>
                                    @if ($product->product_name_th)
                                        <p class="text-xs text-gray-400">{{ $product->product_name_th }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-gray-500">{{ $product->sku }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->category_name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-medium">{{ number_format($product->total_quantity) }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium">
                                    ฿{{ number_format($product->total_sales, 0) }}</td>
                                <td class="px-4 py-3 text-right text-gray-500">
                                    ฿{{ number_format($product->total_cost, 0) }}</td>
                                <td
                                    class="px-4 py-3 text-right font-bold {{ $product->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ฿{{ number_format($product->profit, 0) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $product->profit_margin >= 20 ? 'bg-green-100 text-green-700' : ($product->profit_margin >= 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ number_format($product->profit_margin, 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($productStats->count() > 50)
                <p class="text-center text-gray-400 text-sm mt-4">{{ __('reports.showing_top_50') }}</p>
            @endif
        </div>
    </div>
@endsection

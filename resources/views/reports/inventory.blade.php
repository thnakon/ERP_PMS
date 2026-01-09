@extends('layouts.app')

@section('title', __('inventory_report.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('reports_analytics') }}
        </p>
        <span>{{ __('inventory_report.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('reports.inventory.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}"
        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-file-pdf"></i>
        PDF
    </a>
    <a href="{{ route('reports.inventory.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'excel']) }}"
        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-file-xls"></i>
        Excel
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header with Date Filter --}}
        <div class="card-ios p-4">
            <form action="{{ route('reports.inventory') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label
                        class="text-xs font-semibold text-gray-500 ml-1 mb-1 block">{{ __('inventory_report.date_range') }}</label>
                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="input-ios flex-1">
                        <span class="text-gray-400 self-center">→</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="input-ios flex-1">
                    </div>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-ios-blue text-white font-semibold rounded-xl hover:bg-blue-600 transition flex items-center gap-2">
                    <i class="ph ph-funnel"></i>
                    {{ __('inventory_report.filter') }}
                </button>
            </form>
        </div>

        {{-- Key Metrics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Total Cost Value --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #3B82F6, #2563EB);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('inventory_report.total_cost_value') }}</span>
                    <i class="ph-fill ph-warehouse text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($valuation['total_cost_value'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ number_format($valuation['total_products']) }} {{ __('inventory_report.units') }}
                </p>
            </div>

            {{-- Total Retail Value --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #22C55E, #16A34A);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('inventory_report.total_retail_value') }}</span>
                    <i class="ph-fill ph-currency-circle-dollar text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($valuation['total_retail_value'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('inventory_report.profit_margin') }}: {{ number_format($valuation['profit_margin'], 1) }}%
                </p>
            </div>

            {{-- Potential Profit --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('inventory_report.potential_profit') }}</span>
                    <i class="ph-fill ph-chart-line-up text-2xl text-emerald-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">฿{{ number_format($valuation['potential_profit'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ $valuation['total_skus'] }}
                    {{ __('inventory_report.total_skus') }}
                </p>
            </div>

            {{-- Efficiency: DSI --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('inventory_report.dsi') }}</span>
                    <i class="ph-fill ph-clock-countdown text-2xl text-amber-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($efficiency['dsi'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('inventory_report.days') }}</p>
            </div>
        </div>

        {{-- Risk Analysis Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Expired Stock --}}
            <div class="card-ios p-5 border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('inventory_report.expired_stock') }}</span>
                    <i class="ph-fill ph-warning-octagon text-2xl text-red-500"></i>
                </div>
                <p class="text-2xl font-bold text-red-600">฿{{ number_format($riskAnalysis['expired_value'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ $riskAnalysis['expired_count'] }}
                    {{ __('inventory_report.lots') }}</p>
            </div>

            {{-- Near Expiry 3 Months --}}
            <div class="card-ios p-5 border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('inventory_report.near_expiry_3m') }}</span>
                    <i class="ph-fill ph-hourglass-medium text-2xl text-orange-500"></i>
                </div>
                <p class="text-2xl font-bold text-orange-600">
                    ฿{{ number_format($riskAnalysis['near_expiry_3m_value'], 0) }}
                </p>
                <p class="text-xs mt-2 text-gray-500">{{ $riskAnalysis['near_expiry_3m_count'] }}
                    {{ __('inventory_report.lots') }}</p>
            </div>

            {{-- Near Expiry 6 Months --}}
            <div class="card-ios p-5 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('inventory_report.near_expiry_6m') }}</span>
                    <i class="ph-fill ph-hourglass-low text-2xl text-yellow-500"></i>
                </div>
                <p class="text-2xl font-bold text-yellow-600">
                    ฿{{ number_format($riskAnalysis['near_expiry_6m_value'], 0) }}
                </p>
                <p class="text-xs mt-2 text-gray-500">{{ $riskAnalysis['near_expiry_6m_count'] }}
                    {{ __('inventory_report.lots') }}</p>
            </div>

            {{-- Adjustment Losses --}}
            <div class="card-ios p-5 border-l-4 border-gray-500">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('inventory_report.adjustment_losses') }}</span>
                    <i class="ph-fill ph-arrow-square-out text-2xl text-gray-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-700">฿{{ number_format($riskAnalysis['adjustment_value'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('inventory_report.stock_movements') }}</p>
            </div>
        </div>

        {{-- Charts Row: Stock by Category & Stock Movements --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Stock by Category --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-chart-pie-slice text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('inventory_report.category_breakdown') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('inventory_report.category_analysis') }}</p>
                    </div>
                </div>
                @php
                    $totalCategoryValue = collect($categoryBreakdown)->sum('cost_value') ?: 1;
                    $categoryColors = [
                        '#007AFF',
                        '#34C759',
                        '#FF9500',
                        '#FF3B30',
                        '#AF52DE',
                        '#5856D6',
                        '#00C7BE',
                        '#FF2D55',
                    ];
                @endphp
                <div class="space-y-3">
                    @forelse($categoryBreakdown as $index => $cat)
                        @php
                            $percentage = ($cat['cost_value'] / $totalCategoryValue) * 100;
                            $color = $categoryColors[$index % count($categoryColors)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $color }}"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $cat['name'] }}</span>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900">฿{{ number_format($cat['cost_value'], 0) }}</span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all"
                                    style="width: {{ $percentage }}%; background-color: {{ $color }}"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-400">{{ number_format($cat['total_stock']) }}
                                    {{ __('inventory_report.units') }}</span>
                                <span class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-8">{{ __('inventory_report.no_data') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Stock Movements Summary --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-arrows-left-right text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('inventory_report.stock_movements') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('inventory_report.movement_summary') }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    {{-- Stock In --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-green-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="ph-fill ph-arrow-down text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('inventory_report.stock_in') }}</p>
                                <p class="text-xs text-gray-500">{{ __('inventory_report.units') }}</p>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-green-600">+{{ number_format($stockMovements['stock_in']) }}</p>
                    </div>

                    {{-- Stock Out --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-red-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                <i class="ph-fill ph-arrow-up text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('inventory_report.stock_out') }}</p>
                                <p class="text-xs text-gray-500">{{ __('inventory_report.units') }}</p>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-red-600">-{{ number_format($stockMovements['stock_out']) }}</p>
                    </div>

                    {{-- Sold --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-blue-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="ph-fill ph-shopping-cart text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('inventory_report.sold') }}</p>
                                <p class="text-xs text-gray-500">{{ __('inventory_report.units') }}</p>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($stockMovements['sold']) }}</p>
                    </div>

                    {{-- Net Change --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                <i class="ph-fill ph-equals text-gray-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('inventory_report.net_change') }}</p>
                                <p class="text-xs text-gray-500">{{ __('inventory_report.units') }}</p>
                            </div>
                        </div>
                        <p
                            class="text-2xl font-bold {{ $stockMovements['net_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stockMovements['net_change'] >= 0 ? '+' : '' }}{{ number_format($stockMovements['net_change']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Row: Low Stock & Slow Moving --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Low Stock Alert --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="ph-fill ph-warning text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('inventory_report.low_stock') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('inventory_report.low_stock_desc') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($lowStockProducts as $product)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                <i class="ph-fill ph-package text-red-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product['sku'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">{{ number_format($product['stock_qty']) }} /
                                    {{ number_format($product['min_stock']) }}</p>
                                <p class="text-xs text-gray-500">{{ __('inventory_report.current') }} /
                                    {{ __('inventory_report.minimum') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ph-fill ph-check-circle text-4xl text-green-500 mb-2"></i>
                            <p class="text-gray-500">{{ __('inventory_report.no_data') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Slow Moving Products --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="ph-fill ph-clock text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('inventory_report.slow_moving') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('inventory_report.slow_moving_desc') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($slowMovingProducts as $product)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-50">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                <i class="ph-fill ph-package text-amber-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product['sku'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-600">{{ number_format($product['stock_qty']) }}
                                    {{ __('inventory_report.units') }}</p>
                                <p class="text-xs text-gray-500">฿{{ number_format($product['stock_value'], 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ph-fill ph-check-circle text-4xl text-green-500 mb-2"></i>
                            <p class="text-gray-500">{{ __('inventory_report.no_data') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Top Moving Products --}}
        <div class="card-ios p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                    <i class="ph-fill ph-trophy text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ __('inventory_report.top_moving') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('inventory_report.top_moving_desc') }}</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">#</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                {{ __('inventory_report.products') }}</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">SKU</th>
                            <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                {{ __('inventory_report.qty_sold') }}</th>
                            <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                {{ __('inventory_report.revenue') }}</th>
                            <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                {{ __('inventory_report.stock_qty') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topMovingProducts as $index => $product)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                                        {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-600')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $product['name'] }}</td>
                                <td class="py-3 px-4 text-gray-500 text-sm">{{ $product['sku'] }}</td>
                                <td class="py-3 px-4 text-right font-semibold text-green-600">
                                    {{ number_format($product['total_sold']) }}</td>
                                <td class="py-3 px-4 text-right font-bold text-gray-900">
                                    ฿{{ number_format($product['total_revenue'], 0) }}</td>
                                <td class="py-3 px-4 text-right text-gray-500">{{ number_format($product['stock_qty']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    {{ __('inventory_report.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', __('sales_report.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('reports_analytics') }}
        </p>
        <span>{{ __('sales_report.title') }}</span>
    </div>
@endsection

@section('header-actions')
    {{-- Total Discount Badge --}}
    <div class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 font-semibold rounded-xl flex items-center gap-2">
        <i class="ph-fill ph-tag"></i>
        <span class="text-xs uppercase">{{ __('sales_report.total_discount') }}:</span>
        <span class="font-bold">฿{{ number_format($metrics['total_discount'], 0) }}</span>
    </div>
    <a href="{{ route('reports.sales.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}"
        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-file-pdf"></i>
        PDF
    </a>
    <a href="{{ route('reports.sales.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'excel']) }}"
        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-file-xls"></i>
        Excel
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header with Date Filter --}}
        <div class="card-ios p-4">
            <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label
                        class="text-xs font-semibold text-gray-500 ml-1 mb-1 block">{{ __('sales_report.date_range') }}</label>
                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="input-ios flex-1">
                        <span class="text-gray-400 self-center">→</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="input-ios flex-1">
                    </div>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-ios-blue text-white font-semibold rounded-xl hover:bg-blue-600 transition flex items-center gap-2">
                    <i class="ph ph-funnel"></i>
                    {{ __('sales_report.filter') }}
                </button>
            </form>
        </div>

        {{-- Key Metrics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Net Sales --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #3B82F6, #2563EB);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('sales_report.net_sales') }}</span>
                    <i class="ph-fill ph-currency-circle-dollar text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($metrics['net_sales'], 0) }}</p>
                @if ($metrics['sales_growth'] != 0)
                    <p class="text-xs mt-2" style="color: {{ $metrics['sales_growth'] > 0 ? '#86EFAC' : '#FCA5A5' }};">
                        <i class="ph {{ $metrics['sales_growth'] > 0 ? 'ph-trend-up' : 'ph-trend-down' }}"></i>
                        {{ number_format(abs($metrics['sales_growth']), 1) }}% {{ __('sales_report.vs_last_period') }}
                    </p>
                @endif
            </div>

            {{-- Gross Profit --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #22C55E, #16A34A);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('sales_report.gross_profit') }}</span>
                    <i class="ph-fill ph-chart-line-up text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($metrics['gross_profit'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('sales_report.profit_margin') }}: {{ number_format($metrics['profit_margin'], 1) }}%
                </p>
            </div>

            {{-- Transactions --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('sales_report.transactions') }}</span>
                    <i class="ph-fill ph-receipt text-2xl text-amber-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($metrics['transaction_count']) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('sales_report.transactions_count') }}</p>
            </div>

            {{-- Average Basket --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('sales_report.avg_basket') }}</span>
                    <i class="ph-fill ph-shopping-cart text-2xl text-purple-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">฿{{ number_format($metrics['average_basket'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('sales_report.avg_basket') }}</p>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Hourly Sales Chart --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-clock text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('sales_report.hourly_sales') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('sales_report.peak_hours') }}</p>
                    </div>
                </div>
                @php
                    $maxHourly = collect($hourlyData)->max('sales') ?: 1;
                @endphp
                <div class="flex items-end gap-1" style="height: 200px;">
                    @foreach ($hourlyData as $hour)
                        @php
                            $barHeight = $maxHourly > 0 ? ($hour['sales'] / $maxHourly) * 100 : 0;
                            $barHeight = max($barHeight, 2); // Minimum 2% height for visibility
                        @endphp
                        <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                            <div class="w-full rounded-t-sm transition-all cursor-pointer"
                                style="height: {{ $barHeight }}%; min-height: 4px; background-color: {{ $hour['sales'] > 0 ? '#3B82F6' : '#E5E7EB' }};">
                            </div>
                            <span class="text-[9px] text-gray-400 mt-1">{{ substr($hour['hour'], 0, 2) }}</span>
                            {{-- Tooltip --}}
                            @if ($hour['sales'] > 0)
                                <div
                                    class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                    ฿{{ number_format($hour['sales'], 0) }} ({{ $hour['transactions'] }} bills)
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Category Breakdown --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-chart-pie-slice text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('sales_report.category_breakdown') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('sales_report.category_analysis') }}</p>
                    </div>
                </div>
                @php
                    $totalCategorySales = collect($categoryData)->sum('total_sales') ?: 1;
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
                    @forelse($categoryData as $index => $cat)
                        @php
                            $percentage = ($cat['total_sales'] / $totalCategorySales) * 100;
                            $color = $categoryColors[$index % count($categoryColors)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $color }}"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $cat['name'] }}</span>
                                </div>
                                <span
                                    class="text-sm font-semibold text-gray-900">฿{{ number_format($cat['total_sales'], 0) }}</span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all"
                                    style="width: {{ $percentage }}%; background-color: {{ $color }}"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-400">{{ number_format($cat['total_quantity']) }}
                                    {{ __('sales_report.items_sold') }}</span>
                                <span class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-8">{{ __('sales_report.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Products Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Top Sellers --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-fill ph-trophy text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('sales_report.top_sellers') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('sales_report.product_analysis') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($topProducts as $index => $product)
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl {{ $index < 3 ? 'bg-green-50' : 'bg-gray-50' }}">
                            <div
                                class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                                {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-600')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $product['product_name'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($product['total_quantity']) }}
                                    {{ __('sales_report.quantity_sold') }}</p>
                            </div>
                            <p class="font-bold text-green-600">฿{{ number_format($product['total_sales'], 0) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-8">{{ __('sales_report.no_data') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Dead Stock --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="ph-fill ph-warning text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('sales_report.dead_stock') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('sales_report.dead_stock_desc') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($deadStock as $product)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                <i class="ph-fill ph-package text-red-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product['sku'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">{{ number_format($product['stock_qty']) }} units
                                </p>
                                <p class="text-xs text-gray-500">฿{{ number_format($product['stock_value'], 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ph-fill ph-check-circle text-4xl text-green-500 mb-2"></i>
                            <p class="text-gray-500">{{ __('sales_report.no_dead_stock') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Staff & Customer Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Staff Performance --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="ph-fill ph-user-circle text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('sales_report.staff_performance') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('sales_report.staff_sales') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($staffSales as $index => $staff)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                            <div class="relative">
                                @if ($staff['avatar'])
                                    <img src="{{ asset('storage/' . $staff['avatar']) }}" alt="{{ $staff['name'] }}"
                                        class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($staff['name'], 0, 2)) }}
                                    </div>
                                @endif
                                @if ($index === 0)
                                    <div
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center">
                                        <i class="ph-fill ph-crown text-[10px] text-yellow-900"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $staff['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($staff['transaction_count']) }}
                                    {{ __('sales_report.transactions') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">฿{{ number_format($staff['total_sales'], 0) }}</p>
                                <p class="text-xs text-gray-500">Ø ฿{{ number_format($staff['average_sale'], 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-8">{{ __('sales_report.no_data') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Customer Analysis --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center">
                        <i class="ph-fill ph-users-three text-cyan-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('sales_report.customer_analysis') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('sales_report.member_vs_walkin') }}</p>
                    </div>
                </div>

                {{-- Member vs Walk-in --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-100">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="ph-fill ph-identification-card text-cyan-600"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ __('sales_report.member') }}</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900">
                            ฿{{ number_format($customerAnalysis['member']['sales'], 0) }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ number_format($customerAnalysis['member']['count']) }}
                                bills</span>
                            <span
                                class="text-xs font-semibold text-cyan-600">{{ number_format($customerAnalysis['member']['percentage'], 1) }}%</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-gray-50 to-slate-100 border border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="ph-fill ph-user text-gray-500"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ __('sales_report.walk_in') }}</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900">
                            ฿{{ number_format($customerAnalysis['walk_in']['sales'], 0) }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ number_format($customerAnalysis['walk_in']['count']) }}
                                bills</span>
                            <span
                                class="text-xs font-semibold text-gray-500">{{ number_format($customerAnalysis['walk_in']['percentage'], 1) }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Top Customers --}}
                <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('sales_report.top_customers') }}</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto custom-scroll">
                    @forelse($customerAnalysis['top_customers'] as $customer)
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                            <div
                                class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center text-xs font-bold text-cyan-600">
                                {{ strtoupper(substr($customer['name'], 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $customer['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $customer['phone'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">
                                    ฿{{ number_format($customer['total_spent'], 0) }}</p>
                                <p class="text-xs text-gray-400">{{ $customer['visit_count'] }}
                                    {{ __('sales_report.visits') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4 text-sm">{{ __('sales_report.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', __('finance_report.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('reports_analytics') }}
        </p>
        <span>{{ __('finance_report.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('reports.finance.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'pdf']) }}"
        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-file-pdf"></i>
        PDF
    </a>
    <a href="{{ route('reports.finance.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'excel']) }}"
        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-file-xls"></i>
        Excel
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header with Date Filter --}}
        <div class="card-ios p-4">
            <form action="{{ route('reports.finance') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label
                        class="text-xs font-semibold text-gray-500 ml-1 mb-1 block">{{ __('finance_report.date_range') }}</label>
                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="input-ios flex-1">
                        <span class="text-gray-400 self-center">→</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="input-ios flex-1">
                    </div>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-ios-blue text-white font-semibold rounded-xl hover:bg-blue-600 transition flex items-center gap-2">
                    <i class="ph ph-funnel"></i>
                    {{ __('finance_report.filter') }}
                </button>
            </form>
        </div>

        {{-- P&L Key Metrics --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Net Revenue --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #3B82F6, #2563EB);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('finance_report.net_revenue') }}</span>
                    <i class="ph-fill ph-currency-circle-dollar text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($pnl['net_revenue'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ number_format($pnl['transaction_count']) }} {{ __('finance_report.transactions') }}
                </p>
            </div>

            {{-- Gross Profit --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #22C55E, #16A34A);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('finance_report.gross_profit') }}</span>
                    <i class="ph-fill ph-chart-line-up text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($pnl['gross_profit'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('finance_report.gross_margin') }}: {{ number_format($pnl['gross_margin'], 1) }}%
                </p>
            </div>

            {{-- Output VAT --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #8B5CF6, #7C3AED);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('finance_report.output_vat') }}</span>
                    <i class="ph-fill ph-receipt text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($taxReport['total_output_vat'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('finance_report.vat_7_percent') }}
                </p>
            </div>

            {{-- Avg Transaction --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('finance_report.avg_transaction') }}</span>
                    <i class="ph-fill ph-shopping-bag text-2xl text-amber-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">฿{{ number_format($pnl['avg_transaction'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('finance_report.transactions') }}</p>
            </div>
        </div>

        {{-- P&L Statement & Tax Report Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- P&L Statement --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-chart-bar text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('finance_report.pnl_statement') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('finance_report.pnl') }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    {{-- Gross Revenue --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                        <span class="text-gray-700 font-medium">{{ __('finance_report.gross_revenue') }}</span>
                        <span class="font-bold text-gray-900">฿{{ number_format($pnl['gross_revenue'], 0) }}</span>
                    </div>

                    {{-- Discounts --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50">
                        <span class="text-gray-700 font-medium">(-) {{ __('finance_report.discounts') }}</span>
                        <span class="font-bold text-red-600">฿{{ number_format($pnl['total_discount'], 0) }}</span>
                    </div>

                    {{-- Net Revenue --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 border-l-4 border-blue-500">
                        <span class="text-gray-700 font-semibold">{{ __('finance_report.net_revenue') }}</span>
                        <span class="font-bold text-blue-600">฿{{ number_format($pnl['net_revenue'], 0) }}</span>
                    </div>

                    {{-- COGS --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50">
                        <span class="text-gray-700 font-medium">(-) {{ __('finance_report.cogs') }}</span>
                        <span class="font-bold text-red-600">฿{{ number_format($pnl['cogs'], 0) }}</span>
                    </div>

                    {{-- Gross Profit --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-green-50 border-l-4 border-green-500">
                        <span class="text-gray-700 font-semibold">{{ __('finance_report.gross_profit') }}</span>
                        <span class="font-bold text-green-600">฿{{ number_format($pnl['gross_profit'], 0) }}</span>
                    </div>

                    {{-- Gross Margin --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-100">
                        <span class="text-gray-700 font-medium">{{ __('finance_report.gross_margin') }}</span>
                        <span class="font-bold text-gray-900">{{ number_format($pnl['gross_margin'], 1) }}%</span>
                    </div>
                </div>
            </div>

            {{-- Tax Report --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-receipt text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('finance_report.tax_report') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('finance_report.output_vat') }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    {{-- Taxable Sales 7% --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-purple-50">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                            <span class="text-gray-700 font-medium">{{ __('finance_report.taxable_sales') }}</span>
                        </div>
                        <span
                            class="font-bold text-purple-600">฿{{ number_format($taxReport['taxable_sales'], 0) }}</span>
                    </div>

                    {{-- VAT Amount --}}
                    <div
                        class="flex items-center justify-between p-3 rounded-xl bg-purple-100 border-l-4 border-purple-500">
                        <span class="text-gray-700 font-semibold">{{ __('finance_report.vat_amount') }} (7%)</span>
                        <span class="font-bold text-purple-700">฿{{ number_format($taxReport['vat_amount'], 2) }}</span>
                    </div>

                    {{-- Zero-rated --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                            <span class="text-gray-700 font-medium">{{ __('finance_report.zero_rated') }}</span>
                        </div>
                        <span
                            class="font-bold text-gray-600">฿{{ number_format($taxReport['zero_rated_sales'], 0) }}</span>
                    </div>

                    {{-- Exempt Sales --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                            <span class="text-gray-700 font-medium">{{ __('finance_report.exempt_sales') }}</span>
                        </div>
                        <span class="font-bold text-amber-600">฿{{ number_format($taxReport['exempt_sales'], 0) }}</span>
                    </div>

                    {{-- Taxable vs Exempt Bar --}}
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">
                            {{ __('finance_report.taxable_vs_exempt') }}</p>
                        <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden flex">
                            <div class="h-full bg-purple-500 transition-all"
                                style="width: {{ $taxReport['taxable_percentage'] }}%"></div>
                            <div class="h-full bg-amber-400 transition-all"
                                style="width: {{ $taxReport['exempt_percentage'] }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span>{{ __('finance_report.vat_7_percent') }}:
                                {{ number_format($taxReport['taxable_percentage'], 1) }}%</span>
                            <span>{{ __('finance_report.vat_exempt') }}:
                                {{ number_format($taxReport['exempt_percentage'], 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="card-ios p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                    <i class="ph-fill ph-wallet text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ __('finance_report.payment_methods') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('finance_report.for_bank_reconciliation') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse($paymentMethods as $method)
                    <div class="p-4 rounded-xl border-2"
                        style="border-color: {{ $method['color'] }}20; background: {{ $method['color'] }}10;">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                style="background: {{ $method['color'] }}20;">
                                <i class="ph-fill {{ $method['icon'] }} text-2xl"
                                    style="color: {{ $method['color'] }};"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $method['label'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($method['count']) }}
                                    {{ __('finance_report.transactions') }}</p>
                            </div>
                        </div>
                        <p class="text-2xl font-bold" style="color: {{ $method['color'] }};">
                            ฿{{ number_format($method['amount'], 0) }}
                        </p>
                        <div class="mt-2 w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full"
                                style="width: {{ $method['percentage'] }}%; background: {{ $method['color'] }};"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($method['percentage'], 1) }}%</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <i class="ph-fill ph-wallet text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-400">{{ __('finance_report.no_data') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Monthly Comparison --}}
        <div class="card-ios p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="ph-fill ph-calendar text-amber-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ __('finance_report.monthly_comparison') }}</h3>
                    <p class="text-xs text-gray-500">{{ $monthlyComparison['current_month']['name'] }} vs
                        {{ $monthlyComparison['previous_month']['name'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Current Month --}}
                <div class="p-5 rounded-xl bg-blue-50 border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="text-sm font-bold text-blue-600 uppercase">{{ __('finance_report.current_month') }}</span>
                        <span class="text-xs text-blue-500">{{ $monthlyComparison['current_month']['name'] }}</span>
                    </div>
                    <p class="text-3xl font-bold text-blue-700 mb-2">
                        ฿{{ number_format($monthlyComparison['current_month']['revenue'], 0) }}</p>
                    <p class="text-sm text-blue-600">
                        {{ number_format($monthlyComparison['current_month']['transactions']) }}
                        {{ __('finance_report.transactions') }}
                    </p>
                    @if ($monthlyComparison['revenue_growth'] != 0)
                        <div
                            class="mt-3 inline-flex items-center gap-1 px-2 py-1 rounded-lg {{ $monthlyComparison['revenue_growth'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i
                                class="ph {{ $monthlyComparison['revenue_growth'] > 0 ? 'ph-trend-up' : 'ph-trend-down' }}"></i>
                            <span
                                class="text-xs font-bold">{{ number_format(abs($monthlyComparison['revenue_growth']), 1) }}%</span>
                        </div>
                    @endif
                </div>

                {{-- Previous Month --}}
                <div class="p-5 rounded-xl bg-gray-50 border-2 border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="text-sm font-bold text-gray-500 uppercase">{{ __('finance_report.previous_month') }}</span>
                        <span class="text-xs text-gray-400">{{ $monthlyComparison['previous_month']['name'] }}</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-700 mb-2">
                        ฿{{ number_format($monthlyComparison['previous_month']['revenue'], 0) }}</p>
                    <p class="text-sm text-gray-500">
                        {{ number_format($monthlyComparison['previous_month']['transactions']) }}
                        {{ __('finance_report.transactions') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

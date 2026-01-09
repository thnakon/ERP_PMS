@extends('layouts.app')

@section('title', __('reports.loyal_customers_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('reports_analytics') }}
        </p>
        <span>{{ __('reports.loyal_customers_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <form action="{{ route('reports.loyal-customers') }}" method="GET" class="flex items-center gap-2">
        <input type="date" name="start_date" value="{{ $startDate }}"
            class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
        <span class="text-gray-400">→</span>
        <input type="date" name="end_date" value="{{ $endDate }}"
            class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
        <select name="min_visits" class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
            <option value="2" {{ $minVisits == 2 ? 'selected' : '' }}>{{ __('reports.min_2_visits') }}</option>
            <option value="3" {{ $minVisits == 3 ? 'selected' : '' }}>{{ __('reports.min_3_visits') }}</option>
            <option value="5" {{ $minVisits == 5 ? 'selected' : '' }}>{{ __('reports.min_5_visits') }}</option>
            <option value="10" {{ $minVisits == 10 ? 'selected' : '' }}>{{ __('reports.min_10_visits') }}</option>
        </select>
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
            {{-- Total Customers --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('reports.total_customers') }}</span>
                    <i class="ph-fill ph-users text-2xl text-blue-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_customers']) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('reports.in_system') }}</p>
            </div>

            {{-- Active Customers --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('reports.active_customers') }}</span>
                    <i class="ph-fill ph-user-check text-2xl text-green-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['active_customers']) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('reports.in_period') }}</p>
            </div>

            {{-- Loyal Customers --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #8B5CF6, #7C3AED);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.loyal_customers') }}</span>
                    <i class="ph-fill ph-heart text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">{{ number_format($summary['loyal_customers']) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ number_format($summary['loyalty_rate'], 1) }}% {{ __('reports.of_active') }}</p>
            </div>

            {{-- Loyal Revenue --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #22C55E, #16A34A);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.loyal_revenue') }}</span>
                    <i class="ph-fill ph-currency-circle-dollar text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">฿{{ number_format($summary['total_revenue_loyal'], 0) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('reports.avg') }}
                    ฿{{ number_format($summary['avg_lifetime_value'], 0) }}/{{ __('reports.person') }}</p>
            </div>
        </div>

        {{-- Customer Tiers --}}
        <div class="card-ios p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <i class="ph-fill ph-crown text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ __('reports.customer_tiers') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('reports.based_on_spending') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- VIP --}}
                <div class="p-4 rounded-2xl bg-gradient-to-br from-purple-50 to-violet-100 border border-purple-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-crown text-purple-600"></i>
                        <span class="font-semibold text-purple-700">VIP</span>
                    </div>
                    <p class="text-3xl font-bold text-purple-900">{{ $tiers['vip'] }}</p>
                    <p class="text-xs text-purple-500 mt-1">฿10,000+</p>
                </div>
                {{-- Gold --}}
                <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-50 to-yellow-100 border border-amber-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-medal text-amber-600"></i>
                        <span class="font-semibold text-amber-700">{{ __('reports.gold') }}</span>
                    </div>
                    <p class="text-3xl font-bold text-amber-900">{{ $tiers['gold'] }}</p>
                    <p class="text-xs text-amber-500 mt-1">฿5,000 - ฿9,999</p>
                </div>
                {{-- Silver --}}
                <div class="p-4 rounded-2xl bg-gradient-to-br from-gray-50 to-slate-100 border border-gray-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-medal text-gray-500"></i>
                        <span class="font-semibold text-gray-600">{{ __('reports.silver') }}</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $tiers['silver'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">฿2,000 - ฿4,999</p>
                </div>
                {{-- Bronze --}}
                <div class="p-4 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-100 border border-orange-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-fill ph-star text-orange-500"></i>
                        <span class="font-semibold text-orange-600">{{ __('reports.bronze') }}</span>
                    </div>
                    <p class="text-3xl font-bold text-orange-800">{{ $tiers['bronze'] }}</p>
                    <p class="text-xs text-orange-500 mt-1">
                        < ฿2,000</p>
                </div>
            </div>
        </div>

        {{-- Two Column Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- At Risk Customers --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="ph-fill ph-warning text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('reports.at_risk_customers') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('reports.not_visited_30_days') }}</p>
                    </div>
                </div>
                <div class="space-y-2 max-h-80 overflow-y-auto custom-scroll">
                    @forelse($atRiskCustomers as $customer)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50">
                            <div
                                class="w-10 h-10 rounded-full bg-red-200 flex items-center justify-center text-red-600 font-bold text-sm">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-red-600">{{ $customer->days_since_last }}
                                    {{ __('days') }}</p>
                                <p class="text-xs text-gray-400">฿{{ number_format($customer->total_spent, 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ph-fill ph-check-circle text-4xl text-green-500 mb-2"></i>
                            <p class="text-gray-500">{{ __('reports.no_at_risk') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Monthly Visits Chart --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-chart-bar text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('reports.monthly_visits') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('reports.customer_activity') }}</p>
                    </div>
                </div>
                @php
                    $maxVisits = $monthlyVisits->max('total_visits') ?: 1;
                @endphp
                <div class="flex items-end gap-2" style="height: 200px;">
                    @foreach ($monthlyVisits as $month)
                        @php
                            $barHeight = ($month->total_visits / $maxVisits) * 100;
                            $barHeight = max($barHeight, 5);
                        @endphp
                        <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                            <div class="w-full rounded-t-lg transition-all cursor-pointer bg-blue-500 hover:bg-blue-600"
                                style="height: {{ $barHeight }}%; min-height: 8px;">
                            </div>
                            <span class="text-[9px] text-gray-400 mt-2">
                                {{ \Carbon\Carbon::parse($month->month)->format('M') }}
                            </span>
                            {{-- Tooltip --}}
                            <div
                                class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                {{ $month->unique_customers }} {{ __('reports.customers') }} •
                                {{ $month->total_visits }} {{ __('reports.visits') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Loyal Customers Table --}}
        <div class="card-ios p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-users-three text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('reports.loyal_customers_list') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('reports.top_returning_customers') }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                    {{ $loyalCustomers->count() }} {{ __('reports.customers') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 font-semibold">#</th>
                            <th class="px-4 py-3 font-semibold">{{ __('customer') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('reports.phone') }}</th>
                            <th class="px-4 py-3 font-semibold text-center">{{ __('reports.visits') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.total_spent') }}</th>
                            <th class="px-4 py-3 font-semibold text-right">{{ __('reports.avg_order') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('reports.favorite_category') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('reports.last_visit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loyalCustomers->take(50) as $index => $customer)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                            @if ($customer->member_type)
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full 
                                                    {{ $customer->member_type === 'vip' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                                    {{ ucfirst($customer->member_type) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold text-xs">
                                        {{ $customer->visit_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">
                                    ฿{{ number_format($customer->total_spent, 0) }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">
                                    ฿{{ number_format($customer->avg_order, 0) }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $customer->favorite_category }}</td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-gray-900">
                                            {{ \Carbon\Carbon::parse($customer->last_visit)->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $customer->days_since_last }}
                                            {{ __('days') }} {{ __('reports.ago') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($loyalCustomers->count() > 50)
                <p class="text-center text-gray-400 text-sm mt-4">{{ __('reports.showing_top_50') }}</p>
            @endif
        </div>
    </div>
@endsection

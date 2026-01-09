@extends('layouts.app')

@section('title', __('reports.expiring_products_title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('reports_analytics') }}
        </p>
        <span>{{ __('reports.expiring_products_title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-3">
        <form action="{{ route('reports.expiring-products') }}" method="GET" class="flex items-center gap-2">
            <select name="months" class="px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium bg-white">
                <option value="1" {{ $months == 1 ? 'selected' : '' }}>{{ __('reports.next_month') }}</option>
                <option value="3" {{ $months == 3 ? 'selected' : '' }}>{{ __('reports.next_3_months') }}</option>
                <option value="6" {{ $months == 6 ? 'selected' : '' }}>{{ __('reports.next_6_months') }}</option>
                <option value="12" {{ $months == 12 ? 'selected' : '' }}>{{ __('reports.next_12_months') }}</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-ios-blue text-white font-semibold rounded-xl hover:bg-blue-600 transition flex items-center gap-2">
                <i class="ph ph-funnel"></i>
                {{ __('filter') }}
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Total Expiring --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #F59E0B, #D97706);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.expiring_soon') }}</span>
                    <i class="ph-fill ph-clock-countdown text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">{{ number_format($summary['total_products']) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">{{ __('reports.products') }}</p>
            </div>

            {{-- Total Units --}}
            <div class="card-ios p-5 text-white" style="background: linear-gradient(to bottom right, #EF4444, #DC2626);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide"
                        style="color: rgba(255,255,255,0.8);">{{ __('reports.total_units') }}</span>
                    <i class="ph-fill ph-cube text-2xl" style="color: rgba(255,255,255,0.7);"></i>
                </div>
                <p class="text-2xl font-bold">{{ number_format($summary['total_units']) }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.8);">{{ __('reports.units_at_risk') }}</p>
            </div>

            {{-- Total Value --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('reports.value_at_risk') }}</span>
                    <i class="ph-fill ph-currency-circle-dollar text-2xl text-amber-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">฿{{ number_format($summary['total_value'], 0) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('reports.must_sell_soon') }}</p>
            </div>

            {{-- Already Expired --}}
            <div class="card-ios p-5">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ __('reports.already_expired') }}</span>
                    <i class="ph-fill ph-x-circle text-2xl text-red-500"></i>
                </div>
                <p class="text-2xl font-bold text-red-600">{{ number_format($summary['expired_count']) }}</p>
                <p class="text-xs mt-2 text-gray-500">{{ __('reports.need_attention') }}</p>
            </div>
        </div>

        {{-- Already Expired Products --}}
        @if ($expiredProducts->count() > 0)
            <div class="card-ios p-6 border-l-4 border-red-500">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="ph-fill ph-warning text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-700">{{ __('reports.already_expired') }}</h3>
                        <p class="text-xs text-red-500">{{ __('reports.expired_products_desc') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($expiredProducts as $product)
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-900 truncate">{{ $product->name }}</span>
                                <span class="text-xs font-mono text-gray-500">{{ $product->sku }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-red-600">
                                    <i class="ph ph-calendar-x"></i>
                                    {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}
                                </span>
                                <span class="font-bold text-gray-700">{{ $product->stock_qty }}
                                    {{ __('reports.units') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Expiring by Month --}}
        @forelse($groupedByMonth as $monthData)
            <div class="card-ios p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl {{ $loop->first ? 'bg-amber-100' : 'bg-orange-100' }} flex items-center justify-center">
                            <i
                                class="ph-fill ph-calendar {{ $loop->first ? 'text-amber-600' : 'text-orange-600' }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $monthData['month_name'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $monthData['total_items'] }} {{ __('reports.products') }}
                                •
                                {{ number_format($monthData['total_units']) }} {{ __('reports.units') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-600">฿{{ number_format($monthData['total_value'], 0) }}</p>
                        <p class="text-xs text-gray-500">{{ __('reports.value') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-100">
                                <th class="pb-3 font-semibold">{{ __('product') }}</th>
                                <th class="pb-3 font-semibold">SKU</th>
                                <th class="pb-3 font-semibold">{{ __('reports.expiry_date') }}</th>
                                <th class="pb-3 font-semibold text-right">{{ __('reports.stock') }}</th>
                                <th class="pb-3 font-semibold text-right">{{ __('reports.value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthData['products'] as $product)
                                @php
                                    $daysUntil = \Carbon\Carbon::now()->diffInDays(
                                        \Carbon\Carbon::parse($product->expiry_date),
                                        false,
                                    );
                                    $urgencyClass =
                                        $daysUntil <= 7
                                            ? 'text-red-600 bg-red-50'
                                            : ($daysUntil <= 30
                                                ? 'text-amber-600 bg-amber-50'
                                                : 'text-gray-600 bg-gray-50');
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-gray-50">
                                    <td class="py-3">
                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        @if ($product->name_th)
                                            <p class="text-xs text-gray-400">{{ $product->name_th }}</p>
                                        @endif
                                    </td>
                                    <td class="py-3 font-mono text-gray-500">{{ $product->sku }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $urgencyClass }}">
                                            {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}
                                            <span class="ml-1">({{ $daysUntil }} {{ __('days') }})</span>
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-semibold">{{ number_format($product->stock_qty) }}</td>
                                    <td class="py-3 text-right font-semibold text-gray-700">
                                        ฿{{ number_format($product->stock_qty * $product->cost_price, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="card-ios p-12 text-center">
                <i class="ph-fill ph-check-circle text-6xl text-green-500 mb-4"></i>
                <h3 class="font-bold text-gray-900 text-xl mb-2">{{ __('reports.no_expiring_products') }}</h3>
                <p class="text-gray-500">{{ __('reports.no_expiring_products_desc') }}</p>
            </div>
        @endforelse
    </div>
@endsection

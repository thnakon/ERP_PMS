@extends('layouts.app')

@section('title', __('sidebar.dashboard'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('auth.welcome_back') }}, {{ auth()->user()->name }}!
        </p>
        <span>{{ __('sidebar.dashboard') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-3">
        <div
            class="px-5 py-3 bg-white/80 backdrop-blur-md border border-gray-100 rounded-2xl shadow-sm text-base font-semibold text-gray-700 flex items-center gap-3">
            <i class="ph-fill ph-calendar-blank text-ios-blue text-xl"></i>
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8 pb-10">
        {{-- KPI Cards Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Today's Revenue (Admin only) OR My Sales (Staff) --}}
            @if ($isAdmin)
                <div class="card-ios relative overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                    <div
                        class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center">
                                <i class="ph-fill ph-currency-circle-dollar text-green-600 text-2xl"></i>
                            </div>
                            <span
                                class="text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">{{ __('dashboard.today') }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.today_revenue') }}</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                            ฿{{ number_format($stats['today_revenue'], 2) }}</h3>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-sm">
                            <span class="text-gray-400">{{ __('dashboard.this_month') }}</span>
                            <span class="font-bold text-gray-600">฿{{ number_format($stats['month_revenue'], 0) }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Staff: My Sales Today --}}
                <div class="card-ios relative overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                    <div
                        class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center">
                                <i class="ph-fill ph-user-circle text-indigo-600 text-2xl"></i>
                            </div>
                            <span
                                class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ __('dashboard.my_sales') }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.my_orders_today') }}</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                            {{ number_format($stats['my_today_orders']) }}</h3>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-sm">
                            <span class="text-gray-400">{{ __('dashboard.my_revenue') }}</span>
                            <span
                                class="font-bold text-indigo-600">฿{{ number_format($stats['my_today_revenue'], 0) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Today's Orders --}}
            <div class="card-ios relative overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                            <i class="ph-fill ph-receipt text-blue-600 text-2xl"></i>
                        </div>
                        <span
                            class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">{{ $stats['today_completed'] }}
                            {{ __('dashboard.completed') }}</span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.orders') }}</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                        {{ number_format($stats['today_orders']) }}</h3>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-sm">
                        <span class="text-gray-400">{{ __('dashboard.avg_ticket') }}</span>
                        <span class="font-bold text-gray-600">฿{{ number_format($stats['avg_ticket'], 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- Low Stock Alert --}}
            <div
                class="card-ios relative overflow-hidden group hover:shadow-xl transition-shadow duration-300 {{ $stats['low_stock'] > 0 ? 'border-orange-200 bg-gradient-to-br from-white to-orange-50' : '' }}">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl {{ $stats['low_stock'] > 0 ? 'bg-orange-500' : 'bg-orange-100' }} flex items-center justify-center">
                            <i
                                class="ph-fill ph-warning {{ $stats['low_stock'] > 0 ? 'text-white' : 'text-orange-600' }} text-2xl"></i>
                        </div>
                        @if ($stats['low_stock'] > 0)
                            <span
                                class="text-xs font-bold text-white bg-orange-500 px-2.5 py-1 rounded-full animate-pulse">{{ __('dashboard.action_required') }}</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.low_stock') }}</p>
                    <h3
                        class="text-3xl font-black {{ $stats['low_stock'] > 0 ? 'text-orange-600' : 'text-gray-900' }} tracking-tight">
                        {{ number_format($stats['low_stock']) }}</h3>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('products.index') }}?filter=low_stock"
                            class="text-sm font-semibold text-ios-blue hover:underline flex items-center gap-1">
                            {{ __('dashboard.view_products') }} <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Expiring Alert --}}
            <div
                class="card-ios relative overflow-hidden group hover:shadow-xl transition-shadow duration-300 {{ $stats['expiring_critical'] + $stats['expired'] > 0 ? 'border-red-200 bg-gradient-to-br from-white to-red-50' : '' }}">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl {{ $stats['expiring_critical'] + $stats['expired'] > 0 ? 'bg-red-500' : 'bg-red-100' }} flex items-center justify-center">
                            <i
                                class="ph-fill ph-calendar-x {{ $stats['expiring_critical'] + $stats['expired'] > 0 ? 'text-white' : 'text-red-600' }} text-2xl"></i>
                        </div>
                        @if ($stats['expired'] > 0)
                            <span
                                class="text-xs font-bold text-white bg-red-600 px-2.5 py-1 rounded-full animate-pulse">{{ $stats['expired'] }}
                                {{ __('dashboard.expired') }}</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.expiring_soon') }}</p>
                    <h3
                        class="text-3xl font-black {{ $stats['expiring_critical'] + $stats['expired'] > 0 ? 'text-red-600' : 'text-gray-900' }} tracking-tight">
                        {{ number_format($stats['expiring_critical'] + $stats['expiring_warning']) }}</h3>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('expiry.index') }}"
                            class="text-sm font-semibold text-ios-blue hover:underline flex items-center gap-1">
                            {{ __('dashboard.view_expiry') }} <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Sales Chart --}}
            @if (auth()->user()->isAdmin())
                <div class="lg:col-span-2 card-ios">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('dashboard.weekly_sales') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('dashboard.last_7_days') }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="w-3 h-3 rounded-full bg-ios-blue"></span>
                            <span class="text-gray-500">{{ __('dashboard.revenue') }}</span>
                        </div>
                    </div>
                    <div class="flex items-end justify-between gap-3 px-2 mt-8" style="height: 300px;">
                        @php
                            $maxValue = collect($stats['chart_data'])->max('value') ?: 1;
                            $maxHeight = 160; // max bar height in pixels
                        @endphp
                        @foreach ($stats['chart_data'] as $index => $day)
                            @php
                                $barHeight = $maxValue > 0 ? max(20, ($day['value'] / $maxValue) * $maxHeight) : 20;
                                $isToday = $index === count($stats['chart_data']) - 1;
                            @endphp
                            <div class="flex-1 flex flex-col items-center justify-end h-full">
                                <div class="w-full {{ $isToday ? 'bg-ios-blue shadow-lg shadow-blue-500/30' : 'bg-gray-200 hover:bg-blue-300' }} 
                                rounded-xl cursor-pointer transition-all duration-300 relative group mb-2"
                                    style="height: {{ $barHeight }}px;">
                                    <div
                                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs py-1.5 px-3 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-10">
                                        ฿{{ number_format($day['value'], 0) }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <span
                                        class="text-xs font-bold {{ $isToday ? 'text-ios-blue' : 'text-gray-400' }}">{{ $day['label'] }}</span>
                                    <span class="block text-[10px] text-gray-300">{{ $day['date'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quick Actions (Role-based) --}}
            <div class="card-ios {{ !$isAdmin ? 'lg:col-span-3' : '' }}">
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('dashboard.quick_actions') }}</h3>
                <div class="{{ !$isAdmin ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3' : 'space-y-3' }}">
                    {{-- POS - Everyone --}}
                    <a href="{{ route('pos.index') }}"
                        class="flex items-center gap-4 p-4 bg-gradient-to-r from-ios-blue/5 to-blue-50 hover:from-ios-blue/10 hover:to-blue-100 rounded-2xl transition-all duration-300 group">
                        <div
                            class="w-12 h-12 bg-ios-blue text-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/20">
                            <i class="ph-fill ph-cash-register text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-gray-900">{{ __('dashboard.new_sale') }}</div>
                            <div class="text-sm text-gray-500">{{ __('dashboard.open_pos') }}</div>
                        </div>
                        <i
                            class="ph ph-caret-right text-gray-300 group-hover:text-ios-blue group-hover:translate-x-1 transition-all"></i>
                    </a>

                    @if ($isAdmin)
                        {{-- Admin: Add Product --}}
                        <a href="{{ route('products.create') }}"
                            class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-all duration-300 group">
                            <div
                                class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-fill ph-pill text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900">{{ __('dashboard.add_product') }}</div>
                                <div class="text-sm text-gray-500">{{ $stats['total_products'] }}
                                    {{ __('dashboard.products_active') }}</div>
                            </div>
                            <i
                                class="ph ph-caret-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    @else
                        {{-- Staff: Barcode Scanner --}}
                        <a href="{{ route('barcode.index') }}"
                            class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-all duration-300 group">
                            <div
                                class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-fill ph-barcode text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900">{{ __('barcode.title') }}</div>
                                <div class="text-sm text-gray-500">{{ __('dashboard.scan_lookup') }}</div>
                            </div>
                            <i
                                class="ph ph-caret-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    @endif

                    {{-- Add Customer - Everyone --}}
                    <a href="{{ route('customers.create') }}"
                        class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-all duration-300 group">
                        <div
                            class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-user-plus text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-gray-900">{{ __('dashboard.add_customer') }}</div>
                            <div class="text-sm text-gray-500">{{ $stats['total_customers'] }}
                                {{ __('dashboard.registered') }}</div>
                        </div>
                        <i
                            class="ph ph-caret-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    @if ($isAdmin)
                        {{-- Admin: Create PO --}}
                        <a href="{{ route('purchase-orders.create') }}"
                            class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-all duration-300 group">
                            <div
                                class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-fill ph-shopping-cart text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900">{{ __('dashboard.create_po') }}</div>
                                <div class="text-sm text-gray-500">{{ $stats['pending_pos'] }}
                                    {{ __('dashboard.pending') }}
                                </div>
                            </div>
                            <i
                                class="ph ph-caret-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    @else
                        {{-- Staff: Shift Notes --}}
                        <a href="{{ route('shift-notes.index') }}"
                            class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-all duration-300 group">
                            <div
                                class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-fill ph-note-pencil text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900">{{ __('shift_notes.title') }}</div>
                                <div class="text-sm text-gray-500">{{ __('dashboard.view_notes') }}</div>
                            </div>
                            <i
                                class="ph ph-caret-right text-gray-300 group-hover:text-gray-600 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Staff: Today's Tasks Panel --}}
        @if (!$isAdmin && $todaysTasks->count() > 0)
            <div class="card-ios border-l-4 border-ios-blue">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-ios-blue/10 flex items-center justify-center">
                        <i class="ph-fill ph-clipboard-text text-ios-blue text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('dashboard.todays_tasks') }}</h3>
                        <p class="text-xs text-gray-400">{{ __('dashboard.items_need_attention') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach ($todaysTasks as $task)
                        <a href="{{ $task['link'] }}"
                            class="flex items-center gap-3 p-4 bg-{{ $task['color'] }}-50 hover:bg-{{ $task['color'] }}-100 rounded-xl transition-colors group">
                            <div
                                class="w-10 h-10 rounded-xl bg-{{ $task['color'] }}-100 flex items-center justify-center">
                                <i class="ph-fill {{ $task['icon'] }} text-{{ $task['color'] }}-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">{{ $task['title'] }}</p>
                                <p class="text-2xl font-black text-{{ $task['color'] }}-600">{{ $task['count'] }}</p>
                            </div>
                            <i
                                class="ph ph-arrow-right text-{{ $task['color'] }}-400 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Second Row: Alerts and Activities --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Low Stock Products --}}
            <div class="card-ios">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                            <i class="ph-fill ph-warning text-orange-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ __('dashboard.low_stock_products') }}</h3>
                            <p class="text-xs text-gray-400">{{ __('dashboard.needs_restock') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}?filter=low_stock"
                        class="text-sm font-semibold text-ios-blue hover:underline">{{ __('dashboard.view_all') }}</a>
                </div>
                @if ($lowStockProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach ($lowStockProducts as $product)
                            <div
                                class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-orange-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center overflow-hidden">
                                    @if ($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="ph ph-pill text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-orange-600">{{ $product->stock_qty }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase">{{ __('dashboard.in_stock') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="ph ph-check-circle text-4xl text-green-300 mb-2"></i>
                        <p class="font-medium">{{ __('dashboard.all_stock_ok') }}</p>
                    </div>
                @endif
            </div>

            {{-- Expiring Products --}}
            <div class="card-ios">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                            <i class="ph-fill ph-calendar-x text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ __('dashboard.expiring_products') }}</h3>
                            <p class="text-xs text-gray-400">{{ __('dashboard.within_30_days') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('expiry.index') }}"
                        class="text-sm font-semibold text-ios-blue hover:underline">{{ __('dashboard.view_all') }}</a>
                </div>
                @if ($expiringProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach ($expiringProducts as $lot)
                            @php
                                $daysLeft = now()->diffInDays($lot->expiry_date, false);
                                $urgencyColor =
                                    $daysLeft <= 7 ? 'text-red-600 bg-red-50' : 'text-orange-600 bg-orange-50';
                            @endphp
                            <div
                                class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-red-50 transition-colors">
                                <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center">
                                    <i class="ph ph-clock text-gray-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $lot->product->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ __('dashboard.lot') }}: {{ $lot->lot_number }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-sm font-bold {{ $daysLeft <= 7 ? 'text-red-600' : 'text-orange-600' }}">
                                        {{ $daysLeft > 0 ? $daysLeft . ' ' . __('dashboard.days') : __('dashboard.expired') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">{{ $lot->expiry_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="ph ph-check-circle text-4xl text-green-300 mb-2"></i>
                        <p class="font-medium">{{ __('dashboard.no_expiring') }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Third Row: Recent Orders and Activities --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Orders --}}
            <div class="card-ios">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="ph-fill ph-receipt text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ __('dashboard.recent_orders') }}</h3>
                            <p class="text-xs text-gray-400">{{ __('dashboard.latest_transactions') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm font-semibold text-ios-blue hover:underline">{{ __('dashboard.view_all') }}</a>
                </div>
                @if ($recentOrders->count() > 0)
                    <div class="space-y-3">
                        @foreach ($recentOrders as $order)
                            <a href="{{ route('orders.show', $order) }}"
                                class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-lg {{ $order->status === 'completed' ? 'bg-green-100' : ($order->status === 'refunded' ? 'bg-red-100' : 'bg-yellow-100') }} flex items-center justify-center">
                                    <i
                                        class="ph-fill {{ $order->status === 'completed' ? 'ph-check text-green-600' : ($order->status === 'refunded' ? 'ph-arrow-counter-clockwise text-red-600' : 'ph-clock text-yellow-600') }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $order->customer->name ?? __('dashboard.walk_in') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">฿{{ number_format($order->total_amount, 0) }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="ph ph-receipt text-4xl mb-2"></i>
                        <p class="font-medium">{{ __('dashboard.no_orders_today') }}</p>
                    </div>
                @endif
            </div>

            {{-- Recent Activity (Admin) / Shift Notes (Staff) --}}
            @if ($isAdmin)
                <div class="card-ios">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                <i class="ph-fill ph-clock-counter-clockwise text-purple-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('dashboard.recent_activity') }}</h3>
                                <p class="text-xs text-gray-400">{{ __('dashboard.system_events') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('activity-logs.index') }}"
                            class="text-sm font-semibold text-ios-blue hover:underline">{{ __('dashboard.view_all') }}</a>
                    </div>
                    @if ($recentActivities->count() > 0)
                        <div class="space-y-2 max-h-80 overflow-y-auto custom-scroll">
                            @foreach ($recentActivities as $activity)
                                <div class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        @if ($activity->user && $activity->user->avatar)
                                            <img src="{{ asset('storage/' . $activity->user->avatar) }}"
                                                class="w-full h-full rounded-lg object-cover">
                                        @else
                                            <span
                                                class="text-xs font-bold text-gray-500">{{ strtoupper(substr($activity->user->name ?? 'S', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm text-gray-600">
                                            <span
                                                class="font-semibold text-gray-900">{{ $activity->user->name ?? 'System' }}</span>
                                            {{ __('activity.' . $activity->action) }}
                                            <span class="font-medium text-gray-700">{{ $activity->entity_type }}</span>
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}
                                            </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="ph ph-clock text-4xl mb-2"></i>
                            <p class="font-medium">{{ __('dashboard.no_activity') }}</p>
                        </div>
                    @endif
                </div>
            @else
                {{-- Staff: Shift Notes --}}
                <div class="card-ios">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                <i class="ph-fill ph-note-pencil text-indigo-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('shift_notes.title') }}</h3>
                                <p class="text-xs text-gray-400">{{ __('dashboard.team_notes') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('shift-notes.index') }}"
                            class="text-sm font-semibold text-ios-blue hover:underline">{{ __('dashboard.view_all') }}</a>
                    </div>
                    @if ($recentShiftNotes->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentShiftNotes as $note)
                                <div
                                    class="p-4 bg-gray-50 rounded-xl {{ $note->is_pinned ? 'border-l-4 border-indigo-500' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            @if ($note->is_pinned)
                                                <i class="ph-fill ph-push-pin text-indigo-600"></i>
                                            @else
                                                <span
                                                    class="text-xs font-bold text-indigo-600">{{ strtoupper(substr($note->user->name ?? 'S', 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span
                                                    class="font-semibold text-gray-900 text-sm">{{ $note->user->name ?? 'Unknown' }}</span>
                                                <span
                                                    class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $note->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="ph ph-note text-4xl mb-2"></i>
                            <p class="font-medium">{{ __('dashboard.no_notes') }}</p>
                            <a href="{{ route('shift-notes.create') }}"
                                class="text-ios-blue font-semibold text-sm mt-2 inline-block hover:underline">
                                <i class="ph ph-plus"></i> {{ __('dashboard.create_note') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Top Products & Shift Status --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Top Selling Products --}}
            @if (auth()->user()->isAdmin())
                <div class="lg:col-span-2 card-ios">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                                <i class="ph-fill ph-trend-up text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ __('dashboard.top_products') }}</h3>
                                <p class="text-xs text-gray-400">{{ __('dashboard.this_week') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('reports.sales') }}"
                            class="text-sm font-semibold text-ios-blue hover:underline">{{ __('dashboard.see_report') }}</a>
                    </div>
                    @if ($topProducts->count() > 0)
                        <div class="space-y-3">
                            @foreach ($topProducts as $index => $product)
                                <div
                                    class="flex items-center gap-4 p-3 {{ $index === 0 ? 'bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-100' : 'bg-gray-50' }} rounded-xl">
                                    <div
                                        class="w-8 h-8 rounded-lg {{ $index === 0 ? 'bg-yellow-400 text-white' : ($index === 1 ? 'bg-gray-300 text-white' : ($index === 2 ? 'bg-orange-400 text-white' : 'bg-gray-100 text-gray-500')) }} flex items-center justify-center font-black text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div
                                        class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center overflow-hidden">
                                        @if ($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <i class="ph ph-pill text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-black text-gray-900">{{ $product->sold_qty ?? 0 }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase">{{ __('dashboard.sold') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="ph ph-chart-bar text-4xl mb-2"></i>
                            <p class="font-medium">{{ __('dashboard.no_sales_data') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Current Shift + Team --}}
            <div class="card-ios {{ !auth()->user()->isAdmin() ? 'lg:col-span-3' : '' }}">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="ph-fill ph-user-circle text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('dashboard.current_session') }}</h3>
                        <p class="text-xs text-gray-400">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                {{-- Current User --}}
                <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-lg">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                            @if ($currentShift)
                                <p class="text-xs text-indigo-600 font-medium mt-1">
                                    <i class="ph-fill ph-check-circle"></i> {{ __('dashboard.shift_active') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pharmacist on Duty --}}
                @if ($pharmacistOnDuty)
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                            {{ __('dashboard.pharmacist_on_duty') }}</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden">
                                @if ($pharmacistOnDuty->avatar)
                                    <img src="{{ asset('storage/' . $pharmacistOnDuty->avatar) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-green-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($pharmacistOnDuty->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $pharmacistOnDuty->name }}</p>
                                <p class="text-xs text-green-600">
                                    <i class="ph-fill ph-circle text-[8px]"></i> {{ __('dashboard.on_duty') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div class="p-3 bg-gray-50 rounded-xl text-center">
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-gray-400">{{ __('dashboard.active_staff') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl text-center">
                        <p class="text-2xl font-black text-gray-900">{{ $stats['today_customers'] }}</p>
                        <p class="text-xs text-gray-400">{{ __('dashboard.new_today') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

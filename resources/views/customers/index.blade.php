@extends('layouts.app')

@section('title', __('customers.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('people_logs') }}
        </p>
        <span>{{ __('customers.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('customers.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('customers.add_customer') }}
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            {{-- Total Customers --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-users text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('customers.total_customers') }}</span>
                </div>
                <h3 class="text-xl font-black text-blue-600">{{ number_format($stats['total']) }}</h3>
            </div>

            {{-- Active Customers --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-green-500 uppercase tracking-wider">{{ __('customers.active_customers') }}</span>
                </div>
                <h3 class="text-xl font-black text-green-600">{{ number_format($stats['active']) }}</h3>
            </div>

            {{-- With Allergies --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="ph-bold ph-warning text-red-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ __('customers.customers_with_allergies') }}</span>
                </div>
                <h3 class="text-xl font-black text-red-600">{{ number_format($stats['with_allergies']) }}</h3>
            </div>

            {{-- Platinum Members --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="ph-bold ph-crown text-purple-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">{{ __('customers.platinum_members') }}</span>
                </div>
                <h3 class="text-xl font-black text-purple-600">{{ number_format($stats['platinum']) }}</h3>
            </div>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Search + Quick Nav --}}
            <div class="flex items-center gap-2">
                <form action="{{ route('customers.index') }}" method="GET" class="flex-1 max-w-sm relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('customers.search_placeholder') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                    <button type="submit"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-ios-blue transition-colors flex items-center">
                        <i class="ph ph-arrow-right text-xl"></i>
                    </button>
                    <input type="hidden" name="tier" value="{{ request('tier') }}">
                </form>
                {{-- Quick Navigation Buttons --}}
                <div class="flex items-center gap-1">
                    <a href="{{ route('customers.index', ['sort' => 'created_at', 'dir' => 'asc']) }}"
                        class="quick-nav-btn" title="{{ __('first_item') }}">
                        <i class="ph ph-caret-double-left"></i>
                    </a>
                    <a href="{{ route('customers.index', ['sort' => 'created_at', 'dir' => 'desc']) }}"
                        class="quick-nav-btn" title="{{ __('latest_item') }}">
                        <i class="ph ph-caret-double-right"></i>
                    </a>
                </div>
            </div>

            {{-- Right: Sort Filter --}}
            <div class="flex items-center gap-2">
                <select name="tier"
                    onchange="window.location.href='{{ route('customers.index') }}?search={{ request('search') }}&tier=' + this.value"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('customers.all_tiers') }}</option>
                    <option value="regular" {{ request('tier') === 'regular' ? 'selected' : '' }}>
                        {{ __('customers.regular') }}</option>
                    <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>
                        {{ __('customers.silver') }}</option>
                    <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>{{ __('customers.gold') }}
                    </option>
                    <option value="platinum" {{ request('tier') === 'platinum' ? 'selected' : '' }}>
                        {{ __('customers.platinum') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div id="selection-header" class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all-customers" class="checkbox-ios"
                    onclick="toggleSelectAll(this, '.customer-checkbox')">
                <label for="select-all-customers" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $customers->total() }}</span>
                {{ __('customers.total_customers') }}
            </div>
        </div>

        {{-- Customer List --}}
        <div id="customer-list-container">
            <div class="stack-container view-list" id="customers-stack">
                @forelse ($customers as $customer)
                    <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                        id="customer-list-{{ $customer->id }}" data-customer-id="{{ $customer->id }}"
                        onclick="window.location.href='{{ route('customers.show', $customer) }}'">
                        {{-- Checkbox --}}
                        <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                            <input type="checkbox" value="{{ $customer->id }}" onchange="updateBulkBar(this)"
                                class="row-checkbox checkbox-ios customer-checkbox">
                        </div>

                        {{-- Customer Avatar --}}
                        @if ($customer->gender === 'male')
                            <div class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4"
                                style="background-color: #e3f2fd;">
                                <i class="ph-fill ph-user text-2xl" style="color: #007aff;"></i>
                            </div>
                        @elseif ($customer->gender === 'female')
                            <div class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4"
                                style="background-color: #fce4ec;">
                                <i class="ph-fill ph-user text-2xl" style="color: #ff2d55;"></i>
                            </div>
                        @else
                            <div
                                class="w-14 h-14 rounded-2xl bg-gray-100 flex-shrink-0 flex items-center justify-center mr-4">
                                <i class="ph-fill ph-user text-2xl text-gray-400"></i>
                            </div>
                        @endif

                        {{-- Main Info --}}
                        <div class="stack-col stack-main">
                            <span class="stack-label">{{ __('customers.customer') }}</span>
                            <div class="stack-value text-lg leading-tight">{{ $customer->name }}</div>
                            <div class="text-xs text-gray-400 font-medium mt-0.5">
                                @if ($customer->nickname)
                                    {{ $customer->nickname }} •
                                @endif
                                {{ $customer->phone }}
                            </div>
                        </div>

                        {{-- Age --}}
                        <div class="stack-col stack-data hidden lg:flex">
                            <span class="stack-label">{{ __('customers.age') }}</span>
                            <span class="stack-value text-sm">
                                @if ($customer->age)
                                    {{ $customer->age }} {{ __('customers.years_old') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        {{-- Gender --}}
                        <div class="stack-col stack-data hidden xl:flex">
                            <span class="stack-label">{{ __('customers.gender') }}</span>
                            <span class="stack-value text-sm">
                                @if ($customer->gender)
                                    <div class="flex items-center gap-1.5">
                                        @if ($customer->gender === 'male')
                                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center">
                                                <i class="ph-fill ph-gender-male text-xs" style="color: #007aff;"></i>
                                            </div>
                                            <span class="font-bold"
                                                style="font-size: 13px; color: #007aff;">{{ __('customers.' . $customer->gender) }}</span>
                                        @else
                                            <div class="w-5 h-5 rounded-full bg-pink-50 flex items-center justify-center">
                                                <i class="ph-fill ph-gender-female text-xs" style="color: #ff2d55;"></i>
                                            </div>
                                            <span class="font-bold"
                                                style="font-size: 13px; color: #ff2d55;">{{ __('customers.' . $customer->gender) }}</span>
                                        @endif
                                    </div>
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        {{-- Health Status --}}
                        <div class="stack-col stack-data hidden md:flex">
                            <span class="stack-label">{{ __('customers.health_status') }}</span>
                            <div class="flex flex-wrap gap-1">
                                @if ($customer->hasDrugAllergies())
                                    <span class="badge badge-danger w-fit px-2 py-0.5 text-xs">
                                        <i class="ph-fill ph-warning mr-1"></i>{{ __('customers.allergies') }}
                                    </span>
                                @endif
                                @if ($customer->pregnancy_status && $customer->pregnancy_status !== 'none')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-700 w-fit">
                                        {{ $customer->pregnancy_status_label }}
                                    </span>
                                @endif
                                @if (!$customer->hasDrugAllergies() && (!$customer->pregnancy_status || $customer->pregnancy_status === 'none'))
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </div>
                        </div>

                        {{-- Member Tier --}}
                        <div class="stack-col stack-data">
                            <span class="stack-label">{{ __('customers.membership') }}</span>
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold w-fit {{ $customer->tier_color }}">
                                @if ($customer->member_tier === 'platinum')
                                    <i class="ph-fill ph-crown"></i>
                                @elseif ($customer->member_tier === 'gold')
                                    <i class="ph-fill ph-medal"></i>
                                @elseif ($customer->member_tier === 'silver')
                                    <i class="ph-fill ph-star"></i>
                                @else
                                    <i class="ph ph-user"></i>
                                @endif
                                {{ ucfirst($customer->member_tier ?? 'regular') }}
                            </span>
                        </div>

                        {{-- Points --}}
                        <div class="stack-col stack-data">
                            <span class="stack-label">{{ __('customers.points_balance') }}</span>
                            <span class="stack-value font-bold text-ios-blue text-lg w-fit">
                                {{ number_format($customer->points_balance ?? 0) }}
                            </span>
                        </div>

                        {{-- Actions Dropdown --}}
                        <div class="stack-actions" onclick="event.stopPropagation()">
                            <div class="ios-dropdown">
                                <button type="button" class="stack-action-circle">
                                    <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                </button>
                                <div class="ios-dropdown-menu">
                                    <a href="{{ route('customers.show', $customer) }}" class="ios-dropdown-item">
                                        <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                                        <span>{{ __('view') }}</span>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="ios-dropdown-item">
                                        <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                        <span>{{ __('edit') }}</span>
                                    </a>
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <button type="button"
                                        onclick="deleteRow({{ $customer->id }}, '{{ $customer->name }}', '{{ route('customers.destroy', $customer) }}')"
                                        class="ios-dropdown-item ios-dropdown-item-danger">
                                        <i class="ph ph-trash ios-dropdown-icon"></i>
                                        <span>{{ __('delete') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                        <i class="ph ph-users text-4xl mb-3"></i>
                        <p class="font-medium">{{ __('customers.no_customers') }}</p>
                        <a href="{{ route('customers.create') }}"
                            class="mt-4 inline-block px-4 py-2 bg-ios-blue text-white rounded-lg font-medium text-sm hover:brightness-110 transition">
                            {{ __('customers.add_customer') }}
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($customers->hasPages())
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-400">
                        {{ __('general.showing') }}
                        <span class="text-gray-900 font-bold">{{ $customers->firstItem() ?? 0 }}</span>
                        - <span class="text-gray-900 font-bold">{{ $customers->lastItem() ?? 0 }}</span>
                        {{ __('general.of') }}
                        <span class="text-gray-900 font-bold">{{ $customers->total() }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        {{ $customers->withQueryString()->links('pagination.apple') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Customer page specific scripts
    </script>
@endpush

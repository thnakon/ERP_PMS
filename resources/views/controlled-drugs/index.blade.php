@extends('layouts.app')

@section('title', __('controlled_drugs.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('controlled_drugs.page_subtitle') }}
        </p>
        <span>{{ __('controlled_drugs.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        @if ($stats['pending'] > 0)
            <a href="{{ route('controlled-drugs.pending') }}"
                class="px-4 py-2.5 bg-amber-500 hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/20 transition active-scale flex items-center gap-2">
                <i class="ph-bold ph-hourglass"></i>
                <span class="hidden sm:inline">{{ __('controlled_drugs.pending_approvals') }}</span>
                <span class="bg-white/20 px-1.5 py-0.5 rounded-lg text-xs">{{ $stats['pending'] }}</span>
            </a>
        @endif
        <a href="{{ route('controlled-drugs.fda-report') }}"
            class="px-4 py-2.5 bg-purple-500 hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-purple-500/20 transition active-scale flex items-center gap-2">
            <i class="ph-bold ph-file-text"></i>
            <span class="hidden sm:inline">{{ __('controlled_drugs.fda_report') }}</span>
        </a>
        <a href="{{ route('controlled-drugs.create') }}"
            class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
            <i class="ph-bold ph-plus"></i>
            {{ __('controlled_drugs.add_new') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            {{-- Total Logs --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-clipboard-text text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('controlled_drugs.stat_total') }}</span>
                </div>
                <h3 class="text-xl font-black text-blue-600">{{ number_format($stats['total']) }}</h3>
            </div>

            {{-- Pending --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="ph-bold ph-hourglass text-amber-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">{{ __('controlled_drugs.stat_pending') }}</span>
                </div>
                <h3 class="text-xl font-black text-amber-600">{{ number_format($stats['pending']) }}</h3>
            </div>

            {{-- Approved Today --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-green-500 uppercase tracking-wider">{{ __('controlled_drugs.stat_approved_today') }}</span>
                </div>
                <h3 class="text-xl font-black text-green-600">{{ number_format($stats['approved_today']) }}</h3>
            </div>

            {{-- Dangerous Drugs --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all border-l-4 border-l-orange-500/30">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-warning text-orange-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">{{ __('controlled_drugs.stat_dangerous') }}</span>
                </div>
                <h3 class="text-xl font-black text-orange-600">{{ number_format($stats['dangerous_count']) }}</h3>
            </div>

            {{-- Narcotic/Psychotropic --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all border-l-4 border-l-red-500/30">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="ph-bold ph-shield-warning text-red-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ __('controlled_drugs.stat_specially_controlled') }}</span>
                </div>
                <h3 class="text-xl font-black text-red-600">{{ number_format($stats['specially_controlled_count']) }}</h3>
            </div>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Search + Quick Nav --}}
            <div class="flex items-center gap-2">
                <form action="{{ route('controlled-drugs.index') }}" method="GET" class="flex-1 max-w-sm relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('controlled_drugs.search_placeholder') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-IOS-blue/20 outline-none transition-all shadow-sm">
                    <button type="submit"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-ios-blue transition-colors flex items-center">
                        <i class="ph ph-arrow-right text-xl"></i>
                    </button>
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="drug_schedule" value="{{ request('drug_schedule') }}">
                </form>
                {{-- Quick Navigation Buttons --}}
                <div class="flex items-center gap-1">
                    <a href="{{ route('controlled-drugs.index', ['sort' => 'created_at', 'dir' => 'asc']) }}"
                        class="quick-nav-btn" title="{{ __('first_item') }}">
                        <i class="ph ph-caret-double-left"></i>
                    </a>
                    <a href="{{ route('controlled-drugs.index', ['sort' => 'created_at', 'dir' => 'desc']) }}"
                        class="quick-nav-btn" title="{{ __('latest_item') }}">
                        <i class="ph ph-caret-double-right"></i>
                    </a>
                </div>
            </div>

            {{-- Right: Filters --}}
            <div class="flex items-center gap-2">
                <select
                    onchange="window.location.href='{{ route('controlled-drugs.index') }}?search={{ request('search') }}&status=' + this.value + '&drug_schedule={{ request('drug_schedule') }}'"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('controlled_drugs.filter_all') }} {{ __('controlled_drugs.status') }}
                    </option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.status_pending') }}</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.status_approved') }}</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.status_rejected') }}</option>
                </select>
                <select
                    onchange="window.location.href='{{ route('controlled-drugs.index') }}?search={{ request('search') }}&status={{ request('status') }}&drug_schedule=' + this.value"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('controlled_drugs.filter_all') }} {{ __('controlled_drugs.drug_type') }}
                    </option>
                    <option value="dangerous" {{ request('drug_schedule') === 'dangerous' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.schedule_dangerous') }}</option>
                    <option value="specially_controlled"
                        {{ request('drug_schedule') === 'specially_controlled' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.schedule_specially_controlled') }}</option>
                    <option value="narcotic" {{ request('drug_schedule') === 'narcotic' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.schedule_narcotic') }}</option>
                    <option value="psychotropic" {{ request('drug_schedule') === 'psychotropic' ? 'selected' : '' }}>
                        {{ __('controlled_drugs.schedule_psychotropic') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div id="selection-header" class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all-controlled" class="checkbox-ios"
                    onclick="toggleSelectAll(this, '.drug-log-checkbox')">
                <label for="select-all-controlled" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $logs->total() }}</span>
                {{ __('controlled_drugs.stat_total') }}
            </div>
        </div>

        {{-- Logs List --}}
        <div id="drug-logs-container">
            <div class="stack-container view-list" id="logs-stack">
                @forelse($logs as $log)
                    @php
                        $scheduleColor = match ($log->product->drug_schedule ?? 'normal') {
                            'dangerous' => '#f97316',
                            'specially_controlled' => '#ef4444',
                            'narcotic' => '#b91c1c',
                            'psychotropic' => '#7c3aed',
                            default => '#3b82f6',
                        };
                        $bgLight = match ($log->product->drug_schedule ?? 'normal') {
                            'dangerous' => '#fff7ed',
                            'specially_controlled' => '#fef2f2',
                            'narcotic' => '#fef2f2',
                            'psychotropic' => '#f5f3ff',
                            default => '#eff6ff',
                        };
                    @endphp
                    <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                        onclick="window.location.href='{{ route('controlled-drugs.show', $log) }}'">

                        {{-- Checkbox --}}
                        <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                            <input type="checkbox" value="{{ $log->id }}" onchange="updateBulkBar(this)"
                                class="row-checkbox checkbox-ios drug-log-checkbox">
                        </div>

                        {{-- Log Icon / Schedule Indicator --}}
                        <div class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 relative"
                            style="background-color: {{ $bgLight }}; border: 1px solid {{ $scheduleColor }}20;">
                            <i class="ph-fill ph-pill text-2xl" style="color: {{ $scheduleColor }};"></i>
                            <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white"
                                style="background-color: {{ $scheduleColor }};"></div>
                        </div>

                        {{-- Log Number & Product --}}
                        <div class="stack-col stack-main">
                            <span class="stack-label">{{ __('controlled_drugs.log_number') }}</span>
                            <div class="stack-value text-lg leading-tight text-ios-blue">{{ $log->log_number }}</div>
                            <div class="text-xs text-gray-500 font-bold mt-0.5 uppercase tracking-wide">
                                {{ $log->product->name }}
                            </div>
                        </div>

                        {{-- Recipient Info --}}
                        <div class="stack-col stack-data flex-1">
                            <span class="stack-label">{{ __('controlled_drugs.recipient') }}</span>
                            <div class="stack-value text-sm font-bold">{{ $log->customer_name }}</div>
                            <div class="text-[10px] text-gray-400 font-mono mt-0.5">
                                {{ $log->customer_id_card ?? '-' }}
                            </div>
                        </div>

                        {{-- Drug Type Badge --}}
                        <div class="stack-col stack-data hidden lg:flex w-36">
                            <span class="stack-label">{{ __('controlled_drugs.drug_type') }}</span>
                            <div class="mt-1">{!! $log->product->drug_schedule_badge !!}</div>
                        </div>

                        {{-- Quantity --}}
                        <div class="stack-col stack-data w-24">
                            <span class="stack-label">{{ __('controlled_drugs.quantity') }}</span>
                            <div class="flex items-baseline gap-1">
                                <span
                                    class="stack-value text-lg font-black text-gray-900">{{ number_format($log->quantity) }}</span>
                                <span
                                    class="text-[10px] text-gray-400 font-bold uppercase">{{ $log->product->sell_unit ?? __('units') }}</span>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="stack-col stack-data hidden md:flex">
                            <span class="stack-label">{{ __('controlled_drugs.date') }}</span>
                            <div class="stack-value text-sm font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-gray-400 font-bold">{{ $log->created_at->format('H:i') }}</div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="stack-col stack-data w-28">
                            <span class="stack-label">{{ __('controlled_drugs.status') }}</span>
                            @if ($log->status === 'approved')
                                <span class="badge badge-success px-2.5 py-1">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('controlled_drugs.status_approved') }}
                                </span>
                            @elseif($log->status === 'pending')
                                <span class="badge badge-warning px-2.5 py-1">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    {{ __('controlled_drugs.status_pending') }}
                                </span>
                            @else
                                <span class="badge badge-danger px-2.5 py-1">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('controlled_drugs.status_rejected') }}
                                </span>
                            @endif
                        </div>

                        {{-- Actions Dropdown --}}
                        <div class="stack-actions" onclick="event.stopPropagation()">
                            <div class="ios-dropdown">
                                <button type="button" class="stack-action-circle">
                                    <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                </button>
                                <div class="ios-dropdown-menu">
                                    <a href="{{ route('controlled-drugs.show', $log) }}" class="ios-dropdown-item">
                                        <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                                        <span>{{ __('view') }}</span>
                                    </a>
                                    @if ($log->status === 'pending' && (auth()->user()->isAdmin() || auth()->user()->role === 'pharmacist'))
                                        <form action="{{ route('controlled-drugs.approve', $log) }}" method="POST"
                                            class="contents">
                                            @csrf
                                            <button type="submit" class="ios-dropdown-item">
                                                <i class="ph-bold ph-check-circle ios-dropdown-icon text-green-500"></i>
                                                <span>{{ __('approve') }}</span>
                                            </button>
                                        </form>
                                    @endif
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <button type="button"
                                        onclick="deleteRow({{ $log->id }}, '{{ $log->log_number }}', '{{ route('controlled-drugs.destroy', $log) }}')"
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
                        <i class="ph ph-shield-warning text-4xl mb-3"></i>
                        <p class="font-medium">{{ __('controlled_drugs.no_records') }}</p>
                        <a href="{{ route('controlled-drugs.create') }}"
                            class="mt-4 inline-block px-4 py-2 bg-ios-blue text-white rounded-lg font-medium text-sm hover:brightness-110 transition">
                            {{ __('controlled_drugs.add_new') }}
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-400">
                        {{ __('general.showing') }}
                        <span class="text-gray-900 font-bold">{{ $logs->firstItem() ?? 0 }}</span>
                        - <span class="text-gray-900 font-bold">{{ $logs->lastItem() ?? 0 }}</span>
                        {{ __('general.of') }}
                        <span class="text-gray-900 font-bold">{{ $logs->total() }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        {{ $logs->withQueryString()->links('pagination.apple') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Controlled Drugs page specific scripts
        function updateBulkBar(checkbox) {
            // Placeholder for bulk action logic
        }
    </script>
@endpush

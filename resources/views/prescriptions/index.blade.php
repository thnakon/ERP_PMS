@extends('layouts.app')

@section('title', __('prescriptions.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('prescriptions.page_subtitle') }}
        </p>
        <span>{{ __('prescriptions.title') }}</span>
    </div>
@endsection

@section('header-actions')
    @if ($stats['needs_refill'] > 0)
        <a href="{{ route('prescriptions.refill-reminders') }}"
            class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph-bold ph-bell-ringing"></i>
            {{ __('prescriptions.refill_reminders') }} ({{ $stats['needs_refill'] }})
        </a>
    @endif
    <a href="{{ route('prescriptions.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('prescriptions.add_new') }}
    </a>
@endsection

@section('content')
    <div>
        {{-- Toolbar Row (Products Style) --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Search + Quick Nav --}}
            <div class="flex items-center gap-2">
                <form action="{{ route('prescriptions.index') }}" method="GET" class="flex items-center gap-2">
                    <div class="flex-1 max-w-sm relative">
                        <i
                            class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('prescriptions.search_placeholder') }}"
                            class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                    </div>
                    {{-- Quick Navigation Buttons --}}
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="PrescriptionsPage.goToFirst()" class="quick-nav-btn"
                            title="{{ __('first_item') }}">
                            <i class="ph ph-caret-double-left"></i>
                        </button>
                        <button type="button" onclick="PrescriptionsPage.goToLatest()" class="quick-nav-btn"
                            title="{{ __('latest_item') }}">
                            <i class="ph ph-caret-double-right"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Center: Stats Summary (Products Style) --}}
            <div class="hidden md:flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-lg">
                    <i class="ph-bold ph-prescription text-blue-500 text-sm"></i>
                    <span class="text-sm font-bold text-blue-600">{{ number_format($stats['total']) }}</span>
                    <span class="text-xs text-blue-500">{{ __('prescriptions.total') }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 rounded-lg">
                    <i class="ph-bold ph-hourglass text-amber-500 text-sm"></i>
                    <span class="text-sm font-bold text-amber-600">{{ number_format($stats['pending']) }}</span>
                    <span class="text-xs text-amber-500">{{ __('prescriptions.pending') }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 rounded-lg">
                    <i class="ph-bold ph-check-circle text-green-500 text-sm"></i>
                    <span class="text-sm font-bold text-green-600">{{ number_format($stats['dispensed_today']) }}</span>
                    <span class="text-xs text-green-500">{{ __('prescriptions.dispensed_today') }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-purple-50 rounded-lg">
                    <i class="ph-bold ph-arrows-clockwise text-purple-500 text-sm"></i>
                    <span class="text-sm font-bold text-purple-600">{{ number_format($stats['needs_refill']) }}</span>
                    <span class="text-xs text-purple-500">{{ __('prescriptions.refill') }}</span>
                </div>
            </div>

            {{-- Right: Filter --}}
            <div class="flex items-center gap-2">
                <button type="button" onclick="PrescriptionsPage.openFilterDrawer()" data-no-loading
                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <i class="ph-bold ph-funnel text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Selection Header --}}
        <div id="selection-header" class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all-prescriptions" class="checkbox-ios"
                    onchange="PrescriptionsPage.toggleSelectAll(this)">
                <label for="select-all-prescriptions"
                    class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $prescriptions->total() }}</span>
                {{ __('prescriptions.title') }}
            </div>
        </div>

        {{-- Prescription List (Products Style) --}}
        <div class="stack-container" id="prescriptions-stack">
            @forelse($prescriptions as $prescription)
                @php
                    $borderColor = match ($prescription->status) {
                        'dispensed' => '#22c55e',
                        'pending' => '#f59e0b',
                        'partially_dispensed' => '#3b82f6',
                        'cancelled' => '#ef4444',
                        'expired' => '#6b7280',
                        default => '#E5E7EB',
                    };
                @endphp
                <div class="stack-item hover:bg-gray-50/50 transition-all border-l-4 cursor-pointer"
                    style="border-left-color: {{ $borderColor }}" data-prescription-id="{{ $prescription->id }}"
                    onclick="window.location='{{ route('prescriptions.show', $prescription) }}'">

                    {{-- Checkbox --}}
                    <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                        <input type="checkbox" value="{{ $prescription->id }}"
                            onchange="PrescriptionsPage.updateBulkBar(this)" class="row-checkbox checkbox-ios">
                    </div>

                    {{-- Icon --}}
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-100 border border-gray-50 flex-shrink-0 flex items-center justify-center overflow-hidden mr-4">
                        <i class="ph-fill ph-prescription text-blue-500 text-xl"></i>
                    </div>

                    {{-- RX Number & Status --}}
                    <div class="stack-col stack-main">
                        <span class="stack-label">{{ __('prescriptions.rx_number') }}</span>
                        <div class="flex items-center gap-2">
                            <span
                                class="stack-value text-lg font-bold text-ios-blue">{{ $prescription->prescription_number }}</span>
                        </div>
                        <div class="mt-1">
                            @if ($prescription->status === 'dispensed')
                                <span class="badge badge-success">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('prescriptions.status_dispensed') }}
                                </span>
                            @elseif($prescription->status === 'pending')
                                <span class="badge badge-warning">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    {{ __('prescriptions.status_pending') }}
                                </span>
                            @elseif($prescription->status === 'partially_dispensed')
                                <span class="badge badge-info">
                                    <span class="badge-dot badge-dot-info"></span>
                                    {{ __('prescriptions.status_partially_dispensed') }}
                                </span>
                            @elseif($prescription->status === 'cancelled')
                                <span class="badge badge-danger">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('prescriptions.status_cancelled') }}
                                </span>
                            @elseif($prescription->status === 'expired')
                                <span class="badge badge-gray">
                                    <span class="badge-dot" style="background: #9ca3af"></span>
                                    {{ __('prescriptions.status_expired') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Customer --}}
                    <div class="stack-col stack-data flex-1">
                        <span class="stack-label">{{ __('prescriptions.customer') }}</span>
                        <div class="stack-value font-medium">{{ $prescription->customer->name }}</div>
                        <div class="text-xs text-gray-400">{{ $prescription->customer->phone }}</div>
                    </div>

                    {{-- Doctor --}}
                    <div class="stack-col stack-data flex-1 hidden lg:flex">
                        <span class="stack-label">{{ __('prescriptions.doctor') }}</span>
                        <div class="stack-value text-sm">{{ $prescription->doctor_name }}</div>
                        @if ($prescription->hospital_clinic)
                            <div class="text-xs text-gray-400">{{ $prescription->hospital_clinic }}</div>
                        @endif
                    </div>

                    {{-- Items Count --}}
                    <div class="stack-col stack-data w-20 text-center">
                        <span class="stack-label">{{ __('prescriptions.items') }}</span>
                        <span
                            class="badge badge-gray px-3 py-1 font-bold text-gray-700">{{ $prescription->items->count() }}</span>
                    </div>

                    {{-- Date --}}
                    <div class="stack-col stack-data w-24 hidden md:flex">
                        <span class="stack-label">{{ __('prescriptions.date') }}</span>
                        <div class="stack-value text-sm">{{ $prescription->prescription_date->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $prescription->prescription_date->diffForHumans() }}
                        </div>
                    </div>

                    {{-- Refill Status --}}
                    <div class="stack-col stack-data w-24 hidden md:flex">
                        <span class="stack-label">{{ __('prescriptions.refill') }}</span>
                        @if ($prescription->refill_allowed > 0)
                            <div class="stack-value text-sm font-semibold">
                                {{ $prescription->refill_count }}/{{ $prescription->refill_allowed }}
                            </div>
                            @if ($prescription->can_refill && $prescription->next_refill_date)
                                <div class="text-xs text-purple-500">
                                    {{ $prescription->next_refill_date->format('d/m') }}
                                </div>
                            @endif
                        @else
                            <div class="stack-value text-sm text-gray-400">-</div>
                        @endif
                    </div>

                    {{-- Actions Dropdown --}}
                    <div class="stack-actions" onclick="event.stopPropagation()">
                        <div class="ios-dropdown">
                            <button type="button" class="stack-action-circle">
                                <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                            </button>
                            <div class="ios-dropdown-menu">
                                <a href="{{ route('prescriptions.show', $prescription) }}" class="ios-dropdown-item">
                                    <i class="ph ph-eye ios-dropdown-icon text-ios-blue"></i>
                                    <span>{{ __('view') }}</span>
                                </a>
                                @if ($prescription->status === 'pending')
                                    <a href="{{ route('prescriptions.edit', $prescription) }}" class="ios-dropdown-item">
                                        <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                        <span>{{ __('edit') }}</span>
                                    </a>
                                    <form action="{{ route('prescriptions.dispense', $prescription) }}" method="POST"
                                        class="contents">
                                        @csrf
                                        <button type="submit" class="ios-dropdown-item">
                                            <i class="ph ph-pill ios-dropdown-icon text-green-500"></i>
                                            <span>{{ __('prescriptions.dispense') }}</span>
                                        </button>
                                    </form>
                                @endif
                                @if ($prescription->can_refill)
                                    <form action="{{ route('prescriptions.refill', $prescription) }}" method="POST"
                                        class="contents">
                                        @csrf
                                        <button type="submit" class="ios-dropdown-item">
                                            <i class="ph ph-arrows-clockwise ios-dropdown-icon text-purple-500"></i>
                                            <span>{{ __('prescriptions.process_refill') }}</span>
                                        </button>
                                    </form>
                                @endif
                                @if ($prescription->status !== 'dispensed')
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST"
                                        class="contents"
                                        onsubmit="return confirm('{{ __('prescriptions.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ios-dropdown-item ios-dropdown-item-danger">
                                            <i class="ph ph-trash ios-dropdown-icon"></i>
                                            <span>{{ __('delete') }}</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                    <i class="ph ph-prescription text-4xl mb-3"></i>
                    <p class="font-medium">{{ __('prescriptions.no_prescriptions') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($prescriptions->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm font-medium text-gray-400">
                    {{ __('showing') }}
                    <span class="text-gray-900 font-bold">{{ $prescriptions->firstItem() ?? 0 }}</span>
                    - <span class="text-gray-900 font-bold">{{ $prescriptions->lastItem() ?? 0 }}</span>
                    {{ __('of') }} <span class="text-gray-900 font-bold">{{ $prescriptions->total() }}</span>
                </div>
                <div class="flex items-center gap-1">
                    {{ $prescriptions->links('pagination.apple') }}
                </div>
            </div>
        @endif
    </div>

    {{-- Filter Drawer (Slides from Right) --}}
    <div id="filter-drawer-backdrop" class="filter-drawer-backdrop hidden"
        onclick="PrescriptionsPage.closeFilterDrawer()">
    </div>
    <div id="filter-drawer-panel" class="filter-drawer-panel">
        <div class="filter-drawer-header">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <i class="ph ph-funnel text-ios-blue"></i>
                {{ __('filter') }} {{ __('prescriptions.title') }}
            </h2>
            <button type="button" onclick="PrescriptionsPage.closeFilterDrawer()" class="filter-drawer-close">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <form action="{{ route('prescriptions.index') }}" method="GET" id="filter-form">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <div class="filter-drawer-content">
                {{-- Status Filter --}}
                <div class="filter-section">
                    <h3 class="filter-section-title">
                        <i class="ph ph-hourglass"></i>
                        {{ __('prescriptions.status') }}
                    </h3>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="radio" name="status" value=""
                                {{ !request('status') ? 'checked' : '' }} class="checkbox-ios filter-checkbox">
                            <span>{{ __('prescriptions.filter_all') }}</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="status" value="pending"
                                {{ request('status') === 'pending' ? 'checked' : '' }}
                                class="checkbox-ios filter-checkbox">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                {{ __('prescriptions.status_pending') }}
                            </span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="status" value="dispensed"
                                {{ request('status') === 'dispensed' ? 'checked' : '' }}
                                class="checkbox-ios filter-checkbox">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                {{ __('prescriptions.status_dispensed') }}
                            </span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="status" value="partially_dispensed"
                                {{ request('status') === 'partially_dispensed' ? 'checked' : '' }}
                                class="checkbox-ios filter-checkbox">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                {{ __('prescriptions.status_partially_dispensed') }}
                            </span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="status" value="cancelled"
                                {{ request('status') === 'cancelled' ? 'checked' : '' }}
                                class="checkbox-ios filter-checkbox">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                {{ __('prescriptions.status_cancelled') }}
                            </span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="status" value="expired"
                                {{ request('status') === 'expired' ? 'checked' : '' }}
                                class="checkbox-ios filter-checkbox">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                {{ __('prescriptions.status_expired') }}
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Customer Filter --}}
                <div class="filter-section">
                    <h3 class="filter-section-title">
                        <i class="ph ph-user"></i>
                        {{ __('prescriptions.customer') }}
                    </h3>
                    <select name="customer_id" class="input-ios text-sm w-full">
                        <option value="">{{ __('prescriptions.filter_all') }}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date Range Filter --}}
                <div class="filter-section">
                    <h3 class="filter-section-title">
                        <i class="ph ph-calendar"></i>
                        {{ __('prescriptions.prescription_date') }}
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">{{ __('from') }}</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="input-ios text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">{{ __('to') }}</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="input-ios text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-drawer-footer">
                <button type="button" onclick="PrescriptionsPage.resetFilters()"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl">
                    {{ __('reset') }}
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-ios-blue text-white font-semibold rounded-xl flex items-center justify-center gap-2">
                    <i class="ph ph-funnel"></i>
                    {{ __('apply_filter') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const PrescriptionsPage = {
                toggleSelectAll(checkbox) {
                    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = checkbox.checked);
                },
                updateBulkBar(checkbox) {
                    // Add bulk actions if needed
                },
                goToFirst() {
                    window.location.href = "{{ route('prescriptions.index') }}?page=1";
                },
                goToLatest() {
                    window.location.href = "{{ route('prescriptions.index') }}";
                },
                openFilterDrawer() {
                    document.getElementById('filter-drawer-backdrop').classList.remove('hidden');
                    document.getElementById('filter-drawer-panel').classList.add('open');
                },
                closeFilterDrawer() {
                    document.getElementById('filter-drawer-backdrop').classList.add('hidden');
                    document.getElementById('filter-drawer-panel').classList.remove('open');
                },
                resetFilters() {
                    window.location.href = "{{ route('prescriptions.index') }}";
                }
            };
        </script>
    @endpush
@endsection

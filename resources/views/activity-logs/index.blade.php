@extends('layouts.app')

@section('title', __('activity_logs.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('activity_logs.audit_logs') }}
        </p>
        <span>{{ __('activity_logs.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <button type="button" onclick="toggleClearModal()"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium rounded-xl transition flex items-center gap-2">
            <i class="ph ph-trash"></i>
            {{ __('activity_logs.clear_old_logs') }}
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-ios-blue/10 flex items-center justify-center">
                        <i class="ph-bold ph-list-bullets text-ios-blue text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($stats['total']) }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('activity_logs.total_logs') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($stats['today']) }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('activity_logs.today_activity') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-bold ph-chart-line-up text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($stats['week']) }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('activity_logs.week_activity') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-sign-in text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($stats['logins']) }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('activity_logs.logins_today') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
            <div class="relative w-64 md:w-80">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="log-search" placeholder="{{ __('search_placeholder') }}"
                    value="{{ request('search') }}"
                    class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <select id="action-filter"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('activity_logs.all_actions') }}</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ __('activity_logs.' . $action) }}
                        </option>
                    @endforeach
                </select>
                <select id="module-filter"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('activity_logs.all_modules') }}</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                            {{ $module }}
                        </option>
                    @endforeach
                </select>
                <select id="range-filter"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('activity_logs.all_time') }}</option>
                    <option value="today" {{ request('range') === 'today' ? 'selected' : '' }}>
                        {{ __('activity_logs.today') }}</option>
                    <option value="7days" {{ request('range') === '7days' ? 'selected' : '' }}>
                        {{ __('activity_logs.last_7_days') }}</option>
                    <option value="30days" {{ request('range') === '30days' ? 'selected' : '' }}>
                        {{ __('activity_logs.last_30_days') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all" class="checkbox-ios" onchange="toggleSelectAll(this)">
                <label for="select-all" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $logs->total() }}</span> {{ __('activity_logs.total_logs') }}
            </div>
        </div>

        {{-- Activity Log List --}}
        <div class="stack-container shadow-none space-y-1">
            @forelse($logs as $log)
                <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                    onclick="window.location='{{ route('activity-logs.show', $log) }}'">
                    <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                        <input type="checkbox" value="{{ $log->id }}" onchange="updateBulkBar(this)"
                            class="row-checkbox checkbox-ios log-checkbox">
                    </div>

                    {{-- Action Icon --}}
                    <div
                        class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 {{ $log->action_color }}">
                        <i class="{{ $log->action_icon }} text-xl"></i>
                    </div>

                    {{-- Main Info --}}
                    <div class="stack-col stack-main">
                        <span class="stack-label">{{ __('activity_logs.description') }}</span>
                        <div class="stack-value text-base leading-tight">
                            {{ $log->description ?? __('activity_logs.' . $log->action) . ' - ' . $log->module }}
                        </div>
                        <div class="text-xs text-gray-400 font-medium mt-0.5">
                            {{ $log->logged_at->diffForHumans() }}
                        </div>
                    </div>

                    {{-- User --}}
                    <div class="stack-col stack-data">
                        <span class="stack-label">{{ __('activity_logs.user') }}</span>
                        <span class="stack-value text-sm">
                            {{ $log->user_name ?? 'System' }}
                        </span>
                    </div>

                    {{-- Action --}}
                    <div class="stack-col stack-data hidden md:flex">
                        <span class="stack-label">{{ __('activity_logs.action') }}</span>
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold w-fit {{ $log->action_color }}">
                            <i class="{{ $log->action_icon }} text-xs"></i>
                            {{ __('activity_logs.' . $log->action) }}
                        </span>
                    </div>

                    {{-- Module --}}
                    <div class="stack-col stack-data hidden lg:flex">
                        <span class="stack-label">{{ __('activity_logs.module') }}</span>
                        <span class="stack-value text-sm flex items-center gap-1">
                            <i class="{{ $log->module_icon }} text-gray-400"></i>
                            {{ $log->module }}
                        </span>
                    </div>

                    {{-- IP Address --}}
                    <div class="stack-col stack-data hidden xl:flex">
                        <span class="stack-label">{{ __('activity_logs.ip_address') }}</span>
                        <span class="stack-value text-xs font-mono text-gray-500">
                            {{ $log->ip_address ?? '-' }}
                        </span>
                    </div>

                    {{-- Time --}}
                    <div class="stack-col stack-data">
                        <span class="stack-label">{{ __('activity_logs.time') }}</span>
                        <span class="stack-value text-sm">
                            {{ $log->logged_at->format('H:i') }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $log->logged_at->format('d/m/Y') }}</span>
                    </div>

                    {{-- Actions Dropdown --}}
                    <div class="stack-actions" onclick="event.stopPropagation()">
                        <a href="{{ route('activity-logs.show', $log) }}" class="stack-action-circle">
                            <i class="ph ph-eye text-lg"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                    <i class="ph ph-list-bullets text-4xl mb-3"></i>
                    <p class="font-medium">{{ __('activity_logs.no_logs') }}</p>
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

    {{-- Clear Logs Modal --}}
    <div id="clear-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-warning text-orange-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ __('activity_logs.clear_old_logs') }}</h3>
                <p class="text-gray-500 mt-2">{{ __('activity_logs.clear_logs_confirm') }}</p>
            </div>
            <form action="{{ route('activity-logs.clear') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ลบบันทึกเก่ากว่า</label>
                    <select name="days" class="input-ios">
                        <option value="7">7 วัน</option>
                        <option value="30" selected>30 วัน</option>
                        <option value="60">60 วัน</option>
                        <option value="90">90 วัน</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="toggleClearModal()"
                        class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                        {{ __('cancel') }}
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition">
                        {{ __('confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search functionality
        document.getElementById('log-search')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Filter functionality
        document.getElementById('action-filter')?.addEventListener('change', applyFilters);
        document.getElementById('module-filter')?.addEventListener('change', applyFilters);
        document.getElementById('range-filter')?.addEventListener('change', applyFilters);

        function applyFilters() {
            const search = document.getElementById('log-search').value;
            const action = document.getElementById('action-filter').value;
            const module = document.getElementById('module-filter').value;
            const range = document.getElementById('range-filter').value;

            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (action) params.set('action', action);
            if (module) params.set('module', module);
            if (range) params.set('range', range);

            window.location.href = `{{ route('activity-logs.index') }}?${params.toString()}`;
        }

        // Clear modal toggle
        function toggleClearModal() {
            const modal = document.getElementById('clear-modal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    </script>
@endpush

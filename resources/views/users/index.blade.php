@extends('layouts.app')

@section('title', __('users.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('users.staff_management') }}
        </p>
        <span>{{ __('users.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('users.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('users.add_user') }}
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-ios-blue/10 flex items-center justify-center">
                        <i class="ph-bold ph-users text-ios-blue text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('users.total_users') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['active'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('users.active_users') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-first-aid-kit text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['pharmacists'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('users.pharmacists') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-warning text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['expiring_licenses'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('users.expiring_licenses') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="flex items-center justify-between gap-4 mb-2">
            <div class="relative w-64 md:w-80">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="user-search" placeholder="{{ __('search_placeholder') }}"
                    value="{{ request('search') }}"
                    class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
            </div>

            <div class="flex items-center gap-2">
                <select id="role-filter"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('users.all_roles') }}</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('users.admin') }}
                    </option>
                    <option value="pharmacist" {{ request('role') === 'pharmacist' ? 'selected' : '' }}>
                        {{ __('users.pharmacist') }}</option>
                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>{{ __('users.staff') }}
                    </option>
                </select>
                <select id="status-filter"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('users.all_statuses') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ __('users.active') }}</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>
                        {{ __('users.suspended') }}</option>
                    <option value="resigned" {{ request('status') === 'resigned' ? 'selected' : '' }}>
                        {{ __('users.resigned') }}</option>
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
                <span class="text-gray-900 font-bold">{{ $users->total() }}</span> {{ __('users.total_users') }}
            </div>
        </div>

        {{-- User List --}}
        <div class="stack-container shadow-none space-y-1">
            @forelse($users as $user)
                <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                    onclick="window.location='{{ route('users.show', $user) }}'">
                    <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                        <input type="checkbox" value="{{ $user->id }}" onchange="updateBulkBar(this)"
                            class="row-checkbox checkbox-ios user-checkbox">
                    </div>

                    {{-- User Avatar --}}
                    @if ($user->avatar)
                        <div
                            class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 overflow-hidden">
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-blue-600 flex-shrink-0 flex items-center justify-center mr-4">
                            <i class="ph-fill ph-user text-white text-2xl"></i>
                        </div>
                    @endif

                    {{-- Main Info --}}
                    <div class="stack-col stack-main">
                        <span class="stack-label">{{ __('users.user') }}</span>
                        <div class="stack-value text-lg leading-tight">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400 font-medium mt-0.5">
                            @if ($user->username)
                                {{ $user->username }} •
                            @endif
                            {{ $user->email }}
                        </div>
                    </div>

                    {{-- Position --}}
                    <div class="stack-col stack-data hidden lg:flex">
                        <span class="stack-label">{{ __('users.position') }}</span>
                        <span class="stack-value text-sm">
                            {{ $user->position ?? '-' }}
                        </span>
                    </div>

                    {{-- Role --}}
                    <div class="stack-col stack-data">
                        <span class="stack-label">{{ __('users.role') }}</span>
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold w-fit {{ $user->role_color }}">
                            @if ($user->role === 'admin')
                                <i class="ph-fill ph-crown"></i>
                            @elseif ($user->role === 'pharmacist')
                                <i class="ph-fill ph-first-aid-kit"></i>
                            @else
                                <i class="ph ph-user"></i>
                            @endif
                            {{ __('users.' . $user->role) }}
                        </span>
                    </div>

                    {{-- License Info --}}
                    <div class="stack-col stack-data hidden xl:flex">
                        <span class="stack-label">{{ __('users.pharmacist_license_no') }}</span>
                        <div class="flex flex-col gap-1">
                            @if ($user->pharmacist_license_no)
                                <span class="stack-value text-sm font-mono">{{ $user->pharmacist_license_no }}</span>
                                @if ($user->license_expiry)
                                    @if ($user->license_expiry < now())
                                        <span class="text-xs text-red-600 font-medium">
                                            <i class="ph-fill ph-warning-circle"></i> {{ __('users.license_expired') }}
                                        </span>
                                    @elseif ($user->isLicenseExpiringSoon())
                                        <span class="text-xs text-orange-600 font-medium">
                                            <i class="ph-fill ph-warning"></i> {{ __('users.license_expiring_soon') }}
                                        </span>
                                    @else
                                        <span
                                            class="text-xs text-gray-400">{{ $user->license_expiry->format('d/m/Y') }}</span>
                                    @endif
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="stack-col stack-data">
                        <span class="stack-label">{{ __('users.status') }}</span>
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold w-fit {{ $user->status_color }}">
                            {{ __('users.' . $user->status) }}
                        </span>
                    </div>

                    {{-- Actions Dropdown --}}
                    <div class="stack-actions" onclick="event.stopPropagation()">
                        <div class="ios-dropdown">
                            <button type="button" class="stack-action-circle">
                                <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                            </button>
                            <div class="ios-dropdown-menu">
                                <a href="{{ route('users.show', $user) }}" class="ios-dropdown-item">
                                    <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                                    <span>{{ __('view') }}</span>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="ios-dropdown-item">
                                    <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                    <span>{{ __('edit') }}</span>
                                </a>
                                <div class="h-px bg-gray-100 my-1"></div>
                                <button type="button"
                                    onclick="deleteRow({{ $user->id }}, '{{ $user->name }}', '{{ route('users.destroy', $user) }}')"
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
                    <p class="font-medium">{{ __('users.no_users') }}</p>
                    <a href="{{ route('users.create') }}"
                        class="mt-4 inline-block px-4 py-2 bg-ios-blue text-white rounded-lg font-medium text-sm hover:brightness-110 transition">
                        {{ __('users.add_user') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm font-medium text-gray-400">
                    {{ __('general.showing') }}
                    <span class="text-gray-900 font-bold">{{ $users->firstItem() ?? 0 }}</span>
                    - <span class="text-gray-900 font-bold">{{ $users->lastItem() ?? 0 }}</span>
                    {{ __('general.of') }}
                    <span class="text-gray-900 font-bold">{{ $users->total() }}</span>
                </div>
                <div class="flex items-center gap-1">
                    {{ $users->withQueryString()->links('pagination.apple') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Search functionality
        document.getElementById('user-search')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Filter functionality
        document.getElementById('role-filter')?.addEventListener('change', applyFilters);
        document.getElementById('status-filter')?.addEventListener('change', applyFilters);

        function applyFilters() {
            const search = document.getElementById('user-search').value;
            const role = document.getElementById('role-filter').value;
            const status = document.getElementById('status-filter').value;

            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (role) params.set('role', role);
            if (status) params.set('status', status);

            window.location.href = `{{ route('users.index') }}?${params.toString()}`;
        }
    </script>
@endpush

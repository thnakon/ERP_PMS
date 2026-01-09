@extends('layouts.app')

@section('title', __('notifications.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('notifications.page_subtitle') }}
        </p>
        <span>{{ __('notifications.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('notifications.settings') }}"
            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
            <i class="ph ph-gear"></i>
            <span class="hidden sm:inline">{{ __('notifications.settings') }}</span>
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            {{-- Total Alerts --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-bell text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('notifications.total_alerts') }}</span>
                </div>
                <h3 class="text-xl font-black text-blue-600">{{ number_format($stats['total']) }}</h3>
            </div>

            {{-- Expiring Soon --}}
            <a href="{{ route('notifications.index', ['filter' => 'expiring']) }}"
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all {{ $filter === 'expiring' ? 'ring-2 ring-orange-500' : '' }}">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-calendar-x text-orange-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">{{ __('notifications.expiring_soon') }}</span>
                </div>
                <h3 class="text-xl font-black text-orange-600">{{ number_format($stats['expiring_soon']) }}</h3>
            </a>

            {{-- Low Stock --}}
            <a href="{{ route('notifications.index', ['filter' => 'low_stock']) }}"
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all {{ $filter === 'low_stock' ? 'ring-2 ring-red-500' : '' }}">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="ph-bold ph-package text-red-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ __('notifications.low_stock') }}</span>
                </div>
                <h3 class="text-xl font-black text-red-600">{{ number_format($stats['low_stock']) }}</h3>
            </a>

            {{-- Refill Reminders --}}
            <a href="{{ route('notifications.index', ['filter' => 'refill']) }}"
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all {{ $filter === 'refill' ? 'ring-2 ring-green-500' : '' }}">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-user-circle text-green-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-green-500 uppercase tracking-wider">{{ __('notifications.refill_reminders') }}</span>
                </div>
                <h3 class="text-xl font-black text-green-600">{{ number_format($stats['refill_reminders']) }}</h3>
            </a>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Filter Pills --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
                    class="px-4 py-2 rounded-full text-sm font-bold transition {{ $filter === 'all' ? 'bg-ios-blue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ __('notifications.filter_all') }}
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'expiring']) }}"
                    class="px-4 py-2 rounded-full text-sm font-bold transition {{ $filter === 'expiring' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <i class="ph ph-calendar-x mr-1"></i>{{ __('notifications.expiring') }}
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'low_stock']) }}"
                    class="px-4 py-2 rounded-full text-sm font-bold transition {{ $filter === 'low_stock' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <i class="ph ph-package mr-1"></i>{{ __('notifications.stock') }}
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'refill']) }}"
                    class="px-4 py-2 rounded-full text-sm font-bold transition {{ $filter === 'refill' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <i class="ph ph-user-circle mr-1"></i>{{ __('notifications.refill') }}
                </a>
            </div>

            {{-- Right: Count --}}
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $notifications->count() }}</span>
                {{ __('notifications.items') }}
            </div>
        </div>

        {{-- Notifications List --}}
        <div id="notifications-container">
            <div class="stack-container view-list" id="notifications-stack">
                @forelse ($notifications as $notification)
                    <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                        onclick="window.location.href='{{ $notification['link'] }}'">

                        {{-- Priority Indicator --}}
                        <div
                            class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 {{ $notification['icon_bg'] }}">
                            <i class="ph-fill {{ $notification['icon'] }} text-2xl {{ $notification['icon_color'] }}"></i>
                        </div>

                        {{-- Main Info --}}
                        <div class="stack-col stack-main">
                            <span class="stack-label">
                                @if ($notification['type'] === 'expiring')
                                    {{ __('notifications.type_expiring') }}
                                @elseif($notification['type'] === 'low_stock')
                                    {{ __('notifications.type_stock') }}
                                @else
                                    {{ __('notifications.type_refill') }}
                                @endif
                            </span>
                            <div class="stack-value text-lg leading-tight">{{ $notification['title'] }}</div>
                            <div class="text-xs text-gray-400 font-medium mt-0.5">
                                {{ $notification['subtitle'] }}
                            </div>
                        </div>

                        {{-- Message --}}
                        <div class="stack-col stack-data flex-1 hidden md:flex">
                            <span class="stack-label">{{ __('notifications.status') }}</span>
                            <div
                                class="stack-value text-sm font-bold 
                                @if ($notification['priority_label'] === 'high') text-red-600
                                @elseif($notification['priority_label'] === 'medium') text-orange-600
                                @else text-gray-600 @endif">
                                {{ $notification['message'] }}
                            </div>
                        </div>

                        {{-- Detail --}}
                        <div class="stack-col stack-data hidden lg:flex">
                            <span class="stack-label">{{ __('notifications.details') }}</span>
                            <div class="stack-value text-sm">{{ $notification['detail'] }}</div>
                        </div>

                        {{-- Priority Badge --}}
                        <div class="stack-col stack-data w-28">
                            <span class="stack-label">{{ __('notifications.priority') }}</span>
                            @if ($notification['priority_label'] === 'high')
                                <span class="badge badge-danger px-2.5 py-1">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('notifications.priority_high') }}
                                </span>
                            @elseif($notification['priority_label'] === 'medium')
                                <span class="badge badge-warning px-2.5 py-1">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    {{ __('notifications.priority_medium') }}
                                </span>
                            @else
                                <span class="badge badge-success px-2.5 py-1">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('notifications.priority_low') }}
                                </span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="stack-actions" onclick="event.stopPropagation()">
                            <div class="ios-dropdown">
                                <button type="button" class="stack-action-circle">
                                    <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                </button>
                                <div class="ios-dropdown-menu">
                                    <a href="{{ $notification['link'] }}" class="ios-dropdown-item">
                                        <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                                        <span>{{ __('view') }}</span>
                                    </a>
                                    <form
                                        action="{{ route('notifications.dismiss', ['type' => $notification['type'], 'id' => $notification['id']]) }}"
                                        method="POST" class="contents">
                                        @csrf
                                        <button type="submit" class="ios-dropdown-item">
                                            <i class="ph ph-check-circle ios-dropdown-icon text-green-500"></i>
                                            <span>{{ __('notifications.dismiss') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                        <div class="w-20 h-20 bg-green-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4">
                            <i class="ph ph-check-circle text-4xl text-green-400"></i>
                        </div>
                        <p class="font-bold text-gray-900 text-lg">{{ __('notifications.all_clear') }}</p>
                        <p class="font-medium text-gray-400 mt-1">{{ __('notifications.no_alerts') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-refresh notification count every 60 seconds
        setInterval(async () => {
            const countRes = await fetch('{{ route('notifications.count') }}');
            const data = await countRes.json();

            // Update header badge if exists
            const badge = document.querySelector('#notification-badge');
            if (badge && data.total > 0) {
                badge.textContent = data.total;
                badge.classList.remove('hidden');
            } else if (badge) {
                badge.classList.add('hidden');
            }
        }, 60000);
    </script>
@endpush

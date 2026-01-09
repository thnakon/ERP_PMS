@extends('layouts.app')

@section('title', __('promotions.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('promotions.subtitle') }}
        </p>
        <span>{{ __('promotions.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('promotions.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('promotions.add_promotion') }}
    </a>
@endsection

@section('content')
    @php
        $totalPromotions = $promotions->total();
        $activeCount = \App\Models\Promotion::active()->count();
        $totalUsage = \App\Models\Promotion::sum('usage_count');
        $totalDiscountGiven = \App\Models\PromotionUsage::sum('discount_amount');
    @endphp

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            {{-- Total Promotions --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="ph-bold ph-tag text-purple-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">{{ __('promotions.total_promotions') }}</span>
                </div>
                <h3 class="text-xl font-black text-purple-600">{{ number_format($totalPromotions) }}</h3>
            </div>

            {{-- Active Promotions --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-green-500 uppercase tracking-wider">{{ __('promotions.active_promotions') }}</span>
                </div>
                <h3 class="text-xl font-black text-green-600">{{ number_format($activeCount) }}</h3>
            </div>

            {{-- Total Usage --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-users text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('promotions.usage_count') }}</span>
                </div>
                <h3 class="text-xl font-black text-blue-600">{{ number_format($totalUsage) }}</h3>
            </div>

            {{-- Total Discount --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-coins text-orange-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">{{ __('promotions.total_discount') }}</span>
                </div>
                <h3 class="text-xl font-black text-orange-600">฿{{ number_format($totalDiscountGiven, 0) }}</h3>
            </div>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4">
            {{-- Left: Search --}}
            <div class="flex items-center gap-2 flex-1 max-w-md">
                <form action="{{ route('promotions.index') }}" method="GET" class="flex-1 relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('promotions.search_promotions') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                    <button type="submit"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-ios-blue transition-colors flex items-center">
                        <i class="ph ph-arrow-right text-xl"></i>
                    </button>
                    @if (request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </form>
            </div>

            {{-- Right: Filters --}}
            <div class="flex items-center gap-2">
                <select name="type"
                    onchange="window.location.href='{{ route('promotions.index') }}?search={{ request('search') }}&status={{ request('status') }}&type=' + this.value"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('promotions.all_types') }}</option>
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>

                <select name="status"
                    onchange="window.location.href='{{ route('promotions.index') }}?search={{ request('search') }}&type={{ request('type') }}&status=' + this.value"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="">{{ __('promotions.all_statuses') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        {{ __('promotions.active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        {{ __('promotions.inactive') }}</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>
                        {{ __('promotions.expired') }}</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>
                        {{ __('promotions.scheduled') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div id="selection-header" class="flex items-center justify-between px-8 py-2">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all-promotions" class="checkbox-ios"
                    onclick="toggleSelectAll(this, '.promotion-checkbox')">
                <label for="select-all-promotions" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $promotions->total() }}</span>
                {{ __('promotions.total_promotions') }}
            </div>
        </div>

        {{-- Promotions List (Stack View) --}}
        <div id="promotion-list-container">
            <div class="stack-container view-list">
                @forelse($promotions as $promotion)
                    <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                        onclick="window.location.href='{{ route('promotions.edit', $promotion) }}'">

                        {{-- Checkbox --}}
                        <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                            <input type="checkbox" value="{{ $promotion->id }}"
                                class="row-checkbox checkbox-ios promotion-checkbox">
                        </div>

                        {{-- Icon --}}
                        <div
                            class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 
                            {{ $promotion->is_featured ? 'bg-gradient-to-br from-yellow-400 to-orange-500 shadow-orange-200 shadow-lg' : 'bg-purple-100' }}">
                            <i
                                class="ph-fill {{ $promotion->is_featured ? 'ph-star text-white' : 'ph-tag text-purple-600' }} text-2xl"></i>
                        </div>

                        {{-- Main Info --}}
                        <div class="stack-col stack-main">
                            <span class="stack-label">{{ __('promotions.name') }}</span>
                            <div class="stack-value text-lg leading-tight">{{ $promotion->name }}</div>
                            @if ($promotion->code)
                                <div class="text-xs text-gray-400 font-mono mt-0.5 font-bold uppercase tracking-wider">
                                    {{ $promotion->code }}
                                </div>
                            @endif
                        </div>

                        {{-- Type & Discount --}}
                        <div class="stack-col stack-data hidden md:flex">
                            <span class="stack-label">{{ __('promotions.type') }}</span>
                            <div class="flex flex-col">
                                <span class="stack-value text-sm font-bold text-gray-700">
                                    {{ $promotion->type_label }}
                                </span>
                                <span
                                    class="text-xs font-black
                                    @if ($promotion->type === 'percentage') text-blue-500
                                    @elseif($promotion->type === 'fixed_amount') text-green-500
                                    @elseif($promotion->type === 'buy_x_get_y') text-purple-500
                                    @else text-gray-500 @endif">
                                    @if ($promotion->type === 'percentage')
                                        {{ $promotion->discount_value }}% OFF
                                    @elseif($promotion->type === 'fixed_amount')
                                        ฿{{ number_format($promotion->discount_value, 0) }} OFF
                                    @elseif($promotion->type === 'buy_x_get_y')
                                        BUY {{ $promotion->buy_quantity }} GET {{ $promotion->get_quantity }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Date Range --}}
                        <div class="stack-col stack-data hidden lg:flex">
                            <span class="stack-label">{{ __('promotions.start_date') }} -
                                {{ __('promotions.end_date') }}</span>
                            <div class="stack-value text-xs text-gray-500">
                                @if ($promotion->start_date || $promotion->end_date)
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-calendar"></i>
                                        {{ $promotion->start_date ? $promotion->start_date->format('d/m/Y') : '∞' }} -
                                        {{ $promotion->end_date ? $promotion->end_date->format('d/m/Y') : '∞' }}
                                    </div>
                                @else
                                    {{ __('promotions.always_active') }}
                                @endif
                            </div>
                        </div>

                        {{-- Usage --}}
                        <div class="stack-col stack-data hidden xl:flex">
                            <span class="stack-label">{{ __('promotions.usage_count') }}</span>
                            <div class="flex flex-col">
                                <span class="stack-value font-bold text-ios-blue">
                                    {{ number_format($promotion->usage_count) }}
                                </span>
                                @if ($promotion->usage_limit)
                                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">
                                        Limit: {{ number_format($promotion->usage_limit) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="stack-col stack-data">
                            <span class="stack-label">Status</span>
                            @if ($promotion->isCurrentlyActive())
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    {{ __('promotions.active') }}
                                </span>
                            @elseif(!$promotion->is_active)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-500">
                                    {{ __('promotions.inactive') }}
                                </span>
                            @elseif($promotion->end_date && $promotion->end_date->isPast())
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-600">
                                    {{ __('promotions.expired') }}
                                </span>
                            @elseif($promotion->start_date && $promotion->start_date->isFuture())
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-600">
                                    {{ __('promotions.scheduled') }}
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
                                    <button type="button" onclick="togglePromotion({{ $promotion->id }})"
                                        class="ios-dropdown-item">
                                        @if ($promotion->is_active)
                                            <i class="ph ph-toggle-left ios-dropdown-icon text-gray-400"></i>
                                            <span>{{ __('promotions.deactivate') }}</span>
                                        @else
                                            <i class="ph ph-toggle-right ios-dropdown-icon text-green-500"></i>
                                            <span>{{ __('promotions.activate') }}</span>
                                        @endif
                                    </button>
                                    <a href="{{ route('promotions.edit', $promotion) }}" class="ios-dropdown-item">
                                        <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                        <span>{{ __('edit') }}</span>
                                    </a>
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <button type="button" onclick="deletePromotion({{ $promotion->id }})"
                                        class="ios-dropdown-item ios-dropdown-item-danger">
                                        <i class="ph ph-trash ios-dropdown-icon"></i>
                                        <span>{{ __('delete') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-white/50 backdrop-blur-sm rounded-3xl p-16 text-center text-gray-400 border border-dashed border-gray-200">
                        <i class="ph ph-tag text-5xl mb-4 text-gray-200"></i>
                        <h3 class="text-xl font-bold text-gray-500 mb-2">{{ __('promotions.no_promotions') }}</h3>
                        <p class="text-sm mb-6">{{ __('promotions.no_promotions_desc') }}</p>
                        <a href="{{ route('promotions.create') }}"
                            class="inline-block px-6 py-3 bg-ios-blue text-white rounded-2xl font-bold transition active-scale shadow-lg shadow-blue-500/20">
                            {{ __('promotions.add_promotion') }}
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($promotions->hasPages())
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-400">
                        {{ __('general.showing') }}
                        <span class="text-gray-900 font-bold">{{ $promotions->firstItem() ?? 0 }}</span>
                        - <span class="text-gray-900 font-bold">{{ $promotions->lastItem() ?? 0 }}</span>
                        {{ __('general.of') }}
                        <span class="text-gray-900 font-bold">{{ $promotions->total() }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        {{ $promotions->appends(request()->query())->links('pagination.apple') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        async function togglePromotion(id) {
            try {
                const response = await fetch(`/promotions/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function deletePromotion(id) {
            if (confirm('{{ __('promotions.confirm_delete') }}')) {
                const form = document.getElementById('deleteForm');
                form.action = `/promotions/${id}`;
                form.submit();
            }
        }

        function toggleSelectAll(masterCheckbox, selector) {
            const checkboxes = document.querySelectorAll(selector);
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
            });
        }
    </script>
@endpush

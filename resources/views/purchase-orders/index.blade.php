@extends('layouts.app')

@section('title', __('po.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('purchasing') }}
        </p>
        <span>{{ __('po.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('purchase-orders.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('po.add_new') }}
    </a>
@endsection

@section('content')
    <div>
        {{-- Stats Cards --}}
        <div class="grid grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <i class="ph-bold ph-file-text text-gray-600"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ __('po.total_orders') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <i class="ph-bold ph-pencil-simple-line text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-900">{{ $stats['draft'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ __('po.draft') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-paper-plane-tilt text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-900">{{ $stats['sent'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ __('po.sent') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-clock text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-900">{{ $stats['partial'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ __('po.partial') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-900">{{ $stats['completed'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ __('po.completed') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="flex items-center justify-between gap-4 mb-2">
            <div class="relative w-64 md:w-80">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="po-search" placeholder="{{ __('search_placeholder') }}"
                    value="{{ request('search') }}"
                    class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
            </div>

            <div class="flex items-center gap-2">
                <select id="status-filter" onchange="POPage.applyFilter()"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>{{ __('po.all_status') }}
                    </option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('po.draft') }}
                    </option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>{{ __('po.sent') }}
                    </option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>{{ __('po.partial') }}
                    </option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                        {{ __('po.completed') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                        {{ __('po.cancelled') }}</option>
                </select>
                <select id="sort-filter" onchange="POPage.applyFilter()"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('po.sort_newest') }}
                    </option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>{{ __('po.sort_oldest') }}
                    </option>
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
                <span class="text-gray-900 font-bold">{{ $orders->total() }}</span> {{ __('po.total_orders') }}
            </div>
        </div>

        {{-- List --}}
        <div class="stack-container shadow-none space-y-1">
            @forelse($orders as $order)
                <div class="stack-item hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center pr-4">
                        <input type="checkbox" value="{{ $order->id }}" class="row-checkbox checkbox-ios"
                            onchange="updateBulkBar(this)">
                    </div>

                    {{-- PO Number --}}
                    <div class="stack-col stack-main" style="flex: 0 0 160px;">
                        <span class="stack-label">{{ __('po.po_number') }}</span>
                        <div class="stack-value font-mono text-sm font-bold">{{ $order->po_number }}</div>
                        <div class="text-[10px] text-gray-400 font-medium">{{ $order->order_date->format('d/m/Y') }}</div>
                    </div>

                    {{-- Supplier --}}
                    <div class="stack-col stack-data flex-1">
                        <span class="stack-label">{{ __('po.supplier') }}</span>
                        <div class="stack-value text-sm font-semibold">{{ $order->supplier->name }}</div>
                    </div>

                    {{-- Expected Date --}}
                    <div class="stack-col stack-data" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('po.expected_date') }}</span>
                        <div class="stack-value text-sm">{{ $order->expected_date?->format('d/m/Y') ?? '-' }}</div>
                    </div>

                    {{-- Grand Total --}}
                    <div class="stack-col stack-data" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('po.grand_total') }}</span>
                        <div class="stack-value text-sm font-bold text-ios-blue">
                            ฿{{ number_format($order->grand_total, 2) }}</div>
                    </div>

                    {{-- Status --}}
                    <div class="stack-col stack-data" style="flex: 0 0 120px;">
                        <span class="stack-label">{{ __('po.status') }}</span>
                        @switch($order->status)
                            @case('draft')
                                <span class="badge badge-gray">
                                    <span class="badge-dot badge-dot-gray"></span>
                                    {{ __('po.draft') }}
                                </span>
                            @break

                            @case('sent')
                                <span class="badge badge-info">
                                    <span class="badge-dot badge-dot-info"></span>
                                    {{ __('po.sent') }}
                                </span>
                            @break

                            @case('partial')
                                <span class="badge badge-warning">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    {{ __('po.partial') }}
                                </span>
                            @break

                            @case('completed')
                                <span class="badge badge-success">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('po.completed') }}
                                </span>
                            @break

                            @case('cancelled')
                                <span class="badge badge-danger">
                                    <span class="badge-dot badge-dot-danger"></span>
                                    {{ __('po.cancelled') }}
                                </span>
                            @break
                        @endswitch
                    </div>

                    {{-- Actions --}}
                    <div class="stack-actions">
                        <a href="{{ route('purchase-orders.show', $order) }}"
                            class="stack-action-circle hover:bg-ios-blue/10" title="{{ __('view') }}">
                            <i class="ph ph-caret-right text-gray-400 hover:text-ios-blue"></i>
                        </a>
                    </div>
                </div>
                @empty
                    <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                        <i class="ph ph-file-text text-4xl mb-3"></i>
                        <p class="font-medium">{{ __('po.no_orders') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($orders->hasPages())
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-400">
                        {{ __('general.showing') }}
                        <span class="text-gray-900 font-bold">{{ $orders->firstItem() ?? 0 }}</span>
                        - <span class="text-gray-900 font-bold">{{ $orders->lastItem() ?? 0 }}</span>
                        {{ __('general.of') }}
                        <span class="text-gray-900 font-bold">{{ $orders->total() }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        {{ $orders->links('pagination.apple') }}
                    </div>
                </div>
            @endif
        </div>
    @endsection

    @push('scripts')
        <script>
            const POPage = {
                applyFilter() {
                    const search = document.getElementById('po-search').value;
                    const status = document.getElementById('status-filter').value;
                    const sort = document.getElementById('sort-filter').value;

                    const params = new URLSearchParams();
                    if (search) params.set('search', search);
                    if (status !== 'all') params.set('status', status);
                    if (sort !== 'newest') params.set('sort', sort);

                    window.location.href = `{{ route('purchase-orders.index') }}?${params.toString()}`;
                },

                toggleSelectAll(checkbox) {
                    // Handled by global toggleSelectAll
                }
            };

            window.POPage = POPage;

            document.getElementById('po-search')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') POPage.applyFilter();
            });
        </script>
    @endpush

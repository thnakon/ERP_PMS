@extends('layouts.app')

@section('title', __('gr.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('purchasing') }}
        </p>
        <span>{{ __('gr.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('goods-received.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('gr.add_new') }}
    </a>
@endsection

@section('content')
    <div>
        {{-- Stats Cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-ios-blue/10 flex items-center justify-center">
                        <i class="ph-bold ph-package text-ios-blue text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('gr.total_receipts') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-clock text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['pending'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('gr.pending') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['completed'] }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ __('gr.completed') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Segmented Control --}}
        <div class="flex justify-center mb-6">
            <div class="bg-gray-200/50 p-1 rounded-2xl flex gap-1 backdrop-blur-sm border border-gray-200/50">
                <a href="{{ route('goods-received.index', array_merge(request()->query(), ['tab' => 'history'])) }}"
                    class="px-8 py-2 rounded-xl text-sm font-bold transition-all {{ request('tab', 'history') === 'history' ? 'bg-white shadow-md text-ios-blue scale-[1.02]' : 'text-gray-500 hover:text-gray-700' }} flex items-center gap-2">
                    <i class="ph-bold ph-clock-counter-clockwise"></i>
                    {{ __('gr.total_receipts') }}
                </a>
                <a href="{{ route('goods-received.index', array_merge(request()->query(), ['tab' => 'pending'])) }}"
                    class="px-8 py-2 rounded-xl text-sm font-bold transition-all relative {{ request('tab') === 'pending' ? 'bg-white shadow-md text-ios-blue scale-[1.02]' : 'text-gray-500 hover:text-gray-700' }} flex items-center gap-2">
                    <i class="ph-bold ph-paper-plane-tilt"></i>
                    {{ __('gr.pending_orders') }}
                    @if ($stats['pending'] > 0)
                        <span
                            class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 bg-red-500 text-white text-[10px] flex items-center justify-center rounded-full border-2 border-white shadow-sm px-1 font-black transition-transform hover:scale-110">
                            {{ $stats['pending'] }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        @if (request('tab', 'history') === 'history')
            {{-- Toolbar --}}
            <div class="flex items-center justify-between gap-4 mb-2">
                <div class="relative w-64 md:w-80">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" id="gr-search" placeholder="{{ __('search_placeholder') }}"
                        value="{{ request('search') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-4 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                </div>

                <div class="flex items-center gap-2">
                    <select id="status-filter" onchange="GRPage.applyFilter()"
                        class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>
                            {{ __('gr.all_status') }}
                        </option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            {{ __('gr.pending') }}
                        </option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                            {{ __('gr.completed') }}</option>
                    </select>
                    <select id="sort-filter" onchange="GRPage.applyFilter()"
                        class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                            {{ __('gr.sort_newest') }}
                        </option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                            {{ __('gr.sort_oldest') }}
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
                    <span class="text-gray-900 font-bold">{{ $receipts->total() }}</span> {{ __('gr.total_receipts') }}
                </div>
            </div>

            {{-- List --}}
            <div class="stack-container shadow-none space-y-1">
                @forelse($receipts as $receipt)
                    <div class="stack-item hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center pr-4">
                            <input type="checkbox" value="{{ $receipt->id }}" class="row-checkbox checkbox-ios"
                                onchange="updateBulkBar(this)">
                        </div>

                        {{-- GR Number --}}
                        <div class="stack-col stack-main" style="flex: 0 0 160px;">
                            <span class="stack-label">{{ __('gr.gr_number') }}</span>
                            <div class="stack-value font-mono text-sm font-bold">{{ $receipt->gr_number }}</div>
                            <div class="text-[10px] text-gray-400 font-medium">
                                {{ $receipt->received_date->format('d/m/Y') }}
                            </div>
                        </div>

                        {{-- PO Reference --}}
                        <div class="stack-col stack-data" style="flex: 0 0 140px;">
                            <span class="stack-label">{{ __('gr.po_reference') }}</span>
                            <div class="stack-value text-sm">
                                @if ($receipt->purchaseOrder)
                                    <a href="{{ route('purchase-orders.show', $receipt->purchaseOrder) }}"
                                        class="text-ios-blue hover:underline font-medium">
                                        {{ $receipt->purchaseOrder->po_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </div>

                        {{-- Supplier --}}
                        <div class="stack-col stack-data flex-1">
                            <span class="stack-label">{{ __('gr.supplier') }}</span>
                            <div class="stack-value text-sm font-semibold">{{ $receipt->supplier->name }}</div>
                        </div>

                        {{-- Invoice No --}}
                        <div class="stack-col stack-data" style="flex: 0 0 120px;">
                            <span class="stack-label">{{ __('gr.invoice_no') }}</span>
                            <div class="stack-value text-sm">{{ $receipt->invoice_no ?? '-' }}</div>
                        </div>

                        {{-- Total --}}
                        <div class="stack-col stack-data" style="flex: 0 0 120px;">
                            <span class="stack-label">{{ __('gr.total_amount') }}</span>
                            <div class="stack-value text-sm font-bold text-green-600">
                                ฿{{ number_format($receipt->total_amount, 2) }}</div>
                        </div>

                        {{-- Status --}}
                        <div class="stack-col stack-data" style="flex: 0 0 100px;">
                            <span class="stack-label">{{ __('gr.status') }}</span>
                            @switch($receipt->status)
                                @case('pending')
                                    <span class="badge badge-warning">
                                        <span class="badge-dot badge-dot-warning"></span>
                                        {{ __('gr.pending') }}
                                    </span>
                                @break

                                @case('completed')
                                    <span class="badge badge-success">
                                        <span class="badge-dot badge-dot-success"></span>
                                        {{ __('gr.completed') }}
                                    </span>
                                @break
                            @endswitch
                        </div>

                        {{-- Actions --}}
                        <div class="stack-actions">
                            <a href="{{ route('goods-received.show', $receipt) }}"
                                class="stack-action-circle hover:bg-ios-blue/10" title="{{ __('view') }}">
                                <i class="ph ph-caret-right text-gray-400 hover:text-ios-blue"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                        <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
                            <i class="ph ph-package text-4xl mb-3"></i>
                            <p class="font-medium">{{ __('gr.no_receipts') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($receipts->hasPages())
                    <div class="mt-8 flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-400">
                            {{ __('general.showing') }}
                            <span class="text-gray-900 font-bold">{{ $receipts->firstItem() ?? 0 }}</span>
                            - <span class="text-gray-900 font-bold">{{ $receipts->lastItem() ?? 0 }}</span>
                            {{ __('general.of') }}
                            <span class="text-gray-900 font-bold">{{ $receipts->total() }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            {{ $receipts->links('pagination.apple') }}
                        </div>
                    </div>
                @endif
            @else
                {{-- Pending POs List --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($pendingPOs as $po)
                        <div
                            class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:border-ios-blue/30 transition-all group flex flex-col h-full border-b-[5px] border-b-orange-400 relative overflow-hidden">
                            {{-- Background Decoration --}}
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full blur-2xl opacity-50">
                            </div>

                            <div class="flex justify-between items-start mb-4 relative">
                                <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center">
                                    <i class="ph-fill ph-paper-plane-tilt text-orange-600 text-2xl"></i>
                                </div>
                                <span class="badge badge-warning">
                                    <span class="badge-dot badge-dot-warning"></span>
                                    @if ($po->status === 'sent')
                                        รอนัดรับสินค้า
                                    @else
                                        รับบางส่วนแล้ว
                                    @endif
                                </span>
                            </div>

                            <div class="flex-1 relative">
                                <h3 class="text-lg font-black text-gray-900 mb-1 font-mono tracking-tight">
                                    {{ $po->po_number }}
                                </h3>
                                <div class="flex items-center gap-2 mb-4">
                                    <div
                                        class="w-6 h-6 rounded-full bg-ios-blue text-white flex items-center justify-center text-[10px] font-bold">
                                        {{ strtoupper(substr($po->supplier->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-600">{{ $po->supplier->name }}</span>
                                </div>

                                <div class="space-y-3 bg-gray-50 rounded-2xl p-4 mb-4">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-400 font-bold uppercase tracking-wider">สั่งซื้อเมื่อ</span>
                                        <span class="text-gray-900 font-bold">{{ $po->order_date->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs border-t border-gray-200/50 pt-3">
                                        <span class="text-gray-400 font-bold uppercase tracking-wider">นัดรับสินค้า</span>
                                        <span
                                            class="text-ios-blue font-bold">{{ $po->expected_date?->format('d M Y') ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs border-t border-gray-200/50 pt-3">
                                        <span class="text-gray-400 font-bold uppercase tracking-wider">จำนวนรายการ</span>
                                        <span class="text-gray-900 font-bold">{{ $po->items->count() }} รายการ</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('goods-received.create-from-po', $po) }}"
                                class="w-full py-3.5 bg-ios-blue text-white rounded-2xl font-black text-sm flex items-center justify-center gap-2 hover:brightness-110 active:scale-[0.98] transition-all shadow-lg shadow-blue-500/20 mt-2">
                                <i class="ph-bold ph-package"></i>
                                {{ __('gr.receive_now') }}
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-gray-200 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ph ph-check-circle text-4xl text-gray-300"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">ไม่มีใบสั่งซื้อค้างรับสินค้า</h4>
                            <p class="text-gray-500">ใบสั่งซื้อทั้งหมดถูกรับเข้าคลังเรียบร้อยแล้ว
                                หรือยังไม่มีการส่งใบสั่งซื้อใหม่
                            </p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    @endsection

    @push('scripts')
        <script>
            const GRPage = {
                applyFilter() {
                    const search = document.getElementById('gr-search')?.value;
                    const status = document.getElementById('status-filter')?.value;
                    const sort = document.getElementById('sort-filter')?.value;

                    const params = new URLSearchParams(window.location.search);
                    if (search !== undefined) {
                        if (search) params.set('search', search);
                        else params.delete('search');
                    }
                    if (status !== undefined) {
                        if (status !== 'all') params.set('status', status);
                        else params.delete('status');
                    }
                    if (sort !== undefined) {
                        if (sort !== 'newest') params.set('sort', sort);
                        else params.delete('sort');
                    }

                    window.location.href = `{{ route('goods-received.index') }}?${params.toString()}`;
                }
            };

            window.GRPage = GRPage;

            document.getElementById('gr-search')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') GRPage.applyFilter();
            });
        </script>
    @endpush

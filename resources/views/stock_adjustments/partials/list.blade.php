{{-- Selection Header --}}
<div class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
    <div class="flex items-center gap-3">
        <input type="checkbox" id="select-all-adj" class="checkbox-ios"
            onchange="StockAdjustmentPage.toggleSelectAll(this)">
        <label for="select-all-adj" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
            {{ __('select_all') }}
        </label>
    </div>
    <div class="text-sm font-medium text-gray-400">
        <span class="text-gray-900 font-bold">{{ $adjustments->total() }}</span> {{ __('total_adjustments') }}
    </div>
</div>

<div class="stack-container view-list" id="adj-stack">
    @forelse($adjustments as $adj)
        <div class="stack-item hover:bg-gray-50/50 transition-colors">
            {{-- Checkbox --}}
            <div class="flex items-center pr-4">
                <input type="checkbox" value="{{ $adj->id }}" onchange="StockAdjustmentPage.updateBulkBar(this)"
                    class="row-checkbox checkbox-ios">
            </div>

            {{-- Header Info --}}
            <div class="stack-col stack-main" style="flex: 0 0 160px;">
                <span class="stack-label">{{ __('adj_no') }}</span>
                <div class="stack-value font-mono text-sm">{{ $adj->adjustment_number }}</div>
                <div class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $adj->adjusted_at->format('d/m/Y H:i') }}
                </div>
            </div>

            {{-- Product Info --}}
            <div class="stack-col stack-data flex-1">
                <span class="stack-label">{{ __('product') }}</span>
                <div class="stack-value text-base">{{ $adj->product->name }}</div>
                <div class="text-xs text-gray-400">
                    SKU: {{ $adj->product->sku }}
                    @if ($adj->lot)
                        | Lot: {{ $adj->lot->lot_number }}
                    @endif
                </div>
            </div>

            {{-- Adjustment Details --}}
            <div class="stack-col stack-data" style="flex: 0 0 120px;">
                <span class="stack-label">{{ __('type') }} / {{ __('quantity') }}</span>
                <div class="stack-value flex items-center gap-1.5">
                    @if ($adj->type === 'increase')
                        <i class="ph-bold ph-arrow-circle-up text-green-500"></i>
                        <span class="text-green-600 font-bold">+{{ $adj->quantity }}</span>
                    @elseif($adj->type === 'decrease')
                        <i class="ph-bold ph-arrow-circle-down text-red-500"></i>
                        <span class="text-red-600 font-bold">-{{ $adj->quantity }}</span>
                    @else
                        <i class="ph-bold ph-equals text-blue-500"></i>
                        <span class="text-blue-600 font-bold">{{ $adj->quantity }}</span>
                    @endif
                </div>
            </div>

            {{-- Before/After --}}
            <div class="stack-col stack-data" style="flex: 0 0 100px;">
                <span class="stack-label">{{ __('before') }} / {{ __('after') }}</span>
                <div class="stack-value text-sm text-gray-500">
                    {{ $adj->before_quantity }} <i class="ph ph-arrow-right text-[10px]"></i>
                    {{ $adj->after_quantity }}
                </div>
            </div>

            {{-- Reason --}}
            <div class="stack-col stack-data" style="flex: 0 0 140px;">
                <span class="stack-label">{{ __('reason') }}</span>
                <div class="stack-value">
                    <span class="badge badge-gray px-2 py-0.5 text-[10px] uppercase tracking-wider">
                        {{ __($adj->reason) ?? $adj->reason }}
                    </span>
                </div>
            </div>

            {{-- Auditor --}}
            <div class="stack-col stack-data" style="flex: 0 0 120px;">
                <span class="stack-label">{{ __('auditor') }}</span>
                <div class="stack-value text-sm">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-6 h-6 rounded-full bg-ios-blue/10 flex items-center justify-center text-[10px] text-ios-blue font-bold">
                            {{ substr($adj->user->name, 0, 1) }}
                        </div>
                        <span class="truncate">{{ $adj->user->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="stack-actions">
                <button type="button" class="stack-action-circle hover:bg-gray-100"
                    onclick="window.location.href='{{ route('stock-adjustments.show', $adj) }}'">
                    <i class="ph-bold ph-caret-right text-gray-400"></i>
                </button>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
            <i class="ph ph-scroll text-4xl mb-3"></i>
            <p class="text-lg font-medium">{{ __('no_adjustment_records') }}</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-8 flex justify-between items-center px-4">
    <div class="table-pagination-info">
        {{ __('general.showing') }}
        <span class="font-bold text-gray-900">{{ $adjustments->firstItem() ?? 0 }}</span>
        - <span class="font-bold text-gray-900">{{ $adjustments->lastItem() ?? 0 }}</span>
        {{ __('general.of') }}
        <span class="font-bold text-gray-900">{{ $adjustments->total() }}</span>
    </div>
    <div class="table-pagination-nav">
        {{ $adjustments->links('pagination.apple') }}
    </div>
</div>

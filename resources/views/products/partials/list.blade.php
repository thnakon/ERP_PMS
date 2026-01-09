{{-- Selection Header --}}
<div id="selection-header" class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
    <div class="flex items-center gap-3">
        <input type="checkbox" id="select-all-stack" class="checkbox-ios" onchange="toggleSelectAll(this)">
        <label for="select-all-stack" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
            {{ __('select_all') }}
        </label>
    </div>
    <div class="text-sm font-medium text-gray-400">
        <span class="text-gray-900 font-bold">{{ $products->total() }}</span> {{ __('total_products') }}
    </div>
</div>

{{-- ==================== LIST VIEW ==================== --}}
<div class="stack-container view-list" id="products-stack">
    @forelse($products as $product)
        <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
            id="product-list-{{ $product->id }}" data-product-id="{{ $product->id }}"
            onclick="window.location.href='{{ route('products.show', $product) }}'">
            {{-- Checkbox --}}
            <div class="flex items-center pr-4" onclick="event.stopPropagation()">
                <input type="checkbox" value="{{ $product->id }}" onchange="updateBulkBar(this)"
                    class="row-checkbox checkbox-ios">
            </div>

            {{-- Product Image --}}
            <div
                class="w-14 h-14 bg-gray-100 rounded-2xl flex-shrink-0 flex items-center justify-center border border-gray-50 mr-4 overflow-hidden">
                @if ($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover">
                @else
                    <i class="ph ph-image text-gray-300 text-2xl"></i>
                @endif
            </div>

            {{-- Main Info --}}
            <div class="stack-col stack-main">
                <span class="stack-label">{{ __('products.name') }}</span>
                <div class="stack-value text-lg leading-tight">{{ $product->name }}</div>
                <div class="text-xs text-gray-400 font-medium mt-0.5">{{ $product->sku }}</div>
            </div>

            {{-- Generic Name --}}
            <div class="stack-col stack-data hidden lg:flex">
                <span class="stack-label">{{ __('products.generic_name') }}</span>
                <span class="stack-value text-sm">{{ $product->generic_name ?? '-' }}</span>
            </div>

            {{-- Category --}}
            <div class="stack-col stack-data hidden md:flex">
                <span class="stack-label">{{ __('products.category') }}</span>
                <span class="stack-value text-sm">{{ $product->category?->localized_name ?? '-' }}</span>
            </div>

            {{-- Price --}}
            <div class="stack-col stack-data">
                <span class="stack-label">{{ __('products.price') }}</span>
                <span
                    class="stack-value font-bold text-ios-blue text-lg">฿{{ number_format($product->unit_price, 2) }}</span>
            </div>

            {{-- Stock Status --}}
            <div class="stack-col stack-data">
                <span class="stack-label">{{ __('products.stock') }}</span>
                @if ($product->isLowStock())
                    <span class="badge badge-danger w-fit px-3 py-1 text-xs">{{ $product->stock_qty }}</span>
                @else
                    <span class="badge badge-success w-fit px-3 py-1 text-xs">{{ $product->stock_qty }}</span>
                @endif
            </div>

            {{-- Actions Dropdown --}}
            <div class="stack-actions" onclick="event.stopPropagation()">
                <div class="ios-dropdown">
                    <button type="button" class="stack-action-circle">
                        <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                    </button>
                    <div class="ios-dropdown-menu">
                        <a href="{{ route('products.show', $product) }}" class="ios-dropdown-item">
                            <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                            <span>{{ __('view') }}</span>
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="ios-dropdown-item">
                            <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                            <span>{{ __('edit') }}</span>
                        </a>
                        <div class="h-px bg-gray-100 my-1"></div>
                        <button type="button" onclick="deleteRow(this)"
                            class="ios-dropdown-item ios-dropdown-item-danger">
                            <i class="ph ph-trash ios-dropdown-icon"></i>
                            <span>{{ __('general.delete') ?? 'Delete' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
            <i class="ph ph-package text-4xl mb-3"></i>
            <p class="text-lg font-medium">{{ __('products.no_products') }}</p>
        </div>
    @endforelse
</div>

{{-- ==================== GRID VIEW ==================== --}}
<div class="products-grid view-grid hidden" id="products-grid">
    @forelse($products as $product)
        <div class="product-card cursor-pointer" id="product-grid-{{ $product->id }}"
            data-product-id="{{ $product->id }}"
            onclick="window.location.href='{{ route('products.show', $product) }}'">
            {{-- Checkbox --}}
            <div class="absolute top-3 left-3 z-10" onclick="event.stopPropagation()">
                <input type="checkbox" value="{{ $product->id }}" onchange="updateBulkBar(this)"
                    class="row-checkbox checkbox-ios">
            </div>

            {{-- Image --}}
            <div class="product-card-image">
                @if ($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover">
                @else
                    <i class="ph ph-image text-gray-300 text-4xl"></i>
                @endif
            </div>

            {{-- Content --}}
            <div class="product-card-content">
                <div class="product-card-category">{{ $product->category?->localized_name ?? 'Uncategorized' }}</div>
                <h3 class="product-card-name">{{ $product->name }}</h3>
                <p class="product-card-sku">{{ $product->sku }}</p>

                <div class="product-card-footer">
                    <span class="product-card-price">฿{{ number_format($product->unit_price, 2) }}</span>
                    @if ($product->isLowStock())
                        <span class="badge badge-danger px-2 py-0.5 text-xs">{{ $product->stock_qty }}</span>
                    @else
                        <span class="badge badge-success px-2 py-0.5 text-xs">{{ $product->stock_qty }}</span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="product-card-actions" onclick="event.stopPropagation()">
                <a href="{{ route('products.show', $product) }}" class="product-card-btn" title="View">
                    <i class="ph ph-eye"></i>
                </a>
                <a href="{{ route('products.edit', $product) }}" class="product-card-btn" title="{{ __('edit') }}">
                    <i class="ph ph-pencil-simple"></i>
                </a>
            </div>
        </div>
    @empty
        <div
            class="col-span-full bg-white rounded-3xl p-12 text-center text-gray-400 border border-dashed border-gray-200">
            <i class="ph ph-package text-4xl mb-3"></i>
            <p class="text-lg font-medium">{{ __('products.no_products') }}</p>
        </div>
    @endforelse
</div>

{{-- ==================== COMPACT VIEW ==================== --}}
<div class="products-compact view-compact hidden" id="products-compact">
    <div class="compact-header">
        <div class="compact-cell compact-checkbox">
            <input type="checkbox" id="select-all-compact" class="checkbox-ios" onchange="toggleSelectAll(this)">
        </div>
        <div class="compact-cell compact-name">{{ __('products.name') }}</div>
        <div class="compact-cell compact-sku">SKU</div>
        <div class="compact-cell compact-category">{{ __('products.category') }}</div>
        <div class="compact-cell compact-price">{{ __('products.price') }}</div>
        <div class="compact-cell compact-stock">{{ __('products.stock') }}</div>
        <div class="compact-cell compact-actions"></div>
    </div>
    @forelse($products as $product)
        <div class="compact-row cursor-pointer hover:bg-gray-50" id="product-compact-{{ $product->id }}"
            data-product-id="{{ $product->id }}"
            onclick="window.location.href='{{ route('products.show', $product) }}'">
            <div class="compact-cell compact-checkbox" onclick="event.stopPropagation()">
                <input type="checkbox" value="{{ $product->id }}" onchange="updateBulkBar(this)"
                    class="row-checkbox checkbox-ios">
            </div>
            <div class="compact-cell compact-name">
                <span class="font-medium text-gray-900">{{ Str::limit($product->name, 30) }}</span>
            </div>
            <div class="compact-cell compact-sku">
                <span class="text-xs font-mono text-gray-500">{{ $product->sku }}</span>
            </div>
            <div class="compact-cell compact-category">
                <span class="text-sm text-gray-600">{{ $product->category?->localized_name ?? '-' }}</span>
            </div>
            <div class="compact-cell compact-price">
                <span class="font-bold text-ios-blue">฿{{ number_format($product->unit_price, 2) }}</span>
            </div>
            <div class="compact-cell compact-stock">
                @if ($product->isLowStock())
                    <span class="text-red-600 font-semibold">{{ $product->stock_qty }}</span>
                @else
                    <span class="text-green-600 font-semibold">{{ $product->stock_qty }}</span>
                @endif
            </div>
            <div class="compact-cell compact-actions" onclick="event.stopPropagation()">
                <a href="{{ route('products.show', $product) }}" class="compact-btn" title="View">
                    <i class="ph ph-eye"></i>
                </a>
                <a href="{{ route('products.edit', $product) }}" class="compact-btn" title="{{ __('edit') }}">
                    <i class="ph ph-pencil-simple"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl p-8 text-center text-gray-400">
            <i class="ph ph-package text-3xl mb-2"></i>
            <p>{{ __('products.no_products') }}</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div id="pagination-container" class="mt-8 flex justify-between items-center px-4">
    <div class="table-pagination-info">
        {{ __('general.showing') }}
        <span class="font-bold text-gray-900">{{ $products->firstItem() ?? 0 }}</span>
        - <span class="font-bold text-gray-900">{{ $products->lastItem() ?? 0 }}</span>
        {{ __('general.of') }}
        <span class="font-bold text-gray-900">{{ $products->total() }}</span>
        {{ __('general.items') }}
    </div>
    <div class="table-pagination-nav">
        {{ $products->links('pagination.apple') }}
    </div>
</div>

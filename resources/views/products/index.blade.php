@extends('layouts.app')

@section('title', __('products'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('inventory') }}
        </p>
        <span>{{ __('products') }}</span>
    </div>
@endsection

@section('header-actions')
    <button onclick="ProductsPage.newProduct()" data-no-loading
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('products.add_product') }}
    </button>
@endsection

@section('content')
    <div>
        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Search + Quick Nav --}}
            <div class="flex items-center gap-2">
                <div class="flex-1 max-w-sm relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" id="product-search" placeholder="{{ __('search_placeholder') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                    <button
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-ios-blue transition-colors flex items-center">
                        <i class="ph ph-barcode text-xl"></i>
                    </button>
                </div>
                {{-- Quick Navigation Buttons --}}
                <div class="flex items-center gap-1">
                    <button type="button" onclick="ProductsPage.goToFirst()" class="quick-nav-btn"
                        title="{{ __('first_item') }}">
                        <i class="ph ph-caret-double-left"></i>
                    </button>
                    <button type="button" onclick="ProductsPage.goToLatest()" class="quick-nav-btn"
                        title="{{ __('latest_item') }}">
                        <i class="ph ph-caret-double-right"></i>
                    </button>
                </div>
            </div>

            {{-- Center: Display Format Toggle (Segmented Control) --}}
            <div id="view-toggle" class="flex bg-gray-100 p-1 rounded-xl">
                <button type="button" data-view="list" onclick="ProductsPage.setView('list')"
                    class="view-toggle-btn active px-4 py-1.5 rounded-lg bg-white shadow-sm text-ios-blue font-medium flex items-center gap-2 transition">
                    <i class="ph ph-list-bullets text-lg"></i>
                    <span class="text-sm">List</span>
                </button>
                <button type="button" data-view="grid" onclick="ProductsPage.setView('grid')"
                    class="view-toggle-btn px-4 py-1.5 rounded-lg text-gray-500 hover:text-gray-700 font-medium flex items-center gap-2 transition">
                    <i class="ph ph-squares-four text-lg"></i>
                    <span class="text-sm">Grid</span>
                </button>
                <button type="button" data-view="compact" onclick="ProductsPage.setView('compact')"
                    class="view-toggle-btn px-4 py-1.5 rounded-lg text-gray-500 hover:text-gray-700 font-medium flex items-center gap-2 transition">
                    <i class="ph ph-columns text-lg"></i>
                    <span class="text-sm">Compact</span>
                </button>
            </div>

            {{-- Right: Filter --}}
            <div class="flex items-center gap-2">
                <button type="button" onclick="ProductsPage.openFilterDrawer()" data-no-loading
                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <i class="ph-bold ph-funnel text-xl"></i>
                </button>
            </div>
        </div>

        <div id="product-list-container">
            @include('products.partials.list')
        </div>
    </div>
    {{-- Filter Drawer (Slides from Right) --}}
    <div id="filter-drawer-backdrop" class="filter-drawer-backdrop hidden" onclick="ProductsPage.closeFilterDrawer()"></div>
    <div id="filter-drawer-panel" class="filter-drawer-panel">
        <div class="filter-drawer-header">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <i class="ph ph-funnel text-ios-blue"></i>
                {{ __('filter_products') }}
            </h2>
            <button type="button" onclick="ProductsPage.closeFilterDrawer()" class="filter-drawer-close">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <div class="filter-drawer-content">
            {{-- Category Filter --}}
            <div class="filter-section">
                <h3 class="filter-section-title">
                    <i class="ph ph-folders"></i>
                    {{ __('category') }}
                </h3>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" name="filter_category" value="" checked
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('all') }}</span>
                    </label>
                    @foreach ($categories as $category)
                        <label class="filter-option">
                            <input type="checkbox" name="filter_category" value="{{ $category->id }}"
                                class="checkbox-ios filter-checkbox">
                            <span>{{ $category->localized_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Drug Class Filter --}}
            <div class="filter-section">
                <h3 class="filter-section-title">
                    <i class="ph ph-first-aid-kit"></i>
                    {{ __('drug_class') }}
                </h3>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" name="filter_drug_class" value="" checked
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('all') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="filter_drug_class" value="ยาสามัญประจำบ้าน"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('otc_drug') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="filter_drug_class" value="ยาอันตราย"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('dangerous_drug') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="filter_drug_class" value="ยาควบคุมพิเศษ"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('controlled_drug') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="filter_drug_class" value="อาหารเสริม"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('supplement') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="filter_drug_class" value="เวชภัณฑ์"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('medical_supply') }}</span>
                    </label>
                </div>
            </div>

            {{-- Price Range Filter --}}
            <div class="filter-section">
                <h3 class="filter-section-title">
                    <i class="ph ph-currency-circle-dollar"></i>
                    {{ __('price_range') }}
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">{{ __('min') }}</label>
                        <input type="number" id="filter_price_min" placeholder="฿0" class="input-ios text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">{{ __('max') }}</label>
                        <input type="number" id="filter_price_max" placeholder="฿999" class="input-ios text-sm">
                    </div>
                </div>
            </div>

            {{-- Stock Status Filter --}}
            <div class="filter-section">
                <h3 class="filter-section-title">
                    <i class="ph ph-package"></i>
                    {{ __('stock_status') }}
                </h3>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="radio" name="filter_stock" value="all" checked
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('all') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="filter_stock" value="in_stock" class="checkbox-ios filter-checkbox">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            {{ __('in_stock') }}
                        </span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="filter_stock" value="low_stock"
                            class="checkbox-ios filter-checkbox">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                            {{ __('low_stock') }}
                        </span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="filter_stock" value="out_of_stock"
                            class="checkbox-ios filter-checkbox">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            {{ __('out_of_stock') }}
                        </span>
                    </label>
                </div>
            </div>

            {{-- Prescription Filter --}}
            <div class="filter-section">
                <h3 class="filter-section-title">
                    <i class="ph ph-prescription"></i>
                    {{ __('prescription') }}
                </h3>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="radio" name="filter_prescription" value="all" checked
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('all') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="filter_prescription" value="required"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('requires_prescription') }}</span>
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="filter_prescription" value="not_required"
                            class="checkbox-ios filter-checkbox">
                        <span>{{ __('no_prescription') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="filter-drawer-footer">
            <button type="button" onclick="ProductsPage.clearFilters()" data-no-loading
                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition flex items-center gap-1.5">
                <i class="ph ph-x text-sm"></i>
                {{ __('clear') }}
            </button>
            <button type="button" onclick="ProductsPage.applyFilters()" data-no-loading
                class="flex-1 px-4 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-lg transition active-scale flex items-center justify-center gap-1.5">
                <i class="ph ph-check text-sm"></i>
                {{ __('apply') }}
            </button>
        </div>
    </div>

    {{-- Product Modal --}}
    <div id="product-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'product-modal')"></div>
    <div id="product-modal-panel" class="modal-panel modal-panel-lg modal-panel-hidden">
        <div class="modal-header">
            <h2 id="product-modal-title" class="modal-title">{{ __('add_product') }}</h2>
            <button onclick="toggleModal(false, 'product-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <form id="product-form" class="modal-content max-h-[65vh] overflow-y-auto" enctype="multipart/form-data">
            @csrf

            {{-- Image Upload --}}
            <div class="flex flex-col items-center mb-5">
                <div id="image-upload-area"
                    class="relative w-32 h-32 bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-ios-blue hover:from-blue-50/50 hover:to-blue-50/30 transition-all group">
                    <input type="file" name="image" id="product-image-input" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="image-preview" class="hidden absolute inset-0 w-full h-full rounded-xl overflow-hidden">
                        <img id="image-preview-img" src="" alt="Preview" class="w-full h-full object-cover">
                        <button type="button" onclick="ProductsPage.removeImage()"
                            class="absolute top-1 right-1 w-6 h-6 bg-red-500/90 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition z-20">
                            <i class="ph-bold ph-x text-xs"></i>
                        </button>
                    </div>
                    <div id="image-placeholder"
                        class="flex flex-col items-center text-gray-400 group-hover:text-ios-blue transition">
                        <i class="ph ph-camera text-3xl mb-1"></i>
                        <span class="text-xs font-medium">{{ __('add_photo') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-2 text-[11px] text-gray-400">
                    <span class="flex items-center gap-1">
                        <i class="ph ph-check-circle text-green-500"></i>
                        {{ __('image_format') }}
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="flex items-center gap-1">
                        <i class="ph ph-check-circle text-green-500"></i>
                        {{ __('image_size') }}
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="flex items-center gap-1">
                        <i class="ph ph-check-circle text-green-500"></i>
                        {{ __('image_ratio') }}
                    </span>
                </div>
            </div>

            {{-- Basic Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <i class="ph ph-identification-card"></i>
                    <span>{{ __('basic_info') }}</span>
                </div>
                <div class="form-section-body">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">{{ __('sku') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" class="form-input" required placeholder="PRD-001">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('barcode') }}</label>
                            <input type="text" name="barcode" class="form-input" placeholder="8850123456789">
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">{{ __('product_name') }} <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" class="form-input" required placeholder="Product Name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('thai_name') }}</label>
                            <input type="text" name="name_th" class="form-input" placeholder="ชื่อภาษาไทย">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('generic_name') }}</label>
                        <input type="text" name="generic_name" class="form-input"
                            placeholder="Generic / Scientific Name">
                    </div>
                </div>
            </div>

            {{-- Classification --}}
            <div class="form-section">
                <div class="form-section-header">
                    <i class="ph ph-folders"></i>
                    <span>{{ __('classification') }}</span>
                </div>
                <div class="form-section-body">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">{{ __('category') }}</label>
                            <select name="category_id" class="form-input">
                                <option value="">{{ __('select_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->localized_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('drug_class') }}</label>
                            <select name="drug_class" class="form-input">
                                <option value="">{{ __('select_drug_class') }}</option>
                                <option value="ยาสามัญประจำบ้าน">{{ __('otc_drug') }}</option>
                                <option value="ยาอันตราย">{{ __('dangerous_drug') }}</option>
                                <option value="ยาควบคุมพิเศษ">{{ __('controlled_drug') }}</option>
                                <option value="อาหารเสริม">{{ __('supplement') }}</option>
                                <option value="เวชภัณฑ์">{{ __('medical_supply') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('manufacturer') }}</label>
                        <input type="text" name="manufacturer" class="form-input" placeholder="Manufacturer Name">
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="form-section form-section-blue">
                <div class="form-section-header">
                    <i class="ph ph-currency-circle-dollar"></i>
                    <span>{{ __('pricing_units') }}</span>
                </div>
                <div class="form-section-body">
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('cost_price') }}</label>
                            <input type="number" name="cost_price" step="0.01" class="form-input"
                                placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('sell_price') }} <span class="text-red-500">*</span></label>
                            <input type="number" name="unit_price" step="0.01" class="form-input" required
                                placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('member_price') }}</label>
                            <input type="number" name="member_price" step="0.01" class="form-input"
                                placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('base_unit') }}</label>
                            <input type="text" name="base_unit" class="form-input" value="pcs" placeholder="pcs">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('sell_unit') }}</label>
                            <input type="text" name="sell_unit" class="form-input" placeholder="box">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('conversion') }}</label>
                            <input type="number" name="conversion_factor" class="form-input" value="1"
                                placeholder="1">
                        </div>
                    </div>
                    <label class="form-toggle">
                        <input type="checkbox" name="vat_applicable" class="form-toggle-input" checked>
                        <span class="form-toggle-label">{{ __('vat_applicable') }}</span>
                    </label>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="form-section form-section-green">
                <div class="form-section-header">
                    <i class="ph ph-package"></i>
                    <span>{{ __('inventory') }}</span>
                </div>
                <div class="form-section-body">
                    <div class="form-row-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('quantity') }}</label>
                            <input type="number" name="stock_qty" class="form-input" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('min_stock') }}</label>
                            <input type="number" name="min_stock" class="form-input" value="10">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('reorder') }}</label>
                            <input type="number" name="reorder_point" class="form-input" value="10">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('max_stock') }}</label>
                            <input type="number" name="max_stock" class="form-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('shelf_location') }}</label>
                        <input type="text" name="location" class="form-input" placeholder="A1-01">
                    </div>
                </div>
            </div>

            {{-- Clinical Info --}}
            <div class="form-section form-section-orange">
                <div class="form-section-header">
                    <i class="ph ph-first-aid-kit"></i>
                    <span>{{ __('clinical_info') }}</span>
                </div>
                <div class="form-section-body">
                    <label class="form-toggle form-toggle-prescription">
                        <input type="checkbox" name="requires_prescription" id="requires_prescription"
                            class="form-toggle-input">
                        <i class="ph ph-prescription text-orange-500"></i>
                        <span
                            class="form-toggle-label text-orange-700 font-semibold">{{ __('requires_prescription') }}</span>
                    </label>
                    <div class="form-group">
                        <label class="form-label">{{ __('precautions') }}</label>
                        <textarea name="precautions" rows="2" class="form-input" placeholder="{{ __('precautions_placeholder') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('side_effects') }}</label>
                        <textarea name="side_effects" rows="2" class="form-input" placeholder="{{ __('side_effects_placeholder') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('instructions') }}</label>
                        <textarea name="default_instructions" rows="2" class="form-input"
                            placeholder="{{ __('instructions_placeholder') }}"></textarea>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal-footer">
            <button type="button" onclick="toggleModal(false, 'product-modal')"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('cancel') }}
            </button>
            <button id="product-save-btn" onclick="ProductsPage.saveProduct()" data-no-loading
                class="px-6 py-2.5 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                <i class="ph-bold ph-floppy-disk text-sm"></i>
                {{ __('save') }}
            </button>
        </div>
    </div>

    {{-- Change Category Modal --}}
    <div id="category-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'category-modal')"></div>
    <div id="category-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 24rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('change_category') }}</h2>
            <button onclick="toggleModal(false, 'category-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            <p class="text-sm text-gray-500 mb-4">Select the new category for the <span id="category-modal-count"
                    class="font-bold text-gray-900">0</span> selected items.</p>
            <div class="form-group">
                <label class="form-label">{{ __('category') }}</label>
                <select id="bulk-category-id" class="form-input">
                    <option value="">{{ __('select_category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->localized_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="toggleModal(false, 'category-modal')"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('cancel') }}
            </button>
            <button onclick="ProductsPage.executeBulkCategoryChange()" data-no-loading
                class="px-6 py-2.5 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                <i class="ph-bold ph-check text-sm"></i>
                {{ __('apply') }}
            </button>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        window.productsData = @json($products->items());
    </script>
@endpush

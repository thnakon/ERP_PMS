@extends('layouts.app')

@section('title', $category->localized_name)

@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('categories.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            {{ __('categories.back_to_categories') }}
        </a>
        <span>{{ $category->localized_name }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <button onclick="editCategoryFromShow()"
            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-xl transition flex items-center gap-2">
            <i class="ph ph-pencil"></i>
            {{ __('edit') }}
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Category Header Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex gap-6">
                {{-- Category Icon/Image --}}
                <div class="w-32 h-32 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border-4"
                    style="border-color: {{ $category->color_code ?? '#E5E7EB' }}; background-color: {{ $category->color_code ?? '#E5E7EB' }}20">
                    @if ($category->icon_path)
                        <img src="{{ asset('storage/' . $category->icon_path) }}" alt="{{ $category->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <i class="ph ph-folder text-5xl" style="color: {{ $category->color_code ?? '#9CA3AF' }}"></i>
                    @endif
                </div>

                {{-- Category Info --}}
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                            @if ($category->name_th)
                                <p class="text-lg text-gray-500 mt-1">{{ $category->name_th }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($category->is_active)
                                <span class="badge badge-success">
                                    <span class="badge-dot badge-dot-success"></span>
                                    {{ __('categories.active') }}
                                </span>
                            @else
                                <span class="badge badge-gray">
                                    <span class="badge-dot" style="background: #9ca3af"></span>
                                    {{ __('categories.inactive') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                        {{-- Parent Category --}}
                        <div>
                            <span
                                class="text-xs text-gray-400 uppercase tracking-wider">{{ __('categories.parent') }}</span>
                            <p class="font-medium text-gray-900 mt-1">
                                @if ($category->parent)
                                    <a href="{{ route('categories.show', $category->parent) }}"
                                        class="text-ios-blue hover:underline">
                                        {{ $category->parent->localized_name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">{{ __('categories.none') }}</span>
                                @endif
                            </p>
                        </div>
                        {{-- Color Code --}}
                        <div>
                            <span
                                class="text-xs text-gray-400 uppercase tracking-wider">{{ __('categories.color') }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-200"
                                    style="background-color: {{ $category->color_code ?? '#E5E7EB' }}"></div>
                                <span class="font-mono text-sm text-gray-600">{{ $category->color_code ?? '-' }}</span>
                            </div>
                        </div>
                        {{-- Sort Order --}}
                        <div>
                            <span
                                class="text-xs text-gray-400 uppercase tracking-wider">{{ __('categories.sort_order') }}</span>
                            <p class="font-medium text-gray-900 mt-1">{{ $category->sort_order ?? 0 }}</p>
                        </div>
                        {{-- Total Products --}}
                        <div>
                            <span
                                class="text-xs text-gray-400 uppercase tracking-wider">{{ __('categories.products_count') }}</span>
                            <p class="font-bold text-2xl text-ios-blue mt-1">{{ $products->total() }}</p>
                        </div>
                    </div>

                    @if ($category->description)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <span
                                class="text-xs text-gray-400 uppercase tracking-wider">{{ __('categories.description') }}</span>
                            <p class="text-gray-700 mt-1">{{ $category->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Subcategories (if any) --}}
        @if ($category->children->count() > 0)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i class="ph ph-folder-notch-open text-purple-500"></i>
                    {{ __('categories.sub_categories') }} ({{ $category->children->count() }})
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ($category->children as $child)
                        <a href="{{ route('categories.show', $child) }}"
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                style="background-color: {{ $child->color_code ?? '#E5E7EB' }}20">
                                @if ($child->icon_path)
                                    <img src="{{ asset('storage/' . $child->icon_path) }}"
                                        class="w-full h-full object-cover rounded-xl">
                                @else
                                    <i class="ph ph-folder text-lg"
                                        style="color: {{ $child->color_code ?? '#9CA3AF' }}"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate group-hover:text-ios-blue transition">
                                    {{ $child->localized_name }}</p>
                                <p class="text-xs text-gray-400">{{ $child->products_count ?? 0 }} {{ __('products') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Products in this Category --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-2">
                    <i class="ph ph-package text-blue-500"></i>
                    {{ __('categories.products_in_category') }}
                </h3>
                <span class="text-sm text-gray-400">
                    {{ __('general.showing') }}
                    <span class="font-bold text-gray-900">{{ $products->firstItem() ?? 0 }}</span>
                    - <span class="font-bold text-gray-900">{{ $products->lastItem() ?? 0 }}</span>
                    {{ __('general.of') }}
                    <span class="font-bold text-gray-900">{{ $products->total() }}</span>
                </span>
            </div>

            @if ($products->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}"
                            class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition group">
                            {{-- Product Image --}}
                            <div
                                class="w-14 h-14 bg-gray-100 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="ph ph-pill text-gray-300 text-xl"></i>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 group-hover:text-ios-blue transition truncate">
                                    {{ $product->name }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-400">{{ $product->sku }}</span>
                                    @if ($product->generic_name)
                                        <span class="text-gray-300">•</span>
                                        <span class="text-xs text-gray-400 truncate">{{ $product->generic_name }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="text-right">
                                <p class="font-bold text-ios-blue">฿{{ number_format($product->unit_price, 2) }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ __('products.stock') }}:
                                    <span
                                        class="{{ $product->isLowStock() ? 'text-red-500 font-bold' : 'text-gray-600' }}">
                                        {{ $product->stock_qty }}
                                    </span>
                                </p>
                            </div>

                            {{-- Arrow --}}
                            <div class="text-gray-300 group-hover:text-ios-blue transition">
                                <i class="ph ph-caret-right text-lg"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $products->links('pagination.apple') }}
                </div>
            @else
                <div class="p-12 text-center text-gray-400">
                    <i class="ph ph-package text-4xl mb-3"></i>
                    <p class="font-medium">{{ __('categories.no_products') }}</p>
                    <p class="text-sm mt-1">{{ __('categories.no_products_desc') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Category Modal (reuse from index) --}}
    <div id="category-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'category-modal')"></div>
    <div id="category-modal-panel" class="modal-panel modal-panel-lg modal-panel-hidden">
        <div class="modal-header">
            <h2 id="modal-title" class="modal-title">{{ __('categories.edit_category') }}</h2>
            <button onclick="toggleModal(false, 'category-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <form id="category-form" method="POST" action="{{ route('categories.update', $category) }}"
            enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Names --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                            {{ __('categories.name_en') }} *
                        </label>
                        <input type="text" name="name" id="field-name" required class="input-ios"
                            value="{{ $category->name }}" placeholder="Medicine">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                            {{ __('categories.name_th') }}
                        </label>
                        <input type="text" name="name_th" id="field-name-th" class="input-ios"
                            value="{{ $category->name_th }}" placeholder="ยารักษาโรค">
                    </div>
                </div>

                {{-- Parent & Sort --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                            {{ __('categories.parent') }}
                        </label>
                        <select name="parent_id" id="field-parent-id" class="input-ios">
                            <option value="">{{ __('categories.none') }}</option>
                            @foreach (\App\Models\Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get() as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->localized_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                            {{ __('categories.sort_order') }}
                        </label>
                        <input type="number" name="sort_order" id="field-sort-order" class="input-ios"
                            value="{{ $category->sort_order ?? 0 }}">
                    </div>
                </div>

                {{-- Color & Active --}}
                <div class="grid grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                            {{ __('categories.color') }}
                        </label>
                        <div class="flex gap-2">
                            <input type="color" name="color_code" id="field-color-code"
                                class="w-12 h-10 p-1 bg-white border border-gray-200 rounded-xl cursor-pointer"
                                value="{{ $category->color_code ?? '#007AFF' }}">
                            <input type="text" id="color-hex" class="input-ios flex-1 font-mono text-sm uppercase"
                                value="{{ $category->color_code ?? '#007AFF' }}"
                                oninput="document.getElementById('field-color-code').value = this.value">
                        </div>
                    </div>
                    <div class="pb-2">
                        <label class="apple-toggle flex items-center gap-3 cursor-pointer">
                            <div style="position: relative; width: 51px; height: 31px;">
                                <input type="checkbox" name="is_active" id="field-is-active" value="1"
                                    {{ $category->is_active ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;">
                                <span class="apple-slider"
                                    style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e5e7eb; transition: .4s; border-radius: 34px;"></span>
                                <span class="apple-knob"
                                    style="position: absolute; content: ''; height: 27px; width: 27px; left: 2px; bottom: 2px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></span>
                            </div>
                            <span
                                class="text-sm font-semibold text-gray-700 select-none">{{ __('categories.active') }}</span>
                        </label>
                        <style>
                            .apple-toggle input:checked+.apple-slider {
                                background-color: #34C759 !important;
                            }

                            .apple-toggle input:checked~.apple-knob {
                                transform: translateX(20px);
                            }
                        </style>
                    </div>
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                        {{ __('categories.icon') }}
                    </label>
                    <div class="flex items-center gap-4">
                        <div id="icon-preview"
                            class="w-20 h-20 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                            @if ($category->icon_path)
                                <img src="{{ asset('storage/' . $category->icon_path) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <i class="ph ph-image text-gray-300 text-3xl"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="field-image" class="hidden" accept="image/*"
                                onchange="previewIcon(this)">
                            <button type="button" onclick="document.getElementById('field-image').click()"
                                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition active-scale">
                                {{ __('choose_file') }}
                            </button>
                            <p class="text-[10px] text-gray-400 mt-2">SQ. Ratio recommended. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">
                        {{ __('categories.description') }}
                    </label>
                    <textarea name="description" id="field-description" rows="3" class="input-ios resize-none"
                        placeholder="Details about this group...">{{ $category->description }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="toggleModal(false, 'category-modal')"
                    class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition active-scale">
                    {{ __('cancel') }}
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-ios-blue hover:brightness-110 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition active-scale">
                    {{ __('save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function editCategoryFromShow() {
            toggleModal(true, 'category-modal');
        }

        function previewIcon(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('icon-preview').innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Color picker sync
        document.getElementById('field-color-code')?.addEventListener('input', function() {
            document.getElementById('color-hex').value = this.value;
        });
    </script>
@endpush

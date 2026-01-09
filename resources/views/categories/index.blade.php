@extends('layouts.app')

@section('title', __('categories.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('inventory') }}
        </p>
        <span>{{ __('categories.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <button onclick="openAddModal()" data-no-loading
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('categories.add_category') }}
    </button>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-folders text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('categories.main_categories') }}</span>
                </div>
                <h3 class="text-xl font-black text-blue-600">{{ $stats['main'] }}</h3>
            </div>

            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="ph-bold ph-folder-notch-open text-purple-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">{{ __('categories.sub_categories') }}</span>
                </div>
                <h3 class="text-xl font-black text-purple-600">{{ $stats['subs'] }}</h3>
            </div>

            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-green-500 uppercase tracking-wider">{{ __('categories.active') }}</span>
                </div>
                <h3 class="text-xl font-black text-green-600">{{ $stats['active'] }}</h3>
            </div>

            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="ph-bold ph-package text-gray-500 text-sm"></i>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ __('products') }}</span>
                </div>
                <h3 class="text-xl font-black text-gray-700">{{ $categories->sum('products_count') }}</h3>
            </div>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4 mb-[7px]">
            {{-- Left: Search + Quick Nav --}}
            <div class="flex items-center gap-2">
                <div class="flex-1 max-w-sm relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" id="category-search" placeholder="{{ __('categories.search_placeholder') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                </div>
                {{-- Quick Navigation Buttons --}}
                <div class="flex items-center gap-1">
                    <button type="button" onclick="CategoriesPage.goToFirst()" class="quick-nav-btn"
                        title="{{ __('first_item') }}">
                        <i class="ph ph-caret-double-left"></i>
                    </button>
                    <button type="button" onclick="CategoriesPage.goToLatest()" class="quick-nav-btn"
                        title="{{ __('latest_item') }}">
                        <i class="ph ph-caret-double-right"></i>
                    </button>
                </div>
            </div>

            {{-- Right: Sort Filter --}}
            <div class="flex items-center gap-2">
                <select id="category-sort" onchange="CategoriesPage.applySorting(this.value)"
                    class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-ios-blue/20 outline-none shadow-sm cursor-pointer">
                    <option value="sort_order">{{ __('categories.sort_by_order') }}</option>
                    <option value="name_asc">{{ __('categories.sort_by_name_asc') }}</option>
                    <option value="name_desc">{{ __('categories.sort_by_name_desc') }}</option>
                    <option value="created_asc">{{ __('categories.sort_oldest_first') }}</option>
                    <option value="created_desc">{{ __('categories.sort_newest_first') }}</option>
                </select>
            </div>
        </div>

        {{-- Selection Header --}}
        <div id="selection-header" class="flex items-center justify-between px-[2rem] py-2 mb-[5px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="select-all-categories" class="checkbox-ios"
                    onchange="CategoriesPage.toggleSelectAll(this)">
                <label for="select-all-categories" class="text-sm font-semibold text-gray-500 cursor-pointer select-none">
                    {{ __('select_all') }}
                </label>
            </div>
            <div class="text-sm font-medium text-gray-400">
                <span class="text-gray-900 font-bold">{{ $categories->total() }}</span>
                {{ __('categories.total_categories') }}
            </div>
        </div>

        {{-- Categories List --}}
        <div class="stack-container" id="categories-stack">
            @forelse($categories as $category)
                <div class="stack-item hover:bg-gray-50/50 transition-all border-l-4"
                    style="border-left-color: {{ $category->color_code ?? '#E5E7EB' }}"
                    data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}"
                    data-category-name-th="{{ $category->name_th }}" data-category-parent="{{ $category->parent_id }}"
                    data-category-desc="{{ $category->description }}" data-category-color="{{ $category->color_code }}"
                    data-category-sort="{{ $category->sort_order }}"
                    data-category-active="{{ $category->is_active ? '1' : '0' }}">

                    {{-- Checkbox --}}
                    <div class="flex items-center pr-4">
                        <input type="checkbox" value="{{ $category->id }}" onchange="CategoriesPage.updateBulkBar(this)"
                            class="row-checkbox checkbox-ios">
                    </div>

                    {{-- Icon/Image --}}
                    <div
                        class="w-12 h-12 rounded-2xl bg-gray-100 border border-gray-50 flex-shrink-0 flex items-center justify-center overflow-hidden mr-4">
                        @if ($category->icon_path)
                            <img src="{{ asset('storage/' . $category->icon_path) }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph ph-folder text-gray-400 text-xl"></i>
                        @endif
                    </div>

                    {{-- Main Info --}}
                    <div class="stack-col stack-main">
                        <span class="stack-label">{{ __('categories.name') }}</span>
                        <div class="flex items-center gap-2">
                            <span class="stack-value text-lg">{{ $category->localized_name }}</span>
                            @if ($category->parent)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-bold rounded uppercase">
                                    {{ $category->parent->localized_name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Color Tag --}}
                    <div class="stack-col stack-data hidden md:flex w-24">
                        <span class="stack-label">{{ __('categories.color') }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded-full border border-gray-200"
                                style="background-color: {{ $category->color_code ?? '#E5E7EB' }}"></div>
                            <span class="text-xs font-mono text-gray-400">{{ $category->color_code ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Products Count --}}
                    <div class="stack-col stack-data w-24">
                        <span class="stack-label text-right">{{ __('categories.products_count') }}</span>
                        <div class="flex justify-end pr-2">
                            <span
                                class="badge badge-gray px-3 py-1 font-bold text-gray-700">{{ $category->products_count }}</span>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="stack-col stack-data w-24 flex items-center justify-center">
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

                    {{-- Actions Dropdown --}}
                    <div class="stack-actions">
                        <div class="ios-dropdown">
                            <button type="button" class="stack-action-circle">
                                <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                            </button>
                            <div class="ios-dropdown-menu">
                                <a href="{{ route('categories.show', $category) }}" class="ios-dropdown-item">
                                    <i class="ph ph-eye ios-dropdown-icon text-blue-500"></i>
                                    <span>{{ __('view') }}</span>
                                </a>
                                <button type="button" onclick="editCategory({{ $category->id }})" data-no-loading
                                    class="ios-dropdown-item">
                                    <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                    <span>{{ __('edit') }}</span>
                                </button>
                                <div class="h-px bg-gray-100 my-1"></div>
                                <button type="button" onclick="CategoriesPage.deleteRow(this)"
                                    data-delete-url="{{ route('categories.destroy', $category) }}"
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
                    <i class="ph ph-folders text-4xl mb-3"></i>
                    <p class="font-medium">{{ __('no_records') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex items-center justify-between">
            <div class="text-sm font-medium text-gray-400">
                {{ __('general.showing') }}
                <span class="text-gray-900 font-bold">{{ $categories->firstItem() ?? 0 }}</span>
                - <span class="text-gray-900 font-bold">{{ $categories->lastItem() ?? 0 }}</span>
                {{ __('general.of') }}
                <span class="text-gray-900 font-bold">{{ $categories->total() }}</span>
            </div>
            <div>
                {{ $categories->links('pagination.apple') }}
            </div>
        </div>
    </div>

    {{-- Category Modal (Add/Edit) --}}
    <div id="category-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'category-modal')"></div>
    <div id="category-modal-panel" class="modal-panel modal-panel-lg modal-panel-hidden">
        <div class="modal-header">
            <h2 id="modal-title" class="modal-title">{{ __('categories.new_category') }}</h2>
            <button onclick="toggleModal(false, 'category-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <form id="category-form" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div id="method-container"></div>

            <div class="space-y-4">
                {{-- Names --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.name_en') }}
                            *</label>
                        <input type="text" name="name" id="field-name" required class="input-ios"
                            placeholder="Medicine">
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.name_th') }}</label>
                        <input type="text" name="name_th" id="field-name-th" class="input-ios"
                            placeholder="ยารักษาโรค">
                    </div>
                </div>

                {{-- Parent & Sort --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.parent') }}</label>
                        <select name="parent_id" id="field-parent-id" class="input-ios">
                            <option value="">{{ __('categories.none') }}</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->localized_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.sort_order') }}</label>
                        <input type="number" name="sort_order" id="field-sort-order" class="input-ios" value="0">
                    </div>
                </div>

                {{-- Color & Active --}}
                <div class="grid grid-cols-2 gap-4 items-end">
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.color') }}</label>
                        <div class="flex gap-2">
                            <input type="color" name="color_code" id="field-color-code"
                                class="w-12 h-10 p-1 bg-white border border-gray-200 rounded-xl cursor-pointer"
                                value="#007AFF">
                            <input type="text" id="color-hex" class="input-ios flex-1 font-mono text-sm uppercase"
                                value="#007AFF" oninput="document.getElementById('field-color-code').value = this.value">
                        </div>
                    </div>
                    <div class="pb-2">
                        <label class="apple-toggle flex items-center gap-3 cursor-pointer">
                            <div style="position: relative; width: 51px; height: 31px;">
                                <input type="checkbox" name="is_active" id="field-is-active" value="1" checked
                                    style="opacity: 0; width: 0; height: 0;">
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
                    <label
                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.icon') }}</label>
                    <div class="flex items-center gap-4">
                        <div id="icon-preview"
                            class="w-20 h-20 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                            <i class="ph ph-image text-gray-300 text-3xl"></i>
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
                    <label
                        class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('categories.description') }}</label>
                    <textarea name="description" id="field-description" rows="3" class="input-ios resize-none"
                        placeholder="Details about this group..."></textarea>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="toggleModal(false, 'category-modal')"
                    class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition active-scale">
                    {{ __('cancel') }}
                </button>
                <button type="submit" data-no-loading
                    class="flex-1 py-3 bg-ios-blue hover:brightness-110 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition active-scale">
                    {{ __('save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // CategoriesPage module
        const CategoriesPage = {
            deleteUrl: null,

            goToFirst() {
                const url = new URL(window.location);
                url.searchParams.set('sort', 'created_asc');
                window.location.href = url.toString();
            },

            goToLatest() {
                const url = new URL(window.location);
                url.searchParams.set('sort', 'created_desc');
                window.location.href = url.toString();
            },

            applySorting(value) {
                const url = new URL(window.location);
                url.searchParams.set('sort', value);
                window.location.href = url.toString();
            },

            toggleSelectAll(checkbox) {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                this.updateBulkBarVisibility();
            },

            updateBulkBar(checkbox) {
                this.updateBulkBarVisibility();
            },

            updateBulkBarVisibility() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                const bulkBar = document.getElementById('bulk-action-bar');
                if (bulkBar) {
                    if (checked.length > 0) {
                        bulkBar.classList.remove('bulk-action-bar-hidden');
                        document.getElementById('selected-count').textContent = checked.length;
                    } else {
                        bulkBar.classList.add('bulk-action-bar-hidden');
                    }
                }
            },

            deleteRow(button) {
                const url = button.dataset.deleteUrl;
                this.deleteUrl = url;

                // Use global delete modal
                if (typeof toggleModal === 'function') {
                    toggleModal(true, 'delete-modal');
                }
            },

            executeDelete() {
                if (!this.deleteUrl) return;

                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.deleteUrl;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        };

        // Make CategoriesPage available globally
        window.CategoriesPage = CategoriesPage;

        // Override executeDelete for categories page
        const originalExecuteDelete = window.executeDelete;
        window.executeDelete = function() {
            if (CategoriesPage.deleteUrl) {
                CategoriesPage.executeDelete();
            } else if (originalExecuteDelete) {
                originalExecuteDelete();
            }
        };

        function openAddModal() {
            const form = document.getElementById('category-form');

            document.getElementById('modal-title').innerText = "{{ __('categories.new_category') }}";
            form.action = "{{ route('categories.store') }}";
            document.getElementById('method-container').innerHTML = '';

            form.reset();
            document.getElementById('icon-preview').innerHTML = '<i class="ph ph-image text-gray-300 text-3xl"></i>';
            document.getElementById('field-is-active').checked = true;
            document.getElementById('field-color-code').value = "#007AFF";
            document.getElementById('color-hex').value = "#007AFF";

            toggleModal(true, 'category-modal');
        }

        function editCategory(id) {
            const item = document.querySelector(`.stack-item[data-category-id="${id}"]`);
            const form = document.getElementById('category-form');

            document.getElementById('modal-title').innerText = "{{ __('categories.edit_category') }}";
            form.action = `/categories/${id}`;
            document.getElementById('method-container').innerHTML = '@method('PUT')';

            // Fill fields
            document.getElementById('field-name').value = item.dataset.categoryName;
            document.getElementById('field-name-th').value = item.dataset.categoryNameTh;
            document.getElementById('field-parent-id').value = item.dataset.categoryParent || "";
            document.getElementById('field-description').value = item.dataset.categoryDesc;
            document.getElementById('field-color-code').value = item.dataset.categoryColor || "#007AFF";
            document.getElementById('color-hex').value = item.dataset.categoryColor || "#007AFF";
            document.getElementById('field-sort-order').value = item.dataset.categorySort;
            document.getElementById('field-is-active').checked = item.dataset.categoryActive === '1';

            // Icon preview (simplified, just show the current img if it exists)
            const currentImg = item.querySelector('img');
            if (currentImg) {
                document.getElementById('icon-preview').innerHTML =
                    `<img src="${currentImg.src}" class="w-full h-full object-cover">`;
            } else {
                document.getElementById('icon-preview').innerHTML = '<i class="ph ph-image text-gray-300 text-3xl"></i>';
            }

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

        // Search functionality
        document.getElementById('category-search')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('.stack-item').forEach(item => {
                const name = item.dataset.categoryName?.toLowerCase() || '';
                const nameTh = item.dataset.categoryNameTh?.toLowerCase() || '';
                if (name.includes(query) || nameTh.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Set initial sort value from URL
        document.addEventListener('DOMContentLoaded', function() {
            const url = new URL(window.location);
            const sort = url.searchParams.get('sort');
            if (sort) {
                const select = document.getElementById('category-sort');
                if (select) {
                    select.value = sort;
                }
            }
        });
    </script>
@endpush

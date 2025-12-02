<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Categories</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../../css/inventorys.css">
        <style>
            /* Apple-style Flash Message */
            .flash-message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 12px 24px;
                border-radius: 99px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 9999;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                font-size: 14px;
                font-weight: 500;
                color: #333;
                opacity: 0;
                transition: opacity 0.3s ease, transform 0.3s ease;
                pointer-events: none;
            }

            .flash-message.show {
                opacity: 1;
                transform: translateX(-50%) translateY(10px);
            }

            .flash-message.success i {
                color: #34c759;
            }

            .flash-message.error i {
                color: #ff3b30;
            }

            /* Apple-style Pagination */
            .people-pagination {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 24px;
                border-top: 1px solid #e5e5ea;
                /* Apple separator color */
                margin-top: auto;
                background-color: #fff;
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            .pagination-text {
                font-size: 13px;
                color: #8e8e93;
                /* Apple secondary label color */
                font-weight: 500;
            }

            .pagination-controls {
                display: flex;
                gap: 8px;
            }

            .pagination-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 1px solid #e5e5ea;
                background-color: #fff;
                color: #007aff;
                /* Apple Blue */
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
                font-size: 12px;
            }

            .pagination-btn:hover:not(.disabled) {
                background-color: #f2f2f7;
                /* Apple system gray 6 */
                border-color: #d1d1d6;
            }

            .pagination-btn.disabled {
                color: #c7c7cc;
                /* Apple tertiary label color */
                cursor: not-allowed;
                border-color: #f2f2f7;
                background-color: #fff;
            }
        </style>
    </head>

    <body>

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message">
            <i class="fa-solid fa-check-circle"></i>
            <span id="flash-text">Operation successful</span>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ session('success') }}", 'success');
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ $errors->first() }}", 'error');
                });
            </script>
        @endif

        <div class="inv-container fade-in">
            <div class="inv-breadcrumb-bar">
                Dashboard / Inventory /
                <span style="color: #3a3a3c; font-weight: 600;">Categories</span> > <a
                    href="{{ route('inventorys.expiry-management') }}" style="color: #017aff"> Expiry Management </a>
            </div>
            <div class="inv-header">
                <div class="inv-header-left">
                    <h1 class="inv-page-title">Categories <span
                            style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $categories->total() }})</span>
                    </h1>
                </div>
                <div class="inv-header-right">
                    <button class="inv-btn-primary" onclick="openNewModal()">
                        <i class="fa-solid fa-layer-group"></i> New Category
                    </button>
                </div>
            </div>

            <!-- Controls Row (Search & Bulk Actions) -->
            <div class="inv-filters-wrapper">
                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('inventorys.categories') }}" class="inv-search-form"
                    style="display: flex; gap: 10px; align-items: center;">
                    <!-- Sort Filter -->
                    <select name="sort" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                        </option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                        </option>
                    </select>

                    <!-- Group Filter -->
                    <select name="group" class="inv-form-input" style="width: 220px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="all">All Groups</option>
                        <option value="Medications (Pharmaceuticals)"
                            {{ request('group') == 'Medications (Pharmaceuticals)' ? 'selected' : '' }}>Medications
                            (Pharmaceuticals)</option>
                        <option value="Health Supplements & Wellness"
                            {{ request('group') == 'Health Supplements & Wellness' ? 'selected' : '' }}>Health
                            Supplements & Wellness</option>
                        <option value="First Aid & Wound Care"
                            {{ request('group') == 'First Aid & Wound Care' ? 'selected' : '' }}>First Aid & Wound Care
                        </option>
                        <option value="Personal Care & Hygiene"
                            {{ request('group') == 'Personal Care & Hygiene' ? 'selected' : '' }}>Personal Care &
                            Hygiene</option>
                        <option value="Medical Devices & Aids"
                            {{ request('group') == 'Medical Devices & Aids' ? 'selected' : '' }}>Medical Devices & Aids
                        </option>
                    </select>
                    <!-- Search Input -->
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Categories..." class="inv-form-input"
                            style="width: 280px; height: 44px; padding-left: 40px;">
                        <i class="fa-solid fa-magnifying-glass"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
                    </div>
                </form>

                <!-- Bulk Actions (Hidden by default) -->
                <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
                    <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span
                            id="selected-count">0</span></span>
                    <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"
                        onclick="confirmBulkDelete()"><i class="fa-solid fa-trash"></i> Delete Selected</button>
                </div>
            </div>

            <!-- Table Header -->
            <div class="inv-card-row header grid-categories"
                style="grid-template-columns: 40px 60px 2fr 3fr 1fr 1fr 130px;">
                <div class="inv-checkbox-wrapper">
                    <input type="checkbox" class="inv-checkbox" id="select-all">
                </div>
                <div class="inv-col-header">#</div>
                <div class="inv-col-header">Category Name</div>
                <div class="inv-col-header">Description</div>
                <div class="inv-col-header">Items</div>
                <div class="inv-col-header">Status</div>
                <div class="inv-col-header" style="text-align: right;">Actions</div>
            </div>

            <!-- Categories Loop -->
            @forelse($categories as $index => $category)
                <div class="inv-card-row grid-categories"
                    style="grid-template-columns: 40px 60px 2fr 3fr 1fr 1fr 130px;">
                    <div class="inv-checkbox-wrapper">
                        <input type="checkbox" class="inv-checkbox item-checkbox" data-id="{{ $category->id }}">
                    </div>
                    <div class="inv-text-sub" style="font-weight: 500;">
                        {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                    </div>
                    <div class="inv-product-info" data-label="Name">
                        <div class="inv-product-name">{{ $category->name }}</div>
                        {{-- <div class="inv-product-generic">Generic Name</div> --}}
                    </div>
                    <div class="inv-text-sub" data-label="Desc">{{ Str::limit($category->description, 50) }}</div>
                    <div class="inv-text-main" data-label="Items">{{ $category->products_count }} Items</div>
                    <div data-label="Status">
                        <span
                            class="inv-status-badge {{ strtolower($category->status) == 'active' ? 'active' : 'inactive' }}">
                            {{ $category->status }}
                        </span>
                    </div>
                    <div class="inv-action-group" data-label="Actions"
                        style="display: flex; gap: 6px; justify-content: flex-start;">
                        <button class="inv-icon-action" onclick="openViewModal({{ json_encode($category) }})"><i
                                class="fa-solid fa-eye"></i></button>
                        <button class="inv-icon-action" onclick="openEditModal({{ json_encode($category) }})"><i
                                class="fa-solid fa-pen"></i></button>
                        <button class="inv-icon-action btn-delete-row"
                            onclick="confirmDelete({{ $category->id }})"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            @empty
                <div class="inv-card-row" style="justify-content: center; padding: 20px;">
                    <span class="inv-text-sub">No categories found.</span>
                </div>
            @endforelse

            {{-- Pagination --}}
            {{ $categories->links('vendor.pagination.apple') }}

        </div>

        <!-- MODAL: New/Edit Category -->
        <div class="inv-modal-overlay" id="modal-category">
            <div class="inv-modal">
                <form id="category-form" method="POST" action="{{ route('inventorys.categories.store') }}">
                    @csrf
                    <div id="method-spoof"></div> <!-- For PUT method -->

                    <div class="inv-modal-header">
                        <div class="inv-modal-title" id="modal-title">Add Category</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeModal('modal-category')">&times;</button>
                    </div>
                    <div class="inv-modal-body">
                        <div class="inv-form-group">
                            <label class="inv-form-label">Category Name</label>
                            <input type="text" name="name" id="cat-name" class="inv-form-input"
                                placeholder="e.g. Cosmetics" required>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Description</label>
                            <textarea name="description" id="cat-desc" class="inv-form-input" rows="3"
                                placeholder="Optional details..."></textarea>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Status</label>
                            <select name="status" id="cat-status" class="inv-form-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="inv-modal-footer">
                        <button type="button" class="inv-btn-secondary"
                            onclick="closeModal('modal-category')">Cancel</button>
                        <button type="submit" class="inv-btn-primary" id="modal-submit-btn">Save Category</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Delete Confirmation -->
        <div class="inv-modal-overlay" id="modal-delete">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" style="color: #ff3b30;">Delete Category</div>
                    <button class="inv-modal-close" onclick="closeModal('modal-delete')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="delete-confirm-text">Are you sure you want to delete this category? This action cannot be
                        undone.</p>
                </div>
                <div class="inv-modal-footer">
                    <button class="inv-btn-secondary" onclick="closeModal('modal-delete')">Cancel</button>

                    {{-- Single Delete Form --}}
                    <form id="delete-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary"
                            style="background-color: #ff3b30; border-color: #ff3b30;">Delete</button>
                    </form>

                    {{-- Bulk Delete Button --}}
                    <button id="btn-bulk-delete" type="button" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; display: none;"
                        onclick="executeBulkDelete()">Delete</button>
                </div>
            </div>
        </div>

        <script src="../../js/inventorys.js"></script>
        <script>
            // --- Flash Message Logic ---
            function showFlash(message, type = 'success') {
                const flash = document.getElementById('flash-message');
                const text = document.getElementById('flash-text');
                const icon = flash.querySelector('i');

                text.textContent = message;
                flash.className = 'flash-message show ' + type;

                if (type === 'success') {
                    icon.className = 'fa-solid fa-check-circle';
                } else {
                    icon.className = 'fa-solid fa-circle-exclamation';
                }

                setTimeout(() => {
                    flash.classList.remove('show');
                }, 3000);
            }

            // --- Modal Logic ---
            function openNewModal() {
                document.getElementById('modal-title').textContent = 'Add Category';
                document.getElementById('modal-submit-btn').textContent = 'Save Category';
                document.getElementById('category-form').action = "{{ route('inventorys.categories.store') }}";
                document.getElementById('method-spoof').innerHTML = ''; // Clear PUT

                // Clear inputs
                document.getElementById('cat-name').value = '';
                document.getElementById('cat-desc').value = '';
                document.getElementById('cat-status').value = 'Active';

                openModal('modal-category');
            }

            function openEditModal(category) {
                document.getElementById('modal-title').textContent = 'Edit Category';
                document.getElementById('modal-submit-btn').textContent = 'Update Category';
                document.getElementById('category-form').action = "/inventorys/categories/" + category.id;
                document.getElementById('method-spoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // Fill inputs
                document.getElementById('cat-name').value = category.name;
                document.getElementById('cat-desc').value = category.description;
                document.getElementById('cat-status').value = category.status;

                openModal('modal-category');
            }

            function confirmDelete(id) {
                // Reset to Single Delete Mode
                document.getElementById('delete-form').style.display = 'inline';
                document.getElementById('btn-bulk-delete').style.display = 'none';
                document.getElementById('delete-confirm-text').textContent =
                    'Are you sure you want to delete this category? This action cannot be undone.';

                document.getElementById('delete-form').action = "/inventorys/categories/" + id;
                openModal('modal-delete');
            }

            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
            }

            // --- Bulk Actions Logic ---
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCountSpan = document.getElementById('selected-count');

            function updateBulkActions() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count > 0) {
                    bulkActions.style.display = 'flex';
                    selectedCountSpan.textContent = count;
                } else {
                    bulkActions.style.display = 'none';
                }
            }

            // Select All Listener
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const isChecked = this.checked;
                    document.querySelectorAll('.item-checkbox').forEach(cb => {
                        cb.checked = isChecked;
                    });
                    updateBulkActions();
                });
            }

            // Individual Checkbox Listener
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkActions();

                    // Update Select All state
                    const allChecked = document.querySelectorAll('.item-checkbox:checked').length === document
                        .querySelectorAll('.item-checkbox').length;
                    if (selectAll) selectAll.checked = allChecked;
                });
            });

            function confirmBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count === 0) return;

                // Switch to Bulk Delete Mode
                document.getElementById('delete-form').style.display = 'none';
                document.getElementById('btn-bulk-delete').style.display = 'inline-block';
                document.getElementById('delete-confirm-text').textContent =
                    `Are you sure you want to delete ${count} selected categories? This action cannot be undone.`;

                openModal('modal-delete');
            }

            function executeBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.dataset.id);

                fetch("{{ route('inventorys.categories.bulk-delete') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        closeModal('modal-delete');
                        if (data.success) {
                            showFlash(data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showFlash('Something went wrong', 'error');
                        }
                    });
            }

            function openViewModal(category) {
                document.getElementById('view-cat-name').textContent = category.name;
                document.getElementById('view-cat-group').textContent = category.group || '-';
                document.getElementById('view-cat-desc').textContent = category.description || '-';
                document.getElementById('view-cat-status').textContent = category.status;
                document.getElementById('view-cat-status').className = 'inv-status-badge ' + (category.status.toLowerCase() ===
                    'active' ? 'active' : 'inactive');

                openModal('modal-view-category');
            }
        </script>

        <!-- MODAL: View Category -->
        <div class="inv-modal-overlay" id="modal-view-category">
            <div class="inv-modal">
                <div class="inv-modal-header">
                    <div class="inv-modal-title">Category Details</div>
                    <button type="button" class="inv-modal-close"
                        onclick="closeModal('modal-view-category')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <div class="inv-form-group">
                        <label class="inv-form-label" style="color: #8e8e93;">Category Name</label>
                        <div id="view-cat-name" style="font-weight: 600; font-size: 16px; margin-top: 4px;"></div>
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label" style="color: #8e8e93;">Group</label>
                        <div id="view-cat-group" style="font-size: 15px; margin-top: 4px;"></div>
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label" style="color: #8e8e93;">Description</label>
                        <div id="view-cat-desc" style="font-size: 15px; margin-top: 4px; line-height: 1.5;"></div>
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label" style="color: #8e8e93;">Status</label>
                        <div style="margin-top: 8px;">
                            <span id="view-cat-status" class="inv-status-badge"></span>
                        </div>
                    </div>
                </div>
                <div class="inv-modal-footer">
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('modal-view-category')">Close</button>
                </div>
            </div>
        </div>
    </body>

    </html>
</x-app-layout>

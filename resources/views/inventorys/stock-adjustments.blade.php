<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Stock Adjustments</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('resources/css/inventorys.css') }}">
        <style>
            /* Type Badges */
            .badge-addition {
                background-color: #e5fbeB;
                color: #34c759;
                border: 1px solid #34c759;
            }

            .badge-subtraction {
                background-color: #fff1f0;
                color: #ff3b30;
                border: 1px solid #ff3b30;
            }

            .status-badge {
                font-size: 11px;
                padding: 4px 10px;
                border-radius: 20px;
                font-weight: 600;
                display: inline-block;
                text-transform: capitalize;
            }

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
                margin-top: auto;
                background-color: #fff;
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            .pagination-text {
                font-size: 13px;
                color: #8e8e93;
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
                border-color: #d1d1d6;
            }

            .pagination-btn.disabled {
                color: #c7c7cc;
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

        <div class="inv-container">
            <div class="inv-breadcrumb-bar">
                Dashboard / Inventory /
                <span style="color: #3a3a3c; font-weight: 600;">Stock Adjustments</span>
            </div>
            <div class="inv-header">
                <div class="inv-header-left">
                    <h1 class="inv-page-title">Stock Adjustments <span
                            style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $adjustments->total() }})</span>
                    </h1>
                </div>
                <div class="inv-header-right">
                    <button class="inv-btn-secondary"
                        onclick="window.open('{{ route('inventorys.stock-adjustments.export') }}', '_blank')">
                        <i class="fa-solid fa-file-arrow-down"></i> Export Log
                    </button>
                    <button class="inv-btn-primary" onclick="openModal('modal-add-adjustment')">
                        <i class="fa-solid fa-plus"></i> New Adjustment
                    </button>
                </div>
            </div>

            <!-- Controls Row (Search & Bulk Actions) -->
            <div class="inv-filters-wrapper">
                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('inventorys.stock-adjustments') }}" class="inv-search-form"
                    style="display: flex; gap: 10px; align-items: center;">

                    <!-- Sort Filter -->
                    <select name="sort" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>

                    <!-- Type Filter -->
                    <select name="type" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="addition" {{ request('type') == 'addition' ? 'selected' : '' }}>Addition (+)
                        </option>
                        <option value="subtraction" {{ request('type') == 'subtraction' ? 'selected' : '' }}>
                            Subtraction (-)</option>
                    </select>

                    <!-- Search Input -->
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Reason, Product, User..." class="inv-form-input"
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
            <div class="inv-card-row header grid-adjustment"
                style="display: grid; grid-template-columns: 40px 60px 2fr 1fr 1fr 1fr 1.5fr 1fr 100px; 
                       padding: 0 16px; 
                       margin-bottom: 10px; 
                       background: transparent; 
                       border: none;">
                <div class="inv-checkbox-wrapper">
                    <input type="checkbox" class="inv-checkbox" id="select-all">
                </div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Image</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Product</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Type</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Qty</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Reason</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">User</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Date</div>
                <div class="inv-col-header"
                    style="text-align: right; font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                    Actions</div>
            </div>

            <!-- Adjustments Loop -->
            @forelse($adjustments as $adj)
                <div class="inv-card-row grid-adjustment adj-row"
                    style="display: grid; grid-template-columns: 40px 60px 2fr 1fr 1fr 1fr 1.5fr 1fr 100px; 
                           background: #fff; 
                           border-radius: 22px; 
                           margin-bottom: 8px; 
                           padding: 16px 16px;
                           box-shadow: 0 2px 6px rgba(0,0,0,0.02);
                           border: 1px solid #f5f5f7;
                           transition: all 0.2s ease;
                           align-items: center;"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.02)'">

                    <div class="inv-checkbox-wrapper">
                        <input type="checkbox" class="inv-checkbox item-checkbox" data-id="{{ $adj->id }}">
                    </div>

                    <!-- Image -->
                    <div class="inv-product-image" style="display: flex; align-items: center; justify-content: center;">
                        @if ($adj->product && $adj->product->image_path)
                            <img src="{{ asset($adj->product->image_path) }}" alt="{{ $adj->product->name }}"
                                style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e5ea;">
                        @else
                            <div
                                style="width: 40px; height: 40px; border-radius: 8px; background-color: #f2f2f7; display: flex; align-items: center; justify-content: center; color: #8e8e93;">
                                <i class="fa-solid fa-image" style="font-size: 16px;"></i>
                            </div>
                        @endif
                    </div>

                    <div class="inv-product-info">
                        <div class="inv-product-name" style="font-size: 14px; font-weight: 600; color: #1d1d1f;">
                            {{ $adj->product->name ?? 'Unknown Product' }}
                        </div>
                    </div>

                    <div>
                        <span class="status-badge badge-{{ $adj->type }}">
                            {{ $adj->type == 'addition' ? '+ Addition' : '- Subtraction' }}
                        </span>
                    </div>

                    <div class="inv-text-main" style="font-size: 13px; font-weight: 600; color: #1d1d1f;">
                        {{ $adj->quantity }}
                    </div>

                    <div class="inv-text-sub" style="font-size: 13px; color: #424245;">
                        {{ $adj->reason }}
                    </div>

                    <div class="inv-text-sub" style="font-size: 13px; color: #424245;">
                        {{ $adj->user->name ?? 'Unknown' }}
                    </div>

                    <div class="inv-text-sub" style="font-size: 13px; color: #86868b;">
                        {{ $adj->created_at->format('d/m/Y H:i') }}
                    </div>

                    <div class="inv-action-group"
                        style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                        <!-- Edit Button -->
                        <button class="inv-icon-action" title="Edit"
                            onclick="openEditModal({{ json_encode($adj) }})"
                            style="color: #86868b; background: none; border: none; cursor: pointer;">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <!-- Delete Button -->
                        <button class="inv-icon-action" title="Delete" onclick="confirmDelete({{ $adj->id }})"
                            style="color: #86868b; background: none; border: none; cursor: pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="inv-card-row"
                    style="justify-content: center; padding: 40px; background: #fff; border-radius: 12px; margin-bottom: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <div style="text-align: center;">
                        <i class="fa-solid fa-clipboard-list"
                            style="font-size: 48px; color: #e5e5ea; margin-bottom: 16px;"></i>
                        <div class="inv-text-sub" style="font-size: 16px;">No adjustments found</div>
                    </div>
                </div>
            @endforelse

            {{-- Pagination --}}
            {{ $adjustments->links('vendor.pagination.apple') }}

        </div>

        <!-- MODAL: Add Adjustment -->
        <div class="inv-modal-overlay" id="modal-add-adjustment">
            <div class="inv-modal" style="max-width: 500px;">
                <form method="POST" action="{{ route('inventorys.stock-adjustments.store') }}">
                    @csrf
                    <div class="inv-modal-header">
                        <div class="inv-modal-title">New Stock Adjustment</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeModal('modal-add-adjustment')">&times;</button>
                    </div>
                    <div class="inv-modal-body">
                        <div class="inv-form-group">
                            <label class="inv-form-label">Product</label>
                            <select name="product_id" class="inv-form-input" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="inv-form-row" style="gap: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label">Type</label>
                                <select name="type" class="inv-form-input" required>
                                    <option value="addition">Addition (+)</option>
                                    <option value="subtraction">Subtraction (-)</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label">Quantity</label>
                                <input type="number" name="quantity" class="inv-form-input" min="1"
                                    required>
                            </div>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Reason</label>
                            <select name="reason" class="inv-form-input" required>
                                <option value="Inventory Count">Inventory Count</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Theft/Loss">Theft/Loss</option>
                                <option value="Internal Use">Internal Use</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Note</label>
                            <textarea name="note" class="inv-form-input" rows="3" placeholder="Optional details..."></textarea>
                        </div>
                    </div>
                    <div class="inv-modal-footer">
                        <button type="button" class="inv-btn-secondary"
                            onclick="closeModal('modal-add-adjustment')">Cancel</button>
                        <button type="submit" class="inv-btn-primary">Save Adjustment</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Adjustment -->
        <div class="inv-modal-overlay" id="modal-edit-adjustment">
            <div class="inv-modal" style="max-width: 500px;">
                <form id="edit-adjustment-form" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="inv-modal-header">
                        <div class="inv-modal-title">Edit Adjustment</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeModal('modal-edit-adjustment')">&times;</button>
                    </div>
                    <div class="inv-modal-body">
                        <div class="inv-form-group">
                            <label class="inv-form-label">Product</label>
                            <select name="product_id" id="edit-product-id" class="inv-form-input" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="inv-form-row" style="gap: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label">Type</label>
                                <select name="type" id="edit-type" class="inv-form-input" required>
                                    <option value="addition">Addition (+)</option>
                                    <option value="subtraction">Subtraction (-)</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label">Quantity</label>
                                <input type="number" name="quantity" id="edit-quantity" class="inv-form-input"
                                    min="1" required>
                            </div>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Reason</label>
                            <select name="reason" id="edit-reason" class="inv-form-input" required>
                                <option value="Inventory Count">Inventory Count</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Theft/Loss">Theft/Loss</option>
                                <option value="Internal Use">Internal Use</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Note</label>
                            <textarea name="note" id="edit-note" class="inv-form-input" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="inv-modal-footer">
                        <button type="button" class="inv-btn-secondary"
                            onclick="closeModal('modal-edit-adjustment')">Cancel</button>
                        <button type="submit" class="inv-btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Confirmation (Delete) -->
        <div class="inv-modal-overlay" id="modal-confirm">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" id="confirm-title" style="color: #ff3b30;">Delete Record</div>
                    <button type="button" class="inv-modal-close"
                        onclick="closeModal('modal-confirm')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="confirm-text">Are you sure you want to delete this record?</p>
                </div>
                <div class="inv-modal-footer">
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('modal-confirm')">Cancel</button>

                    <form id="confirm-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary" id="confirm-btn"
                            style="background-color: #ff3b30; border-color: #ff3b30;">Delete</button>
                    </form>

                    {{-- Bulk Delete Button --}}
                    <button id="btn-bulk-confirm" type="button" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; display: none;"
                        onclick="executeBulkDelete()">Delete</button>
                </div>
            </div>
        </div>

        <script>
            // --- Flash Message ---
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
            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
            }

            function openEditModal(adj) {
                document.getElementById('edit-adjustment-form').action = "/inventorys/stock-adjustments/" + adj.id;
                document.getElementById('edit-product-id').value = adj.product_id;
                document.getElementById('edit-type').value = adj.type;
                document.getElementById('edit-quantity').value = adj.quantity;
                document.getElementById('edit-reason').value = adj.reason;
                document.getElementById('edit-note').value = adj.note;
                openModal('modal-edit-adjustment');
            }

            function confirmDelete(id) {
                const form = document.getElementById('confirm-form');
                const title = document.getElementById('confirm-title');
                const text = document.getElementById('confirm-text');
                const btn = document.getElementById('confirm-btn');

                // Reset Bulk Button
                document.getElementById('btn-bulk-confirm').style.display = 'none';
                form.style.display = 'inline';

                form.action = "/inventorys/stock-adjustments/" + id;
                title.textContent = 'Delete Record';
                text.textContent = 'Are you sure you want to delete this adjustment record?';

                openModal('modal-confirm');
            }

            // --- Bulk Actions ---
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

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const isChecked = this.checked;
                    document.querySelectorAll('.item-checkbox').forEach(cb => {
                        cb.checked = isChecked;
                    });
                    updateBulkActions();
                });
            }

            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkActions();
                    const allChecked = document.querySelectorAll('.item-checkbox:checked').length === document
                        .querySelectorAll('.item-checkbox').length;
                    if (selectAll) selectAll.checked = allChecked;
                });
            });

            function confirmBulkDelete() {
                const title = document.getElementById('confirm-title');
                const text = document.getElementById('confirm-text');
                const form = document.getElementById('confirm-form');
                const bulkBtn = document.getElementById('btn-bulk-confirm');

                title.textContent = 'Delete Selected Records';
                text.textContent =
                    `Are you sure you want to delete ${document.querySelectorAll('.item-checkbox:checked').length} selected records?`;

                form.style.display = 'none'; // Hide single delete form
                bulkBtn.style.display = 'inline-block'; // Show bulk button

                openModal('modal-confirm');
            }

            function executeBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.dataset.id);

                if (ids.length === 0) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const btn = document.getElementById('btn-bulk-confirm');
                const originalText = btn.textContent;

                btn.disabled = true;
                btn.textContent = 'Deleting...';

                fetch('{{ route('inventorys.stock-adjustments.bulk_destroy') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(async res => {
                        if (!res.ok) throw new Error(await res.text());
                        return res.json();
                    })
                    .then(data => {
                        closeModal('modal-confirm');
                        if (data.success) {
                            showFlash(data.message, 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showFlash(data.message, 'error');
                            btn.disabled = false;
                            btn.textContent = originalText;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        closeModal('modal-confirm');
                        showFlash('An error occurred', 'error');
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            }
        </script>
    </body>

    </html>
</x-app-layout>

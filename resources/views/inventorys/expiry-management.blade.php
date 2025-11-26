<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Expiry Management</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('resources/css/inventorys.css') }}">
        <style>
            /* Status Badges */
            .badge-expired {
                background-color: #fff1f0;
                color: #ff3b30;
                border: 1px solid #ff3b30;
            }

            .badge-near-expiry {
                background-color: #fff8e6;
                color: #ff9500;
                border: 1px solid #ff9500;
            }

            .badge-good {
                background-color: #e5fbeB;
                color: #34c759;
                border: 1px solid #34c759;
            }

            .status-badge {
                font-size: 11px;
                padding: 4px 10px;
                border-radius: 20px;
                font-weight: 600;
                display: inline-block;
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
                <span style="color: #3a3a3c; font-weight: 600;">Expiry Management</span>
            </div>
            <div class="inv-header">
                <div class="inv-header-left">
                    <h1 class="inv-page-title">Expiry Management <span
                            style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $batches->total() }})</span>
                    </h1>
                </div>
            </div>

            <!-- Controls Row (Search & Bulk Actions) -->
            <div class="inv-filters-wrapper">
                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('inventorys.expiry-management') }}" class="inv-search-form"
                    style="display: flex; gap: 10px; align-items: center;">

                    <!-- Sort Filter -->
                    <select name="sort" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="exp_asc" {{ request('sort') == 'exp_asc' ? 'selected' : '' }}>Expiry (Earliest)
                        </option>
                        <option value="exp_desc" {{ request('sort') == 'exp_desc' ? 'selected' : '' }}>Expiry (Latest)
                        </option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="near_expiry" {{ request('status') == 'near_expiry' ? 'selected' : '' }}>Near
                            Expiry (3 Months)</option>
                        <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Good</option>
                    </select>

                    <!-- Search Input -->
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Product, Batch No..." class="inv-form-input"
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
            <div class="inv-card-row header grid-expiry"
                style="display: grid; grid-template-columns: 40px 60px 2fr 1.5fr 1.5fr 1fr 1fr 150px; 
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
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Batch No.
                </div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Expiry Date
                </div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Status</div>
                <div class="inv-col-header"
                    style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Qty</div>
                <div class="inv-col-header"
                    style="text-align: right; font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                    Actions</div>
            </div>

            <!-- Batches Loop -->
            @forelse($batches as $batch)
                @php
                    $today = \Carbon\Carbon::now();
                    $expiry = \Carbon\Carbon::parse($batch->expiry_date);
                    $diff = $today->diffInDays($expiry, false);

                    if ($diff < 0) {
                        $status = 'Expired';
                        $statusClass = 'badge-expired';
                    } elseif ($diff <= 90) {
                        $status = 'Near Expiry';
                        $statusClass = 'badge-near-expiry';
                    } else {
                        $status = 'Good';
                        $statusClass = 'badge-good';
                    }
                @endphp

                <div class="inv-card-row grid-expiry batch-row"
                    style="display: grid; grid-template-columns: 40px 60px 2fr 1.5fr 1.5fr 1fr 1fr 150px; 
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
                        <input type="checkbox" class="inv-checkbox item-checkbox" data-id="{{ $batch->id }}">
                    </div>

                    <!-- Image -->
                    <div class="inv-product-image" style="display: flex; align-items: center; justify-content: center;">
                        @if ($batch->product && $batch->product->image_path)
                            <img src="{{ asset($batch->product->image_path) }}" alt="{{ $batch->product->name }}"
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
                            {{ $batch->product->name ?? 'Unknown Product' }}
                        </div>
                    </div>

                    <div class="inv-text-sub" style="font-size: 13px; color: #424245;">
                        {{ $batch->batch_number }}
                    </div>

                    <div class="inv-text-sub" style="font-size: 13px; color: #1d1d1f; font-weight: 500;">
                        {{ $expiry->format('d/m/Y') }}
                        <span style="font-size: 11px; color: #86868b; display: block;">
                            {{ $diff < 0 ? abs(floor($diff)) . ' days ago' : floor($diff) . ' days left' }}
                        </span>
                    </div>

                    <div>
                        <span class="status-badge {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div class="inv-text-main" style="font-size: 13px; font-weight: 600; color: #1d1d1f;">
                        {{ $batch->quantity }}
                    </div>

                    <div class="inv-action-group"
                        style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                        <!-- Return Button -->
                        <button class="inv-icon-action" title="Return to Supplier"
                            onclick="confirmAction({{ $batch->id }}, 'return')"
                            style="color: #86868b; background: none; border: none; cursor: pointer;">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>

                        <!-- Write-off Button -->
                        <button class="inv-icon-action" title="Write-off"
                            onclick="confirmAction({{ $batch->id }}, 'write-off')"
                            style="color: #86868b; background: none; border: none; cursor: pointer;">
                            <i class="fa-solid fa-ban"></i>
                        </button>

                        <!-- Edit Button -->
                        <button class="inv-icon-action" title="Edit"
                            onclick="openEditModal({{ json_encode($batch) }})"
                            style="color: #86868b; background: none; border: none; cursor: pointer;">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <!-- Delete Button -->
                        <button class="inv-icon-action" title="Delete"
                            onclick="confirmAction({{ $batch->id }}, 'delete')"
                            style="color: #86868b; background: none; border: none; cursor: pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="inv-card-row"
                    style="justify-content: center; padding: 40px; background: #fff; border-radius: 12px; margin-bottom: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <div style="text-align: center;">
                        <i class="fa-solid fa-box-open"
                            style="font-size: 48px; color: #e5e5ea; margin-bottom: 16px;"></i>
                        <div class="inv-text-sub" style="font-size: 16px;">No batches found</div>
                    </div>
                </div>
            @endforelse

            {{-- Pagination --}}
            {{ $batches->links('vendor.pagination.apple') }}

        </div>

        <!-- MODAL: Edit Batch -->
        <div class="inv-modal-overlay" id="modal-edit-batch">
            <div class="inv-modal" style="max-width: 500px;">
                <form id="edit-batch-form" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="inv-modal-header">
                        <div class="inv-modal-title">Edit Batch</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeModal('modal-edit-batch')">&times;</button>
                    </div>
                    <div class="inv-modal-body">
                        <div class="inv-form-group">
                            <label class="inv-form-label">Batch Number</label>
                            <input type="text" id="edit-batch-number" class="inv-form-input" disabled
                                style="background-color: #f5f5f7;">
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" id="edit-expiry-date" class="inv-form-input"
                                required>
                        </div>
                        <div class="inv-form-group">
                            <label class="inv-form-label">Quantity</label>
                            <input type="number" name="quantity" id="edit-quantity" class="inv-form-input"
                                min="0" required>
                        </div>
                        <div class="inv-form-row" style="gap: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label">Cost Price</label>
                                <input type="number" step="0.01" name="cost_price" id="edit-cost-price"
                                    class="inv-form-input" required>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label">Selling Price</label>
                                <input type="number" step="0.01" name="selling_price" id="edit-selling-price"
                                    class="inv-form-input" required>
                            </div>
                        </div>
                    </div>
                    <div class="inv-modal-footer">
                        <button type="button" class="inv-btn-secondary"
                            onclick="closeModal('modal-edit-batch')">Cancel</button>
                        <button type="submit" class="inv-btn-primary">Update Batch</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Confirmation (Delete/Return/Write-off) -->
        <div class="inv-modal-overlay" id="modal-confirm">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" id="confirm-title" style="color: #ff3b30;">Confirm Action</div>
                    <button type="button" class="inv-modal-close"
                        onclick="closeModal('modal-confirm')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="confirm-text">Are you sure?</p>
                </div>
                <div class="inv-modal-footer">
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('modal-confirm')">Cancel</button>

                    <form id="confirm-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary" id="confirm-btn"
                            style="background-color: #ff3b30; border-color: #ff3b30;">Confirm</button>
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

            function openEditModal(batch) {
                document.getElementById('edit-batch-form').action = "/inventorys/batches/" + batch.id;
                document.getElementById('edit-batch-number').value = batch.batch_number;
                document.getElementById('edit-expiry-date').value = batch.expiry_date.split('T')[0];
                document.getElementById('edit-quantity').value = batch.quantity;
                document.getElementById('edit-cost-price').value = batch.cost_price;
                document.getElementById('edit-selling-price').value = batch.selling_price;
                openModal('modal-edit-batch');
            }

            function confirmAction(id, action) {
                const form = document.getElementById('confirm-form');
                const title = document.getElementById('confirm-title');
                const text = document.getElementById('confirm-text');
                const btn = document.getElementById('confirm-btn');

                // Reset Bulk Button
                document.getElementById('btn-bulk-confirm').style.display = 'none';
                form.style.display = 'inline';

                form.action = "/inventorys/batches/" + id;

                if (action === 'delete') {
                    title.textContent = 'Delete Batch';
                    text.textContent = 'Are you sure you want to delete this batch? This action cannot be undone.';
                    btn.textContent = 'Delete';
                } else if (action === 'return') {
                    title.textContent = 'Return to Supplier';
                    text.textContent =
                        'Are you sure you want to return this batch to the supplier? This will remove it from inventory.';
                    btn.textContent = 'Return & Remove';
                } else if (action === 'write-off') {
                    title.textContent = 'Write-off Batch';
                    text.textContent =
                        'Are you sure you want to write-off this batch (e.g. expired/damaged)? This will remove it from inventory.';
                    btn.textContent = 'Write-off & Remove';
                }

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

                title.textContent = 'Delete Selected Batches';
                text.textContent =
                    `Are you sure you want to delete ${document.querySelectorAll('.item-checkbox:checked').length} selected batches?`;

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

                fetch('{{ route('inventorys.batches.bulk_destroy') }}', {
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

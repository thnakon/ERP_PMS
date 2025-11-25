<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders - Pharmacy ERP</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Main Purchasing CSS -->
    <link rel="stylesheet" href="{{ asset('resources/css/purchasing.css') }}">
    <style>
        /* Custom styles for PO Item Table in Modal */
        .po-item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .po-item-table th,
        .po-item-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .po-item-table th {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .po-item-row input,
        .po-item-row select {
            width: 100%;
            padding: 8px;
            border: 1px solid #e5e5ea;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .po-remove-btn {
            color: #ff3b30;
            background: none;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<x-app-layout>
    <!-- Main Page Container -->
    <div class="purchasing-page-container">

        {{-- Header --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / Purchasing / <span
                        style="color: #3a3a3c; font-weight: 600;">Purchase Orders</span> > <a
                        href="{{ route('purchasing.goodsReceived') }}" style="color: #017aff">Goods Received</a></p>
                <h2 class="sr-page-title">Purchase Orders <span
                        style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $purchaseOrders->total() }})</span>
                </h2>
            </div>
            <div class="sr-header-right" style="display: flex; align-items: center; gap: 12px;">

                <!-- Bulk Actions -->
                <div id="bulk-actions"
                    style="display: none; align-items: center; gap: 8px; margin-right: 12px; padding-right: 12px; border-right: 1px solid #d2d2d7;">
                    <span class="inv-text-sub">Selected: <span id="selected-count"
                            style="color: #1d1d1f; font-weight: 700;">0</span></span>

                    <button class="inv-btn-secondary" id="btn-bulk-delete-po-trigger"
                        style="color: #ff3b30; background-color: #fff1f0;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>

                <button class="purchasing-button-primary" id="open-po-modal" style="margin-top: 20px">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create New PO</span>
                </button>
            </div>
        </div>

        <!-- Action Bar with Search & Filter -->
        <div class="purchasing-action-bar">
            <!-- Search -->
            <form action="{{ route('purchasing.purchaseOrders') }}" method="GET" class="purchasing-search-bar">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by PO Number or Supplier...">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            </form>

            <!-- Sliding Filter -->
            <div class="sliding-toggle-filter" id="po-status-filter" style="margin-bottom: 15px">
                <button class="toggle-btn {{ !request('status') ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('purchasing.purchaseOrders') }}'">All</button>
                <button class="toggle-btn {{ request('status') == 'draft' ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('purchasing.purchaseOrders', ['status' => 'draft']) }}'">Draft</button>
                <button class="toggle-btn {{ request('status') == 'ordered' ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('purchasing.purchaseOrders', ['status' => 'ordered']) }}'">Ordered</button>
                <button class="toggle-btn {{ request('status') == 'completed' ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('purchasing.purchaseOrders', ['status' => 'completed']) }}'">Completed</button>
                <button class="toggle-btn {{ request('status') == 'cancelled' ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('purchasing.purchaseOrders', ['status' => 'cancelled']) }}'">Cancelled</button>
            </div>
        </div>

        <!-- List View -->
        <main class="content-area" id="po-list">
            <div class="purchasing-list-container" id="po-list-container">
                <!-- Header Row -->
                <div class="purchasing-list-row header-row">
                    <div class="col-header">
                        <div class="inv-checkbox" id="select-all-checkbox"></div>
                    </div>
                    <div class="col-header">PO Number</div>
                    <div class="col-header">Supplier</div>
                    <div class="col-header">Date Ordered</div>
                    <div class="col-header">Total Cost</div>
                    <div class="col-header">Status</div>
                    <div class="col-header" style="text-align: center;">Actions</div>
                </div>

                {{-- Dynamic Rows --}}
                @forelse($purchaseOrders as $po)
                    <div class="purchasing-list-row po-item">
                        <div class="col-checkbox">
                            <div class="inv-checkbox item-checkbox" data-id="{{ $po->id }}"></div>
                        </div>
                        <div class="col-po-number" data-label="PO Number">{{ $po->reference_number }}</div>
                        <div class="col-supplier" data-label="Supplier">{{ $po->supplier->name ?? 'Unknown' }}</div>
                        <div class="col-date" data-label="Date Ordered">{{ $po->purchase_date->format('d/m/Y') }}</div>
                        <div class="col-cost" data-label="Total Cost">฿{{ number_format($po->total_amount, 2) }}</div>
                        <div class="col-status" data-label="Status">
                            @php
                                $statusClass = match ($po->status) {
                                    'draft' => 'status-draft',
                                    'ordered' => 'status-sent', // Map ordered to sent style
                                    'completed' => 'status-completed',
                                    'cancelled' => 'status-cancelled',
                                    default => 'status-draft',
                                };
                                $statusLabel = ucfirst($po->status);
                                if ($po->status == 'ordered') {
                                    $statusLabel = 'Ordered';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <div class="col-actions" data-label="Actions"
                            style="display: flex; justify-content: center; gap: 8px;">
                            <!-- Always show View, Edit, Delete -->
                            <button class="purchasing-icon-button btn-view-po" title="View"
                                data-po='@json($po)' data-items='@json($po->items)'>
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <button class="purchasing-icon-button btn-edit-po" title="Edit"
                                data-po='@json($po)' data-items='@json($po->items)'>
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <button class="purchasing-icon-button btn-delete-po" title="Delete"
                                data-id="{{ $po->id }}">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="purchasing-list-row"
                        style="display: flex; justify-content: center; align-items: center; padding: 40px;">
                        <div style="text-align: center; color: var(--text-secondary);">
                            <i class="fa-solid fa-file-invoice"
                                style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <p>No purchase orders found.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $purchaseOrders->appends(request()->query())->links() }}
            </div>
        </main>

        <!-- [!!! CREATE/EDIT PO MODAL !!!] -->
        <div class="inv-modal-overlay" id="po-modal-overlay">
            <div class="inv-modal" style="max-width: 800px;">
                <form id="po-form" method="POST" action="{{ route('purchasing.purchaseOrders.store') }}">
                    @csrf
                    <div id="po-method-spoof"></div>

                    <div class="inv-modal-header">
                        <div class="inv-modal-title" id="po-modal-title">Create New PO</div>
                        <button type="button" class="inv-modal-close" id="close-po-modal-btn">&times;</button>
                    </div>

                    <div class="inv-modal-body">
                        <div class="inv-form-group">
                            <label class="inv-form-label">Select Supplier <span
                                    style="color: var(--required-star);">*</span></label>
                            <select id="po_supplier" name="supplier_id" class="inv-form-input" required>
                                <option value="" disabled selected>-- Choose Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="inv-form-row">
                            <div class="inv-form-group">
                                <label class="inv-form-label">Order Date <span
                                        style="color: var(--required-star);">*</span></label>
                                <input type="date" id="po_date" name="purchase_date" class="inv-form-input"
                                    required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="inv-form-group">
                                <label class="inv-form-label">Status</label>
                                <select id="po_status" name="status" class="inv-form-input">
                                    <option value="draft">Draft</option>
                                    <option value="ordered">Ordered</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Item Selection -->
                        <div class="inv-form-group">
                            <label class="inv-form-label">Order Items</label>
                            <div style="background: #f9f9f9; padding: 16px; border-radius: 12px;">
                                <table class="po-item-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">Product</th>
                                            <th style="width: 20%;">Qty</th>
                                            <th style="width: 25%;">Cost (฿)</th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="po-items-body">
                                        <!-- Dynamic Rows Here -->
                                    </tbody>
                                </table>
                                <button type="button" class="inv-btn-secondary" id="add-item-btn"
                                    style="margin-top: 12px; width: 100%;">
                                    <i class="fa-solid fa-plus"></i> Add Item
                                </button>
                                <div style="text-align: right; margin-top: 16px; font-weight: 700; font-size: 1.1rem;">
                                    Total: <span id="po-total-display">฿0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="inv-modal-footer">
                        <button type="button" class="inv-btn-secondary" id="cancel-po-modal-btn">Cancel</button>
                        <button type="submit" class="inv-btn-primary" id="save-po-btn">Save Purchase Order</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="inv-modal-overlay" id="delete-po-modal-overlay">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" style="color: #ff3b30;">Delete Purchase Order</div>
                    <button type="button" class="inv-modal-close" id="close-delete-po-modal-btn">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="delete-po-confirm-text" style="color: var(--text-secondary); margin: 0;">Are you sure you
                        want to delete this PO? This action cannot be undone.</p>
                </div>
                <div class="inv-modal-footer">
                    <button type="button" class="inv-btn-secondary" id="cancel-delete-po-btn">Cancel</button>

                    {{-- Single Delete Form --}}
                    <form id="delete-po-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary"
                            style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none;">Delete</button>
                    </form>

                    {{-- Bulk Delete Button (Hidden by default) --}}
                    <button id="btn-confirm-bulk-delete-po" type="button" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none; display: none;">Delete</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Notification Container -->
    <div id="notification-container"
        style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; display: flex; flex-direction: column; align-items: center;">
    </div>

    <!-- Pass Products Data to JS -->
    <script>
        window.productsData = @json($products);

        // Flash Message Logic
        document.addEventListener('DOMContentLoaded', () => {
            const showNotification = (message, type = 'success') => {
                const container = document.getElementById('notification-container');
                const notification = document.createElement('div');
                notification.className = `inv-notification ${type}`;
                notification.style.cssText = `
                    background: white;
                    color: #1d1d1f;
                    padding: 12px 24px;
                    border-radius: 14px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                    margin-bottom: 10px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
                    font-size: 14px;
                    font-weight: 500;
                    opacity: 0;
                    transform: translateY(-20px);
                    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                `;

                const icon = type === 'success' ?
                    '<i class="fa-solid fa-circle-check" style="color: #34c759; font-size: 18px;"></i>' :
                    '<i class="fa-solid fa-circle-exclamation" style="color: #ff3b30; font-size: 18px;"></i>';

                notification.innerHTML = `${icon}<span>${message}</span>`;

                container.appendChild(notification);

                // Animate In
                requestAnimationFrame(() => {
                    notification.style.opacity = '1';
                    notification.style.transform = 'translateY(0)';
                });

                // Remove after 3s
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            };

            @if (session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif

            @if (session('error'))
                showNotification("{{ session('error') }}", 'error');
            @endif

            // --- PO Delete Modal Logic ---
            const deletePoModal = document.getElementById('delete-po-modal-overlay');
            const closeDeletePoBtn = document.getElementById('close-delete-po-modal-btn');
            const cancelDeletePoBtn = document.getElementById('cancel-delete-po-btn');
            const deletePoForm = document.getElementById('delete-po-form');
            const deletePoConfirmText = document.getElementById('delete-po-confirm-text');
            const btnConfirmBulkDeletePo = document.getElementById('btn-confirm-bulk-delete-po');

            const openDeletePoModal = () => {
                if (deletePoModal) deletePoModal.classList.add('show');
            };

            const closeDeletePoModal = () => {
                if (deletePoModal) deletePoModal.classList.remove('show');
            };

            if (closeDeletePoBtn) closeDeletePoBtn.addEventListener('click', closeDeletePoModal);
            if (cancelDeletePoBtn) cancelDeletePoBtn.addEventListener('click', closeDeletePoModal);
            if (deletePoModal) {
                deletePoModal.addEventListener('click', (e) => {
                    if (e.target === deletePoModal) closeDeletePoModal();
                });
            }

            // Single Delete
            document.querySelectorAll('.btn-delete-po').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    if (deletePoForm) {
                        deletePoForm.style.display = 'inline';
                        deletePoForm.action = `/purchasing/purchase-orders/${id}`;
                    }
                    if (btnConfirmBulkDeletePo) btnConfirmBulkDeletePo.style.display = 'none';
                    if (deletePoConfirmText) deletePoConfirmText.textContent =
                        'Are you sure you want to delete this PO? This action cannot be undone.';

                    openDeletePoModal();
                });
            });

            // Bulk Delete Trigger
            const bulkDeleteTrigger = document.getElementById('btn-bulk-delete-po-trigger');

            if (bulkDeleteTrigger) {
                bulkDeleteTrigger.addEventListener('click', function() {
                    const checked = document.querySelectorAll('.item-checkbox.active');
                    const count = checked.length;

                    if (count === 0) return;

                    if (deletePoForm) deletePoForm.style.display = 'none';
                    if (btnConfirmBulkDeletePo) btnConfirmBulkDeletePo.style.display = 'inline-block';
                    if (deletePoConfirmText) deletePoConfirmText.textContent =
                        `Are you sure you want to delete ${count} selected orders? This action cannot be undone.`;

                    openDeletePoModal();
                });
            }

            // Execute Bulk Delete
            if (btnConfirmBulkDeletePo) {
                btnConfirmBulkDeletePo.addEventListener('click', function() {
                    const checked = document.querySelectorAll('.item-checkbox.active');
                    const ids = Array.from(checked).map(cb => cb.dataset.id);

                    if (ids.length === 0) return;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    const originalText = this.textContent;
                    this.disabled = true;
                    this.textContent = 'Deleting...';

                    fetch('{{ route('purchasing.purchaseOrders.bulk_destroy') }}', {
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
                            if (!res.ok) {
                                const text = await res.text();
                                throw new Error(text || res.statusText);
                            }
                            return res.json();
                        })
                        .then(data => {
                            closeDeletePoModal();
                            if (data.success) {
                                showNotification(data.message, 'success');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showNotification(data.message || 'Error deleting orders', 'error');
                                this.disabled = false;
                                this.textContent = originalText;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            closeDeletePoModal();
                            showNotification('An error occurred', 'error');
                            this.disabled = false;
                            this.textContent = originalText;
                        });
                });
            }
        });
    </script>
    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>

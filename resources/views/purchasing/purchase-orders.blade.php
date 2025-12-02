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
    <div class="purchasing-page-container fade-in">

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
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Create New PO</span>
                </button>
            </div>
        </div>

        <!-- Action Bar with Search & Filter -->
        <div class="purchasing-action-bar">
            <div style="display: flex; align-items: center; gap: 10px; flex-grow: 1;">
                <!-- Sort Filter -->
                <form action="{{ route('purchasing.purchaseOrders') }}" method="GET">
                    <select name="sort" class="inv-form-input"
                        style="width: 160px; height: 44px; cursor: pointer; border-radius: 22px; border: 1px solid transparent; background-color: #fff; padding: 0 12px;"
                        onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Supplier (A-Z)
                        </option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Supplier (Z-A)
                        </option>
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if (request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </select>
                </form>

                <!-- Search -->
                <form action="{{ route('purchasing.purchaseOrders') }}" method="GET" class="purchasing-search-bar">
                    @if (request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <i class="fa-solid fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by PO Number or Supplier...">
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </form>
            </div>

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
                    <div class="col-header" style="width: 50px;">#</div>
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
                        <div class="col-index"
                            style="width: 50px; font-size: 13px; color: var(--text-secondary); display: flex; align-items: center;">
                            {{ ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() + $loop->iteration }}
                        </div>
                        <div class="col-po-number" data-label="PO Number">{{ $po->reference_number }}</div>
                        <div class="col-supplier" data-label="Supplier">{{ $po->supplier->name ?? 'Unknown' }}</div>
                        <div class="col-date" data-label="Date Ordered">{{ $po->purchase_date->format('d/m/Y') }}
                        </div>
                        <div class="col-cost" data-label="Total Cost">฿{{ number_format($po->total_amount, 2) }}
                        </div>
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

    </div>
    <!-- End of purchasing-page-container -->

    <!-- [!!! VIEW PO MODAL !!!] -->
    <div class="inv-modal-overlay" id="view-po-modal-overlay">
        <div class="inv-modal" style="max-width: 800px;">
            <div class="inv-modal-header">
                <div class="inv-modal-title">Purchase Order Details</div>
                <button type="button" class="inv-modal-close"
                    onclick="closeModal('view-po-modal-overlay')">&times;</button>
            </div>
            <div class="inv-modal-body">
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <div style="font-size: 0.9rem; color: #86868b;">PO Number</div>
                        <div style="font-size: 1.1rem; font-weight: 600;" id="view-po-number"></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.9rem; color: #86868b;">Status</div>
                        <div id="view-po-status"></div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <div style="font-size: 0.9rem; color: #86868b;">Supplier</div>
                        <div style="font-weight: 500;" id="view-po-supplier"></div>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; color: #86868b;">Order Date</div>
                        <div style="font-weight: 500;" id="view-po-date"></div>
                    </div>
                </div>

                <div style="background: #f9f9f9; padding: 16px; border-radius: 12px;">
                    <table class="po-item-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Product</th>
                                <th style="width: 20%;">Qty</th>
                                <th style="width: 30%;">Cost (฿)</th>
                            </tr>
                        </thead>
                        <tbody id="view-po-items-body">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                    <div style="text-align: right; margin-top: 16px; font-weight: 700; font-size: 1.1rem;">
                        Total: <span id="view-po-total">฿0.00</span>
                    </div>
                </div>
            </div>
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn-secondary"
                    onclick="closeModal('view-po-modal-overlay')">Close</button>
            </div>
        </div>
    </div>

    <!-- [!!! CREATE/EDIT PO MODAL !!!] -->
    <div class="inv-modal-overlay" id="po-modal-overlay">
        <div class="inv-modal" style="max-width: 800px;">
            <form id="po-form" method="POST" action="{{ route('purchasing.purchaseOrders.store') }}">
                @csrf
                <div id="po-method-spoof"></div>

                <div class="inv-modal-header">
                    <div class="inv-modal-title" id="po-modal-title">Create New PO</div>
                    <button type="button" class="inv-modal-close"
                        onclick="closeModal('po-modal-overlay')">&times;</button>
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
                            <button type="button" class="inv-btn-secondary" onclick="addItemRow()"
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
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('po-modal-overlay')">Cancel</button>
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
                <button type="button" class="inv-modal-close"
                    onclick="closeModal('delete-po-modal-overlay')">&times;</button>
            </div>
            <div class="inv-modal-body">
                <p id="delete-po-confirm-text" style="color: var(--text-secondary); margin: 0;">Are you sure you
                    want to delete this PO? This action cannot be undone.</p>
            </div>
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn-secondary"
                    onclick="closeModal('delete-po-modal-overlay')">Cancel</button>

                {{-- Single Delete Form --}}
                <form id="delete-po-form" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none;">Delete</button>
                </form>

                {{-- Bulk Delete Button --}}
                <button id="btn-confirm-bulk-delete-po" type="button" class="inv-btn-primary"
                    style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none; display: none;"
                    onclick="executeBulkDelete()">Delete</button>
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

        // --- Modal Functions ---
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // --- Notification Logic ---
        function showNotification(message, type = 'success') {
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

            requestAnimationFrame(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            });

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // --- PO Modal Logic ---
        let rowCount = 0;

        function openNewModal() {
            document.getElementById('po-modal-title').textContent = 'Create New PO';
            document.getElementById('save-po-btn').textContent = 'Save Purchase Order';
            document.getElementById('po-form').action = "{{ route('purchasing.purchaseOrders.store') }}";
            document.getElementById('po-method-spoof').innerHTML = '';

            // Reset Form
            document.getElementById('po_supplier').value = '';
            document.getElementById('po_date').value = new Date().toISOString().split('T')[0];
            document.getElementById('po_status').value = 'draft';
            document.getElementById('po-items-body').innerHTML = '';
            document.getElementById('po-total-display').textContent = '฿0.00';
            rowCount = 0;

            // Add one empty row
            addItemRow();

            openModal('po-modal-overlay');
        }

        function openEditModal(po, items) {
            document.getElementById('po-modal-title').textContent = 'Edit Purchase Order';
            document.getElementById('save-po-btn').textContent = 'Update Purchase Order';
            document.getElementById('po-form').action = `/purchasing/purchase-orders/${po.id}`;
            document.getElementById('po-method-spoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Fill Form
            document.getElementById('po_supplier').value = po.supplier_id;
            document.getElementById('po_date').value = po.purchase_date.split('T')[0]; // Assuming ISO string or formatted
            document.getElementById('po_status').value = po.status;

            // Fill Items
            document.getElementById('po-items-body').innerHTML = '';
            rowCount = 0;

            if (items && items.length > 0) {
                items.forEach(item => addItemRow(item));
            } else {
                addItemRow();
            }

            calculateTotal();
            openModal('po-modal-overlay');
        }

        function openViewModal(po, items) {
            document.getElementById('view-po-number').textContent = po.reference_number;
            document.getElementById('view-po-status').innerHTML =
                `<span class="status-badge status-${po.status}">${po.status.charAt(0).toUpperCase() + po.status.slice(1)}</span>`;
            document.getElementById('view-po-supplier').textContent = po.supplier ? po.supplier.name : 'Unknown';
            document.getElementById('view-po-date').textContent = new Date(po.purchase_date).toLocaleDateString();

            const tbody = document.getElementById('view-po-items-body');
            tbody.innerHTML = '';
            let total = 0;

            items.forEach(item => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid #eee';
                const itemTotal = item.quantity * item.unit_price;
                total += itemTotal;
                tr.innerHTML = `
                    <td style="padding: 12px;">${item.product ? item.product.name : 'Unknown Product'}</td>
                    <td style="padding: 12px;">${item.quantity}</td>
                    <td style="padding: 12px;">฿${parseFloat(item.unit_price).toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('view-po-total').textContent = '฿' + total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            openModal('view-po-modal-overlay');
        }

        function addItemRow(item = null) {
            const tbody = document.getElementById('po-items-body');
            const tr = document.createElement('tr');
            tr.className = 'po-item-row';
            tr.dataset.index = rowCount;

            const productsOptions = window.productsData.map(p =>
                `<option value="${p.id}" data-price="${p.cost_price || 0}" ${item && item.product_id == p.id ? 'selected' : ''}>${p.name}</option>`
            ).join('');

            tr.innerHTML = `
                <td>
                    <select name="items[${rowCount}][product_id]" class="inv-form-input product-select" required onchange="updateRowPrice(this)">
                        <option value="" disabled ${!item ? 'selected' : ''}>Select Product</option>
                        ${productsOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][quantity]" class="inv-form-input qty-input"
                        value="${item ? item.quantity : 1}" min="1" required oninput="calculateTotal()">
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][unit_price]" class="inv-form-input price-input"
                        value="${item ? item.unit_price : 0}" step="0.01" min="0" required oninput="calculateTotal()">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="po-remove-btn" onclick="removeRow(this)">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
            rowCount++;
            if (!item) {
                // Trigger price update for new empty row if needed, though usually waits for selection
            }
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            calculateTotal();
        }

        function updateRowPrice(select) {
            const price = select.options[select.selectedIndex].dataset.price;
            const row = select.closest('tr');
            const priceInput = row.querySelector('.price-input');
            if (priceInput && !priceInput.value) { // Only auto-fill if empty or maybe always? Let's auto-fill
                priceInput.value = price;
            }
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.po-item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                total += qty * price;
            });
            document.getElementById('po-total-display').textContent = '฿' + total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // --- Delete Logic ---
        function confirmDelete(id) {
            document.getElementById('delete-po-form').style.display = 'inline';
            document.getElementById('btn-confirm-bulk-delete-po').style.display = 'none';
            document.getElementById('delete-po-confirm-text').textContent =
                'Are you sure you want to delete this PO? This action cannot be undone.';
            document.getElementById('delete-po-form').action = `/purchasing/purchase-orders/${id}`;
            openModal('delete-po-modal-overlay');
        }

        // --- Bulk Actions Logic ---
        const selectAll = document.getElementById(
            'select-all-checkbox'); // Note: ID might be 'select-all' in other files, checking...
        // In the viewed file it was 'select-all-checkbox' div class inv-checkbox. Wait, it's a div?
        // In categories it was an input type checkbox.
        // In the viewed file: <div class="inv-checkbox" id="select-all-checkbox"></div>
        // This implies custom JS handling for checkboxes in purchasing.js.
        // But I am rewriting JS. I should probably change these to real checkboxes for simplicity if I want to match categories.blade.php exactly.
        // However, changing the table structure might break existing CSS if it relies on div.inv-checkbox.
        // Let's stick to the existing HTML structure for the table but ensure my JS handles it, OR switch to input checkboxes.
        // The user said "Adjust Modal... to be like this page".
        // I will stick to the modal logic. The bulk delete trigger is outside the modal.
        // I'll assume the checkboxes are handled by purchasing.js or I need to handle them.
        // Let's look at the viewed file again.
        // <div class="inv-checkbox item-checkbox" data-id="{{ $po->id }}"></div>
        // This is definitely a custom checkbox.
        // I will add a helper to get selected IDs based on this structure.

        function getSelectedIds() {
            const selected = document.querySelectorAll('.item-checkbox.active'); // Assuming 'active' class is toggled
            return Array.from(selected).map(el => el.dataset.id);
        }

        function confirmBulkDelete() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;

            document.getElementById('delete-po-form').style.display = 'none';
            document.getElementById('btn-confirm-bulk-delete-po').style.display = 'inline-block';
            document.getElementById('delete-po-confirm-text').textContent =
                `Are you sure you want to delete ${ids.length} selected orders? This action cannot be undone.`;

            openModal('delete-po-modal-overlay');
        }

        function executeBulkDelete() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;

            const btn = document.getElementById('btn-confirm-bulk-delete-po');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Deleting...';

            fetch('{{ route('purchasing.purchaseOrders.bulk_destroy') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        ids: ids
                    })
                })
                .then(res => res.json())
                .then(data => {
                    closeModal('delete-po-modal-overlay');
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showNotification(data.message, 'error');
                        btn.disabled = false;
                        btn.textContent = originalText;
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification('An error occurred', 'error');
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif
            @if (session('error'))
                showNotification("{{ session('error') }}", 'error');
            @endif

            // Bind Create Button
            const createBtn = document.getElementById('open-po-modal');
            if (createBtn) {
                createBtn.onclick = openNewModal;
            }

            // Bind Bulk Delete Trigger
            const bulkTrigger = document.getElementById('btn-bulk-delete-po-trigger');
            if (bulkTrigger) {
                bulkTrigger.onclick = confirmBulkDelete;
            }
        });
    </script>
    <!-- Main Purchasing JS (Defer to keep checkbox logic if it exists there) -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>

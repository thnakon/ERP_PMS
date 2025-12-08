<x-app-layout>
    <!-- Main Page Container -->
    <div class="purchasing-page-container fade-in">

        {{-- Header --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / Purchasing / <span style="color: #3a3a3c; font-weight: 600;">Goods
                        Received</span>
                    < <a href="{{ route('purchasing.purchaseOrders') }}" style="color: #017aff">Purchase Orders</a>
                </p>
                <h2 class="sr-page-title">Goods Received <span
                        style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $receivedPos->total() }})</span>
                </h2>
            </div>

            <div class="sr-header-right" style="margin-right: 10px; display: flex; align-items: center; gap: 12px;">

                <!-- Bulk Actions (For History Table) -->
                <div id="bulk-actions"
                    style="display: none; align-items: center; gap: 8px; margin-right: 12px; padding-right: 12px; border-right: 1px solid #d2d2d7;">
                    <span class="inv-text-sub">Selected: <span id="selected-count"
                            style="color: #1d1d1f; font-weight: 700;">0</span></span>

                    <button class="inv-btn-secondary" id="btn-bulk-delete-gr-trigger"
                        style="color: #ff3b30; background-color: #fff1f0;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>

                <!-- Toggle View Button (Optional, maybe just scroll) -->
            </div>
        </div>

        <!-- VIEW 1: Main Dashboard (Awaiting & History) -->
        <div id="gr-main-view">

            <!-- Section A: Awaiting Reception -->
            <div class="gr-section" style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1d1d1f;">
                        <i class="fa-solid fa-box-open" style="color: #007aff; margin-right: 8px;"></i> Awaiting
                        Reception
                    </h3>

                    <!-- Search -->
                    <form action="{{ route('purchasing.goodsReceived') }}" method="GET" class="purchasing-search-bar"
                        style="width: 300px; margin-left: 15px;">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search PO Number...">
                    </form>
                </div>

                <div class="po-awaiting-list"
                    style="background: white; border-radius: 22px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    @if ($awaitingPos->count() > 0)
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($awaitingPos as $po)
                                <li
                                    style="display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid #f5f5f7;">
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <div
                                            style="background: #e8f2ff; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #007aff;">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #1d1d1f; font-size: 1rem;">
                                                {{ $po->reference_number }}</div>
                                            <div style="color: #86868b; font-size: 0.9rem;">
                                                {{ $po->supplier->name ?? 'Unknown Supplier' }} •
                                                {{ $po->items->count() }} Items • Ordered:
                                                {{ $po->purchase_date->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <button class="purchasing-button-primary btn-receive-trigger"
                                        data-id="{{ $po->id }}" data-ref="{{ $po->reference_number }}">
                                        Receive Goods
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div style="text-align: center; padding: 30px; color: #86868b;">
                            <i class="fa-solid fa-check-circle"
                                style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                            <p>No pending orders found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section B: Received History -->
            <div class="gr-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1d1d1f; margin: 0;">
                        <i class="fa-solid fa-clock-rotate-left" style="color: #34c759; margin-right: 8px;"></i>
                        Received
                        History
                    </h3>
                    <!-- Sort Filter -->
                    <form action="{{ route('purchasing.goodsReceived') }}" method="GET" id="history-filter-form"
                        style="display: flex; gap: 10px;">
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            placeholder="Search History..." class="inv-form-input"
                            style="width: 200px; height: 36px; border-radius: 18px; border: 1px solid #d2d2d7; padding: 0 12px; font-size: 0.9rem;">

                        <select name="sort" id="history-sort-select" class="inv-form-input"
                            style="width: 160px; height: 36px; cursor: pointer; border-radius: 18px; border: 1px solid #d2d2d7; padding: 0 12px; font-size: 0.9rem;"
                            onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Received
                            </option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Received
                            </option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Supplier
                                (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Supplier
                                (Z-A)</option>
                        </select>
                    </form>
                </div>

                <div id="view-list" class="transition-opacity duration-300">
                    <div class="purchasing-list-container" id="gr-list">
                        <div class="purchasing-list-row header-row">
                            <div class="col-header">
                                <div class="inv-checkbox" id="select-all-checkbox"></div>
                            </div>
                            <div class="col-header" style="width: 50px;">#</div>
                            <div class="col-header">PO Number</div>
                            <div class="col-header">Supplier</div>
                            <div class="col-header">Received Date</div>
                            <div class="col-header">Total Amount</div>
                            <div class="col-header" style="text-align: center;">Actions</div>
                        </div>

                        @forelse($receivedPos as $po)
                            <div class="purchasing-list-row">
                                <div class="col-checkbox">
                                    <div class="inv-checkbox item-checkbox" data-id="{{ $po->id }}"></div>
                                </div>
                                <div class="col-index"
                                    style="width: 50px; font-size: 13px; color: var(--text-secondary); display: flex; align-items: center;">
                                    {{ ($receivedPos->currentPage() - 1) * $receivedPos->perPage() + $loop->iteration }}
                                </div>
                                <div class="col-po-number">{{ $po->reference_number }}</div>
                                <div class="col-supplier">{{ $po->supplier->name ?? 'Unknown Supplier' }}</div>
                                <div class="col-date">{{ $po->updated_at->format('d/m/Y') }}</div>
                                <!-- Using updated_at as received date for now -->
                                <div class="col-cost">฿{{ number_format($po->total_amount, 2) }}</div>
                                <div class="col-actions" style="display: flex; justify-content: center; gap: 8px;">
                                    <button class="purchasing-icon-button btn-delete-gr"
                                        data-id="{{ $po->id }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="purchasing-list-row"
                                style="justify-content: center; padding: 20px; color: #86868b;">
                                No received history.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $receivedPos->onEachSide(1)->links('vendor.pagination.apple') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- VIEW 2: Receiving Interface (Hidden initially) -->
        <div id="gr-receive-view" class="gr-workflow-container" style="display: none;">
            <div class="receive-header"
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h2 id="receive-po-title" style="margin: 0; font-size: 1.5rem;">Receiving Items</h2>
                    <p id="receive-po-subtitle" style="color: #86868b; margin-top: 4px;">Loading details...</p>
                </div>
                <button class="purchasing-button-secondary" id="back-to-main-btn">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </button>
            </div>

            <form id="receive-form">
                <input type="hidden" id="receive_po_id" name="po_id">

                <div
                    style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="form-group" style="max-width: 300px;">
                        <label class="inv-form-label">Received Date <span
                                style="color: var(--required-star);">*</span></label>
                        <input type="date" id="receive_date" name="received_date" class="inv-form-input" required
                            value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Receiving Table -->
                <div
                    style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <table class="data-table receiving-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid #eee;">
                                <th style="text-align: left; padding: 12px;">Product</th>
                                <th style="text-align: left; padding: 12px; width: 100px;">Ordered</th>
                                <th style="text-align: left; padding: 12px; width: 120px;">Received <span
                                        style="color:red">*</span></th>
                                <th style="text-align: left; padding: 12px;">Batch / Lot No. <span
                                        style="color:red">*</span></th>
                                <th style="text-align: left; padding: 12px;">Expiry Date <span
                                        style="color:red">*</span></th>
                            </tr>
                        </thead>
                        <tbody id="receive-items-body">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>

                <div class="receive-footer" style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="purchasing-button-primary purchasing-button-lg btn-confirm-receive"
                        style="padding: 12px 32px; font-size: 1rem;">
                        <i class="fa-solid fa-check-double"></i> Confirm & Receive Stock
                    </button>
                </div>
            </form>
        </div>

    </div>
    <!-- End of purchasing-page-container -->

    <!-- Notification Container -->
    <div id="notification-container"
        style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; display: flex; flex-direction: column; align-items: center;">
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="inv-modal-overlay" id="delete-gr-modal-overlay">
        <div class="inv-modal" style="max-width: 400px;">
            <div class="inv-modal-header">
                <div class="inv-modal-title" style="color: #ff3b30;">Delete Received Record</div>
                <button type="button" class="inv-modal-close"
                    onclick="closeModal('delete-gr-modal-overlay')">&times;</button>
            </div>
            <div class="inv-modal-body">
                <p id="delete-gr-confirm-text" style="color: var(--text-secondary); margin: 0;">Are you sure? This
                    will revert the PO status and remove the record.</p>
            </div>
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn-secondary"
                    onclick="closeModal('delete-gr-modal-overlay')">Cancel</button>
                <button id="btn-confirm-delete-gr" type="button" class="inv-btn-primary"
                    style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none;"
                    onclick="executeDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            // --- View Switching ---
            const mainView = document.getElementById('gr-main-view');
            const receiveView = document.getElementById('gr-receive-view');
            const backBtn = document.getElementById('back-to-main-btn');

            const showReceiveView = () => {
                mainView.style.display = 'none';
                receiveView.style.display = 'block';
            };

            const showMainView = () => {
                receiveView.style.display = 'none';
                mainView.style.display = 'block';
            };

            if (backBtn) backBtn.addEventListener('click', showMainView);

            // --- Receive Logic ---
            const receiveItemsBody = document.getElementById('receive-items-body');
            const receivePoTitle = document.getElementById('receive-po-title');
            const receivePoSubtitle = document.getElementById('receive-po-subtitle');
            const receivePoIdInput = document.getElementById('receive_po_id');

            document.querySelectorAll('.btn-receive-trigger').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const poId = btn.dataset.id;
                    const poRef = btn.dataset.ref;

                    showReceiveView();
                    receivePoTitle.textContent = `Receiving Items for ${poRef}`;
                    receivePoSubtitle.textContent = 'Loading...';
                    receiveItemsBody.innerHTML =
                        '<tr><td colspan="5" style="text-align:center; padding:20px;">Loading items...</td></tr>';
                    receivePoIdInput.value = poId;

                    try {
                        const res = await fetch(`/purchasing/purchase-orders/${poId}/details`);
                        const po = await res.json();

                        receivePoSubtitle.innerHTML =
                            `<strong>Supplier:</strong> ${po.supplier.name} | <strong>Ordered:</strong> ${new Date(po.purchase_date).toLocaleDateString()}`;

                        receiveItemsBody.innerHTML = '';
                        po.items.forEach((item, index) => {
                            const tr = document.createElement('tr');
                            tr.style.borderBottom = '1px solid #eee';
                            tr.innerHTML = `
                                <td style="padding: 12px;">
                                    <div style="font-weight: 500;">${item.product.name}</div>
                                    <div style="font-size: 0.85rem; color: #86868b;">SKU: ${item.product.sku || '-'}</div>
                                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                                </td>
                                <td style="padding: 12px;">${item.quantity}</td>
                                <td style="padding: 12px;">
                                    <input type="number" name="items[${index}][quantity]" class="inv-form-input" value="${item.quantity}" min="1" required style="width: 80px;">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="text" name="items[${index}][batch_number]" class="inv-form-input" placeholder="Lot No." required>
                                </td>
                                <td style="padding: 12px;">
                                    <input type="date" name="items[${index}][expiry_date]" class="inv-form-input" required>
                                </td>
                            `;
                            receiveItemsBody.appendChild(tr);
                        });

                    } catch (err) {
                        console.error(err);
                        showNotification('Error loading PO details', 'error');
                        showMainView();
                    }
                });
            });

            // --- Submit Receive ---
            const receiveForm = document.getElementById('receive-form');
            if (receiveForm) {
                receiveForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const btn = receiveForm.querySelector('button[type="submit"]');
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

                    try {
                        const formData = new FormData(receiveForm);
                        // Manual JSON build
                        const payload = {
                            po_id: receivePoIdInput.value,
                            received_date: document.getElementById('receive_date').value,
                            items: []
                        };

                        const rows = receiveItemsBody.querySelectorAll('tr');
                        rows.forEach(row => {
                            payload.items.push({
                                product_id: row.querySelector(
                                    'input[name*="[product_id]"]').value,
                                quantity: row.querySelector('input[name*="[quantity]"]')
                                    .value,
                                batch_number: row.querySelector(
                                    'input[name*="[batch_number]"]').value,
                                expiry_date: row.querySelector(
                                    'input[name*="[expiry_date]"]').value,
                            });
                        });

                        const res = await fetch('{{ route('purchasing.goodsReceived.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await res.json();

                        if (result.success) {
                            showNotification(result.message, 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            throw new Error(result.message);
                        }

                    } catch (err) {
                        showNotification(err.message || 'Error processing receipt', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });
            }

            // --- Delete Logic ---
            let deleteIds = [];
            const deleteConfirmText = document.getElementById('delete-gr-confirm-text');

            // Single Delete
            document.querySelectorAll('.btn-delete-gr').forEach(btn => {
                btn.addEventListener('click', () => {
                    deleteIds = [btn.dataset.id];
                    deleteConfirmText.textContent =
                        'Are you sure you want to delete this received record? This will revert the PO status.';
                    openModal('delete-gr-modal-overlay');
                });
            });

            // Bulk Delete Trigger
            const bulkDeleteTrigger = document.getElementById('btn-bulk-delete-gr-trigger');
            if (bulkDeleteTrigger) {
                bulkDeleteTrigger.addEventListener('click', () => {
                    const checked = document.querySelectorAll('.item-checkbox.active');
                    deleteIds = Array.from(checked).map(cb => cb.dataset.id);
                    if (deleteIds.length === 0) return;

                    deleteConfirmText.textContent =
                        `Are you sure you want to delete ${deleteIds.length} records?`;
                    openModal('delete-gr-modal-overlay');
                });
            }

            // Execute Delete Function (Global scope for onclick)
            window.executeDelete = async function() {
                const confirmDeleteBtn = document.getElementById('btn-confirm-delete-gr');
                if (deleteIds.length === 0) return;

                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.textContent = 'Deleting...';

                try {
                    const res = await fetch(
                        '{{ route('purchasing.purchaseOrders.bulk_destroy') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                ids: deleteIds
                            })
                        });

                    const result = await res.json();
                    if (result.success) {
                        showNotification(result.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        throw new Error(result.message);
                    }
                } catch (err) {
                    showNotification(err.message, 'error');
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.textContent = 'Delete';
                    closeModal('delete-gr-modal-overlay');
                }
            };

            // --- Bulk Actions Logic ---
            function initializeCustomCheckboxes() {
                const selectAll = document.getElementById('select-all-checkbox');
                const checkboxes = document.querySelectorAll('.item-checkbox');
                const bulkActions = document.getElementById('bulk-actions');
                const selectedCountSpan = document.getElementById('selected-count');

                function updateBulkUI() {
                    const count = document.querySelectorAll('.item-checkbox.active').length;
                    if (count > 0) {
                        bulkActions.style.display = 'flex';
                        selectedCountSpan.textContent = count;
                    } else {
                        bulkActions.style.display = 'none';
                    }
                }

                if (selectAll) {
                    // Clear old listeners by cloning
                    const newSelectAll = selectAll.cloneNode(true);
                    selectAll.parentNode.replaceChild(newSelectAll, selectAll);

                    newSelectAll.addEventListener('click', function() {
                        const isActive = this.classList.contains('active');
                        if (isActive) {
                            this.classList.remove('active');
                            document.querySelectorAll('.item-checkbox').forEach(cb => {
                                cb.classList.remove('active');
                                cb.closest('.purchasing-list-row').classList.remove('selected-row');
                            });
                        } else {
                            this.classList.add('active');
                            document.querySelectorAll('.item-checkbox').forEach(cb => {
                                cb.classList.add('active');
                                cb.closest('.purchasing-list-row').classList.add('selected-row');
                            });
                        }
                        updateBulkUI();
                    });
                }

                checkboxes.forEach(cb => {
                    const newCb = cb.cloneNode(true);
                    cb.parentNode.replaceChild(newCb, cb);

                    newCb.addEventListener('click', function() {
                        this.classList.toggle('active');
                        this.closest('.purchasing-list-row').classList.toggle('selected-row');
                        updateBulkUI();

                        // Update Select All
                        const allActive = document.querySelectorAll('.item-checkbox.active')
                            .length === document.querySelectorAll('.item-checkbox').length;
                        const selectAllBtn = document.getElementById('select-all-checkbox');
                        if (selectAllBtn) {
                            if (allActive) selectAllBtn.classList.add('active');
                            else selectAllBtn.classList.remove('active');
                        }
                    });
                });
            }

            // Initial init
            initializeCustomCheckboxes();

            // --- Real-time Search ---
            const searchInput = document.getElementById('search-input');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value;
                    const url = new URL(window.location.href);

                    if (query.length > 0) {
                        url.searchParams.set('search', query);
                        url.searchParams.delete('page');
                    } else {
                        url.searchParams.delete('search');
                    }

                    window.history.pushState({}, '', url);

                    searchTimeout = setTimeout(() => {
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                // Update List
                                document.getElementById('view-list').innerHTML = doc
                                    .getElementById('view-list').innerHTML;

                                // Re-init checkboxes
                                initializeCustomCheckboxes();
                            })
                            .catch(e => console.error(e));
                    }, 400);
                });
            }
        });
    </script>
    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>

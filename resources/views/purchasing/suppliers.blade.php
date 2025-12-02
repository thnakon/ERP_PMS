<x-app-layout>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Suppliers - Pharmacy ERP</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('resources/css/purchasing.css') }}">
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

    <div class="purchasing-page-container fade-in">
        <!-- Header -->
        <div class="purchasing-header">
            <div class="purchasing-header-left">
                <p class="sr-breadcrumb">Dashboard / Purchasing / <span
                        style="color: #3a3a3c; font-weight: 600;">Suppliers</span> > <a
                        href="{{ route('purchasing.purchaseOrders') }}" style="color: #017aff"> Purchase-Orders </a></p>
                <h2 class="sr-page-title">Suppliers <span
                        style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $suppliers->total() }})</span>
                </h2>
            </div>
            <div class="purchasing-header-right">
                <button class="purchasing-button-primary" onclick="openNewModal()">
                    <i class="fa-solid fa-truck-field"></i>
                    <span>Add New Supplier</span>
                </button>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="purchasing-action-bar" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px; flex-grow: 1;">
                <!-- Sort Filter -->
                <form action="{{ route('purchasing.suppliers') }}" method="GET">
                    <select name="sort" class="inv-form-input"
                        style="width: 160px; height: 44px; cursor: pointer; border-radius: 22px; border: 1px solid transparent; background-color: #fff; padding: 0 12px;"
                        onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                        </option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                        </option>
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </select>
                </form>

                <form action="{{ route('purchasing.suppliers') }}" method="GET" class="purchasing-search-bar">
                    @if (request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <i class="fa-solid fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by Company, Contact, or Phone...">
                </form>
            </div>

            <!-- Bulk Actions Panel -->
            <div id="bulk-actions" style="display: none; align-items: center; gap: 12px;">
                <span style="color: var(--text-secondary); font-size: 0.9rem;">Selected: <span id="selected-count"
                        style="font-weight: 600; color: var(--text-primary);">0</span></span>
                <button class="purchasing-button-secondary" onclick="confirmBulkDelete()"
                    style="color: #ff3b30; background-color: #fff1f0; border-color: transparent; height: 36px; font-size: 0.85rem;">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
        </div>

        <!-- List View -->
        <div class="purchasing-list-container" id="supplier-list">
            <!-- Header Row -->
            <div class="purchasing-list-row header-row">
                <div class="col-checkbox">
                    <div class="inv-checkbox-wrapper">
                        <input type="checkbox" class="inv-checkbox" id="select-all">
                    </div>
                </div>
                <div class="col-index" style="font-weight: 600; color: var(--text-secondary);">#</div>
                <div class="col-company-name">Company Name</div>
                <div class="col-contact">Contact Person</div>
                <div class="col-phone">Phone</div>
                <div class="col-email">Email</div>
                <div class="col-pos">Total POs</div>
                <div class="col-actions" style="text-align: center;">Actions</div>
            </div>

            <!-- Data Rows -->
            @forelse($suppliers as $supplier)
                <div class="purchasing-list-row">
                    <div class="col-checkbox">
                        <div class="inv-checkbox-wrapper">
                            <input type="checkbox" class="inv-checkbox item-checkbox" data-id="{{ $supplier->id }}">
                        </div>
                    </div>
                    <div class="col-index" style="font-size: 13px; color: var(--text-secondary);">
                        {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}</div>
                    <div class="col-company-name" data-label="Company Name">{{ $supplier->name }}</div>
                    <div class="col-contact" data-label="Contact Person">{{ $supplier->contact_person ?? '-' }}</div>
                    <div class="col-phone" data-label="Phone">{{ $supplier->phone ?? '-' }}</div>
                    <div class="col-email" data-label="Email">{{ $supplier->email ?? '-' }}</div>
                    <div class="col-pos" data-label="Total POs" style="text-align: center">
                        {{ $supplier->purchases_count ?? 0 }}</div>
                    <div class="col-actions" data-label="Actions"
                        style="display: flex; justify-content: center; gap: 8px;">
                        <button class="purchasing-icon-button" onclick="openViewModal({{ json_encode($supplier) }})"
                            title="View">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button" onclick="openEditModal({{ json_encode($supplier) }})"
                            title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete-row"
                            onclick="confirmDelete({{ $supplier->id }})" title="Delete">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="purchasing-list-row"
                    style="display: flex; justify-content: center; align-items: center; padding: 40px;">
                    <div style="text-align: center; color: var(--text-secondary);">
                        <i class="fa-solid fa-box-open"
                            style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p>No suppliers found.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $suppliers->links('vendor.pagination.apple') }}
        </div>
    </div>

    <!-- MODAL: New/Edit Supplier -->
    <div class="inv-modal-overlay" id="modal-supplier">
        <div class="inv-modal">
            <form id="supplier-form" method="POST" action="{{ route('purchasing.suppliers.store') }}">
                @csrf
                <div id="method-spoof"></div>

                <div class="inv-modal-header">
                    <div class="inv-modal-title" id="modal-title">Add New Supplier</div>
                    <button type="button" class="inv-modal-close"
                        onclick="closeModal('modal-supplier')">&times;</button>
                </div>

                <div class="inv-modal-body">
                    <div class="inv-form-group">
                        <label class="inv-form-label">Company Name <span
                                style="color: var(--required-star);">*</span></label>
                        <input type="text" id="company_name" name="name" class="inv-form-input" required
                            placeholder="e.g. Siam Pharma Supply">
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" class="inv-form-input"
                            placeholder="e.g. John Doe">
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label">Phone</label>
                        <input type="tel" id="phone" name="phone" class="inv-form-input"
                            placeholder="e.g. 02-123-4567">
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label">Email</label>
                        <input type="email" id="email" name="email" class="inv-form-input"
                            placeholder="e.g. contact@example.com">
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label">Status</label>
                        <select id="status" name="status" class="inv-form-input">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label">Address</label>
                        <textarea id="address" name="address" class="inv-form-input" rows="3" placeholder="Enter full address"></textarea>
                    </div>
                </div>

                <div class="inv-modal-footer" style="margin-top: 0px">
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('modal-supplier')">Cancel</button>
                    <button type="submit" class="inv-btn-primary" id="modal-submit-btn">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: View Supplier -->
    <div class="inv-modal-overlay" id="modal-view-supplier">
        <div class="inv-modal">
            <div class="inv-modal-header">
                <div class="inv-modal-title">Supplier Details</div>
                <button type="button" class="inv-modal-close"
                    onclick="closeModal('modal-view-supplier')">&times;</button>
            </div>
            <div class="inv-modal-body">
                <div class="inv-form-group">
                    <label class="inv-form-label" style="color: #8e8e93;">Company Name</label>
                    <div id="view-name" style="font-weight: 600; font-size: 16px; margin-top: 4px;"></div>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label" style="color: #8e8e93;">Contact Person</label>
                    <div id="view-contact" style="font-size: 15px; margin-top: 4px;"></div>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label" style="color: #8e8e93;">Phone</label>
                    <div id="view-phone" style="font-size: 15px; margin-top: 4px;"></div>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label" style="color: #8e8e93;">Email</label>
                    <div id="view-email" style="font-size: 15px; margin-top: 4px;"></div>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label" style="color: #8e8e93;">Status</label>
                    <div style="margin-top: 8px;">
                        <span id="view-status" class="inv-status-badge"></span>
                    </div>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label" style="color: #8e8e93;">Address</label>
                    <div id="view-address" style="font-size: 15px; margin-top: 4px; line-height: 1.5;"></div>
                </div>
            </div>
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn-secondary"
                    onclick="closeModal('modal-view-supplier')">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL: Delete Confirmation -->
    <div class="inv-modal-overlay" id="modal-delete">
        <div class="inv-modal" style="max-width: 400px;">
            <div class="inv-modal-header">
                <div class="inv-modal-title" style="color: #ff3b30;">Delete Supplier</div>
                <button type="button" class="inv-modal-close" onclick="closeModal('modal-delete')">&times;</button>
            </div>
            <div class="inv-modal-body">
                <p id="delete-confirm-text">Are you sure you want to delete this supplier? This action cannot be
                    undone.</p>
            </div>
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn-secondary" onclick="closeModal('modal-delete')">Cancel</button>

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

    <script src="{{ asset('resources/js/purchasing.js') }}"></script>

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
            document.getElementById('modal-title').textContent = 'Add New Supplier';
            document.getElementById('modal-submit-btn').textContent = 'Save Supplier';
            document.getElementById('supplier-form').action = "{{ route('purchasing.suppliers.store') }}";
            document.getElementById('method-spoof').innerHTML = ''; // Clear PUT

            // Clear inputs
            document.getElementById('company_name').value = '';
            document.getElementById('contact_person').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('email').value = '';
            document.getElementById('status').value = 'Active';
            document.getElementById('address').value = '';

            openModal('modal-supplier');
        }

        function openEditModal(supplier) {
            document.getElementById('modal-title').textContent = 'Edit Supplier';
            document.getElementById('modal-submit-btn').textContent = 'Update Supplier';
            document.getElementById('supplier-form').action = "/purchasing/suppliers/" + supplier.id;
            document.getElementById('method-spoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Fill inputs
            document.getElementById('company_name').value = supplier.name;
            document.getElementById('contact_person').value = supplier.contact_person || '';
            document.getElementById('phone').value = supplier.phone || '';
            document.getElementById('email').value = supplier.email || '';
            document.getElementById('status').value = supplier.status || 'Active';
            document.getElementById('address').value = supplier.address || '';

            openModal('modal-supplier');
        }

        function openViewModal(supplier) {
            document.getElementById('view-name').textContent = supplier.name;
            document.getElementById('view-contact').textContent = supplier.contact_person || '-';
            document.getElementById('view-phone').textContent = supplier.phone || '-';
            document.getElementById('view-email').textContent = supplier.email || '-';
            document.getElementById('view-address').textContent = supplier.address || '-';

            const statusBadge = document.getElementById('view-status');
            statusBadge.textContent = supplier.status || 'Active';
            // Assuming you have status badge styles, if not, basic styling
            statusBadge.className = 'inv-status-badge ' + (supplier.status === 'Active' ? 'active' : 'inactive');

            openModal('modal-view-supplier');
        }

        function confirmDelete(id) {
            // Reset to Single Delete Mode
            document.getElementById('delete-form').style.display = 'inline';
            document.getElementById('btn-bulk-delete').style.display = 'none';
            document.getElementById('delete-confirm-text').textContent =
                'Are you sure you want to delete this supplier? This action cannot be undone.';

            document.getElementById('delete-form').action = "/purchasing/suppliers/" + id;
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
                `Are you sure you want to delete ${count} selected suppliers? This action cannot be undone.`;

            openModal('modal-delete');
        }

        function executeBulkDelete() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            const ids = Array.from(checked).map(cb => cb.dataset.id);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = document.getElementById('btn-bulk-delete');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Deleting...';

            fetch('{{ route('purchasing.suppliers.bulk_destroy') }}', {
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
                    closeModal('modal-delete');
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
                    closeModal('modal-delete');
                    showFlash('An error occurred', 'error');
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }
    </script>
</x-app-layout>

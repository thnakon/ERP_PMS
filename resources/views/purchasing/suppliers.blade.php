<x-app-layout>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Suppliers - Pharmacy ERP</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('resources/css/purchasing.css') }}">
        <style>
            /* Apple-style Flash Message (Copied from categories.blade.php) */
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

    <!-- Flash Message Container -->
    <div id="flash-message" class="flash-message">
        <i class="fa-solid fa-check-circle"></i>
        <span id="flash-text">Operation successful</span>
    </div>

    <div class="purchasing-page-container">
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


                <button class="purchasing-button-primary" id="open-supplier-modal">
                    <i class="fa-solid fa-plus"></i>
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
                <button class="purchasing-button-secondary" id="btn-bulk-delete-trigger"
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
                    <div class="inv-checkbox" id="select-all-checkbox"></div>
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
                        <div class="inv-checkbox item-checkbox" data-id="{{ $supplier->id }}"></div>
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
                        <button class="purchasing-icon-button btn-view" title="View"
                            data-supplier='@json($supplier)'>
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button btn-edit" title="Edit"
                            data-supplier='@json($supplier)'>
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete" title="Delete"
                            data-id="{{ $supplier->id }}">
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

    <!-- Add/Edit/View Modal -->
    <div class="inv-modal-overlay" id="supplier-modal-overlay">
        <div class="inv-modal">
            <form id="supplier-form" method="POST" action="{{ route('purchasing.suppliers.store') }}">
                @csrf
                <div id="method-spoof"></div>

                <div class="inv-modal-header">
                    <div class="inv-modal-title" id="modal-title">Add New Supplier</div>
                    <button type="button" class="inv-modal-close" id="close-modal-btn">&times;</button>
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

                <div class="inv-modal-footers"
                    style="padding: 24px 0 24px 32px; border-top: 1px solid #e5e5ea; 
            display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                    <button type="button" class="inv-btn-secondary" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="inv-btn-primary" id="save-modal-btn">Save Supplier</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="inv-modal-overlay" id="delete-modal-overlay">
        <div class="inv-modal" style="max-width: 400px;">
            <div class="inv-modal-header">
                <div class="inv-modal-title" style="color: #ff3b30;">Delete Supplier</div>
                <button type="button" class="inv-modal-close" id="close-delete-modal-btn">&times;</button>
            </div>
            <div class="inv-modal-body">
                <p id="delete-confirm-text" style="color: var(--text-secondary); margin: 0;">Are you sure you want to
                    delete this
                    supplier? This action cannot be undone.</p>
            </div>
            <div class="inv-modal-footer">
                <button type="button" class="inv-btn-secondary" id="cancel-delete-btn">Cancel</button>

                {{-- Single Delete Form --}}
                <form id="delete-form" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none;">Delete</button>
                </form>

                {{-- Bulk Delete Button (Hidden by default) --}}
                <button id="btn-confirm-bulk-delete" type="button" class="inv-btn-primary"
                    style="background-color: #ff3b30; border-color: #ff3b30; box-shadow: none; display: none;">Delete</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('resources/js/purchasing.js') }}"></script>

    <!-- Flash Message Script -->
    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showFlash("{{ session('success') }}", 'success');
            @endif

            @if ($errors->any())
                showFlash("{{ $errors->first() }}", 'error');
            @endif

            // --- Modal Elements ---
            const deleteModal = document.getElementById('delete-modal-overlay');
            const closeDeleteBtn = document.getElementById('close-delete-modal-btn');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const deleteForm = document.getElementById('delete-form');
            const deleteConfirmText = document.getElementById('delete-confirm-text');
            const btnConfirmBulkDelete = document.getElementById('btn-confirm-bulk-delete');

            // --- Open Modal Function ---
            const openDeleteModal = () => {
                if (deleteModal) deleteModal.classList.add('show');
            };

            const closeDeleteModal = () => {
                if (deleteModal) deleteModal.classList.remove('show');
            };

            // --- Event Listeners for Closing ---
            if (closeDeleteBtn) closeDeleteBtn.addEventListener('click', closeDeleteModal);
            if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            if (deleteModal) {
                deleteModal.addEventListener('click', (e) => {
                    if (e.target === deleteModal) closeDeleteModal();
                });
            }

            // --- Single Delete Logic ---
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default if it's a link/submit
                    const id = this.dataset.id;

                    // Reset to Single Delete Mode
                    if (deleteForm) {
                        deleteForm.style.display = 'inline';
                        deleteForm.action = `/purchasing/suppliers/${id}`;
                    }
                    if (btnConfirmBulkDelete) btnConfirmBulkDelete.style.display = 'none';
                    if (deleteConfirmText) deleteConfirmText.textContent =
                        'Are you sure you want to delete this supplier? This action cannot be undone.';

                    openDeleteModal();
                });
            });

            // --- Bulk Delete Logic ---
            const bulkDeleteTrigger = document.getElementById(
                'btn-bulk-delete-trigger'); // Changed ID to avoid conflict

            if (bulkDeleteTrigger) {
                bulkDeleteTrigger.addEventListener('click', function() {
                    const checked = document.querySelectorAll('.item-checkbox.active');
                    const count = checked.length;

                    if (count === 0) return;

                    // Switch to Bulk Delete Mode
                    if (deleteForm) deleteForm.style.display = 'none';
                    if (btnConfirmBulkDelete) btnConfirmBulkDelete.style.display = 'inline-block';
                    if (deleteConfirmText) deleteConfirmText.textContent =
                        `Are you sure you want to delete ${count} selected suppliers? This action cannot be undone.`;

                    openDeleteModal();
                });
            }

            // --- Execute Bulk Delete ---
            if (btnConfirmBulkDelete) {
                btnConfirmBulkDelete.addEventListener('click', function() {
                    const checked = document.querySelectorAll('.item-checkbox.active');
                    const ids = Array.from(checked).map(cb => cb.dataset.id);

                    if (ids.length === 0) return;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    const originalText = this.textContent;
                    this.disabled = true;
                    this.textContent = 'Deleting...';

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
                            if (!res.ok) {
                                const text = await res.text();
                                throw new Error(text || res.statusText);
                            }
                            return res.json();
                        })
                        .then(data => {
                            closeDeleteModal();
                            if (data.success) {
                                showFlash(data.message, 'success');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showFlash(data.message || 'Error deleting suppliers', 'error');
                                this.disabled = false;
                                this.textContent = originalText;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            closeDeleteModal();
                            let msg = 'An error occurred';
                            try {
                                const errorObj = JSON.parse(err.message);
                                msg = errorObj.message || errorObj.error || msg;
                            } catch (e) {
                                msg = err.message;
                            }
                            if (msg.length > 100) msg = 'Server Error (Check Console)';
                            showFlash(msg, 'error');

                            this.disabled = false;
                            this.textContent = originalText;
                        });
                });
            }
        });
    </script>
</x-app-layout>

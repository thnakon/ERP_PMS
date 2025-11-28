@php
    if (session('success')) {
        session()->flash('suppress_global_toast', true);
    }
@endphp
<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Notifications Management</title>
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

        {{-- Flash messages handled by local script --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ session('success') }}", 'success');
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ session('error') }}", 'error');
                });
            </script>
        @endif

        <div class="inv-container">
            <div class="inv-breadcrumb-bar">
                Dashboard / System /
                <span style="color: #3a3a3c; font-weight: 600;">Notifications</span>
            </div>
            <div class="inv-header">
                <div class="inv-header-left">
                    <h1 class="inv-page-title">Notifications <span
                            style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $notifications->total() }})</span>
                    </h1>
                </div>
                <!-- Optional: Add filters or actions here if needed -->
            </div>

            <!-- Controls Row (Search & Bulk Actions) - Adapted from Categories -->
            <div class="inv-filters-wrapper">
                <!-- Search Form -->
                <form method="GET" action="{{ route('notifications.index') }}" class="inv-search-form"
                    style="display: flex; gap: 10px; align-items: center;">
                    <!-- Search Input -->
                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Notifications..." class="inv-form-input"
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
            <div class="inv-card-row header grid-notifications"
                style="grid-template-columns: 40px 60px 2fr 1fr 3fr 1fr 100px;">
                <div class="inv-checkbox-wrapper">
                    <input type="checkbox" class="inv-checkbox" id="select-all">
                </div>
                <div class="inv-col-header">#</div>
                <div class="inv-col-header">User</div>
                <div class="inv-col-header">Action</div>
                <div class="inv-col-header">Description</div>
                <div class="inv-col-header">Date</div>
                @if (Auth::user()->role === 'admin')
                    <div class="inv-col-header" style="text-align: right;">Actions</div>
                @else
                    <div class="inv-col-header"></div>
                @endif
            </div>

            <!-- Notifications Loop -->
            @forelse($notifications as $notification)
                <div class="inv-card-row grid-notifications"
                    style="grid-template-columns: 40px 60px 2fr 1fr 3fr 1fr 100px;">
                    <div class="inv-checkbox-wrapper">
                        <input type="checkbox" class="inv-checkbox item-checkbox" data-id="{{ $notification->id }}">
                    </div>
                    <div class="inv-text-sub" style="font-weight: 500;">
                        {{ ($notifications->currentPage() - 1) * $notifications->perPage() + $loop->iteration }}
                    </div>
                    <div class="inv-product-info">
                        <div class="flex items-center gap-3">
                            <img class="h-8 w-8 rounded-full object-cover border border-gray-200"
                                src="{{ $notification->user->profile_photo_path ? asset('storage/' . $notification->user->profile_photo_path) : asset('images/default-avatar.png') }}"
                                alt="">
                            <div>
                                <div class="inv-product-name">{{ $notification->user->name ?? 'Unknown User' }}</div>
                                <div class="inv-text-sub" style="font-size: 11px;">
                                    {{ $notification->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="inv-text-main">
                        <span class="inv-status-badge active" style="background-color: #E5F1FF; color: #007AFF;">
                            {{ $notification->action }}
                        </span>
                    </div>
                    <div class="inv-text-sub">{{ $notification->description }}</div>
                    <div class="inv-text-sub">{{ $notification->created_at->format('M d, Y H:i') }}</div>

                    @if (Auth::user()->role === 'admin')
                        <div class="inv-action-group" style="justify-content: flex-end;">
                            <button class="inv-icon-action"
                                onclick="openEditModal({{ $notification->id }}, '{{ addslashes($notification->description) }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="inv-icon-action btn-delete-row"
                                onclick="confirmDelete({{ $notification->id }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    @else
                        <div></div>
                    @endif
                </div>
            @empty
                <div class="inv-card-row" style="justify-content: center; padding: 20px;">
                    <span class="inv-text-sub">No notifications found.</span>
                </div>
            @endforelse

            {{-- Pagination --}}
            {{ $notifications->links('vendor.pagination.apple') }}
        </div>

        <!-- MODAL: Edit Notification -->
        <div class="inv-modal-overlay" id="modal-edit">
            <div class="inv-modal">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="inv-modal-header">
                        <div class="inv-modal-title">Edit Notification</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeModal('modal-edit')">&times;</button>
                    </div>
                    <div class="inv-modal-body">
                        <div class="inv-form-group">
                            <label class="inv-form-label">Description</label>
                            <textarea name="description" id="editDescription" class="inv-form-input" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="inv-modal-footer">
                        <button type="button" class="inv-btn-secondary"
                            onclick="closeModal('modal-edit')">Cancel</button>
                        <button type="submit" class="inv-btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Delete Confirmation -->
        <div class="inv-modal-overlay" id="modal-delete">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" style="color: #ff3b30;">Delete Notification</div>
                    <button class="inv-modal-close" onclick="closeModal('modal-delete')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="delete-confirm-text">Are you sure you want to delete this notification? This action cannot
                        be undone.</p>
                </div>
                <div class="inv-modal-footer">
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('modal-delete')">Cancel</button>

                    {{-- Single Delete Form --}}
                    <form id="delete-form" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary"
                            style="background-color: #ff3b30; border-color: #ff3b30;">Delete</button>
                    </form>

                    {{-- Bulk Delete Button --}}
                    <button id="btn-bulk-delete" type="button" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; display: none;"
                        onclick="executeBulkDelete()">Delete Selected</button>
                </div>
            </div>
        </div>

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

            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
            }

            function openEditModal(id, description) {
                const form = document.getElementById('editForm');
                const descInput = document.getElementById('editDescription');

                form.action = `/notifications/${id}`;
                descInput.value = description;
                openModal('modal-edit');
            }

            function confirmDelete(id) {
                document.getElementById('delete-form').style.display = 'inline';
                document.getElementById('btn-bulk-delete').style.display = 'none';
                document.getElementById('delete-confirm-text').textContent =
                    'Are you sure you want to delete this notification? This action cannot be undone.';

                const form = document.getElementById('delete-form');
                form.action = `/notifications/${id}`;
                openModal('modal-delete');
            }

            function confirmBulkDelete() {
                document.getElementById('delete-form').style.display = 'none';
                document.getElementById('btn-bulk-delete').style.display = 'inline';

                const count = document.querySelectorAll('.item-checkbox:checked').length;
                document.getElementById('delete-confirm-text').textContent =
                    `Are you sure you want to delete ${count} selected notifications? This action cannot be undone.`;

                openModal('modal-delete');
            }

            function executeBulkDelete() {
                const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.dataset.id);

                if (selected.length === 0) return;

                fetch('{{ route('notifications.bulk-destroy') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids: selected
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showFlash('Notifications deleted successfully', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showFlash('Error deleting notifications', 'error');
                            closeModal('modal-delete');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFlash('An error occurred', 'error');
                        closeModal('modal-delete');
                    });
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
        </script>
    </body>

    </html>
</x-app-layout>

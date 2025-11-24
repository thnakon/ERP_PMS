document.addEventListener('DOMContentLoaded', function () {
    // --- Elements ---
    const modalBackdrop = document.getElementById('supplier-modal-backdrop');
    const modalContent = document.getElementById('supplier-modal-content');
    const openModalBtn = document.getElementById('open-supplier-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    const supplierForm = document.getElementById('supplier-form');
    const modalTitle = document.getElementById('modal-title');
    const saveBtn = document.getElementById('save-modal-btn');

    const bulkActions = document.getElementById('bulk-actions');
    const selectedCountSpan = document.getElementById('selected-count');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const deleteBulkBtn = bulkActions ? bulkActions.querySelector('.inv-btn-secondary[style*="color: #ff3b30"]') : null;

    const searchInput = document.querySelector('.purchasing-search-bar input');
    const supplierList = document.getElementById('supplier-list');
    const flashMessageContainer = document.getElementById('flash-message-container');

    let isEditMode = false;
    let currentSupplierId = null;

    // --- Helper: Get CSRF Token ---
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.content;
        const input = document.querySelector('input[name="_token"]');
        return input ? input.value : '';
    }

    // --- Modal Functions ---
    function openModal(editMode = false, supplierData = null) {
        isEditMode = editMode;
        currentSupplierId = supplierData ? supplierData.id : null;

        // Reset form
        supplierForm.reset();

        if (isEditMode && supplierData) {
            modalTitle.textContent = 'Edit Supplier';
            saveBtn.textContent = 'Update Supplier';

            // Populate fields
            document.getElementById('name').value = supplierData.name || '';
            document.getElementById('tax_id').value = supplierData.tax_id || '';
            document.getElementById('contact_person').value = supplierData.contact_person || '';
            document.getElementById('phone').value = supplierData.phone || '';
            document.getElementById('email').value = supplierData.email || '';
            document.getElementById('address').value = supplierData.address || '';
            document.getElementById('notes').value = supplierData.notes || '';
        } else {
            modalTitle.textContent = 'Add New Supplier';
            saveBtn.textContent = 'Save Supplier';
        }

        // Show modal with fade in
        modalBackdrop.style.display = 'flex';
        // Force reflow
        void modalBackdrop.offsetWidth;
        modalBackdrop.classList.add('show');
        modalContent.classList.add('show');
    }

    function closeModal() {
        modalBackdrop.classList.remove('show');
        modalContent.classList.remove('show');

        setTimeout(() => {
            modalBackdrop.style.display = 'none';
        }, 300); // Match transition duration
    }

    // --- Event Listeners ---
    if (openModalBtn) {
        openModalBtn.addEventListener('click', () => openModal(false));
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

    // Close on backdrop click
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeModal();
        });
    }

    // --- Form Submission ---
    if (supplierForm) {
        supplierForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(supplierForm);
            const data = Object.fromEntries(formData.entries());

            const url = isEditMode ? `/suppliers/${currentSupplierId}` : '/suppliers';
            const method = isEditMode ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    const result = await response.json();
                    showFlashMessage(result.message || 'Success', 'success');
                    closeModal();
                    setTimeout(() => window.location.reload(), 1000); // Reload to show changes
                } else {
                    const errorData = await response.json();
                    showFlashMessage(errorData.message || 'An error occurred', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showFlashMessage('An unexpected error occurred', 'error');
            }
        });
    }

    // --- Edit / View / Delete Actions ---
    document.addEventListener('click', async function (e) {
        const target = e.target.closest('button');
        if (!target) return;

        if (target.classList.contains('btn-edit')) {
            const supplierData = JSON.parse(target.dataset.supplier);
            openModal(true, supplierData);
        } else if (target.classList.contains('btn-view')) {
            const supplierData = JSON.parse(target.dataset.supplier);
            openModal(true, supplierData);
        } else if (target.classList.contains('btn-delete')) {
            const supplierId = target.dataset.supplierId;
            if (confirm('Are you sure you want to delete this supplier?')) {
                try {
                    const response = await fetch(`/suppliers/${supplierId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken()
                        }
                    });

                    if (response.ok) {
                        showFlashMessage('Supplier deleted successfully', 'success');
                        target.closest('.purchasing-list-row').remove();
                    } else {
                        showFlashMessage('Failed to delete supplier', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showFlashMessage('An error occurred', 'error');
                }
            }
        }
    });

    // --- Bulk Actions ---
    const checkboxes = document.querySelectorAll('.inv-checkbox[data-id]');
    let selectedIds = new Set();

    function updateBulkActions() {
        const count = selectedIds.size;
        selectedCountSpan.textContent = count;
        if (count > 0) {
            bulkActions.style.display = 'flex';
        } else {
            bulkActions.style.display = 'none';
        }

        // Update select all state
        if (count === checkboxes.length && count > 0) {
            selectAllCheckbox.classList.add('checked');
        } else {
            selectAllCheckbox.classList.remove('checked');
        }
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('click', function () {
            const id = this.dataset.id;
            if (selectedIds.has(id)) {
                selectedIds.delete(id);
                this.classList.remove('checked');
            } else {
                selectedIds.add(id);
                this.classList.add('checked');
            }
            updateBulkActions();
        });
    });

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', function () {
            const isChecked = this.classList.contains('checked');
            if (isChecked) {
                // Uncheck all
                selectedIds.clear();
                checkboxes.forEach(cb => cb.classList.remove('checked'));
                this.classList.remove('checked');
            } else {
                // Check all
                checkboxes.forEach(cb => {
                    selectedIds.add(cb.dataset.id);
                    cb.classList.add('checked');
                });
                this.classList.add('checked');
            }
            updateBulkActions();
        });
    }

    if (deleteBulkBtn) {
        deleteBulkBtn.addEventListener('click', async function () {
            if (selectedIds.size === 0) return;

            if (confirm(`Are you sure you want to delete ${selectedIds.size} suppliers?`)) {
                try {
                    const response = await fetch('/suppliers/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({ ids: Array.from(selectedIds) })
                    });

                    if (response.ok) {
                        showFlashMessage('Suppliers deleted successfully', 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showFlashMessage('Failed to delete suppliers', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showFlashMessage('An error occurred', 'error');
                }
            }
        });
    }

    // --- Search Functionality ---
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.supplier-row');

            rows.forEach(row => {
                const name = row.dataset.name ? row.dataset.name.toLowerCase() : '';
                const phone = row.dataset.phone ? row.dataset.phone.toLowerCase() : '';

                if (name.includes(term) || phone.includes(term)) {
                    row.style.display = 'grid'; // Assuming grid layout
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // --- Flash Message Function ---
    function showFlashMessage(message, type = 'success') {
        const msgDiv = document.createElement('div');
        msgDiv.className = `flash-message ${type}`;
        msgDiv.innerHTML = `
            <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;

        flashMessageContainer.appendChild(msgDiv);

        // Trigger animation
        requestAnimationFrame(() => {
            msgDiv.classList.add('show');
        });

        // Remove after 3 seconds
        setTimeout(() => {
            msgDiv.classList.remove('show');
            setTimeout(() => {
                msgDiv.remove();
            }, 300);
        }, 3000);
    }
});

/**
 * Oboun ERP - Bulk Actions System
 * Multi-select table with floating action bar
 */

import { showToast } from './toast.js';
import { ModalSystem } from './modal.js';

const BulkActionsSystem = {
    selectedItems: new Set(),
    targetToDelete: null,
    activeTableId: null,

    /**
     * Initialize bulk actions for a table
     * @param {string} tableId - The table ID
     */
    init(tableId = 'data-table') {
        const table = document.getElementById(tableId);
        if (!table) return;

        this.activeTableId = tableId;

        // Setup select all checkbox
        const selectAllCheckbox = table.querySelector('.select-all-checkbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                this.toggleSelectAll(e.target.checked, tableId);
            });
        }

        // Setup row checkboxes
        const rowCheckboxes = table.querySelectorAll('.row-checkbox');
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateBulkBar(tableId);
            });
        });
    },

    /**
     * Get the currently visible data container
     */
    getActiveContainer() {
        const productViews = ['products-stack', 'products-grid', 'products-compact'];
        for (const id of productViews) {
            const el = document.getElementById(id);
            if (el && !el.classList.contains('hidden')) return el;
        }
        return document.querySelector('table') || document.querySelector('.stack-container') || document.querySelector('.table-container');
    },

    /**
     * Toggle select all checkboxes
     * @param {boolean} checked - Whether to check or uncheck all
     * @param {string} tableId - The table ID
     */
    toggleSelectAll(checked, containerId = 'data-table') {
        const container = document.getElementById(containerId) || this.getActiveContainer();
        if (!container) return;

        const checkboxes = container.querySelectorAll('.row-checkbox');

        this.selectedItems.clear();

        checkboxes.forEach(cb => {
            cb.checked = checked;
            const item = cb.closest('tr') || cb.closest('.stack-item') || cb.closest('.product-card') || cb.closest('.compact-row');

            if (checked && item) {
                item.classList.add('selected');
                this.selectedItems.add(cb.value || item.id);
            } else if (item) {
                item.classList.remove('selected');
            }
        });

        this.updateBulkBar(containerId);
    },

    /**
     * Update the bulk action bar visibility and count
     * @param {string} containerId - The container ID
     */
    updateBulkBar(containerId = 'data-table') {
        const container = document.getElementById(containerId) || this.getActiveContainer();
        if (!container) return;

        const checkboxes = container.querySelectorAll('.row-checkbox');
        const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

        const bar = document.getElementById('bulk-action-bar');
        const countSpan = document.getElementById('selected-count');
        const selectAll = container.querySelector('.select-all-checkbox') || document.getElementById('select-all-stack');

        // Update row highlights
        checkboxes.forEach(cb => {
            const item = cb.closest('tr') || cb.closest('.stack-item') || cb.closest('.product-card') || cb.closest('.compact-row');
            if (item) {
                if (cb.checked) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            }
        });

        // Update select all checkbox state
        if (selectAll) {
            if (selectedCount === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            } else if (selectedCount === checkboxes.length) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            }
        }

        // Show/hide floating bar with animations
        if (bar && countSpan) {
            countSpan.textContent = selectedCount;

            if (selectedCount > 0) {
                this.activeTableId = containerId;
                if (bar.classList.contains('hidden')) {
                    bar.classList.remove('hidden');
                }
                bar.classList.remove('bulk-action-bar-hidden');
                bar.classList.add('animate-slide-up-fade');
                bar.classList.remove('animate-slide-down-fade-out');
            } else if (!bar.classList.contains('bulk-action-bar-hidden')) {
                bar.classList.add('animate-slide-down-fade-out');
                bar.classList.remove('animate-slide-up-fade');

                // Set back to hidden after animation completes
                setTimeout(() => {
                    const currentContainer = document.getElementById(containerId) || this.getActiveContainer();
                    const currentCount = currentContainer ? Array.from(currentContainer.querySelectorAll('.row-checkbox')).filter(cb => cb.checked).length : 0;
                    if (currentCount === 0) {
                        bar.classList.add('bulk-action-bar-hidden');
                        bar.classList.add('hidden');
                    }
                }, 300);
            }
        }
    },

    /**
     * Delete selected items
     * @param {string} tableId - The table ID
     */
    deleteSelected(tableId = 'data-table') {
        const table = document.getElementById(tableId) || this.getActiveContainer();
        if (!table) return;

        const checkboxes = table.querySelectorAll('.row-checkbox:checked');
        if (checkboxes.length > 0) {
            this.activeTableId = table.id || tableId;
            this.targetToDelete = 'bulk';
            this.openDeleteModal(checkboxes.length);
        }
    },

    /**
     * Delete a single row
     * @param {HTMLElement} btn - The delete button element
     */
    deleteRow(btn) {
        this.targetToDelete = btn.closest('tr') || btn.closest('.stack-item') || btn.closest('.product-card') || btn.closest('.compact-row');
        this.activeTableId = this.targetToDelete.parentElement ? (this.targetToDelete.parentElement.id || 'data-table') : 'data-table';
        this.openDeleteModal(1);
    },

    /**
     * Open delete confirmation modal
     * @param {number} count - Number of items to delete
     */
    openDeleteModal(count) {
        const title = document.getElementById('delete-title');
        const desc = document.getElementById('delete-desc');

        if (title && desc) {
            if (count > 1) {
                title.innerText = `Delete ${count} Items?`;
                desc.innerText = `Are you sure you want to delete these ${count} items? This action cannot be undone.`;
            } else {
                title.innerText = 'Delete Item?';
                desc.innerText = 'Are you sure you want to delete this item? This action cannot be undone.';
            }
        }

        ModalSystem.open('delete-modal');
    },

    /**
     * Close delete modal
     */
    closeDeleteModal() {
        ModalSystem.close('delete-modal');
        setTimeout(() => {
            this.targetToDelete = null;
        }, 300);
    },

    /**
     * Execute the delete action
     */
    async executeDelete() {
        const tableId = this.activeTableId || 'data-table';
        this.closeDeleteModal();

        const table = document.getElementById(tableId) || this.getActiveContainer();

        if (this.targetToDelete === 'bulk') {
            if (!table) return;
            const checkboxes = table.querySelectorAll('.row-checkbox:checked');
            const count = checkboxes.length;
            const productIds = Array.from(checkboxes).map(cb => cb.value);

            // Real bulk delete for products
            if (tableId.includes('product') || window.location.pathname.includes('/products')) {
                if (typeof window.showLoading === 'function') window.showLoading();

                setTimeout(async () => {
                    try {
                        const response = await fetch('/products/bulk-delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ ids: productIds })
                        });

                        if (response.ok) {
                            showToast(`Deleted ${count} items successfully`, 'success');
                            setTimeout(() => window.location.reload(), 500);
                        } else {
                            showToast('Failed to delete items', 'error');
                            if (typeof window.hideLoading === 'function') window.hideLoading();
                        }
                    } catch (error) {
                        console.error('Bulk delete error:', error);
                        showToast('An error occurred during deletion', 'error');
                        if (typeof window.hideLoading === 'function') window.hideLoading();
                    }
                }, 1000);
                return;
            }

            // Fallback: visual only
            checkboxes.forEach(cb => {
                const row = cb.closest('tr') || cb.closest('.stack-item') || cb.closest('.product-card') || cb.closest('.compact-row');
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => row.remove(), 300);
                }
            });

            // Reset UI
            const bar = document.getElementById('bulk-action-bar');
            if (bar) bar.classList.add('hidden');
            showToast(`Deleted ${count} items successfully`, 'success');

        } else if (this.targetToDelete) {
            // Single item delete
            const row = this.targetToDelete;
            const cb = row.querySelector('.row-checkbox');
            const productId = cb ? cb.value : null;

            // Real delete for products if applicable
            if (productId && (tableId.includes('product') || window.location.pathname.includes('/products'))) {
                if (typeof window.showLoading === 'function') window.showLoading();

                setTimeout(async () => {
                    try {
                        const response = await fetch(`/products/${productId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';

                            setTimeout(() => {
                                row.remove();
                                this.updateBulkBar(tableId);
                                showToast('Item deleted successfully', 'success');
                                if (typeof window.hideLoading === 'function') window.hideLoading();
                            }, 300);
                        } else {
                            showToast('Failed to delete item', 'error');
                            if (typeof window.hideLoading === 'function') window.hideLoading();
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        showToast('An error occurred during deletion', 'error');
                        if (typeof window.hideLoading === 'function') window.hideLoading();
                    }
                }, 1000);
                return;
            }

            // Fallback: visual only
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';

            setTimeout(() => {
                row.remove();
                this.updateBulkBar(tableId);
                showToast('Item deleted successfully', 'success');
            }, 300);
        }
    }
};

// Global functions for inline usage
function toggleSelectAll(source) {
    const container = source.closest('.stack-container') || source.closest('.table-container') || source.closest('table') || document.querySelector('.stack-container');
    if (container) BulkActionsSystem.toggleSelectAll(source.checked, container.id || 'bulk-container');
}

function updateBulkBar(source) {
    const container = source ? (source.closest('.stack-container') || source.closest('.table-container') || source.closest('table')) : (document.querySelector('.stack-container') || document.querySelector('.table-container'));
    if (container) BulkActionsSystem.updateBulkBar(container.id || 'bulk-container');
}

function deleteSelected(btn) {
    BulkActionsSystem.deleteSelected();
}

function deleteRow(btn) {
    BulkActionsSystem.deleteRow(btn);
}

function closeDeleteModal() {
    BulkActionsSystem.closeDeleteModal();
}

function executeDelete() {
    BulkActionsSystem.executeDelete();
}

// Export for module usage
export {
    BulkActionsSystem,
    toggleSelectAll,
    updateBulkBar,
    deleteSelected,
    deleteRow,
    closeDeleteModal,
    executeDelete
};

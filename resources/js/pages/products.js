/**
 * Oboun ERP - Products Page
 * Product management with multi-select
 */

import { showToast } from '../components/toast.js';
import { BulkActionsSystem } from '../components/bulk-actions.js';
import { ModalSystem } from '../components/modal.js';

const ProductsPage = {
    products: [],
    currentProduct: null,
    currentView: 'list', // list, grid, compact

    /**
     * Initialize products page
     */
    init() {
        // Load products data from window
        if (window.productsData) {
            this.products = Array.isArray(window.productsData) ? window.productsData : [];
        }

        // Load saved view preference
        const savedView = localStorage.getItem('productsView');
        if (savedView && ['list', 'grid', 'compact'].includes(savedView)) {
            this.currentView = savedView;
            this.applyView(savedView);
        }

        BulkActionsSystem.init('products-stack');
        this.initEventListeners();
    },

    /**
     * Set view mode
     * @param {string} view - list, grid, or compact
     */
    setView(view) {
        if (!['list', 'grid', 'compact'].includes(view)) return;

        this.currentView = view;
        localStorage.setItem('productsView', view);
        this.applyView(view);
    },

    /**
     * Apply view mode to DOM
     * @param {string} view - list, grid, or compact
     */
    applyView(view) {
        // Update toggle buttons
        document.querySelectorAll('.view-toggle-btn').forEach(btn => {
            if (btn.dataset.view === view) {
                btn.classList.add('active', 'bg-white', 'shadow-sm', 'text-ios-blue');
                btn.classList.remove('text-gray-500');
            } else {
                btn.classList.remove('active', 'bg-white', 'shadow-sm', 'text-ios-blue');
                btn.classList.add('text-gray-500');
            }
        });

        // Show/hide views
        const listView = document.getElementById('products-stack');
        const gridView = document.getElementById('products-grid');
        const compactView = document.getElementById('products-compact');
        const selectionHeader = document.getElementById('selection-header');

        // Hide all views
        [listView, gridView, compactView].forEach(el => {
            if (el) el.classList.add('hidden');
        });

        // Always show selection header for all views
        if (selectionHeader) selectionHeader.classList.remove('hidden');

        // Show selected view
        if (view === 'list' && listView) {
            listView.classList.remove('hidden');
        } else if (view === 'grid' && gridView) {
            gridView.classList.remove('hidden');
        } else if (view === 'compact' && compactView) {
            compactView.classList.remove('hidden');
        }
    },

    /**
     * Open filter drawer
     */
    openFilterDrawer() {
        const backdrop = document.getElementById('filter-drawer-backdrop');
        const panel = document.getElementById('filter-drawer-panel');

        if (backdrop) {
            backdrop.classList.remove('hidden');
            // Force reflow for animation
            void backdrop.offsetWidth;
            backdrop.classList.add('visible');
        }
        if (panel) {
            panel.classList.add('open');
        }
        document.body.style.overflow = 'hidden';
    },

    /**
     * Close filter drawer
     */
    closeFilterDrawer() {
        const backdrop = document.getElementById('filter-drawer-backdrop');
        const panel = document.getElementById('filter-drawer-panel');

        if (backdrop) {
            backdrop.classList.remove('visible');
        }
        if (panel) {
            panel.classList.remove('open');
        }

        // Hide backdrop after animation
        setTimeout(() => {
            if (backdrop) backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }, 350);
    },

    /**
     * Apply filters and reload products
     */
    applyFilters() {
        const params = new URLSearchParams();

        // Category filter
        const categoryCheckboxes = document.querySelectorAll('input[name="filter_category"]:checked');
        const categories = Array.from(categoryCheckboxes)
            .map(cb => cb.value)
            .filter(v => v !== '');
        if (categories.length > 0) {
            params.set('categories', categories.join(','));
        }

        // Drug class filter
        const drugClassCheckboxes = document.querySelectorAll('input[name="filter_drug_class"]:checked');
        const drugClasses = Array.from(drugClassCheckboxes)
            .map(cb => cb.value)
            .filter(v => v !== '');
        if (drugClasses.length > 0) {
            params.set('drug_classes', drugClasses.join(','));
        }

        // Price range
        const priceMin = document.getElementById('filter_price_min')?.value;
        const priceMax = document.getElementById('filter_price_max')?.value;
        if (priceMin) params.set('price_min', priceMin);
        if (priceMax) params.set('price_max', priceMax);

        // Stock status
        const stockFilter = document.querySelector('input[name="filter_stock"]:checked')?.value;
        if (stockFilter && stockFilter !== 'all') {
            params.set('stock_status', stockFilter);
        }

        // Prescription
        const rxFilter = document.querySelector('input[name="filter_prescription"]:checked')?.value;
        if (rxFilter && rxFilter !== 'all') {
            params.set('prescription', rxFilter);
        }

        // Close drawer and reload with filters
        this.closeFilterDrawer();

        const queryString = params.toString();
        const url = queryString ? `/products?${queryString}` : '/products';
        window.location.href = url;
    },

    /**
     * Clear all filters
     */
    clearFilters() {
        // Reset category and drug class checkboxes
        document.querySelectorAll('input[name="filter_category"]').forEach(cb => {
            cb.checked = cb.value === '';
        });
        document.querySelectorAll('input[name="filter_drug_class"]').forEach(cb => {
            cb.checked = cb.value === '';
        });

        // Reset price inputs
        const priceMin = document.getElementById('filter_price_min');
        const priceMax = document.getElementById('filter_price_max');
        if (priceMin) priceMin.value = '';
        if (priceMax) priceMax.value = '';

        // Reset radio buttons to "all"
        document.querySelector('input[name="filter_stock"][value="all"]').checked = true;
        document.querySelector('input[name="filter_prescription"][value="all"]').checked = true;

        // Close drawer and go to unfiltered page
        this.closeFilterDrawer();
        window.location.href = '/products';
    },

    /**
     * Go to first (oldest) item
     */
    goToFirst() {
        // Navigate to products sorted by oldest first
        window.location.href = '/products?sort=oldest';
    },

    /**
     * Go to latest (newest) item
     */
    goToLatest() {
        // Navigate to products sorted by newest first
        window.location.href = '/products?sort=latest';
    },

    /**
     * Debounce helper
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Initialize event listeners
     */
    initEventListeners() {
        const searchInput = document.getElementById('product-search');
        if (searchInput) {
            const handleSearch = this.debounce((e) => {
                this.searchProducts(e.target.value);
            }, 300);
            searchInput.addEventListener('input', handleSearch);
        }

        const productForm = document.getElementById('product-form');
        if (productForm) {
            productForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveProduct();
            });
        }

        // Image preview
        const imageInput = document.getElementById('product-image-input');
        if (imageInput) {
            imageInput.addEventListener('change', (e) => {
                this.previewImage(e.target.files[0]);
            });
        }
    },

    /**
     * Preview selected image
     */
    previewImage(file) {
        if (!file) return;

        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('image-preview-img');
        const placeholder = document.getElementById('image-placeholder');

        if (preview && previewImg && placeholder) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    },

    /**
     * Remove selected image
     */
    removeImage() {
        const imageInput = document.getElementById('product-image-input');
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('image-placeholder');

        if (imageInput) imageInput.value = '';
        if (preview) preview.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
    },

    /**
     * Search products via AJAX
     */
    async searchProducts(query) {
        const container = document.getElementById('product-list-container');
        if (!container) return;

        // Optional: show loading state
        container.style.opacity = '0.5';

        try {
            const response = await fetch(`/products?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const html = await response.text();
            container.innerHTML = html;
            container.style.opacity = '1';

            // Re-initialize bulk actions for the new content
            BulkActionsSystem.updateBulkBar('product-list-container');
        } catch (error) {
            console.error('Search error:', error);
            container.style.opacity = '1';
        }
    },

    /**
     * Render products table
     */
    renderTable() {
        const tbody = document.querySelector('#products-table tbody');
        if (!tbody) return;

        tbody.innerHTML = this.products.map(product => `
            <tr class="hover:bg-gray-50 transition-colors" id="product-${product.id}">
                <td class="pl-6 py-4">
                    <input type="checkbox" value="${product.id}" onchange="BulkActionsSystem.updateBulkBar('products-table')" 
                           class="row-checkbox table-checkbox">
                </td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">${product.name}</div>
                    <div class="text-xs text-gray-400">${product.sku}</div>
                </td>
                <td class="px-6 py-4 text-gray-500">${product.generic_name || '-'}</td>
                <td class="px-6 py-4 text-gray-500">${product.category?.name || '-'}</td>
                <td class="px-6 py-4 text-gray-900 font-medium">฿${product.unit_price}</td>
                <td class="px-6 py-4">
                    ${product.stock_qty <= product.min_stock
                ? `<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span> ${product.stock_qty}</span>`
                : `<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span> ${product.stock_qty}</span>`
            }
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="table-row-actions">
                        <button onclick="ProductsPage.viewProduct(${product.id})" 
                                class="table-row-btn table-row-btn-view" title="View">
                            <i class="ph-bold ph-eye text-lg"></i>
                        </button>
                        <button onclick="ProductsPage.editProduct(${product.id})" 
                                class="table-row-btn table-row-btn-edit" title="Edit">
                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                        </button>
                        <button onclick="BulkActionsSystem.deleteRow(this)" 
                                class="table-row-btn table-row-btn-delete" title="Delete">
                            <i class="ph-bold ph-trash text-lg"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

    /**
     * Open new product form
     */
    newProduct() {
        this.currentProduct = null;
        this.clearForm();
        this.removeImage();
        document.getElementById('product-modal-title').textContent = 'Add Product';
        ModalSystem.open('product-modal');
    },

    /**
     * View product details
     * @param {number} productId - Product ID
     */
    viewProduct(productId) {
        window.location.href = `/products/${productId}`;
    },

    /**
     * Fill form with product data
     * @param {Object} product - Product data
     * @param {boolean} readonly - Whether form should be readonly
     */
    fillForm(product, readonly = false) {
        const form = document.getElementById('product-form');
        if (!form) return;

        const fields = ['name', 'sku', 'generic_name', 'unit_price', 'stock_qty', 'min_stock'];

        fields.forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.value = product[field] || '';
                input.readOnly = readonly;
                input.disabled = readonly;
            }
        });

        // Category select
        const categorySelect = form.querySelector('[name="category_id"]');
        if (categorySelect) {
            categorySelect.value = product.category_id || '';
            categorySelect.disabled = readonly;
        }

        // Show/hide save button
        const saveBtn = document.getElementById('product-save-btn');
        if (saveBtn) {
            saveBtn.style.display = readonly ? 'none' : 'block';
        }
    },

    /**
     * Clear the form
     */
    clearForm() {
        const form = document.getElementById('product-form');
        if (form) {
            form.reset();
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.readOnly = false;
                el.disabled = false;
            });
        }

        const saveBtn = document.getElementById('product-save-btn');
        if (saveBtn) saveBtn.style.display = 'block';
    },

    /**
     * Save product
     */
    async saveProduct() {
        const form = document.getElementById('product-form');
        const formData = new FormData(form);

        try {
            let url = '/products';

            if (this.currentProduct) {
                url = `/products/${this.currentProduct.id}`;
                formData.append('_method', 'PUT');  // Method spoofing for Laravel
            }

            const response = await fetch(url, {
                method: 'POST',  // Always POST for FormData with file uploads
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const loader = document.getElementById('pill-loading');

            if (response.ok) {
                const result = await response.json();

                if (this.currentProduct) {
                    showToast('Product updated successfully', 'success');
                } else {
                    showToast('Product created successfully', 'success');
                }

                ModalSystem.close('product-modal');

                // Reload the product list
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                if (loader) loader.classList.remove('active');
                const error = await response.json();
                showToast(error.message || 'Failed to save product', 'error');
            }
        } catch (error) {
            const loader = document.getElementById('pill-loading');
            if (loader) loader.classList.remove('active');
            console.error('Save product error:', error);
            showToast('Failed to save product', 'error');
        }
    },

    /**
     * Open the bulk category change modal
     */
    openBulkCategoryModal() {
        const container = document.getElementById('products-stack') ||
            document.getElementById('products-grid') ||
            document.getElementById('products-compact') ||
            document.querySelector('table');

        if (!container) return;

        const checkboxes = container.querySelectorAll('.row-checkbox:checked');
        if (checkboxes.length === 0) {
            showToast('Please select at least one item', 'warning');
            return;
        }

        const countSpan = document.getElementById('category-modal-count');
        if (countSpan) countSpan.textContent = checkboxes.length;

        ModalSystem.open('category-modal');
    },

    /**
     * Execute the bulk category change
     */
    async executeBulkCategoryChange() {
        const categoryId = document.getElementById('bulk-category-id').value;
        if (!categoryId) {
            showToast('Please select a category', 'warning');
            return;
        }

        const container = document.getElementById('products-stack') ||
            document.getElementById('products-grid') ||
            document.getElementById('products-compact') ||
            document.querySelector('table');

        if (!container) return;

        const checkboxes = container.querySelectorAll('.row-checkbox:checked');
        const productIds = Array.from(checkboxes).map(cb => cb.value);

        try {
            const response = await fetch('/products/bulk-update-category', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ids: productIds,
                    category_id: categoryId
                })
            });

            if (response.ok) {
                showToast(`Updated ${productIds.length} items successfully`, 'success');
                ModalSystem.close('category-modal');
                setTimeout(() => window.location.reload(), 500);
            } else {
                const error = await response.json();
                showToast(error.message || 'Failed to update items', 'error');
            }
        } catch (error) {
            console.error('Bulk update error:', error);
            showToast('An error occurred during bulk update', 'error');
        }
    }
};

// Make available globally
window.ProductsPage = ProductsPage;

// Initialize on DOM ready only if on products page
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('product-list-container')) {
        ProductsPage.init();
    }
});

export { ProductsPage };

/**
 * Oboun ERP - Expiry Management Page
 * Lot tracking and expiry alerts
 */

import { showToast } from '../components/toast.js';
import { BulkActionsSystem } from '../components/bulk-actions.js';

const ExpiryPage = {
    lots: [],
    filters: {
        days: 30,
        status: 'all'
    },

    /**
     * Initialize expiry page
     */
    init() {
        BulkActionsSystem.init('expiry-table');
        this.initFilterListeners();
        this.loadLots();
    },

    /**
     * Initialize filter listeners
     */
    initFilterListeners() {
        // Days filter
        const daysFilter = document.getElementById('expiry-days-filter');
        if (daysFilter) {
            daysFilter.addEventListener('change', (e) => {
                this.filters.days = parseInt(e.target.value);
                this.loadLots();
            });
        }

        // Status filter
        const statusFilter = document.getElementById('expiry-status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.loadLots();
            });
        }
    },

    /**
     * Load lots - data is passed from Blade template via page reload with filters
     */
    loadLots() {
        // On expiry page, data comes from Blade template
        // Filter changes trigger page reload via the filter listeners in blade
        console.log('Expiry lots loaded from server-side rendering');
    },

    /**
     * Render expiry table
     */
    renderTable() {
        const tbody = document.querySelector('#expiry-table tbody');
        if (!tbody) return;

        if (this.lots.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                        <i class="ph ph-check-circle text-4xl mb-2"></i>
                        <p>No expiring items found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = this.lots.map(lot => {
            const daysUntilExpiry = this.getDaysUntilExpiry(lot.expiry_date);
            const status = this.getExpiryStatus(daysUntilExpiry);

            return `
                <tr class="hover:bg-gray-50 transition-colors ${status.rowClass}" id="lot-${lot.id}">
                    <td class="pl-6 py-4">
                        <input type="checkbox" value="${lot.id}" onchange="BulkActionsSystem.updateBulkBar('expiry-table')" 
                               class="row-checkbox table-checkbox">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">${lot.product.name}</div>
                        <div class="text-xs text-gray-400">${lot.product.sku}</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-600">${lot.lot_number}</td>
                    <td class="px-6 py-4 text-gray-900">${lot.quantity}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium ${status.textClass}">${lot.expiry_date}</div>
                        <div class="text-xs ${status.textClass}">${status.label}</div>
                    </td>
                    <td class="px-6 py-4">
                        ${status.badge}
                    </td>
                </tr>
            `;
        }).join('');
    },

    /**
     * Calculate days until expiry
     * @param {string} expiryDate - Expiry date string
     * @returns {number} Days until expiry
     */
    getDaysUntilExpiry(expiryDate) {
        const today = new Date();
        const expiry = new Date(expiryDate);
        const diffTime = expiry - today;
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },

    /**
     * Get expiry status object
     * @param {number} days - Days until expiry
     * @returns {Object} Status object with styling info
     */
    getExpiryStatus(days) {
        if (days <= 0) {
            return {
                label: 'Expired',
                badge: '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span> Expired</span>',
                textClass: 'text-red-500',
                rowClass: 'bg-red-50'
            };
        } else if (days <= 7) {
            return {
                label: `${days} days left`,
                badge: '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span> Critical</span>',
                textClass: 'text-red-500',
                rowClass: ''
            };
        } else if (days <= 30) {
            return {
                label: `${days} days left`,
                badge: '<span class="badge badge-warning"><span class="badge-dot badge-dot-warning"></span> Warning</span>',
                textClass: 'text-orange-500',
                rowClass: ''
            };
        } else if (days <= 90) {
            return {
                label: `${days} days left`,
                badge: '<span class="badge badge-info"><span class="badge-dot badge-dot-info"></span> Notice</span>',
                textClass: 'text-blue-500',
                rowClass: ''
            };
        } else {
            return {
                label: `${days} days left`,
                badge: '<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span> Good</span>',
                textClass: 'text-green-500',
                rowClass: ''
            };
        }
    },

    /**
     * Update expiry stats
     */
    updateStats() {
        const expired = this.lots.filter(l => this.getDaysUntilExpiry(l.expiry_date) <= 0).length;
        const critical = this.lots.filter(l => {
            const d = this.getDaysUntilExpiry(l.expiry_date);
            return d > 0 && d <= 7;
        }).length;
        const warning = this.lots.filter(l => {
            const d = this.getDaysUntilExpiry(l.expiry_date);
            return d > 7 && d <= 30;
        }).length;

        const stats = {
            expired: document.getElementById('stat-expired'),
            critical: document.getElementById('stat-critical'),
            warning: document.getElementById('stat-warning'),
            total: document.getElementById('stat-total')
        };

        if (stats.expired) stats.expired.textContent = expired;
        if (stats.critical) stats.critical.textContent = critical;
        if (stats.warning) stats.warning.textContent = warning;
        if (stats.total) stats.total.textContent = this.lots.length;
    },

    /**
     * Export expiry report
     */
    exportReport() {
        window.open(`/api/product-lots/export?days=${this.filters.days}&status=${this.filters.status}`, '_blank');
        showToast('Generating expiry report...', 'info');
    }
};

// Make available globally
window.ExpiryPage = ExpiryPage;

// Initialize on DOM ready only if on expiry page
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('expiry-table')) {
        ExpiryPage.init();
    }
});

export { ExpiryPage };

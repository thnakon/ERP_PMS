/**
 * Oboun ERP - Orders Page
 * Order management with refunds
 */

import { showToast } from '../components/toast.js';
import { BulkActionsSystem } from '../components/bulk-actions.js';
import { DrawerSystem } from '../components/drawer.js';
import { ModalSystem } from '../components/modal.js';

const OrdersPage = {
    orders: [],
    currentOrder: null,

    /**
     * Initialize orders page
     */
    init() {
        BulkActionsSystem.init('orders-table');
        this.loadOrders();
    },

    /**
     * Load orders from server or window data
     */
    loadOrders() {
        // Use data passed from Blade template if available
        if (window.ordersData) {
            this.orders = Array.isArray(window.ordersData) ? window.ordersData : [];
            return;
        }
        // Fallback to API
        fetch('/api/orders')
            .then(res => res.json())
            .then(data => { this.orders = data; this.renderTable(); })
            .catch(err => console.warn('Could not load orders via API'));
    },

    /**
     * Render orders table
     */
    renderTable() {
        const tbody = document.querySelector('#orders-table tbody');
        if (!tbody) return;

        tbody.innerHTML = this.orders.map(order => `
            <tr class="hover:bg-gray-50 transition-colors" id="order-${order.id}">
                <td class="pl-6 py-4">
                    <input type="checkbox" value="${order.id}" onchange="BulkActionsSystem.updateBulkBar('orders-table')" 
                           class="row-checkbox table-checkbox">
                </td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">${order.order_number}</div>
                    <div class="text-xs text-gray-400">${order.created_at}</div>
                </td>
                <td class="px-6 py-4">
                    ${order.customer ? `
                        <div class="table-user-cell">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(order.customer.name)}&background=007AFF&color=fff&rounded=true" 
                                 class="table-user-avatar">
                            <div>
                                <div class="table-user-name">${order.customer.name}</div>
                                <div class="table-user-email">${order.customer.phone || ''}</div>
                            </div>
                        </div>
                    ` : '<span class="text-gray-400">Walk-in</span>'}
                </td>
                <td class="px-6 py-4">
                    ${this.getStatusBadge(order.status)}
                </td>
                <td class="px-6 py-4 text-gray-900 font-medium text-right">฿${order.total_amount}</td>
                <td class="px-6 py-4 text-right">
                    <div class="table-row-actions">
                        <button onclick="OrdersPage.viewOrder(${order.id})" 
                                class="table-row-btn table-row-btn-view" title="View">
                            <i class="ph-bold ph-eye text-lg"></i>
                        </button>
                        ${order.status === 'completed' ? `
                            <button onclick="OrdersPage.initiateRefund(${order.id})" 
                                    class="table-row-btn text-orange-500 hover:bg-orange-50" title="Refund">
                                <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                            </button>
                        ` : ''}
                        <button onclick="OrdersPage.printReceipt(${order.id})" 
                                class="table-row-btn table-row-btn-view" title="Print Receipt">
                            <i class="ph-bold ph-printer text-lg"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

    /**
     * Get status badge HTML
     * @param {string} status - Order status
     */
    getStatusBadge(status) {
        const badges = {
            'completed': '<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span> Completed</span>',
            'pending': '<span class="badge badge-warning"><span class="badge-dot badge-dot-warning"></span> Pending</span>',
            'refunded': '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span> Refunded</span>',
            'partial_refund': '<span class="badge badge-info"><span class="badge-dot badge-dot-info"></span> Partial Refund</span>'
        };
        return badges[status] || badges.pending;
    },

    /**
     * View order details in drawer
     * @param {number} orderId - Order ID
     */
    async viewOrder(orderId) {
        try {
            const response = await fetch(`/api/orders/${orderId}`);
            const order = await response.json();
            this.currentOrder = order;

            const content = document.getElementById('order-drawer-content');
            if (content) {
                content.innerHTML = `
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-900">${order.order_number}</h3>
                            ${this.getStatusBadge(order.status)}
                        </div>
                        
                        ${order.customer ? `
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <div class="text-sm text-gray-500 mb-1">Customer</div>
                                <div class="font-medium text-gray-900">${order.customer.name}</div>
                                <div class="text-sm text-gray-500">${order.customer.phone || ''}</div>
                            </div>
                        ` : ''}
                        
                        <div>
                            <div class="text-sm text-gray-500 mb-3">Items</div>
                            <div class="space-y-2">
                                ${order.items.map(item => `
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                        <div>
                                            <div class="font-medium text-gray-900">${item.product.name}</div>
                                            <div class="text-sm text-gray-500">฿${item.unit_price} x ${item.quantity}</div>
                                        </div>
                                        <div class="font-medium text-gray-900">฿${(item.unit_price * item.quantity).toFixed(2)}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-ios-blue">฿${order.total_amount}</span>
                        </div>
                        
                        ${order.status === 'completed' ? `
                            <button onclick="OrdersPage.initiateRefund(${order.id})"
                                    class="w-full py-3 rounded-xl font-semibold text-orange-500 bg-orange-50 hover:bg-orange-100 transition active-scale">
                                <i class="ph ph-arrow-counter-clockwise mr-2"></i> Process Refund
                            </button>
                        ` : ''}
                    </div>
                `;
            }

            DrawerSystem.open('order-drawer');
        } catch (error) {
            console.error('View order error:', error);
            showToast('Failed to load order details', 'error');
        }
    },

    /**
     * Initiate refund process
     * @param {number} orderId - Order ID
     */
    initiateRefund(orderId) {
        const order = this.orders.find(o => o.id === orderId);
        if (!order) return;

        this.currentOrder = order;

        // Set refund modal content
        document.getElementById('refund-order-number').textContent = order.order_number;
        document.getElementById('refund-amount').value = order.total_amount;

        ModalSystem.open('refund-modal');
    },

    /**
     * Process refund
     */
    async processRefund() {
        if (!this.currentOrder) return;

        const reason = document.getElementById('refund-reason').value;
        const amount = document.getElementById('refund-amount').value;

        try {
            const response = await fetch(`/api/orders/${this.currentOrder.id}/refund`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ reason, amount })
            });

            if (response.ok) {
                showToast('Refund processed successfully. Stock has been adjusted.', 'success');
                ModalSystem.close('refund-modal');
                this.loadOrders();
            } else {
                const error = await response.json();
                showToast(error.message || 'Failed to process refund', 'error');
            }
        } catch (error) {
            console.error('Refund error:', error);
            showToast('Failed to process refund', 'error');
        }
    },

    /**
     * Print receipt
     * @param {number} orderId - Order ID
     */
    printReceipt(orderId) {
        window.open(`/orders/${orderId}/receipt`, '_blank');
    }
};

// Make available globally
window.OrdersPage = OrdersPage;

// Initialize on DOM ready only if on orders page
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('orders-table')) {
        OrdersPage.init();
    }
});

export { OrdersPage };

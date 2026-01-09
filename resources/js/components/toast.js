/**
 * Oboun ERP - Toast System
 * Apple-style toast notifications
 */

const ToastSystem = {
    container: null,

    /**
     * Initialize the toast system
     */
    init() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            console.warn('Toast container not found');
        }
    },

    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - Toast type: 'success', 'error', 'warning', 'info'
     * @param {number} duration - Duration in ms (default: 3000)
     */
    show(message, type = 'info', duration = 3000) {
        if (!message) return;

        if (!this.container) {
            this.init();
        }

        if (!this.container) {
            // If still no container, wait for DOM
            document.addEventListener('DOMContentLoaded', () => this.show(message, type, duration), { once: true });
            return;
        }

        const styles = {
            success: { icon: 'ph-check-circle', iconColor: 'text-ios-green' },
            error: { icon: 'ph-warning-circle', iconColor: 'text-ios-red' },
            warning: { icon: 'ph-warning', iconColor: 'text-yellow-500' },
            info: { icon: 'ph-info', iconColor: 'text-ios-blue' }
        };

        const style = styles[type] || styles.info;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="ph-fill ${style.icon} toast-icon ${style.iconColor}"></i>
            <span class="toast-message">${message}</span>
        `;

        this.container.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            toast.classList.add('toast-exit');
            toast.addEventListener('animationend', () => toast.remove());
        }, duration);
    },

    /**
     * Convenience methods
     */
    success(message, duration) {
        this.show(message, 'success', duration);
    },

    error(message, duration) {
        this.show(message, 'error', duration);
    },

    warning(message, duration) {
        this.show(message, 'warning', duration);
    },

    info(message, duration) {
        this.show(message, 'info', duration);
    }
};

// Global function for inline usage
function showToast(message, type = 'info', duration = 3000) {
    ToastSystem.show(message, type, duration);
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    ToastSystem.init();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ToastSystem;
}

export { ToastSystem, showToast };

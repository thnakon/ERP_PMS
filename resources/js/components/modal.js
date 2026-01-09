/**
 * Oboun ERP - Modal System
 * Apple-style modal dialogs
 */

const ModalSystem = {
    activeModal: null,

    /**
     * Open a modal by ID
     * @param {string} modalId - The ID of the modal to open
     */
    open(modalId) {
        const backdrop = document.getElementById(`${modalId}-backdrop`);
        const panel = document.getElementById(`${modalId}-panel`);

        if (!backdrop || !panel) {
            console.warn(`Modal ${modalId} not found`);
            return;
        }

        // Store active modal
        this.activeModal = modalId;

        // Show modal
        backdrop.classList.remove('hidden', 'modal-backdrop-hidden');
        backdrop.classList.add('modal-backdrop-visible');

        // Force reflow for animation
        void backdrop.offsetWidth;

        panel.classList.remove('modal-panel-hidden');
        panel.classList.add('modal-panel-visible');

        // Lock body scroll
        document.body.style.overflow = 'hidden';

        // Trap focus
        this.trapFocus(panel);
    },

    /**
     * Close a modal by ID
     * @param {string} modalId - The ID of the modal to close
     */
    close(modalId) {
        const backdrop = document.getElementById(`${modalId}-backdrop`);
        const panel = document.getElementById(`${modalId}-panel`);

        if (!backdrop || !panel) return;

        // Animate out
        backdrop.classList.remove('modal-backdrop-visible');
        backdrop.classList.add('modal-backdrop-hidden');
        panel.classList.remove('modal-panel-visible');
        panel.classList.add('modal-panel-hidden');

        // Hide after animation
        setTimeout(() => {
            backdrop.classList.add('hidden');
            this.activeModal = null;
            document.body.style.overflow = '';
        }, 300);
    },

    /**
     * Toggle a modal
     * @param {string} modalId - The ID of the modal
     * @param {boolean} show - Force show/hide
     */
    toggle(modalId, show) {
        if (show === true) {
            this.open(modalId);
        } else if (show === false) {
            this.close(modalId);
        } else {
            const backdrop = document.getElementById(`${modalId}-backdrop`);
            if (backdrop && backdrop.classList.contains('hidden')) {
                this.open(modalId);
            } else {
                this.close(modalId);
            }
        }
    },

    /**
     * Close the currently active modal
     */
    closeCurrent() {
        if (this.activeModal) {
            this.close(this.activeModal);
        }
    },

    /**
     * Trap focus inside modal
     * @param {HTMLElement} element - The modal panel element
     */
    trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
    }
};

// Legacy function for inline usage
function toggleModal(show, modalId = 'modal') {
    ModalSystem.toggle(modalId, show);
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && ModalSystem.activeModal) {
        ModalSystem.closeCurrent();
    }
});

// Export for module usage
export { ModalSystem, toggleModal };

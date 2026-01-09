/**
 * Oboun ERP - Drawer System
 * Apple-style slide-over panels
 */

const DrawerSystem = {
    activeDrawer: null,

    /**
     * Open a drawer by ID
     * @param {string} drawerId - The ID of the drawer to open (default: 'drawer')
     */
    open(drawerId = 'drawer') {
        const backdrop = document.getElementById(`${drawerId}-backdrop`);
        const panel = document.getElementById(`${drawerId}-panel`);

        if (!backdrop || !panel) {
            console.warn(`Drawer ${drawerId} not found`);
            return;
        }

        this.activeDrawer = drawerId;

        // Show drawer
        backdrop.classList.remove('hidden', 'drawer-backdrop-hidden');
        backdrop.classList.add('drawer-backdrop-visible');

        // Force reflow
        void backdrop.offsetWidth;

        panel.classList.remove('drawer-panel-hidden');
        panel.classList.add('drawer-panel-visible');

        // Lock body scroll
        document.body.style.overflow = 'hidden';
    },

    /**
     * Close a drawer by ID
     * @param {string} drawerId - The ID of the drawer to close
     */
    close(drawerId = 'drawer') {
        const backdrop = document.getElementById(`${drawerId}-backdrop`);
        const panel = document.getElementById(`${drawerId}-panel`);

        if (!backdrop || !panel) return;

        // Animate out
        backdrop.classList.remove('drawer-backdrop-visible');
        backdrop.classList.add('drawer-backdrop-hidden');
        panel.classList.remove('drawer-panel-visible');
        panel.classList.add('drawer-panel-hidden');

        // Hide after animation
        setTimeout(() => {
            backdrop.classList.add('hidden');
            this.activeDrawer = null;
            document.body.style.overflow = '';
        }, 300);
    },

    /**
     * Toggle drawer visibility
     * @param {boolean} show - Force show/hide
     * @param {string} drawerId - The ID of the drawer
     */
    toggle(show, drawerId = 'drawer') {
        if (show === true) {
            this.open(drawerId);
        } else if (show === false) {
            this.close(drawerId);
        } else {
            const backdrop = document.getElementById(`${drawerId}-backdrop`);
            if (backdrop && backdrop.classList.contains('hidden')) {
                this.open(drawerId);
            } else {
                this.close(drawerId);
            }
        }
    },

    /**
     * Close the currently active drawer
     */
    closeCurrent() {
        if (this.activeDrawer) {
            this.close(this.activeDrawer);
        }
    }
};

// Legacy function for inline usage
function toggleDrawer(show, drawerId = 'drawer') {
    DrawerSystem.toggle(show, drawerId);
}

// Close drawer on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && DrawerSystem.activeDrawer) {
        DrawerSystem.closeCurrent();
    }
});

// Export for module usage
export { DrawerSystem, toggleDrawer };

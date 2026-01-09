/**
 * Oboun ERP - Header System
 * User menu and language toggle
 */

import { showToast } from './toast.js';

const HeaderSystem = {
    userDropdown: null,
    userMenuContainer: null,

    /**
     * Initialize header components
     */
    init() {
        this.userDropdown = document.getElementById('user-dropdown');
        this.userMenuContainer = document.getElementById('user-menu-container');

        // Keyboard shortcut: Cmd+K or Ctrl+K to search
        document.addEventListener('keydown', (event) => {
            if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
                event.preventDefault();
                const search = document.getElementById('global-search');
                if (search) search.focus();
            }
        });

        // Close user menu when clicking outside
        document.addEventListener('click', (event) => {
            if (this.userMenuContainer &&
                !this.userMenuContainer.contains(event.target) &&
                this.userDropdown &&
                !this.userDropdown.classList.contains('hidden')) {
                this.closeUserMenu();
            }
        });
    },

    /**
     * Toggle user menu
     */
    toggleUserMenu() {
        if (!this.userDropdown) this.init();

        if (this.userDropdown.classList.contains('hidden')) {
            // Open
            this.userDropdown.classList.remove('hidden', 'user-dropdown-hidden');
            void this.userDropdown.offsetWidth; // Force reflow
            this.userDropdown.classList.add('user-dropdown-visible');
        } else {
            // Close
            this.closeUserMenu();
        }
    },

    /**
     * Close user menu
     */
    closeUserMenu() {
        if (!this.userDropdown) return;

        this.userDropdown.classList.remove('user-dropdown-visible');
        this.userDropdown.classList.add('user-dropdown-hidden');

        setTimeout(() => {
            this.userDropdown.classList.add('hidden');
        }, 200);
    },

    /**
     * Set application language
     * @param {string} lang - Language code
     */
    toggleLang(lang, btn) {
        if (typeof window.showLoading === 'function') {
            window.showLoading();
        }

        // Wait 2 seconds as requested for language switch
        setTimeout(() => {
            window.location.href = `/lang/${lang}`;
        }, 2000);
    }
};

// Global functions for inline usage
function toggleUserMenu() {
    HeaderSystem.toggleUserMenu();
}

function toggleLang(lang, btn) {
    HeaderSystem.toggleLang(lang, btn);
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    HeaderSystem.init();
});

export { HeaderSystem, toggleUserMenu, toggleLang };

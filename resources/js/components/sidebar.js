/**
 * Oboun ERP - Sidebar System
 */

const SidebarSystem = {
    sidebar: null,
    overlay: null,
    isOpen: false,
    initialized: false,

    /**
     * Initialize sidebar
     */
    init() {
        if (this.initialized) return;

        this.sidebar = document.getElementById('sidebar');
        this.overlay = document.getElementById('sidebar-overlay');
        if (!this.sidebar) return;

        this.initSearch();
        this.initScrollPersistence();
        this.scrollToActive();

        this.initialized = true;
        console.log('✅ Sidebar system initialized');
    },

    /**
     * Persist scroll position across page reloads
     */
    initScrollPersistence() {
        const nav = this.sidebar.querySelector('.sidebar-nav');
        if (!nav) return;

        // Restore scroll position with a slight delay to override browser defaults
        const savedScroll = localStorage.getItem('sidebar-scroll');
        if (savedScroll) {
            // Immediate restoration
            nav.scrollTop = parseInt(savedScroll, 10);

            // Backup restoration after a tick
            setTimeout(() => {
                nav.scrollTop = parseInt(savedScroll, 10);
            }, 100);
        }

        // Save scroll position when clicking any link
        this.sidebar.addEventListener('click', (e) => {
            const link = e.target.closest('.sidebar-link');
            if (link) {
                localStorage.setItem('sidebar-scroll', nav.scrollTop);
            }
        });

        // Save on scroll (throttled)
        let scrollTimeout;
        nav.addEventListener('scroll', () => {
            if (scrollTimeout) return;
            scrollTimeout = setTimeout(() => {
                localStorage.setItem('sidebar-scroll', nav.scrollTop);
                scrollTimeout = null;
            }, 250);
        }, { passive: true });
    },

    /**
     * Ensure active link is visible
     */
    scrollToActive() {
        // If we have a saved scroll position, we prioritize that over auto-scrolling to active
        if (localStorage.getItem('sidebar-scroll')) return;

        const activeLink = this.sidebar.querySelector('.sidebar-link-active');
        if (!activeLink) return;

        const nav = this.sidebar.querySelector('.sidebar-nav');
        const buffer = 100;

        const activeRect = activeLink.getBoundingClientRect();
        const navRect = nav.getBoundingClientRect();

        if (activeRect.top < navRect.top + buffer || activeRect.bottom > navRect.bottom - buffer) {
            activeLink.scrollIntoView({ behavior: 'auto', block: 'center' });
        }
    },

    /**
     * Initialize sidebar search functionalites
     */
    initSearch() {
        const searchInput = document.getElementById('sidebar-search');
        if (!searchInput) return;

        // Listen for '/' shortcut
        document.addEventListener('keydown', (e) => {
            // Only if not in an input/textarea
            if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                searchInput.focus();
            }
        });

        // Simple filtering
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const links = this.sidebar.querySelectorAll('.sidebar-link');
            const sections = this.sidebar.querySelectorAll('.sidebar-nav > div');

            links.forEach(link => {
                const text = link.textContent.toLowerCase();
                const parentLi = link.closest('li');

                if (text.includes(query)) {
                    parentLi.style.display = '';
                    link.style.opacity = '1';
                } else {
                    parentLi.style.display = 'none';
                    link.style.opacity = '0';
                }
            });

            // Hide sections if no visible links
            sections.forEach(section => {
                const visibleLinks = section.querySelectorAll('li:not([style*="display: none"])');
                section.style.display = visibleLinks.length > 0 ? '' : 'none';
            });
        });
    },

    /**
     * Toggle sidebar on mobile
     */
    toggle() {
        if (!this.sidebar) this.init();

        console.log('🔄 Toggling sidebar. Current state:', this.isOpen);
        this.sidebar.classList.toggle('open');
        if (this.overlay) this.overlay.classList.toggle('hidden');
        this.isOpen = !this.isOpen;
        console.log('🆕 New state:', this.isOpen, 'Classes:', this.sidebar.className);
    },

    /**
     * Close sidebar on mobile
     */
    close() {
        if (!this.sidebar) return;

        console.log('🔒 Closing sidebar');
        this.sidebar.classList.remove('open');
        if (this.overlay) this.overlay.classList.add('hidden');
        this.isOpen = false;
    },

    /**
     * Open sidebar on mobile
     */
    open() {
        if (!this.sidebar) this.init();

        console.log('🔓 Opening sidebar');
        this.sidebar.classList.add('open');
        if (this.overlay) this.overlay.classList.remove('hidden');
        this.isOpen = true;
    }
};

// Global function for inline usage
function toggleSidebar() {
    SidebarSystem.toggle();
}



export { SidebarSystem, toggleSidebar };

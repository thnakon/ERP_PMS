/**
 * Oboun ERP - Main Application Entry
 * Imports all components and initializes the application
 */

// Import CSS
import '../css/app.css';

// Import component modules
import { ToastSystem, showToast } from './components/toast.js';
import { ModalSystem, toggleModal } from './components/modal.js';
import { DrawerSystem, toggleDrawer } from './components/drawer.js';
import { SidebarSystem, toggleSidebar } from './components/sidebar.js';
import { HeaderSystem, toggleUserMenu, toggleLang } from './components/header.js';
import { BulkActionsSystem, toggleSelectAll, updateBulkBar, deleteSelected, deleteRow, closeDeleteModal, executeDelete } from './components/bulk-actions.js';
import { AccordionSystem, toggleAccordion } from './components/accordion.js';
import { SegmentSystem, moveSegment } from './components/segment.js';
import { LoadingSystem, showLoading, hideLoading } from './components/loading.js';

// Import page modules
import { DashboardPage } from './pages/dashboard.js';
import { POSPage } from './pages/pos.js';
import { ProductsPage } from './pages/products.js';
import { OrdersPage } from './pages/orders.js';
import { ExpiryPage } from './pages/expiry.js';

// Make functions available globally for inline event handlers
window.showToast = showToast;
window.toggleModal = toggleModal;
window.toggleDrawer = toggleDrawer;
window.toggleSidebar = toggleSidebar;
window.toggleUserMenu = toggleUserMenu;
window.toggleLang = toggleLang;
window.toggleSelectAll = toggleSelectAll;
window.updateBulkBar = updateBulkBar;
window.deleteSelected = deleteSelected;
window.deleteRow = deleteRow;
window.closeDeleteModal = closeDeleteModal;
window.executeDelete = executeDelete;
window.toggleAccordion = toggleAccordion;
window.moveSegment = moveSegment;
window.showLoading = showLoading;
window.hideLoading = hideLoading;

// Make page modules available globally
window.DashboardPage = DashboardPage;
window.POSPage = POSPage;
window.ProductsPage = ProductsPage;
window.OrdersPage = OrdersPage;
window.ExpiryPage = ExpiryPage;
window.ModalSystem = ModalSystem;

// Initialize application
document.addEventListener('DOMContentLoaded', () => {
    console.log('🏥 Oboun ERP initialized');

    // Initialize core systems
    ToastSystem.init();
    SidebarSystem.init();
    HeaderSystem.init();
    LoadingSystem.init();

    // Show welcome toast if first visit
    const isFirstVisit = !localStorage.getItem('obounerp_visited');
    if (isFirstVisit) {
        setTimeout(() => {
            showToast('Welcome to Oboun ERP! 🏥', 'success');
            localStorage.setItem('obounerp_visited', 'true');
        }, 500);
    }
});

// Export for external usage
export {
    ToastSystem,
    ModalSystem,
    DrawerSystem,
    SidebarSystem,
    HeaderSystem,
    BulkActionsSystem,
    AccordionSystem,
    SegmentSystem
};

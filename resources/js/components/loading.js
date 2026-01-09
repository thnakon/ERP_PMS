/**
 * Oboun ERP - Loading System
 * Handles Apple-style loading overlays for form submissions and actions
 */

export const LoadingSystem = {
    init() {
        this.overlay = document.getElementById('pill-loading');
        if (!this.overlay) return;

        // 1. Handle all form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.dataset.noLoading || form.dataset.isSubmitting) return;

            e.preventDefault();
            this.show();

            form.dataset.isSubmitting = 'true';
            setTimeout(() => {
                form.submit();
            }, 1000);
        });

        // 2. Handle primary action buttons/links
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('button, a.btn, a[class*="btn-"], a.px-5, .action-loading, .ios-dropdown-item');
            if (!btn) return;

            const text = btn.textContent.trim().toUpperCase();
            const isAction =
                btn.classList.contains('bg-ios-blue') ||
                btn.classList.contains('bg-orange-500') ||
                btn.classList.contains('bg-red-500') ||
                btn.classList.contains('action-loading') ||
                ['EN', 'TH'].includes(text) ||
                ['ADD', 'EDIT', 'SAVE', 'DELETE', 'CREATE', 'UPDATE', 'RECEIVE', 'LOGOUT', 'SIGN OUT', 'เพิ่ม', 'แก้ไข', 'บันทึก', 'ลบ', 'ยืนยัน', 'รับ', 'ออกจากระบบ'].some(k => text.includes(k));

            if (!isAction) return;
            console.log('Loading triggered for:', text);
            if (btn.hasAttribute('onclick') && (btn.getAttribute('onclick').includes('toggleModal') || btn.getAttribute('onclick').includes('toggleDrawer'))) return;
            if (btn.dataset.noLoading !== undefined) return;

            // Show loading overlay
            this.show();

            // Special handling for links (delay navigation)
            if (btn.tagName === 'A' && btn.href && !btn.href.startsWith('javascript:') && !btn.href.startsWith('#')) {
                e.preventDefault();
                const duration = btn.dataset.loadingDuration ? parseInt(btn.dataset.loadingDuration) : 1000;
                setTimeout(() => {
                    window.location.href = btn.href;
                }, duration);
            }
        });
    },

    show(text = null) {
        if (!this.overlay) return;
        if (text) {
            const textEl = this.overlay.querySelector('.loading-text');
            if (textEl) textEl.textContent = text;
        }
        this.overlay.style.display = 'flex';
        void this.overlay.offsetWidth; // Force reflow
        this.overlay.classList.add('active');
    },

    hide() {
        if (!this.overlay) return;
        this.overlay.classList.remove('active');
    }
};

export const showLoading = (text = null) => LoadingSystem.show(text);
export const hideLoading = () => LoadingSystem.hide();

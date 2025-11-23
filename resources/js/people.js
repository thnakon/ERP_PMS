document.addEventListener('DOMContentLoaded', () => {

    // --- [!!! RENAMED SLIDER SCRIPT !!!] ---
    const toggleContainer = document.querySelector('.people-view-toggle');

    // [!!! MODIFIED] หา view wrapper ที่ "มองเห็นได้" ในหน้านี้
    // (จะหา #patient-list-view หรือ #staff-list-view ก็ได้)
    const listView = document.querySelector('#patient-list-view, #staff-list-view');
    const activityView = document.querySelector('#patient-activity-view, #staff-activity-view');

    // [MOD] ปรับเงื่อนไขเป็น || (หรือ) เพราะในหน้าหนึ่งจะมีแค่อย่างใดอย่างหนึ่ง
    if (toggleContainer && (listView || activityView)) {

        const toggleButtons = toggleContainer.querySelectorAll('.people-toggle-btn');
        const activeButton = toggleContainer.querySelector('.people-toggle-btn.active');

        // ฟังก์ชันสำหรับอัปเดตตำแหน่งและขนาดของ Slider (เหมือนเดิม)
        function updateSlider(activeBtn) {
            if (!activeBtn) return;

            const left = activeBtn.offsetLeft;
            const width = activeBtn.offsetWidth;

            toggleContainer.style.setProperty('--slider-left', `${left}px`);
            toggleContainer.style.setProperty('--slider-width', `${width}px`);
        }

        // 1. ตั้งค่าเริ่มต้นเมื่อโหลดหน้า
        if (activeButton) {
            updateSlider(activeButton);
        }

        // 2. เพิ่ม Event Listener ให้ทุกปุ่ม
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (button.classList.contains('active')) {
                    return;
                }

                toggleButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // อัปเดตตำแหน่ง Slider
                updateSlider(button);

                // --- [!!! LOGIC การสลับ VIEW ที่แก้ไขแล้ว !!!] ---
                const targetView = button.dataset.target; // (จะได้ 'list-view' หรือ 'activity-view')

                // (ซ่อน/แสดง view ที่เราเจอในหน้านี้)
                if (targetView === 'list-view') {
                    if (listView) listView.style.display = 'block';
                    if (activityView) activityView.style.display = 'none';
                } else if (targetView === 'activity-view') {
                    if (listView) listView.style.display = 'none';
                    if (activityView) activityView.style.display = 'block';
                }
                // --- [!!! END LOGIC !!!] ---
            });
        });

        // 3. เพิ่มคลาส 'slider-ready' เพื่อเริ่ม animation (เหมือนเดิม)
        setTimeout(() => {
            toggleContainer.classList.add('slider-ready');
        }, 50);

    } // end if (toggleContainer)

    // --- [!!! NEW: Modal Logic with Animation !!!] ---

    // Helper: Open Modal with Fade In
    function openModal(modalId, mode = 'add', data = null) {
        const modal = document.getElementById(modalId);
        if (modal) {
            // Setup Modal based on Mode (Add/Edit/View)
            const titleEl = modal.querySelector('.sr-modal-title');
            const formInputs = modal.querySelectorAll('input, select, textarea');
            const saveBtn = modal.querySelector('.sr-button-primary');

            if (titleEl) {
                if (mode === 'add') titleEl.textContent = titleEl.textContent.replace('Edit', 'Add').replace('View', 'Add').replace('New', 'Add New'); // Reset
                if (mode === 'edit') titleEl.textContent = 'Edit ' + (modalId.includes('customer') ? 'Customer' : 'Staff');
                if (mode === 'view') titleEl.textContent = 'View ' + (modalId.includes('customer') ? 'Customer' : 'Staff');
            }

            // Reset Form
            if (mode === 'add') {
                formInputs.forEach(input => {
                    input.value = '';
                    input.disabled = false;
                });
                if (saveBtn) saveBtn.style.display = 'inline-flex';
            } else if (mode === 'edit') {
                // Simulate Pre-fill
                formInputs.forEach(input => {
                    input.disabled = false;
                    // In a real app, you'd fill values from 'data' here
                });
                if (saveBtn) saveBtn.style.display = 'inline-flex';
            } else if (mode === 'view') {
                formInputs.forEach(input => {
                    input.disabled = true;
                });
                if (saveBtn) saveBtn.style.display = 'none';
            }

            // Animation: Display -> Opacity
            modal.classList.add('active');
            setTimeout(() => {
                modal.classList.add('visible');
            }, 10);
            document.body.style.overflow = 'hidden';
        }
    }

    // Helper: Close Modal with Fade Out
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('visible');
            setTimeout(() => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }, 400); // Match CSS transition duration
        }
    }

    // 1. Open "Add New" Modal
    const addCustomerBtn = document.querySelector('.sr-header-right .sr-button-primary');
    const modalAddCustomer = document.getElementById('modal-add-customer');
    const modalAddStaff = document.getElementById('modal-add-staff');

    if (addCustomerBtn) {
        addCustomerBtn.addEventListener('click', () => {
            if (modalAddCustomer) openModal('modal-add-customer', 'add');
            if (modalAddStaff) openModal('modal-add-staff', 'add');
        });
    }

    // 2. Handle Inline Actions (View, Edit, Delete)
    document.addEventListener('click', (e) => {
        // Find closest button
        const btn = e.target.closest('.sr-btn-icon');
        if (!btn) return;

        const row = btn.closest('.people-list-row');
        const isCustomerPage = !!document.getElementById('modal-add-customer');
        const modalId = isCustomerPage ? 'modal-add-customer' : 'modal-add-staff';

        if (btn.classList.contains('view')) {
            openModal(modalId, 'view', row);
        } else if (btn.classList.contains('edit')) {
            openModal(modalId, 'edit', row);
        } else if (btn.classList.contains('delete')) {
            if (confirm('Are you sure you want to delete this user?')) {
                // In a real app, send delete request
                row.style.opacity = '0.5';
                row.style.pointerEvents = 'none';
            }
        }
    });

    // 3. Close Modals (Buttons with data-close)
    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.dataset.close;
            closeModal(modalId);
        });
    });

    // 4. Close Modal when clicking outside
    document.querySelectorAll('.sr-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                const modalId = overlay.id;
                closeModal(modalId);
            }
        });
    });

});
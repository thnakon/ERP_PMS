// resources/js/header.js

// [!!! เพิ่มส่วนนี้]
// --- A. Logic ซ่อน Loader เมื่อหน้าโหลดเสร็จ ---
// เราใช้ 'load' (รอรูปโหลดเสร็จ) แทน 'DOMContentLoaded'
window.addEventListener('load', function () {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.classList.add('hidden');
    }
});


document.addEventListener('DOMContentLoaded', function () {

    // ... (1. โค้ด Sidebar) ...
    // ... (2. โค้ด Profile Dropdown) ...
    // ... (3. Logic การค้นหา) ...


    // --- [!!! แก้ไขส่วนนี้] ---
    // --- 4. Logic Page Loader (ตอนคลิก Link) ---

    const pageLoader = document.getElementById('page-loader');

    if (pageLoader) {

        // ดักฟังการคลิกที่ "body"
        document.body.addEventListener('click', function (event) {

            const link = event.target.closest('a');
            if (link) {
                // ... (โค้ดตรวจสอบเงื่อนไข link.target, href ต่างๆ เหมือนเดิม) ...
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank' || event.metaKey || event.ctrlKey) {
                    return;
                }
                const onclickAttr = link.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes('event.preventDefault()')) {
                    return;
                }

                // --- [!!! แก้ไข] ---
                // เปลี่ยนจาก .style.display เป็น .classList.remove
                // pageLoader.style.display = 'flex'; // (ของเดิม)
                pageLoader.classList.remove('hidden'); // (ของใหม่)
            }
        });

        // (แนะนำ) เพิ่มสำหรับ Form Submissions ด้วย
        const allForms = document.querySelectorAll('form');
        allForms.forEach(form => {
            form.addEventListener('submit', function () {
                if (form.target !== '_blank') {
                    // --- [!!! แก้ไข] ---
                    // pageLoader.style.display = 'flex'; // (ของเดิม)
                    pageLoader.classList.remove('hidden'); // (ของใหม่)
                }
            });
        });
    }

    // ... (5. Logic Placeholder Animation) ...

}); // <-- ปิด DOMContentLoaded


// resources/js/header.js

document.addEventListener('DOMContentLoaded', function () {

    // ... (โค้ดเดิมของคุณ: Sidebar, Dropdown, Search, Page Loader, Toast...) ...


    // --- [!!! ADD THIS CODE !!!] ---
    // --- Logic 6: Help Modal ---

    const showButton = document.getElementById('showHelpModalButton');
    const closeButton = document.getElementById('closeHelpModal');
    const overlay = document.getElementById('helpModalOverlay');

    if (showButton && closeButton && overlay) {

        // 1. คลิกปุ่ม "ช่วยเหลือ" (i)
        showButton.addEventListener('click', function () {
            overlay.classList.add('show');
        });

        // 2. คลิกปุ่ม "ปิด" (X)
        closeButton.addEventListener('click', function () {
            overlay.classList.remove('show');
        });

        // 3. คลิกที่ "พื้นหลังเบลอๆ" เพื่อปิด
        overlay.addEventListener('click', function (event) {
            // เช็คว่ากดที่ตัว Overlay จริงๆ (ไม่ใช่ตัว Modal ที่อยู่ข้างใน)
            if (event.target === overlay) {
                overlay.classList.remove('show');
            }
        });
    }

}); // <-- ปิด DOMContentLoaded

document.addEventListener('DOMContentLoaded', function () {

    // --- 1. องค์ประกอบ Modal และปุ่มควบคุม Modal (IDs เดิม) ---
    const logoutModal = document.getElementById('logoutModal');
    const cancelButton = document.getElementById('cancelLogout');
    const confirmButton = document.getElementById('confirmLogout');

    // --- 2. องค์ประกอบปุ่มเปิด Modal (IDs ที่อาจแตกต่างกัน) ---
    // ดักฟังปุ่ม Sidebar (เดิม)
    const openSidebarButton = document.getElementById('open-logout-modal');
    // ดักฟังปุ่ม Header (ใหม่)
    const openHeaderButton = document.getElementById('open-logout-modal-header');

    // สร้าง Array ของปุ่มทั้งหมด
    const allOpenButtons = [];
    if (openSidebarButton) allOpenButtons.push(openSidebarButton);
    if (openHeaderButton) allOpenButtons.push(openHeaderButton);

    // ตรวจสอบว่ามี Modal และปุ่มเปิดอย่างน้อย 1 ปุ่ม
    if (!logoutModal || allOpenButtons.length === 0) {
        return;
    }

    // ฟังก์ชัน Show/Hide Modal (เหมือนเดิม)
    function showModal() {
        logoutModal.classList.add('show');
    }

    function hideModal() {
        logoutModal.classList.remove('show');
    }

    // --- 3. ผูก Event Listener ให้ปุ่มเปิดทั้งหมด ---
    allOpenButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            // A. หา Form ที่อยู่ใกล้ปุ่มที่ถูกคลิก (ไม่ว่าจะเป็น sidebar-form หรือ header-form)
            const formToSubmit = button.closest('form');
            if (!formToSubmit) return;

            // B. ผูก Event Listener กับปุ่มยืนยันใน Modal ชั่วคราว 
            //    เพื่อให้แน่ใจว่ามันจะ Submit Form ที่ถูกต้อง
            confirmButton.onclick = function () {
                formToSubmit.submit(); // Submit Form ที่ถูกต้อง
            };

            showModal(); // แสดง Modal
        });
    });

    // --- 4. ผูก Event Listener ให้ปุ่มควบคุม Modal ---

    // ปุ่มยกเลิก
    cancelButton.addEventListener('click', function () {
        hideModal();
    });

    // ปิด Modal เมื่อคลิกที่พื้นหลัง
    logoutModal.addEventListener('click', function (event) {
        if (event.target === logoutModal) {
            hideModal();
        }
    });
});

// --- [!!! ADD THIS CODE !!!] ---
// --- Logic 7: Apple Landing Page Scroll Animations ---
document.addEventListener('DOMContentLoaded', function () {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); // Run once
            }
        });
    }, observerOptions);

    const fadeElements = document.querySelectorAll('.fade-on-scroll');
    fadeElements.forEach(el => observer.observe(el));
});
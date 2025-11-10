// resources/js/header.js

// [!!! เพิ่มส่วนนี้]
// --- A. Logic ซ่อน Loader เมื่อหน้าโหลดเสร็จ ---
// เราใช้ 'load' (รอรูปโหลดเสร็จ) แทน 'DOMContentLoaded'
window.addEventListener('load', function() {
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
        document.body.addEventListener('click', function(event) {
            
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
            form.addEventListener('submit', function() {
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
        showButton.addEventListener('click', function() {
            overlay.classList.add('show');
        });

        // 2. คลิกปุ่ม "ปิด" (X)
        closeButton.addEventListener('click', function() {
            overlay.classList.remove('show');
        });

        // 3. คลิกที่ "พื้นหลังเบลอๆ" เพื่อปิด
        overlay.addEventListener('click', function(event) {
            // เช็คว่ากดที่ตัว Overlay จริงๆ (ไม่ใช่ตัว Modal ที่อยู่ข้างใน)
            if (event.target === overlay) {
                overlay.classList.remove('show');
            }
        });
    }

}); // <-- ปิด DOMContentLoaded
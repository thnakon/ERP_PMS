/* === 3. JavaScript (Refactored) === */

document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    if (!sidebar) {
        console.error("Sidebar element not found!");
        return;
    }
    
    // --- 1. Sidebar Collapse/Expand Toggle ---
    const toggleBtn = document.getElementById("toggleSidebar");
    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
            
            // (เพิ่ม) เมื่อย่อ sidebar ให้ปิดเมนูย่อยที่เปิดอยู่ทั้งหมด
            if (sidebar.classList.contains("collapsed")) {
                document.querySelectorAll('.sidebar-body .has-submenu.open').forEach(openMenu => {
                    openMenu.classList.remove('open');
                });
            }
        });
    }

    // --- 2. Search Filter (Refactored) ---
    const searchInput = document.getElementById('sidebarSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const filterText = this.value.toLowerCase().trim();
            const allItems = document.querySelectorAll('.sidebar-body .menu-item, .sidebar-body .has-submenu');

            // (ปรับ) ใช้ classList.toggle(className, boolean) เพื่อประสิทธิภาพ
            const isFiltering = filterText !== '';

            allItems.forEach(item => {
                let isMatch = false;
                let hasSubmenuMatch = false;

                // 3.1 ตรวจสอบข้อความในเมนูหลัก (ใช้ .menu-item-content span)
                const mainSpan = item.querySelector('.menu-item-content span');
                if (mainSpan && mainSpan.textContent.toLowerCase().includes(filterText)) {
                    isMatch = true;
                }

                // 3.2 ถ้าเป็นกลุ่มที่มีเมนูย่อย (has-submenu)
                if (item.classList.contains('has-submenu')) {
                    const subItems = item.querySelectorAll('.submenu-item');

                    subItems.forEach(subItem => {
                        const subSpan = subItem.querySelector('span'); // (ปรับ) ค้นหาจาก span
                        let subMatch = false;
                        if (subSpan && subSpan.textContent.toLowerCase().includes(filterText)) {
                            hasSubmenuMatch = true;
                            subMatch = true;
                        }
                        // (ปรับ) สลับคลาสของ .submenu-item
                        subItem.classList.toggle('is-filtered-out', isFiltering && !subMatch);
                    });

                    // ถ้ามี sub-item ตรง หรือ ตัวแม่ (Inventory) ตรง
                    if (hasSubmenuMatch || isMatch) {
                        isMatch = true;
                        if (isFiltering) {
                            item.classList.add('open'); // เปิด submenu อัตโนมัติ
                        }
                    } else if (isFiltering) {
                        item.classList.remove('open'); // ปิดถ้าไม่มีอะไรตรงเลย
                    }
                }

                // 4. สรุปผลการแสดงผล (สลับคลาสของ .menu-item / .has-submenu)
                item.classList.toggle('is-filtered-out', isFiltering && !isMatch);
            });
        });
    }

    // --- 3. Accordion Submenu (Logic เดิม + ปรับปรุง) ---
    document.querySelectorAll('.submenu-toggle').forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            // (ป้องกันการคลิกที่ badge แล้วทำให้เมนูเปิด/ปิด)
            if (e.target.classList.contains('sidebar-badge')) {
                return;
            }

            let clickedParentMenu = toggle.closest('.has-submenu');
            if (!clickedParentMenu) return;

            // 1. ค้นหาเมนูอื่นๆ ที่เปิดค้างไว้
            document.querySelectorAll('.has-submenu.open').forEach(openMenu => {
                // 2. ถ้าเมนูที่เปิดอยู่ ไม่ใช่เมนูที่เราเพิ่งคลิก...
                if (openMenu !== clickedParentMenu) {
                    // 3. ...สั่งปิดมัน
                    openMenu.classList.remove('open');
                }
            });

            // 4. สลับ (toggle) อันที่เราคลิก
            clickedParentMenu.classList.toggle('open');
        });
    });

    // --- 4. (เพิ่ม) Click Outside to Close (for Collapsed Mode) ---
    document.addEventListener('click', function(e) {
        // ถ้า sidebar หดอยู่ และ คลิกนอก sidebar (และไม่ได้คลิกที่ปุ่ม toggle)
        if (sidebar.classList.contains('collapsed') && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            // ปิด .submenu ที่เปิดอยู่ทั้งหมด
            document.querySelectorAll('.has-submenu.open').forEach(openMenu => {
                openMenu.classList.remove('open');
            });
        }
    });

    // --- 5. (สมมติฐาน) Overlay (สำหรับ Mobile) ---
    // (คุณสามารถเพิ่ม JS สำหรับ .sidebar-overlay ที่นี่ ถ้าต้องการ)
    const overlay = document.getElementById('sidebarOverlay');
    if(overlay) {
        // เช่น เมื่อคลิก overlay ให้ซ่อน sidebar (สำหรับ mobile)
        // overlay.addEventListener('click', () => {
        //     sidebar.classList.add('collapsed'); // หรือ 'hidden'
        //     overlay.classList.add('hidden');
        // });
        
        // หรือถ้า toggleBtn ใช้สำหรับ mobile ด้วย
        // toggleBtn.addEventListener('click', () => {
        //     overlay.classList.toggle('hidden');
        // });
    }

}); // <-- ปิด DOMContentLoaded
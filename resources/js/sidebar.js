document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");

    // ย่อ/ขยาย sidebar
    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
    });

    // จัดการเปิด/ปิด submenu (Collections)
    document.querySelectorAll(".has-submenu .submenu-toggle").forEach(toggle => {
        toggle.addEventListener("click", () => {
            const parent = toggle.closest(".has-submenu");
            parent.classList.toggle("open");
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sidebarSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filterText = this.value.toLowerCase().trim();
            
            // 1. เลือกทุกรายการเมนู (ทั้งเมนูหลัก, เมนูย่อย, และปุ่ม logout)
            const allItems = document.querySelectorAll('.sidebar-body .menu-item, .sidebar-body .has-submenu');

            // 2. ถ้าช่องค้นหาว่าง ให้แสดงผลทุกอย่างและปิด Submenu
            if (filterText === '') {
                allItems.forEach(item => {
                    item.style.display = ""; // ใช้ "" เพื่อคืนค่า default (flex หรือ block)
                });
                document.querySelectorAll('.sidebar-body .has-submenu').forEach(submenu => {
                    submenu.classList.remove('open');
                    submenu.querySelectorAll('.submenu-item').forEach(sub => {
                        sub.style.display = ""; // คืนค่า sub-item ด้วย
                    });
                });
                return; // จบการทำงาน
            }

            // 3. ถ้ามีการค้นหา
            allItems.forEach(item => {
                let isMatch = false;
                
                // 3.1 ตรวจสอบข้อความในเมนูหลัก (เช่น "Landmarks", "Support", "Collections", "Log out")
                const mainSpan = item.querySelector('span');
                if (mainSpan && mainSpan.textContent.toLowerCase().includes(filterText)) {
                    isMatch = true;
                }

                // 3.2 ถ้าเป็นกลุ่มที่มีเมนูย่อย (has-submenu)
                if (item.classList.contains('has-submenu')) {
                    let hasSubmenuMatch = false;
                    const subItems = item.querySelectorAll('.submenu-item');
                    
                    subItems.forEach(subItem => {
                        if (subItem.textContent.toLowerCase().includes(filterText)) {
                            subItem.style.display = ""; // แสดง sub-item ที่ตรง
                            hasSubmenuMatch = true;
                        } else {
                            subItem.style.display = "none"; // ซ่อน sub-item ที่ไม่ตรง
                        }
                    });

                    // ถ้ามี sub-item ตรง หรือ ตัวแม่ (Collections) ตรง
                    if (hasSubmenuMatch || isMatch) {
                        isMatch = true;
                        item.classList.add('open'); // เปิด submenu อัตโนมัติ
                    } else {
                        item.classList.remove('open'); // ปิดถ้าไม่มีอะไรตรงเลย
                    }
                }

                // 4. สรุปผลการแสดงผล
                if (isMatch) {
                    item.style.display = ""; // แสดงผลรายการนี้
                } else {
                    item.style.display = "none"; // ซ่อนรายการนี้
                }
            });
        });
    }
});
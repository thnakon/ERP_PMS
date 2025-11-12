/**
 * สคริปต์สำหรับจัดการการสลับแท็บในหน้า Settings
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. หาแท็บและเนื้อหาทั้งหมด
    const navContainer = document.querySelector('.settings-tabs-nav');
    const tabLinks = document.querySelectorAll('.settings-tab-item');
    const tabPanes = document.querySelectorAll('.settings-pane');
    
    // [!!! NEW !!!] หา Pill ที่จะใช้เลื่อน
    const activePill = document.querySelector('.active-pill-background');

    // 2. ตรวจสอบว่ามีแท็บในหน้านี้หรือไม่
    if (!navContainer || !activePill || tabLinks.length === 0 || tabPanes.length === 0) {
        return; // ออกจากฟังก์ชันถ้าองค์ประกอบไม่ครบ
    }

    // [!!! NEW !!!] ฟังก์ชันสำหรับเลื่อน Pill
    function movePill(activeTab) {
        if (!activeTab) return;
        
        // activeTab.offsetLeft คือระยะห่างจากขอบซ้ายของ Nav
        // activeTab.offsetWidth คือความกว้างของแท็บ
        
        activePill.style.width = `${activeTab.offsetWidth}px`;
        activePill.style.left = `${activeTab.offsetLeft}px`;
    }

    // 3. ใส่ Event Listener ให้กับทุกปุ่มแท็บ
    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // ป้องกันการลิงก์ #

            // 3.1) เอาคลาส 'active' ออกจากทุกแท็บและทุกเนื้อหา
            tabLinks.forEach(tab => tab.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // 3.2) เพิ่มคลาส 'active' ให้กับแท็บที่ถูกคลิก
            this.classList.add('active');

            // 3.3) แสดงเนื้อหาที่ตรงกัน
            const tabId = this.getAttribute('data-tab'); 
            const activePane = document.getElementById(tabId);
            if (activePane) {
                activePane.classList.add('active');
            }

            // 3.4) [!!! NEW !!!] สั่งให้ Pill เลื่อนไปที่แท็บที่คลิก
            movePill(this);
        });
    });

    // 4. [!!! NEW !!!] ตั้งค่าเริ่มต้นให้ Pill (สำคัญมาก)
    // หาแท็บที่ active อยู่ตอนโหลดหน้า
    const initialActiveTab = navContainer.querySelector('.settings-tab-item.active');
    if (initialActiveTab) {
        // หน่วงเวลาเล็กน้อยเพื่อให้แน่ใจว่า CSS โหลดเสร็จและคำนวณขนาดถูกต้อง
        setTimeout(() => {
            movePill(initialActiveTab);
            // เพิ่ม transition หลังจากที่ตั้งค่าครั้งแรกเสร็จ
            // (ป้องกันการเลื่อนมาจาก 0,0 ตอนโหลด)
            activePill.style.transition = 'left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        }, 50); // 50ms delay
    }
});


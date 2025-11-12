document.addEventListener('DOMContentLoaded', function() {

    // 1. ค้นหาองค์ประกอบทั้งหมดที่เราต้องการ
    const openButton = document.getElementById('openSupportModalBtn');
    const modalOverlay = document.getElementById('supportModalOverlay');
    const closeButton = document.getElementById('closeSupportModalBtn');

    // 2. ตรวจสอบว่าองค์ประกอบทั้งหมดมีอยู่จริงหรือไม่
    if (!openButton || !modalOverlay || !closeButton) {
        // ถ้าหาไม่เจอ (เช่น อยู่คนละหน้า) ก็ไม่ต้องทำอะไร
        return; 
    }

    // 3. ฟังก์ชันสำหรับเปิด Modal [!!! ADJUSTED !!!]
    function showModal() {
        // เพิ่มคลาส .active ให้ปุ่ม Support
        openButton.classList.add('active');
        
        // (กันหน้าเว็บเลื่อน)
        document.body.style.overflow = 'hidden'; 

        // [!!! FIX !!!]
        // 1. ลบคลาส .is-hiding ออกก่อน (ถ้ามี)
        modalOverlay.classList.remove('is-hiding');
        
        // 2. แสดง Modal (ลบคลาส .hidden)
        // CSS :not(.hidden) จะทำงาน และเริ่มอนิเมชั่น fadeIn
        modalOverlay.classList.remove('hidden');
    }

    // 4. ฟังก์ชันสำหรับปิด Modal [!!! ADJUSTED !!!]
    function hideModal() {
        // เอาคลาส .active ออกจากปุ่ม Support
        openButton.classList.remove('active');
        
        // (คืนค่าให้หน้าเว็บเลื่อนได้)
        document.body.style.overflow = ''; 

        // [!!! FIX !!!]
        // 1. เพิ่มคลาส 'is-hiding' เพื่อเริ่มอนิเมชั่น fadeOut
        modalOverlay.classList.add('is-hiding');

        // 2. "หลังจาก" อนิเมชั่นจบ (300ms) ค่อยซ่อนมันจริงๆ
        setTimeout(() => {
            // 3. เพิ่ม 'hidden' (ที่เป็น opacity: 0)
            modalOverlay.classList.add('hidden');
            // 4. เอาคลาสอนิเมชั่นออก
            modalOverlay.classList.remove('is-hiding');
        }, 300); // 300ms (ต้องตรงกับ aniamtion-duration ใน CSS)
    }

    // 5. เชื่อม Event ให้ปุ่ม "Support"
    openButton.addEventListener('click', function(event) {
        event.preventDefault(); // ป้องกันการเลื่อนไปบนสุด
        showModal();
    });

    // 6. เชื่อม Event ให้ปุ่มปิด "X"
    closeButton.addEventListener('click', function() {
        hideModal();
    });

    // 7. เชื่อม Event ให้ฉากหลัง (Overlay)
    modalOverlay.addEventListener('click', function(event) {
        // ตรวจสอบว่าเราคลิกที่ฉากหลังจริงๆ (ไม่ใช่กล่องเนื้อหา)
        if (event.target === modalOverlay) {
            hideModal();
        }
    });

    // (ทางเลือก) ปิดด้วยปุ่ม Esc
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
            hideModal();
        }
    });

});
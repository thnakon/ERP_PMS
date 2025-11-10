// รอให้หน้าเว็บโหลดเสร็จก่อน
document.addEventListener('DOMContentLoaded', function() {

    // 1. เลือก Navbar
    const nav = document.getElementById('global-nav');

    // 2. ตรวจสอบว่ามี Navbar นี้ในหน้าจริง
    if (nav) {
        
        // 3. สร้างฟังก์ชันสำหรับตรวจสอบการ scroll
        function handleScroll() {
            // ตรวจสอบว่า scroll ลงมาเกิน 10px หรือไม่
            if (window.scrollY > 10) {
                // ถ้าเกิน ให้เพิ่ม class
                nav.classList.add('is-scrolled');
            } else {
                // ถ้าไม่ ให้ลบ class ออก (กลับไปโปร่งใส)
                nav.classList.remove('is-scrolled');
            }
        }

        // 4. สั่งให้ browser คอย "ฟัง" event การ scroll
        window.addEventListener('scroll', handleScroll);
        
        // 5. เรียกใช้ฟังก์ชันนี้ 1 ครั้งตอนโหลดหน้า
        // (เผื่อว่าผู้ใช้ refresh ตอนที่ scroll ค้างอยู่)
        handleScroll();
    }

});

// =============================
// 🔍 Apple-style Search Overlay
// =============================
document.addEventListener('DOMContentLoaded', () => {
    const searchToggle = document.getElementById('search-toggle');
    const overlay = document.getElementById('search-overlay');
    const searchField = document.querySelector('.search-field');
    const closeBtn = document.querySelector('.close-search');

    if (searchToggle && overlay) {
        searchToggle.addEventListener('click', () => {
            overlay.classList.add('active');
            setTimeout(() => searchField.focus(), 400);
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            overlay.classList.remove('active');
        });
    }

    // ปิดเมื่อกด ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') overlay.classList.remove('active');
    });
});

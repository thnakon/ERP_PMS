document.addEventListener('DOMContentLoaded', function() {

    // 1. ค้นหาองค์ประกอบทั้งหมดที่เราต้องการ (ใช้ Class แทน ID สำหรับปุ่มเปิด)
    const openButtons = document.querySelectorAll('.open-support-modal-btn'); // <--- แก้ไขตรงนี้
    const modalOverlay = document.getElementById('supportModalOverlay');
    const closeButton = document.getElementById('closeSupportModalBtn');

    // 2. ตรวจสอบว่าองค์ประกอบทั้งหมดมีอยู่จริงหรือไม่
    if (openButtons.length === 0 || !modalOverlay || !closeButton) {
        return;
    }
    
    // ... ฟังก์ชัน showModal และ hideModal ยังคงเดิม ...
    function showModal() {
        // ... (โค้ด showModal เดิม) ...
        // ในฟังก์ชัน showModal นี้ เราจะไม่สามารถ .classList.add('active') ให้ปุ่มใดปุ่มหนึ่งได้ง่ายๆ
        // หากต้องการสถานะ active ต้องส่งปุ่มที่ถูกคลิกเข้ามาในฟังก์ชันด้วย
        
        // สำหรับตอนนี้ ให้ตัดบรรทัดนี้ออกไปก่อน:
        // openButton.classList.add('active'); 
        
        document.body.style.overflow = 'hidden';
        modalOverlay.classList.remove('is-hiding');
        modalOverlay.classList.remove('hidden');
    }

    function hideModal() {
        // ... (โค้ด hideModal เดิม) ...
        // ตัดบรรทัดนี้ออกไปก่อน:
        // openButton.classList.remove('active'); 
        
        document.body.style.overflow = '';
        modalOverlay.classList.add('is-hiding');
        setTimeout(() => {
            modalOverlay.classList.add('hidden');
            modalOverlay.classList.remove('is-hiding');
        }, 300);
    }
    
    // 5. เชื่อม Event ให้ปุ่ม "Support" ทั้งหมด (ใช้ forEach)
    openButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            showModal();
            // (ถ้าต้องการให้ปุ่มที่ถูกคลิกมีสถานะ active)
            // button.classList.add('active'); 
        });
    });
    
    // ... ส่วนที่เหลือของโค้ดยังคงเดิม ...
    
    // 6. เชื่อม Event ให้ปุ่มปิด "X"
    closeButton.addEventListener('click', function() {
        hideModal();
    });

    // 7. เชื่อม Event ให้ฉากหลัง (Overlay)
    modalOverlay.addEventListener('click', function(event) {
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
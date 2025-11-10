document.addEventListener("DOMContentLoaded", function() {
    
    // เลือก input ทั้งหมดที่เราจะทำ effect
    const formInputs = document.querySelectorAll('.form-input');

    formInputs.forEach(input => {
        const parent = input.closest('.form-field');
        if (!parent) return;

        // 1. ตรวจสอบตั้งแต่ตอนโหลดหน้า
        // ถ้า input มีค่าอยู่แล้ว (เช่นกรอกผิดแล้วเด้งกลับมา) ให้ label ลอยเลย
        if (input.value.trim() !== '' || input.matches(':-webkit-autofill')) {
            parent.classList.add('active');
        }

        // 2. เมื่อคลิก (focus)
        input.addEventListener('focus', () => {
            parent.classList.add('active');
        });

        // 3. เมื่อคลิกออก (blur)
        input.addEventListener('blur', () => {
            // ถ้าช่องว่างเปล่า ให้ label กลับลงมา
            if (input.value.trim() === '') {
                parent.classList.remove('active');
            }
        });

        // 4. จัดการกรณี Autofill ของ Browser (สำคัญ)
        input.addEventListener('animationstart', (e) => {
            if (e.animationName === 'onAutoFillStart') {
                 parent.classList.add('active');
            }
        });
        
        input.addEventListener('animationend', (e) => {
             if (e.animationName === 'onAutoFillCancel') {
                 if (input.value.trim() === '') {
                    parent.classList.remove('active');
                 }
            }
        });
    });
});

// เราต้องเพิ่ม keyframes สำหรับดักจับ autofill ใน CSS ด้วย
// แต่เนื่องจากเราแก้ไขไฟล์ guest.js ผมจะเพิ่ม CSS ผ่าน JS ไปเลย
// หรือคุณจะเอาไปใส่ใน guest.css ก็ได้

const style = document.createElement('style');
style.innerHTML = `
    @keyframes onAutoFillStart { from {} to {} }
    @keyframes onAutoFillCancel { from {} to {} }

    .form-input:-webkit-autofill {
        animation-name: onAutoFillStart;
        animation-fill-mode: both;
    }
    
    .form-input:not(:-webkit-autofill) {
        animation-name: onAutoFillCancel;
        animation-fill-mode: both;
    }
`;
document.head.appendChild(style);


document.addEventListener("DOMContentLoaded", function() {
    const fields = document.querySelectorAll(".form-field");

    fields.forEach(field => {
        const input = field.querySelector(".form-input");

        // เมื่อ focus → เพิ่มคลาส active
        input.addEventListener("focus", () => {
            field.classList.add("active");
        });

        // เมื่อ blur → เอา active ออก ถ้าไม่มีค่า
        input.addEventListener("blur", () => {
            if (input.value === "") {
                field.classList.remove("active");
            }
        });

        // ถ้ามีค่าเดิมจาก old() → ให้ active ตั้งแต่โหลดหน้า
        if (input.value !== "") {
            field.classList.add("active");
        }
    });
});


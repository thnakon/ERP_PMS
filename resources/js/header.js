// resources/js/header.js

document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. โค้ด Sidebar (เดิมของคุณ) ---
    const sidebar = document.getElementById('sidebar');
    const header = document.getElementById('header');

    if (sidebar && header) {
        const observer = new MutationObserver(() => {
            if (sidebar.classList.contains('collapsed')) {
                header.style.left = '85px';
            } else {
                header.style.left = '270px';
            }
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
    
    // --- 2. โค้ด Profile Dropdown (เดิมของคุณ) ---
    const profileButton = document.getElementById('userProfileButton');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileButton && profileDropdown) {
        profileButton.addEventListener('click', (event) => {
            profileDropdown.classList.toggle('show');
            profileButton.classList.toggle('active');
            event.stopPropagation(); 
        });
    }


    // --- [CODE_NEW] 3. Logic การค้นหาทั้งหมด ---
    
    const searchInput = document.getElementById('globalSearch');
    const aiSearchButton = document.getElementById('aiSearchButton');
    const resultsContainer = document.getElementById('liveSearchResults');
    let debounceTimer; // สำหรับหน่วงเวลาการพิมพ์

    if (searchInput && aiSearchButton && resultsContainer) {
        
        // ACTION 1: พิมพ์เพื่อค้นหา (Live Search)
        searchInput.addEventListener('input', () => {
            const query = searchInput.value;
            
            // หน่วงเวลา 300ms ค่อยยิง API
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchLiveResults(query);
            }, 300);
        });

        // ACTION 2: กด Enter (Search Everything)
        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault(); // ป้องกันการ submit form (ถ้ามี)
                const query = searchInput.value;
                if (query.trim() !== '') {
                    // ส่งไปหน้าค้นหาปกติ
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // ACTION 3: คลิกไอคอน Atom (AI Search)
        aiSearchButton.addEventListener('click', () => {
            const query = searchInput.value;
            if (query.trim() !== '') {
                // ส่งไปหน้าค้นหา AI
                window.location.href = `/ai-search?q=${encodeURIComponent(query)}`;
            } else {
                searchInput.focus(); // ถ้าว่าง ให้ focus แทน
            }
        });

        // ฟังก์ชันยิง API ไปยัง Backend (Laravel)
        async function fetchLiveResults(query) {
            if (query.trim().length < 2) {
                resultsContainer.classList.remove('show');
                return;
            }

            try {
                // *** คุณต้องสร้าง Route นี้ใน Laravel ***
                const response = await fetch(`/live-search?q=${encodeURIComponent(query)}`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json(); // รอรับ JSON
                renderResults(data);

            } catch (error) {
                console.error('Fetch error:', error);
                resultsContainer.classList.remove('show');
            }
        }

        // ฟังก์ชันแสดงผลลัพธ์ในกล่อง
        function renderResults(data) {
            resultsContainer.innerHTML = ''; // ล้างของเก่า
            
            if (!data || data.length === 0) {
                resultsContainer.classList.remove('show');
                return;
            }

            // --- (ตัวอย่างการแสดงผล) ---
            // (คุณต้องปรับส่วนนี้ให้ตรงกับ JSON ที่ส่งกลับมาจาก Laravel)
            
            // ตัวอย่าง: ถ้าส่ง JSON หน้าตาแบบนี้
            // { 
            //   "products": [ { "name": "Paracetamol", "url": "/products/1" } ],
            //   "patients": [ { "name": "Evan Yates", "url": "/patients/5" } ]
            // }

            if (data.products && data.products.length > 0) {
                resultsContainer.innerHTML += '<div class="search-result-header">Products</div>';
                data.products.forEach(item => {
                    resultsContainer.innerHTML += `
                        <a href="${item.url}" class="search-result-item">
                            <strong>${item.name}</strong>
                            <small>In Stock</small>
                        </a>`;
                });
            }

            if (data.patients && data.patients.length > 0) {
                resultsContainer.innerHTML += '<div class="search-result-header">Patients</div>';
                data.patients.forEach(item => {
                    resultsContainer.innerHTML += `
                        <a href="${item.url}" class="search-result-item">
                            <strong>${item.name}</strong>
                            <small>Patient Record</small>
                        </a>`;
                });
            }
            // --- (จบตัวอย่าง) ---

            resultsContainer.classList.add('show');
        }

        // ACTION 4: คลิกที่อื่นเพื่อปิด (รวม Logic กับ Profile)
        window.addEventListener('click', (event) => {
            // ปิด Profile Dropdown
            if (profileDropdown && profileDropdown.classList.contains('show')) {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                    profileButton.classList.remove('active');
                }
            }
            
            // ปิด Live Search Results
            if (resultsContainer.classList.contains('show')) {
                if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target)) {
                    resultsContainer.classList.remove('show');
                }
            }
        });
        
        // [!!! ADD THIS CODE !!!]
// --- 5. Logic Placeholder Animation ---

const phrasesToType = [
    "Search...",
    "✨ Ai search...",
    "Search or ✨ Ai search" // <--- (ประโยคเดิมของคุณ)
];
let phraseIndex = 0;
let charIndex = 0;
let isDeleting = false;

const typeSpeed = 120; // ความเร็วในการพิมพ์ (ms)
const deleteSpeed = 80; // ความเร็วในการลบ (ms)
const pauseTime = 2000; // เวลาหยุดพักเมื่อพิมพ์จบ (ms)

function animatePlaceholder() {
    // ดึงประโยคปัจจุบัน
    const currentPhrase = phrasesToType[phraseIndex];
    let speed;

    if (isDeleting) {
        // --- กำลังลบ ---
        searchInput.placeholder = currentPhrase.substring(0, charIndex);
        charIndex--;
        speed = deleteSpeed;
    } else {
        // --- กำลังพิมพ์ ---
        searchInput.placeholder = currentPhrase.substring(0, charIndex);
        charIndex++;
        speed = typeSpeed;
    }

    // --- ตรวจสอบการเปลี่ยนสถานะ ---
    
    if (!isDeleting && charIndex > currentPhrase.length) {
        // พิมพ์จบแล้ว: หยุดพัก แล้วเริ่มลบ
        isDeleting = true;
        speed = pauseTime; // หยุดพัก 2 วินาที
        charIndex = currentPhrase.length; // แก้ไข index
    } else if (isDeleting && charIndex < 0) {
        // ลบจบแล้ว: ไปประโยคถัดไป
        isDeleting = false;
        phraseIndex = (phraseIndex + 1) % phrasesToType.length; // วนกลับไปประโยคแรก
        charIndex = 0; // เริ่มพิมพ์ใหม่
        speed = typeSpeed;
    }

    // เรียกตัวเองซ้ำตามความเร็วที่กำหนด
    setTimeout(animatePlaceholder, speed);
}

// เริ่มต้นแอนิเมชั่น
if (searchInput) {
    animatePlaceholder();
}
    }

}); // <-- ปิด DOMContentLoaded
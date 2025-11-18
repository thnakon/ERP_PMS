document.addEventListener('DOMContentLoaded', function () {
    
    // --- Global Elements ---
    const sidebar = document.getElementById('sidebar');
    const header = document.getElementById('header');
    
    const profileButton = document.getElementById('userProfileButton');
    const profileDropdown = document.getElementById('profileDropdown');

    const searchInput = document.getElementById('globalSearch');
    const aiSearchButton = document.getElementById('aiSearchButton');
    const resultsContainer = document.getElementById('liveSearchResults');
    let debounceTimer; 

    // --- Modal Elements ---
    const openHelpModalBtn = document.getElementById('showHelpModalButton'); // ปุ่มหลักใน Header
    const openSupportDropdownBtn = document.getElementById('openSupportModalBtn'); // ลิงก์ใน Dropdown
    const openFeedbackDropdownBtn = document.getElementById('openFeedbackModalBtn'); // ลิงก์ Feedback
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalOverlay = document.getElementById('appleModalOverlay');
    
    // --- NEW: Panel Elements ---
    const appearancePanelOverlay = document.getElementById('appearancePanelOverlay');
    const languagePanelOverlay = document.getElementById('languagePanelOverlay');
    // Selectors สำหรับปุ่มใน Dropdown
    const openAppearancePanelBtn = document.getElementById('openAppearancePanelBtn');
    const openLanguagePanelBtn = document.getElementById('openLanguagePanelBtn');
    // Selectors สำหรับปุ่ม Back ใน Panel
    const backToProfileBtnAppearance = document.getElementById('backToProfileBtnAppearance');
    const backToProfileBtnLanguage = document.getElementById('backToProfileBtnLanguage');
    
    // --- 1. Sidebar/Header Position Observer ---
    if (sidebar && header) {
        // ใช้ body class แทน MutationObserver เพื่อให้ CSS จัดการง่ายขึ้น
        const body = document.body;
        if (sidebar.classList.contains('collapsed')) {
             body.classList.add("sidebar-collapsed");
        }
        
        const observer = new MutationObserver(() => {
            if (sidebar.classList.contains('collapsed')) {
                body.classList.add("sidebar-collapsed");
            } else {
                body.classList.remove("sidebar-collapsed");
            }
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
    
    // --- 2. Profile Dropdown Logic (เดิม) ---
    if (profileButton && profileDropdown) {
        profileButton.addEventListener('click', (event) => {
            // ปิด Panel ก่อนเปิด Dropdown (ถ้าเปิดอยู่)
            hidePanel(appearancePanelOverlay);
            hidePanel(languagePanelOverlay);

            profileDropdown.classList.toggle('show');
            profileButton.classList.toggle('active');
            event.stopPropagation(); 
        });
    }

    // --- 3. Global Search Logic (เดิม) ---
    if (searchInput && aiSearchButton && resultsContainer) {
        
        searchInput.addEventListener('input', () => {
            const query = searchInput.value;
            
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchLiveResults(query);
            }, 300);
        });

        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                const query = searchInput.value;
                if (query.trim() !== '') {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        aiSearchButton.addEventListener('click', () => {
            const query = searchInput.value;
            if (query.trim() !== '') {
                window.location.href = `/ai-search?q=${encodeURIComponent(query)}`;
            } else {
                searchInput.focus();
            }
        });

        async function fetchLiveResults(query) {
            if (query.trim().length < 2) {
                resultsContainer.classList.remove('show');
                return;
            }

            try {
                // *** คุณต้องสร้าง Route นี้ใน Laravel ***
                const response = await fetch(`/live-search?q=${encodeURIComponent(query)}`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json(); 
                renderResults(data);

            } catch (error) {
                console.error('Fetch error:', error);
                resultsContainer.classList.remove('show');
            }
        }

        function renderResults(data) {
            resultsContainer.innerHTML = ''; 
            
            if (!data || (data.products?.length === 0 && data.patients?.length === 0)) {
                resultsContainer.classList.remove('show');
                return;
            }

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

            resultsContainer.classList.add('show');
        }

        // Logic Placeholder Animation (เดิม)
        const phrasesToType = [
            "Search...",
            "✨ Ai search...",
            "Search or ✨ Ai search"
        ];
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        const typeSpeed = 120;
        const deleteSpeed = 80;
        const pauseTime = 2000;

        function animatePlaceholder() {
            const currentPhrase = phrasesToType[phraseIndex];
            let speed;

            if (isDeleting) {
                searchInput.placeholder = currentPhrase.substring(0, charIndex);
                charIndex--;
                speed = deleteSpeed;
            } else {
                searchInput.placeholder = currentPhrase.substring(0, charIndex);
                charIndex++;
                speed = typeSpeed;
            }
            
            if (!isDeleting && charIndex > currentPhrase.length) {
                isDeleting = true;
                speed = pauseTime;
                charIndex = currentPhrase.length;
            } else if (isDeleting && charIndex < 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrasesToType.length;
                charIndex = 0;
                speed = typeSpeed;
            }

            setTimeout(animatePlaceholder, speed);
        }
        animatePlaceholder();
    }
    
    // --- 4. Modal Logic (Support/Feedback) ---

    // Function to show modal
    function showModal(title = "Help & Support") {
        if (modalOverlay) {
            // อัปเดต Title (ถ้ามีการส่งมา)
            document.getElementById('modalTitle').textContent = title; 
            
            modalOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // ป้องกัน Scroll ด้านหลัง
            
            // ปิด Dropdown ทันทีเมื่อเปิด Modal
            if (profileDropdown && profileDropdown.classList.contains('show')) {
                profileDropdown.classList.remove('show');
                profileButton.classList.remove('active');
            }
            // ปิด Settings Panels ทันทีเมื่อเปิด Modal
            hidePanel(appearancePanelOverlay);
            hidePanel(languagePanelOverlay);
        }
    }

    // Function to hide modal
    function hideModal() {
        if (modalOverlay) {
            modalOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    // Event Listener: Open buttons (Main Header Button)
    if (openHelpModalBtn) {
        openHelpModalBtn.addEventListener('click', () => showModal("Help & Support"));
    }
    
    // Event Listener: Open buttons (Dropdown - Support)
    if (openSupportDropdownBtn) {
        openSupportDropdownBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            showModal("Help & Support");
        });
    }

    // Event Listener: Open buttons (Dropdown - Feedback)
    if (openFeedbackDropdownBtn) {
        // สมมติว่า Feedback ใช้ Modal เดียวกัน แต่เปลี่ยน Title
        openFeedbackDropdownBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showModal("Send Feedback"); 
        });
    }

    // Event Listener: Close button
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', hideModal);
    }

    // Event Listener: Click outside (on overlay)
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            // ปิดเมื่อคลิกที่ Overlay โดยตรงเท่านั้น
            if (e.target === modalOverlay) {
                hideModal();
            }
        });
    }

    // Event Listener: Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (modalOverlay.classList.contains('show')) {
                 hideModal();
            } else if (appearancePanelOverlay.classList.contains('show-panel')) {
                 hidePanel(appearancePanelOverlay);
                 showProfileDropdown();
            } else if (languagePanelOverlay.classList.contains('show-panel')) {
                 hidePanel(languagePanelOverlay);
                 showProfileDropdown();
            }
        }
    });

    // --- 5. NEW: Settings Panel Logic ---

    function showPanel(panelElement) {
        // ปิด Dropdown ก่อน
        if (profileDropdown && profileDropdown.classList.contains('show')) {
            profileDropdown.classList.remove('show');
            profileButton.classList.remove('active');
        }
        // ปิด Panel อื่น
        if (panelElement === appearancePanelOverlay) {
            hidePanel(languagePanelOverlay);
        } else if (panelElement === languagePanelOverlay) {
            hidePanel(appearancePanelOverlay);
        }
        
        // เปิด Panel ที่ต้องการ
        if (panelElement) {
            panelElement.classList.add('show-panel');
        }
    }

    function hidePanel(panelElement) {
        if (panelElement) {
            panelElement.classList.remove('show-panel');
        }
    }

    function showProfileDropdown() {
        if (profileDropdown) {
            profileDropdown.classList.add('show');
            profileButton.classList.add('active');
        }
    }

    // Event Listeners: Open buttons (Appearance)
    if (openAppearancePanelBtn) {
        openAppearancePanelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showPanel(appearancePanelOverlay);
            e.stopPropagation(); // [MODIFIED] ป้องกันการปิดทันทีจาก Global click
        });
        
        // Dummy logic for theme selection
        appearancePanelOverlay.querySelectorAll('.setting-option').forEach(btn => {
            btn.addEventListener('click', () => {
                appearancePanelOverlay.querySelectorAll('.setting-option').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                console.log('Theme selected:', btn.dataset.theme);
            });
        });
    }

    // Event Listeners: Open buttons (Language)
    if (openLanguagePanelBtn) {
        openLanguagePanelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showPanel(languagePanelOverlay);
            e.stopPropagation(); // [MODIFIED] ป้องกันการปิดทันทีจาก Global click
        });

        // Dummy logic for language selection
        languagePanelOverlay.querySelectorAll('.lang-option').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                languagePanelOverlay.querySelectorAll('.lang-option').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                console.log('Language selected:', link.dataset.lang);
            });
        });
    }

    // Event Listeners: Back buttons (MODIFIED: Added stopPropagation)
    if (backToProfileBtnAppearance) {
        backToProfileBtnAppearance.addEventListener('click', (e) => {
            hidePanel(appearancePanelOverlay);
            showProfileDropdown();
            e.stopPropagation(); 
        });
    }
    if (backToProfileBtnLanguage) {
        backToProfileBtnLanguage.addEventListener('click', (e) => {
            hidePanel(languagePanelOverlay);
            showProfileDropdown();
            e.stopPropagation();
        });
    }

    // --- 6. Global Click Outside Logic (อัปเดตเพื่อรองรับ Panel) ---
    window.addEventListener('click', (event) => {
        // ปิด Profile Dropdown
        if (profileDropdown && profileDropdown.classList.contains('show')) {
            // Dropdown ควรปิดเมื่อคลิกนอก Dropdown, ปุ่มเปิด, และ Panels ที่อาจกำลังจะเปิด
            if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target) && 
                !openAppearancePanelBtn.contains(event.target) && !openLanguagePanelBtn.contains(event.target)) {
                profileDropdown.classList.remove('show');
                profileButton.classList.remove('active');
            }
        }
        
        // ปิด Settings Panels (ถ้าคลิกนอกบริเวณ Dropdown, Panels, และปุ่มเปิด)
        // Note: การคลิกปุ่ม Appearance/Language ใน Dropdown จะถูก showPanel จัดการ
        if (appearancePanelOverlay && appearancePanelOverlay.classList.contains('show-panel')) {
            if (!appearancePanelOverlay.contains(event.target) && !openAppearancePanelBtn.contains(event.target)) {
                hidePanel(appearancePanelOverlay);
                // ไม่ต้องเรียก showProfileDropdown() เพราะถือว่าผู้ใช้ต้องการปิดทั้งเมนู
            }
        }

        if (languagePanelOverlay && languagePanelOverlay.classList.contains('show-panel')) {
            if (!languagePanelOverlay.contains(event.target) && !openLanguagePanelBtn.contains(event.target)) {
                hidePanel(languagePanelOverlay);
                // ไม่ต้องเรียก showProfileDropdown() เพราะถือว่าผู้ใช้ต้องการปิดทั้งเมนู
            }
        }
        
        // ปิด Live Search Results (เดิม)
        if (resultsContainer && resultsContainer.classList.contains('show')) {
            if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target) && !aiSearchButton.contains(event.target)) {
                resultsContainer.classList.remove('show');
            }
        }
    });

});
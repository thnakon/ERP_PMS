document.addEventListener('DOMContentLoaded', () => {

    // --- [!!! RENAMED SLIDER SCRIPT !!!] ---
    // (เปลี่ยน .chart-toggle-buttons เป็น .people-view-toggle)
    const toggleContainer = document.querySelector('.people-view-toggle');
    
    // [!!! NEW] ตัวแปรสำหรับ View
    const listView = document.getElementById('list-view');
    const activityView = document.getElementById('activity-view');

    if (toggleContainer && listView && activityView) {
        // (เปลี่ยน .toggle-btn เป็น .people-toggle-btn)
        const toggleButtons = toggleContainer.querySelectorAll('.people-toggle-btn');
        const activeButton = toggleContainer.querySelector('.people-toggle-btn.active');

        // ฟังก์ชันสำหรับอัปเดตตำแหน่งและขนาดของ Slider (เหมือนเดิม)
        function updateSlider(activeBtn) {
            if (!activeBtn) return;
            
            const left = activeBtn.offsetLeft;
            const width = activeBtn.offsetWidth;
            
            toggleContainer.style.setProperty('--slider-left', `${left}px`);
            toggleContainer.style.setProperty('--slider-width', `${width}px`);
        }

        // 1. ตั้งค่าเริ่มต้นเมื่อโหลดหน้า
        if (activeButton) {
            updateSlider(activeButton);
        }

        // 2. เพิ่ม Event Listener ให้ทุกปุ่ม
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (button.classList.contains('active')) {
                    return;
                }
                
                toggleButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // อัปเดตตำแหน่ง Slider
                updateSlider(button);

                // --- [!!! NEW VIEW TOGGLING LOGIC !!!] ---
                const targetViewId = button.dataset.target; // (จะได้ 'list-view' หรือ 'activity-view')

                if (targetViewId === 'list-view') {
                    listView.style.display = 'block';
                    activityView.style.display = 'none';
                } else if (targetViewId === 'activity-view') {
                    listView.style.display = 'none';
                    activityView.style.display = 'block';
                }
                // --- [!!! END NEW LOGIC !!!] ---
            });
        });

        // 3. เพิ่มคลาส 'slider-ready' เพื่อเริ่ม animation (เหมือนเดิม)
        setTimeout(() => {
            toggleContainer.classList.add('slider-ready');
        }, 50);

    } // end if (toggleContainer)

});
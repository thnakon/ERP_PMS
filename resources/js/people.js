document.addEventListener('DOMContentLoaded', () => {

    // --- [!!! RENAMED SLIDER SCRIPT !!!] ---
    const toggleContainer = document.querySelector('.people-view-toggle');
    
    // [!!! MODIFIED] หา view wrapper ที่ "มองเห็นได้" ในหน้านี้
    // (จะหา #patient-list-view หรือ #staff-list-view ก็ได้)
    const listView = document.querySelector('#patient-list-view, #staff-list-view');
    const activityView = document.querySelector('#patient-activity-view, #staff-activity-view');

    // [MOD] ปรับเงื่อนไขเป็น || (หรือ) เพราะในหน้าหนึ่งจะมีแค่อย่างใดอย่างหนึ่ง
    if (toggleContainer && (listView || activityView)) { 
        
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

                // --- [!!! LOGIC การสลับ VIEW ที่แก้ไขแล้ว !!!] ---
                const targetView = button.dataset.target; // (จะได้ 'list-view' หรือ 'activity-view')

                // (ซ่อน/แสดง view ที่เราเจอในหน้านี้)
                if (targetView === 'list-view') {
                    if (listView) listView.style.display = 'block';
                    if (activityView) activityView.style.display = 'none';
                } else if (targetView === 'activity-view') {
                    if (listView) listView.style.display = 'none';
                    if (activityView) activityView.style.display = 'block';
                }
                // --- [!!! END LOGIC !!!] ---
            });
        });

        // 3. เพิ่มคลาส 'slider-ready' เพื่อเริ่ม animation (เหมือนเดิม)
        setTimeout(() => {
            toggleContainer.classList.add('slider-ready');
        }, 50);

    } // end if (toggleContainer)

});
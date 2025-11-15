/**
 * JavaScript สำหรับหน้า Sales Report (sale-report.js)
 *
 * เราจะใช้ Chart.js เพื่อวาดกราฟ
 * เราต้องรอให้ DOM โหลดเสร็จก่อน
 */

document.addEventListener('DOMContentLoaded', () => {

    // ตรวจสอบว่ามี Canvas ID อยู่ในหน้าหรือไม่
    const salesChartCtx = document.getElementById('salesOverTimeChart');
    const categoriesChartCtx = document.getElementById('topCategoriesChart');

    // 1. กราฟเส้น: ยอดขายตามช่วงเวลา (Sales Over Time)
    if (salesChartCtx) {
        // ข้อมูล Mockup สำหรับกราฟ
        const salesData = {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            datasets: [{
                label: 'ยอดขาย (บาท)',
                data: [65000, 59000, 80000, 81000, 56000],
                fill: true,
                borderColor: '#007aff', // สีฟ้า Apple
                backgroundColor: 'rgba(0,122,255,0.1)', // สีฟ้าจางๆ
                tension: 0.3, // ทำให้เส้นโค้ง
                pointBackgroundColor: '#007aff',
                pointBorderColor: '#ffffff',
                pointHoverRadius: 6,
                pointHoverBorderWidth: 2,
            }]
        };

        new Chart(salesChartCtx, {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // ซ่อน Legend
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { weight: 'bold' },
                        bodySpacing: 4,
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false // ซ่อนเส้นตารางแกน X
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e5e5', // สีเส้นตารางจางๆ
                            borderDash: [2, 4], // ทำให้เป็นเส้นประ
                        }
                    }
                }
            }
        });
    }

    // 2. กราฟวงกลม: ยอดขายตามหมวดหมู่ (Top Categories)
    if (categoriesChartCtx) {
        // ข้อมูล Mockup สำหรับกราฟ
        const categoriesData = {
            labels: ['ยาอันตราย', 'เวชสำอาง', 'อุปกรณ์การแพทย์', 'อาหารเสริม', 'อื่นๆ'],
            datasets: [{
                label: 'ยอดขาย',
                data: [40, 25, 15, 10, 10], // %
                backgroundColor: [
                    '#FF3B30', // แดง
                    '#5E5CE6', // ม่วง
                    '#34C759', // เขียว
                    '#FF9F0A', // ส้ม
                    '#6e6e73', // เทา
                ],
                borderWidth: 0, // ไม่มีเส้นขอบ
                hoverOffset: 10
            }]
        };

        new Chart(categoriesChartCtx, {
            type: 'doughnut', // หรือ 'pie'
            data: categoriesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom', // ย้าย Legend ไปด้านล่าง
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20
                        }
                    }
                }
            }
        });
    }

    // (ในอนาคต) ที่นี่คือที่ที่คุณจะใส่ Logic
    // - การดึงข้อมูล Report ด้วย AJAX เมื่อกดปุ่ม "ใช้ตัวกรอง"
    // - การอัปเดต KPI และ กราฟ ด้วยข้อมูลใหม่
    // - การเปิด/ปิด Date Picker แบบ Custom

});

document.addEventListener('DOMContentLoaded', () => {

    const profitCogsCtx = document.getElementById('profitCogsChart');

    if (profitCogsCtx) {
        // Mockup Data
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
        
        new Chart(profitCogsCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue (บาท)',
                        data: [120000, 150000, 140000, 160000, 150000],
                        borderColor: '#007aff', // สีฟ้า Apple (Accent)
                        backgroundColor: 'rgba(0,122,255,0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'COGS (บาท)',
                        data: [80000, 100000, 95000, 110000, 100000],
                        borderColor: '#FF9F0A', // สีส้ม
                        backgroundColor: 'rgba(255,159,10,0.1)',
                        fill: true,
                        tension: 0.3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        cornerRadius: 8,
                        padding: 12,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e5e5',
                            borderDash: [2, 4],
                        }
                    }
                }
            }
        });
    }

});

/**
 * JavaScript สำหรับหน้า Inventory Report (inventory-report.js)
 *
 * 1. ควบคุม Sliding Toggle Tabs
 * 2. วาดกราฟ Stock by Category
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- 1. Logic สำหรับ Sliding Toggle Tabs ---
    const tabsContainer = document.getElementById('inventory-tabs');
    const tabButtons = tabsContainer?.querySelectorAll('.toggle-btn');
    const tabPanes = document.querySelectorAll('.inventory-tab-content .tab-pane');

    if (tabsContainer && tabButtons && tabPanes) {

        // ฟังก์ชันสำหรับเลื่อนตัวสไลเดอร์
        const moveSlider = (targetButton) => {
            const left = targetButton.offsetLeft;
            const width = targetButton.offsetWidth;

            tabsContainer.style.setProperty('--slider-left', `${left}px`);
            tabsContainer.style.setProperty('--slider-width', `${width}px`);
        };

        // ฟังก์ชันสำหรับสลับ Tab
        const switchTab = (targetButton) => {
            // 1. (Buttons)
            // ลบ active class ออกจากปุ่มทั้งหมด
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // เพิ่ม active class ให้ปุ่มที่กด
            targetButton.classList.add('active');

            // 2. (Content)
            const targetContentId = targetButton.dataset.target; // (เช่น "#expiryReportContent")
            
            // ซ่อน content ทั้งหมด
            tabPanes.forEach(pane => {
                pane.style.display = 'none';
                pane.classList.remove('active');
            });
            
            // แสดง content ที่ต้องการ
            const targetPane = document.querySelector(targetContentId);
            if (targetPane) {
                targetPane.style.display = 'block';
                targetPane.classList.add('active');
            }

            // 3. (Slider)
            moveSlider(targetButton);
        };

        // 4. (Event Listeners)
        // เพิ่ม click event ให้ทุกปุ่ม
        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                switchTab(e.currentTarget);
            });
        });

        // 5. (Initialization)
        // ตั้งค่า Slider ไปที่ปุ่ม active (ปุ่มแรก) ตอนโหลดหน้า
        const activeButton = tabsContainer.querySelector('.toggle-btn.active');
        if (activeButton) {
            moveSlider(activeButton);
        }

        // 6. (Enable Transition)
        // เพิ่มคลาส .slider-ready หลังจากโหลดเสร็จ
        // เพื่อให้ transition CSS ทำงาน (ป้องกันการกระตุกตอนโหลด)
        setTimeout(() => {
            tabsContainer.classList.add('slider-ready');
        }, 50); // หน่วงเวลาเล็กน้อย
    }


    // --- 2. Logic สำหรับกราฟ Stock by Category (ใน Tab 4) ---
    const stockChartCtx = document.getElementById('stockByCategoryChart');

    if (stockChartCtx) {
        new Chart(stockChartCtx, {
            type: 'bar',
            data: {
                labels: ['ยาอันตราย', 'เวชสำอาง', 'อุปกรณ์การแพทย์', 'อาหารเสริม', 'อื่นๆ'],
                datasets: [{
                    label: 'มูลค่าสต็อก (บาท)',
                    data: [450000, 300000, 150000, 250000, 50000],
                    backgroundColor: [
                        '#007aff',
                        '#34c759',
                        '#ff9f0a',
                        '#5e5ce6',
                        '#6e6e73'
                    ],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // ทำให้เป็นกราฟแท่งแนวนอน
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e5e5',
                            borderDash: [2, 4],
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

});
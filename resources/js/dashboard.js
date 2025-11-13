/*
 * resources/js/dashboard.js
 * JavaScript for dashboard interactivity (e.g., charts)
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. Sales Chart (using Chart.js)
    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        const ctx = salesChartCanvas.getContext('2d');

        // สร้าง Gradient (สไตล์ Apple)
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 122, 255, 0.3)'); // --apple-blue with opacity
        gradient.addColorStop(1, 'rgba(0, 122, 255, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sales',
                    data: [8200, 9500, 11000, 10500, 14000, 12450, 15000], // ข้อมูลตัวอย่าง
                    borderColor: '#007aff', // --apple-blue
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4, // ทำให้เส้นโค้งมน
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#007aff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // ซ่อน legend (เราทำเองใน HTML)
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#ffffff',
                        titleColor: '#1d1d1f',
                        bodyColor: '#1d1d1f',
                        borderColor: '#e8e8ea',
                        borderWidth: 1,
                        displayColors: false,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    // จัดฟอร์แมตตัวเลขเป็นสกุลเงิน (ตัวอย่าง)
                                    label += new Intl.NumberFormat('th-TH', {
                                        style: 'currency',
                                        currency: 'THB'
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6e6e73', // --text-secondary
                            font: {
                                family: '"SF Pro Text", sans-serif',
                                size: 12,
                            },
                            // ฟอร์แมตตัวเลขแกน Y
                            callback: function(value, index, values) {
                                return '฿' + (value / 1000) + 'k';
                            }
                        },
                        grid: {
                            color: '#e8e8ea', // --border-color
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6e6e73', // --text-secondary
                            font: {
                                family: '"SF Pro Text", sans-serif',
                                size: 12,
                            }
                        },
                        grid: {
                            display: false // ซ่อนเส้น grid แกน X
                        }
                    }
                }
            }
        });
    }

    // 2. Sidebar Logic (จากโค้ดเดิมของคุณ)
    // หาก app.js ของคุณ handle ส่วนนี้อยู่แล้ว อาจไม่จำเป็นต้องใส่ซ้ำ
    // แต่เพื่อความสมบูรณ์ ผมขอนำ logic การเปิด/ปิด submenu มาใส่ไว้
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const parent = toggle.closest('.has-submenu');
            parent.classList.toggle('active');

            const submenu = parent.querySelector('.submenu');
            if (submenu.style.maxHeight) {
                submenu.style.maxHeight = null;
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
            }
        });
    });

    // (Optional) Logic สำหรับปุ่ม toggle sidebar หลัก (ถ้ามี)
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('collapsed');
            overlay.classList.add('hidden');
        });
    }

});

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');

    if (sidebar && toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }
});
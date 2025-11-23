/*
 * resources/js/dashboard.js
 * JavaScript for dashboard interactivity (e.g., charts, sliding toggles)
 * [!!! FULLY REVISED FILE !!!]
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. Sales Chart (using Chart.js)
    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        const ctx = salesChartCanvas.getContext('2d');
        let salesChartInstance = null; // To hold the chart object

        // --- 1. Data Definitions ---
        const chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        const metricsData = {
            sales: {
                label: 'Sales',
                data: [8200, 9500, 11000, 10500, 14000, 12450, 15000],
                borderColor: '#007aff', // --apple-blue
                gradientColor: 'rgba(0, 122, 255, 0.3)',
                yAxisFormatter: (value) => '฿' + (value / 1000) + 'k'
            },
            profit: {
                label: 'Profit',
                data: [3100, 3800, 4200, 4000, 5500, 4820, 5800], // ข้อมูลตัวอย่าง
                borderColor: '#34c759', // --apple-green
                gradientColor: 'rgba(52, 199, 89, 0.3)',
                yAxisFormatter: (value) => '฿' + (value / 1000) + 'k'
            },
            outOfStock: {
                label: 'Out of Stock',
                data: [5, 6, 6, 7, 9, 12, 12], // ข้อมูลตัวอย่าง
                borderColor: '#ff9500', // --apple-orange
                gradientColor: 'rgba(255, 149, 0, 0.3)',
                yAxisFormatter: (value) => value // แสดงตัวเลขธรรมดา
            }
        };

        // --- 2. Utility Functions ---

        /**
         * สร้าง Gradient (สไตล์ Apple)
         */
        function createChartGradient(color) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, color);
            gradient.addColorStop(1, color.replace(/, 0.3\)$/, ', 0)')); // Fade to transparent
            return gradient;
        }

        /**
         * จัดการ Tooltip
         */
        const tooltipLabelCallback = (context) => {
            let label = context.dataset.label || '';
            if (label) {
                label += ': ';
            }
            if (context.parsed.y !== null) {
                const metric = context.dataset.metric;

                if (metric === 'sales' || metric === 'profit') {
                    label += new Intl.NumberFormat('th-TH', {
                        style: 'currency',
                        currency: 'THB',
                        maximumFractionDigits: 0
                    }).format(context.parsed.y);
                } else {
                    label += context.parsed.y + ' Items';
                }
            }
            return label;
        };

        // --- 3. Chart Update Function ---

        /**
         * อัปเดตข้อมูลกราฟตาม metric ที่เลือก
         */
        function updateChart(metric) {
            if (!salesChartInstance) return; // ถ้ากราฟยังไม่ถูกสร้าง

            const metricConfig = metricsData[metric];
            if (!metricConfig) {
                console.error("Invalid metric:", metric);
                return;
            }

            const newGradient = createChartGradient(metricConfig.gradientColor);

            salesChartInstance.data.datasets[0].data = metricConfig.data;
            salesChartInstance.data.datasets[0].label = metricConfig.label;
            salesChartInstance.data.datasets[0].borderColor = metricConfig.borderColor;
            salesChartInstance.data.datasets[0].backgroundColor = newGradient;
            salesChartInstance.data.datasets[0].pointBorderColor = metricConfig.borderColor;
            salesChartInstance.data.datasets[0].metric = metric;

            salesChartInstance.options.scales.y.ticks.callback = metricConfig.yAxisFormatter;

            salesChartInstance.update();
        }

        // --- 4. Chart Initialization ---

        function initChart() {
            const initialMetric = 'sales';
            const initialConfig = metricsData[initialMetric];

            salesChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: initialConfig.label,
                        data: initialConfig.data,
                        borderColor: initialConfig.borderColor,
                        backgroundColor: createChartGradient(initialConfig.gradientColor),
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: initialConfig.borderColor,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        metric: initialMetric
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
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
                                label: tooltipLabelCallback
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#6e6e73',
                                font: { family: '"SF Pro Text", sans-serif', size: 12 },
                                callback: initialConfig.yAxisFormatter
                            },
                            grid: {
                                color: '#e8e8ea',
                                drawBorder: false,
                            }
                        },
                        x: {
                            ticks: {
                                color: '#6e6e73',
                                font: { family: '"SF Pro Text", sans-serif', size: 12 }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // --- 5. Event Listeners (Toggle Buttons) ---
        const toggleButtonsContainer = document.querySelector('.chart-toggle-buttons');
        const toggleButtons = document.querySelectorAll('.chart-toggle-buttons .toggle-btn');

        // [!!! NEW !!!] ฟังก์ชันสำหรับขยับสไลเดอร์
        function moveSlider(targetButton) {
            if (!targetButton || !toggleButtonsContainer) return;

            const sliderLeft = targetButton.offsetLeft;
            const sliderWidth = targetButton.offsetWidth;

            // อัปเดต CSS Variables
            toggleButtonsContainer.style.setProperty('--slider-left', `${sliderLeft}px`);
            toggleButtonsContainer.style.setProperty('--slider-width', `${sliderWidth}px`);
        }


        if (toggleButtonsContainer) {
            toggleButtonsContainer.addEventListener('click', (e) => {
                const clickedButton = e.target.closest('.toggle-btn');
                if (!clickedButton) return;

                if (clickedButton.classList.contains('active')) return;

                const metric = clickedButton.dataset.metric;
                if (!metric) return;

                // อัปเดตสไตล์ปุ่ม
                toggleButtons.forEach(btn => btn.classList.remove('active'));
                clickedButton.classList.add('active');

                // [!!! UPDATE !!!] เรียกใช้ฟังก์ชันสไลเดอร์
                moveSlider(clickedButton);

                // อัปเดต กราฟ
                updateChart(metric);
            });
        }

        // --- KICK-OFF ---
        initChart(); // สร้างกราฟครั้งแรก

        // [!!! NEW !!!] ตั้งค่าสไลเดอร์ไปที่ปุ่ม active ตัวแรกเมื่อโหลดหน้า
        const initialActiveButton = document.querySelector('.chart-toggle-buttons .toggle-btn.active');
        if (initialActiveButton) {
            setTimeout(() => {
                moveSlider(initialActiveButton);
                toggleButtonsContainer.classList.add('slider-ready');
            }, 50);
        } else if (toggleButtons.length > 0) {
            toggleButtons[0].classList.add('active');
            setTimeout(() => {
                moveSlider(toggleButtons[0]);
                toggleButtonsContainer.classList.add('slider-ready');
            }, 50);
        }

    } // End if (salesChartCanvas)


    // 2. Sidebar Logic (ส่วนเมนูย่อย Submenu)
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const parent = toggle.closest('.has-submenu');
            parent.classList.toggle('active'); // <-- คุณอาจจะใช้ 'open' หรือ 'active'

            const submenu = parent.querySelector('.submenu');
            if (submenu.style.maxHeight) {
                submenu.style.maxHeight = null;
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
            }
        });
    });

});
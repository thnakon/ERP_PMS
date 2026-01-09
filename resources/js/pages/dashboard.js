/**
 * Oboun ERP - Dashboard Page
 * Chart rendering and KPI updates
 */

import { showToast } from '../components/toast.js';

const DashboardPage = {
    chartData: null,

    /**
     * Initialize dashboard
     */
    init() {
        this.initCharts();
        this.initKPICards();
    },

    /**
     * Initialize chart bars with animation
     */
    initCharts() {
        const chartBars = document.querySelectorAll('.chart-bar');

        // Animate bars on load
        chartBars.forEach((bar, index) => {
            const originalHeight = bar.style.height || bar.offsetHeight + 'px';
            bar.style.height = '0';

            setTimeout(() => {
                bar.style.height = originalHeight;
            }, 100 + (index * 100));
        });
    },

    /**
     * Initialize KPI card hover effects
     */
    initKPICards() {
        const kpiCards = document.querySelectorAll('.kpi-card');

        kpiCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('card-ios-hover');
            });

            card.addEventListener('mouseleave', () => {
                card.classList.remove('card-ios-hover');
            });
        });
    },

    /**
     * Refresh dashboard data
     */
    async refresh() {
        try {
            showToast('Refreshing dashboard...', 'info');

            const response = await fetch('/api/dashboard/stats');
            const data = await response.json();

            this.updateKPIs(data.kpis);
            this.updateCharts(data.charts);

            showToast('Dashboard updated', 'success');
        } catch (error) {
            showToast('Failed to refresh dashboard', 'error');
            console.error('Dashboard refresh error:', error);
        }
    },

    /**
     * Update KPI values
     * @param {Object} kpis - KPI data object
     */
    updateKPIs(kpis) {
        if (kpis.revenue) {
            const revenueEl = document.getElementById('kpi-revenue');
            if (revenueEl) revenueEl.textContent = kpis.revenue;
        }

        if (kpis.orders) {
            const ordersEl = document.getElementById('kpi-orders');
            if (ordersEl) ordersEl.textContent = kpis.orders;
        }

        if (kpis.alerts) {
            const alertsEl = document.getElementById('kpi-alerts');
            if (alertsEl) alertsEl.textContent = kpis.alerts;
        }
    },

    /**
     * Update chart data
     * @param {Array} chartData - Chart data array
     */
    updateCharts(chartData) {
        const chartBars = document.querySelectorAll('.chart-bar');

        if (chartData && chartData.length) {
            chartData.forEach((value, index) => {
                if (chartBars[index]) {
                    const maxValue = Math.max(...chartData);
                    const percentage = (value / maxValue) * 100;
                    chartBars[index].style.height = percentage + '%';
                }
            });
        }
    }
};

// Initialize on DOM ready only if on dashboard page
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.kpi-card')) {
        DashboardPage.init();
    }
});

export { DashboardPage };

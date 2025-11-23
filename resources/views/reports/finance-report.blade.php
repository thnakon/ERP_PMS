{{-- 
  * resources/views/reports/finance-report.blade.php
  * หน้ารายงานการเงิน (Financial Report)
  * ใช้คลาส CSS 'sr-' จาก sale-report.css ทั้งหมด
--}}

<x-app-layout>
    {{-- [!!!] 1. ส่วน Header ของหน้า --}}


    {{-- [!!!] 2. ส่วนเนื้อหาหลัก (Container) --}}
    <div class="settings-layout">
        <div class="sr-container-f">
            {{-- [!!! REFACTORED HEADER !!!] --}}
            <div class="sr-header">
                <div class="sr-header-left">
                    <p class="sr-breadcrumb">Dashboard / Settings / Financial-Report</p>
                    <h2 class="sr-page-title">Financial Report</h2>
                </div>
                <div class="sr-header-right">
                    {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
             และใช้คลาสใหม่ sr-button-primary --}}
                    <button class="sr-button-primary" id="btn-add-finance-report">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add New Report</span>
                    </button>
                </div>
            </div>

            {{-- 2.1 Filters --}}
            <div class="sr-filters-wrapper">
                <div class="sr-filter-group">
                    <label for="date-range"><i class="fa-solid fa-calendar-days"></i> Date Range</label>
                    <select id="date-range" class="sr-select">
                        <option value="this-month">This Month</option>
                        <option value="last-month">Last Month</option>
                        <option value="this-quarter">This Quarter</option>
                        <option value="this-year">This Year</option>
                    </select>
                </div>

                <button class="sr-button-primary sr-filter-apply-btn" style="align-self: flex-end;">
                    Apply Filter
                </button>
            </div>

            {{-- 2.2 KPIs Grid --}}
            <div class="sr-kpi-grid">
                {{-- KPI 1: Total Revenue --}}
                <div class="sr-kpi-card">
                    <div class="sr-kpi-icon-bg" style="--icon-bg: #e6f2ff; --icon-color: #007aff;">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div class="sr-kpi-content">
                        <span class="sr-kpi-title">TOTAL REVENUE</span>
                        <span class="sr-kpi-value">฿150,000.00</span>
                        <span class="sr-kpi-delta sr-delta-positive">+5.2%</span>
                    </div>
                </div>
                {{-- KPI 2: COGS --}}
                <div class="sr-kpi-card">
                    <div class="sr-kpi-icon-bg" style="--icon-bg: #ffeee6; --icon-color: #ff9f0a;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="sr-kpi-content">
                        <span class="sr-kpi-title">COST OF GOODS SOLD (COGS)</span>
                        <span class="sr-kpi-value">฿100,000.00</span>
                        <span class="sr-kpi-delta sr-delta-neutral">-1.1%</span>
                    </div>
                </div>
                {{-- KPI 3: Gross Profit --}}
                <div class="sr-kpi-card">
                    <div class="sr-kpi-icon-bg" style="--icon-bg: #e6fff0; --icon-color: #34c759;">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div class="sr-kpi-content">
                        <span class="sr-kpi-title">GROSS PROFIT</span>
                        <span class="sr-kpi-value">฿50,000.00</span>
                        <span class="sr-kpi-delta sr-delta-positive">+15.8%</span>
                    </div>
                </div>
                {{-- KPI 4: Profit Margin --}}
                <div class="sr-kpi-card">
                    <div class="sr-kpi-icon-bg" style="--icon-bg: #eef0ff; --icon-color: #5e5ce6;">
                        <i class="fa-solid fa-percent"></i>
                    </div>
                    <div class="sr-kpi-content">
                        <span class="sr-kpi-title">PROFIT MARGIN</span>
                        <span class="sr-kpi-value">25.00%</span>
                        <span class="sr-kpi-delta sr-delta-positive">+2.5%</span>
                    </div>
                </div>
            </div>

            {{-- 2.3 Widgets Grid --}}
            <div class="sr-widgets-grid">
                {{-- Widget 1: P&L Summary (Simple List) --}}
                <div class="sr-widget-card">
                    <h3 class="sr-widget-title">Profit & Loss Summary</h3>
                    {{-- ใช้ List ธรรมดาแทนตาราง จะดูคลีนกว่า --}}
                    <ul style="display: flex; flex-direction: column; gap: 16px; list-style-type: none; padding: 0;">
                        <li
                            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                            <span style="color: var(--text-secondary);">1. Total Revenue</span>
                            <span style="font-weight: 600; font-size: 1rem;">฿150,000.00</span>
                        </li>
                        <li
                            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                            <span style="color: var(--text-secondary);">2. COGS</span>
                            <span
                                style="font-weight: 600; font-size: 1rem; color: var(--negative-delta);">(฿100,000.00)</span>
                        </li>
                        <li
                            style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px;">
                            <span style="font-weight: 600;">Gross Profit</span>
                            <span
                                style="font-weight: 700; font-size: 1.1rem; color: var(--positive-delta);">฿50,000.00</span>
                        </li>
                    </ul>
                </div>

                {{-- Widget 2: Chart --}}
                <div class="sr-widget-card">
                    <h3 class="sr-widget-title">Revenue vs COGS</h3>
                    <div class="sr-chart-container" style="height: 300px;">
                        <canvas id="profitCogsChart"></canvas>
                    </div>
                </div>

                {{-- Widget 3: Profit by Category (Full Width) --}}
                <div class="sr-widget-card sr-widget-full-width">
                    <h3 class="sr-widget-title">Profit by Category</h3>
                    <div class="sr-table-container">
                        <table class="sr-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Revenue</th>
                                    <th>COGS</th>
                                    <th>Profit (THB)</th>
                                    <th>Margin (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>เวชสำอาง</td>
                                    <td>฿40,000</td>
                                    <td>฿20,000</td>
                                    <td>฿20,000</td>
                                    <td><span style="color: var(--positive-delta); font-weight: 600;">50.0%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ยาอันตราย</td>
                                    <td>฿60,000</td>
                                    <td>฿45,000</td>
                                    <td>฿15,000</td>
                                    <td>25.0%</td>
                                </tr>
                                <tr>
                                    <td>อาหารเสริม</td>
                                    <td>฿30,000</td>
                                    <td>฿25,000</td>
                                    <td>฿5,000</td>
                                    <td>16.7%</td>
                                </tr>
                                <tr>
                                    <td>อุปกรณ์การแพทย์</td>
                                    <td>฿20,000</td>
                                    <td>฿10,000</td>
                                    <td>฿10,000</td>
                                    <td><span style="color: var(--positive-delta); font-weight: 600;">50.0%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Widget 4: Tax Report (Full Width) --}}
                <div class="sr-widget-card sr-widget-full-width">
                    <h3 class="sr-widget-title">Tax Summary (ภ.พ. 30)</h3>
                    <div class="sr-table-container">
                        <table class="sr-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Amount (Before VAT)</th>
                                    <th>VAT (7%)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ยอดขายที่ต้องเสียภาษี (Taxable Sales)</td>
                                    <td>฿140,186.92</td>
                                    <td>฿9,813.08</td>
                                    <td>฿150,000.00</td>
                                </tr>
                                <tr>
                                    <td>ยอดขายที่ได้รับการยกเว้น (Exempt Sales)</td>
                                    <td>฿0.00</td>
                                    <td>฿0.00</td>
                                    <td>฿0.00</td>
                                </tr>
                                <tr style="background-color: var(--bg-color); font-weight: 600;">
                                    <td>Total Revenue</td>
                                    <td>฿140,186.92</td>
                                    <td>฿9,813.08</td>
                                    <td>฿150,000.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>
    </div>

    {{-- [!!! NEW: Add Finance Report Modal !!!] --}}
    <div id="modal-add-finance-report" class="sr-modal-overlay">
        <div class="sr-modal">
            <div class="sr-modal-header">
                <h3 class="sr-modal-title">Create Financial Report</h3>
                <button class="sr-modal-close" data-close="modal-add-finance-report"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="sr-modal-body">
                <form id="form-add-finance-report">
                    <div class="sr-form-group">
                        <label class="sr-form-label">Report Period</label>
                        <select class="sr-form-select">
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="q1">Q1 (Jan-Mar)</option>
                            <option value="q2">Q2 (Apr-Jun)</option>
                            <option value="ytd">Year to Date</option>
                        </select>
                    </div>
                    <div class="sr-form-group">
                        <label class="sr-form-label">Report Type</label>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="display: flex; gap: 8px; align-items: center; font-size: 0.95rem;">
                                <input type="radio" name="finance_type" value="pnl" checked> Profit & Loss (P&L)
                            </label>
                            <label style="display: flex; gap: 8px; align-items: center; font-size: 0.95rem;">
                                <input type="radio" name="finance_type" value="tax"> Tax Report (VAT ภ.พ. 30)
                            </label>
                            <label style="display: flex; gap: 8px; align-items: center; font-size: 0.95rem;">
                                <input type="radio" name="finance_type" value="expense"> Expense Report
                            </label>
                        </div>
                    </div>
                    <div class="sr-form-group">
                        <label class="sr-form-label">Export Format</label>
                        <select class="sr-form-select">
                            <option value="pdf">PDF (Official)</option>
                            <option value="excel">Excel (.xlsx)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="sr-modal-footer">
                <button class="sr-btn-secondary" data-close="modal-add-finance-report">Cancel</button>
                <button class="sr-button-primary">Generate Report</button>
            </div>
        </div>
    </div>

    {{-- [!!!] 3. โหลด CSS และ JS --}}
    @push('styles')
        {{-- [สำคัญ] เราใช้ CSS เดิมจาก Sale Report --}}
        <link rel="stylesheet" href="{{ asset('css/sale-report.css') }}">
        {{-- (อย่าลืมโหลด FontAwesome ใน app.blade.php) --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> --}}
    @endpush

    @push('scripts')
        {{-- โหลด Chart.js (ต้องมีใน app.blade.php หรือที่นี่) --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        {{-- [สำคัญ] โหลด JS หลักสำหรับ Modal และ Logic รวม --}}
        <script src="{{ asset('resources/js/sale-report.js') }}"></script>
        {{-- [สำคัญ] โหลด JS ของหน้านี้โดยเฉพาะ --}}
        <script src="{{ asset('js/finance-report.js') }}"></script>
    @endpush

</x-app-layout>

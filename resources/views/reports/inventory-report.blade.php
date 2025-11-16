{{-- 
  * resources/views/reports/inventory-report.blade.php
  * หน้ารายงานสินค้าคงคลัง (Inventory Report)
  * ใช้คลาส CSS 'sr-' จาก sale-report.css และ CSS สำหรับ Sliding Toggle
--}}

<x-app-layout>

    {{-- [!!!] 2. ส่วนเนื้อหาหลัก (Container) --}}
    <div>
        <div>
            <div class="sr-container">
                {{-- [!!! REFACTORED HEADER !!!] --}}
                <div class="sr-header">
                    <div class="sr-header-left">
                        <p class="sr-breadcrumb">Dashboard / Settings / Inventory-Report</p>
                        <h2 class="sr-page-title">Inventory Report</h2>
                    </div>
                    <div class="sr-header-right">
                        {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
             และใช้คลาสใหม่ sr-button-primary --}}
                        <button class="sr-button-primary">
                            <i class="fa-solid fa-plus"></i>
                            <span>เพิ่มรายงานใหม่</span>
                        </button>
                    </div>
                </div>

                {{-- 2.1 Filters --}}
                <div class="sr-filters-wrapper">
                    <div class="sr-filter-group">
                        <label for="filter-supplier"><i class="fa-solid fa-truck-field"></i> Supplier</label>
                        <select id="filter-supplier" class="sr-select">
                            <option value="all">All Suppliers</option>
                            <option value="mega">Mega We Care</option>
                            <option value="zuellig">Zuellig Pharma</option>
                        </select>
                    </div>
                    <div class="sr-filter-group">
                        <label for="filter-category"><i class="fa-solid fa-tags"></i> Category</label>
                        <select id="filter-category" class="sr-select">
                            <option value="all">All Categories</option>
                            <option value="cosmetic">เวชสำอาง</option>
                            <option value="medicine">ยาอันตราย</option>
                        </select>
                    </div>
                    <div class="sr-filter-group">
                        <label for="filter-stock-level"><i class="fa-solid fa-boxes-stacked"></i> Stock Level</label>
                        <select id="filter-stock-level" class="sr-select">
                            <option value="all">All</option>
                            <option value="low-stock">Low Stock</option>
                            <option value="out-of-stock">Out of Stock</option>
                        </select>
                    </div>

                    <button class="sr-button-primary sr-filter-apply-btn" style="align-self: flex-end;">
                        Apply Filter
                    </button>
                </div>

                {{-- 2.2 KPIs Grid --}}
                <div class="sr-kpi-grid">
                    {{-- KPI 1: Total Inventory Value --}}
                    <div class="sr-kpi-card">
                        <div class="sr-kpi-icon-bg" style="--icon-bg: #e6f2ff; --icon-color: #007aff;">
                            <i class="fa-solid fa-barcode"></i>
                        </div>
                        <div class="sr-kpi-content">
                            <span class="sr-kpi-title">TOTAL INVENTORY VALUE (COST)</span>
                            <span class="sr-kpi-value">฿1,200,000</span>
                            <span class="sr-kpi-delta sr-delta-neutral">- ฿50,000</span>
                        </div>
                    </div>
                    {{-- KPI 2: Total SKUs --}}
                    <div class="sr-kpi-card">
                        <div class="sr-kpi-icon-bg" style="--icon-bg: #eef0ff; --icon-color: #5e5ce6;">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div class="sr-kpi-content">
                            <span class="sr-kpi-title">TOTAL SKUs</span>
                            <span class="sr-kpi-value">1,500</span>
                            <span class="sr-kpi-delta sr-delta-neutral">+12 new</span>
                        </div>
                    </div>
                    {{-- KPI 3: Items Low Stock --}}
                    <div class="sr-kpi-card">
                        <div class="sr-kpi-icon-bg" style="--icon-bg: #ffeee6; --icon-color: #ff9f0a;">
                            <i class="fa-solid fa-arrow-down-short-wide"></i>
                        </div>
                        <div class="sr-kpi-content">
                            <span class="sr-kpi-title">ITEMS LOW STOCK</span>
                            <span class="sr-kpi-value">45 Items</span>
                            <span class="sr-kpi-delta sr-delta-negative">Needs re-order</span>
                        </div>
                    </div>
                    {{-- KPI 4: Items Expiring Soon --}}
                    <div class="sr-kpi-card">
                        <div class="sr-kpi-icon-bg" style="--icon-bg: #ffe6e6; --icon-color: #ff3b30;">
                            <i class="fa-solid fa-calendar-times"></i>
                        </div>
                        <div class="sr-kpi-content">
                            <span class="sr-kpi-title">ITEMS EXPIRING (90 DAYS)</span>
                            <span class="sr-kpi-value">12 Items</span>
                            <span class="sr-kpi-delta sr-delta-negative">Action required</span>
                        </div>
                    </div>
                </div>

                {{-- 2.3 Widgets Grid (Tabs) --}}
                <div class="sr-widgets-grid">

                    {{-- [!!!] WIDGET TABS (Full Width) --}}
                    <div class="sr-widget-card sr-widget-full-width">

                        {{-- [!!!] นี่คือ SLIDING TOGGLE --}}
                        <div class="chart-toggle-buttons-wrapper"
                            style="display: flex; justify-content: center; padding-bottom: 16px;">
                            <div class="chart-toggle-buttons" id="inventory-tabs">
                                {{-- JS จะใช้ data-target เพื่อหา ID ของ content --}}
                                <button class="toggle-btn active" data-target="#expiryReportContent">Expiry
                                    Report</button>
                                <button class="toggle-btn" data-target="#lowStockReportContent">Low Stock</button>
                                <button class="toggle-btn" data-target="#deadStockReportContent">Dead Stock</button>
                                <button class="toggle-btn" data-target="#fullListContent">Full List</button>
                            </div>
                        </div>

                        {{-- [!!!] นี่คือ Content ของแต่ละ Tab --}}
                        <div class="inventory-tab-content">

                            {{-- Tab 1: Expiry Report --}}
                            <div id="expiryReportContent" class="tab-pane active">
                                <h3 class="sr-widget-title" style="text-align: center; margin-bottom: 16px;">Expiry
                                    Report (Items expiring in 90 days)</h3>
                                <div class="sr-table-container">
                                    <table class="sr-table">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Lot/Batch No.</th>
                                                <th style="color: var(--negative-delta);">Expiry Date</th>
                                                <th>Qty</th>
                                                <th>Supplier</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Amoxicillin 500mg</td>
                                                <td>B202501A</td>
                                                <td>Dec 15, 2025 (in 30 days)</td>
                                                <td>50</td>
                                                <td>Zuellig Pharma</td>
                                            </tr>
                                            <tr>
                                                <td>Vitamin C 1000mg</td>
                                                <td>VC-1002</td>
                                                <td>Jan 30, 2026 (in 76 days)</td>
                                                <td>120</td>
                                                <td>Mega We Care</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tab 2: Low Stock Report --}}
                            <div id="lowStockReportContent" class="tab-pane" style="display: none;">
                                <h3 class="sr-widget-title" style="text-align: center; margin-bottom: 16px;">Low Stock
                                    Report (Below Reorder Point)</h3>
                                <div class="sr-table-container">
                                    <table class="sr-table">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th style="color: var(--negative-delta);">Qty on Hand</th>
                                                <th>Reorder Point</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Paracetamol 500mg</td>
                                                <td>ยา
                                                <td>5</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>Alcohol 70% 450ml</td>
                                                <td>อุปกรณ์การแพทย์</td>
                                                <td>12</td>
                                                <td>20</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tab 3: Dead Stock Report --}}
                            <div id="deadStockReportContent" class="tab-pane" style="display: none;">
                                <h3 class="sr-widget-title" style="text-align: center; margin-bottom: 16px;">Dead
                                    Stock (No sales in 180 days)</h3>
                                <div class="sr-table-container">
                                    <table class="sr-table">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Last Sale Date</th>
                                                <th>Qty on Hand</th>
                                                <th>Cost Value (THB)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>[Special] Face Mask XYZ</td>
                                                <td>May 01, 2025</td>
                                                <td>200</td>
                                                <td>฿2,000.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tab 4: Full List --}}
                            <div id="fullListContent" class="tab-pane" style="display: none;">
                                <h3 class="sr-widget-title" style="text-align: center; margin-bottom: 16px;">Full
                                    Inventory List</h3>
                                {{-- เพิ่มกราฟในแท็บนี้ได้ --}}
                                <div class="sr-chart-container" style="height: 250px; margin-bottom: 24px;">
                                    <canvas id="stockByCategoryChart"></canvas>
                                </div>
                                <div class="sr-table-container">
                                    <table class="sr-table">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Qty on Hand</th>
                                                <th>Cost</th>
                                                <th>Sale Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Amoxicillin 500mg</td>
                                                <td>ยาอันตราย</td>
                                                <td>50</td>
                                                <td>฿150.00</td>
                                                <td>฿220.00</td>
                                            </tr>
                                            <tr>
                                                <td>Paracetamol 500mg</td>
                                                <td>ยา</td>
                                                <td>5</td>
                                                <td>฿50.00</td>
                                                <td>฿75.00</td>
                                            </tr>
                                            <tr>
                                                <td>Vitamin C 1000mg</td>
                                                <td>อาหารเสริม</td>
                                                <td>120</td>
                                                <td>฿200.00</td>
                                                <td>฿350.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- [!!!] 3. โหลด CSS และ JS --}}
    @push('styles')
        {{-- [สำคัญ] เราใช้ CSS เดิมจาก Sale Report --}}
        <link rel="stylesheet" href="{{ asset('css/sale-report.css') }}">
        {{-- (อย่าลืมเพิ่ม CSS ของ Sliding Toggle ไว้ใน sale-report.css ด้วยนะครับ) --}}
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        {{-- [สำคัญ] โหลด JS ของหน้านี้สำหรับ Sliding Toggle --}}
        <script src="{{ asset('js/inventory-report.js') }}"></script>
    @endpush

</x-app-layout>

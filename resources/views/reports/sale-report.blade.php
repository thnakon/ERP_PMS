{{-- 
    Assuming this file is called within a main layout (e.g., app.blade.php)
    that uses <x-app-layout>, which already contains <main> and {{ $slot }}.
    We use <x-app-layout> to wrap everything.
    --}}

<x-app-layout>
    {{-- 
    [!!! HEADER SLOT !!!] 
    This is the page header.
    We'll adjust it slightly as requested.
    --}}

    {{-- 
    [!!! MAIN CONTENT !!!] 
    This is the main content for the sales report page,
    which will be displayed within your <main class="main-content-wrapper">.
    --}}

    {{-- Links to page-specific CSS and JS --}}
    <link rel="stylesheet" href="{{ asset('resources/css/sale-report.css') }}">

    {{-- CDN for Chart.js (for drawing charts) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="settings-layout">    
    {{-- [!!! REFACTORED !!!] All class names are retained --}}
    <div class="sr-container-s">

        {{-- [!!! REFACTORED HEADER !!!] --}}
        <div class="sr-header">
            <div class="sr-header-left">
                {{-- (Adjusted breadcrumb to be more logical) --}}
                <p class="sr-breadcrumb">Dashboard / Reports / Sales Report</p>
                <h2 class="sr-page-title">Sales Report</h2>
            </div>
            <div class="sr-header-right">
                {{-- [!!! ADJUSTED & TRANSLATED !!!] --}}
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add New Report</span>
                </button>
            </div>
        </div>

        <!-- 1. Filters -->
        <div class="sr-filters-wrapper">
            <div class="sr-filter-group">
                <label for="date-range-filter">
                    <i class="fa-solid fa-calendar-days"></i> Date Range
                </label>
                <select id="date-range-filter" class="sr-select">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month" selected>This Month</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <div class="sr-filter-group">
                <label for="staff-filter">
                    <i class="fa-solid fa-user-doctor"></i> Staff
                </label>
                <select id="staff-filter" class="sr-select">
                    <option value="all">All Staff</option>
                    @isset($staffList)
                        @foreach ($staffList as $staff)
                            <option value="{{ $staff['id'] }}">{{ $staff['name'] }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="sr-filter-group">
                <label for="category-filter">
                    <i class="fa-solid fa-tags"></i> Category
                </label>
                <select id="category-filter" class="sr-select">
                    <option value="all">All Categories</option>
                    @isset($categoriesList)
                        @foreach ($categoriesList as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div class="sr-filter-group">
                <label for="payment-filter">
                    <i class="fa-solid fa-credit-card"></i> Payment
                </label>
                <select id="payment-filter" class="sr-select">
                    <option value="all">All Payments</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="credit_card">Credit Card</option>
                </select>
            </div>

            <button class="sr-button-secondary sr-filter-apply-btn">
                <i class="fa-solid fa-filter"></i>
                <span>Apply Filters</span>
            </button>
        </div>

        <!-- 2. Key Metrics / KPIs -->
        <div class="sr-kpi-grid">
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #E6F7FF; --icon-color: #00A3FF;">
                    <i class="fa-solid fa-baht-sign"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">Total Revenue</span>
                    <span class="sr-kpi-value">฿150,000.00</span>
                    <span class="sr-kpi-delta sr-delta-positive">+5.2% vs. previous period</span>
                </div>
            </div>
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #EBFDEF; --icon-color: #34C759;">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">Total Orders</span>
                    <span class="sr-kpi-value">520 Orders</span>
                    <span class="sr-kpi-delta sr-delta-positive">+12 orders</span>
                </div>
            </div>
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #FFF8E6; --icon-color: #FF9F0A;">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">Avg. Order Value (AOV)</span>
                    <span class="sr-kpi-value">฿288.46</span>
                    <span class="sr-kpi-delta sr-delta-negative">-฿10.15</span>
                </div>
            </div>
            <div class="sr-kpi-card">
                <div class="sr-kpi-icon-bg" style="--icon-bg: #F0EFFF; --icon-color: #5E5CE6;">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div class="sr-kpi-content">
                    <span class="sr-kpi-title">Items Sold</span>
                    <span class="sr-kpi-value">1,200 Items</span>
                    <span class="sr-kpi-delta sr-delta-neutral">Stable</span>
                </div>
            </div>
        </div>

        <!-- 3. Graphs & Tables -->
        <div class="sr-widgets-grid">

            <!-- Sales Over Time (Line Chart) -->
            <div class="sr-widget-card sr-widget-full-width">
                <h3 class="sr-widget-title">Sales Over Time</h3>
                <div class="sr-chart-container" style="height: 350px;">
                    <canvas id="salesOverTimeChart"></canvas>
                </div>
            </div>

            <!-- Top Categories (Pie Chart) -->
            <div class="sr-widget-card">
                <h3 class="sr-widget-title">Sales by Category</h3>
                <div class="sr-chart-container" style="height: 300px;">
                    <canvas id="topCategoriesChart"></canvas>
                </div>
            </div>

            <!-- Top Staff (Table) -->
            <div class="sr-widget-card">
                <h3 class="sr-widget-title">Top Staff</h3>
                <div class="sr-table-container">
                    <table class="sr-table">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Orders</th>
                                <th>Total Revenue (THB)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Pharmacist A</td>
                                <td>180</td>
                                <td>฿65,000.00</td>
                            </tr>
                            <tr>
                                <td>Pharmacist B</td>
                                <td>175</td>
                                <td>฿55,000.00</td>
                            </tr>
                            <tr>
                                <td>Staff C</td>
                                <td>165</td>
                                <td>฿30,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Best-Selling Products (Table) -->
            <div class="sr-widget-card sr-widget-full-width">
                <h3 class="sr-widget-title">Top 10 Best-Selling Products</h3>
                <div class="sr-table-container">
                    <table class="sr-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Quantity (Items)</th>
                                <th>Total Revenue (THB)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Product A (10mg)</td>
                                <td>Pharmacy Drug</td>
                                <td>150</td>
                                <td>฿15,000.00</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Serum B</td>
                                <td>Cosmeceutical</td>
                                <td>80</td>
                                <td>฿12,000.00</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Blood Pressure Monitor C</td>
                                <td>Medical Device</td>
                                <td>30</td>
                                <td>฿10,500.00</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Vitamin D (1000 IU)</td>
                                <td>Supplement</td>
                                <td>200</td>
                                <td>฿9,000.00</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Product E (50mg)</td>
                                <span></span>
                                <td>90</td>
                                <td>฿8,100.00</td>
                            </tr>
                            <!-- ... more rows ... -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- .sr-widgets-grid -->
    </div> <!-- .sr-container -->
</div>
    

    {{-- Load this page's specific JS file --}}
    <script src="{{ asset('resources/js/sale-report.js') }}"></script>

</x-app-layout>
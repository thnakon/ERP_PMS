<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/css/dashboard.css'])
    </x-slot>

    <div class="dashboard-main-content">

        <!-- 1. Page Header -->
        <div class="dashboard-page-header">
            <div class="header-left">
                <p class="breadcrumb">Dashboard ▶ Welcome back, {{ Auth::user()->name ?? 'Pharmacist' }}</p>
                <h2 class="dashboard-page-title">Overview</h2>
            </div>
            <div class="header-right">
                <div class="date-picker-box">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>{{ now()->startOfMonth()->format('M d, Y') . ' - ' . now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- 2. KPI Stat Cards Grid -->
        <div class="dashboard-grid kpi-grid">
            <!-- (การ์ด KPI ทั้ง 4... เหมือนเดิม) -->
            <div class="dashboard-card kpi-card">
                <div class="card-icon icon-sales">
                    <i class="fa-solid fa-baht-sign"></i>
                </div>
                <div class="card-info">
                    <span class="kpi-title">Today's Sales</span>
                    <span class="kpi-value">฿12,450</span>
                    <span class="kpi-comparison good">
                        <i class="fa-solid fa-arrow-trend-up"></i> +15% vs yesterday
                    </span>
                </div>
            </div>

            <div class="dashboard-card kpi-card">
                <div class="card-icon icon-profit">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="card-info">
                    <span class="kpi-title">Today's Profit</span>
                    <span class="kpi-value">฿4,820</span>
                    <span class="kpi-comparison good">
                        <i class="fa-solid fa-arrow-trend-up"></i> +12% vs yesterday
                    </span>
                </div>
            </div>

            <div class="dashboard-card kpi-card">
                <div class="card-icon icon-customer">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="card-info">
                    <span class="kpi-title">New Customers</span>
                    <span class="kpi-value">8</span>
                    <span class="kpi-comparison">
                        <i class="fa-solid fa-arrow-trend-down"></i> -2 vs yesterday
                    </span>
                </div>
            </div>

            <div class="dashboard-card kpi-card">
                <div class="card-icon icon-stock">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="card-info">
                    <span class="kpi-title">Out of Stock</span>
                    <span class="kpi-value">12 Items</span>
                    <span class="kpi-comparison bad">
                        <i class="fa-solid fa-arrow-trend-up"></i> +2 new
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Sales Trend & Notifications Grid -->
        <div class="dashboard-grid main-grid">
            <!-- (กราฟ และ Notifications... เหมือนเดิม) -->
            <div class="dashboard-card chart-card">
                <div class="card-header">
                    <h3>Sales Trend (Last 7 Days)</h3>
                    <div class="chart-legend">
                        <span class="legend-dot sales"></span> Sales
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="dashboard-card notification-card">
                <div class="card-header">
                    <h3>Stock Notifications</h3>
                    <a href="#" class="view-all">View all</a>
                </div>
                <ul class="notification-list">
                    <li class="notification-item critical">
                        <div class="notif-icon">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div class="notif-content">
                            <strong>Low Stock Alert</strong>
                            <span>Paracetamol 500mg (เหลือ 8)</span>
                        </div>
                        <span class="notif-time">Just now</span>
                    </li>
                    <li class="notification-item warning">
                        <div class="notif-icon">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div class="notif-content">
                            <strong>Expiring Soon</strong>
                            <span>Amoxicillin Batch A (หมดอายุใน 7 วัน)</span>
                        </div>
                        <span class="notif-time">1h ago</span>
                    </li>
                    <li class="notification-item critical">
                        <div class="notif-icon">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div class="notif-content">
                            <strong>Low Stock Alert</strong>
                            <span>Betadine (เหลือ 3)</span>
                        </div>
                        <span class="notif-time">3h ago</span>
                    </li>
                    <li class="notification-item">
                        <div class="notif-icon">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="notif-content">
                            <strong>Stock Received</strong>
                            <span>Purchase Order #PO-00125</span>
                        </div>
                        <span class="notif-time">Yesterday</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 4. Expiring & Top Selling Grid -->
        <div class="dashboard-grid list-grid">
            <!-- (รายการยาหมดอายุ และ ยอดขาย... เหมือนเดิม) -->
            <div class="dashboard-card list-card">
                <div class="card-header">
                    <h3>Top 10 Expiring Drugs (Next 30 Days)</h3>
                    <a href="#" class="view-all">View all</a>
                </div>
                <div class="list-table-header">
                    <span>Product Name</span>
                    <span>Expiry Date</span>
                    <span>Qty</span>
                </div>
                <ul class="data-list">
                    <li class="data-list-item">
                        <span class="product-name">Amoxicillin Batch A</span>
                        <span class="expiry-date warning">Nov 20, 2025</span>
                        <span class="qty">15</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">Vitamin C injection</span>
                        <span class="expiry-date warning">Nov 22, 2025</span>
                        <span class="qty">30</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">Ibuprofen Batch C</span>
                        <span class="expiry-date">Dec 05, 2025</span>
                        <span class="qty">50</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">Saline Solution</span>
                        <span class="expiry-date">Dec 10, 2025</span>
                        <span class="qty">22</span>
                    </li>
                </ul>
            </div>

            <div class="dashboard-card list-card">
                <div class="card-header">
                    <h3>Recent Sales</h3>
                    <a href="#" class="view-all">View all</a>
                </div>
                <div class="list-table-header">
                    <span>Customer</span>
                    <span>Items</span>
                    <span>Total</span>
                </div>
                <ul class="data-list">
                    <li class="data-list-item">
                        <span class="product-name">คุณสมชาย ใจดี</span>
                        <span class="qty">3</span>
                        <span class="total-price">฿450.00</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">คุณอารีรัตน์</span>
                        <span class="qty">1</span>
                        <span class="total-price">฿120.00</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">(Walk-in)</span>
                        <span class="qty">2</span>
                        <span class="total-price">฿85.50</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">คุณวิเชียร</span>
                        <span class="qty">5</span>
                        <span class="total-price">฿1,200.00</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- [!!! NEW !!!] 5. Quick Actions -->
        <div class="dashboard-grid quick-actions-grid">
            <a href="#" class="dashboard-card action-card">
                <i class="fa-solid fa-cash-register"></i>
                <span>Go to POS</span>
            </a>
            <a href="#" class="dashboard-card action-card">
                <i class="fa-solid fa-plus"></i>
                <span>Add Product</span>
            </a>
            <a href="#" class="dashboard-card action-card">
                <i class="fa-solid fa-boxes-packing"></i>
                <span>Receive Stock</span>
            </a>
            <a href="#" class="dashboard-card action-card">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add Patient</span>
            </a>
            <a href="#" class="dashboard-card action-card">
                <i class="fa-solid fa-chart-pie"></i>
                <span>View Reports</span>
            </a>
        </div>

        <!-- [!!! NEW !!!] 6. Pending Tasks Grid -->
        <div class="dashboard-grid list-grid">
            <!-- Left Card: Pending Purchase Orders -->
            <div class="dashboard-card list-card">
                <div class="card-header">
                    <h3>Pending Purchase Orders</h3>
                    <a href="#" class="view-all">View all</a>
                </div>
                <div class="list-table-header">
                    <span>Order ID</span>
                    <span>Supplier</span>
                    <span>Status</span>
                </div>
                <ul class="data-list">
                    <li class="data-list-item">
                        <span class="product-name">PO-00126</span>
                        <span class="supplier-name">Med-Supply Co.</span>
                        <span class="status pending">Pending</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">PO-00125</span>
                        <span class="supplier-name">PharmaDeal</span>
                        <span class="status intransit">In Transit</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">PO-00124</span>
                        <span class="supplier-name">Thai-Pha</span>
                        <span class="status pending">Pending</span>
                    </li>
                </ul>
            </div>

            <!-- Right Card: Prescription Queue (ถ้ามีระบบนี้) -->
            <div class="dashboard-card list-card">
                <div class="card-header">
                    <h3>Prescription Queue</h3>
                    <a href="#" class="view-all">View all</a>
                </div>
                <div class="list-table-header">
                    <span>Patient Name</span>
                    <span>Doctor</span>
                    <span>Status</span>
                </div>
                <ul class="data-list">
                    <li class="data-list-item">
                        <span class="product-name">คุณอารยา</span>
                        <span class="supplier-name">นพ. สมเกียรติ</span>
                        <span class="status pending">Waiting</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">คุณปิติ</span>
                        <span class="supplier-name">คลินิกใกล้บ้าน</span>
                        <span class="status intransit">Filling</span>
                    </li>
                    <li class="data-list-item">
                        <span class="product-name">คุณสมหญิง</span>
                        <span class="supplier-name">นพ. สมเกียรติ</span>
                        <span class="status pending">Waiting</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @vite(['resources/js/dashboard.js'])
    @endpush

    

</x-app-layout>

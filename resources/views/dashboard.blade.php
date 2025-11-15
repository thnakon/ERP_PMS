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

        <div class="dashboard-page-header">
            <div class="header-left">
                <p class="breadcrumb">Dashboard / Welcome back, {{ Auth::user()->name ?? 'Pharmacist' }} !</p>
                <h2 class="dashboard-page-title">Overview</h2>
            </div>
            <div class="header-right">
                <div class="date-picker-box">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>{{ now()->startOfMonth()->format('M d, Y') . ' - ' . now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid kpi-grid">
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

        <div class="dashboard-grid main-grid">
            <div class="dashboard-card chart-card">
                <div class="card-header">
                    <!-- [!!! CHANGED !!!] เปลี่ยน Title ให้อยู่กลางๆ -->
                    <h3>Performance Trend</h3>
                    
                    <!-- [!!! CHANGED !!!] แทนที่ Legend เดิมด้วยปุ่ม Toggle -->
                    <div class="chart-toggle-buttons">
                        <button class="toggle-btn active" data-metric="sales">Sales</button>
                        <button class="toggle-btn" data-metric="profit">Profit</button>
                        <button class="toggle-btn" data-metric="outOfStock">Out of Stock</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="dashboard-card notification-card">
                <div class="card-header">
                    <h3>Stock Notifications</h3>
                </div>
                
                <ul class="an-notification-list">
                    
                    {{-- Item 1: Critical (สีแดง) --}}
                    <li class="an-notification-item an-critical">
                        <div class="an-content">
                            <span class="an-title">Dead Stock over !</span>
                            <span class="an-subtitle">5%</span>
                            <span class="an-timestamp">Today | 5:00 PM</span>
                        </div>
                        <div class="an-meta">
                            <span class="an-time-badge">4h</span>
                            <i class="fa-solid fa-arrow-up an-arrow an-positive"></i>
                        </div>
                    </li>

                    {{-- Item 2: Warning (สีเหลือง) --}}
                    <li class="an-notification-item an-warning">
                        <div class="an-content">
                            <span class="an-title">Expiring Soon</span>
                            <span class="an-subtitle">Vitamin C injection</span>
                            <span class="an-timestamp">Today | 6:00 PM</span>
                        </div>
                        <div class="an-meta">
                            <span class="an-time-badge">4h</span>
                            <i class="fa-solid fa-arrow-down an-arrow an-negative"></i>
                        </div>
                    </li>

                    {{-- Item 3: Info (สีน้ำเงิน) --}}
                    <li class="an-notification-item an-info">
                        <div class="an-content">
                            <span class="an-title">Expiry Risk</span>
                            <span class="an-subtitle">Antibiotics down</span>
                            <span class="an-timestamp">Tomorrow | 2:00 PM</span>
                        </div>
                        <div class="an-meta">
                            <span class="an-time-badge">4h</span>
                            <i class="fa-solid fa-arrow-down an-arrow an-negative"></i>
                        </div>
                    </li>
                    
                </ul>
                
                {{-- ลิงก์ View more ด้านล่าง --}}
                <a href="#" class="an-view-more">
                    View more
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
            </div>
            </div>

        <div class="dashboard-grid list-grid">

            <!-- Card 1: Top 10 Expiring Drugs (New Design) -->
    <div class="dashboard-card ed-card"> <!-- ed = Expiring Drugs -->
        <div class="card-header">
            <h3>Top 5 Expiring Drugs</h3>
            <a href="#" class="view-all">View all <i class="fa-solid fa-chevron-right"></i></a>
        </div>

        <ul class="ed-list">
            <!-- Item 1 -->
            <li class="ed-list-item">
                <div class="ed-item-main">
                    <div class="ed-item-header">
                        <div class="ed-item-icon" style="background-color: #fdf0e6;">
                            <!-- -->
                            <img src="https://placehold.co/100x100/fdf0e6/f2994a?text=P&font=roboto" alt="drug-icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="ed-item-title-block">
                            <span class="ed-item-sku">PN0001265</span>
                            <span class="ed-item-name">Paracetamol Batch A</span>
                        </div>
                    </div>
                    <div class="ed-item-meta">
                        <div class="ed-item-meta-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>Created Sep 12, 2020</span>
                        </div>
                        <div class="ed-item-meta-priority medium">
                            <i class="fa-solid fa-arrow-up"></i>
                            <span>Medium</span>
                        </div>
                    </div>
                </div>
                <div class="ed-item-info">
                    <span class="ed-info-header">Information</span>
                    <div class="ed-info-details">
                        <div class="ed-info-grid">
                            <div class="ed-info-block">
                                <span class="ed-info-label">Quantity</span>
                                <span class="ed-info-value">100</span>
                            </div>
                            <div class="ed-info-block">
                                <span class="ed-info-label">Expiry Date</span>
                                <span class="ed-info-value">10 <span class="days">days</span></span>
                            </div>
                        </div>
                        <div class="ed-info-action">
                            <i class="fa-regular fa-eye"></i>
                        </div>
                    </div>
                </div>
            </li>
            
            <!-- Item 2 -->
            <li class="ed-list-item">
                <div class="ed-item-main">
                    <div class="ed-item-header">
                        <div class="ed-item-icon" style="background-color: #e6f7f4;">
                            <!-- -->
                            <img src="https://placehold.co/100x100/e6f7f4/27ae60?text=A&font=roboto" alt="drug-icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="ed-item-title-block">
                            <span class="ed-item-sku">PN0001221</span>
                            <span class="ed-item-name">Amoxicillin Batch B</span>
                        </div>
                    </div>
                    <div class="ed-item-meta">
                        <div class="ed-item-meta-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>Created Sep 10, 2020</span>
                        </div>
                        <div class="ed-item-meta-priority medium">
                            <i class="fa-solid fa-arrow-up"></i>
                            <span>Medium</span>
                        </div>
                    </div>
                </div>
                <div class="ed-item-info">
                    <span class="ed-info-header">Information</span>
                    <div class="ed-info-details">
                        <div class="ed-info-grid">
                            <div class="ed-info-block">
                                <span class="ed-info-label">Quantity</span>
                                <span class="ed-info-value">75</span>
                            </div>
                            <div class="ed-info-block">
                                <span class="ed-info-label">Expiry Date</span>
                                <span class="ed-info-value">15 <span class="days">days</span></span>
                            </div>
                        </div>
                        <div class="ed-info-action">
                            <i class="fa-regular fa-eye"></i>
                        </div>
                    </div>
                </div>
            </li>

            <!-- Item 3 -->
            <li class="ed-list-item">
                <div class="ed-item-main">
                    <div class="ed-item-header">
                        <div class="ed-item-icon" style="background-color: #f0eefe;">
                            <!-- -->
                            <img src="https://placehold.co/100x100/f0eefe/9b51e0?text=I&font=roboto" alt="drug-icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="ed-item-title-block">
                            <span class="ed-item-sku">PN0001290</span>
                            <span class="ed-item-name">Ibuprofen Batch C</span>
                        </div>
                    </div>
                    <div class="ed-item-meta">
                        <div class="ed-item-meta-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>Created May 28, 2020</span>
                        </div>
                        <div class="ed-item-meta-priority low">
                            <i class="fa-solid fa-arrow-down"></i>
                            <span>Low</span>
                        </div>
                    </div>
                </div>
                <div class="ed-item-info">
                    <span class="ed-info-header">Information</span>
                    <div class="ed-info-details">
                        <div class="ed-info-grid">
                            <div class="ed-info-block">
                                <span class="ed-info-label">Quantity</span>
                                <span class="ed-info-value">60</span>
                            </div>
                            <div class="ed-info-block">
                                <span class="ed-info-label">Expiry Date</span>
                                <span class="ed-info-value">20 <span class="days">days</span></span>
                            </div>
                        </div>
                        <div class="ed-info-action">
                            <i class="fa-regular fa-eye"></i>
                        </div>
                    </div>
                </div>
            </li>
            
             <!-- Item 3 -->
            <li class="ed-list-item">
                <div class="ed-item-main">
                    <div class="ed-item-header">
                        <div class="ed-item-icon" style="background-color: #f0eefe;">
                            <!-- -->
                            <img src="https://placehold.co/100x100/f0eefe/9b51e0?text=I&font=roboto" alt="drug-icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="ed-item-title-block">
                            <span class="ed-item-sku">PN0001290</span>
                            <span class="ed-item-name">Ibuprofen Batch C</span>
                        </div>
                    </div>
                    <div class="ed-item-meta">
                        <div class="ed-item-meta-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>Created May 28, 2020</span>
                        </div>
                        <div class="ed-item-meta-priority low">
                            <i class="fa-solid fa-arrow-down"></i>
                            <span>Low</span>
                        </div>
                    </div>
                </div>
                <div class="ed-item-info">
                    <span class="ed-info-header">Information</span>
                    <div class="ed-info-details">
                        <div class="ed-info-grid">
                            <div class="ed-info-block">
                                <span class="ed-info-label">Quantity</span>
                                <span class="ed-info-value">60</span>
                            </div>
                            <div class="ed-info-block">
                                <span class="ed-info-label">Expiry Date</span>
                                <span class="ed-info-value">20 <span class="days">days</span></span>
                            </div>
                        </div>
                        <div class="ed-info-action">
                            <i class="fa-regular fa-eye"></i>
                        </div>
                    </div>
                </div>
            </li>

             <!-- Item 3 -->
            <li class="ed-list-item">
                <div class="ed-item-main">
                    <div class="ed-item-header">
                        <div class="ed-item-icon" style="background-color: #f0eefe;">
                            <!-- -->
                            <img src="https://placehold.co/100x100/f0eefe/9b51e0?text=I&font=roboto" alt="drug-icon" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="ed-item-title-block">
                            <span class="ed-item-sku">PN0001290</span>
                            <span class="ed-item-name">Ibuprofen Batch C</span>
                        </div>
                    </div>
                    <div class="ed-item-meta">
                        <div class="ed-item-meta-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>Created May 28, 2020</span>
                        </div>
                        <div class="ed-item-meta-priority low">
                            <i class="fa-solid fa-arrow-down"></i>
                            <span>Low</span>
                        </div>
                    </div>
                </div>
                <div class="ed-item-info">
                    <span class="ed-info-header">Information</span>
                    <div class="ed-info-details">
                        <div class="ed-info-grid">
                            <div class="ed-info-block">
                                <span class="ed-info-label">Quantity</span>
                                <span class="ed-info-value">60</span>
                            </div>
                            <div class="ed-info-block">
                                <span class="ed-info-label">Expiry Date</span>
                                <span class="ed-info-value">20 <span class="days">days</span></span>
                            </div>
                        </div>
                        <div class="ed-info-action">
                            <i class="fa-regular fa-eye"></i>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </div>

    <!-- Card 2: Activity Stream (New Design) -->
    <div class="dashboard-card as-card"> <!-- as = Activity Stream -->
        <div class="card-header">
            <h3>Activity Stream</h3>
        </div>
        
        <div class="as-user-block">
            <img class="as-avatar" src="https://placehold.co/100x100/E0E0E0/757575?text=O" alt="User Avatar">
            <div class="as-user-info">
                <span class="name">Oscar Holloway</span>
                <span class="role">Pharmacist | Staff</span>
            </div>
        </div>

        <ul class="as-action-list">
            <li class="as-action-item">
                <i class="fa-solid fa-cloud-arrow-up as-action-icon" style="color: #2F80ED;"></i>
                <span class="as-action-text">Updated the status of Mind Map task to In Progress</span>
            </li>
            <li class="as-action-item">
                <i class="fa-solid fa-paperclip as-action-icon" style="color: #9B51E0;"></i>
                <span class="as-action-text">Attached files to the task</span>
            </li>
            <li class="as-action-item">
                <i class="fa-solid fa-paperclip as-action-icon" style="color: #9B51E0;"></i>
                <span class="as-action-text">Attached files to the task</span>
            </li>
            <li class="as-action-item">
                <i class="fa-solid fa-paperclip as-action-icon" style="color: #9B51E0;"></i>
                <span class="as-action-text">Attached files to the task</span>
            </li>
        </ul>

        <a href="#" class="as-view-more">
            View more
            <i class="fa-solid fa-chevron-down"></i>
        </a>
    </div>

            

        
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @vite(['resources/js/dashboard.js'])
    @endpush

</x-app-layout>
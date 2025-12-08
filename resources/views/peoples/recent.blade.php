<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recent Activity - Pharmacy ERP</title>

        <!-- Import Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Import Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Import FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Sarabun", sans-serif;
                background-color: #F5F5F7;
                color: #1D1D1F;
                -webkit-font-smoothing: antialiased;
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 0px;
                background: transparent;
            }

            /* Soft Shadow */
            .soft-shadow {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            }

            /* Animations */
            .fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Segmented Control Active State */
            .view-toggle-active {
                background-color: #007AFF;
                color: white;
                box-shadow: 0 2px 8px rgba(0, 122, 255, 0.25);
            }

            .view-toggle-inactive {
                color: #86868B;
                background-color: transparent;
            }

            .view-toggle-inactive:hover {
                color: #1D1D1F;
            }

            /* Activity Icons Backgrounds */
            .icon-bg-sales {
                background-color: #E5F1FF;
                color: #007AFF;
            }

            .icon-bg-inventory {
                background-color: #FFF7E6;
                color: #FF9500;
            }

            .icon-bg-system {
                background-color: #F2F2F7;
                color: #86868B;
            }

            .icon-bg-error {
                background-color: #FFF5F5;
                color: #FF3B30;
            }

            /* --- Inventory/Table Styles (Ported) --- */
            .inv-card-row {
                display: grid;
                gap: 16px;
                align-items: center;
                background: white;
                border-radius: 12px;
                padding: 16px 24px;
                margin-bottom: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }

            .inv-card-row:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
                border-color: rgba(0, 122, 255, 0.1);
            }

            .inv-card-row.header {
                background: transparent;
                box-shadow: none;
                padding: 12px 24px;
                margin-bottom: 0;
                border-radius: 0;
                border: none;
            }

            .inv-card-row.header:hover {
                transform: none;
                box-shadow: none;
                border-color: transparent;
            }

            .inv-col-header {
                font-size: 12px;
                font-weight: 600;
                color: #8e8e93;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .inv-text-main {
                font-size: 14px;
                font-weight: 500;
                color: #1d1d1f;
            }

            .inv-text-sub {
                font-size: 13px;
                color: #86868b;
            }

            .inv-product-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .inv-product-name {
                font-weight: 600;
                color: #1d1d1f;
                font-size: 15px;
            }

            /* Grid Template for Recent Activity */
            .grid-recent {
                grid-template-columns: 3fr 4fr 2fr 2fr 1fr;
            }

            /* Pagination */
            .people-pagination {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 4px;
                margin-top: 10px;
                background-color: transparent;
            }

            .pagination-text {
                font-size: 13px;
                color: #86868b;
                font-weight: 500;
            }

            .pagination-controls {
                display: flex;
                gap: 8px;
                background: white;
                padding: 6px 8px;
                border-radius: 32px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            }

            .pagination-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: none;
                background-color: transparent;
                color: #1d1d1f;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
                font-size: 12px;
            }

            .pagination-btn:hover:not(.disabled) {
                background-color: #f5f5f7;
                color: #007aff;
                transform: scale(1.05);
            }

            .pagination-btn.disabled {
                color: #d1d1d6;
                cursor: not-allowed;
            }

            /* Header Styles */
            .sr-header {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 32px;
                gap: 16px;
            }

            @media (min-width: 768px) {
                .sr-header {
                    flex-direction: row;
                }
            }

            .sr-header-left {
                width: 100%;
            }

            @media (min-width: 768px) {
                .sr-header-left {
                    width: auto;
                }
            }

            .sr-breadcrumb {
                font-size: 14px;
                color: #86868b;
                margin-bottom: 8px;
            }

            .sr-page-title {
                font-size: 32px;
                font-weight: 700;
                color: #1d1d1f;
                letter-spacing: -0.02em;
            }
        </style>
    </head>

    <body class="min-h-screen p-6 md:p-10">

        <div class="max-w-[1400px] mx-auto">

            <!-- WRAPPER -->
            <div class="os-container">

                <!-- Header Section -->
                <div class="sr-header" style="margin-bottom: 0px">
                    <div class="sr-header-left">
                        <p class="sr-breadcrumb">
                            Dashboard / <span style="color: #3a3a3c; font-weight: 600;">Recent Activity</span>
                        </p>
                        <h2 class="sr-page-title">System Logs <span
                                style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">(Today)</span></h2>
                    </div>

                    <div class="flex items-center gap-4 w-full md:w-auto justify-between md::justify-end">

                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search logs..."
                                class="pl-10 pr-4 py-2 rounded-xl border border-transparent bg-white focus:bg-white focus:border-blue-300 focus:outline-none transition w-64 text-sm shadow-sm text-[#1D1D1F]">
                            <i
                                class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#86868B]"></i>
                        </div>

                        <!-- Category Filter -->
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px]"
                            style="border-radius: 25px">
                            <button onclick="filterLogs('all')" id="filter-all"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-active"
                                style="border-radius: 25px">All</button>
                            <button onclick="filterLogs('sales')" id="filter-sales"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">Sales</button>
                            <button onclick="filterLogs('inventory')" id="filter-inventory"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">Stock</button>
                            <button onclick="filterLogs('system')" id="filter-system"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">System</button>
                        </div>

                        <!-- Export Button -->
                        <button
                            class="bg-white text-[#1D1D1F] border border-gray-200 px-4 py-2.5 rounded-xl font-medium text-sm shadow-sm hover:bg-[#F9F9FB] transition flex items-center gap-2"
                            style="border-radius: 25px">
                            <i class="fa-solid fa-download text-[#86868B]"></i> Export
                        </button>
                    </div>
                </div>

                <!-- ACTIVITY LIST -->
                <div id="view-list" class="transition-opacity duration-300">

                    <!-- Table Header -->
                    <div class="inv-card-row header grid-recent">
                        <div class="inv-col-header">User / Actor</div>
                        <div class="inv-col-header">Activity Description</div>
                        <div class="inv-col-header">Category</div>
                        <div class="inv-col-header">Time</div>
                        <div class="inv-col-header" style="text-align: right;">Status</div>
                    </div>

                    <div class="space-y-3" id="log-container">

                        <!-- Item 1: Sales (Just Now) -->
                        <div class="inv-card-row grid-recent list-row" data-category="sales" style="border-radius: 24px;">
                            <div class="inv-product-info">
                                <img src="https://i.pravatar.cc/150?u=Pharm"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-100">
                                <div>
                                    <div class="inv-product-name">Somchai Jaidee</div>
                                    <div class="inv-text-sub">Pharmacist</div>
                                </div>
                            </div>
                            <div>
                                <div class="inv-text-main">Created Invoice #INV-2025-001</div>
                                <div class="inv-text-sub">Sold: Paracetamol, Vitamin C</div>
                            </div>
                            <div>
                                <span class="inv-status-badge" style="background-color: #E5F1FF; color: #007AFF;">
                                    <i class="fa-solid fa-basket-shopping text-[10px] mr-1"></i> Sales
                                </span>
                            </div>
                            <div>
                                <div class="inv-text-main">Just now</div>
                                <div class="inv-text-sub">10:42 AM</div>
                            </div>
                            <div style="text-align: right;">
                                <i class="fa-solid fa-circle-check text-[#34C759] text-lg" title="Success"></i>
                            </div>
                        </div>

                        <!-- Item 2: Inventory (15m ago) -->
                        <div class="inv-card-row grid-recent list-row" data-category="inventory" style="border-radius: 24px;">
                            <div class="inv-product-info">
                                <img src="https://i.pravatar.cc/150?u=Asst"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-100">
                                <div>
                                    <div class="inv-product-name">Wipawee S.</div>
                                    <div class="inv-text-sub">Assistant</div>
                                </div>
                            </div>
                            <div>
                                <div class="inv-text-main">Stock Adjustment</div>
                                <div class="inv-text-sub">Updated 'Tylenol 500mg' qty: 50 -> 45</div>
                            </div>
                            <div>
                                <span class="inv-status-badge" style="background-color: #FFF7E6; color: #FF9500;">
                                    <i class="fa-solid fa-boxes-stacked text-[10px] mr-1"></i> Inventory
                                </span>
                            </div>
                            <div>
                                <div class="inv-text-main">15 mins ago</div>
                                <div class="inv-text-sub">10:27 AM</div>
                            </div>
                            <div style="text-align: right;">
                                <i class="fa-solid fa-circle-check text-[#34C759] text-lg" title="Success"></i>
                            </div>
                        </div>

                        <!-- Item 3: System (1h ago) -->
                        <div class="inv-card-row grid-recent list-row" data-category="system" style="border-radius: 24px;">
                            <div class="inv-product-info">
                                <div
                                    class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 border border-gray-200">
                                    <i class="fa-solid fa-server"></i>
                                </div>
                                <div>
                                    <div class="inv-product-name">System Auto</div>
                                    <div class="inv-text-sub">Bot</div>
                                </div>
                            </div>
                            <div>
                                <div class="inv-text-main">Daily Backup Completed</div>
                                <div class="inv-text-sub">Database backup file generated (24MB)</div>
                            </div>
                            <div>
                                <span class="inv-status-badge" style="background-color: #F2F2F7; color: #86868B;">
                                    <i class="fa-solid fa-gears text-[10px] mr-1"></i> System
                                </span>
                            </div>
                            <div>
                                <div class="inv-text-main">1 hour ago</div>
                                <div class="inv-text-sub">09:00 AM</div>
                            </div>
                            <div style="text-align: right;">
                                <i class="fa-solid fa-circle-check text-[#34C759] text-lg" title="Success"></i>
                            </div>
                        </div>

                        <!-- Item 4: System Error (Yesterday) -->
                        <div class="inv-card-row grid-recent list-row" data-category="system"
                            style="background-color: #FFF5F5; border-radius: 24px;">
                            <div class="inv-product-info">
                                <img src="https://i.pravatar.cc/150?u=James"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-100">
                                <div>
                                    <div class="inv-product-name">James W.</div>
                                    <div class="inv-text-sub">Admin</div>
                                </div>
                            </div>
                            <div>
                                <div class="inv-text-main">Failed Login Attempt</div>
                                <div class="inv-text-sub" style="color: #FF3B30;">Incorrect password entered 3 times
                                </div>
                            </div>
                            <div>
                                <span class="inv-status-badge" style="background-color: #FFF5F5; color: #FF3B30;">
                                    <i class="fa-solid fa-shield-halved text-[10px] mr-1"></i> Security
                                </span>
                            </div>
                            <div>
                                <div class="inv-text-main">Yesterday</div>
                                <div class="inv-text-sub">08:15 PM</div>
                            </div>
                            <div style="text-align: right;">
                                <i class="fa-solid fa-circle-exclamation text-[#FF3B30] text-lg" title="Warning"></i>
                            </div>
                        </div>

                        <!-- Item 5: Sales (Yesterday) -->
                        <div class="inv-card-row grid-recent list-row" data-category="sales" style="border-radius: 24px;">
                            <div class="inv-product-info">
                                <img src="https://i.pravatar.cc/150?u=Pharm"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-100">
                                <div>
                                    <div class="inv-product-name">Somchai Jaidee</div>
                                    <div class="inv-text-sub">Pharmacist</div>
                                </div>
                            </div>
                            <div>
                                <div class="inv-text-main">New Patient Registered</div>
                                <div class="inv-text-sub">Added 'Mrs. Malee Jaiyen' to system</div>
                            </div>
                            <div>
                                <span class="inv-status-badge" style="background-color: #E5F1FF; color: #007AFF;">
                                    <i class="fa-solid fa-user-plus text-[10px] mr-1"></i> Registration
                                </span>
                            </div>
                            <div>
                                <div class="inv-text-main">Yesterday</div>
                                <div class="inv-text-sub">04:30 PM</div>
                            </div>
                            <div style="text-align: right;">
                                <i class="fa-solid fa-circle-check text-[#34C759] text-lg" title="Success"></i>
                            </div>
                        </div>

                    </div>

                    <!-- Pagination -->
                    <div class="people-pagination">
                        <div class="pagination-text" style="margin-right:5px">Showing 1 to 5 of 128 logs</div>
                        <div class="pagination-controls">
                            <a href="#" class="pagination-btn disabled"><i
                                    class="fa-solid fa-chevron-left"></i></a>
                            <a href="#" class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            function filterLogs(category) {
                // Update Buttons
                ['all', 'sales', 'inventory', 'system'].forEach(c => {
                    const btn = document.getElementById('filter-' + c);
                    if (c === category) {
                        btn.classList.add('view-toggle-active');
                        btn.classList.remove('view-toggle-inactive');
                    } else {
                        btn.classList.add('view-toggle-inactive');
                        btn.classList.remove('view-toggle-active');
                    }
                });

                // Filter Rows
                const rows = document.querySelectorAll('#log-container .list-row');
                rows.forEach(row => {
                    if (category === 'all' || row.dataset.category === category) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            }
        </script>
    </body>

    </html>
</x-app-layout>

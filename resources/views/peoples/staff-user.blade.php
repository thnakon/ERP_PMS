<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Management - Pharmacy ERP</title>

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

            /* Apple Switch */
            .apple-switch {
                position: relative;
                display: inline-block;
                width: 42px;
                height: 24px;
            }

            .apple-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #E5E5EA;
                transition: .4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 20px;
                width: 20px;
                left: 2px;
                bottom: 2px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            input:checked+.slider {
                background-color: #34C759;
            }

            input:checked+.slider:before {
                transform: translateX(18px);
            }

            /* Soft Shadow */
            .soft-shadow {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            }

            /* Segmented Control */
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

            /* List Row */
            .list-row {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .list-row:hover {
                transform: scale(1.002);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
                z-index: 10;
            }

            /* Activity Card */
            .activity-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .activity-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
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

            /* Form Inputs */
            .apple-input {
                background-color: #F2F2F7;
                border: 1px solid transparent;
                transition: all 0.2s ease;
            }

            .apple-input:focus {
                background-color: #FFFFFF;
                border-color: #007AFF;
                outline: none;
                box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            }

            .apple-input-readonly {
                background-color: #E5E5EA;
                color: #86868B;
                cursor: not-allowed;
                border: none;
            }

            /* --- Inventory/Table Styles (Ported from inventorys.css) --- */
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

            /* Checkbox */
            .inv-checkbox-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .inv-checkbox {
                appearance: none;
                width: 20px;
                height: 20px;
                border: 2px solid #d1d1d6;
                border-radius: 6px;
                cursor: pointer;
                position: relative;
                transition: all 0.2s ease;
            }

            .inv-checkbox:checked {
                background-color: #007aff;
                border-color: #007aff;
            }

            .inv-checkbox:checked::after {
                content: '\f00c';
                font-family: "Font Awesome 6 Free";
                font-weight: 900;
                color: white;
                font-size: 12px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            /* Status Badge */
            .inv-status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
            }

            .inv-status-badge.active {
                background-color: #e8f5e9;
                color: #34c759;
            }

            .inv-status-badge.inactive {
                background-color: #f2f2f7;
                color: #8e8e93;
            }

            /* Actions */
            .inv-action-group {
                display: flex;
                gap: 8px;
            }

            .inv-icon-action {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #86868b;
                transition: all 0.2s ease;
                cursor: pointer;
                background: transparent;
                border: none;
            }

            .inv-icon-action:hover {
                background-color: #f2f2f7;
                color: #007aff;
            }

            .inv-icon-action.btn-delete-row:hover {
                background-color: #fff1f0;
                color: #ff3b30;
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
        </style>
    </head>

    <body class="bg-[#F5F5F7] font-sans">

        <div class="os-container">

            <!-- WRAPPER for User List Page -->
            <div id="page-user-list" class="fade-in">
                {{-- [!!! REFACTORED HEADER !!!] --}}
                <div class="sr-header">
                    <div class="sr-header-left">
                        <p class="sr-breadcrumb">
                            Dashboard / <span style="color: #3a3a3c; font-weight: 600;">Staff | Users </span>
                        </p>

                        <h2 class="sr-page-title">Employees <span
                               style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">(28)</span></h2>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">

                        <!-- Type Filter -->
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px]"
                            style="border-radius: 25px">
                            <button onclick="filterList('all')" id="filter-all"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-active"
                                style="border-radius: 25px">All</button>
                            <button onclick="filterList('staff')" id="filter-staff"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">Staff</button>
                            <button onclick="filterList('user')" id="filter-user"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">Users</button>
                        </div>

                        <!-- View Toggle -->
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px]"
                            style="border-radius: 25px">
                            <button onclick="switchView('list')" id="toggle-list"
                                class="px-6 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-active"
                                style="border-radius: 25px">List</button>
                            <button onclick="switchView('activity')" id="toggle-activity"
                                class="px-6 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">Activity</button>
                        </div>

                        <!-- Add Button -->
                        <button
                            class="bg-[#007AFF] text-white px-5 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-blue-500/20 hover:bg-[#005ECB] transition flex items-center gap-2"
                            style="border-radius: 25px">
                            <i class="fa-solid fa-plus"></i> Add Employee
                        </button>
                    </div>

                </div>
                </header>

                <!-- VIEW 1: LIST VIEW -->
                <div id="view-list" class="transition-opacity duration-300" style="margin-top: 20px;">

                    <!-- Table Header -->
                    <div class="inv-card-row header grid-users"
                        style="grid-template-columns: 40px 60px 2fr 2fr 1.5fr 1fr 130px; ">
                        <div class="inv-checkbox-wrapper">
                            <input type="checkbox" class="inv-checkbox" id="select-all">
                        </div>
                        <div class="inv-col-header">#</div>
                        <div class="inv-col-header">Name</div>
                        <div class="inv-col-header">Email</div>
                        <div class="inv-col-header">Role</div>
                        <div class="inv-col-header">Status</div>
                        <div class="inv-col-header" style="text-align: right;">Actions</div>
                    </div>

                    <div class="space-y-3" id="list-container">
                        <!-- Row 1: Staff -->
                        <div class="inv-card-row grid-users list-row" data-role="staff"
                            style="grid-template-columns: 40px 60px 2fr 2fr 1.5fr 1fr 130px; border-radius: 24px; ">
                            <div class="inv-checkbox-wrapper">
                                <input type="checkbox" class="inv-checkbox item-checkbox" data-id="1">
                            </div>
                            <div class="inv-text-sub" style="font-weight: 500;">1</div>
                            <div class="inv-product-info" onclick="navigateToProfile(1, 'staff')">
                                <img src="https://i.pravatar.cc/150?u=Pharm" class="w-8 h-8 rounded-full mr-2">
                                <div class="inv-product-name">Somchai Jaidee</div>
                            </div>
                            <div class="inv-text-sub">somchai.j@pharmacy.com</div>
                            <div class="inv-text-main">Pharmacist</div>
                            <div><span class="inv-status-badge active">Active</span></div>
                            <div class="inv-action-group" style="justify-content: flex-end;">
                                <button class="inv-icon-action" onclick="navigateToProfile(1, 'staff')">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Row 2: User -->
                        <div class="inv-card-row grid-users list-row" data-role="user"
                            style="grid-template-columns: 40px 60px 2fr 2fr 1.5fr 1fr 130px; border-radius: 24px;">
                            <div class="inv-checkbox-wrapper">
                                <input type="checkbox" class="inv-checkbox item-checkbox" data-id="2">
                            </div>
                            <div class="inv-text-sub" style="font-weight: 500;">2</div>
                            <div class="inv-product-info" onclick="navigateToProfile(2, 'user')">
                                <img src="https://i.pravatar.cc/150?u=User" class="w-8 h-8 rounded-full mr-2">
                                <div class="inv-product-name">Suda Meesuk</div>
                            </div>
                            <div class="inv-text-sub">suda.m@gmail.com</div>
                            <div class="inv-text-main">Customer (VIP)</div>
                            <div><span class="inv-status-badge active">Active</span></div>
                            <div class="inv-action-group" style="justify-content: flex-end;">
                                <button class="inv-icon-action" onclick="navigateToProfile(2, 'user')">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Row 3: Staff -->
                        <div class="inv-card-row grid-users list-row" data-role="staff"
                            style="grid-template-columns: 40px 60px 2fr 2fr 1.5fr 1fr 130px; border-radius: 24px;">
                            <div class="inv-checkbox-wrapper">
                                <input type="checkbox" class="inv-checkbox item-checkbox" data-id="3">
                            </div>
                            <div class="inv-text-sub" style="font-weight: 500;">3</div>
                            <div class="inv-product-info" onclick="navigateToProfile(3, 'staff')">
                                <img src="https://i.pravatar.cc/150?u=Owner" class="w-8 h-8 rounded-full mr-2">
                                <div class="inv-product-name">Dr. Prasert</div>
                            </div>
                            <div class="inv-text-sub">prasert.owner@pharmacy.com</div>
                            <div class="inv-text-main">Owner/Admin</div>
                            <div><span class="inv-status-badge active">Active</span></div>
                            <div class="inv-action-group" style="justify-content: flex-end;">
                                <button class="inv-icon-action" onclick="navigateToProfile(3, 'staff')">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Row 4: Staff -->
                        <div class="inv-card-row grid-users list-row" data-role="staff"
                            style="grid-template-columns: 40px 60px 2fr 2fr 1.5fr 1fr 130px; border-radius: 24px;">
                            <div class="inv-checkbox-wrapper">
                                <input type="checkbox" class="inv-checkbox item-checkbox" data-id="4">
                            </div>
                            <div class="inv-text-sub" style="font-weight: 500;">4</div>
                            <div class="inv-product-info" onclick="navigateToProfile(4, 'staff')">
                                <img src="https://i.pravatar.cc/150?u=Asst"
                                    class="w-8 h-8 rounded-full mr-2 grayscale opacity-70">
                                <div class="inv-product-name text-gray-500">Wipawee S.</div>
                            </div>
                            <div class="inv-text-sub">wipawee.asst@pharmacy.com</div>
                            <div class="inv-text-main">Assistant</div>
                            <div><span class="inv-status-badge inactive">Inactive</span></div>
                            <div class="inv-action-group" style="justify-content: flex-end;">
                                <button class="inv-icon-action" onclick="navigateToProfile(4, 'staff')">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Row 5: User -->
                        <div class="inv-card-row grid-users list-row" data-role="user"
                            style="grid-template-columns: 40px 60px 2fr 2fr 1.5fr 1fr 130px; border-radius: 24px;">
                            <div class="inv-checkbox-wrapper">
                                <input type="checkbox" class="inv-checkbox item-checkbox" data-id="5">
                            </div>
                            <div class="inv-text-sub" style="font-weight: 500;">5</div>
                            <div class="inv-product-info" onclick="navigateToProfile(5, 'user')">
                                <img src="https://i.pravatar.cc/150?u=John" class="w-8 h-8 rounded-full mr-2">
                                <div class="inv-product-name">John Doe</div>
                            </div>
                            <div class="inv-text-sub">john.d@gmail.com</div>
                            <div class="inv-text-main">Customer</div>
                            <div><span class="inv-status-badge active">Active</span></div>
                            <div class="inv-action-group" style="justify-content: flex-end;">
                                <button class="inv-icon-action" onclick="navigateToProfile(5, 'user')">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="people-pagination">
                        <div class="pagination-text" style="margin-right:5px" >Showing 1 to 5 of 28 results</div>
                        <div class="pagination-controls">
                            <a href="#" class="pagination-btn disabled"><i
                                    class="fa-solid fa-chevron-left"></i></a>
                            <a href="#" class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- VIEW 2: ACTIVITY VIEW (Grid) -->
                <div id="view-activity" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="activity-container">

                        <!-- Card 1: Active -->
                        <div class="activity-card bg-white rounded-[24px] p-6 soft-shadow cursor-pointer border border-transparent"
                            data-role="staff" onclick="navigateToProfile(1, 'staff')">
                            <div class="flex flex-col items-center mb-6">
                                <div class="relative">
                                    <img src="https://i.pravatar.cc/150?u=Pharm"
                                        class="w-20 h-20 rounded-full mb-3 border-4 border-white shadow-sm">
                                    <span
                                        class="absolute bottom-3 right-0 bg-[#34C759] w-5 h-5 rounded-full border-2 border-white"></span>
                                </div>
                                <h3 class="font-bold text-[#1D1D1F] text-lg">Somchai Jaidee</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-xs text-[#86868B] bg-[#F2F2F7] px-2 py-0.5 rounded">Pharmacist</span>
                                    <span
                                        class="text-[10px] text-[#007AFF] border border-blue-100 px-1.5 py-0.5 rounded">Mid</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 text-center border-t border-gray-50 pt-4">
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">2</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Backlog</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">12</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Doing</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">5</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Review</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Inactive (Sleeping) -->
                        <div class="activity-card bg-[#FFFDF5] rounded-[24px] p-6 soft-shadow cursor-pointer border border-transparent"
                            data-role="staff" onclick="navigateToProfile(4, 'staff')">
                            <div class="absolute top-4 right-4 text-[#FF9500]"><i
                                    class="fa-solid fa-moon text-lg"></i></div>
                            <div class="flex flex-col items-center mb-6 opacity-80">
                                <div class="relative">
                                    <img src="https://i.pravatar.cc/150?u=Asst"
                                        class="w-20 h-20 rounded-full mb-3 border-4 border-white shadow-sm grayscale">
                                </div>
                                <h3 class="font-bold text-[#1D1D1F] text-lg">Wipawee S.</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-xs text-[#86868B] bg-white px-2 py-0.5 rounded border border-gray-100">Assistant</span>
                                    <span
                                        class="text-[10px] text-[#86868B] border border-gray-200 px-1.5 py-0.5 rounded bg-white">Jr</span>
                                </div>
                            </div>
                            <div
                                class="grid grid-cols-3 text-center border-t border-dashed border-orange-100 pt-4 opacity-60">
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">0</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Backlog</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">0</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Doing</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">0</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Review</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Active User -->
                        <div class="activity-card bg-white rounded-[24px] p-6 soft-shadow cursor-pointer border border-transparent"
                            data-role="user" onclick="navigateToProfile(2, 'user')">
                            <div class="flex flex-col items-center mb-6">
                                <div class="relative">
                                    <img src="https://i.pravatar.cc/150?u=User"
                                        class="w-20 h-20 rounded-full mb-3 border-4 border-white shadow-sm">
                                    <span
                                        class="absolute bottom-3 right-0 bg-[#34C759] w-5 h-5 rounded-full border-2 border-white"></span>
                                </div>
                                <h3 class="font-bold text-[#1D1D1F] text-lg">Suda Meesuk</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-xs text-[#86868B] bg-[#F2F2F7] px-2 py-0.5 rounded">Customer</span>
                                    <span
                                        class="text-[10px] text-[#FF9500] border border-orange-100 px-1.5 py-0.5 rounded">VIP</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 text-center border-t border-gray-50 pt-4">
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">1</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Orders</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">0</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Pending</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-[#1D1D1F]">-</p>
                                    <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Review</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ==========================================
             VIEW 3: USER PROFILE PAGE (Initially Hidden)
             ========================================== -->
            <div id="page-user-profile" class="hidden fade-in">

                <!-- Breadcrumb Navigation -->
                <div class="mb-6 flex items-center gap-2 text-sm">
                    <button onclick="navigateBackToList()"
                        class="flex items-center gap-1 text-[#86868B] hover:text-[#1D1D1F] transition">
                        <i class="fa-solid fa-arrow-left"></i> Users
                    </button>
                    <span class="text-[#86868B]">/</span>
                    <span class="font-semibold text-[#1D1D1F]" id="profile-name-header">Somchai Jaidee</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- LEFT COLUMN: Identity Card (Matched to Image) -->
                    <div class="lg:col-span-4 xl:col-span-3">
                        <div class="bg-white rounded-[24px] p-8 soft-shadow relative text-center">

                            <!-- Edit Link -->
                            <button onclick="switchTab('settings')"
                                class="absolute top-6 right-6 text-[#007AFF] font-bold text-sm hover:underline">Edit</button>

                            <!-- Avatar -->
                            <div class="relative inline-block mb-4 mt-2">
                                <div class="w-32 h-32 rounded-full p-1 border border-gray-100">
                                    <img src="https://i.pravatar.cc/150?u=Pharm"
                                        class="w-full h-full rounded-full object-cover" id="profile-avatar">
                                </div>
                                <div
                                    class="absolute bottom-2 right-2 w-6 h-6 bg-[#34C759] border-4 border-white rounded-full">
                                </div>
                            </div>

                            <!-- Name & Title -->
                            <h2 class="text-2xl font-bold text-[#1D1D1F] mb-1" id="profile-name">Somchai | pharm</h2>
                            <p class="text-[#86868B] text-sm mb-6" id="profile-role">Pharmacist</p>

                            <div class="w-full h-px bg-gray-100 mb-6"></div>

                            <!-- Details List -->
                            <div class="space-y-4 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Role</span>
                                    <span class="font-medium text-[#1D1D1F]"
                                        id="profile-role-detail">Pharmacist</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Email</span>
                                    <span class="font-medium text-[#1D1D1F]">somchai.j@pharmacy.com</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Phone</span>
                                    <span class="font-medium text-[#1D1D1F]">081-111-1110</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Gender</span>
                                    <span class="font-medium text-[#1D1D1F]">Male</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Birthday</span>
                                    <span class="font-medium text-[#1D1D1F]">Jan 02, 1985</span>
                                </div>
                                <div
                                    class="flex justify-between items-center border-t border-dotted border-gray-200 pt-4 mt-2">
                                    <span class="text-[#86868B]">Employee ID</span>
                                    <span class="font-bold text-[#1D1D1F]">EMP-001</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Branch</span>
                                    <span class="font-bold text-[#1D1D1F]">Chiang Mai</span>
                                </div>
                            </div>

                            <!-- Status Pill -->
                            <div class="mt-8 bg-[#F2F2F7] rounded-xl py-3 px-4 flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#34C759]"></div>
                                <span class="text-[#1D1D1F] font-medium text-sm">Account Active</span>
                            </div>

                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Tabs & Content -->
                    <div class="lg:col-span-8 xl:col-span-9">

                        <!-- Tabs -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div
                                class="bg-[#E5E5EA] p-1 rounded-full flex font-medium text-[13px] overflow-x-auto no-scrollbar">
                                <button onclick="switchTab('overview')" id="tab-overview"
                                    class="px-6 py-2 rounded-full bg-white text-[#1D1D1F] shadow-sm transition-all">Overview</button>
                                <button onclick="switchTab('schedule')" id="tab-schedule"
                                    class="px-6 py-2 rounded-full text-[#86868B] hover:bg-black/5 transition-all">Schedule</button>
                                <button onclick="switchTab('docs')" id="tab-docs"
                                    class="px-6 py-2 rounded-full text-[#86868B] hover:bg-black/5 transition-all">Documents</button>
                                <button onclick="switchTab('activity')" id="tab-activity"
                                    class="px-6 py-2 rounded-full text-[#86868B] hover:bg-black/5 transition-all">Activity</button>
                                <button onclick="switchTab('settings')" id="tab-settings"
                                    class="px-6 py-2 rounded-full text-[#86868B] hover:bg-black/5 transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-gear text-xs"></i> Settings
                                </button>
                            </div>
                        </div>

                        <!-- TAB 1: OVERVIEW -->
                        <div id="content-overview" class="space-y-8">
                            <h2 class="text-2xl font-bold text-[#1D1D1F]">Professional Overview</h2>
                            <!-- Staff Performance -->
                            <div id="staff-performance-section">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div class="bg-white p-6 rounded-[24px] soft-shadow">
                                        <div
                                            class="w-10 h-10 bg-blue-50 text-[#007AFF] rounded-lg flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <p class="text-xs text-[#86868B] font-bold uppercase tracking-wider mb-1">
                                            POSITION</p>
                                        <h3 class="text-xl font-bold text-[#1D1D1F] mb-4">Pharmacist</h3>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs bg-[#F2F2F7] px-2 py-1 rounded">Full-time</span>
                                            <span class="font-bold text-[#1D1D1F]">฿45,000</span>
                                        </div>
                                    </div>
                                    <div class="bg-white p-6 rounded-[24px] soft-shadow">
                                        <div
                                            class="w-10 h-10 bg-orange-50 text-[#FF9500] rounded-lg flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </div>
                                        <p class="text-xs text-[#86868B] font-bold uppercase tracking-wider mb-1">
                                            ACCESS LEVEL</p>
                                        <h3 class="text-xl font-bold text-[#1D1D1F] mb-4">Level 2 (Staff)</h3>
                                        <div class="flex gap-1 h-1.5 rounded-full overflow-hidden bg-gray-100">
                                            <div class="w-2/3 bg-[#007AFF]"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 5: SETTINGS (Edit Form) -->
                        <div id="content-settings" class="hidden space-y-6">
                            <div class="bg-white rounded-[24px] p-8 soft-shadow">

                                <!-- Form Row 1 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">First Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" value="Somchai"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Last Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" value="Jaidee | pharm"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                </div>

                                <!-- Form Row 2 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Email <span
                                                class="text-red-500">*</span></label>
                                        <input type="email" value="somchai.j@pharmacy.com"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Phone</label>
                                        <input type="text" value="081-111-1110"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                </div>

                                <!-- Form Row 3 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Gender</label>
                                        <select
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Birthday</label>
                                        <input type="date" value="1985-01-02"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                </div>

                                <!-- Role Read-only -->
                                <div class="mb-2">
                                    <label
                                        class="block text-[13px] font-bold text-[#86868B] mb-2 flex items-center gap-1">Role
                                        <i class="fa-solid fa-lock text-[10px]"></i></label>
                                    <div
                                        class="w-full bg-[#E5E5EA] rounded-lg px-4 py-3 text-[#86868B] font-medium text-[15px]">
                                        Pharmacist - System User
                                    </div>
                                </div>
                                <p class="text-[12px] text-[#86868B] mb-8">Contact Admin to update Role.</p>

                                <!-- Actions -->
                                <div class="flex justify-end pt-4 border-t border-gray-100">
                                    <button
                                        class="bg-[#007AFF] text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-md hover:bg-[#005ECB] transition">Save
                                        Changes</button>
                                </div>
                            </div>
                        </div>

                        <!-- Placeholders for other tabs -->
                        <div id="content-schedule" class="hidden">
                            <p class="text-center text-gray-400 mt-10">Schedule Content</p>
                        </div>
                        <div id="content-docs" class="hidden">
                            <p class="text-center text-gray-400 mt-10">Documents Content</p>
                        </div>
                        <div id="content-activity" class="hidden">
                            <p class="text-center text-gray-400 mt-10">Activity Content</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- JavaScript Logic -->
        <script>
            // Filter Logic
            function filterList(type) {
                // Update Buttons
                ['all', 'staff', 'user'].forEach(t => {
                    const btn = document.getElementById('filter-' + t);
                    if (t === type) {
                        btn.classList.add('view-toggle-active');
                        btn.classList.remove('view-toggle-inactive');
                    } else {
                        btn.classList.add('view-toggle-inactive');
                        btn.classList.remove('view-toggle-active');
                    }
                });

                // Filter Rows in List View
                document.querySelectorAll('#list-container .list-row').forEach(row => {
                    if (type === 'all' || row.dataset.role === type) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });

                // Filter Cards in Activity View
                document.querySelectorAll('#activity-container .activity-card').forEach(card => {
                    if (type === 'all' || card.dataset.role === type) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            // Navigation Logic
            function navigateToProfile(userId, type) {
                document.getElementById('page-user-list').classList.add('hidden');
                document.getElementById('page-user-profile').classList.remove('hidden');
                window.scrollTo(0, 0);

                // Mock Data Population
                const nameEl = document.getElementById('profile-name');
                const roleEl = document.getElementById('profile-role');
                const roleDetEl = document.getElementById('profile-role-detail');
                const avatarEl = document.getElementById('profile-avatar');

                if (type === 'user') {
                    nameEl.innerText = "Suda | VIP";
                    roleEl.innerText = "Customer";
                    roleDetEl.innerText = "Customer";
                    avatarEl.src = "https://i.pravatar.cc/150?u=User";
                    document.getElementById('staff-performance-section').classList.add('hidden');
                } else {
                    nameEl.innerText = "Somchai | pharm";
                    roleEl.innerText = "Pharmacist";
                    roleDetEl.innerText = "Pharmacist";
                    avatarEl.src = "https://i.pravatar.cc/150?u=Pharm";
                    document.getElementById('staff-performance-section').classList.remove('hidden');
                }
            }

            function navigateBackToList() {
                document.getElementById('page-user-profile').classList.add('hidden');
                document.getElementById('page-user-list').classList.remove('hidden');
            }

            // Tab Switching
            function switchTab(tabId) {
                ['overview', 'schedule', 'docs', 'activity', 'settings'].forEach(id => {
                    document.getElementById('content-' + id)?.classList.add('hidden');
                    document.getElementById('tab-' + id)?.classList.remove('bg-white', 'text-[#1D1D1F]', 'shadow-sm');
                    document.getElementById('tab-' + id)?.classList.add('text-[#86868B]');
                });
                document.getElementById('content-' + tabId).classList.remove('hidden');
                const activeBtn = document.getElementById('tab-' + tabId);
                activeBtn.classList.remove('text-[#86868B]');
                activeBtn.classList.add('bg-white', 'text-[#1D1D1F]', 'shadow-sm');
            }

            // View Switcher
            function switchView(viewName) {
                const listBtn = document.getElementById('toggle-list');
                const actBtn = document.getElementById('toggle-activity');
                const listView = document.getElementById('view-list');
                const actView = document.getElementById('view-activity');

                if (viewName === 'list') {
                    listBtn.classList.add('view-toggle-active');
                    listBtn.classList.remove('view-toggle-inactive');
                    actBtn.classList.add('view-toggle-inactive');
                    actBtn.classList.remove('view-toggle-active');
                    listView.classList.remove('hidden');
                    actView.classList.add('hidden');
                } else {
                    listBtn.classList.add('view-toggle-inactive');
                    listBtn.classList.remove('view-toggle-active');
                    actBtn.classList.add('view-toggle-active');
                    actBtn.classList.remove('view-toggle-inactive');
                    listView.classList.add('hidden');
                    actView.classList.remove('hidden');
                }
            }
        </script>
    </body>

    </html>
</x-app-layout>

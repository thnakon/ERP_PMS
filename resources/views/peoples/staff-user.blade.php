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

            /* Apple-style Flash Message */
            .flash-message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 12px 24px;
                border-radius: 99px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 9999;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                font-size: 14px;
                font-weight: 500;
                color: #333;
                opacity: 0;
                transition: opacity 0.3s ease, transform 0.3s ease;
                pointer-events: none;
            }

            .flash-message.show {
                opacity: 1;
                transform: translateX(-50%) translateY(10px);
            }

            .flash-message.success i {
                color: #34c759;
            }

            .flash-message.error i {
                color: #ff3b30;
            }
        </style>
    </head>

    <body class="bg-[#F5F5F7] font-sans">

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message">
            <i class="fa-solid fa-check-circle"></i>
            <span id="flash-text">Operation successful</span>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ session('success') }}", 'success');
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ $errors->first() }}", 'error');
                });
            </script>
        @endif

        <div class="os-container">

            <!-- WRAPPER for User List Page -->
            <div id="page-user-list" class="fade-in">
                {{-- [!!! REFACTORED HEADER !!!] --}}
                <div class="sr-header">
                    <div class="sr-header-left">
                        <p class="sr-breadcrumb">
                            Dashboard / People / <span style="color: #3a3a3c; font-weight: 600;">Staff | Users </span>
                        </p>

                        <h2 class="sr-page-title">Employees <span
                                style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">(28)</span></h2>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">

                        <!-- Type Filter -->
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px]"
                            style="border-radius: 25px">
                            <button onclick="filterList('all')" id="filter-all"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 {{ request('role') == 'all' || !request('role') ? 'view-toggle-active' : 'view-toggle-inactive' }}"
                                style="border-radius: 25px">All</button>
                            <button onclick="filterList('staff')" id="filter-staff"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 {{ request('role') == 'staff' ? 'view-toggle-active' : 'view-toggle-inactive' }}"
                                style="border-radius: 25px">Staff</button>
                            <button onclick="filterList('admin')" id="filter-admin"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 {{ request('role') == 'admin' ? 'view-toggle-active' : 'view-toggle-inactive' }}"
                                style="border-radius: 25px">Admin</button>
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
                        <button onclick="openAddEmployeeModal()"
                            class="bg-[#007AFF] text-white px-5 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-blue-500/20 hover:bg-[#005ECB] transition flex items-center gap-2"
                            style="border-radius: 25px">
                            <i class="fa-solid fa-user-plus"></i> Add Employee
                        </button>
                    </div>

                </div>
                </header>
                <!-- Controls Row (Search & Bulk Actions) -->
                <div class="inv-filters-wrapper">
                    <!-- Search & Filter Form -->
                    <form method="GET" action="{{ route('peoples.staff-user') }}" class="inv-search-form"
                        style="display: flex; gap: 10px; align-items: center;">

                        <!-- Sort Filter -->
                        <select name="sort" class="inv-form-input"
                            style="width: 180px; height: 44px; cursor: pointer;" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added
                            </option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added
                            </option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name
                                (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name
                                (Z-A)
                            </option>
                        </select>

                        <!-- Role Filter -->
                        <select name="role" class="inv-form-input"
                            style="width: 220px; height: 44px; cursor: pointer;" onchange="this.form.submit()">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User/Customer
                            </option>
                        </select>

                        <!-- Search Input -->
                        <div style="position: relative;">
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                                placeholder="Search Name, SKU..." class="inv-form-input"
                                style="width: 280px; height: 44px; padding-left: 40px;">
                            <i class="fa-solid fa-magnifying-glass"
                                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
                        </div>
                    </form>

                    <!-- Bulk Actions (Hidden by default) -->
                    <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
                        <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span
                                id="selected-count">0</span></span>
                        <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"
                            onclick="confirmBulkDelete()"><i class="fa-solid fa-trash"></i> Delete Selected</button>
                    </div>
                </div>

                <!-- VIEW 1: LIST VIEW -->
                <div id="view-list" class="transition-opacity duration-300" style="margin-top: 20px;">

                    <!-- Table Header -->
                    <div class="inv-card-row header grid-users"
                        style="grid-template-columns: 40px 60px 2fr 2fr 1.2fr 0.8fr 1.2fr 0.8fr 130px; ">
                        <div class="inv-checkbox-wrapper">
                            <input type="checkbox" class="inv-checkbox" id="select-all">
                        </div>
                        <div class="inv-col-header">#</div>
                        <div class="inv-col-header">Name</div>
                        <div class="inv-col-header">Email</div>
                        <div class="inv-col-header">Phone</div>
                        <div class="inv-col-header">Gender</div>
                        <div class="inv-col-header">Role</div>
                        <div class="inv-col-header">Status</div>
                        <div class="inv-col-header" style="text-align: right;">Actions</div>
                    </div>

                    <div class="space-y-3" id="list-container">
                        @forelse($staffs as $staff)
                            <div class="inv-card-row grid-users list-row" data-role="{{ strtolower($staff->role) }}"
                                style="grid-template-columns: 40px 60px 2fr 2fr 1.2fr 0.8fr 1.2fr 0.8fr 130px; border-radius: 24px; ">
                                <div class="inv-checkbox-wrapper">
                                    <input type="checkbox" class="inv-checkbox item-checkbox"
                                        data-id="{{ $staff->id }}">
                                </div>
                                <div class="inv-text-sub" style="font-weight: 500;">
                                    {{ ($staffs->currentPage() - 1) * $staffs->perPage() + $loop->iteration }}</div>
                                <div class="inv-product-info" onclick='navigateToProfile({{ json_encode($staff) }})'>

                                    <img src="{{ $staff->profile_photo_path ? asset('storage/' . $staff->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->first_name . ' ' . $staff->last_name) . '&background=random' }}"
                                        class="w-8 h-8 rounded-full mr-2 object-cover">
                                    <div class="inv-product-details">
                                        <div class="inv-product-name">{{ $staff->first_name }}
                                            {{ $staff->last_name }}</div>
                                        <div class="inv-product-generic" style="font-size: 11px; color: #86868b;">
                                            {{ $staff->employee_id ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="inv-text-sub">{{ $staff->email }}</div>
                                <div class="inv-text-sub">{{ $staff->phone_number ?? '-' }}</div>
                                <div class="inv-text-sub">{{ $staff->gender ? ucfirst($staff->gender) : '-' }}</div>
                                <div class="inv-text-main">{{ ucfirst($staff->role) }}
                                    {{ $staff->position ? '- ' . $staff->position : '' }}</div>
                                <div>
                                    <span class="inv-status-badge active">Active</span>
                                </div>
                                <div class="inv-action-group" data-label="Actions"
                                    style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                                    <button class="inv-icon-action"
                                        onclick='navigateToProfile({{ json_encode($staff) }})'
                                        style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#007aff'"
                                        onmouseout="this.style.color='#86868b'">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="inv-icon-action"
                                        onclick="openEditStaffModal({{ json_encode($staff) }})"
                                        style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#ff9500'"
                                        onmouseout="this.style.color='#86868b'">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="inv-icon-action btn-delete-row"
                                        onclick="confirmDeleteStaff({{ $staff->id }})"
                                        style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#ff3b30'"
                                        onmouseout="this.style.color='#86868b'">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-gray-500">No employees found.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="people-pagination">

                        <div class="pagination-controls">
                            {{ $staffs->onEachSide(1)->links('vendor.pagination.apple') }}
                        </div>
                    </div>
                </div>

                <!-- VIEW 2: ACTIVITY VIEW (Grid) -->
                <div id="view-activity" class="hidden transition-opacity duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="activity-container">

                        @forelse($staffs as $staff)
                            <div class="activity-card bg-white rounded-[24px] p-6 soft-shadow cursor-pointer border border-transparent"
                                data-role="{{ strtolower($staff->role) }}"
                                onclick="navigateToProfile({{ $staff->id }}, '{{ strtolower($staff->role) }}')">
                                <div class="flex flex-col items-center mb-6">
                                    <div class="relative">
                                        <img src="{{ $staff->profile_photo_path ? asset('storage/' . $staff->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->first_name . ' ' . $staff->last_name) . '&background=random' }}"
                                            class="w-20 h-20 rounded-full mb-3 border-4 border-white shadow-sm object-cover">
                                        <span
                                            class="absolute bottom-3 right-0 bg-[#34C759] w-5 h-5 rounded-full border-2 border-white"></span>
                                    </div>
                                    <h3 class="font-bold text-[#1D1D1F] text-lg">{{ $staff->first_name }}
                                        {{ $staff->last_name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span
                                            class="text-xs text-[#86868B] bg-[#F2F2F7] px-2 py-0.5 rounded">{{ ucfirst($staff->role) }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 text-center border-t border-gray-50 pt-4">
                                    <div>
                                        <p class="text-xl font-bold text-[#1D1D1F]">-</p>
                                        <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Orders</p>
                                    </div>
                                    <div>
                                        <p class="text-xl font-bold text-[#1D1D1F]">-</p>
                                        <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Pending</p>
                                    </div>
                                    <div>
                                        <p class="text-xl font-bold text-[#1D1D1F]">-</p>
                                        <p class="text-[9px] text-[#86868B] uppercase font-bold mt-1">Review</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10">
                                <p class="text-gray-500">No staff found.</p>
                            </div>
                        @endforelse

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
                                    <span class="font-medium text-[#1D1D1F]" id="profile-role-detail">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Email</span>
                                    <span class="font-medium text-[#1D1D1F]" id="profile-email">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Phone</span>
                                    <span class="font-medium text-[#1D1D1F]" id="profile-phone">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Gender</span>
                                    <span class="font-medium text-[#1D1D1F]" id="profile-gender">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Birthday</span>
                                    <span class="font-medium text-[#1D1D1F]" id="profile-birthday">-</span>
                                </div>
                                <div
                                    class="flex justify-between items-center border-t border-dotted border-gray-200 pt-4 mt-2">
                                    <span class="text-[#86868B]">Employee ID</span>
                                    <span class="font-bold text-[#1D1D1F]" id="profile-employee-id">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Position</span>
                                    <span class="font-bold text-[#1D1D1F]" id="profile-position">-</span>
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
                            <!-- Section A: Key Status Cards -->
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Professional Overview
                                    </h3>
                                    <button
                                        class="text-[#007AFF] text-sm font-medium hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-download"></i> Download Resume
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    <!-- License Card -->

                                    <div
                                        class="bg-white rounded-[24px] p-6 soft-shadow hover:scale-[1.01] transition duration-300">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#E8F8F0] flex items-center justify-center text-[#34C759] mb-4">
                                            <i class="fa-solid fa-file-medical text-xl"></i>
                                        </div>
                                        <h4 class="text-[#86868B] text-xs font-bold uppercase tracking-wider mb-1">
                                            License
                                        </h4>
                                        <p class="text-xl font-bold text-[#1D1D1F] mb-1">No.
                                        </p>
                                        <div
                                            class="flex justify-between items-center mt-4 pt-4 border-t border-gray-50">
                                            <span class="text-sm font-medium text-[#34C759] flex items-center gap-1"><i
                                                    class="fa-solid fa-circle-check"></i> Valid</span>
                                            <span class="text-xs text-[#FF9500] font-medium">Exp: Jan 2025</span>
                                        </div>
                                    </div>


                                    <!-- Employment Card -->
                                    <div
                                        class="bg-white rounded-[24px] p-6 soft-shadow hover:scale-[1.01] transition duration-300">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#E5F1FF] flex items-center justify-center text-[#007AFF] mb-4">
                                            <i class="fa-solid fa-user-doctor text-xl"></i>
                                        </div>
                                        <h4 class="text-[#86868B] text-xs font-bold uppercase tracking-wider mb-1">
                                            Position
                                        </h4>
                                        <p class="text-xl font-bold text-[#1D1D1F] mb-1" id="profile-position-card">-
                                        </p>
                                        <div
                                            class="flex justify-between items-center mt-4 pt-4 border-t border-gray-50">
                                            <span
                                                class="text-xs bg-[#F2F2F7] px-2 py-1 rounded text-[#1D1D1F]">Full-time</span>
                                            <span class="text-sm font-bold text-[#1D1D1F]">฿45,000</span>
                                        </div>
                                    </div>

                                    <!-- Permissions Card -->
                                    <div
                                        class="bg-white rounded-[24px] p-6 soft-shadow hover:scale-[1.01] transition duration-300 md:col-span-2 xl:col-span-1">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#FFF6E5] flex items-center justify-center text-[#FF9500] mb-4">
                                            <i class="fa-solid fa-shield-halved text-xl"></i>
                                        </div>
                                        <h4 class="text-[#86868B] text-xs font-bold uppercase tracking-wider mb-1">
                                            Access
                                            Level</h4>
                                        <p class="text-xl font-bold text-[#1D1D1F] mb-1" id="profile-access-level">
                                            Level 1 (Staff)
                                        </p>
                                        <div class="flex gap-1 mt-5">
                                            <div class="h-1.5 w-full rounded-full bg-[#007AFF]"></div>
                                            <div class="h-1.5 w-full rounded-full bg-[#007AFF]"></div>
                                            <div class="h-1.5 w-full rounded-full ">
                                            </div>
                                            <div class="h-1.5 w-full rounded-full bg-[#E5E5EA]"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section B: Performance Metrics (New) -->
                            <div>
                                <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-chart-line text-[#007AFF]"></i> Performance (November)
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                        <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                            Prescriptions</p>
                                        <p class="text-2xl font-bold text-[#1D1D1F]">1,240</p>
                                        <p class="text-xs text-[#34C759] font-medium mt-1 flex items-center gap-1"><i
                                                class="fa-solid fa-arrow-up"></i> 12%</p>
                                    </div>
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                        <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                            Consultations</p>
                                        <p class="text-2xl font-bold text-[#1D1D1F]">85</p>
                                        <p class="text-xs text-[#86868B] font-medium mt-1">Avg 15m/case</p>
                                    </div>
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                        <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                            Sales
                                            (Personal)</p>
                                        <p class="text-2xl font-bold text-[#1D1D1F]">฿450k</p>
                                        <p class="text-xs text-[#34C759] font-medium mt-1">On Target</p>
                                    </div>
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                        <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                            Attendance</p>
                                        <p class="text-2xl font-bold text-[#1D1D1F]">98%</p>
                                        <p class="text-xs text-[#FF9500] font-medium mt-1">1 Late</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section C: Education & CPE (New) -->
                            <div>
                                <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-graduation-cap text-[#007AFF]"></i> Education & Training
                                </h4>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Education -->
                                    <div class="bg-white rounded-[24px] p-6 soft-shadow flex items-start gap-5">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#F2F2F7] flex items-center justify-center text-[#86868B] shrink-0">
                                            <i class="fa-solid fa-certificate text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#1D1D1F] text-lg">Academic Background</h4>
                                            <div class="mt-3 space-y-4">
                                                <div class="relative pl-4 border-l-2 border-gray-100">
                                                    <p class="font-semibold text-[#1D1D1F] text-sm">Doctor of Pharmacy
                                                        (Pharm.D.)</p>
                                                    <p class="text-xs text-[#86868B]">Chulalongkorn University • 2015
                                                    </p>
                                                </div>
                                                <div class="relative pl-4 border-l-2 border-gray-100">
                                                    <p class="font-semibold text-[#1D1D1F] text-sm">Board Certified
                                                        Pharmacotherapy</p>
                                                    <p class="text-xs text-[#86868B]">Pharmacy Council • 2018</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CPE Credits -->
                                    <div class="bg-white rounded-[24px] p-6 soft-shadow flex items-start gap-5">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#FFF6E5] flex items-center justify-center text-[#FF9500] shrink-0">
                                            <i class="fa-solid fa-star text-xl"></i>
                                        </div>
                                        <div class="w-full">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-[#1D1D1F] text-lg">CPE Credits (2025)</h4>
                                                <span
                                                    class="text-[10px] font-bold bg-[#FFF6E5] text-[#FF9500] px-2 py-0.5 rounded-md">Required</span>
                                            </div>
                                            <div class="flex items-end gap-2 mb-3">
                                                <p class="text-3xl font-bold text-[#1D1D1F]">15.5</p>
                                                <p class="text-sm text-[#86868B] font-medium mb-1">/ 20 Credits</p>
                                            </div>
                                            <div class="w-full bg-[#F2F2F7] rounded-full h-2.5 mb-2">
                                                <div class="bg-gradient-to-r from-[#FF9500] to-[#FFCC00] h-2.5 rounded-full"
                                                    style="width: 78%"></div>
                                            </div>
                                            <p class="text-[11px] text-[#86868B]">Last updated: 2 days ago via Pharmacy
                                                Council API</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TAB 2: SCHEDULE (Static for now as no Schedule model) -->
                        <div id="content-schedule" class="tab-content hidden space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Shift Schedule</h3>
                                <span
                                    class="text-sm text-[#86868B] font-medium bg-white px-3 py-1 rounded-full shadow-sm">November
                                    2025</span>
                            </div>
                            <!-- ... (Keep static schedule content for now) ... -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                    <p class="text-xs text-[#86868B] uppercase font-bold">Total Hours</p>
                                    <p class="text-xl font-bold text-[#1D1D1F]">176</p>
                                </div>
                                <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                    <p class="text-xs text-[#34C759] uppercase font-bold">On Time</p>
                                    <p class="text-xl font-bold text-[#34C759]">100%</p>
                                </div>
                                <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                    <p class="text-xs text-[#007AFF] uppercase font-bold">Shifts</p>
                                    <p class="text-xl font-bold text-[#007AFF]">22</p>
                                </div>
                                <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                    <p class="text-xs text-[#FF9500] uppercase font-bold">OT</p>
                                    <p class="text-xl font-bold text-[#FF9500]">4h</p>
                                </div>
                            </div>
                            <div class="bg-white rounded-[24px] overflow-hidden soft-shadow">
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fa-solid fa-calendar-days text-4xl mb-4 opacity-50"></i>
                                    <p>Schedule integration coming soon.</p>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: DOCUMENTS (Static) -->
                        <div id="content-docs" class="tab-content hidden space-y-6">
                            <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight mb-4">Documents & Contracts
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Doc 1 -->
                                <div
                                    class="bg-white p-5 rounded-[20px] soft-shadow border border-transparent hover:border-[#007AFF] transition cursor-pointer group flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-[#FFECEC] flex items-center justify-center text-[#FF3B30] shrink-0">
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 class="font-semibold text-[#1D1D1F] text-sm truncate">Employment
                                            Contract.pdf
                                        </h4>
                                        <p class="text-xs text-[#86868B]">2.4 MB • Signed 2020</p>
                                    </div>
                                    <i
                                        class="fa-solid fa-download text-[#86868B] ml-auto group-hover:text-[#007AFF]"></i>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 4: ACTIVITY -->
                        <div id="content-activity" class="tab-content hidden space-y-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Activity Log</h3>
                                <a href="{{ route('profile.logs.export') }}"
                                    class="text-[#007AFF] text-sm font-medium hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-download"></i> Export Log
                                </a>
                            </div>
                            <div class="bg-white rounded-[24px] p-6 soft-shadow">
                                <div class="space-y-0">
                                    <div class="flex gap-4 relative pb-8 timeline-item">
                                        <div
                                            class="relative z-10 w-10 h-10 rounded-full bg-[#E5F1FF] flex items-center justify-center border-4 border-white shadow-sm text-[#007AFF] shrink-0">

                                            <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>

                                            <i class="fa-solid fa-pen text-sm"></i>

                                            <i class="fa-solid fa-key text-sm"></i>

                                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>

                                        </div>
                                        <div class="pt-1 w-full">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-center sm:gap-2 mb-1 justify-between">
                                                <h4 class="font-semibold text-[#1D1D1F] text-sm">
                                                </h4>
                                                <span
                                                    class="text-[10px] text-[#86868B] bg-[#F2F2F7] px-2 py-0.5 rounded-md w-fit"></span>
                                            </div>
                                            <p class="text-[#86868B] text-xs"></p>
                                            <p class="text-[10px] text-[#86868B] mt-1">IP:

                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-center py-8 text-[#86868B]">
                                        <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-20"></i>
                                        <p>No activity recorded yet.</p>
                                    </div>

                                </div>


                                <div class="mt-4 pt-4 border-t border-gray-100">

                                </div>

                            </div>
                        </div>

                        <!-- TAB 5: ACCOUNT SETTINGS (Merged from Version B) -->
                        <div id="content-settings" class="tab-content hidden space-y-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Account Settings</h3>
                                <button onclick="document.getElementById('staff-profile-update-form').submit()"
                                    class="bg-[#007AFF] text-white px-5 py-2 rounded-full font-medium text-sm shadow-md hover:bg-[#005ECB] transition">
                                    <i class="fa-solid fa-check mr-1"></i> Save Changes
                                </button>
                            </div>

                            <!-- SECTION 1: EDIT PROFILE -->
                            <form id="staff-profile-update-form" method="POST" action=""
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="bg-white rounded-[24px] p-8 soft-shadow">
                                    <h2 class="text-lg font-bold text-[#1D1D1F] mb-6 flex items-center gap-2">
                                        <i class="fa-regular fa-id-card text-[#007AFF]"></i> Edit Personal Details
                                    </h2>

                                    <!-- Avatar Edit Section -->
                                    <div class="flex items-center gap-6 mb-8 pb-8 border-b border-gray-100">
                                        <div class="relative group cursor-pointer"
                                            onclick="document.getElementById('profile_photo_input').click()">
                                            <div
                                                class="w-20 h-20 rounded-full p-1 bg-gradient-to-b from-gray-100 to-gray-200 relative shadow-inner">
                                                <img id="staff-profile-preview" src="" alt="Current Profile"
                                                    class="w-full h-full rounded-full object-cover border-2 border-white shadow-sm">
                                            </div>
                                            <div
                                                class="absolute bottom-0 right-0 w-6 h-6 bg-[#007AFF] rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                                                <i class="fa-solid fa-camera text-white text-[10px]"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-[#1D1D1F] mb-1">Profile Photo</h4>
                                            <div class="flex gap-3">
                                                <button type="button"
                                                    onclick="document.getElementById('profile_photo_input').click()"
                                                    class="text-xs bg-white border border-gray-300 px-3 py-1.5 rounded-lg font-medium text-[#1D1D1F] hover:bg-gray-50 transition shadow-sm">Upload
                                                    New</button>
                                                <input type="file" id="profile_photo_input" name="profile_photo"
                                                    class="hidden" accept="image/*" onchange="previewImage(event)">
                                            </div>
                                            <p class="text-[11px] text-[#86868B] mt-2">Recommended 300x300 px. JPG,
                                                PNG.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        <!-- Split Name: First & Last -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="group">
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">First
                                                    Name <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <input type="text" name="first_name" value=""
                                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Last
                                                    Name <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <input type="text" name="last_name" value=""
                                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Email & Phone -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Email
                                                    <span class="text-red-500">*</span></label>
                                                <input type="email" name="email" value=""
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                                    required>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Phone</label>
                                                <input type="text" name="phone_number" value=""
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                            </div>
                                        </div>

                                        <!-- Gender & Birthday -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="group">
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Gender</label>
                                                <div class="relative">
                                                    <select name="gender"
                                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                                        <option value="male">Male
                                                        </option>
                                                        <option value="female">Female
                                                        </option>
                                                        <option value="other">Other
                                                        </option>
                                                    </select>
                                                    <i
                                                        class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#86868B] text-xs pointer-events-none"></i>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Birthday</label>
                                                <input type="date" name="birthdate" value=""
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                            </div>
                                        </div>

                                        <!-- Role & Position (Admin can edit) -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="group">
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Role
                                                    <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <select name="role"
                                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                                        <option value="staff">Staff</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="pharmacist">Pharmacist</option>
                                                        <option value="user">User/Customer</option>
                                                    </select>
                                                    <i
                                                        class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#86868B] text-xs pointer-events-none"></i>
                                                </div>
                                            </div>
                                            <div class="group">
                                                <label
                                                    class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Position</label>
                                                <input type="text" name="position" value=""
                                                    placeholder="e.g. Senior Pharmacist"
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>

                            <!-- SECTION 2: SECURITY -->
                            <div class="bg-white rounded-[24px] p-8 soft-shadow">
                                <h2 class="text-lg font-bold text-[#1D1D1F] mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-shield-halved text-[#34C759]"></i> Security
                                </h2>

                                <!-- Password -->
                                <form method="post" action="{{ route('password.update') }}" class="mb-8">
                                    @csrf
                                    @method('put')
                                    <h3 class="text-[14px] font-semibold text-[#1D1D1F] mb-4">Update Password</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <input type="password" name="current_password"
                                                placeholder="Current Password"
                                                class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]">
                                            @error('current_password', 'updatePassword')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <input type="password" name="password" placeholder="New Password"
                                                class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]">
                                            @error('password', 'updatePassword')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <input type="password" name="password_confirmation"
                                                placeholder="Confirm Password"
                                                class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]">
                                        </div>
                                    </div>
                                    <div class="mt-4 text-right">
                                        <button type="submit"
                                            class="text-sm font-medium text-[#007AFF] hover:underline">Update
                                            Password</button>
                                    </div>
                                </form>

                                <!-- 2FA Switch -->
                                <div class="flex items-center justify-between py-4 border-t border-gray-100">
                                    <div>
                                        <h3 class="text-[14px] font-semibold text-[#1D1D1F]">Two-Factor Authentication
                                            (2FA)</h3>
                                        <p class="text-[12px] text-[#86868B]">Require verification code upon login.</p>
                                    </div>
                                    <label class="apple-switch">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>

                                <!-- Sessions -->
                                <div class="flex items-center justify-between py-4 border-t border-gray-100">
                                    <div>
                                        <h3 class="text-[14px] font-semibold text-[#1D1D1F]">Active Sessions</h3>
                                        <p class="text-[12px] text-[#86868B]">Log out from all other devices.</p>
                                    </div>
                                    <button
                                        class="bg-[#F2F2F7] text-[#FF3B30] border border-gray-200 px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#FFE5E5] transition">
                                        Log Out All
                                    </button>
                                </div>
                            </div>

                            <!-- SECTION 3: PREFERENCES -->
                            <div class="bg-white rounded-[24px] p-8 soft-shadow">
                                <h2 class="text-lg font-bold text-[#1D1D1F] mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-sliders text-[#FF9500]"></i> Preferences
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-[13px] font-semibold text-[#1D1D1F] mb-2">Language</label>
                                        <select name="language"
                                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                            <option value="th">
                                                Thai
                                                (ภาษาไทย)</option>
                                            <option value="en">
                                                English
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[13px] font-semibold text-[#1D1D1F] mb-2">Theme</label>

                                        <div class="bg-[#F2F2F7] p-1 rounded-xl flex">
                                            <button
                                                class="flex-1 bg-white shadow-sm rounded-lg py-2 text-sm font-medium text-[#1D1D1F] flex items-center justify-center gap-2"><i
                                                    class="fa-regular fa-sun"></i> Light</button>
                                            <button
                                                class="flex-1 text-[#86868B] py-2 text-sm font-medium flex items-center justify-center gap-2 hover:bg-black/5 rounded-lg"><i
                                                    class="fa-regular fa-moon"></i> Dark</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 4: DELETE ACCOUNT (DANGER ZONE) -->
                            <div class="bg-white rounded-[24px] p-8 soft-shadow border border-red-100">
                                <h2 class="text-lg font-bold text-[#FF3B30] mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                                </h2>
                                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                                    <div>
                                        <h3 class="text-[14px] font-semibold text-[#1D1D1F]">Delete Account</h3>
                                        <p class="text-[12px] text-[#86868B] mt-1 max-w-md">Once you delete your
                                            account,
                                            there is no going back. Please be certain.</p>
                                    </div>
                                    <button
                                        onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                                        class="bg-[#FFF5F5] text-[#FF3B30] border border-red-200 px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-[#FFE5E5] transition whitespace-nowrap">
                                        Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        </div>

        <!-- JavaScript Logic -->
        <script>
            // --- Helper Functions ---
            function showFlash(message, type = 'success') {
                const flash = document.getElementById('flash-message');
                const text = document.getElementById('flash-text');
                const icon = flash.querySelector('i');

                text.textContent = message;
                flash.className = 'flash-message show ' + type;

                if (type === 'success') {
                    icon.className = 'fa-solid fa-check-circle';
                } else {
                    icon.className = 'fa-solid fa-circle-exclamation';
                }

                setTimeout(() => {
                    flash.classList.remove('show');
                }, 3000);
            }

            // Filter Logic (Server-Side)
            function filterList(type) {
                const roleSelect = document.querySelector('select[name="role"]');
                if (roleSelect) {
                    roleSelect.value = type;
                    roleSelect.form.submit();
                }
            }

            // Navigation Logic - Navigate to profile with full staff data
            let currentStaffId = null;

            function navigateToProfile(staff) {
                document.getElementById('page-user-list').classList.add('hidden');
                document.getElementById('page-user-profile').classList.remove('hidden');
                window.scrollTo(0, 0);

                // Store current staff ID for form submission
                currentStaffId = staff.id;

                // Format name
                const fullName = (staff.first_name || '') + ' ' + (staff.last_name || '');
                const avatarUrl = staff.profile_photo_path ?
                    '/storage/' + staff.profile_photo_path :
                    'https://ui-avatars.com/api/?name=' + encodeURIComponent(fullName) + '&background=random';

                // Format birthday
                let birthdayStr = '-';
                if (staff.birthdate) {
                    const date = new Date(staff.birthdate);
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: '2-digit'
                    };
                    birthdayStr = date.toLocaleDateString('en-US', options);
                }

                // Populate left column profile info
                document.getElementById('profile-name-header').innerText = fullName;
                document.getElementById('profile-name').innerText = fullName;
                document.getElementById('profile-role').innerText = staff.position || (staff.role ? staff.role.charAt(0)
                    .toUpperCase() + staff.role.slice(1) : '-');
                document.getElementById('profile-role-detail').innerText = staff.role ? staff.role.charAt(0).toUpperCase() +
                    staff.role.slice(1) : '-';
                document.getElementById('profile-avatar').src = avatarUrl;
                document.getElementById('profile-email').innerText = staff.email || '-';
                document.getElementById('profile-phone').innerText = staff.phone_number || '-';
                document.getElementById('profile-gender').innerText = staff.gender ? staff.gender.charAt(0).toUpperCase() +
                    staff.gender.slice(1) : '-';
                document.getElementById('profile-birthday').innerText = birthdayStr;
                document.getElementById('profile-employee-id').innerText = staff.employee_id || '-';
                document.getElementById('profile-position').innerText = staff.position || '-';

                // Update settings form
                const form = document.getElementById('staff-profile-update-form');
                if (form) {
                    form.action = '/peoples/staff-user/' + staff.id;
                }

                // Populate edit form fields
                const editFirstName = document.querySelector('#staff-profile-update-form input[name="first_name"]');
                const editLastName = document.querySelector('#staff-profile-update-form input[name="last_name"]');
                const editEmail = document.querySelector('#staff-profile-update-form input[name="email"]');
                const editPhone = document.querySelector('#staff-profile-update-form input[name="phone_number"]');
                const editGender = document.querySelector('#staff-profile-update-form select[name="gender"]');
                const editBirthdate = document.querySelector('#staff-profile-update-form input[name="birthdate"]');
                const editRole = document.querySelector('#staff-profile-update-form select[name="role"]');
                const editPosition = document.querySelector('#staff-profile-update-form input[name="position"]');
                const profilePreview = document.getElementById('staff-profile-preview');

                if (editFirstName) editFirstName.value = staff.first_name || '';
                if (editLastName) editLastName.value = staff.last_name || '';
                if (editEmail) editEmail.value = staff.email || '';
                if (editPhone) editPhone.value = staff.phone_number || '';
                if (editGender) editGender.value = staff.gender || '';
                if (editBirthdate) editBirthdate.value = staff.birthdate ? staff.birthdate.split('T')[0] : '';
                if (editRole) editRole.value = staff.role || 'staff';
                if (editPosition) editPosition.value = staff.position || '';
                if (profilePreview) profilePreview.src = avatarUrl;

                // Update overview cards with position info
                const positionCard = document.getElementById('profile-position-card');
                if (positionCard) {
                    positionCard.innerText = staff.position || (staff.role ? staff.role.charAt(0).toUpperCase() + staff.role
                        .slice(1) : '-');
                }

                // Update access level based on role
                const accessLevel = document.getElementById('profile-access-level');
                if (accessLevel) {
                    if (staff.role === 'admin') {
                        accessLevel.innerText = 'Level 3 (Admin)';
                    } else if (staff.role === 'pharmacist') {
                        accessLevel.innerText = 'Level 2 (Pharmacist)';
                    } else {
                        accessLevel.innerText = 'Level 1 (Staff)';
                    }
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
        <!-- MODAL: Add Employee -->
        <div class="inv-modal-overlay" id="modal-add-employee"
            style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center;">
            <div class="inv-modal"
                style="background: #ffffff; width: 90%; max-width: 650px; border-radius: 24px; padding: 0; overflow: hidden; transform: scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);">
                <form id="employee-form" method="POST" action="{{ route('peoples.staff-user.store') }}"
                    enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; height: 85vh; max-height: 800px;">
                    @csrf

                    <div class="inv-modal-header"
                        style="padding: 24px 32px; border-bottom: none; display: flex; justify-content: space-between; align-items: center;">
                        <div class="inv-modal-title" style="font-size: 24px; font-weight: 700; color: #1d1d1f;">Add
                            New Employee</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeEmployeeModal('modal-add-employee')"
                            style="font-size: 28px; color: #86868b; background: none; border: none; cursor: pointer; line-height: 1;">&times;</button>
                    </div>

                    <div class="inv-modal-body" style="padding: 0 32px 32px; overflow-y: auto; flex: 1;">

                        <!-- Image Upload -->
                        <div class="inv-form-group" style="text-align: center; margin-bottom: 32px;">
                            <div class="inv-image-upload-box"
                                style="width: 140px; height: 140px; margin: 0 auto; border-radius: 50%; border: 2px dashed #d2d2d7; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; background: #fbfbfd; transition: all 0.2s;"
                                onclick="document.getElementById('profile-image-input').click()"
                                onmouseover="this.style.borderColor='#007aff'; this.style.background='#f2f2f7'"
                                onmouseout="this.style.borderColor='#d2d2d7'; this.style.background='#fbfbfd'">
                                <img id="profile-image-preview" src=""
                                    style="display: none; width: 100%; height: 100%; object-fit: cover;">
                                <div id="profile-image-placeholder" style="color: #8e8e93; text-align: center;">
                                    <i class="fa-solid fa-camera"
                                        style="font-size: 28px; margin-bottom: 8px; color: #86868b;"></i>
                                    <div style="font-size: 13px; font-weight: 500;">Add Photo</div>
                                </div>
                                <input type="file" name="profile_photo_path" id="profile-image-input"
                                    accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
                            </div>
                        </div>

                        <!-- 1. Personal Information -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            1. Personal Information</div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">First
                                    Name <span style="color: #ff3b30; margin-left: 2px;">*</span></label>
                                <input type="text" name="first_name" class="inv-form-input w-full"
                                    placeholder="e.g. Somsak" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Last
                                    Name</label>
                                <input type="text" name="last_name" class="inv-form-input w-full"
                                    placeholder="e.g. Dee"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 32px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Gender</label>
                                <select name="gender" class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%; background-color: #fff;">
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Date
                                    of Birth</label>
                                <input type="date" name="birthdate" class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <!-- 2. Employment Details -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            2. Employment Details</div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Role
                                    <span style="color: #ff3b30; margin-left: 2px;">*</span></label>
                                <select name="role" class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%; background-color: #fff;">
                                    <option value="staff" selected>Staff</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User/Customer</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Position</label>
                                <input type="text" name="position" class="inv-form-input w-full"
                                    placeholder="e.g. Pharmacist, Assistant"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <div class="inv-form-group" style="margin-bottom: 32px;">
                            <label class="inv-form-label"
                                style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Pharmacist
                                License ID (Optional)</label>
                            <input type="text" name="pharmacist_license_id" class="inv-form-input w-full"
                                placeholder="e.g. PH-12345"
                                style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                        </div>

                        <!-- 3. Account & Contact -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            3. Account & Contact</div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Email
                                    Address <span style="color: #ff3b30; margin-left: 2px;">*</span></label>
                                <input type="email" name="email" class="inv-form-input w-full"
                                    placeholder="name@example.com" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Phone
                                    Number</label>
                                <input type="tel" name="phone_number" class="inv-form-input w-full"
                                    placeholder="081-xxx-xxxx"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <div class="inv-form-group" style="margin-bottom: 10px;">
                            <label class="inv-form-label"
                                style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Password
                                <span style="color: #ff3b30; margin-left: 2px;">*</span></label>
                            <input type="password" name="password" class="inv-form-input w-full"
                                placeholder="Secure Password" required
                                style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            <p style="font-size: 12px; color: #86868b; margin-top: 6px;">Must be at least 8 characters
                            </p>
                        </div>

                    </div>

                    <div class="inv-modal-footer"
                        style="padding: 20px 32px; border-top: 1px solid #e5e5ea; display: flex; justify-content: flex-end; gap: 12px; background: #fff;">
                        <button type="button" class="inv-btn-cancel"
                            onclick="closeEmployeeModal('modal-add-employee')"
                            style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 500; color: #1d1d1f; background: #e5e5ea; border: none; cursor: pointer; transition: background 0.2s;">
                            Cancel
                        </button>
                        <button type="submit" class="inv-btn-submit"
                            style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 600; color: #fff; background: #007aff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3); transition: background 0.2s;">
                            Save Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Employee -->
        <div class="inv-modal-overlay" id="modal-edit-employee"
            style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center;">
            <div class="inv-modal"
                style="background: #ffffff; width: 90%; max-width: 650px; border-radius: 24px; padding: 0; overflow: hidden; transform: scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);">
                <form id="edit-employee-form" method="POST" action="" enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; height: 85vh; max-height: 800px;">
                    @csrf
                    @method('PUT')

                    <div class="inv-modal-header"
                        style="padding: 24px 32px; border-bottom: none; display: flex; justify-content: space-between; align-items: center;">
                        <div class="inv-modal-title" style="font-size: 24px; font-weight: 700; color: #1d1d1f;">Edit
                            Employee</div>
                        <button type="button" class="inv-modal-close"
                            onclick="closeStaffModal('modal-edit-employee')"
                            style="font-size: 28px; color: #86868b; background: none; border: none; cursor: pointer; line-height: 1;">&times;</button>
                    </div>

                    <div class="inv-modal-body" style="padding: 0 32px 32px; overflow-y: auto; flex: 1;">
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            1. Personal Information</div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">First
                                    Name <span style="color: #ff3b30;">*</span></label>
                                <input type="text" name="first_name" id="edit-first-name"
                                    class="inv-form-input w-full" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Last
                                    Name</label>
                                <input type="text" name="last_name" id="edit-last-name"
                                    class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 32px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Gender</label>
                                <select name="gender" id="edit-gender" class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%; background-color: #fff;">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Date
                                    of Birth</label>
                                <input type="date" name="birthdate" id="edit-birthdate"
                                    class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            2. Employment Details</div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Role
                                    <span style="color: #ff3b30;">*</span></label>
                                <select name="role" id="edit-role" class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%; background-color: #fff;">
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User/Customer</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Position</label>
                                <input type="text" name="position" id="edit-position"
                                    class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>

                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            3. Contact</div>

                        <div class="inv-form-row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Email
                                    Address <span style="color: #ff3b30;">*</span></label>
                                <input type="email" name="email" id="edit-email" class="inv-form-input w-full"
                                    required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="display: block; font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Phone
                                    Number</label>
                                <input type="tel" name="phone_number" id="edit-phone"
                                    class="inv-form-input w-full"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                            </div>
                        </div>
                    </div>

                    <div class="inv-modal-footer"
                        style="padding: 20px 32px; border-top: 1px solid #e5e5ea; display: flex; justify-content: flex-end; gap: 12px; background: #fff;">
                        <button type="button" class="inv-btn-cancel"
                            onclick="closeStaffModal('modal-edit-employee')"
                            style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 500; color: #1d1d1f; background: #e5e5ea; border: none; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" class="inv-btn-submit"
                            style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 600; color: #fff; background: #007aff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);">
                            Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Delete Confirmation -->
        <div class="inv-modal-overlay" id="modal-delete-staff"
            style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(8px); z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center;">
            <div class="inv-modal"
                style="background: #ffffff; width: 90%; max-width: 400px; border-radius: 24px; padding: 0; overflow: hidden; transform: scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);">
                <div class="inv-modal-header"
                    style="padding: 24px 32px; border-bottom: none; display: flex; justify-content: space-between; align-items: center;">
                    <div class="inv-modal-title" style="font-size: 20px; font-weight: 700; color: #ff3b30;">Delete
                        Employee</div>
                    <button type="button" class="inv-modal-close" onclick="closeStaffModal('modal-delete-staff')"
                        style="font-size: 28px; color: #86868b; background: none; border: none; cursor: pointer; line-height: 1;">&times;</button>
                </div>
                <div class="inv-modal-body" style="padding: 0 32px 24px;">
                    <p id="delete-staff-text" style="color: #424245; font-size: 15px; line-height: 1.5;">Are you sure
                        you want to delete this employee? This action cannot be undone.</p>
                </div>
                <div class="inv-modal-footer"
                    style="padding: 20px 32px; border-top: 1px solid #e5e5ea; display: flex; justify-content: flex-end; gap: 12px; background: #fff;">
                    <button type="button" class="inv-btn-cancel" onclick="closeStaffModal('modal-delete-staff')"
                        style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 500; color: #1d1d1f; background: #e5e5ea; border: none; cursor: pointer;">
                        Cancel
                    </button>

                    {{-- Single Delete Form --}}
                    <form id="delete-staff-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-submit"
                            style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 600; color: #fff; background: #ff3b30; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(255, 59, 48, 0.3);">
                            Delete
                        </button>
                    </form>

                    {{-- Bulk Delete Button --}}
                    <button id="btn-bulk-delete-staff" type="button" class="inv-btn-submit"
                        style="padding: 10px 24px; border-radius: 12px; font-size: 15px; font-weight: 600; color: #fff; background: #ff3b30; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(255, 59, 48, 0.3); display: none;"
                        onclick="executeBulkDeleteStaff()">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Modal Logic
            function openAddEmployeeModal() {
                const modal = document.getElementById('modal-add-employee');
                if (modal) {
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.querySelector('.inv-modal').style.transform = 'scale(1)';
                }
            }

            function closeEmployeeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.visibility = 'hidden';
                    modal.style.opacity = '0';
                    modal.querySelector('.inv-modal').style.transform = 'scale(0.95)';

                    // Optional: Reset form
                    const form = modal.querySelector('form');
                    if (form) form.reset();

                    // Reset image preview
                    const preview = document.getElementById('profile-image-preview');
                    const placeholder = document.getElementById('profile-image-placeholder');
                    if (preview && placeholder) {
                        preview.style.display = 'none';
                        preview.src = '';
                        placeholder.style.display = 'block';
                    }
                }
            }

            // Generic Modal Open/Close for Edit & Delete
            function openStaffModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.querySelector('.inv-modal').style.transform = 'scale(1)';
                }
            }

            function closeStaffModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.visibility = 'hidden';
                    modal.style.opacity = '0';
                    modal.querySelector('.inv-modal').style.transform = 'scale(0.95)';
                    const form = modal.querySelector('form');
                    if (form) form.reset();

                    // Reset delete modal state
                    if (modalId === 'modal-delete-staff') {
                        document.getElementById('delete-staff-form').style.display = 'inline';
                        document.getElementById('btn-bulk-delete-staff').style.display = 'none';
                        document.getElementById('delete-staff-text').textContent =
                            'Are you sure you want to delete this employee? This action cannot be undone.';
                    }
                }
            }

            // Edit Staff Modal
            function openEditStaffModal(staff) {
                document.getElementById('edit-employee-form').action = "/peoples/staff-user/" + staff.id;
                document.getElementById('edit-first-name').value = staff.first_name || '';
                document.getElementById('edit-last-name').value = staff.last_name || '';
                document.getElementById('edit-gender').value = staff.gender || '';
                document.getElementById('edit-birthdate').value = staff.birthdate ? staff.birthdate.split('T')[0] : '';
                document.getElementById('edit-role').value = staff.role || 'staff';
                document.getElementById('edit-position').value = staff.position || '';
                document.getElementById('edit-email').value = staff.email || '';
                document.getElementById('edit-phone').value = staff.phone_number || '';
                openStaffModal('modal-edit-employee');
            }

            // Delete Confirmation
            function confirmDeleteStaff(id) {
                document.getElementById('delete-staff-form').action = "/peoples/staff-user/" + id;
                openStaffModal('modal-delete-staff');
            }

            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('modal-add-employee');
                if (modal && e.target === modal) {
                    closeEmployeeModal('modal-add-employee');
                }
            });

            // Image Preview Logic
            function previewProfileImage(event) {
                const input = event.target;
                const preview = document.getElementById('profile-image-preview');
                const placeholder = document.getElementById('profile-image-placeholder');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            // --- Bulk Selection Logic ---
            const bulkActionsStaff = document.getElementById('bulk-actions');
            const selectedCountStaff = document.getElementById('selected-count');

            function updateBulkActionsStaff() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count > 0) {
                    bulkActionsStaff.style.display = 'flex';
                    selectedCountStaff.textContent = count;
                } else {
                    bulkActionsStaff.style.display = 'none';
                }
            }

            function initializeBulkListeners() {
                const selectAllStaff = document.getElementById('select-all');

                if (selectAllStaff) {
                    selectAllStaff.onchange = function() {
                        const isChecked = this.checked;
                        document.querySelectorAll('.item-checkbox').forEach(cb => {
                            cb.checked = isChecked;
                        });
                        updateBulkActionsStaff();
                    };
                }

                document.querySelectorAll('.item-checkbox').forEach(cb => {
                    cb.onchange = function() {
                        updateBulkActionsStaff();
                        const selectAll = document.getElementById('select-all');
                        if (selectAll) {
                            const allChecked = document.querySelectorAll('.item-checkbox:checked').length ===
                                document.querySelectorAll('.item-checkbox').length;
                            selectAll.checked = allChecked;
                        }
                    };
                });
            }

            // Initial bind
            initializeBulkListeners();

            // --- Real-time Search ---
            const searchInput = document.getElementById('search-input');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value;
                    const url = new URL(window.location.href);

                    if (query.length > 0) {
                        url.searchParams.set('search', query);
                        url.searchParams.delete('page'); // Reset to page 1
                    } else {
                        url.searchParams.delete('search');
                    }

                    window.history.pushState({}, '', url);

                    searchTimeout = setTimeout(() => {
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                // Replace the list view
                                const newListContent = doc.getElementById('view-list');
                                if (newListContent) {
                                    document.getElementById('view-list').innerHTML = newListContent
                                        .innerHTML;
                                }

                                // Replace the grid/activity view
                                const newActivityContent = doc.getElementById('view-activity');
                                if (newActivityContent) {
                                    document.getElementById('view-activity').innerHTML = newActivityContent
                                        .innerHTML;
                                }

                                // Re-initialize listeners
                                initializeBulkListeners();
                            })
                            .catch(err => console.error('Search error:', err));
                    }, 400); // 400ms debounce
                });
            }

            // --- Bulk Delete Functions ---
            function confirmBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count === 0) return;

                document.getElementById('delete-staff-form').style.display = 'none';
                document.getElementById('btn-bulk-delete-staff').style.display = 'inline-block';
                document.getElementById('delete-staff-text').textContent =
                    `Are you sure you want to delete ${count} selected employees? This action cannot be undone.`;

                openStaffModal('modal-delete-staff');
            }

            function executeBulkDeleteStaff() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.dataset.id);

                if (ids.length === 0) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]') ?
                    document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

                const btn = document.getElementById('btn-bulk-delete-staff');
                const originalText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Deleting...';

                fetch('/peoples/staff-user/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const text = await res.text();
                            throw new Error(text || res.statusText);
                        }
                        return res.json();
                    })
                    .then(data => {
                        closeStaffModal('modal-delete-staff');
                        if (data.success) {
                            showFlash(data.message || 'Employees deleted successfully', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showFlash(data.message || 'Error deleting employees', 'error');
                            btn.disabled = false;
                            btn.textContent = originalText;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        closeStaffModal('modal-delete-staff');
                        showFlash('An error occurred: ' + err.message, 'error');
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            }

            // Reset modal state when closing
            function resetDeleteModal() {
                document.getElementById('delete-staff-form').style.display = 'inline';
                document.getElementById('btn-bulk-delete-staff').style.display = 'none';
                document.getElementById('delete-staff-text').textContent =
                    'Are you sure you want to delete this employee? This action cannot be undone.';
            }

            // Preview image when uploading new profile photo
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('staff-profile-preview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (preview) {
                            preview.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </body>

    </html>
</x-app-layout>

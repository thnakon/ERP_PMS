<x-app-layout>
    <head>
        {{-- (... head content ... ) --}}
        {{-- (CSS/JS ถูกโหลดมาจาก app-layout แล้ว) --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    
    <div class="sr-container">

        {{-- [!!! REFACTORED HEADER !!!] --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / People / Recent < <a href="{{ route('peoples.staff-user') }}" style="color: #017aff">Staff-Users</a></p>
                <h2 class="sr-page-title">Recent (Log)</h2>
            </div>

            <div class="sr-header-right" style="margin-right: 10px">
                <button class="sr-icon-button" title="Filter">
                    <i class="fa-solid fa-filter"></i>
                </button>
                {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
                และใช้คลาสใหม่ sr-button-primary --}}
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add new recent</span>
                </button>
            </div>
        </div>

        {{-- [!!! NEW FILTER BAR !!!] --}}
{{-- (Filter bar design specifically for the Log page) --}}
<div class="people-filters-wrapper">
    <div class="people-filter-group">
        <label for="date-range-filter">
            <i class="fa-solid fa-calendar-days"></i> Date Range
        </label>
        <select id="date-range-filter" class="people-select">
            <option value="today" selected>Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="this_week">This Week</option>
            <option value="custom">Custom Range</option>
        </select>
    </div>

    <div class="people-filter-group">
        <label for="user-filter">
            <i class="fa-solid fa-user"></i> User
        </label>
        <select id="user-filter" class="people-select">
            <option value="all">All Users</option>
            <option value="admin">Pharmacist Apichat</option>
            <option value="pharmacist_sr">Pharmacist Sureeporn</option>
            <option value="staff_nv">Staff A (Nattawut)</option>
        </select>
    </div>

    <div class="people-filter-group">
        <label for="action-filter">
            <i class="fa-solid fa-bolt"></i> Action
        </label>
        <select id="action-filter" class="people-select">
            <option value="all">All Actions</option>
            <option value="create">Create</option>
            <option value="update">Update</option>
            <option value="delete">Delete/Deactivate</option>
            <option value="view">View</option>
        </select>
    </div>

    <button class="people-button-secondary">
        <i class="fa-solid fa-filter"></i>
        <span>Apply Filters</span>
    </button>
</div>

        {{-- [!!! LOG TABLE CONTENT !!!] --}}
        <div class="people-content-area">
            <div class="people-list-container">
                {{-- ส่วนหัวตาราง --}}
                <div class="people-list-row header-row log-header">
                    <div class="col-log-time">Timestamp</div>
                    <div class="col-log-user">User</div>
                    <div class="col-log-action">Action</div>
                    <div class="col-log-details">Details</div>
                </div>

                {{-- Log Row 1 --}}
                <div class="people-list-row log-row">
                    <div class="col-log-time" data-label="Timestamp">15/11/2025 11:30:15</div>
                    <div class="col-log-user" data-label="User">
                        <div class="sr-user-info">
                            <span class="sr-user-name">ภญ. สุรีพร</span>
                            <span class="sr-user-email">Pharmacist</span>
                        </div>
                    </div>
                    <div class="col-log-action" data-label="Action">
                        <span class="log-action-tag update">UPDATED_STOCK</span>
                    </div>
                    <div class="col-log-details" data-label="Details">
                        แก้ไขสต็อก Paracetamol (10mg) จาก 100 เป็น 90
                    </div>
                </div>

                {{-- Log Row 1 --}}
                <div class="people-list-row log-row">
                    <div class="col-log-time" data-label="Timestamp">15/11/2025 11:30:15</div>
                    <div class="col-log-user" data-label="User">
                        <div class="sr-user-info">
                            <span class="sr-user-name">ภญ. สุรีพร</span>
                            <span class="sr-user-email">Pharmacist</span>
                        </div>
                    </div>
                    <div class="col-log-action" data-label="Action">
                        <span class="log-action-tag update">UPDATED_STOCK</span>
                    </div>
                    <div class="col-log-details" data-label="Details">
                        แก้ไขสต็อก Paracetamol (10mg) จาก 100 เป็น 90
                    </div>
                </div>

                {{-- Log Row 2 --}}
                <div class="people-list-row log-row">
                    <div class="col-log-time" data-label="Timestamp">15/11/2025 11:29:00</div>
                    <div class="col-log-user" data-label="User">
                        <div class="sr-user-info">
                            <span class="sr-user-name">ภญ. สุรีพร</span>
                            <span class="sr-user-email">Pharmacist</span>
                        </div>
                    </div>
                    <div class="col-log-action" data-label="Action">
                        <span class="log-action-tag view">VIEWED_PATIENT</span>
                    </div>
                    <div class="col-log-details" data-label="Details">
                        ดูข้อมูลผู้ป่วย ID: 105 (สมชาย ใจดี)
                    </div>
                </div>

                {{-- Log Row 3 --}}
                <div class="people-list-row log-row">
                    <div class="col-log-time" data-label="Timestamp">15/11/2025 11:28:10</div>
                    <div class="col-log-user" data-label="User">
                         <div class="sr-user-info">
                            <span class="sr-user-name">พนักงาน ก. (ณัฐวุฒิ)</span>
                            <span class="sr-user-email">Staff</span>
                        </div>
                    </div>
                    <div class="col-log-action" data-label="Action">
                        <span class="log-action-tag create">CREATED_ORDER</span>
                    </div>
                    <div class="col-log-details" data-label="Details">
                        สร้างบิล #2025-00520 (ยอด ฿288.46)
                    </div>
                </div>

                {{-- Log Row 4 --}}
                <div class="people-list-row log-row">
                    <div class="col-log-time" data-label="Timestamp">15/11/2025 11:25:02</div>
                    <div class="col-log-user" data-label="User">
                         <div class="sr-user-info">
                            <span class="sr-user-name">ภก. อภิชาติ</span>
                            <span class="sr-user-email">Admin</span>
                        </div>
                    </div>
                    <div class="col-log-action" data-label="Action">
                        <span class="log-action-tag delete">DEACTIVATED_USER</span>
                    </div>
                    <div class="col-log-details" data-label="Details">
                        ปิดใช้งานผู้ใช้ ID: 4 (อดีตพนักงาน ข.)
                    </div>
                </div>

            </div>
        </div> {{-- end .people-content-area --}}

        {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-4 of 120</span>
            <div class="pagination-controls">
                <button class="pagination-btn disabled" aria-label="Previous Page">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="pagination-btn" aria-label="Next Page">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    
    </div> {{-- end .sr-container --}}
</x-app-layout>
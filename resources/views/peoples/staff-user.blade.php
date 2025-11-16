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
                <p class="sr-breadcrumb">Dashboard / People / < <a href="{{ route('peoples.patients-customer') }}" style="color: #017aff">Patients-Customers</a> / Staff-Users > <a href="{{ route('peoples.recent') }}" style="color: #017aff">Recent</a></p>
                <h2 class="sr-page-title">Staff | Users (4)</h2>
            </div>

            <div class="people-view-toggle">
                <button class="people-toggle-btn active" data-target="list-view">
                    <i class="fa-solid fa-list-ul"></i>
                    <span>List</span>
                </button>
                <button class="people-toggle-btn" data-target="activity-view">
                    <i class="fa-solid fa-grip"></i>
                    <span>Activity</span>
                </button>
            </div>

            <div class="sr-header-right" style="margin-right: 10px">
                <button class="sr-icon-button" title="Filter">
                    <i class="fa-solid fa-filter"></i>
                </button>
                {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
                และใช้คลาสใหม่ sr-button-primary --}}
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add new staff</span>
                </button>
            </div>
        </div>

        <div class="people-content-area">

            {{-- [!!! VIEW 1: LIST VIEW (Staff) !!!] --}}
            {{-- [!!! ID UPDATED !!!] --}}
            <div id="staff-list-view" class="people-view-wrapper">
                
                <div class="people-list-container">
                    {{-- ส่วนหัวตาราง --}}
                    <div class="people-list-row header-row">
                        <div class="col-name">Name</div>
                        <div class="col-email">Email / Username</div>
                        <div class="col-role">Role</div>
                        <div class="col-status">Status</div>
                        <div class="col-actions"></div>
                    </div>

                    {{-- Data Row 1 (Admin) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=AP" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">ภก. อภิชาติ</span>
                                <span class="sr-user-email">admin@oboun.com</span>
                            </div>
                        </div>
                        <div class="col-email" data-label="Email">admin@oboun.com</div>
                        <div class="col-role" data-label="Role">
                            <span class="people-role-badge admin">Admin</span>
                        </div>
                        <div class="col-status" data-label="Status">
                            <span class="people-status-badge active">Active</span>
                        </div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>
                    {{-- Data Row 1 (Admin) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=AP" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">ภก. อภิชาติ</span>
                                <span class="sr-user-email">admin@oboun.com</span>
                            </div>
                        </div>
                        <div class="col-email" data-label="Email">admin@oboun.com</div>
                        <div class="col-role" data-label="Role">
                            <span class="people-role-badge admin">Admin</span>
                        </div>
                        <div class="col-status" data-label="Status">
                            <span class="people-status-badge active">Active</span>
                        </div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>
                    
                    {{-- Data Row 2 (Pharmacist) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=SR" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">ภญ. สุรีพร</span>
                                <span class="sr-user-email">sureeporn@oboun.com</span>
                            </div>
                        </div>
                        <div class="col-email" data-label="Email">sureeporn@oboun.com</div>
                        <div class="col-role" data-label="Role">
                            <span class="people-role-badge pharmacist">Pharmacist</span>
                        </div>
                        <div class="col-status" data-label="Status">
                            <span class="people-status-badge active">Active</span>
                        </div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>

                    {{-- Data Row 3 (Staff) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=NV" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">พนักงาน ก. (ณัฐวุฒิ)</span>
                                <span class="sr-user-email">staff01@oboun.com</span>
                            </div>
                        </div>
                        <div class="col-email" data-label="Email">staff01@oboun.com</div>
                        <div class="col-role" data-label="Role">
                            <span class="people-role-badge staff">Staff</span>
                        </div>
                        <div class="col-status" data-label="Status">
                            <span class="people-status-badge active">Active</span>
                        </div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>

                    {{-- Data Row 4 (Inactive) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=AD" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">อดีตพนักงาน ข.</span>
                                <span class="sr-user-email">staff02@oboun.com</span>
                            </div>
                        </div>
                        <div class="col-email" data-label="Email">staff02@oboun.com</div>
                        <div class="col-role" data-label="Role">
                            <span class="people-role-badge staff">Staff</span>
                        </div>
                        <div class="col-status" data-label="Status">
                            <span class="people-status-badge inactive">Inactive</span>
                        </div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>

                </div>
            </div> {{-- end #staff-list-view --}}


            {{-- [!!! VIEW 2: ACTIVITY VIEW (Staff) !!!] --}}
            {{-- [!!! ID UPDATED !!!] --}}
            <div id="staff-activity-view" class="people-view-wrapper">
                <div class="people-grid-view">
                    
                    {{-- Card 1 (Admin) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=AP" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">ภก. อภิชาติ</h4>
                        <p class="activity-title">Admin / Owner</p>
                        <span class="people-badge active">Active</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">520</span><span class="stat-label">Sales</span></div>
                            <div class="stat-item"><span class="stat-number">210</span><span class="stat-label">Logs</span></div>
                            <div class="stat-item"><span class="stat-number">2</span><span class="stat-label">Tasks</span></div>
                        </div>
                    </div>

                    {{-- Card 2 (Pharmacist) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=SR" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">ภญ. สุรีพร</h4>
                        <p class="activity-title">Pharmacist</p>
                        <span class="people-badge active">Active</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">180</span><span class="stat-label">Sales</span></div>
                            <div class="stat-item"><span class="stat-number">95</span><span class="stat-label">Logs</span></div>
                            <div class="stat-item"><span class="stat-number">5</span><span class="stat-label">Tasks</span></div>
                        </div>
                    </div>

                    {{-- Card 3 (Staff) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=NV" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">พนักงาน ก. (ณัฐวุฒิ)</h4>
                        <p class="activity-title">Staff</p>
                        <span class="people-badge active">Active</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">165</span><span class="stat-label">Sales</span></div>
                            <div class="stat-item"><span class="stat-number">70</span><span class="stat-label">Logs</span></div>
                            <div class="stat-item"><span class="stat-number">1</span><span class="stat-label">Tasks</span></div>
                        </div>
                    </div>

                    {{-- Card 4 (Inactive) --}}
                    <div class="people-activity-card is-inactive">
                         <div class="inactive-overlay"><span>Z</span><span>z</span><span>z</span></div>
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=AD" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">อดีตพนักงาน ข.</h4>
                        <p class="activity-title">Staff</p>
                        <span class="people-badge inactive">Inactive</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">...</span><span class="stat-label">Sales</span></div>
                            <div class="stat-item"><span class="stat-number">...</span><span class="stat-label">Logs</span></div>
                            <div class="stat-item"><span class="stat-number">...</span><span class="stat-label">Tasks</span></div>
                        </div>
                    </div>

                </div>
            </div> {{-- end #staff-activity-view --}}

        </div> {{-- end .people-content-area --}}

        {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-4 of 4</span>
            <div class="pagination-controls">
                <button class="pagination-btn disabled" aria-label="Previous Page">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="pagination-btn disabled" aria-label="Next Page">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    
    </div> {{-- end .sr-container --}}
</x-app-layout>
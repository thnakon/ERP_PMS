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
                <p class="sr-breadcrumb">Dashboard / People / Patients-Customers > <a href="{{ route('peoples.staff-user') }}" style="color: #017aff">Staff-Users</a> </p>
                <h2 class="sr-page-title">Patients | Customer (4)</h2>
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
                    <span>Add new customer</span>
                </button>
            </div>
        </div>

        <div class="people-content-area">

            {{-- [!!! VIEW 1: LIST VIEW !!!] --}}
            {{-- [!!! ID UPDATED !!!] --}}
            <div id="patient-list-view" class="people-view-wrapper">
                
                <div class="people-list-container">
                    {{-- ส่วนหัวตาราง --}}
                    <div class="people-list-row header-row">
                        <div class="col-name">Name</div>
                        <div class="col-phone">Phone</div>
                        <div class="col-gender">Gender</div>
                        <div class="col-age">Age</div>
                        <div class="col-allergies">Allergies</div>
                        <div class="col-visit">Last Visit</div>
                        <div class="col-actions"></div>
                    </div>

                    {{-- Data Row 1 (มีแพ้ยา) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=SS" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">สมชาย ใจดี</span>
                                <span class="sr-user-email">somchai.j@example.com</span>
                            </div>
                        </div>
                        <div class="col-phone" data-label="Phone">081-234-5678</div>
                        <div class="col-gender" data-label="Gender">Male</div>
                        <div class="col-age" data-label="Age">42</div>
                        <div class="col-allergies" data-label="Allergies">
                            <span class="sr-allergy-warning">
                                <i class="fa-solid fa-triangle-exclamation"></i> Penicillin
                            </span>
                        </div>
                        <div class="col-visit" data-label="Last Visit">14/11/2025</div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="View/Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>
                    {{-- Data Row 2 (ไม่มีแพ้ยา) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=AR" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">อารยา รักสงบ</span>
                                <span class="sr-user-email">araya.r@example.com</span>
                            </div>
                        </div>
                        <div class="col-phone" data-label="Phone">092-111-2233</div>
                        <div class="col-gender" data-label="Gender">Female</div>
                        <div class="col-age" data-label="Age">28</div>
                        <div class="col-allergies" data-label="Allergies">
                            <span class="sr-allergy-none">None</span>
                        </div>
                        <div class="col-visit" data-label="Last Visit">12/11/2025</div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="View/Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>
                    {{-- Data Row 3 (ไม่มีแพ้ยา) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=WP" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">วิชัย ประเสริฐ</span>
                                <span class="sr-user-email">wichai.p@example.com</span>
                            </div>
                        </div>
                        <div class="col-phone" data-label="Phone">088-777-6655</div>
                        <div class="col-gender" data-label="Gender">Male</div>
                        <div class="col-age" data-label="Age">55</div>
                        <div class="col-allergies" data-label="Allergies">
                            <span class="sr-allergy-none">None</span>
                        </div>
                        <div class="col-visit" data-label="Last Visit">10/11/2025</div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="View/Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>
                    {{-- Data Row 4 (มีแพ้ยา) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=KT" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">กานดา งามตา</span>
                                <span class="sr-user-email">kanda.n@example.com</span>
                            </div>
                        </div>
                        <div class="col-phone" data-label="Phone">085-555-1212</div>
                        <div class="col-gender" data-label="Gender">Female</div>
                        <div class="col-age" data-label="Age">31</div>
                        <div class="col-allergies" data-label="Allergies">
                             <span class="sr-allergy-warning">
                                <i class="fa-solid fa-triangle-exclamation"></i> Sulfa
                            </span>
                        </div>
                        <div class="col-visit" data-label="Last Visit">09/11/2025</div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="View/Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>
                     {{-- Data Row 5 (ไม่มีแพ้ยา) --}}
                    <div class="people-list-row">
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=PP" alt="Avatar" class="sr-avatar">
                            <div class="sr-user-info">
                                <span class="sr-user-name">ประวิทย์ สุขใจ</span>
                                <span class="sr-user-email">prawit.s@example.com</span>
                            </div>
                        </div>
                        <div class="col-phone" data-label="Phone">061-222-3344</div>
                        <div class="col-gender" data-label="Gender">Male</div>
                        <div class="col-age" data-label="Age">60</div>
                        <div class="col-allergies" data-label="Allergies">
                            <span class="sr-allergy-none">None</span>
                        </div>
                        <div class="col-visit" data-label="Last Visit">08/11/2025</div>
                        <div class="col-actions">
                            <button class="sr-action-btn" title="View/Edit"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>

                </div>
            </div> {{-- end #patient-list-view --}}


            {{-- [!!! VIEW 2: ACTIVITY VIEW !!!] --}}
            {{-- [!!! ID UPDATED !!!] --}}
            <div id="patient-activity-view" class="people-view-wrapper">
                <div class="people-grid-view">
                    
                    {{-- Card 1 (Active) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=SS" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">สมชาย ใจดี</h4>
                        <p class="activity-title">Customer</p>
                        <span class="people-badge Member">Member</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">12</span><span class="stat-label">Orders</span></div>
                            <div class="stat-item"><span class="stat-number">1</span><span class="stat-label">Pending</span></div>
                            <div class="stat-item"><span class="stat-number">2</span><span class="stat-label">Notes</span></div>
                        </div>
                    </div>
                    {{-- Card 2 (Active) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=AR" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">อารยา รักสงบ</h4>
                        <p class="activity-title">Customer</p>
                        <span class="people-badge New">New</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">1</span><span class="stat-label">Orders</span></div>
                            <div class="stat-item"><span class="stat-number">0</span><span class="stat-label">Pending</span></div>
                            <div class="stat-item"><span class="stat-number">0</span><span class="stat-label">Notes</span></div>
                        </div>
                    </div>
                    {{-- Card 3 (Inactive) --}}
                    <div class="people-activity-card is-inactive">
                        <div class="inactive-overlay"><span>Z</span><span>z</span><span>z</span></div>
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=WP" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">วิชัย ประเสริฐ</h4>
                        <p class="activity-title">Customer</p>
                        <span class="people-badge inactive">Inactive</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">5</span><span class="stat-label">Orders</span></div>
                            <div class="stat-item"><span class="stat-number">0</span><span class="stat-label">Pending</span></div>
                            <div class="stat-item"><span class="stat-number">1</span><span class="stat-label">Notes</span></div>
                        </div>
                    </div>
                    {{-- Card 4 (Active) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=KT" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">กานดา งามตา</h4>
                        <p class="activity-title">Customer</p>
                        <span class="people-badge Member">Member</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">8</span><span class="stat-label">Orders</span></div>
                            <div class="stat-item"><span class="stat-number">0</span><span class="stat-label">Pending</span></div>
                            <div class="stat-item"><span class="stat-number">1</span><span class="stat-label">Notes</span></div>
                        </div>
                    </div>
                     {{-- Card 5 (Active) --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=PP" alt="Avatar" class="activity-avatar">
                        <h4 class="activity-name">ประวิทย์ สุขใจ</h4>
                        <p class="activity-title">Customer</p>
                        <span class="people-badge New">New</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">2</span><span class="stat-label">Orders</span></div>
                            <div class="stat-item"><span class="stat-number">1</span><span class="stat-label">Pending</span></div>
                            <div class="stat-item"><span class="stat-number">0</span><span class="stat-label">Notes</span></div>
                        </div>
                    </div>

                </div>
            </div> {{-- end #patient-activity-view --}}

        </div> {{-- end .people-content-area --}}

        {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-8 of 28</span>
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
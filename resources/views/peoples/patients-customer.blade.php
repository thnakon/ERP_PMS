<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/people.css') }}">
    </head>
    
    <div class="sr-container">

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
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add new customer</span>
                </button>
            </div>
        </div>

        <div class="people-content-area">

            {{-- [!!! VIEW 1: LIST VIEW !!!] --}}
            <div id="patient-list-view" class="people-view-wrapper">
                <!-- Controls Row -->
            <div class="inv-filters-wrapper">
                {{-- Bulk Actions --}}
<div id="bulk-actions" 
    style="
        display: none;
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 16px;
    ">

    <!-- Selected Counter -->
    <span style="font-weight: 600; color: #6e6e73; font-size: 0.9rem;">
        Selected: <span id="selected-count">0</span>
    </span>

    <!-- Print Barcode -->
    <button 
        style="
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 32px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1d1d1f;
            box-shadow: 0 2px 5px rgba(0,0,0,0.06);
        ">
        <i class="fa-solid fa-barcode"></i> Print Barcode
    </button>

    <!-- Delete -->
    <button 
        style="
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 32px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #ff3b30;
            box-shadow: 0 2px 5px rgba(0,0,0,0.06);
        ">
        <i class="fa-solid fa-trash"></i> Delete
    </button>

</div>


            </div>

                <div class="people-list-container">
                    {{-- ส่วนหัวตาราง --}}
                    <div class="people-list-row header-row">
                        <div class="people-checkbox" id="select-all"></div>
                        <div class="col-name">Name</div>
                        <div class="col-phone">Phone</div>
                        <div class="col-gender">Gender</div>
                        <div class="col-age">Age</div>
                        <div class="col-allergies">Allergies</div>
                        <div class="col-visit">Last Visit</div>
                        <div class="col-actions"></div>
                    </div>

                    {{-- Data Row 1 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
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
                    {{-- Data Row 2 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
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
                    {{-- Data Row 3 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
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
                    {{-- Data Row 4 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
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
                     {{-- Data Row 5 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
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
            </div>

            {{-- [!!! VIEW 2: ACTIVITY VIEW !!!] --}}
            <div id="patient-activity-view" class="people-view-wrapper">
                <div class="people-grid-view">
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
                     <!-- More activity cards... -->
                </div>
            </div>

        </div>

        <div class="people-pagination">
            <span class="pagination-text">1-8 of 28</span>
            <div class="pagination-controls">
                <button class="pagination-btn disabled"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="pagination-btn"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    
    </div>
    <script src="{{ asset('js/people.js') }}"></script>
</x-app-layout>
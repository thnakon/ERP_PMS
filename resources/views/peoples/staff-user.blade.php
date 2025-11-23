<x-app-layout>

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/people.css') }}">
    </head>

    <div class="sr-container">
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / People / < <a href="{{ route('peoples.patients-customer') }}"
                        style="color: #017aff">Patients-Customers</a> / Staff-Users > <a
                            href="{{ route('peoples.recent') }}" style="color: #017aff">Recent</a></p>
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
                <button class="sr-button-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add new staff</span>
                </button>
            </div>
        </div>

        <div class="people-content-area">

            {{-- [!!! VIEW 1: LIST VIEW !!!] --}}
            <div id="staff-list-view" class="people-view-wrapper">
                <div class="inv-filters-wrapper">
                    {{-- Bulk Actions --}}
                    {{-- Bulk Actions --}}
                    <div id="bulk-actions"
                        style="
        display: none;
        align-items: center;
        gap: 16px;

        
        border-radius: 32px;
     
        margin-left: auto;
  
    ">

                        <!-- Selected Counter -->
                        <span style="font-weight: 600; color: #6e6e73; font-size: 0.9rem;">
                            Selected: <span id="selected-count">0</span>
                        </span>

                        <!-- Deactivate Button -->
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
                            <i class="fa-solid fa-user-slash"></i>
                            Deactivate Selected
                        </button>

                    </div>

                </div>

                <div class="people-list-container">
                    <div class="people-list-row header-row">
                        <div class="people-checkbox" id="select-all"></div>
                        <div class="col-name">Name</div>
                        <div class="col-email">Email / Username</div>
                        <div class="col-role">Role</div>
                        <div class="col-status">Status</div>
                        <div class="col-actions">Actions</div>
                    </div>

                    {{-- Data Row 1 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=AP" alt="Avatar"
                                class="sr-avatar">
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
                            <div class="sr-action-group">
                                <button class="sr-btn-icon view" title="View Details"><i
                                        class="fa-solid fa-eye"></i></button>
                                <button class="sr-btn-icon edit" title="Edit Staff"><i
                                        class="fa-solid fa-pen"></i></button>
                                <button class="sr-btn-icon delete" title="Delete Staff"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- Data Row 2 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=SR" alt="Avatar"
                                class="sr-avatar">
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
                            <div class="sr-action-group">
                                <button class="sr-btn-icon view" title="View Details"><i
                                        class="fa-solid fa-eye"></i></button>
                                <button class="sr-btn-icon edit" title="Edit Staff"><i
                                        class="fa-solid fa-pen"></i></button>
                                <button class="sr-btn-icon delete" title="Delete Staff"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- Data Row 3 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=NV" alt="Avatar"
                                class="sr-avatar">
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
                            <div class="sr-action-group">
                                <button class="sr-btn-icon view" title="View Details"><i
                                        class="fa-solid fa-eye"></i></button>
                                <button class="sr-btn-icon edit" title="Edit Staff"><i
                                        class="fa-solid fa-pen"></i></button>
                                <button class="sr-btn-icon delete" title="Delete Staff"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- Data Row 4 --}}
                    <div class="people-list-row">
                        <div class="people-checkbox item-checkbox"></div>
                        <div class="col-name" data-label="Name">
                            <img src="https://placehold.co/40x40/E2E8F0/4A5568?text=AD" alt="Avatar"
                                class="sr-avatar">
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
                            <button class="sr-action-btn" title="Edit"><i
                                    class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- [!!! VIEW 2: ACTIVITY VIEW !!!] --}}
            <div id="staff-activity-view" class="people-view-wrapper">
                <div class="people-grid-view">
                    {{-- Activity cards... --}}
                    <div class="people-activity-card">
                        <img src="https://placehold.co/80x80/E2E8F0/4A5568?text=AP" alt="Avatar"
                            class="activity-avatar">
                        <h4 class="activity-name">ภก. อภิชาติ</h4>
                        <p class="activity-title">Admin / Owner</p>
                        <span class="people-badge active">Active</span>
                        <div class="people-card-stats">
                            <div class="stat-item"><span class="stat-number">520</span><span
                                    class="stat-label">Sales</span></div>
                            <div class="stat-item"><span class="stat-number">210</span><span
                                    class="stat-label">Logs</span></div>
                            <div class="stat-item"><span class="stat-number">2</span><span
                                    class="stat-label">Tasks</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="people-pagination">
            <span class="pagination-text">1-4 of 4</span>
            <div class="pagination-controls">
                <button class="pagination-btn disabled"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="pagination-btn disabled"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

    </div>
    {{-- [!!! NEW: Add Staff Modal (Updated Fields) !!!] --}}
    <div id="modal-add-staff" class="sr-modal-overlay">
        <div class="sr-modal">
            <div class="sr-modal-header">
                <h3 class="sr-modal-title">Add New Staff</h3>
                <button class="sr-modal-close" data-close="modal-add-staff"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="sr-modal-body">
                <form id="form-add-staff">
                    <div class="sr-form-row">
                        <div class="sr-form-group">
                            <label class="sr-form-label">Full Name <span style="color:red">*</span></label>
                            <input type="text" class="sr-form-input" placeholder="e.g. Somchai Jaidee" required>
                        </div>
                        <div class="sr-form-group">
                            <label class="sr-form-label">Email / Username <span style="color:red">*</span></label>
                            <input type="email" class="sr-form-input" placeholder="e.g. staff@oboun.com" required>
                        </div>
                    </div>

                    <div class="sr-form-row">
                        <div class="sr-form-group">
                            <label class="sr-form-label">Phone Number</label>
                            <input type="tel" class="sr-form-input" placeholder="e.g. 089-999-8888">
                        </div>
                        <div class="sr-form-group">
                            <label class="sr-form-label">Position / Job Title</label>
                            <input type="text" class="sr-form-input" placeholder="e.g. General Staff">
                        </div>
                    </div>

                    <div class="sr-form-row">
                        <div class="sr-form-group">
                            <label class="sr-form-label">Role</label>
                            <select class="sr-form-select">
                                <option value="staff">Staff</option>
                                <option value="pharmacist">Pharmacist</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="sr-form-group">
                            <label class="sr-form-label">Start Date</label>
                            <input type="date" class="sr-form-input">
                        </div>
                    </div>

                    <div class="sr-form-group">
                        <label class="sr-form-label">Salary (Optional)</label>
                        <input type="number" class="sr-form-input" placeholder="0.00">
                    </div>

                </form>
            </div>
            <div class="sr-modal-footer">
                <button class="sr-btn-secondary" data-close="modal-add-staff">Cancel</button>
                <button class="sr-button-primary">Save Staff</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/people.js') }}"></script>
</x-app-layout>

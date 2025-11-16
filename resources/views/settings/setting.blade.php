{{-- Use your main layout (app.blade.php) --}}
<x-app-layout>

    {{-- [!!! ADJUSTED !!!] --}}
    {{-- We won't use the old "header" x-slot, but create our own header below --}}

    {{-- Main content for the Settings page --}}
    <div class="settings-layout">

        <!-- [!!! NEW HEADER !!!] New page header design -->
        <div class="settings-page-header">
            <div class="header-left">
                <p class="breadcrumb">Dashboard / Settings</p>
                <h2 class="settings-page-title">Settings</h2>
            </div>
            <div class="header-right">
                <div class="date-picker-box">
                    <i class="fa-solid fa-calendar-day"></i>
                    {{-- [!!! ADJUSTED !!!] Changed from Static text to PHP Blade --}}
                    <span>{{ now()->startOfMonth()->format('M d, Y') . ' - ' . now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
        <div class="settings-tabs-wrapper">
                <!-- 1. Tab Menu (Horizontal) -->
                <nav class="settings-tabs-nav">
                    <span class="active-pill-background"></span>
                    <a href="#" class="settings-tab-item active" data-tab="tab-general">
                        <i class="fa-solid fa-building"></i>
                        <span>General | Organization</span>
                    </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-users">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Users & Permissions</span>
                    </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-pos">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>Sales (POS)</span>
                    </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-inventory">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Inventory & Products</span>
                </a>
                <a href="#" class="settings-tab-item" data-tab="tab-pharmacy">
                    <i class="fa-solid fa-pills"></i>
                    <span>Pharmacy & Patients</span>
                </a>
                    <a href="#" class="settings-tab-item" data-tab="tab-system">
                        <i class="fa-solid fa-bell"></i>
                        <span>System & Notifications</span>
                    </a>
                </nav>
            </div>  
        <div class="settings-content-container">
      
                <!-- 2. Content Panes -->
                <div class="settings-content-pane">

                    <!-- ======================= -->
                    <!--   Tab 1: General / Organization   -->
                    <!-- ======================= -->
                    <section id="tab-general" class="settings-pane active">
                        
                        <div class="settings-card">
                            <h3 class="card-title">Store Details</h3>
                            <p class="card-description">This information will appear on your receipts and documents.</p>

                            <div class="form-grid-2-col">
                                <div class="form-group">
                                    <label for="store_name" class="form-label">Store Name</label>
                                    <input type="text" id="store_name" class="form-input" value="Oboun ERP">
                                </div>
                                <div class="form-group">
                                    <label for="store_phone" class="form-label">Phone Number</label>
                                    <input type="text" id="store_phone" class="form-input"
                                        placeholder="e.g., 081-234-5678">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="store_address" class="form-label">Address</label>
                                <textarea id="store_address" class="form-textarea" rows="3"
                                    placeholder="Street, District, Sub-district, Province, Postal Code"></textarea>
                            </div>
                            <div class="form-grid-2-col">
                                <div class="form-group">
                                    <label for="tax_id" class="form-label">Tax ID</label>
                                    <input type="text" id="tax_id" class="form-input"
                                        placeholder="Enter 13-digit number">
                                </div>
                                <div class="form-group">
                                    <label for="license_id" class="form-label">Pharmacy License No.</label>
                                    <input type="text" id="license_id" class="form-input" placeholder="For pharmacies">
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h3 class="card-title">Logo</h3>
                            <p class="card-description">Upload your store's logo for the Login page and receipts (transparent .png recommended)</p>
                            <div class="form-group-upload">
                                <div class="logo-preview-box">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                                <input type="file" id="logo_upload" class="form-input-file" hidden>
                                <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('logo_upload').click();">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    Upload Logo
                                </button>
                            </div>
                        </div>

                        <!-- [!!! CARD 3 - ADJUSTED !!!] Standard Card -->
                        <div class="settings-card">
                            <h3 class="card-title">Standards & Compliance</h3>
                            <p class="card-description">Configure connections and enable modes related to pharmacy standards and laws.</p>

                            <!-- 1. Toggles -->
                            <h4 class="form-section-title">System Settings</h4>
                            <div class="form-toggle-list">
                                <!-- PDPA -->
                                <div class="form-toggle-item">
                                    <span>
                                        <i class="fa-solid fa-shield-halved" style="color: #0071e3;"></i>
                                        <b>PDPA Mode:</b> Enable patient data privacy (masking)
                                    </span>
                                    <label class="form-toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <!-- TMT -->
                                <div class="form-toggle-item">
                                    <span>
                                        <i class="fa-solid fa-database" style="color: #34c759;"></i>
                                        <b>TMT Integration:</b> Enable TMT drug code referencing
                                    </span>
                                    <label class="form-toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- [!!! NEW SECTION !!!] -->
                            <!-- 2. Compliance Cards -->
                            <h4 class="form-section-title" style="margin-top: 24px;">Compliance Info</h4>
                            <p class="card-description" style="margin-top: -12px; margin-bottom: 16px;">
                                This software is developed in consideration of the following standards:
                            </p>
                            
                            <!-- [REUSE] Use existing .form-grid-2-col -->
                            <div class="form-grid-2-col">
                                <!-- GPP Card -->
                                <div class="compliance-card">
                                    <div class="compliance-icon-wrapper" style="background-color: #e6f6e9;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShMbCDzNr7Me_KYOXQh-vb1mIpsiVGPAyrr1qORTKzWh6tB60O4LLbgbJ9LJnJm7k9cG4&usqp=CAU" style="border-radius: 60%; object-fit: cover;">
                                    </div>
                                    <h5 class="compliance-title">GPP (Good Pharmacy Practice)</h5>
                                    <p class="compliance-description">
                                        Supports operations according to GPP standards (e.g., expiry tracking, inventory management).
                                    </p>
                                </div>
                                
                                <!-- ISO Card -->
                                <div class="compliance-card">
                                    <div class="compliance-icon-wrapper" style="background-color: #e5f1ff;">
                                        <i class="fa-solid fa-award" style="color: #0071e3;"></i>
                                    </div>
                                    <h5 class="compliance-title">ISO/IEC 29110</h5>
                                    <p class="compliance-description">
                                        The software development process adheres to software engineering standards.
                                    </p>
                                </div>
                            </div>
                            <!-- [!!! END NEW SECTION !!!] -->

                        </div>


                        <div class="form-actions">
                            <button class="btn btn-primary">Save Changes</button>
                        </div>
                    </section>

                    <!-- ======================= -->
                    <!--  Tab 2: Users & Permissions  -->
                    <!-- ======================= -->
                    <section id="tab-users" class="settings-pane">

                    <div class="settings-card">
                        <h3 class="card-title">Roles Management</h3>
                        <p class="card-description">Create staff roles to define access permissions for different sections.</p>

                        <div class="roles-list">
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Admin</strong>
                                    <small>Full access, including settings and reports</small>
                                </div>
                                <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Pharmacist</strong>
                                    <small>Sell, manage stock, receive goods (manage medicine)</small>
                                </div>
                                <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Staff</strong>
                                    <small>Can only sell (POS) and view stock</small>
                                </div>
                                <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                            </div>
                        </div>
                        <button class="btn btn-secondary" style="margin-top: 1rem;">
                            <i class="fa-solid fa-plus"></i> Add New Role
                        </button>
                    </div>

                    <div class="settings-card">
                        
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">User Management</h3>
                                <p class="card-description">Add, remove, and edit user accounts.</p>
                            </div>
                            <div>
                                <button class="btn btn-primary">
                                    <i class="fa-solid fa-user-plus"></i>
                                    Add New User
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive-wrapper">
                            <table class="settings-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">A</div>
                                                <div class="user-name-email">
                                                    <strong>Admin User</strong>
                                                    <small>admin@example.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role" style="background-color: #ffe6e6; color: #d90000;">Admin</span>
                                        </td>
                                        <td>
                                            <span class="badge-status active">Active</span>
                                        </td>
                                        <td>
                                            <button class="btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">P</div>
                                                <div class="user-name-email">
                                                    <strong>Pharma One</strong>
                                                    <small>pharma1@example.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role" style="background-color: #e6f6e9; color: #008a1e;">Pharmacist</span>
                                        </td>
                                        <td>
                                            <span class="badge-status active">Active</span>
                                        </td>
                                        <td>
                                            <button class="btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">S</div>
                                                <div class="user-name-email">
                                                    <strong>Staff Member</strong>
                                                    <small>staff@example.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role">Staff</span>
                                        </td>
                                        <td>
                                            <span class="badge-status inactive">Inactive</span>
                                        </td>
                                        <td>
                                            <button class="btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                    <!-- ======================= -->
                    <!--     Tab 3: Sales (POS)    -->
                    <!-- ======================= -->
                    <section id="tab-pos" class="settings-pane">

                    <div class="settings-card">
                         <h3 class="card-title">POS Details</h3>
                            <p class="card-description">Your POS sales information</p>
                        <h3 class="card-title">Tax</h3>
                        <div class="form-group">
                            <label for="vat_rate" class="form-label">VAT Rate</label>
                            <div class="input-with-suffix">
                                <input type="number" id="vat_rate" class="form-input" value="7">
                                <span>%</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Price Display</label>
                            <div class="form-radio-group">
                                <label class="form-radio-label">
                                    <input type="radio" name="price_display" value="inclusive" checked>
                                    <span>Price includes VAT</span>
                                </label>
                                <label class="form-radio-label">
                                    <input type="radio" name="price_display" value="exclusive">
                                    <span>Price excludes VAT</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">Payment Methods</h3>
                        <p class="card-description">Enable/Disable payment methods available at POS.</p>
                        <div class="form-toggle-list">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-money-bill-wave"></i> Cash</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-qrcode"></i> QR Payment</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-credit-card"></i> Credit Card</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">Hardware & Connection</h3>
                        <p class="card-description">Configure receipt printer and cash drawer connections for POS.</p>

                        <h4 class="form-section-title">Receipt Printer</h4>
                        
                        <div class="form-grid-2-col">
                            <div class="form-group">
                                <label for="printer_connection" class="form-label">Connection Type</label>
                                <select id="printer_connection" class="form-select">
                                    <option value="none">Disable</option>
                                    <option value="browser" selected>Browser Print</option>
                                    <option value="network">Network Printer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="printer_paper_size" class="form-label">Paper Size</label>
                                <select id="printer_paper_size" class="form-select">
                                    <option value="80mm" selected>80mm (Standard)</option>
                                    <option value="58mm">58mm</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="printer_ip" class="form-label">Printer IP Address (if any)</label>
                            <input type="text" id="printer_ip" class="form-input" placeholder="e.g., 192.168.1.100">
                            <p class="form-label-description" style="margin-top: 8px;">*Required when "Network Printer" is selected</p>
                        </div>
                        
                        <h4 class="form-section-title">Automation</h4>

                        <div class="form-toggle-list">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-print"></i> Auto-print receipt after sale</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-cash-register"></i> Auto-open cash drawer (for cash payments)</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">Receipt Template</h3>
                        <div class="form-group">
                            <label for="receipt_header" class="form-label">Receipt Header Text</label>
                            <textarea id="receipt_header" class="form-textarea" rows="2" placeholder="e.g., 'Thank you for your visit'"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="receipt_footer" class="form-label">Receipt Footer Text</label>
                            <textarea id="receipt_footer" class="form-textarea" rows="2"
                                placeholder="e.g., 'Please check items before leaving'"></textarea>
                        </div>
                    </div>
                </section>

                <section id="tab-inventory" class="settings-pane">

                    <div class="settings-card">
                        
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Product Categories</h3>
                                <p class="card-description">Group products and medicines for easier management and reporting.</p>
                            </div>
                            <div>
                                <button class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i>
                                    Add New Category
                                </button>
                            </div>
                        </div>
                        
                        <div class="roles-list">
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Pharmacy Drug</strong>
                                    <small>Drugs requiring pharmacist (e.g., antibiotics)</small>
                                </div>
                                <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Controlled Drug</strong>
                                    <small>Drugs requiring prescription</small>
                                </div>
                                <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Household Medicine</strong>
                                    <small>Over-the-counter (e.g., Paracetamol)</small>
                                </div>
                                <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>Supplies / Devices</strong>
                                    <small>e.g., Cotton, bandages, masks</small>
                                </div>
                                <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Units of Measurement</h3>
                                <p class="card-description">
                                    <strong>Very Important:</strong> Define unit relationships (e.g., Box -> Pack -> Pill).
                                </p>
                            </div>
                            <div>
                                <button class="btn btn-secondary">
                                    <i class="fa-solid fa-plus"></i>
                                    Add New Unit
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive-wrapper">
                            <table class="settings-table">
                                <thead>
                                    <tr>
                                        <th>Main Unit (e.g., Box)</th>
                                        <th>Sub-unit (e.g., Pack)</th>
                                        <th>Ratio</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Box</strong></td>
                                        <td>Pack</td>
                                        <td>1 Box = 10 Packs</td>
                                        <td>
                                            <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pack</strong></td>
                                        <td>Pill</td>
                                        <td>1 Pack = 10 Pills</td>
                                        <td>
                                            <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bottle</strong></td>
                                        <td>Milliliter (ml)</td>
                                        <td>1 Bottle = 500 ml</td>
                                        <td>
                                            <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dozen</strong></td>
                                        <td>Piece</td>
                                        <td>1 Dozen = 12 Pieces</td>
                                        <td>
                                            <button class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                            <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section id="tab-pharmacy" class="settings-pane">

                    <div class="settings-card">
                        <h3 class="card-title">Patient & Member Settings</h3>
                        <p class="card-description">Configure loyalty program and required patient data.</p>

                        <h4 class="form-section-title">Loyalty Program</h4>
                        
                        <div class="form-toggle-list" style="margin-bottom: 24px;">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-star"></i> Enable Loyalty Program</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-grid-2-col">
                            <div class="form-group">
                                <label for="loyalty_earn_rate" class="form-label">For every (THB) spent</label>
                                <input type="number" id="loyalty_earn_rate" class="form-input" value="25">
                            </div>
                            <div class="form-group">
                                <label for="loyalty_earn_points" class="form-label">Receive (points)</label>
                                <input type="number" id="loyalty_earn_points" class="form-input" value="1">
                            </div>
                        </div>

                        <div class="form-grid-2-col">
                            <div class="form-group">
                                <label for="loyalty_redeem_points" class="form-label">Redemption Rate (points)</label>
                                <input type="number" id="loyalty_redeem_points" class="form-input" value="100">
                            </div>
                            <div class="form-group">
                                <label for="loyalty_redeem_value" class="form-label">Is equivalent to (THB)</label>
                                <div class="input-with-suffix">
                                    <input type="number" id="loyalty_redeem_value" class="form-input" value="10">
                                    <span>THB</span>
                                </div>
                            </div>
                        </div>

                        <h4 class="form-section-title">Patient Profile</h4>
                        <div class="form-toggle-list">
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-triangle-exclamation"></i> Require Allergy Information</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-toggle-item">
                                <span><i class="fa-solid fa-heart-pulse"></i> Require Chronic Conditions</span>
                                <label class="form-toggle-switch">
                                    <input type="checkbox" checked >
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h3 class="card-title">Drug Label Settings</h3>
                        <p class="card-description">Define default text and a library of dosing instructions for printing labels.</p>

                        <div class="form-group">
                            <label for="label_header" class="form-label">Label Header (Default)</label>
                            <textarea id="label_header" class="form-textarea" rows="2" placeholder="e.g., 'For [Patient Name]'"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="label_footer" class="form-label">Label Footer (Default)</label>
                            <textarea id="label_footer" class="form-textarea" rows="2"
                                placeholder="e.g., 'Keep out of reach of children' or 'Stop if allergic reaction occurs'"></textarea>
                        </div>

                        <h4 class="form-section-title">Dosing Instructions Library</h4>
                        <p class="card-description" style="margin-top: -12px; margin-bottom: 16px;">
                            Add/Remove common dosing instructions for quick selection by pharmacists.
                        </p>

                        <div class="roles-list">
                            <div class="role-item">
                                <div class="role-info"><strong>Take 1 pill, 3 times daily, after meals (Morning, Lunch, Evening)</strong></div>
                                <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info"><strong>Take 1 pill, 2 times daily, after meals (Morning, Evening)</strong></div>
                                <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info"><strong>Take 1 pill at bedtime</strong></div>
                                <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            <div class="role-item">
                                <div class="role-info"><strong>Apply to affected area, 2 times daily (Morning, Evening)</strong></div>
                                <button class="btn-icon" title="Delete" style="color: #d90000;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>

                        <button class="btn btn-secondary" style="margin-top: 1rem;">
                            <i class="fa-solid fa-plus"></i> Add New Instruction
                        </button>
                    </div>

                </section>


                    <!-- ======================= -->
                    <!-- Tab 6: System & Notifications -->
                    <!-- ======================= -->
                    <section id="tab-system" class="settings-pane">
                        <div class="settings-card">
                            <h3 class="card-title">Alerts & Notifications</h3>

                            <div class="form-group">
                                <label for="low_stock_alert" class="form-label">Low Stock Alert</label>
                                <p class="form-label-description">Alert when stock falls below the specified amount.</p>
                                <div class="input-with-suffix">
                                    <input type="number" id="low_stock_alert" class="form-input" value="10">
                                    <span>items</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="expiry_alert" class="form-label">Expiry Alert</label>
                                <p class="form-label-description">Alert in advance before medicine expires (Very Important).</p>
                                <div class="input-with-suffix">
                                    <input type="number" id="expiry_alert" class="form-input" value="90">
                                    <span>days</span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-card">
                            <h3 class="card-title">General Settings</h3>
                            <div class="form-grid-2-col">
                                <div class="form-group">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select id="timezone" class="form-select">
                                        <option value="Asia/Bangkok" selected>Asia/Bangkok (GMT+7)</option>
                                        <option value="Europe/London">Europe/London (GMT+0)</option>
                                        <option value="America/New_York">America/New_York (GMT-5)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select id="currency" class="form-select">
                                        <option value="THB" selected>THB (฿)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="JPY">JPY (¥)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
</x-app-layout>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers - Pharmacy ERP</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Main Purchasing CSS -->
    <link rel="stylesheet" href="{{ asset('resources/css/purchasing.css') }}">
</head>

<x-app-layout>
    <!-- Main Page Container -->
    <div class="purchasing-page-container">

        {{-- [!!! REFACTORED HEADER !!!] --}}
        <div class="sr-header">
            <div class="sr-header-left">
                <p class="sr-breadcrumb">Dashboard / Purchasing / Suppliers > <a href="{{ route('purchasing.purchaseOrders') }}" style="color: #017aff">Purchase-Orders</a> </p>
                <h2 class="sr-page-title">Suppliers</h2>
            </div>
            <div class="sr-header-right" style="display: flex; align-items: center; gap: 12px;">
                
                <!-- [!!! NEW: Bulk Actions (Hidden by default) !!!] -->
                <div id="bulk-actions" style="display: none; align-items: center; gap: 8px; margin-right: 12px; padding-right: 12px; border-right: 1px solid #d2d2d7;">
                    <span class="inv-text-sub">Selected: <span id="selected-count" style="color: #1d1d1f; font-weight: 700;">0</span></span>
                    
                    <button class="inv-btn-secondary">
                        <i class="fa-solid fa-file-export"></i> Export
                    </button>
                    
                    <button class="inv-btn-secondary" style="color: #ff3b30; background-color: #fff1f0;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>

                <button class="sr-button-primary" id="open-supplier-modal">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add New Supplier</span>
                </button>
            </div>
        </div>

        {{-- <!-- [!!! NEW: Action Bar with Search !!!] --> --}}
        <div class="purchasing-action-bar">
            <div class="purchasing-search-bar">
                <i class="fa-solid fa-search"></i>
                <input type="text" placeholder="ค้นหาจากชื่อบริษัท หรือ เบอร์โทรศัพท์...">
            </div>
            <!-- Add filters here if needed -->
        </div>

        <!-- [!!! NEW: List View (Replaces Table) !!!] -->
        <main class="purchasing-content-area" id="supplier-list">
            <div class="purchasing-list-container">
                <!-- Header Row (Added empty column for checkbox) -->
                <div class="purchasing-list-row header-row">
                    <div class="col-header"><div class="inv-checkbox" id="select-all-checkbox"></div></div> <!-- Checkbox Spacer -->
                    <div class="col-header">Company Name</div>
                    <div class="col-header">Contact Person</div>
                    <div class="col-header">Phone</div>
                    <div class="col-header">Email</div>
                    <div class="col-header">Total POs</div>
                    <div class="col-header">Actions</div>
                </div>

                <!-- Example Row 1 -->
                <div class="purchasing-list-row">
                    <!-- [!!! Checkbox !!!] -->
                    <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="1"></div>
                    </div>
                    <div class="col-company-name" data-label="Company Name">บริษัท ยาดี จำกัด</div>
                    <div class="col-contact" data-label="Contact Person">คุณสมชาย</div>
                    <div class="col-phone" data-label="Phone">081-234-5678</div>
                    <div class="col-email" data-label="Email">contact@yad.co.th</div>
                    <div class="col-pos" data-label="Total POs">125</div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button btn-view" title="View" data-supplier-id="1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button btn-edit" title="Edit" data-supplier-id="1">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete" title="Delete" data-supplier-id="1">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <!-- Example Row 2 -->
                <div class="purchasing-list-row">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="2"></div>
                    </div>
                    <div class="col-company-name" data-label="Company Name">Pharma Distribution Co., Ltd.</div>
                    <div class="col-contact" data-label="Contact Person">คุณสุนีย์</div>
                    <div class="col-phone" data-label="Phone">02-999-8888</div>
                    <div class="col-email" data-label="Email">sunee@pharma-dist.com</div>
                    <div class="col-pos" data-label="Total POs">88</div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button btn-view" title="View" data-supplier-id="2">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button btn-edit" title="Edit" data-supplier-id="2">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete" title="Delete" data-supplier-id="2">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <!-- Example Row 3 -->
                <div class="purchasing-list-row">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="3"></div>
                    </div>
                    <div class="col-company-name" data-label="Company Name">MedSupply Thailand</div>
                    <div class="col-contact" data-label="Contact Person">คุณวสันต์</div>
                    <div class="col-phone" data-label="Phone">090-123-4567</div>
                    <div class="col-email" data-label="Email">contact@medsupply.th</div>
                    <div class="col-pos" data-label="Total POs">45</div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button btn-view" title="View" data-supplier-id="3">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button btn-edit" title="Edit" data-supplier-id="3">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete" title="Delete" data-supplier-id="3">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <!-- Example Row 4 -->
                <div class="purchasing-list-row">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="4"></div>
                    </div>
                    <div class="col-company-name" data-label="Company Name">Bangkok Drugs Import</div>
                    <div class="col-contact" data-label="Contact Person">Dr. Aree</div>
                    <div class="col-phone" data-label="Phone">02-100-2000</div>
                    <div class="col-email" data-label="Email">aree@bdi.co.th</div>
                    <div class="col-pos" data-label="Total POs">210</div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button btn-view" title="View" data-supplier-id="4">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button btn-edit" title="Edit" data-supplier-id="4">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete" title="Delete" data-supplier-id="4">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <!-- Example Row 5 -->
                <div class="purchasing-list-row">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="5"></div>
                    </div>
                    <div class="col-company-name" data-label="Company Name">Pure Health Corp.</div>
                    <div class="col-contact" data-label="Contact Person">Ms. Joy</div>
                    <div class="col-phone" data-label="Phone">089-000-1111</div>
                    <div class="col-email" data-label="Email">joy@purehealth.net</div>
                    <div class="col-pos" data-label="Total POs">15</div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button btn-view" title="View" data-supplier-id="5">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="purchasing-icon-button btn-edit" title="Edit" data-supplier-id="5">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="purchasing-icon-button btn-delete" title="Delete" data-supplier-id="5">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <!-- Supplier Modal HTML (Same as before) -->
    <div class="modal-backdrop" id="supplier-modal-backdrop" style="display: none;">
        <div class="modal-content" id="supplier-modal-content">
            <form id="supplier-form">
                @csrf
                <div class="modal-header">
                    <h2 id="modal-title">Add New Supplier</h2>
                    <button type="button" class="purchasing-icon-button btn-close-modal" id="close-modal-btn"><i
                            class="fa-solid fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group span-2">
                            <label for="company_name">ชื่อบริษัท (Company Name) <span
                                    class="required">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="purchasing-input"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="tax_id">เลขประจำตัวผู้เสียภาษี (Tax ID)</label>
                            <input type="text" id="tax_id" name="tax_id" class="purchasing-input">
                        </div>
                        <div class="form-group">
                            <label for="contact_person">ชื่อผู้ติดต่อ (Contact Person)</label>
                            <input type="text" id="contact_person" name="contact_person"
                                class="purchasing-input">
                        </div>
                        <div class="form-group">
                            <label for="phone">เบอร์โทร (Phone) <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="purchasing-input"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">อีเมล (Email)</label>
                            <input type="email" id="email" name="email" class="purchasing-input">
                        </div>
                        <div class="form-group span-2">
                            <label for="address">ที่อยู่ (Address)</label>
                            <textarea id="address" name="address" class="purchasing-input" rows="3"></textarea>
                        </div>
                        <div class="form-group span-2">
                            <label for="notes">Notes (หมายเหตุ)</label>
                            <textarea id="notes" name="notes" class="purchasing-input" rows="3"
                                placeholder="เช่น: ส่งของทุกวันอังคาร, ขั้นต่ำ 5,000 บาท"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="purchasing-button-secondary"
                        id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="purchasing-button-primary" id="save-modal-btn">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>
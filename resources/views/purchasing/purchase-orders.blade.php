<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders - Pharmacy ERP</title>
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
                <p class="sr-breadcrumb">Dashboard / Purchasing / <a href="{{ route('purchasing.suppliers') }}" style="color: #017aff">Suppliers</a> / Purchase Orders > <a href="{{ route('purchasing.goodsReceived') }}" style="color: #017aff">Goods Received</a></p>
                <h2 class="sr-page-title">Purchase Orders</h2>
            </div>
            <div class="sr-header-right" style="display: flex; align-items: center; gap: 12px;">
                
                <!-- [!!! NEW: Bulk Actions (Hidden by default) !!!] -->
                <div id="bulk-actions" style="display: none; align-items: center; gap: 8px; margin-right: 12px; padding-right: 12px; border-right: 1px solid #d2d2d7;">
                    <span class="inv-text-sub">Selected: <span id="selected-count" style="color: #1d1d1f; font-weight: 700;">0</span></span>
                    
                    <button class="inv-btn-secondary">
                        <i class="fa-solid fa-print"></i> Print PO
                    </button>
                    
                    <button class="inv-btn-secondary" style="color: #ff3b30; background-color: #fff1f0;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>

                {{-- ID changed to open-po-modal --}}
                <button class="sr-button-primary" id="open-po-modal">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create New PO</span>
                </button>
            </div>
        </div>

        <!-- [!!! NEW: Action Bar with Search & Filter !!!] -->
        <div class="purchasing-action-bar">
            <!-- Search -->
            <div class="purchasing-search-bar">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="po-search-input" placeholder="ค้นหาจากเลขที่ PO หรือ ชื่อซัพพลายเออร์...">
            </div>

            <!-- Sliding Filter -->
            <div class="sliding-toggle-filter" id="po-status-filter">
                <button class="toggle-btn active" data-filter="all">All</button>
                <button class="toggle-btn" data-filter="draft">Draft</button>
                <button class="toggle-btn" data-filter="sent">Sent</button>
                <button class="toggle-btn" data-filter="partially">Partially</button>
                <button class="toggle-btn" data-filter="completed">Completed</button>
                <button class="toggle-btn" data-filter="cancelled">Cancelled</button>
            </div>
        </div>

        <!-- [!!! NEW: List View !!!] -->
        <main class="content-area" id="po-list">
            <div class="purchasing-list-container" id="po-list-container">
                <!-- Header Row (Added empty column for checkbox) -->
                <div class="purchasing-list-row header-row">
                    <div class="col-header"><div class="inv-checkbox" id="select-all-checkbox"></div></div> <!-- Checkbox Spacer -->
                    <div class="col-header">PO Number</div>
                    <div class="col-header">Supplier</div>
                    <div class="col-header">Date Ordered</div>
                    <div class="col-header">Expected Delivery</div>
                    <div class="col-header">Total Cost</div>
                    <div class="col-header">Status</div>
                    <div class="col-header">Actions</div>
                </div>

                {{-- Rows with Checkboxes --}}

                <!-- Row 1: Completed -->
                <div class="purchasing-list-row po-item" data-status="completed">
                    <!-- [!!! Checkbox !!!] -->
                    <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="PO-2025-001"></div>
                    </div>
                    <div class="col-po-number" data-label="PO Number">PO-2025-001</div>
                    <div class="col-supplier" data-label="Supplier">บริษัท ยาดี จำกัด</div>
                    <div class="col-date" data-label="Date Ordered">15/10/2025</div>
                    <div class="col-date" data-label="Expected Delivery">17/10/2025</div>
                    <div class="col-cost" data-label="Total Cost">฿15,000.00</div>
                    <div class="col-status" data-label="Status"><span class="status-badge status-completed">Completed</span></div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button" title="View"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>

                <!-- Row 2: Sent -->
                <div class="purchasing-list-row po-item" data-status="sent">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="PO-2025-002"></div>
                    </div>
                    <div class="col-po-number" data-label="PO Number">PO-2025-002</div>
                    <div class="col-supplier" data-label="Supplier">Pharma Distribution</div>
                    <div class="col-date" data-label="Date Ordered">16/10/2025</div>
                    <div class="col-date" data-label="Expected Delivery">20/10/2025</div>
                    <div class="col-cost" data-label="Total Cost">฿8,500.00</div>
                    <div class="col-status" data-label="Status"><span class="status-badge status-sent">Sent</span></div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button" title="View"><i class="fa-solid fa-eye"></i></button>
                        <button class="purchasing-icon-button" title="Receive Stock"><i class="fa-solid fa-check"></i></button>
                    </div>
                </div>

                <!-- Row 3: Draft -->
                <div class="purchasing-list-row po-item" data-status="draft">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="PO-2025-003"></div>
                    </div>
                    <div class="col-po-number" data-label="PO Number">PO-2025-003</div>
                    <div class="col-supplier" data-label="Supplier">บริษัท ยาดี จำกัด</div>
                    <div class="col-date" data-label="Date Ordered">17/10/2025</div>
                    <div class="col-date" data-label="Expected Delivery">-</div>
                    <div class="col-cost" data-label="Total Cost">฿22,000.00</div>
                    <div class="col-status" data-label="Status"><span class="status-badge status-draft">Draft</span></div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button" title="View"><i class="fa-solid fa-eye"></i></button>
                        <button class="purchasing-icon-button" title="Edit"><i class="fa-solid fa-pen"></i></button>
                    </div>
                </div>

                <!-- Row 4: Partially Received -->
                <div class="purchasing-list-row po-item" data-status="partially">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="PO-2025-004"></div>
                    </div>
                    <div class="col-po-number" data-label="PO Number">PO-2025-004</div>
                    <div class="col-supplier" data-label="Supplier">MedSupply (Thailand)</div>
                    <div class="col-date" data-label="Date Ordered">14/10/2025</div>
                    <div class="col-date" data-label="Expected Delivery">16/10/2025</div>
                    <div class="col-cost" data-label="Total Cost">฿31,000.00</div>
                    <div class="col-status" data-label="Status"><span class="status-badge status-partially">Partially Received</span></div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button" title="View"><i class="fa-solid fa-eye"></i></button>
                        <button class="purchasing-icon-button" title="Receive Remaining Stock"><i class="fa-solid fa-check"></i></button>
                    </div>
                </div>
                
                <!-- Row 5: Cancelled -->
                <div class="purchasing-list-row po-item" data-status="cancelled">
                     <!-- [!!! Checkbox !!!] -->
                     <div class="col-checkbox">
                        <div class="inv-checkbox" data-id="PO-2025-005"></div>
                    </div>
                    <div class="col-po-number" data-label="PO Number">PO-2025-005</div>
                    <div class="col-supplier" data-label="Supplier">Pure Health Corp.</div>
                    <div class="col-date" data-label="Date Ordered">10/10/2025</div>
                    <div class="col-date" data-label="Expected Delivery">-</div>
                    <div class="col-cost" data-label="Total Cost">฿5,000.00</div>
                    <div class="col-status" data-label="Status"><span class="status-badge status-cancelled">Cancelled</span></div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button" title="View"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>
            </div>
        </main>

        <!-- [!!! CREATE PO MODAL !!!] -->
        <div class="modal-backdrop" id="po-modal-backdrop" style="display: none;">
            <div class="modal-content" id="po-modal-content">
                <form id="create-po-form">
                    <div class="modal-header">
                        <h2>Create Purchase Order</h2>
                        <button type="button" class="purchasing-icon-button btn-close-modal" id="close-po-modal-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="form-grid">
                            <!-- Row 1 -->
                            <div class="form-group span-2">
                                <label for="po_supplier">Select Supplier <span class="required">*</span></label>
                                <select id="po_supplier" name="supplier" class="purchasing-input-lg" required>
                                    <option value="" disabled selected>-- Choose Supplier --</option>
                                    <option value="1">บริษัท ยาดี จำกัด</option>
                                    <option value="2">Pharma Distribution Co., Ltd.</option>
                                    <option value="3">MedSupply Thailand</option>
                                </select>
                            </div>

                            <!-- Row 2 -->
                            <div class="form-group">
                                <label for="po_date">Order Date <span class="required">*</span></label>
                                <input type="date" id="po_date" name="order_date" class="purchasing-input" required>
                            </div>
                            <div class="form-group">
                                <label for="expected_date">Expected Delivery</label>
                                <input type="date" id="expected_date" name="expected_date" class="purchasing-input">
                            </div>

                            <!-- Row 3 -->
                            <div class="form-group span-2">
                                <label for="po_notes">Notes / Remarks</label>
                                <textarea id="po_notes" name="notes" class="purchasing-input" rows="3" placeholder="Add instructions for supplier..."></textarea>
                            </div>
                            
                            <!-- Item List Placeholder -->
                            <div class="form-group span-2" style="border-top: 1px dashed #d2d2d7; padding-top: 16px; margin-top: 8px;">
                                <label>Order Items (Preview)</label>
                                <div style="background: #f9f9f9; padding: 12px; border-radius: 12px; text-align: center; color: #888;">
                                    <i class="fa-solid fa-list-check" style="margin-bottom: 8px; display: block;"></i>
                                    Item selection interface would go here...
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="purchasing-button-secondary" id="cancel-po-modal-btn">Cancel</button>
                        <button type="submit" class="purchasing-button-primary">Create Draft PO</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>
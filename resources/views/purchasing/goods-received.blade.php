<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Received - Pharmacy ERP</title>
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
                <p class="sr-breadcrumb">Dashboard / Purchasing / Goods Received < <a href="{{ route('purchasing.purchaseOrders') }}" style="color: #017aff">Purchase Orders</a></p>
                <h2 class="sr-page-title">Goods Received (4)</h2>
            </div>

            <div class="sr-header-right" style="margin-right: 10px; display: flex; align-items: center; gap: 12px;">    
                
                <!-- [!!! NEW: Bulk Actions (Hidden by default) !!!] -->
                <div id="bulk-actions" style="display: none; align-items: center; gap: 8px; margin-right: 12px; padding-right: 12px; border-right: 1px solid #d2d2d7;">
                    <span class="inv-text-sub">Selected: <span id="selected-count" style="color: #1d1d1f; font-weight: 700;">0</span></span>
                    
                    <button class="inv-btn-secondary">
                        <i class="fa-solid fa-barcode"></i> Print
                    </button>
                    
                    <button class="inv-btn-secondary" style="color: #ff3b30; background-color: #fff1f0;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>

                <!-- Standard Actions (Filter) -->
                <button class="sr-icon-button" title="Filter">
                    <i class="fa-solid fa-filter"></i>
                </button>
                
                <!-- Add Button -->
                <button class="sr-button-primary" id="open-gr-modal">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add new Receive</span>
                </button>
            </div>
        </div>

        <!-- Workflow View 1: Search for PO -->
        <div id="po-search-view" class="gr-workflow-container">
            <label for="po-search-input"><h2>Search by PO Number</h2></label>
            <div class="po-search-box">
                <input type="text" id="po-search-input" class="purchasing-input-lg" placeholder="e.g., PO-2025-002">
                <button class="purchasing-button-primary purchasing-button-lg" id="search-po-btn">
                    <i class="fa-solid fa-search"></i> Receive
                </button>
            </div>

            <div class="po-awaiting-list">
                <h3><i class="fa-solid fa-list-check"></i> POs Awaiting Reception (สถานะ: Sent)</h3>
                <ul style="list-style: none; padding: 0;">
                    <!-- [!!! NEW: Added Checkboxes to List Items !!!] -->
                    <li style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px dashed #d2d2d7;">
                        <div class="inv-checkbox" data-id="PO-2025-002"></div>
                        <div>
                            <a href="#" class="po-link" data-po="PO-2025-002">PO-2025-002</a> (from Pharma Distribution)
                        </div>
                    </li>
                    
                    <li style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px dashed #d2d2d7;">
                        <div class="inv-checkbox" data-id="PO-2025-004"></div>
                        <div>
                            <a href="#" class="po-link" data-po="PO-2025-004">PO-2025-004</a> (from MedSupply (Thailand)) - <em style="color: #faad14; font-style: normal;">Partial</em>
                        </div>
                    </li>
                    
                    <li style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px dashed #d2d2d7;">
                        <div class="inv-checkbox" data-id="PO-2025-005"></div>
                        <div>
                             <a href="#" class="po-link" data-po="PO-2025-005">PO-2025-005</a> (from Bangkok Drugs)
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Workflow View 2: Receive Items (Hidden by default) -->
        <div id="po-receive-view" class="gr-workflow-container" style="display: none;">
            
            <div class="receive-header">
                <div>
                    <h2>Receiving Items for PO-2025-002</h2>
                    <p><strong>Supplier:</strong> Pharma Distribution<br>
                       <strong>Ordered:</strong> 16/10/2025 | <strong>Expected:</strong> 20/10/2025</p>
                </div>
                <button class="purchasing-button-secondary" id="back-to-search-btn">
                    <i class="fa-solid fa-arrow-left"></i> Back to Search
                </button>
            </div>

            <!-- Receiving Table -->
            <table class="data-table receiving-table">
                <thead>
                    <tr>
                        <th>สินค้า (Product)</th>
                        <th>สั่ง (Ordered)</th>
                        <th>ได้รับ (Received) <span class="required">*</span></th>
                        <th>Batch / Lot No. <span class="required">*</span></th>
                        <th>วันหมดอายุ (Expiry) <span class="required">*</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Product">Paracetamol 500mg</td>
                        <td data-label="Ordered">100 กล่อง</td>
                        <td data-label="Received"><input type="number" class="purchasing-input rcv-input" value="100"></td>
                        <td data-label="Batch / Lot"><input type="text" class="purchasing-input batch-input" placeholder="e.g., AB1234"></td>
                        <td data-label="Expiry"><input type="date" class="purchasing-input expiry-input"></td>
                    </tr>
                    <tr>
                        <td data-label="Product">Vitamin C 1000mg</td>
                        <td data-label="Ordered">50 ขวด</td>
                        <td data-label="Received"><input type="number" class="purchasing-input rcv-input" value="50"></td>
                        <td data-label="Batch / Lot"><input type="text" class="purchasing-input batch-input" placeholder="e.g., VC5566"></td>
                        <td data-label="Expiry"><input type="date" class="purchasing-input expiry-input"></td>
                    </tr>
                    <tr>
                        <td data-label="Product">Alcohol 70% (30ml)</td>
                        <td data-label="Ordered">30 ขวด</td>
                        <td data-label="Received"><input type="number" class="purchasing-input rcv-input" value="30"></td>
                        <td data-label="Batch / Lot"><input type="text" class="purchasing-input batch-input" placeholder="e.g., ALC7788"></td>
                        <td data-label="Expiry"><input type="date" class="purchasing-input expiry-input"></td>
                    </tr>
                </tbody>
            </table>

            <div class="receive-footer">
                <div class="form-group">
                    <label for="gr-notes">หมายเหตุการรับของ (Optional)</label>
                    <input type="text" id="gr-notes" class="purchasing-input" placeholder="เช่น: Alcohol แตก 1 ขวด, รับจริง 29">
                </div>
                <button class="purchasing-button-primary purchasing-button-lg btn-confirm-receive">
                    <i class="fa-solid fa-check-double"></i> Confirm & Receive Stock
                </button>
            </div>

        </div>

        <!-- Create Receive Modal -->
        <div class="modal-backdrop" id="gr-modal-backdrop" style="display: none;">
            <div class="modal-content" id="gr-modal-content">
                <form id="create-gr-form">
                    <div class="modal-header">
                        <h2>New Goods Received</h2>
                        <button type="button" class="purchasing-icon-button btn-close-modal" id="close-gr-modal-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="form-grid">
                            <div class="form-group span-2">
                                <label for="gr_po_select">Select Purchase Order <span class="required">*</span></label>
                                <select id="gr_po_select" name="po_id" class="purchasing-input-lg" required>
                                    <option value="" disabled selected>-- Select Pending PO --</option>
                                    <option value="PO-2025-002">PO-2025-002 (Pharma Dist.)</option>
                                    <option value="PO-2025-004">PO-2025-004 (MedSupply) - Partial</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="gr_date">Received Date <span class="required">*</span></label>
                                <input type="date" id="gr_date" name="received_date" class="purchasing-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="gr_ref_no">Invoice / DO Ref. No.</label>
                                <input type="text" id="gr_ref_no" name="ref_no" class="purchasing-input" placeholder="e.g. INV-998877">
                            </div>

                            <div class="form-group span-2">
                                <div style="background-color: #f0f8ff; padding: 12px; border-radius: 12px; color: #0056b3; font-size: 0.9rem; display: flex; gap: 8px; align-items: flex-start;">
                                    <i class="fa-solid fa-circle-info" style="margin-top: 3px;"></i>
                                    <span>Selecting a PO will verify the item list. You can adjust quantities in the next step.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="purchasing-button-secondary" id="cancel-gr-modal-btn">Cancel</button>
                        <button type="submit" class="purchasing-button-primary">Proceed to Items</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>
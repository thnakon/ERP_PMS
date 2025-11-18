
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

            <div class="sr-header-right" style="margin-right: 10px">    
                <button class="sr-icon-button" title="Filter">
                    <i class="fa-solid fa-filter"></i>
                </button>
                {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
                และใช้คลาสใหม่ sr-button-primary --}}
                <button class="sr-button-primary">
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
                <ul>
                    <li><a href="#" class="po-link" data-po="PO-2025-002">PO-2025-002</a> (from Pharma Distribution)</li>
                    <li><a href="#" class="po-link" data-po="PO-2025-004">PO-2025-004</a> (from MedSupply (Thailand)) - <em>Partial</em></li>
                    <!-- List generated dynamically -->
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

            <!-- Receiving Table (Kept as table for data entry, re-styled by CSS) -->
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
                    <!-- Example from user's request -->
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

    </div>

    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</x-app-layout>
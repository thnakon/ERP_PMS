
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
                   <p class="sr-breadcrumb">Dashboard / Purchasing / < <a href="{{ route('purchasing.suppliers') }}" style="color: #017aff">Suplliers</a> / Purchase Orders > <a href="{{ route('purchasing.goodsReceived') }}" style="color: #017aff">Goods Received</a></p>
                    <h2 class="sr-page-title">Purchase Orders</h2>
                </div>
                <div class="sr-header-right">
                    {{-- [!!! ADJUSTED !!!] เปลี่ยน div/span เป็น <button> 
             และใช้คลาสใหม่ sr-button-primary --}}
                    <button class="sr-button-primary" id="open-supplier-modal">
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
                <input type="text" placeholder="ค้นหาจากเลขที่ PO หรือ ชื่อซัพพลายเออร์...">
            </div>

            <!-- Sliding Filter (Re-styled) -->
            <div class="sliding-toggle-filter" id="po-status-filter">
                <button class="toggle-btn active" data-filter="all">All</button>
                <button class="toggle-btn" data-filter="draft">Draft</button>
                <button class="toggle-btn" data-filter="sent">Sent</button>
                <button class="toggle-btn" data-filter="partially">Partially</button>
                <button class="toggle-btn" data-filter="completed">Completed</button>
                <button class="toggle-btn" data-filter="cancelled">Cancelled</button>
            </div>
        </div>

        <!-- [!!! NEW: List View (Replaces Table) !!!] -->
        <main class="content-area" id="po-list">
            <div class="purchasing-list-container">
                <!-- Header Row -->
                <div class="purchasing-list-row header-row">
                    <div class="col-header">PO Number</div>
                    <div class="col-header">Supplier</div>
                    <div class="col-header">Date Ordered</div>
                    <div class="col-header">Expected Delivery</div>
                    <div class="col-header">Total Cost</div>
                    <div class="col-header">Status</div>
                    <div class="col-header">Actions</div>
                </div>

                <!-- Example Row 1: Completed -->
                <div class="purchasing-list-row">
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

                <!-- Example Row 2: Sent -->
                <div class="purchasing-list-row">
                    <div class="col-po-number" data-label="PO Number">PO-2025-002</div>
                    <div class="col-supplier" data-label="Supplier">Pharma Distribution</div>
                    <div class="col-date" data-label="Date Ordered">16/10/2025</div>
                    <div class="col-date" data-label="Expected Delivery">20/10/2025</div>
                    <div class="col-cost" data-label="Total Cost">฿8,500.00</div>
                    <div class="col-status" data-label="Status"><span class="status-badge status-sent">Sent</span></div>
                    <div class="col-actions" data-label="Actions">
                        <button class="purchasing-icon-button" title="View"><i class="fa-solid fa-eye"></i></button>
                        <button class="purchasing-icon-button" title="Receive Stock" id="go-to-receive"><i class="fa-solid fa-check"></i></button>
                    </div>
                </div>

                <!-- Example Row 3: Draft -->
                <div class="purchasing-list-row">
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

                <!-- Example Row 4: Partially Received -->
                <div class="purchasing-list-row">
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
                <!-- Example Row 4: Partially Received -->
                <div class="purchasing-list-row">
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
            </div>
        </main>

    </div>

    <!-- Main Purchasing JS -->
    <script src="{{ asset('resources/js/purchasing.js') }}" defer></script>
</body>
</x-app-layout>
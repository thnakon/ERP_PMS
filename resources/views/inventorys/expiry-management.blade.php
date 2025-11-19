<x-app-layout>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expiry Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/inventorys.css">
</head>
<body>

<div class="inv-container">
    <div class="inv-container">
    <div class="inv-breadcrumb-bar">
    Dashboard / Inventory /
    <span style="color: #3a3a3c; font-weight: 600;">Expiry Management</span> > <a href="{{ route('inventorys.stock-adjustments') }}" style="color: #017aff"> Stock Adjustments </a>
</div>
    <div class="inv-header">
        <div class="inv-header-left">
            <h1 class="inv-page-title">Expiry Management</h1>
        </div>
        <div class="inv-header-right">
            <button class="inv-btn-primary" style="background-color: #ff3b30;" onclick="openModal('modal-return')">
                <i class="fa-solid fa-file-export"></i> Return to Supplier
            </button>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="inv-dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: #fff; padding: 20px; border-radius: 16px; box-shadow: var(--shadow-sm);">
            <div class="inv-text-sub">Expired</div>
            <div class="inv-text-main" style="font-size: 1.8rem; color: #ff3b30;">12</div>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 16px; box-shadow: var(--shadow-sm);">
            <div class="inv-text-sub">Expiring Soon (30d)</div>
            <div class="inv-text-main" style="font-size: 1.8rem; color: #ff9500;">5</div>
        </div>
         <div style="background: #fff; padding: 20px; border-radius: 16px; box-shadow: var(--shadow-sm);">
            <div class="inv-text-sub">Loss Value</div>
            <div class="inv-text-main" style="font-size: 1.8rem; color: #1d1d1f;">฿4,500</div>
        </div>
    </div>

    <!-- Controls Row (Toggle & Bulk Actions) -->
    <div class="inv-filters-wrapper">
        <!-- Toggle -->
        <div class="inv-toggle-wrapper" id="expiryViewToggle">
            <button class="inv-toggle-btn active" data-target="all">All Alerts</button>
            <button class="inv-toggle-btn" data-target="expired">Expired</button>
            <button class="inv-toggle-btn" data-target="near">Near Expiry</button>
        </div>
        
        <!-- Search -->
        <div style="position: relative; margin-left: 12px;">
            <input type="text" placeholder="Search Product/Lot..." class="inv-form-input" style="width: 240px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
        </div>

        <!-- Bulk Actions (Hidden by default) -->
        <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
            <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span id="selected-count">0</span></span>
            <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"><i class="fa-solid fa-trash"></i> Delete / Dispose</button>
             <button class="inv-btn-secondary" style="font-size: 0.8rem;" onclick="openModal('modal-return')"><i class="fa-solid fa-box"></i> Add to Return</button>
        </div>
    </div>

    <!-- Table Header -->
    <div class="inv-card-row header grid-expiry">
        <div class="inv-checkbox" id="select-all"></div>
        <div class="inv-col-header">Product</div>
        <div class="inv-col-header">Lot No.</div>
        <div class="inv-col-header">Expiry Date</div>
        <div class="inv-col-header">Remaining</div>
        <div class="inv-col-header">Location</div>
        <div class="inv-col-header" style="text-align: right;">Status</div>
    </div>

    <!-- SECTION: EXPIRED -->
    <div id="section-expired">
        <!-- Row 1 (Expired) -->
        <!-- [!!! ADDED 'product-row' and 'data-status="expired"' !!!] -->
        <div class="inv-card-row grid-expiry product-row" data-status="expired" style="border-left: 4px solid #ff3b30;">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-name">Vitamin C 1000mg</div>
                <div class="inv-product-generic">Bio-C Brand</div>
            </div>
            <div class="inv-text-main" data-label="Lot">L-220401</div>
            <div class="inv-text-sub" data-label="Date">15 Nov 2024</div>
            <div class="inv-text-main" data-label="Remaining" style="color: #ff3b30;">-3 Days</div>
            <div class="inv-text-sub" data-label="Location">Shelf A1</div>
            <div data-label="Status" style="text-align: right;"><span class="inv-status-badge expired">Expired</span></div>
        </div>
    </div>

    <!-- SECTION: NEAR EXPIRY -->
    <div id="section-near">
        <!-- Row 2 (Near) -->
        <!-- [!!! ADDED 'product-row' and 'data-status="near"' !!!] -->
        <div class="inv-card-row grid-expiry product-row" data-status="near" style="border-left: 4px solid #ff9500;">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-name">Amoxicillin 500mg</div>
                <div class="inv-product-generic">Antibiotics</div>
            </div>
            <div class="inv-text-main" data-label="Lot">AX-9988</div>
            <div class="inv-text-sub" data-label="Date">30 Nov 2024</div>
            <div class="inv-text-main" data-label="Remaining" style="color: #ff9500;">12 Days</div>
            <div class="inv-text-sub" data-label="Location">Shelf B2</div>
            <div data-label="Status" style="text-align: right;"><span class="inv-status-badge low-stock">Near Expiry</span></div>
        </div>
        
        <!-- Row 3 (Near) -->
        <!-- [!!! ADDED 'product-row' and 'data-status="near"' !!!] -->
        <div class="inv-card-row grid-expiry product-row" data-status="near" style="border-left: 4px solid #ff9500;">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-name">Paracetamol Syrup</div>
                <div class="inv-product-generic">Kids Formula</div>
            </div>
            <div class="inv-text-main" data-label="Lot">PS-1122</div>
            <div class="inv-text-sub" data-label="Date">15 Dec 2024</div>
            <div class="inv-text-main" data-label="Remaining" style="color: #ff9500;">27 Days</div>
            <div class="inv-text-sub" data-label="Location">Shelf C1</div>
            <div data-label="Status" style="text-align: right;"><span class="inv-status-badge low-stock">Near Expiry</span></div>
        </div>
    </div>
    
    <!-- SECTION: GOOD/OTHERS -->
    <div id="section-others">
         <!-- Row 4 (Good) -->
         <!-- [!!! ADDED 'product-row' and 'data-status="good"' !!!] -->
         <div class="inv-card-row grid-expiry product-row" data-status="good">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-name">Aspirin 81mg</div>
                <div class="inv-product-generic">Heart Health</div>
            </div>
            <div class="inv-text-main" data-label="Lot">AS-8888</div>
            <div class="inv-text-sub" data-label="Date">10 Jan 2025</div>
            <div class="inv-text-main" data-label="Remaining" style="color: #34c759;">53 Days</div>
            <div class="inv-text-sub" data-label="Location">Shelf A3</div>
            <div data-label="Status" style="text-align: right;"><span class="inv-status-badge active">Good</span></div>
        </div>
        
        <!-- Row 5 (Good) -->
        <!-- [!!! ADDED 'product-row' and 'data-status="good"' !!!] -->
        <div class="inv-card-row grid-expiry product-row" data-status="good">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-name">Betadine Solution</div>
                <div class="inv-product-generic">Antiseptic 15ml</div>
            </div>
            <div class="inv-text-main" data-label="Lot">BT-5544</div>
            <div class="inv-text-sub" data-label="Date">20 Feb 2025</div>
            <div class="inv-text-main" data-label="Remaining" style="color: #34c759;">94 Days</div>
            <div class="inv-text-sub" data-label="Location">Shelf D2</div>
            <div data-label="Status" style="text-align: right;"><span class="inv-status-badge active">Good</span></div>
        </div>
    </div>

    {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-5 of 17</span>
            <div class="pagination-controls">
                <button class="pagination-btn disabled" aria-label="Previous Page">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="pagination-btn" aria-label="Next Page">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

</div>

<!-- MODAL: Return to Supplier -->
<div class="inv-modal-overlay" id="modal-return">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <div class="inv-modal-title">Create Return Request</div>
            <button class="inv-modal-close" onclick="closeModal('modal-return')">&times;</button>
        </div>
        <div class="inv-modal-body">
            <div class="inv-form-group">
                <label class="inv-form-label">Select Supplier</label>
                <select class="inv-form-input"><option>Zuellig Pharma</option><option>DKSH</option></select>
            </div>
            <div class="inv-form-group">
                <label class="inv-form-label">Reason</label>
                <select class="inv-form-input"><option>Expired Goods</option><option>Damaged Goods</option></select>
            </div>
             <p style="color: var(--text-secondary); font-size: 0.9rem;">* Selected items will be added to the return list automatically.</p>
        </div>
        <div class="inv-modal-footer">
            <button class="inv-btn-secondary" onclick="closeModal('modal-return')">Cancel</button>
            <button class="inv-btn-primary">Generate Form</button>
        </div>
    </div>
</div>

<script src="../../js/inventorys.js"></script>
</body>
</html>
</x-app-layout>
<x-app-layout>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/inventorys.css">
</head>
<body>

<div class="inv-container">
    
    <div class="inv-breadcrumb-bar">Inventory > Manage Products</div>
    
    <!-- Header Area -->
    <div class="inv-header">
        <div class="inv-header-left">
            <h1 class="inv-page-title">Manage Products</h1>
        </div>
        <div class="inv-header-right">
            <button class="inv-btn-secondary">
                <i class="fa-solid fa-file-arrow-up"></i> Import
            </button>
            <button class="inv-btn-secondary">
                <i class="fa-solid fa-file-arrow-down"></i> Export
            </button>
            <div style="width: 1px; height: 24px; background-color: #d2d2d7; margin: 0 4px;"></div>
             <button class="inv-btn-primary" onclick="openModal('modal-new-product')">
                <i class="fa-solid fa-plus"></i> New Product
            </button>
        </div>
    </div>
    
    <!-- Controls Row -->
    <div class="inv-filters-wrapper">
        <!-- Toggle -->
        <div class="inv-toggle-wrapper" id="productViewToggle">
            <button class="inv-toggle-btn active" data-target="active">Active Stock</button>
            <button class="inv-toggle-btn" data-target="all">All Items</button>
        </div>

        <!-- Search -->
        <div style="position: relative; margin-left: 12px;">
            <input type="text" id="productSearch" placeholder="Search Name, SKU..." class="inv-form-input" style="width: 280px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
        </div>

        <!-- Bulk Actions (Hidden by default, shown when items selected) -->
        <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
            <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span id="selected-count">0</span></span>
            <button class="inv-btn-secondary" style="font-size: 0.8rem;"><i class="fa-solid fa-barcode"></i> Print Barcode</button>
            <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"><i class="fa-solid fa-trash"></i> Delete</button>
        </div>
    </div>

    <!-- Table Headers -->
    <div class="inv-card-row header grid-products">
        <div class="inv-checkbox" id="select-all"></div>
        <div class="inv-col-header">Product</div>
        <div class="inv-col-header">SKU / Barcode</div>
        <div class="inv-col-header">Location</div>
        <div class="inv-col-header">Category</div>
        <div class="inv-col-header">Price</div>
        <div class="inv-col-header">Stock</div>
        <div class="inv-col-header">Status</div>
        <div class="inv-col-header" style="text-align: right;">Actions</div>
    </div>

    <!-- SECTION: Active Stock (5 Items) -->
    <div id="section-active">
        <!-- Item 1 -->
        <div class="inv-card-row grid-products product-row" data-status="active">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #e5fbeB; color: #34c759;">
                    <i class="fa-solid fa-pills"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Paracetamol 500mg</div>
                    <div class="inv-product-generic">Acetaminophen</div>
                </div>
            </div>
            <div class="inv-text-main" data-label="SKU">885001234567</div>
            <div class="inv-text-sub" data-label="Location">Cab A, Shelf 2</div>
            <div class="inv-text-sub" data-label="Category">Household Med</div>
            <div class="inv-text-main" data-label="Price">฿120.00</div>
            <div class="inv-text-main" data-label="Stock">50 <span class="inv-text-sub">Jars</span></div>
            <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
            <div class="inv-action-group" data-label="Actions" style="text-align: right;">
                <button class="inv-icon-action view" onclick="openModal('modal-view-product')"><i class="fa-solid fa-eye"></i></button>
                <button class="inv-icon-action edit" onclick="openModal('modal-new-product')"><i class="fa-solid fa-pen"></i></button>
                <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="inv-card-row grid-products product-row" data-status="active">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #e6f7ff; color: #007aff;">
                    <i class="fa-solid fa-tablets"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Amoxicillin 500mg</div>
                    <div class="inv-product-generic">Antibiotics</div>
                </div>
            </div>
            <div class="inv-text-main" data-label="SKU">885009988776</div>
            <div class="inv-text-sub" data-label="Location">Cab B, Shelf 1</div>
            <div class="inv-text-sub" data-label="Category">Dangerous Drugs</div>
            <div class="inv-text-main" data-label="Price">฿45.00</div>
            <div class="inv-text-main" data-label="Stock">12 <span class="inv-text-sub" style="color:#ff9500;">(Low)</span></div>
            <div data-label="Status"><span class="inv-status-badge low-stock">Restock</span></div>
            <div class="inv-action-group" data-label="Actions" style="text-align: right;">
                <button class="inv-icon-action view"><i class="fa-solid fa-eye"></i></button>
                <button class="inv-icon-action edit"><i class="fa-solid fa-pen"></i></button>
                <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="inv-card-row grid-products product-row" data-status="active">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #fff4e0; color: #ff9500;">
                    <i class="fa-solid fa-capsules"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Vitamin C 1000mg</div>
                    <div class="inv-product-generic">Ascorbic Acid</div>
                </div>
            </div>
            <div class="inv-text-main" data-label="SKU">885004455667</div>
            <div class="inv-text-sub" data-label="Location">Cab A, Shelf 3</div>
            <div class="inv-text-sub" data-label="Category">Supplements</div>
            <div class="inv-text-main" data-label="Price">฿350.00</div>
            <div class="inv-text-main" data-label="Stock">100 <span class="inv-text-sub">Btl.</span></div>
            <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
            <div class="inv-action-group" data-label="Actions" style="text-align: right;">
                <button class="inv-icon-action view"><i class="fa-solid fa-eye"></i></button>
                <button class="inv-icon-action edit"><i class="fa-solid fa-pen"></i></button>
                <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <!-- Item 4 -->
        <div class="inv-card-row grid-products product-row" data-status="active">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #f3e8ff; color: #af52de;">
                    <i class="fa-solid fa-mask"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">N95 Medical Mask</div>
                    <div class="inv-product-generic">Respirator Mask</div>
                </div>
            </div>
            <div class="inv-text-main" data-label="SKU">885001122334</div>
            <div class="inv-text-sub" data-label="Location">Cab C, Drawer 1</div>
            <div class="inv-text-sub" data-label="Category">Medical Devices</div>
            <div class="inv-text-main" data-label="Price">฿450.00</div>
            <div class="inv-text-main" data-label="Stock">25 <span class="inv-text-sub">Box</span></div>
            <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
            <div class="inv-action-group" data-label="Actions" style="text-align: right;">
                <button class="inv-icon-action view"><i class="fa-solid fa-eye"></i></button>
                <button class="inv-icon-action edit"><i class="fa-solid fa-pen"></i></button>
                <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <!-- Item 5 -->
        <div class="inv-card-row grid-products product-row" data-status="active">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #e5fbeB; color: #34c759;">
                    <i class="fa-solid fa-tablets"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Cetirizine 10mg</div>
                    <div class="inv-product-generic">Antihistamine</div>
                </div>
            </div>
            <div class="inv-text-main" data-label="SKU">885009988112</div>
            <div class="inv-text-sub" data-label="Location">Cab A, Shelf 1</div>
            <div class="inv-text-sub" data-label="Category">Household Med</div>
            <div class="inv-text-main" data-label="Price">฿60.00</div>
            <div class="inv-text-main" data-label="Stock">200 <span class="inv-text-sub">Strip</span></div>
            <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
            <div class="inv-action-group" data-label="Actions" style="text-align: right;">
                <button class="inv-icon-action view"><i class="fa-solid fa-eye"></i></button>
                <button class="inv-icon-action edit"><i class="fa-solid fa-pen"></i></button>
                <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    </div>
    
    <!-- SECTION: Inactive (Hidden by default) -->
    <div id="section-inactive" class="hidden">
        <div class="inv-card-row header grid-products">
            <div class="inv-checkbox"></div>
            <div class="inv-col-header">Discontinued Products</div>
        </div>
        <!-- Item 6 (Inactive) -->
        <div class="inv-card-row grid-products product-row" data-status="inactive">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #f5f5f7; color: #8e8e93;">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name" style="color: #8e8e93;">Old Cough Syrup</div>
                    <div class="inv-product-generic">Discontinued</div>
                </div>
            </div>
            <div class="inv-text-main" data-label="SKU" style="color:#aaa">885000000000</div>
            <div class="inv-text-sub" data-label="Location">-</div>
            <div class="inv-text-sub" data-label="Category">Medicine</div>
            <div class="inv-text-main" data-label="Price" style="color:#aaa">฿0.00</div>
            <div class="inv-text-main" data-label="Stock" style="color:#aaa">0</div>
            <div data-label="Status"><span class="inv-status-badge inactive">Inactive</span></div>
            <div class="inv-action-group" data-label="Actions" style="text-align: right;">
                <button class="inv-icon-action view"><i class="fa-solid fa-eye"></i></button>
                <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    </div>

    {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-5 of 2,450</span>
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

<!-- MODAL: New/Edit Product -->
<div class="inv-modal-overlay" id="modal-new-product">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <div class="inv-modal-title">Add New Product</div>
            <button class="inv-modal-close" onclick="closeModal('modal-new-product')">&times;</button>
        </div>
        <div class="inv-modal-body">
            <div class="inv-form-row">
                 <div class="inv-form-group" style="flex: 2;">
                    <label class="inv-form-label">Product Name</label>
                    <input type="text" class="inv-form-input" placeholder="Trade Name (e.g. Tylenol)">
                </div>
                <div class="inv-form-group" style="flex: 1;">
                     <label class="inv-form-label">Status</label>
                     <select class="inv-form-input"><option>Active</option><option>Inactive</option></select>
                </div>
            </div>
            <div class="inv-form-group">
                <label class="inv-form-label">Generic Name</label>
                <input type="text" class="inv-form-input" placeholder="Scientific Name">
            </div>
            <div class="inv-form-row">
                <div class="inv-form-group">
                    <label class="inv-form-label">SKU / Barcode</label>
                    <input type="text" class="inv-form-input" placeholder="Scan Barcode">
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label">Category</label>
                    <select class="inv-form-input"><option>Household Med</option><option>Dangerous Drugs</option></select>
                </div>
            </div>
            <div class="inv-form-row">
                <div class="inv-form-group">
                    <label class="inv-form-label">Price (Sell)</label>
                    <input type="number" class="inv-form-input" placeholder="0.00">
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label">Cost (Buy)</label>
                    <input type="number" class="inv-form-input" placeholder="0.00">
                </div>
            </div>
            <div class="inv-form-row">
                 <div class="inv-form-group">
                    <label class="inv-form-label">Stock Qty</label>
                    <input type="number" class="inv-form-input" placeholder="0">
                </div>
                 <div class="inv-form-group">
                    <label class="inv-form-label">Reorder Point</label>
                    <input type="number" class="inv-form-input" placeholder="Alert when < 10">
                </div>
            </div>
            <div class="inv-form-group">
                <label class="inv-form-label">Location (Shelf/Cabinet)</label>
                <input type="text" class="inv-form-input" placeholder="e.g. Cabinet A, Shelf 2">
            </div>
        </div>
        <div class="inv-modal-footer">
            <button class="inv-btn-secondary" onclick="closeModal('modal-new-product')">Cancel</button>
            <button class="inv-btn-primary">Save Product</button>
        </div>
    </div>
</div>

<!-- MODAL: View Product Details -->
<div class="inv-modal-overlay" id="modal-view-product">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <div class="inv-modal-title">Product Details</div>
            <button class="inv-modal-close" onclick="closeModal('modal-view-product')">&times;</button>
        </div>
        <div class="inv-modal-body" style="text-align: center;">
            <div style="width: 80px; height: 80px; background: #e5fbeB; border-radius: 20px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #34c759;">
                <i class="fa-solid fa-pills"></i>
            </div>
            <h2 style="margin: 0; color: var(--text-primary);">Paracetamol 500mg</h2>
            <p style="color: var(--text-secondary); margin-top: 4px;">Acetaminophen | SKU: 885001234567</p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px; text-align: left; background: #f9f9f9; padding: 20px; border-radius: 16px;">
                <div>
                    <div class="inv-text-sub">Stock Remaining</div>
                    <div class="inv-text-main" style="font-size: 1.2rem; font-weight: 700;">50 Jars</div>
                </div>
                 <div>
                    <div class="inv-text-sub">Selling Price</div>
                    <div class="inv-text-main" style="font-size: 1.2rem; font-weight: 700;">฿120.00</div>
                </div>
                 <div>
                    <div class="inv-text-sub">Location</div>
                    <div class="inv-text-main">Cabinet A, Shelf 2</div>
                </div>
                 <div>
                    <div class="inv-text-sub">Last Updated</div>
                    <div class="inv-text-main">18 Nov 2024</div>
                </div>
            </div>
        </div>
        <div class="inv-modal-footer">
            <button class="inv-btn-secondary" onclick="closeModal('modal-view-product')">Close</button>
            <button class="inv-btn-primary"><i class="fa-solid fa-pen"></i> Edit</button>
        </div>
    </div>
</div>

<script src="../../js/inventorys.js"></script>
</body>
</html>
</x-app-layout>
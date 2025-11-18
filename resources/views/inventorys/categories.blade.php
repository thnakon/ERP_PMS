<x-app-layout>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/inventorys.css">
</head>
<body>

<div class="inv-container">
    <div class="inv-breadcrumb-bar">Inventory > Categories</div>
    <div class="inv-header">
        <div class="inv-header-left">
            <h1 class="inv-page-title">Categories</h1>
        </div>
        <div class="inv-header-right">
            <button class="inv-btn-primary" onclick="openModal('modal-new-category')">
                <i class="fa-solid fa-layer-group"></i> New Category
            </button>
        </div>
    </div>

    <!-- Controls Row (Search & Bulk Actions) -->
    <div class="inv-filters-wrapper">
        <!-- Search -->
        <div style="position: relative;">
            <input type="text" placeholder="Search Categories..." class="inv-form-input" style="width: 280px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
        </div>

        <!-- Bulk Actions (Hidden by default) -->
        <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
            <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span id="selected-count">0</span></span>
            <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"><i class="fa-solid fa-trash"></i> Delete Selected</button>
        </div>
    </div>

    <!-- Table Header -->
    <div class="inv-card-row header grid-categories">
        <div class="inv-checkbox" id="select-all"></div>
        <div class="inv-col-header">Category Name</div>
        <div class="inv-col-header">Description</div>
        <div class="inv-col-header">Items</div>
        <div class="inv-col-header">Status</div>
        <div class="inv-col-header" style="text-align: right;">Actions</div>
    </div>

    <!-- Cat 1 -->
    <div class="inv-card-row grid-categories">
        <div class="inv-checkbox item-checkbox"></div>
        <div class="inv-product-info" data-label="Name">
            <div class="inv-product-name">Dangerous Drugs</div>
            <div class="inv-product-generic">ยาอันตราย</div>
        </div>
        <div class="inv-text-sub" data-label="Desc">Requires pharmacist supervision</div>
        <div class="inv-text-main" data-label="Items">154 Items</div>
        <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
        <div class="inv-action-group" data-label="Actions" style="text-align: right;">
             <button class="inv-icon-action edit" onclick="openModal('modal-new-category')"><i class="fa-solid fa-pen"></i></button>
             <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>

    <!-- Cat 2 -->
    <div class="inv-card-row grid-categories">
        <div class="inv-checkbox item-checkbox"></div>
        <div class="inv-product-info" data-label="Name">
            <div class="inv-product-name">Household Medicine</div>
            <div class="inv-product-generic">ยาสามัญประจำบ้าน</div>
        </div>
        <div class="inv-text-sub" data-label="Desc">Common safe drugs</div>
        <div class="inv-text-main" data-label="Items">89 Items</div>
        <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
        <div class="inv-action-group" data-label="Actions" style="text-align: right;">
             <button class="inv-icon-action edit" onclick="openModal('modal-new-category')"><i class="fa-solid fa-pen"></i></button>
             <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>

    <!-- Cat 3 -->
    <div class="inv-card-row grid-categories">
        <div class="inv-checkbox item-checkbox"></div>
        <div class="inv-product-info" data-label="Name">
            <div class="inv-product-name">Supplements</div>
            <div class="inv-product-generic">อาหารเสริม</div>
        </div>
        <div class="inv-text-sub" data-label="Desc">Vitamins and minerals</div>
        <div class="inv-text-main" data-label="Items">45 Items</div>
        <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
        <div class="inv-action-group" data-label="Actions" style="text-align: right;">
             <button class="inv-icon-action edit" onclick="openModal('modal-new-category')"><i class="fa-solid fa-pen"></i></button>
             <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>

    <!-- Cat 4 (New) -->
    <div class="inv-card-row grid-categories">
        <div class="inv-checkbox item-checkbox"></div>
        <div class="inv-product-info" data-label="Name">
            <div class="inv-product-name">Medical Devices</div>
            <div class="inv-product-generic">อุปกรณ์การแพทย์</div>
        </div>
        <div class="inv-text-sub" data-label="Desc">Tools and equipment</div>
        <div class="inv-text-main" data-label="Items">210 Items</div>
        <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
        <div class="inv-action-group" data-label="Actions" style="text-align: right;">
             <button class="inv-icon-action edit" onclick="openModal('modal-new-category')"><i class="fa-solid fa-pen"></i></button>
             <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>

    <!-- Cat 5 (New) -->
    <div class="inv-card-row grid-categories">
        <div class="inv-checkbox item-checkbox"></div>
        <div class="inv-product-info" data-label="Name">
            <div class="inv-product-name">Cosmetics & Skincare</div>
            <div class="inv-product-generic">เวชสำอาง</div>
        </div>
        <div class="inv-text-sub" data-label="Desc">Dermatologically tested</div>
        <div class="inv-text-main" data-label="Items">120 Items</div>
        <div data-label="Status"><span class="inv-status-badge active">Active</span></div>
        <div class="inv-action-group" data-label="Actions" style="text-align: right;">
             <button class="inv-icon-action edit" onclick="openModal('modal-new-category')"><i class="fa-solid fa-pen"></i></button>
             <button class="inv-icon-action delete"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>

    {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-5 of 15</span>
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

<!-- MODAL: New Category -->
<div class="inv-modal-overlay" id="modal-new-category">
    <div class="inv-modal">
        <div class="inv-modal-header">
            <div class="inv-modal-title">Add Category</div>
            <button class="inv-modal-close" onclick="closeModal('modal-new-category')">&times;</button>
        </div>
        <div class="inv-modal-body">
            <div class="inv-form-group">
                <label class="inv-form-label">Category Name</label>
                <input type="text" class="inv-form-input" placeholder="e.g. Cosmetics">
            </div>
            <div class="inv-form-group">
                <label class="inv-form-label">Description</label>
                <textarea class="inv-form-input" rows="3" placeholder="Optional details..."></textarea>
            </div>
            <div class="inv-form-group">
                 <label class="inv-form-label">Status</label>
                 <select class="inv-form-input"><option>Active</option><option>Inactive</option></select>
            </div>
        </div>
        <div class="inv-modal-footer">
            <button class="inv-btn-secondary" onclick="closeModal('modal-new-category')">Cancel</button>
            <button class="inv-btn-primary">Save Category</button>
        </div>
    </div>
</div>

<script src="../../js/inventorys.js"></script>
</body>
</html>
</x-app-layout>
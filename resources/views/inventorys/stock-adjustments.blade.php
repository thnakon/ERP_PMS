<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stock Adjustments</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/inventorys.css') }}">
    </head>
    <body>

    <div class="inv-container">
        <div class="inv-breadcrumb-bar">
    Dashboard / Inventory /
     <a href="{{ route('inventorys.expiry-management') }}" style="color: #017aff"> Expiry Management </a> < <span style="color: #3a3a3c; font-weight: 600;">Stock Adjustments</span>
</div>
        
        <!-- Header -->
        <div class="inv-header">
            <div class="inv-header-left">
                <h1 class="inv-page-title">Stock Adjustments</h1>
            </div>
            <div class="inv-header-right">
                <button class="inv-btn-secondary">
                    <i class="fa-solid fa-file-arrow-down"></i> Export Log
                </button>
                <div style="width: 1px; height: 24px; background-color: #d2d2d7; margin: 0 4px;"></div>
                <button class="inv-btn-primary" onclick="openModal('modal-adjustment')">
                    <i class="fa-solid fa-sliders"></i> New Adjustment
                </button>
            </div>
        </div>

        <!-- Controls: Search & Bulk Actions -->
        <div class="inv-filters-wrapper">
            <!-- Bulk Actions (Hidden by default) -->
            <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
                <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span id="selected-count">0</span></span>
                <button class="inv-btn-secondary" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-file-export"></i> Export Selected
                </button>
            </div>
        </div>

        <!-- Header -->
        <div class="inv-card-row header grid-adjustments">
            <div class="inv-checkbox" id="select-all"></div>
            <div class="inv-col-header">Date</div>
            <div class="inv-col-header">Product</div>
            <div class="inv-col-header">Type</div>
            <div class="inv-col-header">Qty</div>
            <div class="inv-col-header">Reason</div>
            <div class="inv-col-header" style="text-align: right;">User</div>
        </div>

        <!-- Log 1: Broken Item -->
        <div class="inv-card-row grid-adjustments">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-text-sub" data-label="Date">18 Nov 10:30</div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #f2f2f7; color: #8e8e93;">
                    <i class="fa-solid fa-temperature-half"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Glass Thermometer</div>
                    <div class="inv-product-generic">Mercury Free</div>
                </div>
            </div>
            <div data-label="Type"><span class="inv-status-badge expired" style="background-color: #ffeaea; color: #ff3b30;">Remove (-)</span></div>
            <div class="inv-text-main" data-label="Qty" style="color: #ff3b30;">-1</div>
            <div class="inv-text-sub" data-label="Reason">Broken in storage (Cabinet A)</div>
            <div class="inv-text-sub" data-label="User" style="text-align: right;">John Pharm</div>
        </div>

        <!-- Log 2: Found Extra Stock -->
        <div class="inv-card-row grid-adjustments">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-text-sub" data-label="Date">17 Nov 14:15</div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #e5fbeB; color: #34c759;">
                    <i class="fa-solid fa-mask"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Surgical Mask (Box)</div>
                    <div class="inv-product-generic">50 pcs/box</div>
                </div>
            </div>
            <div data-label="Type"><span class="inv-status-badge active" style="background-color: #e5fbeB; color: #008a2a;">Add (+)</span></div>
            <div class="inv-text-main" data-label="Qty" style="color: #008a2a;">+5</div>
            <div class="inv-text-sub" data-label="Reason">Found unlisted stock</div>
            <div class="inv-text-sub" data-label="User" style="text-align: right;">Admin</div>
        </div>

        <!-- Log 3: Expired Disposal -->
        <div class="inv-card-row grid-adjustments">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-text-sub" data-label="Date">15 Nov 09:00</div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #e6f7ff; color: #007aff;">
                    <i class="fa-solid fa-pump-soap"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Hand Sanitizer Gel</div>
                    <div class="inv-product-generic">Alcohol 70%</div>
                </div>
            </div>
            <div data-label="Type"><span class="inv-status-badge expired" style="background-color: #ffeaea; color: #ff3b30;">Remove (-)</span></div>
            <div class="inv-text-main" data-label="Qty" style="color: #ff3b30;">-12</div>
            <div class="inv-text-sub" data-label="Reason">Expired (Lot A-001)</div>
            <div class="inv-text-sub" data-label="User" style="text-align: right;">John Pharm</div>
        </div>

        <!-- Log 4: Count Correction -->
        <div class="inv-card-row grid-adjustments">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-text-sub" data-label="Date">14 Nov 18:45</div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #fff4e0; color: #ff9500;">
                    <i class="fa-solid fa-pills"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Vitamin C 500mg</div>
                    <div class="inv-product-generic">Ascorbic Acid</div>
                </div>
            </div>
            <div data-label="Type"><span class="inv-status-badge low-stock" style="background-color: #fff4e0; color: #ff9500;">Adjust (+/-)</span></div>
            <div class="inv-text-main" data-label="Qty" style="color: #ff9500;">-2</div>
            <div class="inv-text-sub" data-label="Reason">Count correction (Audit)</div>
            <div class="inv-text-sub" data-label="User" style="text-align: right;">Manager</div>
        </div>

        <!-- Log 5: Internal Use -->
        <div class="inv-card-row grid-adjustments">
            <div class="inv-checkbox item-checkbox"></div>
            <div class="inv-text-sub" data-label="Date">12 Nov 11:20</div>
            <div class="inv-product-info" data-label="Product">
                <div class="inv-product-icon" style="background-color: #f3e8ff; color: #af52de;">
                    <i class="fa-solid fa-bandage"></i>
                </div>
                <div class="inv-product-details">
                    <div class="inv-product-name">Elastic Bandage</div>
                    <div class="inv-product-generic">Size M</div>
                </div>
            </div>
            <div data-label="Type"><span class="inv-status-badge expired" style="background-color: #ffeaea; color: #ff3b30;">Remove (-)</span></div>
            <div class="inv-text-main" data-label="Qty" style="color: #ff3b30;">-1</div>
            <div class="inv-text-sub" data-label="Reason">Internal use (Staff injury)</div>
            <div class="inv-text-sub" data-label="User" style="text-align: right;">Admin</div>
        </div>

        {{-- [!!! PAGINATION !!!] --}}
        <div class="people-pagination">
            <span class="pagination-text">1-5 of 27</span>
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

    <!-- MODAL: New Adjustment -->
    <div class="inv-modal-overlay" id="modal-adjustment">
        <div class="inv-modal">
            <div class="inv-modal-header">
                <div class="inv-modal-title">New Stock Adjustment</div>
                <button class="inv-modal-close" onclick="closeModal('modal-adjustment')">&times;</button>
            </div>
            <div class="inv-modal-body">
                <div class="inv-form-group">
                    <label class="inv-form-label">Select Product</label>
                    <input type="text" class="inv-form-input" placeholder="Search name or scan barcode...">
                </div>
                <div class="inv-form-row">
                    <div class="inv-form-group">
                        <label class="inv-form-label">Adjustment Type</label>
                        <select class="inv-form-input">
                            <option value="remove" style="color: red;">Remove (-)</option>
                            <option value="add" style="color: green;">Add (+)</option>
                            <option value="count">Count Correction</option>
                        </select>
                    </div>
                    <div class="inv-form-group">
                        <label class="inv-form-label">Quantity</label>
                        <input type="number" class="inv-form-input" placeholder="0">
                    </div>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label">Reason</label>
                    <textarea class="inv-form-input" rows="3" placeholder="e.g. Broken in storage, Expired batch L-2201, Found during audit"></textarea>
                </div>
                <div class="inv-form-group">
                    <label class="inv-form-label">Note (Optional)</label>
                    <input type="text" class="inv-form-input" placeholder="Additional details">
                </div>
            </div>
            <div class="inv-modal-footer">
                <button class="inv-btn-secondary" onclick="closeModal('modal-adjustment')">Cancel</button>
                <button class="inv-btn-primary">Confirm Adjustment</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/inventorys.js') }}"></script>
    </body>
    </html>
</x-app-layout>
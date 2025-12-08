<x-app-layout>
    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Manage Products</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../../css/inventorys.css">
        <style>
            /* Apple-style Flash Message */
            .flash-message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 12px 24px;
                border-radius: 99px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 9999;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                font-size: 14px;
                font-weight: 500;
                color: #333;
                opacity: 0;
                transition: opacity 0.3s ease, transform 0.3s ease;
                pointer-events: none;
            }

            .flash-message.show {
                opacity: 1;
                transform: translateX(-50%) translateY(10px);
            }

            .flash-message.success i {
                color: #34c759;
            }

            .flash-message.error i {
                color: #ff3b30;
            }

            /* Apple-style Pagination */
            .people-pagination {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 24px;
                border-top: 1px solid #e5e5ea;
                margin-top: auto;
                background-color: #fff;
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            .pagination-text {
                font-size: 13px;
                color: #8e8e93;
                font-weight: 500;
            }

            .pagination-controls {
                display: flex;
                gap: 8px;
            }

            .pagination-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 1px solid #e5e5ea;
                background-color: #fff;
                color: #007aff;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
                font-size: 12px;
            }

            .pagination-btn:hover:not(.disabled) {
                background-color: #f2f2f7;
                border-color: #d1d1d6;
            }

            .pagination-btn.disabled {
                color: #c7c7cc;
                cursor: not-allowed;
                border-color: #f2f2f7;
                background-color: #fff;
            }

            /* Section Headers in Modal */
            .modal-section-title {
                font-size: 14px;
                font-weight: 600;
                color: #1d1d1f;
                margin-bottom: 12px;
                margin-top: 20px;
                padding-bottom: 8px;
                border-bottom: 1px solid #e5e5ea;
            }

            .modal-section-title:first-child {
                margin-top: 0;
            }
        </style>
    </head>

    <body>

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message">
            <i class="fa-solid fa-check-circle"></i>
            <span id="flash-text">Operation successful</span>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ session('success') }}", 'success');
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ $errors->first() }}", 'error');
                });
            </script>
        @endif

        <script>
            window.assetUrl = "{{ asset('') }}";
        </script>

        <div class="inv-container fade-in">
            <div class="inv-breadcrumb-bar">
                Dashboard / Inventory /
                <span style="color: #3a3a3c; font-weight: 600;">Manage Products</span> > <a
                    href="{{ route('inventorys.categories') }}" style="color: #017aff"> Categories </a>
            </div>
            <div class="inv-header">
                <div class="inv-header-left">
                    <h1 class="inv-page-title">Manage Products <span
                            style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">({{ $products->total() }})</span>
                    </h1>
                </div>
                <div class="inv-header-right">
                    <button class="inv-btn-primary" onclick="openNewModal()">
                        <i class="fa-solid fa-tablets"></i> New Product
                    </button>
                </div>
            </div>

            <!-- Controls Row (Search & Bulk Actions) -->
            <div class="inv-filters-wrapper">
                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('inventorys.manage-products') }}" class="inv-search-form"
                    style="display: flex; gap: 10px; align-items: center;">

                    <!-- Sort Filter -->
                    <select name="sort" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                        </option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                        </option>
                    </select>

                    <!-- Category Filter -->
                    <select name="category" class="inv-form-input" style="width: 220px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Search Input -->
                    <div style="position: relative;">
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            placeholder="Search Name, SKU..." class="inv-form-input"
                            style="width: 280px; height: 44px; padding-left: 40px;">
                        <i class="fa-solid fa-magnifying-glass"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
                    </div>
                </form>

                <!-- Bulk Actions (Hidden by default) -->
                <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
                    <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span
                            id="selected-count">0</span></span>
                    <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"
                        onclick="confirmBulkDelete()"><i class="fa-solid fa-trash"></i> Delete Selected</button>
                </div>
            </div>

            <!-- VIEW LIST CONTAINER -->
            <div id="view-list" class="transition-opacity duration-300">
                <!-- Table Header -->
                <div class="inv-card-row header grid-products"
                    style="grid-template-columns: 40px 50px 60px 3fr 1.5fr 1.5fr 1fr 1fr 1fr 130px; 
                       padding: 0 16px; 
                       margin-bottom: 10px; 
                       background: transparent; 
                       border: none;">
                    <div class="inv-checkbox-wrapper">
                        <input type="checkbox" class="inv-checkbox" id="select-all">
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">#</div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Image
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Product
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Category
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                        SKU/Barcode
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Price
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Stock
                    </div>
                    <div class="inv-col-header"
                        style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">Status
                    </div>
                    <div class="inv-col-header"
                        style="text-align: right; font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                        Actions</div>
                </div>

                <!-- Products Loop -->
                @forelse($products as $index => $product)
                    <div class="inv-card-row grid-products product-row"
                        style="grid-template-columns: 40px 50px 60px 3fr 1.5fr 1.5fr 1fr 1fr 1fr 130px; 
                           background: #fff; 
                           border-radius: 22px; 
                           margin-bottom: 8px; 
                           padding: 16px 16px;
                           box-shadow: 0 2px 6px rgba(0,0,0,0.02);
                           border: 1px solid #f5f5f7;
                           transition: all 0.2s ease;"
                        onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.02)'">

                        <div class="inv-checkbox-wrapper">
                            <input type="checkbox" class="inv-checkbox item-checkbox" data-id="{{ $product->id }}">
                        </div>

                        <div class="inv-text-sub" style="font-weight: 500; font-size: 13px; color: #8e8e93;">
                            {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                        </div>

                        <!-- Image Column -->
                        <div class="inv-product-image"
                            style="display: flex; align-items: center; justify-content: center;">
                            @if ($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                    style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e5ea;">
                            @else
                                <div
                                    style="width: 40px; height: 40px; border-radius: 8px; background-color: #f2f2f7; display: flex; align-items: center; justify-content: center; color: #8e8e93;">
                                    <i class="fa-solid fa-image" style="font-size: 16px;"></i>
                                </div>
                            @endif
                        </div>

                        <div class="inv-product-info" data-label="Product">
                            <div class="inv-product-details">
                                <div class="inv-product-name"
                                    style="font-size: 14px; font-weight: 600; color: #1d1d1f;">
                                    {{ $product->name }}</div>
                                <div class="inv-product-generic" style="font-size: 12px; color: #86868b;">
                                    {{ $product->generic_name ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="inv-text-sub" data-label="Category" style="font-size: 13px; color: #424245;">
                            {{ $product->category->name ?? '-' }}
                        </div>

                        <div class="inv-text-sub" data-label="SKU" style="font-size: 13px; color: #86868b;">
                            {{ $product->barcode ?? '-' }}
                        </div>

                        <div class="inv-text-main" data-label="Price"
                            style="font-size: 13px; font-weight: 600; color: #1d1d1f;">
                            ฿{{ number_format($product->selling_price, 2) }}
                        </div>

                        <div class="inv-text-main" data-label="Stock"
                            style="font-size: 13px; font-weight: 600; color: #1d1d1f;">
                            {{ $product->total_stock }} <span
                                style="font-size: 11px; color: #86868b; font-weight: 400;">Units</span>
                        </div>

                        <div data-label="Status">
                            <span class="inv-status-badge {{ $product->is_active ? 'active' : 'inactive' }}"
                                style="font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: 600; background-color: {{ $product->is_active ? '#e5fbeB' : '#f5f5f7' }}; color: {{ $product->is_active ? '#34c759' : '#8e8e93' }};">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="inv-action-group" data-label="Actions"
                            style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <button class="inv-icon-action"
                                onclick="openViewModal({{ json_encode($product->load('category')) }})"
                                style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                onmouseover="this.style.color='#007aff'" onmouseout="this.style.color='#86868b'">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="inv-icon-action" onclick="openEditModal({{ json_encode($product) }})"
                                style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                onmouseover="this.style.color='#ff9500'" onmouseout="this.style.color='#86868b'">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="inv-icon-action btn-delete-row"
                                onclick="confirmDelete({{ $product->id }})"
                                style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                onmouseover="this.style.color='#ff3b30'" onmouseout="this.style.color='#86868b'">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="inv-card-row"
                        style="justify-content: center; padding: 40px; background: #fff; border-radius: 12px; margin-bottom: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                        <div style="text-align: center;">
                            <i class="fa-solid fa-box-open"
                                style="font-size: 48px; color: #e5e5ea; margin-bottom: 16px;"></i>
                            <div class="inv-text-sub" style="font-size: 16px;">No products found</div>
                        </div>
                    </div>
                @endforelse

                {{-- Pagination --}}
                {{ $products->onEachSide(1)->links('vendor.pagination.apple') }}
            </div>

        </div>

        <!-- MODAL: New/Edit Product -->
        <div class="inv-modal-overlay" id="modal-new-product">
            <div class="inv-modal" style="max-width: 700px; border-radius: 24px; padding: 0; overflow: hidden;">
                <form id="product-form" method="POST" action="{{ route('inventorys.products.store') }}"
                    enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; height: 85vh; max-height: 800px;">
                    @csrf
                    <div id="method-spoof"></div> <!-- For PUT method -->

                    <div class="inv-modal-header" style="padding: 24px 32px; border-bottom: none;">
                        <div class="inv-modal-title" id="modal-title" style="font-size: 24px; font-weight: 700;">Add
                            New Product</div>
                        <button type="button" class="inv-modal-close" onclick="closeModal('modal-new-product')"
                            style="font-size: 28px; color: #86868b;">&times;</button>
                    </div>

                    <div class="inv-modal-body" style="padding: 0 32px 32px; overflow-y: auto; flex: 1;">

                        <!-- Image Upload -->
                        <div class="inv-form-group" style="text-align: center; margin-bottom: 32px;">
                            <div class="inv-image-upload-box"
                                style="width: 140px; height: 140px; margin: 0 auto; border-radius: 24px; border: 2px dashed #d2d2d7; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; background: #fbfbfd; transition: all 0.2s;"
                                onclick="document.getElementById('product-image-input').click()"
                                onmouseover="this.style.borderColor='#007aff'; this.style.background='#f2f2f7'"
                                onmouseout="this.style.borderColor='#d2d2d7'; this.style.background='#fbfbfd'">
                                <img id="inv-image-preview" src=""
                                    style="display: none; width: 100%; height: 100%; object-fit: cover;">
                                <div id="inv-image-placeholder" style="color: #8e8e93; text-align: center;">
                                    <i class="fa-solid fa-camera"
                                        style="font-size: 28px; margin-bottom: 8px; color: #86868b;"></i>
                                    <div style="font-size: 13px; font-weight: 500;">Add Photo</div>
                                </div>
                                <input type="file" name="image" id="product-image-input" accept="image/*"
                                    style="display: none;" onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- 1. Core Identification -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            1. Core Identification</div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Brand
                                    Name (Trade Name)</label>
                                <input type="text" name="name" id="prod-name" class="inv-form-input"
                                    placeholder="e.g. Tylenol" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Generic
                                    Name</label>
                                <input type="text" name="generic_name" id="prod-generic" class="inv-form-input"
                                    placeholder="e.g. Paracetamol"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 32px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Strength</label>
                                <input type="text" name="strength" id="prod-strength" class="inv-form-input"
                                    placeholder="e.g. 500 mg"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Dosage
                                    Form</label>
                                <input type="text" name="dosage_form" id="prod-dosage" class="inv-form-input"
                                    placeholder="e.g. Tablet"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>

                        <!-- 2. Administrative & Financial -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            2. Administrative & Financial</div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Registration
                                    No. (Reg. No.)</label>
                                <input type="text" name="registration_number" id="prod-reg-no"
                                    class="inv-form-input" placeholder="e.g. 1A 123/45"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Category</label>
                                <select name="category_id" id="prod-category" class="inv-form-input" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; background-color: #fff;">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Cost
                                    Price (Buy)</label>
                                <input type="number" step="0.01" name="cost_price" id="prod-cost"
                                    class="inv-form-input" placeholder="0.00" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Selling
                                    Price (Sell)</label>
                                <input type="number" step="0.01" name="selling_price" id="prod-sell"
                                    class="inv-form-input" placeholder="0.00" required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 32px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">SKU
                                    / Barcode</label>
                                <input type="text" name="barcode" id="prod-barcode" class="inv-form-input"
                                    placeholder="Scan or Enter"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Status</label>
                                <select name="status" id="prod-status" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; background-color: #fff;">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3. Safety and Regulatory -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            3. Safety and Regulatory</div>
                        <div class="inv-form-group" style="margin-bottom: 20px;">
                            <label class="inv-form-label"
                                style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Primary
                                Indication</label>
                            <input type="text" name="primary_indication" id="prod-indication"
                                class="inv-form-input" placeholder="e.g. Pain Relief, Fever"
                                style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                        </div>
                        <div class="inv-form-group" style="margin-bottom: 20px;">
                            <label class="inv-form-label"
                                style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Regulatory
                                Class</label>
                            <select name="regulatory_class" id="prod-class" class="inv-form-input"
                                style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; background-color: #fff;">
                                <option value="OTC">Over-the-Counter (OTC)</option>
                                <option value="Prescription (Rx)">Prescription (Rx)</option>
                                <option value="Controlled">Controlled Substance</option>
                                <option value="Medical Device">Medical Device</option>
                                <option value="Supplement">Supplement</option>
                                <option value="Cosmetic">Cosmetic</option>
                                <option value="Non-Regulated">Non-Regulated</option>
                            </select>
                        </div>

                    </div>
                    <div class="inv-modal-footer"
                        style="padding: 24px 32px; border-top: 1px solid #e5e5ea; display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="button" class="inv-btn-secondary" onclick="closeModal('modal-new-product')"
                            style="border-radius: 99px; padding: 12px 24px; font-weight: 600; border: 1px solid #d2d2d7; color: #1d1d1f; background: #fff;">Cancel</button>
                        <button type="submit" class="inv-btn-primary" id="modal-submit-btn"
                            style="border-radius: 99px; padding: 12px 24px; font-weight: 600; background: #007aff; color: #fff; border: none;">Save
                            Product</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: View Product Details -->
        <div class="inv-modal-overlay" id="modal-view-product">
            <div class="inv-modal" style="max-width: 600px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title">Product Details</div>
                    <button type="button" class="inv-modal-close"
                        onclick="closeModal('modal-view-product')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <div id="view-prod-image-container"
                            style="width: 80px; height: 80px; background: #e5fbeB; border-radius: 20px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #34c759;">
                            <i class="fa-solid fa-pills"></i>
                        </div>
                        <h2 id="view-prod-name" style="margin: 0; color: #1d1d1f; font-size: 20px;">-</h2>
                        <p id="view-prod-generic" style="color: #86868b; margin-top: 4px; font-size: 14px;">-</p>
                        <div style="margin-top: 8px;">
                            <span id="view-prod-status" class="inv-status-badge"></span>
                        </div>
                    </div>

                    <div style="background: #f5f5f7; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                        <div class="modal-section-title" style="margin-top: 0;">Core Info</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                            <div><span style="color: #86868b;">Strength:</span> <span id="view-prod-strength"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Dosage Form:</span> <span id="view-prod-dosage"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Category:</span> <span id="view-prod-category"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Barcode:</span> <span id="view-prod-barcode"
                                    style="font-weight: 500;">-</span></div>
                        </div>
                    </div>

                    <div style="background: #f5f5f7; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                        <div class="modal-section-title" style="margin-top: 0;">Financial & Admin</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                            <div><span style="color: #86868b;">Cost Price:</span> <span id="view-prod-cost"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Selling Price:</span> <span id="view-prod-sell"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Reg. No.:</span> <span id="view-prod-reg"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Stock:</span> <span id="view-prod-stock"
                                    style="font-weight: 500;">-</span></div>
                        </div>
                    </div>

                    <div style="background: #f5f5f7; border-radius: 12px; padding: 16px;">
                        <div class="modal-section-title" style="margin-top: 0;">Regulatory</div>
                        <div style="display: grid; grid-template-columns: 1fr; gap: 8px; font-size: 14px;">
                            <div><span style="color: #86868b;">Indication:</span> <span id="view-prod-indication"
                                    style="font-weight: 500;">-</span></div>
                            <div><span style="color: #86868b;">Class:</span> <span id="view-prod-class"
                                    style="font-weight: 500;">-</span></div>
                        </div>
                    </div>
                </div>
                <div class="inv-modal-footer">
                    <button type="button" class="inv-btn-secondary"
                        onclick="closeModal('modal-view-product')">Close</button>
                </div>
            </div>
        </div>

        <!-- MODAL: Delete Confirmation -->
        <div class="inv-modal-overlay" id="modal-delete">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" style="color: #ff3b30;">Delete Product</div>
                    <button class="inv-modal-close" onclick="closeModal('modal-delete')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="delete-confirm-text">Are you sure you want to delete this product? This action cannot be
                        undone.</p>
                </div>
                <div class="inv-modal-footer">
                    <button class="inv-btn-secondary" onclick="closeModal('modal-delete')">Cancel</button>

                    {{-- Single Delete Form --}}
                    <form id="delete-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary"
                            style="background-color: #ff3b30; border-color: #ff3b30;">Delete</button>
                    </form>

                    {{-- Bulk Delete Button --}}
                    <button id="btn-bulk-delete" type="button" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; display: none;"
                        onclick="executeBulkDelete()">Delete</button>
                </div>
            </div>
        </div>

        <script src="../../js/inventorys.js"></script>
        <script>
            // --- Flash Message Logic ---
            function showFlash(message, type = 'success') {
                const flash = document.getElementById('flash-message');
                const text = document.getElementById('flash-text');
                const icon = flash.querySelector('i');

                text.textContent = message;
                flash.className = 'flash-message show ' + type;

                if (type === 'success') {
                    icon.className = 'fa-solid fa-check-circle';
                } else {
                    icon.className = 'fa-solid fa-circle-exclamation';
                }

                setTimeout(() => {
                    flash.classList.remove('show');
                }, 3000);
            }

            // --- Modal Logic ---
            function openNewModal() {
                document.getElementById('modal-title').textContent = 'Add New Product';
                document.getElementById('modal-submit-btn').textContent = 'Save Product';
                document.getElementById('product-form').action = "{{ route('inventorys.products.store') }}";
                document.getElementById('method-spoof').innerHTML = ''; // Clear PUT

                // Clear inputs
                document.getElementById('product-form').reset();
                document.getElementById('inv-image-preview').style.display = 'none';
                document.getElementById('inv-image-placeholder').style.display = 'block';

                openModal('modal-new-product');
            }

            function openEditModal(product) {
                document.getElementById('modal-title').textContent = 'Edit Product';
                document.getElementById('modal-submit-btn').textContent = 'Update Product';
                document.getElementById('product-form').action = "/inventorys/products/" + product.id;
                document.getElementById('method-spoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // Fill inputs
                document.getElementById('prod-name').value = product.name;
                document.getElementById('prod-generic').value = product.generic_name || '';
                document.getElementById('prod-strength').value = product.strength || '';
                document.getElementById('prod-dosage').value = product.dosage_form || '';
                document.getElementById('prod-reg-no').value = product.registration_number || '';
                document.getElementById('prod-category').value = product.category_id;
                document.getElementById('prod-cost').value = product.cost_price || '';
                document.getElementById('prod-sell').value = product.selling_price || '';
                document.getElementById('prod-barcode').value = product.barcode || '';
                document.getElementById('prod-status').value = product.is_active ? 'Active' : 'Inactive';
                document.getElementById('prod-indication').value = product.primary_indication || '';
                document.getElementById('prod-class').value = product.regulatory_class || 'OTC';

                // Handle Image Preview
                const preview = document.getElementById('inv-image-preview');
                const placeholder = document.getElementById('inv-image-placeholder');

                if (product.image_path) {
                    preview.src = product.image_path;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                    placeholder.style.display = 'block';
                }

                openModal('modal-new-product');
            }

            function openViewModal(product) {
                document.getElementById('view-prod-name').textContent = product.name;
                document.getElementById('view-prod-generic').textContent = product.generic_name || '-';
                document.getElementById('view-prod-status').textContent = product.is_active ? 'Active' : 'Inactive';
                document.getElementById('view-prod-status').className = 'inv-status-badge ' + (product.is_active ? 'active' :
                    'inactive');

                document.getElementById('view-prod-strength').textContent = product.strength || '-';
                document.getElementById('view-prod-dosage').textContent = product.dosage_form || '-';
                document.getElementById('view-prod-category').textContent = product.category ? product.category.name : '-';
                document.getElementById('view-prod-barcode').textContent = product.barcode || '-';

                document.getElementById('view-prod-cost').textContent = '฿' + (product.cost_price || '0.00');
                document.getElementById('view-prod-sell').textContent = '฿' + (product.selling_price || '0.00');
                document.getElementById('view-prod-reg').textContent = product.registration_number || '-';
                document.getElementById('view-prod-stock').textContent = product.total_stock || '0';

                document.getElementById('view-prod-indication').textContent = product.primary_indication || '-';
                document.getElementById('view-prod-class').textContent = product.regulatory_class || '-';

                // Handle Image in View Modal
                const viewImageContainer = document.getElementById('view-prod-image-container');
                if (product.image_path) {
                    viewImageContainer.innerHTML =
                        `<img src="${product.image_path}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;">`;
                    viewImageContainer.style.background = 'transparent';
                } else {
                    viewImageContainer.innerHTML = `<i class="fa-solid fa-pills"></i>`;
                    viewImageContainer.style.background = '#e5fbeB';
                }

                openModal('modal-view-product');
            }

            function confirmDelete(id) {
                document.getElementById('delete-form').style.display = 'inline';
                document.getElementById('btn-bulk-delete').style.display = 'none';
                document.getElementById('delete-confirm-text').textContent =
                    'Are you sure you want to delete this product? This action cannot be undone.';
                document.getElementById('delete-form').action = "/inventorys/products/" + id;
                openModal('modal-delete');
            }

            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
            }

            function previewImage(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('inv-image-preview');
                        const placeholder = document.getElementById('inv-image-placeholder');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // --- Bulk Actions Logic ---
            const selectAll = document.getElementById('select-all');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCountSpan = document.getElementById('selected-count');

            function updateBulkActions() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count > 0) {
                    bulkActions.style.display = 'flex';
                    selectedCountSpan.textContent = count;
                } else {
                    bulkActions.style.display = 'none';
                }
            }

            function initializeBulkListeners() {
                const selectAll = document.getElementById('select-all');

                if (selectAll) {
                    selectAll.onchange = function() {
                        const isChecked = this.checked;
                        document.querySelectorAll('.item-checkbox').forEach(cb => {
                            cb.checked = isChecked;
                        });
                        updateBulkActions();
                    };
                }

                document.querySelectorAll('.item-checkbox').forEach(cb => {
                    cb.onchange = function() {
                        updateBulkActions();
                        if (selectAll) {
                            const allChecked = document.querySelectorAll('.item-checkbox:checked').length ===
                                document
                                .querySelectorAll('.item-checkbox').length;
                            selectAll.checked = allChecked;
                        }
                    };
                });
            }

            // Initial bind
            initializeBulkListeners();

            // --- Real-time Search ---
            const searchInput = document.getElementById('search-input');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value;
                    const url = new URL(window.location.href);

                    if (query.length > 0) {
                        url.searchParams.set('search', query);
                        url.searchParams.delete('page'); // Reset to page 1
                    } else {
                        url.searchParams.delete('search');
                    }

                    window.history.pushState({}, '', url);

                    searchTimeout = setTimeout(() => {
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                // Replace the list view
                                const newContent = doc.getElementById('view-list').innerHTML;
                                document.getElementById('view-list').innerHTML = newContent;

                                // Re-initialize listeners
                                initializeBulkListeners();
                            })
                            .catch(err => console.error('Search error:', err));
                    }, 400); // 400ms debounce
                });
            }

            function confirmBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count === 0) return;

                document.getElementById('delete-form').style.display = 'none';
                document.getElementById('btn-bulk-delete').style.display = 'inline-block';
                document.getElementById('delete-confirm-text').textContent =
                    `Are you sure you want to delete ${count} selected products? This action cannot be undone.`;

                openModal('modal-delete');
            }

            function executeBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.dataset.id);

                if (ids.length === 0) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Disable button to prevent double submit
                const btn = document.getElementById('btn-bulk-delete');
                const originalText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Deleting...';

                fetch('{{ route('inventorys.products.bulk-delete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const text = await res.text();
                            throw new Error(text || res.statusText);
                        }
                        return res.json();
                    })
                    .then(data => {
                        closeModal('modal-delete');
                        if (data.success) {
                            showFlash(data.message, 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showFlash(data.message || 'Error deleting products', 'error');
                            btn.disabled = false;
                            btn.textContent = originalText;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        closeModal('modal-delete');
                        showFlash('An error occurred: ' + err.message, 'error');
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            }
        </script>
    </body>

    </html>
</x-app-layout>

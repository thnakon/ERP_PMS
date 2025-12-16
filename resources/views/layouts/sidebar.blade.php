<div id="sidebar" class="apple-sidebar">

    <div class="sidebar-search-container">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="sidebarSearch" placeholder="Search">
        <i class="fa-solid fa-microphone mic-icon"></i>
    </div>

    <div class="sidebar-top-section">
        <div class="sidebar-header">
            <div class="logo-section">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/LOGO.png') }}" alt="Logo" class="sidebar-logo">
                </a>
                <div class="sidebar-title">
                    <strong>Oboun ERP</strong><br>
                    <span class="subtitle">Pharmacy Management System</span>
                </div>
            </div>
        </div>

        <button id="toggleSidebar" class="toggle-btn" type="button">
            <i class="fa fa-bars"></i>
        </button>
    </div>

    <div class="sidebar-body">
        <div class="sidebar-content">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span>Dashboard</span>
                </div>
                <span class="sidebar-badge">1</span>
            </a>

            <!-- Point of Sale -->
            <a href="{{ route('pos.index') }}" class="menu-item {{ Route::is('pos.index') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Point of Sale</span>
                </div>
            </a>

            <!-- Orders | Sales (แก้ไข: ใช้ 'orders.index' เพื่อ Active ตัวเอง) -->
            <a href="{{ route('orders.index') }}" class="menu-item {{ Route::is('orders.index') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Orders | Sales</span>
                </div>
                <span class="sidebar-badge">1</span>
            </a>

            <!-- Inventory (Route Group) -->
            <div class="has-submenu {{ Route::is('inventorys.*') ? 'open active' : '' }}">
                <div class="submenu-toggle {{ Route::is('inventorys.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Inventory</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>

                <div class="submenu">
                    <a href="{{ route('inventorys.manage-products') }}"
                        class="submenu-item {{ Route::is('inventorys.manage-products') ? 'active' : '' }}">
                        <i class="fa-solid fa-tablets"></i>Manage Products
                        @if (isset($badgeCounts['manage_products']) && $badgeCounts['manage_products'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['manage_products'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('inventorys.categories') }}"
                        class="submenu-item {{ Route::is('inventorys.categories') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group"></i>Categories
                        @if (isset($badgeCounts['categories']) && $badgeCounts['categories'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['categories'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('inventorys.expiry-management') }}"
                        class="submenu-item {{ Route::is('inventorys.expiry-management') ? 'active' : '' }}">
                        <i class="fa-solid fa-exclamation"></i>
                        <span>Expiry Management</span>
                        @if (isset($badgeCounts['expiry_management']) && $badgeCounts['expiry_management'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['expiry_management'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('inventorys.stock-adjustments') }}"
                        class="submenu-item {{ Route::is('inventorys.stock-adjustments') ? 'active' : '' }}">
                        <i class="fa-brands fa-stack-overflow"></i>
                        <span>Stock Adjustments</span>
                        @if (isset($badgeCounts['stock_adjustments']) && $badgeCounts['stock_adjustments'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['stock_adjustments'] }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Purchasing (Route Group: เพิ่ม 'active' ที่เมนูหลัก) -->
            <div class="has-submenu {{ Route::is('purchasing.*') ? 'open active' : '' }}">
                <div class="submenu-toggle {{ Route::is('purchasing.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-moving"></i>
                    <span>Purchasing</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('purchasing.suppliers') }}"
                        class="submenu-item {{ Route::is('purchasing.suppliers') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-field"></i>Suppliers
                        @if (isset($badgeCounts['suppliers']) && $badgeCounts['suppliers'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['suppliers'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('purchasing.purchaseOrders') }}"
                        class="submenu-item {{ Route::is('purchasing.purchaseOrders') ? 'active' : '' }}">
                        <i class="fa-solid fa-cart-shopping"></i>Purchase Orders
                        @if (isset($badgeCounts['purchase_orders']) && $badgeCounts['purchase_orders'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['purchase_orders'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('purchasing.goodsReceived') }}"
                        class="submenu-item {{ Route::is('purchasing.goodsReceived') ? 'active' : '' }}">
                        <i class="fa-brands fa-get-pocket"></i>Goods Received
                        @if (isset($badgeCounts['goods_received']) && $badgeCounts['goods_received'] > 0)
                            <span class="sidebar-badge">{{ $badgeCounts['goods_received'] }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- People (Route Group: เพิ่ม 'active' ที่เมนูหลัก) -->
            <div class="has-submenu {{ Route::is('peoples.*') ? 'open active' : '' }}">
                <div class="submenu-toggle {{ Route::is('peoples.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>People</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('peoples.patients-customer') }}"
                        class="submenu-item {{ Route::is('peoples.patients-customer') ? 'active' : '' }}">
                        <i class="fa-solid fa-user"></i>Patients | Customers
                    </a>

                    <a href="{{ route('peoples.staff-user') }}"
                        class="submenu-item {{ Route::is('peoples.staff-user') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-nurse"></i>Staff | Users
                    </a>

                    <a href="{{ route('peoples.recent') }}"
                        class="submenu-item {{ Route::is('peoples.recent') ? 'active' : '' }}">
                        <i class="fa-solid fa-signal"></i>Recent
                    </a>
                </div>
            </div>

            <!-- Reports (Route Group: เพิ่ม 'active' ที่เมนูหลัก) -->
            <div class="has-submenu {{ Route::is('reports.*') ? 'open active' : '' }}">
                <div class="submenu-toggle {{ Route::is('reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Reports</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">

                    <a href="{{ route('reports.sales') }}"
                        class="submenu-item {{ Route::is('reports.sales') ? 'active' : '' }}">
                        <i class="fa-solid fa-capsules"></i>
                        Sales Report
                        <span class="sidebar-badge">1</span>
                    </a>

                    <a href="{{ route('reports.inventory') }}"
                        class="submenu-item {{ Route::is('reports.inventory') ? 'active' : '' }}">
                        <i class="fa-solid fa-warehouse"></i>
                        Inventory Report
                    </a>

                    <a href="{{ route('reports.finance') }}"
                        class="submenu-item {{ Route::is('reports.finance') ? 'active' : '' }}">
                        <i class="fa-solid fa-coins"></i>
                        Financial Report
                    </a>
                </div>
            </div>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}"
                class="menu-item {{ Route::is('notifications.index') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fa-solid fa-bell"></i>
                    <span>Notifications</span>
                </div>
                @if (isset($badgeCounts['notifications']) && $badgeCounts['notifications'] > 0)
                    <span
                        class="sidebar-badge">{{ $badgeCounts['notifications'] > 99 ? '99+' : $badgeCounts['notifications'] }}</span>
                @endif
            </a>

            <!-- Comments -->
            <a href="#" class="menu-item open-support-modal-btn">
                <div class="menu-item-content">
                    <i class="fa-solid fa-comments"></i>
                    <span>Comments</span>
                </div>
            </a>

            <!-- Settings -->
            <a href="{{ route('settings.index') }}"
                class="menu-item {{ Route::is('settings.index') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </div>
                <span class="sidebar-badge">1</span>
            </a>

            <!-- Support -->
            <a href="#" class="menu-item open-support-modal-btn" title="Help & Support" id="showHelpModalButton">
                <div class="menu-item-content">
                    <i class="fa-solid fa-headset"></i>
                    <span>Support</span>
                </div>
            </a>

        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="#" class="menu-item logout-item" id="open-logout-modal">
                    <div class="menu-item-content">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>{{ __('Log out') }}</span>
                    </div>
                </a>
            </form>
        </div>
    </div>


</div>

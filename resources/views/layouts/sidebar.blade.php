<div id="sidebar" class="apple-sidebar">

    <div class="sidebar-search-container">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="sidebarSearch" placeholder="Search">
        <i class="fa-solid fa-microphone mic-icon"></i>
    </div>

    <div class="sidebar-top-section">
        <div class="sidebar-header">
            <div class="logo-section">
                <a href="{{ route('dashboard') }}"><img src="{{ asset('images/LOGO.png') }}" alt="Logo"
                        class="sidebar-logo"></a>
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
            <a href="#" class="menu-item">
                <div class="menu-item-content">
                    <i class="fa-solid fa-table-cells-large"></i></i>
                    <span>Dashboard</span>
                </div>
                <span class="sidebar-badge">1</span>
            </a>

            <a href="#" class="menu-item">
                <div class="menu-item-content">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Point of Sale</span>
                </div>
            </a>

            <a href="#" class="menu-item">
                <div class="menu-item-content">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Orders | Sales</span>
                </div>
                <span class="sidebar-badge">1</span>
            </a>

            <div class="has-submenu">
                <div class="submenu-toggle">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Inventory</span>

                    <span class="sidebar-badge">3</span>

                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item"><i class="fa-solid fa-tablets"></i>Manage Products</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-layer-group"></i>Categories</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-exclamation"></i><span>Expiry Management</span> <span class="sidebar-badge">2</span></a>
                    <a href="#" class="submenu-item"><i class="fa-brands fa-stack-overflow"></i><span>Stock Adjustments</span>  <span class="sidebar-badge">1</span></a>
                </div>
            </div>

            <div class="has-submenu">
                <div class="submenu-toggle">
                    <i class="fa-solid fa-truck-moving"></i>
                    <span>Purchasing</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item"><i class="fa-solid fa-truck-field"></i>Suppliers</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-cart-shopping"></i>Purchase Orders</a>
                    <a href="#" class="submenu-item"><i class="fa-brands fa-get-pocket"></i>Goods Received</a>
                </div>
            </div>

            <div class="has-submenu">
                <div class="submenu-toggle">
                    <i class="fa-solid fa-users"></i>
                    <span>People</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item"><i class="fa-solid fa-user"></i>Patients | Customers</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-user-nurse"></i>Staff | Users</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-signal"></i>Recent</a>
                </div>
            </div>

            <div class="has-submenu">
                <div class="submenu-toggle">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Reports</span>
                    <i class="fa-solid fa-chevron-right arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item"><i class="fa-solid fa-capsules"></i>Sales Report</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-warehouse"></i>Inventory Report</a>
                    <a href="#" class="submenu-item"><i class="fa-solid fa-coins"></i>Financial Report</a>
                </div>
            </div>

            <a href="#" class="menu-item">
                <div class="menu-item-content">

                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </div>
            </a>

            <a href="#" class="menu-item">
                <div class="menu-item-content">

                    <i class="fa-solid fa-headset"></i>
                    <span>Support</span>
                </div>
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf

                <a href="{{ route('logout') }}" class="menu-item logout-item"
                    onclick="event.preventDefault();
                    this.closest('form').submit();">
                    <div class="menu-item-content">

                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>{{ __('Log out') }}</span>
                    </div>
                </a>
            </form>
        </div>
    </div>
</div>

<div id="sidebarOverlay" class="sidebar-overlay hidden"></div>

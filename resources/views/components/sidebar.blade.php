<aside id="sidebar" class="sidebar">
    {{-- Sidebar Quick Search --}}
    <div class="px-4 mt-5 mb-2">
        <div class="relative group">
            <input type="text" id="sidebar-search" placeholder="{{ __('general.search') }}..."
                class="sidebar-search-input glossy-input">
            <i class="ph ph-magnifying-glass sidebar-search-icon"></i>
            <div class="sidebar-search-right-icons">
                <i class="ph ph-microphone sidebar-mic-icon"></i>
                <div class="sidebar-search-shortcut">
                    <span class="text-[9px] font-bold">/</span>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar-logo">
        <div class="sidebar-logo-inner">
            @php
                $storeLogo = \App\Models\Setting::get('store_logo');
                $storeName = \App\Models\Setting::get('store_name', 'Oboun');
                $isAdmin = auth()->user()->isAdmin();
            @endphp
            @if ($storeLogo)
                <img src="{{ Storage::url($storeLogo) }}" alt="Store Logo" class="sidebar-logo-img"
                    style="width: 45px; height: 45px; max-width: 45px; max-height: 45px; object-fit: contain;">
            @else
                <i class="ph-fill ph-pill sidebar-logo-icon text-ios-blue"></i>
            @endif
            <div class="flex flex-col">
                <span class="sidebar-logo-text">{{ $storeName }}</span>
                <span class="sidebar-logo-subtext">ERP Management</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        {{-- Sales Operations (All users) --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('sales_operations') }}</h3>
            <ul class="sidebar-section">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-chart-pie-slice sidebar-link-icon"></i>
                            <span>{{ __('dashboard') }}</span>
                        </div>
                        @if (($sidebarBadges['dashboard'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['dashboard'] }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('pos.index') }}"
                        class="sidebar-link {{ request()->routeIs('pos.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-cash-register sidebar-link-icon"></i>
                            <span>{{ __('pos') }}</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders.index') }}"
                        class="sidebar-link {{ request()->routeIs('orders.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-receipt sidebar-link-icon"></i>
                            <span>{{ __('orders') }}</span>
                        </div>
                        @if (($sidebarBadges['orders'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['orders'] }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('calendar.index') }}"
                        class="sidebar-link {{ request()->routeIs('calendar.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-calendar-blank sidebar-link-icon"></i>
                            <span>{{ __('calendar') }}</span>
                        </div>
                        @if (($sidebarBadges['calendar'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['calendar'] }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('messenger.index') }}"
                        class="sidebar-link {{ request()->routeIs('messenger.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-messenger-logo sidebar-link-icon"></i>
                            <span>{{ __('messenger') }}</span>
                        </div>
                        @if (($sidebarBadges['messenger'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['messenger'] }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifications.index') }}"
                        class="sidebar-link {{ request()->routeIs('notifications.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-bell sidebar-link-icon"></i>
                            <span>{{ __('notifications.title') }}</span>
                        </div>
                        @if (($sidebarBadges['notifications'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['notifications'] }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- Inventory Management --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('inventory') }}</h3>
            <ul class="sidebar-section">
                {{-- Products: All users can view --}}
                <li>
                    <a href="{{ route('products.index') }}"
                        class="sidebar-link {{ request()->routeIs('products.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-pill sidebar-link-icon"></i>
                            <span>{{ __('products') }}</span>
                        </div>
                        @if (($sidebarBadges['products'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['products'] }}</span>
                        @endif
                    </a>
                </li>

                {{-- Stock Adjustments: Admin only --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('stock-adjustments.index') }}"
                            class="sidebar-link {{ request()->routeIs('stock-adjustments.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-list-checks sidebar-link-icon"></i>
                                <span>{{ __('stock_adjustments') }}</span>
                            </div>
                            @if (($sidebarBadges['stock_adjustments'] ?? 0) > 0)
                                <span
                                    class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['stock_adjustments'] }}</span>
                            @endif
                        </a>
                    </li>
                @endif

                {{-- Expiry: All users --}}
                <li>
                    <a href="{{ route('expiry.index') }}"
                        class="sidebar-link {{ request()->routeIs('expiry.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-calendar-x sidebar-link-icon"></i>
                            <span>{{ __('expiry') }}</span>
                        </div>
                        @if (($sidebarBadges['expiry'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['expiry'] }}</span>
                        @endif
                    </a>
                </li>

                {{-- Categories: Admin only --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="sidebar-link {{ request()->routeIs('categories.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-folder sidebar-link-icon"></i>
                                <span>{{ __('categories') }}</span>
                            </div>
                        </a>
                    </li>
                @endif

                {{-- Prescriptions: All users --}}
                <li>
                    <a href="{{ route('prescriptions.index') }}"
                        class="sidebar-link {{ request()->routeIs('prescriptions.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-prescription sidebar-link-icon"></i>
                            <span>{{ __('prescriptions.title') }}</span>
                        </div>
                        @if (($sidebarBadges['prescriptions'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['prescriptions'] }}</span>
                        @endif
                    </a>
                </li>

                {{-- Controlled Drugs: All users --}}
                <li>
                    <a href="{{ route('controlled-drugs.index') }}"
                        class="sidebar-link {{ request()->routeIs('controlled-drugs.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-shield-warning sidebar-link-icon"></i>
                            <span>{{ __('controlled_drugs.title') }}</span>
                        </div>
                        @if (($sidebarBadges['controlled_drugs'] ?? 0) > 0)
                            <span
                                class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['controlled_drugs'] }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- Tools (All users) --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('inventory_tools') }}</h3>
            <ul class="sidebar-section">
                <li>
                    <a href="{{ route('barcode.index') }}"
                        class="sidebar-link {{ request()->routeIs('barcode.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-barcode sidebar-link-icon"></i>
                            <span>{{ __('barcode.title') }}</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('shift-notes.index') }}"
                        class="sidebar-link {{ request()->routeIs('shift-notes.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-note-pencil sidebar-link-icon"></i>
                            <span>{{ __('shift_notes.title') }}</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('calculators.index') }}"
                        class="sidebar-link {{ request()->routeIs('calculators.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-calculator sidebar-link-icon"></i>
                            <span>{{ __('calculators.title') }}</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('drug-interactions.index') }}"
                        class="sidebar-link {{ request()->routeIs('drug-interactions.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-shield-check sidebar-link-icon"></i>
                            <span>{{ __('drug_interactions.title') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Purchasing --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('purchasing') }}</h3>
            <ul class="sidebar-section">
                {{-- Suppliers: Admin only --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('suppliers.index') }}"
                            class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-truck sidebar-link-icon"></i>
                                <span>{{ __('suppliers.title') }}</span>
                            </div>
                            @if (($sidebarBadges['suppliers'] ?? 0) > 0)
                                <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['suppliers'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchase-orders.index') }}"
                            class="sidebar-link {{ request()->routeIs('purchase-orders.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-shopping-cart sidebar-link-icon"></i>
                                <span>{{ __('po.title') }}</span>
                            </div>
                            @if (($sidebarBadges['purchase_orders'] ?? 0) > 0)
                                <span
                                    class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['purchase_orders'] }}</span>
                            @endif
                        </a>
                    </li>
                @endif

                {{-- Goods Received: All users --}}
                <li>
                    <a href="{{ route('goods-received.index') }}"
                        class="sidebar-link {{ request()->routeIs('goods-received.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-package sidebar-link-icon"></i>
                            <span>{{ __('gr.title') }}</span>
                        </div>
                        @if (($sidebarBadges['goods_received'] ?? 0) > 0)
                            <span
                                class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['goods_received'] }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- People & Logs --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('people_logs') }}</h3>
            <ul class="sidebar-section">
                {{-- Customers: All users --}}
                <li>
                    <a href="{{ route('customers.index') }}"
                        class="sidebar-link {{ request()->routeIs('customers.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-users sidebar-link-icon"></i>
                            <span>{{ __('patients_customers') }}</span>
                        </div>
                        @if (($sidebarBadges['customers'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['customers'] }}</span>
                        @endif
                    </a>
                </li>

                {{-- Staff/Users: Admin only --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="sidebar-link {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-user-gear sidebar-link-icon"></i>
                                <span>{{ __('staff_users') }}</span>
                            </div>
                            @if (($sidebarBadges['users'] ?? 0) > 0)
                                <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['users'] }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activity-logs.index') }}"
                            class="sidebar-link {{ request()->routeIs('activity-logs.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-clock-counter-clockwise sidebar-link-icon"></i>
                                <span>{{ __('recent') }}</span>
                            </div>
                            @if (($sidebarBadges['activity_logs'] ?? 0) > 0)
                                <span
                                    class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['activity_logs'] }}</span>
                            @endif
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Reports & Analytics --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('reports_analytics') }}</h3>
            <ul class="sidebar-section">
                {{-- Admin only reports --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('reports.sales') }}"
                            class="sidebar-link {{ request()->routeIs('reports.sales') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-trend-up sidebar-link-icon"></i>
                                <span>{{ __('sales_report') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.inventory') }}"
                            class="sidebar-link {{ request()->routeIs('reports.inventory*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-stack sidebar-link-icon"></i>
                                <span>{{ __('inventory_report') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.finance') }}"
                            class="sidebar-link {{ request()->routeIs('reports.finance*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-bank sidebar-link-icon"></i>
                                <span>{{ __('finance_report') }}</span>
                            </div>
                        </a>
                    </li>
                @endif

                {{-- Expiring Products: All users --}}
                <li>
                    <a href="{{ route('reports.expiring-products') }}"
                        class="sidebar-link {{ request()->routeIs('reports.expiring-products') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-clock-countdown sidebar-link-icon"></i>
                            <span>{{ __('reports.expiring_products') }}</span>
                        </div>
                    </a>
                </li>

                {{-- Admin only reports --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('reports.product-profit') }}"
                            class="sidebar-link {{ request()->routeIs('reports.product-profit') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-chart-line-up sidebar-link-icon"></i>
                                <span>{{ __('reports.product_profit') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.loyal-customers') }}"
                            class="sidebar-link {{ request()->routeIs('reports.loyal-customers') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-heart sidebar-link-icon"></i>
                                <span>{{ __('reports.loyal_customers') }}</span>
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Promotions & Marketing: Admin only --}}
        @if ($isAdmin)
            <div>
                <h3 class="sidebar-section-title">{{ __('promotions.title') }}</h3>
                <ul class="sidebar-section">
                    <li>
                        <a href="{{ route('promotions.index') }}"
                            class="sidebar-link {{ request()->routeIs('promotions.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-tag sidebar-link-icon"></i>
                                <span>{{ __('promotions.title') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bundles.index') }}"
                            class="sidebar-link {{ request()->routeIs('bundles.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-package sidebar-link-icon"></i>
                                <span>{{ __('bundles.title') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member-tiers.index') }}"
                            class="sidebar-link {{ request()->routeIs('member-tiers.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-crown sidebar-link-icon"></i>
                                <span>{{ __('tiers.title') }}</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        {{-- Settings --}}
        <div>
            <h3 class="sidebar-section-title">{{ __('settings') }}</h3>
            <ul class="sidebar-section">
                {{-- Profile is for everyone --}}
                <li>
                    <a href="{{ route('profile.edit') }}"
                        class="sidebar-link {{ request()->routeIs('profile.*') ? 'sidebar-link-active' : '' }}">
                        <div class="sidebar-link-content">
                            <i class="ph ph-user-circle sidebar-link-icon"></i>
                            <span>{{ __('profile') }}</span>
                        </div>
                        @if (($sidebarBadges['profile'] ?? 0) > 0)
                            <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['profile'] }}</span>
                        @endif
                    </a>
                </li>

                {{-- Admin only settings --}}
                @if ($isAdmin)
                    <li>
                        <a href="{{ route('settings.index') }}"
                            class="sidebar-link {{ request()->routeIs('settings.index') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-gear sidebar-link-icon"></i>
                                <span>{{ __('general') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.hardware.index') }}"
                            class="sidebar-link {{ request()->routeIs('settings.hardware.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-printer sidebar-link-icon"></i>
                                <span>{{ __('hardware_printing') }}</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.backup.index') }}"
                            class="sidebar-link {{ request()->routeIs('settings.backup.*') ? 'sidebar-link-active' : '' }}">
                            <div class="sidebar-link-content">
                                <i class="ph ph-database sidebar-link-icon"></i>
                                <span>{{ __('database_backup') }}</span>
                            </div>
                            @if (($sidebarBadges['backup'] ?? 0) > 0)
                                <span class="sidebar-badge sidebar-badge-red">{{ $sidebarBadges['backup'] }}</span>
                            @endif
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <button type="button" class="sidebar-footer-btn mb-1"
            onclick="window.dispatchEvent(new CustomEvent('open-support-modal'))">
            <i class="ph ph-lifebuoy sidebar-footer-btn-icon"></i>
            <span>{{ __('support') }}</span>
        </button>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="sidebar-logout-btn group">
                <i class="ph ph-sign-out sidebar-logout-icon"></i>
                <span class="sidebar-logout-text">{{ __('user.sign_out') }}</span>
            </button>
        </form>
    </div>
</aside>

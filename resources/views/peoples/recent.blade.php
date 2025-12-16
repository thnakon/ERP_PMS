<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Recent Activity - Pharmacy ERP</title>

        <!-- Import Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Import Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Import FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Sarabun", sans-serif;
                background-color: #F5F5F7;
                color: #1D1D1F;
                -webkit-font-smoothing: antialiased;
            }

            ::-webkit-scrollbar {
                width: 0px;
                background: transparent;
            }

            .soft-shadow {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            }

            .fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .view-toggle-active {
                background-color: #007AFF;
                color: white;
                box-shadow: 0 2px 8px rgba(0, 122, 255, 0.25);
            }

            .view-toggle-inactive {
                color: #86868B;
                background-color: transparent;
            }

            .view-toggle-inactive:hover {
                color: #1D1D1F;
            }

            .inv-card-row {
                display: grid;
                gap: 16px;
                align-items: center;
                background: white;
                border-radius: 12px;
                padding: 16px 24px;
                margin-bottom: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }

            .inv-card-row:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
                border-color: rgba(0, 122, 255, 0.1);
            }

            .inv-card-row.header {
                background: transparent;
                box-shadow: none;
                padding: 12px 24px;
                margin-bottom: 0;
                border-radius: 0;
                border: none;
            }

            .inv-card-row.header:hover {
                transform: none;
                box-shadow: none;
                border-color: transparent;
            }

            .inv-col-header {
                font-size: 12px;
                font-weight: 600;
                color: #8e8e93;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .inv-text-main {
                font-size: 14px;
                font-weight: 500;
                color: #1d1d1f;
            }

            .inv-text-sub {
                font-size: 13px;
                color: #86868b;
            }

            .inv-product-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .inv-product-name {
                font-weight: 600;
                color: #1d1d1f;
                font-size: 15px;
            }

            .grid-recent {
                grid-template-columns: 2.5fr 4fr 1.5fr 2fr 0.8fr;
            }

            .inv-status-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }

            .sr-header {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 32px;
                gap: 16px;
            }

            @media (min-width: 768px) {
                .sr-header {
                    flex-direction: row;
                }
            }

            .sr-header-left {
                width: 100%;
            }

            @media (min-width: 768px) {
                .sr-header-left {
                    width: auto;
                }
            }

            .sr-breadcrumb {
                font-size: 14px;
                color: #86868b;
                margin-bottom: 8px;
            }

            .sr-page-title {
                font-size: 32px;
                font-weight: 700;
                color: #1d1d1f;
                letter-spacing: -0.02em;
            }

            .stat-card {
                background: white;
                border-radius: 20px;
                padding: 20px 24px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
                transition: all 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            }

            .inv-form-input {
                background-color: #ffffff;
                border: 1px solid transparent;
                border-radius: 22px;
                padding: 10px 16px;
                font-size: 14px;
                transition: all 0.2s ease;
                color: #1d1d1f;
            }

            .inv-form-input:focus {
                background-color: #ffffff;
                border-color: #007aff;
                outline: none;
                box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            }

            .error-row {
                background-color: #FFF5F5 !important;
            }

            /* List Row Hover */
            .list-row {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .list-row:hover {
                transform: scale(1.002);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
                z-index: 10;
                background-color: #FAFAFC;
            }

            /* Animations */
            .fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>

    <body class="min-h-screen p-6 md:p-10">
        <div class="os-container fade-in">

            <!-- Header Section -->
            <div class="sr-header" style="margin-bottom: 0px">
                <div class="sr-header-left">
                    <p class="sr-breadcrumb">
                        Dashboard / People / <span style="color: #3a3a3c; font-weight: 600;">Recent Activity</span>
                    </p>
                    <h2 class="sr-page-title">System Logs <span
                            style="font-size: 0.6em; color: #8e8e93; font-weight: 500;">(Activity Log)</span></h2>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                    <!-- Export Dropdown -->
                    <div class="relative" id="export-dropdown-container">
                        <button onclick="toggleExportDropdown()"
                            class="bg-white text-[#1D1D1F] border border-gray-200 px-4 py-2.5 rounded-xl font-medium text-sm shadow-sm hover:bg-[#F9F9FB] transition flex items-center gap-2"
                            style="border-radius: 25px">
                            <i class="fa-solid fa-download text-[#86868B]"></i> Export
                            <i class="fa-solid fa-chevron-down text-[#86868B] text-xs ml-1"></i>
                        </button>
                        <div id="export-dropdown"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50 hidden"
                            style="box-shadow: 0 10px 40px rgba(0,0,0,0.12);">
                            <a href="{{ route('peoples.recent.export', array_merge(request()->query(), ['format' => 'excel'])) }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-[#1D1D1F] hover:bg-[#F5F5F7] transition">
                                <i class="fa-solid fa-file-excel text-[#34C759]"></i>
                                <span>Excel (.xlsx)</span>
                            </a>
                            <a href="{{ route('peoples.recent.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-[#1D1D1F] hover:bg-[#F5F5F7] transition border-t border-gray-100">
                                <i class="fa-solid fa-file-pdf text-[#FF3B30]"></i>
                                <span>PDF (.pdf)</span>
                            </a>
                            <a href="{{ route('peoples.recent.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-[#1D1D1F] hover:bg-[#F5F5F7] transition border-t border-gray-100">
                                <i class="fa-solid fa-file-csv text-[#007AFF]"></i>
                                <span>CSV (.csv)</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Controls Row (Search & Filters) -->
            <div class="inv-filters-wrapper" style="margin-bottom: 0px">
                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('peoples.recent') }}" id="filter-form" class="inv-search-form"
                    style="display: flex; gap: 10px; align-items: center;">

                    <!-- Category Filter -->
                    <select name="category" class="inv-form-input" style="width: 180px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="all"
                            {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>All Categories
                        </option>
                        <option value="sales" {{ request('category') == 'sales' ? 'selected' : '' }}>Sales</option>
                        <option value="inventory" {{ request('category') == 'inventory' ? 'selected' : '' }}>Inventory
                        </option>
                        <option value="system" {{ request('category') == 'system' ? 'selected' : '' }}>System</option>
                        <option value="security" {{ request('category') == 'security' ? 'selected' : '' }}>Security
                        </option>
                        <option value="user" {{ request('category') == 'user' ? 'selected' : '' }}>User Management
                        </option>
                    </select>

                    <!-- Date Filter -->
                    <select name="date" class="inv-form-input" style="width: 160px; height: 44px; cursor: pointer;"
                        onchange="this.form.submit()">
                        <option value="" {{ !request('date') ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>

                    <!-- Search Input -->
                    <div style="position: relative;">
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            placeholder="Search action, user..." class="inv-form-input"
                            style="width: 280px; height: 44px; padding-left: 40px;">
                        <i class="fa-solid fa-magnifying-glass"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #86868B; font-size: 0.9rem;"></i>
                    </div>

                    <!-- Clear Filters -->
                    @if (request('search') || request('category') || request('date'))
                        <a href="{{ route('peoples.recent') }}"
                            class="text-sm text-[#FF3B30] hover:underline font-medium" style="white-space: nowrap;">
                            <i class="fa-solid fa-times mr-1"></i> Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- ACTIVITY LIST -->
            <div id="view-list" class="transition-opacity duration-300">

                <!-- Table Header -->
                <div class="inv-card-row header grid-recent">
                    <div class="inv-col-header">User / Actor</div>
                    <div class="inv-col-header">Activity Description</div>
                    <div class="inv-col-header">Category</div>
                    <div class="inv-col-header">Time</div>
                    <div class="inv-col-header" style="text-align: right;">Status</div>
                </div>

                <div class="space-y-3" id="log-container">
                    @forelse($logs as $log)
                        @php
                            $badge = $log->category_badge;
                            $statusIcon = $log->status_icon;
                        @endphp
                        <div class="inv-card-row grid-recent list-row {{ $log->status === 'error' ? 'error-row' : '' }}"
                            data-category="{{ $log->category }}" style="border-radius: 24px;">

                            <!-- User Info -->
                            <div class="inv-product-info">
                                @if ($log->user)
                                    <img src="{{ $log->user->profile_photo_path ? asset('storage/' . $log->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($log->user->first_name . ' ' . $log->user->last_name) . '&background=random' }}"
                                        class="w-9 h-9 rounded-full object-cover border border-gray-100">
                                    <div>
                                        <div class="inv-product-name">{{ $log->user->first_name }}
                                            {{ $log->user->last_name }}</div>
                                        <div class="inv-text-sub">{{ ucfirst($log->user->role ?? 'User') }}</div>
                                    </div>
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 border border-gray-200">
                                        <i class="fa-solid fa-server"></i>
                                    </div>
                                    <div>
                                        <div class="inv-product-name">System</div>
                                        <div class="inv-text-sub">Automated</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Activity -->
                            <div>
                                <div class="inv-text-main">{{ $log->action }}</div>
                                <div class="inv-text-sub {{ $log->status === 'error' ? 'text-red-500' : '' }}">
                                    {{ Str::limit($log->description, 60) ?? '-' }}
                                </div>
                            </div>

                            <!-- Category Badge -->
                            <div>
                                <span class="inv-status-badge"
                                    style="background-color: {{ $badge['bg'] }}; color: {{ $badge['color'] }};">
                                    <i class="fa-solid {{ $badge['icon'] }} text-[10px]"></i>
                                    {{ ucfirst($log->category) }}
                                </span>
                            </div>

                            <!-- Time -->
                            <div>
                                <div class="inv-text-main">{{ $log->created_at->diffForHumans() }}</div>
                                <div class="inv-text-sub">{{ $log->created_at->format('h:i A') }}</div>
                            </div>

                            <!-- Status Icon -->
                            <div style="text-align: right;">
                                <i class="fa-solid {{ $statusIcon['icon'] }} text-lg"
                                    style="color: {{ $statusIcon['color'] }};"
                                    title="{{ ucfirst($log->status) }}"></i>
                            </div>
                        </div>
                    @empty
                        <div class="inv-card-row" style="border-radius: 24px;">
                            <div class="text-center py-12 col-span-5">
                                <i class="fa-solid fa-clock-rotate-left text-5xl text-gray-200 mb-4"></i>
                                <p class="text-[#86868B] text-lg">No activity logs found</p>
                                <p class="text-[#86868B] text-sm mt-2">Activity will appear here as users interact with
                                    the system</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($logs->hasPages())
                    <div class="mt-6">
                        {{ $logs->links('vendor.pagination.apple') }}
                    </div>
                @else
                    <div class="mt-4 text-center text-sm text-[#86868B]">
                        Showing {{ $logs->count() }} of {{ $logs->total() }} logs
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Real-time search with debounce and AJAX
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
                        const viewList = document.getElementById('view-list');
                        viewList.style.opacity = '0.5';

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
                                const newContent = doc.getElementById('view-list');
                                if (newContent) {
                                    viewList.innerHTML = newContent.innerHTML;
                                }
                                viewList.style.opacity = '1';
                            })
                            .catch(err => {
                                console.error('Search error:', err);
                                viewList.style.opacity = '1';
                            });
                    }, 400); // 400ms debounce
                });
            }

            // Export Dropdown Toggle
            function toggleExportDropdown() {
                const dropdown = document.getElementById('export-dropdown');
                dropdown.classList.toggle('hidden');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const container = document.getElementById('export-dropdown-container');
                const dropdown = document.getElementById('export-dropdown');
                if (container && !container.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>
    </body>

    </html>
</x-app-layout>

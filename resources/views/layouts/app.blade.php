<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Oboun ERP') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    @php
        $favicon = \App\Models\Setting::get('store_favicon');
    @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body class="bg-ios-bg font-sans" style="font-family: 'Inter', sans-serif;">

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Bulk Action Floating Bar -->
    <div id="bulk-action-bar" class="bulk-action-bar bulk-action-bar-hidden">
        <div class="bulk-action-bar-inner">
            <span class="bulk-action-count">
                <span id="selected-count">0</span> {{ __('general.selected') }}
            </span>
            <div class="bulk-action-divider"></div>
            <div class="bulk-action-buttons">
                <button onclick="ProductsPage.openBulkCategoryModal()" class="bulk-action-btn"
                    title="{{ __('change_category') }}">
                    <i class="ph ph-archive bulk-action-btn-icon"></i>
                </button>
                <button onclick="deleteSelected()" class="bulk-action-btn-delete">
                    <i class="ph ph-trash bulk-action-btn-icon"></i> {{ __('general.delete') }}
                </button>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
        <!-- Sidebar Navigation -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Mobile Header -->
            @include('components.header-mobile')

            <!-- Desktop Header -->
            @include('components.header')

            <!-- Page Content -->
            <div class="content-area">
                <div class="max-w-[1800px] px-2 lg:px-4">
                    <header class="content-header mb-8 flex items-center justify-between">
                        <h1 class="content-title text-3xl font-bold text-gray-900">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <div class="header-actions">
                            @yield('header-actions')
                        </div>
                    </header>
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Drawer Overlay -->
    @include('components.drawer')

    <!-- Modal Overlays -->
    @include('components.modal')
    @include('components.delete-modal')

    <!-- Apple Style Loading Animation -->
    <div id="pill-loading" class="pill-loading-overlay">
        <div class="apple-spinner">
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
        </div>
        <div class="loading-text">{{ __('loading') }}</div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')

    {{-- Flash Messages as Toasts --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('success')), 'success');
                }
            });
        </script>
    @endif
    @if (session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('warning')), 'warning');
                }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('error')), 'error');
                }
            });
        </script>
    @endif
</body>

</html>

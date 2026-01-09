<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Oboun ERP') }} - @yield('title', 'POS')</title>

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

    <style>
        /* POS Layout - Full Width, No Sidebar */
        .pos-layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--ios-bg, #f5f5f7);
        }

        .pos-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .pos-header-inner {
            max-w: full;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
        }

        @media (min-width: 1024px) {
            .pos-header-inner {
                padding: 0 24px;
            }
        }

        .pos-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .pos-back-btn:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: #1e293b;
            transform: translateX(-2px);
        }

        .pos-back-btn i {
            font-size: 18px;
        }

        .pos-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pos-logo-img {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: cover;
        }

        .pos-logo-text {
            font-size: 18px;
            font-weight: 700;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .pos-main {
            flex: 1;
            padding: 16px;
            overflow: hidden;
        }

        @media (min-width: 1024px) {
            .pos-main {
                padding: 20px 24px;
            }
        }

        @media (min-width: 1536px) {
            .pos-main {
                padding: 24px 32px;
            }
        }

        .pos-content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        @media (min-width: 1024px) {
            .pos-content-header {
                margin-bottom: 20px;
            }
        }

        .pos-content-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        @media (min-width: 1024px) {
            .pos-content-title {
                font-size: 28px;
            }
        }

        .pos-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (min-width: 768px) {
            .pos-header-actions {
                gap: 12px;
            }
        }

        /* POS Toast - Bottom Center */
        #toast-container {
            position: fixed !important;
            bottom: 24px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            top: auto !important;
            right: auto !important;
            width: auto !important;
            max-width: 90vw !important;
            z-index: 9999 !important;
            display: flex !important;
            flex-direction: column-reverse !important;
            gap: 8px !important;
            align-items: center !important;
        }

        #toast-container .toast {
            animation: slideUpToast 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        #toast-container .toast.toast-exit {
            animation: slideDownToast 0.3s ease-in forwards !important;
        }

        @keyframes slideUpToast {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDownToast {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-ios-bg font-sans" style="font-family: 'Inter', sans-serif;">

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <div class="pos-layout">
        <!-- POS Header -->
        <header class="pos-header">
            <div class="pos-header-inner">
                <!-- Left: Back to CMS Button + Logo -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="pos-back-btn" title="Back to Dashboard">
                        <i class="ph ph-arrow-left"></i>
                        <span class="hidden sm:inline">{{ __('pos.back_to_cms') ?? 'Back to CMS' }}</span>
                    </a>

                    <div class="pos-logo hidden md:flex">
                        @php
                            $logo = \App\Models\Setting::get('store_logo');
                        @endphp
                        @if ($logo)
                            <img src="{{ Storage::url($logo) }}" alt="Logo" class="pos-logo-img">
                        @else
                            <div
                                class="pos-logo-img bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr(config('app.name', 'O'), 0, 1) }}
                            </div>
                        @endif
                        <span
                            class="pos-logo-text">{{ \App\Models\Setting::get('store_name', config('app.name', 'Oboun ERP')) }}</span>
                    </div>
                </div>

                <!-- Center: Page Title -->
                <div class="flex-1 flex justify-center">
                    <h1 class="pos-content-title hidden lg:block">
                        @yield('page-title', __('pos.title'))
                    </h1>
                </div>

                <!-- Right: Header Actions + User Info -->
                <div class="flex items-center gap-3">
                    @yield('header-actions')

                    <!-- User Info -->
                    <div class="hidden md:flex items-center gap-3 pl-4 border-l border-gray-200">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Staff' }}</div>
                        </div>
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-lg object-cover">
                        @else
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- POS Main Content -->
        <main class="pos-main">
            @yield('content')
        </main>
    </div>

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

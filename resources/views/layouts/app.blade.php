<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Stylesheets  + js -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/main.css', 'resources/js/main.js', 'resources/css/app.css', 'resources/js/app.js', 'resources/css/sidebar.css', 'resources/js/dashboard.js', 'resources/js/sidebar.js', 'resources/css/header.css', 'resources/js/header.js', 'resources/css/settings.css', 'resources/js/setting.js', 'resources/css/modal.css', 'resources/js/modal.js', 'resources/css/dashboard.css', 'resources/css/sale-report.css', 'resources/js/sale-report.js', 'resources/css/footer.css', 'resources/js/footer.js', 'resources/css/people.css', 'resources/js/people.js', 'resources/css/purchasing.css', 'resources/js/purchasing.js', 'resources/css/orders-sales.css', 'resources/js/orders-sales.js', 'resources/css/inventorys.css', 'resources/js/inventorys.js', 'resources/css/pos.css', 'resources/js/pos.js'])
    @else
        {{-- Fallback for when Vite build is missing --}}
        <link rel="stylesheet" href="{{ asset('resources/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/sidebar.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/purchasing.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/modal.css') }}">
        {{-- Add other necessary CSS files here if needed --}}
    @endif

</head>

<body class="apple-dashboard">
    @include('layouts.page-loader')
    @include('layouts.sidebar')
    @include('layouts.header')
    @include('layouts.madal-support')

    <!-- Page Content -->
    <main class="main-content-wrapper">
        {{ $slot }}


        @include('layouts.footer')
    </main>






    {{-- Confirm Logout Modal --}}
    <div id="logoutModal" class="apple-modal-overlay">
        <div class="apple-modal-content">
            <div class="apple-modal-header">
                <h3>{{ __('Confirm Logout') }}</h3>
            </div>
            <div class="apple-modal-body">
                <p>{{ __('Are you sure you want to log out?') }}</p>
            </div>
            <div class="apple-modal-footer">
                <button type="button" class="apple-button cancel-button" id="cancelLogout">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="apple-button confirm-button" id="confirmLogout">
                    {{ __('Log Out') }}
                </button>
            </div>
        </div>
    </div>



    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // ตรวจสอบว่า Laravel ส่งข้อความ 'success' มาหรือไม่ และต้องไม่มีการระงับ (suppress)
        @if (session('success') && !session('suppress_global_toast'))
            Toastify({
                // [!!! 1. แก้ไข text !!!]
                // เราจะใส่โค้ด SVG และห่อด้วย Wrapper ที่เราสร้างใน CSS
                text: `
                <div class='toastify-content-wrapper'>
                    <svg class="toast-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark-check" fill="none" d="M14 27l10 10L38 23"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            `,

                // [!!! 2. เพิ่มอันนี้ !!!]
                escapeMarkup: false, // สำคัญ: เพื่อให้แสดง HTML/SVG ได้

                duration: 3000,
                gravity: "bottom",
                position: "center",
                stopOnFocus: true,
                style: {
                    // ...
                }
            }).showToast();
        @endif
    </script>

    @stack('scripts')
</body>

</html>

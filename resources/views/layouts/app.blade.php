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
        @vite(['resources/css/main.css', 'resources/js/main.js', 'resources/css/app.css', 'resources/js/app.js', 'resources/css/sidebar.css','resources/js/dashboard.js' , 'resources/js/sidebar.js', 'resources/css/header.css', 'resources/js/header.js', 'resources/css/settings.css', 'resources/js/setting.js', 'resources/css/modal.css', 'resources/js/modal.js',
         'resources/css/dashboard.css' , 'resources/css/sale-report.css', 'resources/js/sale-report.js', 'resources/css/footer.css', 'resources/js/footer.js',
         'resources/css/people.css', 'resources/js/people.js'])
    @else
        {{-- (โค้ด fallback) --}}
        <style>
            /* ... */
        </style>
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




    {{-- Modal แนะนำการใช้งานระบบ --}}
    <div id="helpModalOverlay" class="help-modal-overlay">
        <div id="helpModal" class="help-modal">

            <div class="help-modal-header">
                <h2><i class="fa-solid fa-book"></i> คู่มือการใช้งานระบบ</h2>
                <button id="closeHelpModal" class="help-modal-close-btn">&times;</button>
            </div>

            <div class="help-modal-content">
                <h3>ยินดีต้อนรับสู่ Oboun ERP</h3>
                <p>ระบบบริหารจัดการร้านขายยา (Pharmacy Management System)
                    ที่ออกแบบมาเพื่อช่วยให้การจัดการร้านขายยาของคุณเป็นเรื่องง่าย, มีประสิทธิภาพ,
                    และลดข้อผิดพลาดในการทำงาน</p>
                <p>ระบบของเราครอบคลุมทุกกระบวนการสำคัญ ตั้งแต่การขายหน้าร้าน (POS), การจัดการสต็อกยาและเวชภัณฑ์,
                    การติดตามวันหมดอายุ, การจัดซื้อ, ไปจนถึงการออกรายงานสรุปยอดขาย</p>

                <h4>1. การเริ่มต้นใช้งาน</h4>
                <p><strong>1.1 การเข้าสู่ระบบ (Login)<br></strong> ใช้ชื่อผู้ใช้และรหัสผ่านเพื่อเข้าสู่หน้าจอหลัก</p>
                <p><strong>1.2 หน้าแดชบอร์ด (Dashboard)<br></strong> หน้าสรุปภาพรวมที่สำคัญของร้าน เช่น ยอดขาย,
                    ยาใกล้หมดอายุ, และสินค้าใกล้หมดสต็อก</p>

                <h4>2. คู่มือการใช้งานเมนูหลัก</h4>
                <p><i class="fa-solid fa-cash-register"></i> <strong>2.1 ระบบขายหน้าร้าน (POS)</strong><br>
                    หัวใจหลักสำหรับการขาย ค้นหาสินค้า, เพิ่มลงตะกร้า, ชำระเงิน, และพิมพ์ใบเสร็จ</p>

                <p><i class="fa-solid fa-receipt"></i> <strong>2.2 ประวัติการขาย (Orders / Sales)</strong><br>
                    ดูรายการบิลย้อนหลัง, ค้นหาบิลเก่า, หรือทำการคืนสินค้า</p>

                <p><i class="fa-solid fa-boxes-stacked"></i> <strong>2.3 การจัดการคลังสินค้า (Inventory)</strong><br>
                    ส่วนสำคัญในการบริหารสต็อก (จัดการสินค้า, หมวดหมู่, และการจัดการวันหมดอายุ)</p>

                <p><i class="fa-solid fa-truck-moving"></i> <strong>2.4 การจัดซื้อ (Purchasing)</strong><br>
                    จัดการกระบวนการสั่งซื้อ (ซัพพลายเออร์, สร้างใบสั่งซื้อ, รับสินค้าเข้า)</p>

                <p><i class="fa-solid fa-users"></i> <strong>2.5 การจัดการบุคคล (People)</strong><br>
                    บันทึกประวัติลูกค้า/ผู้ป่วย และจัดการบัญชีผู้ใช้งาน</p>

                <p><i class="fa-solid fa-chart-pie"></i> <strong>2.6 รายงาน (Reports)</strong><br>
                    ดูข้อมูลสรุปยอดขาย, สินค้าคงคลัง, และกำไร-ขาดทุน</p>

                <h4>3. การตั้งค่า และ การออกจากระบบ</h4>
                <p><i class="fa-solid fa-gear"></i> <strong>3.1 การตั้งค่า (Settings)<br></strong>
                    ตั้งค่าข้อมูลพื้นฐานของร้าน</p>
                <p><i class="fa-solid fa-right-from-bracket"></i> <strong>3.2 การออกจากระบบ (Log Out)<br></strong>
                    คลิกที่ปุ่ม Log out เมื่อใช้งานเสร็จสิ้น</p>
            </div>

        </div>
    </div>

    {{-- Comfirm Madol log out --}}
    <div id="logoutModal" class="apple-modal-overlay">
        <div class="apple-modal-content">
            <div class="apple-modal-header">
                <h3>{{ __('ยืนยันการออกจากระบบ') }}</h3>
            </div>
            <div class="apple-modal-body">
                <p>{{ __('คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?') }}</p>
            </div>
            <div class="apple-modal-footer">
                <button type="button" class="apple-button cancel-button" id="cancelLogout">
                    {{ __('ยกเลิก') }}
                </button>
                <button type="button" class="apple-button confirm-button" id="confirmLogout">
                    {{ __('ออกจากระบบ') }}
                </button>
            </div>
        </div>
    </div>

    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // ตรวจสอบว่า Laravel ส่งข้อความ 'success' มาหรือไม่
        @if (session('success'))
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

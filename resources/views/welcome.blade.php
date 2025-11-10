<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    {{-- <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite([
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/navigation-welcome.css',  {{-- CSS ของ Navbar (ตัวเดิม) --}}
                'resources/css/welcome.css',             {{-- 👈 **CSS ใหม่สำหรับ Body (เพิ่มตรงนี้) ** --}}
                'resources/css/footer-welcome.css',
                'resources/js/navigation.js',             {{-- JS ของ Navbar (ตัวเดิม) --}}
                'resources/css/main.css',
                'resources/js/main.js'  
            ])
    @else
        {{-- (โค้ด fallback) --}}
        <style>
            /* ... */
        </style>
    @endif
</head>

{{-- 
      * เราจะเพิ่ม class 'welcome-body' เพื่อให้ CSS ทำงาน
      * และ 'antialiased' เป็น class ของ Tailwind ที่ช่วยให้ฟอนต์สวยขึ้น
    --}}

<body class="welcome-body antialiased">
    @include('layouts.page-loader')
    {{-- 1. ส่วนของ Navbar (เหมือนเดิม) --}}
    @include('layouts.navigation-welcome')

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

    {{-- 2. ส่วนเนื้อหาใหม่ (สไตล์ Apple) --}}
    <section class="store-section">
        
        <div class="store-header">
            <h2 class="store-title">Oboun ERP</h2>
            <div class="store-header-right">
                <p class="store-tagline">โปรแกรมร้านยาระบบบริหารร้านขายยา</p>
                <p class="store-tagline">เลือกที่โปรแกรมที่ดีสำหรับคุณ</p>
                <a href="#" class="store-finder-link">About Oboun ERP > </a>
            </div>
        </div>

    </section>

    <section class="benefits-section">
        <div class="benefits-header">
            <h2 class="benefits-title">ทำไมถึงเลือก Obun ERP ของเรา</h2>
            <a href="#" class="benefits-link">ทำไมถึงต้องเรา ></a>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <h3 class="card-title">Apple Trade In</h3>
                <p class="card-subtitle">ประหยัดกับ iPhone เครื่องใหม่ด้วยการนำอุปกรณ์มาแลก</p>
                <p class="card-description">
                    รับเครดิตมูลค่า ฿5,000 – ฿20,500 สำหรับซื้อ iPhone 17, iPhone Air หรือ iPhone 17 Pro เมื่อคุณนำ iPhone 13 หรือใหม่กว่ามาแลก<sup>2</sup>
                </p>
                <div class="card-image-placeholder trade-in">
                    </div>
                <button class="card-action-button" aria-label="Learn more about Apple Trade In">+</button>
            </div>

            <div class="benefit-card">
                <h3 class="card-title">บริการด้านการเงิน</h3>
                <p class="card-subtitle">แบ่งจ่ายรายเดือนได้ง่ายๆ</p>
                <p class="card-description">
                    จ่ายดอกเบี้ย 0% นานสูงสุด 10 เดือน หากคุณมีบัตรเครดิตที่เข้าเกณฑ์ กรุณาเลือกบริการด้านการเงินที่เหมาะกับคุณ<sup>1</sup>
                </p>
                <div class="card-image-placeholder finance">
                    </div>
                <button class="card-action-button" aria-label="Learn more about finance options">+</button>
            </div>

            <div class="benefit-card">
                <h3 class="card-title">การตั้งค่าส่วนบุคคล</h3>
                <p class="card-subtitle">ทำความรู้จักกับ iPhone เครื่องใหม่ของคุณด้วยการตั้งค่าส่วนบุคคล</p>
                <p class="card-description">
                    เข้าร่วมเซสชั่นออนไลน์กับ Specialist เพื่อตั้งค่า iPhone ของคุณและค้นพบคุณสมบัติใหม่ๆ
                </p>
                <div class="card-image-placeholder setup">
                    </div>
                <button class="card-action-button" aria-label="Learn more about personal setup">+</button>
            </div>

            <div class="benefit-card">
                <h3 class="card-title">บริการจัดส่งและรับสินค้า</h3>
                <p class="card-subtitle">บริการจัดส่งฟรี และรับสินค้าที่ร้าน</p>
                <p class="card-description">
                    รับบริการจัดส่งฟรี หรือรับสินค้าที่ Apple Store
                </p>
                <div class="card-image-placeholder delivery">
                    </div>
                <button class="card-action-button" aria-label="Learn more about delivery and pickup">+</button>
            </div>

            <div class="benefit-card">
                <h3 class="card-title">บริการจัดส่งและรับสินค้า</h3>
                <p class="card-subtitle">บริการจัดส่งฟรี และรับสินค้าที่ร้าน</p>
                <p class="card-description">
                    รับบริการจัดส่งฟรี หรือรับสินค้าที่ Apple Store
                </p>
                <div class="card-image-placeholder delivery">
                    </div>
                <button class="card-action-button" aria-label="Learn more about delivery and pickup">+</button>
            </div>
        </div>
    </section>

    
    @include('layouts.footer-welcome')

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

</body>

</html>

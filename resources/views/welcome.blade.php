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

    {{-- 1. ส่วนของ Navbar (เหมือนเดิม) --}}
    @include('layouts.navigation-welcome')

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

    {{-- 2. ส่วนเนื้อหาใหม่ (สไตล์ Apple) --}}
    <main id="main-content">

        {{-- ส่วน Hero Section (แบนเนอร์ใหญ่) --}}
        <section class="hero-section">
            <div class="container">
                <h1 class="hero-title">Developer</h1>
                <p class="hero-subtitle">
                    Build the next generation of apps for Apple platforms.
                </p>
                <div class="hero-links">
                    <a href="#" class="hero-link">Learn about development</a>
                </div>
            </div>
        </section>

        {{-- ส่วน Feature Grid (กล่อง 2 คอลัมน์) --}}
        <section class="feature-section">
            <div class="container">
                <div class="feature-grid">

                    {{-- กล่องที่ 1 --}}
                    <div class="feature-card" style="background-color: #f5f5f7;">
                        <h2 class="card-title">Design</h2>
                        <p class="card-description">
                            Get design guidance and UI resources for building intuitive, beautiful, and inclusive apps.
                        </p>
                        <p class="card-description">
                            Get design guidance and UI resources for building intuitive, beautiful, and inclusive apps.
                        </p>
                    </div>

                    {{-- กล่องที่ 2 --}}
                    <div class="feature-card" style="background-color: #f5f5f7;">
                        <h2 class="card-title">Develop</h2>
                        <p class="card-description">
                            Learn how to build, test, and deploy your apps using the latest Apple technologies and SDKs.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- ส่วน Hero Section (แบนเนอร์ใหญ่) --}}
        <section class="hero-section">
            <div class="container">
                <h1 class="hero-title">นักพัฒนา</h1>
                <p class="hero-subtitle">
                    สร้างแอปรุ่นถัดไปสำหรับแพลตฟอร์ม Apple
                </p>
                <div class="hero-links">
                    <a href="#" class="hero-link">เรียนรู้เกี่ยวกับการพัฒนา</a>
                </div>
            </div>
        </section>

        {{-- ส่วน Feature Grid (กล่อง 2 คอลัมน์) --}}
        <section class="feature-section">
            <div class="container">
                <div class="feature-grid">

                    {{-- กล่องที่ 1 --}}
                    <div class="feature-card" style="background-color: #f5f5f7;">
                        <h2 class="card-title">ออกแบบ</h2>
                        <p class="card-description">
                            รับคำแนะนำด้านการออกแบบและทรัพยากร UI เพื่อสร้างแอปที่ใช้งานง่าย สวยงาม และครอบคลุม
                        </p>
                    </div>

                    {{-- กล่องที่ 2 --}}
                    <div class="feature-card" style="background-color: #f5f5f7;">
                        <h2 class="card-title">นักพัฒนา</h2>
                        <p class="card-description">
                            เรียนรู้วิธีสร้าง ทดสอบ และปรับใช้แอปของคุณโดยใช้เทคโนโลยีและ SDK ของ Apple ล่าสุด
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- หลังจากสิ้นสุด feature-grid แล้ว เพิ่มส่วน Spotlight / Gallery --}}
        <section class="spotlight-section">
            <div class="container">
                <div class="spotlight-content">
                    <div class="spotlight-image">
                        <img src="/images/mac-spotlight-1.jpg" alt="Mac Spotlight 1" />
                    </div>
                    <div class="spotlight-text">
                        <h2 class="spotlight-title">Mac ที่ออกแบบมาเพื่อคุณ</h2>
                        <p class="spotlight-subtitle">
                            พบกับ Mac รุ่นล่าสุดที่รวมประสิทธิภาพระดับสูง ดีไซน์สวย และระบบนิเวศที่แข็งแกร่ง
                        </p>
                        <a href="#" class="spotlight-link">ดู Mac ทั้งหมด</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Grid แบบ 3 คอลัมน์ (Feature Highlights) --}}
        <section class="highlights-section">
            <div class="container">
                <h2 class="section-heading">สิ่งที่ทำให้ Mac แตกต่าง</h2>
                <div class="highlights-grid">
                    <div class="highlight-card">
                        <h3 class="card-heading">ชิป Apple M-ซีรีส์</h3>
                        <p class="card-text">
                            ประสิทธิภาพ และความเร็วที่ก้าวกระโดด พร้อมพลังการประมวลผลกราฟิกที่ทรงพลัง
                        </p>
                        <a href="#" class="card-link">เรียนรู้เพิ่มเติม</a>
                    </div>
                    <div class="highlight-card">
                        <h3 class="card-heading">ดีไซน์บางและเบา</h3>
                        <p class="card-text">
                            จากวัสดุหรู น้ำหนักเบา พร้อมหน้าจอ Retina และระบบเสียงคุณภาพสูง
                        </p>
                        <a href="#" class="card-link">ดูรุ่น</a>
                    </div>
                    <div class="highlight-card">
                        <h3 class="card-heading">ระบบนิเวศ Apple</h3>
                        <p class="card-text">
                            เชื่อมต่อกับ iPhone, iPad, Apple Watch ได้อย่างราบรื่น พร้อม iCloud และ Handoff
                        </p>
                        <a href="#" class="card-link">ดูเพิ่มเติม</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Call to Action (CTA) ใหญ่ท้ายสุด --}}
        <section class="cta-section">
            <div class="container text-center">
                <h2 class="cta-title">เริ่มต้นกับ Mac ใหม่ของคุณวันนี้</h2>
                <p class="cta-subtitle">
                    เลือกรุ่นที่ใช่ พร้อมข้อเสนอพิเศษ และบริการสนับสนุนจาก Apple
                </p>
                <a href="#" class="cta-button">ดูรุ่น Mac ทั้งหมด</a>
            </div>
        </section>



    </main>

    {{-- ⭐️ เพิ่ม Footer ตรงนี้ ⭐️ --}}
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

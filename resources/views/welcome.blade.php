<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    >{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    {{-- <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet"> --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap"rel="stylesheet">
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


    {{-- 2. ส่วนเนื้อหาใหม่ (สไตล์ Apple Landing Page - Full Scale) --}}
    <section class="apple-landing-hero">
        <div class="hero-content">
            <h1 class="hero-title fade-on-scroll">Oboun ERP</h1>
            <h2 class="hero-subtitle fade-on-scroll delay-1">ระบบบริหารจัดการร้านยาที่เหนือกว่า</h2>
            <p class="hero-description fade-on-scroll delay-2">
                เปลี่ยนการจัดการร้านยาของคุณให้ง่าย รวดเร็ว และแม่นยำ<br>
                ด้วยเทคโนโลยีที่ออกแบบมาเพื่อคุณโดยเฉพาะ
            </p>
            <div class="hero-actions fade-on-scroll delay-3">
                <a href="#" class="btn-apple-primary">เริ่มต้นใช้งาน</a>
                <a href="#" class="btn-apple-secondary">เรียนรู้เพิ่มเติม ></a>
            </div>
        </div>
        <div class="hero-visual fade-on-scroll delay-4">
            {{-- ใช้รูป Placeholder หรือรูปที่มีอยู่ --}}
            <img src="{{ asset('images/laptop-phone.png') }}" alt="Oboun ERP Dashboard" class="hero-image">
        </div>
    </section>
    <section class="device-section">
        <div class="benefits-header fade-on-scroll">
            <h2 class="benefits-title">อุปกรณ์ที่รองรับ Oboun ERP</h2>
            <a href="#" class="benefits-link">ดูอุปกรณ์ที่รองรับทั้งหมด ></a>
        </div>

        <div class="device-scroll-container">

            <a href="#" class="device-card fade-on-scroll delay-1">
                <img src="https://placehold.co/100x70/ffffff/000?text=Mac" alt="Mac">
                <span>Mac</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-2">
                <img src="https://placehold.co/100x70/ffffff/000?text=iPhone" alt="iPhone">
                <span>iPhone</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-3">
                <img src="https://placehold.co/100x70/ffffff/000?text=iPad" alt="iPad">
                <span>iPad</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-1">
                <img src="https://placehold.co/100x70/ffffff/000?text=Watch" alt="Apple Watch">
                <span>Apple Watch</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-2">
                <img src="https://placehold.co/100x70/ffffff/000?text=AirPods" alt="AirPods">
                <span>AirPods</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-3">
                <img src="https://placehold.co/100x70/ffffff/000?text=AirTag" alt="AirTag">
                <span>AirTag</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-1">
                <img src="https://placehold.co/100x70/ffffff/000?text=TV" alt="Apple TV">
                <span>Apple TV 4K</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-2">
                <img src="https://placehold.co/100x70/ffffff/000?text=HomePod" alt="HomePod">
                <span>HomePod</span>
            </a>

            <a href="#" class="device-card fade-on-scroll delay-3">
                <img src="https://placehold.co/100x70/ffffff/000?text=Acc" alt="Accessories">
                <span>อุปกรณ์เสริม</span>
            </a>

        </div>
    </section>

    <section class="benefits-section">
        <div class="benefits-header fade-on-scroll">
            <h2 class="benefits-title">อุปกรณ์ที่รองรับ Obun ERP ของเรา</h2>
            <a href="#" class="benefits-link">ทำไมถึงต้องเรา ></a>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card fade-on-scroll">
                <h3 class="card-title">Apple Trade In</h3>
                <p class="card-subtitle">ประหยัดกับ iPhone เครื่องใหม่ด้วยการนำอุปกรณ์มาแลก</p>
                <p class="card-description">
                    รับเครดิตมูลค่า ฿5,000 – ฿20,500 สำหรับซื้อ iPhone 17, iPhone Air หรือ iPhone 17 Pro เมื่อคุณนำ
                    iPhone 13 หรือใหม่กว่ามาแลก<sup>2</sup>
                </p>
                <div class="card-image-placeholder trade-in">
                </div>
                <button class="card-action-button" aria-label="Learn more about Apple Trade In">+</button>
            </div>

            <div class="benefit-card fade-on-scroll delay-1">
                <h3 class="card-title">บริการด้านการเงิน</h3>
                <p class="card-subtitle">แบ่งจ่ายรายเดือนได้ง่ายๆ</p>
                <p class="card-description">
                    จ่ายดอกเบี้ย 0% นานสูงสุด 10 เดือน หากคุณมีบัตรเครดิตที่เข้าเกณฑ์
                    กรุณาเลือกบริการด้านการเงินที่เหมาะกับคุณ<sup>1</sup>
                </p>
                <div class="card-image-placeholder finance">
                </div>
                <button class="card-action-button" aria-label="Learn more about finance options">+</button>
            </div>

            <div class="benefit-card fade-on-scroll delay-2">
                <h3 class="card-title">การตั้งค่าส่วนบุคคล</h3>
                <p class="card-subtitle">ทำความรู้จักกับ iPhone เครื่องใหม่ของคุณด้วยการตั้งค่าส่วนบุคคล</p>
                <p class="card-description">
                    เข้าร่วมเซสชั่นออนไลน์กับ Specialist เพื่อตั้งค่า iPhone ของคุณและค้นพบคุณสมบัติใหม่ๆ
                </p>
                <div class="card-image-placeholder setup">
                </div>
                <button class="card-action-button" aria-label="Learn more about personal setup">+</button>
            </div>

            <div class="benefit-card fade-on-scroll delay-3">
                <h3 class="card-title">บริการจัดส่งและรับสินค้า</h3>
                <p class="card-subtitle">บริการจัดส่งฟรี และรับสินค้าที่ร้าน</p>
                <p class="card-description">
                    รับบริการจัดส่งฟรี หรือรับสินค้าที่ Apple Store
                </p>
                <div class="card-image-placeholder delivery">
                </div>
                <button class="card-action-button" aria-label="Learn more about delivery and pickup">+</button>
            </div>

            <div class="benefit-card fade-on-scroll delay-1">
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


    <section class="apple-bento-section">
        <div class="section-header fade-on-scroll">
            <h2 class="section-title">ทำไมต้อง Oboun ERP?</h2>
            <p class="section-subtitle">ฟีเจอร์ที่ทรงพลัง ในดีไซน์ที่เรียบง่าย</p>
        </div>

        <div class="bento-grid">
            <div class="bento-item item-large fade-on-scroll">
                <div class="bento-content">
                    <h3>Dashboard อัจฉริยะ</h3>
                    <p>เห็นภาพรวมธุรกิจของคุณได้ในหน้าเดียว พร้อมกราฟวิเคราะห์ยอดขายแบบ Real-time</p>
                </div>
                <div class="bento-image">
                    <img src="https://placehold.co/600x400/f5f5f7/1d1d1f?text=Smart+Dashboard" alt="Dashboard">
                </div>
            </div>

            <div class="bento-item item-tall fade-on-scroll delay-1">
                <div class="bento-content">
                    <h3>จัดการสต็อก</h3>
                    <p>แม่นยำทุกรายการยา แจ้งเตือนเมื่อของใกล้หมด</p>
                </div>
                <div class="bento-image bottom">
                    <img src="https://placehold.co/300x400/e8e8ed/1d1d1f?text=Stock" alt="Stock">
                </div>
            </div>

            <div class="bento-item item-medium fade-on-scroll delay-2">
                <div class="bento-content">
                    <h3>ระบบสมาชิก</h3>
                    <p>เก็บข้อมูลลูกค้า ประวัติการแพ้ยา และสะสมแต้ม</p>
                </div>
            </div>

            <div class="bento-item item-medium fade-on-scroll delay-3">
                <div class="bento-content">
                    <h3>รายงานภาษี</h3>
                    <p>ออกรายงานภาษีซื้อ-ขาย ได้ถูกต้องตามมาตรฐาน</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 2.1 Features Detail Section (Zig-Zag) --}}
    <section id="features" class="apple-feature-detail">
        <div class="feature-row fade-on-scroll">
            <div class="feature-text">
                <h3 class="feature-heading">จัดการสต็อกยา<br><span class="text-gradient">แม่นยำทุกเม็ด</span></h3>
                <p class="feature-desc">
                    หมดปัญหายาหายหรือยาหมดอายุ ด้วยระบบแจ้งเตือนอัจฉริยะที่ทำงานแบบ Real-time
                    ช่วยให้คุณวางแผนการสั่งซื้อได้อย่างมีประสิทธิภาพ ลดต้นทุนจม และเพิ่มกำไรสูงสุด
                </p>
            </div>
            <div class="feature-visual">
                <img src="https://placehold.co/600x400/f5f5f7/1d1d1f?text=Stock+Management" alt="Stock Management">
            </div>
        </div>

        <div class="feature-row reverse fade-on-scroll">
            <div class="feature-text">
                <h3 class="feature-heading">ระบบขายหน้าร้าน<br><span class="text-gradient">ที่เร็วที่สุด</span></h3>
                <p class="feature-desc">
                    ออกแบบมาเพื่อเภสัชกรโดยเฉพาะ รองรับการสแกนบาร์โค้ด ค้นหายาด้วยชื่อสามัญ
                    และเชื่อมต่อกับลิ้นชักเก็บเงินและเครื่องพิมพ์ใบเสร็จได้อย่างราบรื่น
                </p>
            </div>
            <div class="feature-visual">
                <img src="https://placehold.co/600x400/f5f5f7/1d1d1f?text=POS+System" alt="POS System">
            </div>
        </div>
    </section>

    {{-- 2.2 About Section --}}
    <section id="about" class="apple-about-section fade-on-scroll">
        <div class="about-container">
            <h2 class="about-title">About Oboun ERP</h2>
            <p class="about-lead">
                เราเชื่อว่าร้านยาคือหัวใจสำคัญของชุมชน
            </p>
            <p class="about-text">
                Oboun ERP เกิดขึ้นจากความตั้งใจที่จะยกระดับมาตรฐานร้านยาไทย ด้วยเทคโนโลยีที่ทันสมัยแต่ใช้งานง่าย
                เรามุ่งมั่นที่จะเป็นพาร์ทเนอร์ที่ช่วยให้เภสัชกรได้โฟกัสกับการดูแลผู้ป่วย
                ในขณะที่เราดูแลระบบหลังบ้านให้คุณ
            </p>
        </div>
    </section>

    {{-- 2.3 Why Us Section --}}
    <section id="why-us" class="apple-why-us-section">
        <div class="section-header fade-on-scroll">
            <h2 class="section-title">Why Oboun ERP?</h2>
            <p class="section-subtitle">ความแตกต่างที่คุณสัมผัสได้</p>
        </div>
        <div class="why-us-grid">
            <div class="why-card fade-on-scroll delay-1">
                <div class="why-icon"><i class="fa-solid fa-bolt"></i></div>
                <h3>รวดเร็ว</h3>
                <p>ระบบ Cloud-Native ที่ทำงานได้ลื่นไหล ไม่มีสะดุด แม้ในช่วงเวลาขายดี</p>
            </div>
            <div class="why-card fade-on-scroll delay-2">
                <div class="why-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>ปลอดภัย</h3>
                <p>ข้อมูลของคุณถูกเข้ารหัสและสำรองข้อมูลอัตโนมัติทุกวัน ปลอดภัยหายห่วง</p>
            </div>
            <div class="why-card fade-on-scroll delay-3">
                <div class="why-icon"><i class="fa-solid fa-heart"></i></div>
                <h3>เข้าใจง่าย</h3>
                <p>UX/UI ที่ออกแบบมาให้เป็นมิตร ไม่ต้องเก่งคอมพิวเตอร์ก็ใช้งานได้ทันที</p>
            </div>
        </div>
    </section>

    {{-- 2.4 Standard Section --}}
    <section id="standard" class="apple-standard-section fade-on-scroll">
        <div class="standard-content">
            <div class="standard-badge">
                <i class="fa-solid fa-certificate"></i>
            </div>
            <h2 class="standard-title">มาตรฐานระดับสากล</h2>
            <p class="standard-desc">
                Oboun ERP ได้รับการออกแบบให้สอดคล้องกับมาตรฐาน GPP (Good Pharmacy Practice)
                และรองรับการเชื่อมต่อกับระบบสาธารณสุขต่างๆ เพื่อให้ร้านยาของคุณพร้อมสำหรับอนาคต
            </p>
            <div class="standard-logos">
                <span>GPP Ready</span>
                <span>ISO 27001 Security</span>
                <span>PDPA Compliant</span>
            </div>
        </div>
    </section>

    {{-- 2.5 Resources Section --}}
    <section id="resources" class="apple-resources-section">
        <div class="section-header fade-on-scroll">
            <h2 class="section-title">Resources</h2>
            <p class="section-subtitle">คลังความรู้เพื่อความสำเร็จของคุณ</p>
        </div>
        <div class="resource-grid">
            <a href="#" class="resource-card fade-on-scroll">
                <div class="resource-img"
                    style="background-image: url('https://placehold.co/400x250/e8e8ed/1d1d1f?text=Guide');"></div>
                <div class="resource-content">
                    <span class="resource-tag">คู่มือการใช้งาน</span>
                    <h4>เริ่มต้นใช้งาน Oboun ERP ใน 5 นาที</h4>
                </div>
            </a>
            <a href="#" class="resource-card fade-on-scroll delay-1">
                <div class="resource-img"
                    style="background-image: url('https://placehold.co/400x250/e8e8ed/1d1d1f?text=Article');"></div>
                <div class="resource-content">
                    <span class="resource-tag">บทความ</span>
                    <h4>เทคนิคการบริหารสต็อกยาให้คุ้มค่า</h4>
                </div>
            </a>
            <a href="#" class="resource-card fade-on-scroll delay-2">
                <div class="resource-img"
                    style="background-image: url('https://placehold.co/400x250/e8e8ed/1d1d1f?text=Video');"></div>
                <div class="resource-content">
                    <span class="resource-tag">วิดีโอ</span>
                    <h4>ฟีเจอร์ใหม่ประจำเดือนนี้</h4>
                </div>
            </a>
        </div>
    </section>

    {{-- 2.6 Help Section --}}
    <section id="help" class="apple-help-section fade-on-scroll">
        <div class="help-container">
            <h2 class="help-title">ต้องการความช่วยเหลือ?</h2>
            <p class="help-subtitle">ทีมซัพพอร์ตของเราพร้อมดูแลคุณตลอด 24 ชั่วโมง</p>

            <div class="help-actions">
                <div class="help-box">
                    <i class="fa-regular fa-circle-question"></i>
                    <h3>ศูนย์ช่วยเหลือ</h3>
                    <a href="#">ดูคำถามที่พบบ่อย ></a>
                </div>
                <div class="help-box">
                    <i class="fa-solid fa-headset"></i>
                    <h3>ติดต่อเรา</h3>
                    <a href="#">แชทกับเจ้าหน้าที่ ></a>
                </div>
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

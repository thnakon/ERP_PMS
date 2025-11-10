<header class="global-nav" id="global-nav">
    <nav class="global-nav-content">
        <ul class="global-nav-menu">
            {{-- โลโก้ --}}
            <li class="global-nav-item">
                <a href="/" class="global-nav-link apple-logo">
                    <img src="{{ asset('images/LOGO.png') }}" alt="Logo" class="logo-svg-img">
                    <span class="logo-text">Oboun ERP</span>
                </a>
            </li>

            {{-- เมนูหลัก --}}
            
            <li class="global-nav-item"><a href="#" class="global-nav-link">Features</a></li>
            <li class="global-nav-item"><a href="#" class="global-nav-link">About</a></li>
            <li class="global-nav-item"><a href="#" class="global-nav-link">Why us</a></li>
            <li class="global-nav-item"><a href="#" class="global-nav-link">Standard</a></li>
            <li class="global-nav-item"><a href="#" class="global-nav-link">Resources</a></li>
            <li class="global-nav-item"><a href="#" class="global-nav-link">Help </a></li>

            {{-- ปุ่มค้นหา --}}
            <li class="global-nav-item nav-right">
                <button id="search-toggle" class="global-nav-link search-button" aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </li>

            {{-- ปุ่ม Auth --}}
            @if (Route::has('login'))
                @auth
                    <li class="global-nav-item">
                        <a href="{{ url('/dashboard') }}" class="global-nav-link">Dashboard</a>
                    </li>
                @else
                    <li class="global-nav-item">
                        <a href="{{ route('login') }}" class="global-nav-link">Log in</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="global-nav-item">
                            <a href="{{ route('register') }}" class="global-nav-link">Sign in of service</a>
                        </li>
                    @endif
                @endauth
            @endif
        </ul>
    </nav>

    {{-- 🔍 ส่วนค้นหาแบบ Apple --}}
    <div id="search-overlay" class="search-overlay">
        <div class="search-box">
            <div class="search-box-inner">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" class="search-field" placeholder="ค้นหาบริการช่วยเหลือ">
                <button class="close-search" aria-label="Close">
                    ✕
                </button>
            </div>

            <div class="quick-links">
                <h4>ลิงก์ด่วน</h4>
                <ul>
                    <li>→ หากคุณลืมรหัสผ่านผ่าน Apple ID</li>
                    <li>→ หากคุณลืมรหัสสำหรับ iPhone, iPad หรือ iPod touch</li>
                    <li>→ ดู เปลี่ยนแปลง หรือยกเลิกการสมัครสมาชิกของคุณ</li>
                    <li>→ อัปเดต iPhone, iPad หรือ iPod touch ของคุณ</li>
                    <li>→ ติดต่อบริการช่วยเหลืออย่างเป็นทางการของ Apple</li>
                </ul>
            </div>
        </div>
    </div>
</header>

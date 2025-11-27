<x-guest-layout>
    {{-- 1. กล่องล็อกอิน (โค้ดเดิมของคุณ) --}}
    <div class="login-container">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- โลโก้ที่คุณระบุ --}}
        <div class="login-logo">
            <img src="{{ asset('images/LOGO.png') }}" alt="Logo">

            {{-- วงจุดหลายวง --}}
            <div class="orbit orbit-1">
                <div class="dot blue"></div>
            </div>
            <div class="orbit orbit-2">
                <div class="dot pink"></div>
            </div>
            <div class="orbit orbit-3">
                <div class="dot cyan"></div>
            </div>
        </div>

        <h1 class="login-title">บัญชี Oboun</h1>
        <h2 class="login-subtitle">เข้าถึงบริการทั้งหมดด้วยบัญชี Oboun ของคุณ</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-field">
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}"
                    required autofocus autocomplete="username" placeholder=" " />
                <label for="email" class="form-label">อีเมลหรือหมายเลขโทรศัพท์</label>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <div class="form-field mt-4">
                <input id="password" class="form-input" type="password" name="password" required
                    autocomplete="current-password" placeholder=" " />
                <label for="password" class="form-label">รหัสผ่าน</label>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="block mt-4 remember-me">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        {{ __('ลืมรหัสผ่านหรือไม่?') }}
                    </a>
                @endif

                <button type="submit" class="apple-button-guest">
                    {{ __('Log in') }}
                </button>
            </div>
            @if (session('success'))
                <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
                <script>
                    Toastify({
                        text: '<div class="flex items-center gap-3"><div class="w-6 h-6 rounded-full bg-[#34C759] flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-white text-[10px]"></i></div><span class="text-[#1D1D1F] font-medium text-sm">Login successful!</span></div>',
                        duration: 3000,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "white",
                        escapeMarkup: false,
                        stopOnFocus: true,
                        className: "soft-shadow rounded-full px-6 py-3 border border-gray-100",
                        style: {
                            background: "white",
                            boxShadow: "0 8px 30px rgba(0,0,0,0.12)",
                            borderRadius: "50px",
                            padding: "12px 24px",
                            color: "#1D1D1F",
                            display: "flex",
                            alignItems: "center",
                            gap: "12px",
                            minWidth: "300px"
                        }
                    }).showToast();
                </script>
            @endif
    </div>
    {{-- (สิ้นสุดกล่องล็อกอิน) --}}


    {{-- 2. ⭐️⭐️ ส่วน Feature Cards ที่เพิ่มใหม่ ⭐️⭐️ --}}
    <div class="feature-cards-section">

        {{-- การ์ดที่ 1: คุณเป็นผู้ควบคุม --}}
        <div class="feature-card">
            <div class="feature-icon">
                {{-- ไอคอน Controls/Sliders (Heroicons) --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 18H7.5m3-6h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0M3.75 12H7.5" />
                </svg>
            </div>
            <h3 class="feature-title">คุณเป็นผู้ควบคุม</h3>
            <p class="feature-description">
                ตรวจสอบหรืออัปเดตข้อมูลสำคัญ เช่น ชื่อ รหัสผ่าน และรายละเอียดความปลอดภัย
                พร้อมดูวิธีที่ผู้อื่นสามารถติดต่อคุณ ตรวจสอบข้อมูลการชำระเงิน และจัดการอุปกรณ์ที่เชื่อมต่อกับบัญชีของคุณ
            </p>
        </div>

        {{-- การ์ดที่ 2: เป็นส่วนตัวและปลอดภัย --}}
        <div class="feature-card">
            <div class="feature-icon">
                {{-- ไอคอน Hand/Privacy (Heroicons) --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.218m-1.06-8.218a.75.75 0 0 0-1.06.023L16.5 12.23a.75.75 0 0 0 .023 1.06c.39.39 1.023.39 1.414 0l.293-.293m-1.152 0A10.062 10.062 0 0 1 12 21a10.062 10.062 0 0 1-5.657-1.789m5.657 0a3.75 3.75 0 0 0-5.657 0M12 3c-3.132 0-6.136.986-8.635 2.787A15.025 15.025 0 0 0 3 10.5c0 2.92.556 5.709 1.568 8.218m16.864-8.218a.75.75 0 0 0-1.06.023L16.5 12.23a.75.75 0 0 0 .023 1.06c.39.39 1.023.39 1.414 0l.293-.293m-1.152 0A10.062 10.062 0 0 1 12 21a10.062 10.062 0 0 1-5.657-1.789m5.657 0a3.75 3.75 0 0 0-5.657 0M12 3c-3.132 0-6.136.986-8.635 2.787A15.025 15.025 0 0 0 3 10.5c0 2.92.556 5.709 1.568 8.218" />
                </svg>
            </div>
            <h3 class="feature-title">เป็นส่วนตัวและปลอดภัย</h3>
            <p class="feature-description">
                ความเป็นส่วนตัวและความปลอดภัยมีมาให้พร้อม ด้วยคุณสมบัติการรักษาความปลอดภัยของบัญชี เช่น
                การตรวจสอบสิทธิ์สองปัจจัย Apple ช่วยรักษาบัญชีของคุณให้ปลอดภัย ปกป้องความเป็นส่วนตัว
                และให้คุณควบคุมข้อมูลของคุณได้ด้วยตนเอง
            </p>
        </div>

    </div>
</x-guest-layout>

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

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite([
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/navigation-welcome.css',  {{-- CSS ของ Navbar (ตัวเดิม) --}}
                'resources/css/welcome.css',             {{-- 👈 **CSS ใหม่สำหรับ Body (เพิ่มตรงนี้) ** --}}
                'resources/css/footer-welcome.css',
                'resources/js/navigation.js',            {{-- JS ของ Navbar (ตัวเดิม) --}}
                'resources/css/guest.css',
                'resources/js/guest.js',
            ])
    @else
        {{-- (โค้ด fallback) --}}
        <style>
            /* ... */
        </style>
    @endif

</head>

<body class="welcome-body antialiased">
    {{-- 1. ส่วนของ Navbar (เหมือนเดิม) --}}
    @include('layouts.navigation-welcome')
    {{-- 1. Apple Navigation Bar (ส่วนหัว) --}}
    <nav class="apple-nav">
        <div class="nav-content">
            <a href="/" class="nav-brand">บัญชี Oboun</a>
            <div class="nav-links">
                <a href="{{ route('login') }}">ลงชื่อเข้า</a>
                <a href="{{ route('register') }}">สร้างบัญชี Oboun ของคุณ</a>
                <a href="#">คำถามที่พบบ่อย</a>
            </div>
        </div>
    </nav>

    {{-- 2. Main Content (ส่วนเนื้อหา) --}}
    <main class="main-content">
        {{ $slot }} {{-- 👈 นี่คือจุดที่ฟอร์ม login.blade.php จะถูกแทรกเข้ามา --}}
    </main>

    {{-- 3. Apple Footer (ส่วนท้าย) --}}
    <footer class="apple-footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">นโยบายความเป็นส่วนตัว</a>
                <a href="#">ข้อกำหนดและเงื่อนไข</a>
                <a href="#">การขายและการคืนเงิน</a>
            </div>
            <div class="footer-copyright">
                Copyright © {{ date('Y') }} ObounInc. สงวนสิทธิ์ทุกประการ
            </div>
        </div>
    </footer>
</body>


</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title data-i18n="terms_title_tag">Terms of Service - Oboun ERP</title>

    <!-- Favicon -->
    @php
        $favicon = \App\Models\Setting::get('store_favicon');
    @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ios-blue': '#007AFF',
                        'ios-green': '#34C759',
                        'ios-red': '#FF3B30',
                        'ios-orange': '#FF9500',
                        'ios-bg': '#F5F5F7',
                    },
                    fontFamily: {
                        sans: ['Inter', 'IBM Plex Sans Thai', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Inter', 'IBM Plex Sans Thai', system-ui, sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .logo-ring {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .logo-ring img,
        .logo-ring>div {
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007AFF 0%, #0056B3 100%);
            box-shadow: 0 8px 24px rgba(0, 122, 255, 0.25);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 122, 255, 0.35);
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 140px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.2s;
            z-index: 100;
            overflow: hidden;
            margin-top: 8px;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            font-size: 14px;
            color: #374151;
            transition: background 0.15s;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.active {
            background: #eff6ff;
            color: #007AFF;
        }

        html.dark {
            background: #000;
        }

        html.dark body {
            background: #000;
            color: #fff;
        }

        html.dark .glass {
            background: rgba(28, 28, 30, 0.8);
        }

        html.dark .bg-white {
            background: #1c1c1e !important;
        }

        html.dark .text-gray-900 {
            color: #fff !important;
        }

        html.dark .text-gray-600,
        html.dark .text-gray-700 {
            color: #a1a1aa !important;
        }

        html.dark .border-gray-100,
        html.dark .border-gray-200 {
            border-color: #333 !important;
        }
    </style>
</head>

<body class="bg-ios-bg antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-6">
                    <a href="{{ route('landing') }}"
                        class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        @php $logo = \App\Models\Setting::get('store_logo'); @endphp
                        @if ($logo)
                            <div class="logo-ring"><img src="{{ Storage::url($logo) }}" alt="Logo"
                                    class="object-cover"></div>
                        @else
                            <div class="logo-ring">
                                <div
                                    class="bg-gradient-to-br from-ios-blue to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    O</div>
                            </div>
                        @endif
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-ios-blue to-blue-600 bg-clip-text text-transparent">Oboun
                            ERP</span>
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('landing') }}#features"
                            class="text-sm font-medium text-gray-600 hover:text-ios-blue transition"
                            data-i18n="nav_features">Features</a>
                        <a href="{{ route('landing') }}#how-it-works"
                            class="text-sm font-medium text-gray-600 hover:text-ios-blue transition"
                            data-i18n="nav_how">How It Works</a>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="dropdown">
                        <button
                            class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition"><i
                                class="ph ph-globe text-lg text-gray-600"></i></button>
                        <div class="dropdown-menu">
                            <div class="dropdown-item" onclick="setLanguage('en')" id="lang-en"><span>🇺🇸</span>
                                English</div>
                            <div class="dropdown-item" onclick="setLanguage('th')" id="lang-th"><span>🇹🇭</span>
                                ภาษาไทย</div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                            id="themeBtn"><i class="ph ph-sun text-lg text-gray-600" id="themeIcon"></i></button>
                        <div class="dropdown-menu">
                            <div class="dropdown-item" onclick="setTheme('light')" id="theme-light"><i
                                    class="ph ph-sun"></i> Light</div>
                            <div class="dropdown-item" onclick="setTheme('dark')" id="theme-dark"><i
                                    class="ph ph-moon"></i> Dark</div>
                            <div class="dropdown-item" onclick="setTheme('system')" id="theme-system"><i
                                    class="ph ph-desktop"></i> System</div>
                        </div>
                    </div>
                    <a href="{{ route('login') }}"
                        class="btn-primary px-5 py-2.5 rounded-full text-white text-sm font-semibold flex items-center gap-2">
                        <span data-i18n="get_started">Get Started</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-gray-100 dark:border-gray-800">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-8" data-i18n="terms_h1">Terms of Service
                </h1>

                <div class="prose prose-blue prose-sm md:prose-base max-w-none dark:prose-invert">
                    <div data-i18n="terms_content">
                        <!-- Content will be injected by JS -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer same as landing -->
    <footer class="py-20 bg-ios-bg dark:bg-black border-t border-gray-200/50 dark:border-gray-800/30 text-gray-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs text-gray-400 dark:text-gray-500" data-i18n="footer_copy">Copyright © 2026 - All rights
                reserved</p>
        </div>
    </footer>

    <script>
        const translations = {
            en: {
                nav_features: 'Features',
                nav_how: 'How It Works',
                get_started: 'Get Started',
                terms_h1: 'Terms of Service',
                footer_copy: 'Copyright © 2026 - All rights reserved',
                terms_content: `
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing or using Oboun ERP, you agree to be bound by these Terms of Service.</p>
                    
                    <h2>2. Use License</h2>
                    <p>Permission is granted to use Oboun ERP for your business management needs. This is the grant of a license, not a transfer of title.</p>
                    
                    <h2>3. Disclaimer</h2>
                    <p>Oboun ERP is provided on an 'as is' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties.</p>
                    
                    <h2>4. Limitations</h2>
                    <p>In no event shall Oboun ERP be liable for any damages arising out of the use or inability to use the services.</p>
                `
            },
            th: {
                nav_features: 'ฟีเจอร์',
                nav_how: 'วิธีใช้งาน',
                get_started: 'เริ่มต้นใช้งาน',
                terms_h1: 'ข้อกำหนดการใช้งาน',
                footer_copy: 'ลิขสิทธิ์ © 2026 - สงวนลิขสิทธิ์ทั้งหมด',
                terms_content: `
                    <h2>1. การยอมรับข้อกำหนด</h2>
                    <p>โดยการเข้าถึงหรือใช้งาน Oboun ERP คุณตกลงที่จะผูกพันตามข้อกำหนดการใช้งานเหล่านี้</p>
                    
                    <h2>2. ใบอนุญาตการใช้งาน</h2>
                    <p>คุณได้รับอนุญาตให้ใช้ Oboun ERP สำหรับความต้องการในการจัดการธุรกิจของคุณ นี่เป็นการให้ใบอนุญาต ไม่ใช่การโอนกรรมสิทธิ์</p>
                    
                    <h2>3. ข้อสงวนสิทธิ์</h2>
                    <p>Oboun ERP จัดหาให้ตามสภาพที่เป็นอยู่ เราไม่มีการรับประกันใดๆ ทั้งโดยชัดแจ้งหรือโดยนัย และขอสงวนสิทธิ์ในการรับประกันอื่นๆ ทั้งหมด</p>
                    
                    <h2>4. ข้อจำกัดความรับผิดชอบ</h2>
                    <p>ไม่ว่าในกรณีใด Oboun ERP จะไม่รับผิดชอบต่อความเสียหายใดๆ ที่เกิดจากการใช้งานหรือการไม่สามารถใช้งานบริการได้</p>
                `
            }
        };

        let currentLang = localStorage.getItem('landing_lang') || 'en';

        function applyTheme(theme) {
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)')
                .matches);
            document.documentElement.classList.toggle('dark', isDark);
            const icon = document.getElementById('themeIcon');
            if (icon) icon.className = 'ph ph-' + (isDark ? 'moon' : 'sun') + ' text-lg text-gray-600';
        }

        function setTheme(theme) {
            localStorage.setItem('landing_theme', theme);
            applyTheme(theme);
        }

        function applyLanguage(lang) {
            const t = translations[lang];
            if (!t) return;
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (t[key]) el.innerHTML = t[key];
            });
        }

        function setLanguage(lang) {
            currentLang = lang;
            localStorage.setItem('landing_lang', lang);
            applyLanguage(lang);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('landing_theme') || 'system';
            applyTheme(savedTheme);
            applyLanguage(currentLang);
        });
    </script>
</body>

</html>

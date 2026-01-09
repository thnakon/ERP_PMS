<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Oboun ERP - ระบบจัดการร้านขายยาครบวงจร</title>
    <meta name="description"
        content="ระบบจัดการร้านขายยาที่ช่วยให้คุณขายได้ง่าย จัดการสต็อก ติดตามวันหมดอายุ และดูแลลูกค้าได้ครบจบในที่เดียว">

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

        html {
            scroll-behavior: smooth;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .hero-section {
            position: relative;
            background: #f8fafc;
            overflow: hidden;
        }

        /* Floating Background Icons */
        .hero-icons {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .hero-icon {
            position: absolute;
            color: rgba(0, 122, 255, 0.08);
            font-size: 28px;
            animation: floatIcon 20s ease-in-out infinite;
        }

        @keyframes floatIcon {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        /* Dark mode for hero */
        html.dark .hero-section {
            background: #0a0a0a;
        }

        html.dark .hero-icon {
            color: rgba(255, 255, 255, 0.04);
        }

        /* Text Highlight Box */
        .text-highlight {
            position: relative;
            display: inline;
        }

        .text-highlight::before {
            content: '';
            position: absolute;
            left: -8px;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            height: 70%;
            background: #e0f2fe;
            border-radius: 8px;
            z-index: -1;
        }

        html.dark .text-highlight::before {
            background: rgba(0, 122, 255, 0.15);
        }

        /* Red Underline Highlight */
        .text-underline-red {
            position: relative;
            display: inline-block;
            color: #ef4444;
            z-index: 1;
        }

        .text-underline-red::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 6px;
            height: 10px;
            background: #fca5a5;
            opacity: 0.4;
            border-radius: 4px;
            z-index: -1;
        }

        html.dark .text-underline-red::after {
            background: rgba(239, 68, 68, 0.3);
        }

        /* Expiry Statement Card */
        .expiry-card-shadow {
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15), 0 10px 20px -10px rgba(0, 0, 0, 0.05);
        }

        html.dark .expiry-card-shadow {
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5);
        }

        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 122, 255, 0.15);
        }

        /* Logo Circle */
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

        /* Header Entrance Animation */
        .header-animate {
            animation: slideDown 0.6s ease-out forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .problem-card {
            background: linear-gradient(135deg, #fef2f2 0%, #fff 100%);
            border-left: 4px solid #FF3B30;
        }

        .solution-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #fff 100%);
            border-left: 4px solid #34C759;
        }

        .testimonial-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .faq-item {
            border-bottom: 1px solid #e5e7eb;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        /* Bento Grid Styles */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: minmax(180px, auto);
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .bento-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .bento-grid {
                grid-template-columns: 1fr;
            }
        }

        .bento-card {
            background-color: white;
            border-radius: 2.5rem;
            border: 1px solid #f1f5f9;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .dark .bento-card {
            background-color: #111827;
            border-color: #1f2937;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        .bento-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #e2e8f0;
            z-index: 10;
        }

        .dark .bento-card:hover {
            border-color: #374151;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        /* Graph Animation */
        .graph-line {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: drawLine 2s ease-out forwards;
        }

        @keyframes drawLine {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* Social Testimonial Styles */
        .social-testimonial-card {
            break-inside: avoid;
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .social-testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dark .social-testimonial-card {
            background: #1f2937;
            border-color: #374151;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }

        .testimonial-header {
            background: #0f172a;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .testimonial-body {
            padding: 1.5rem;
            position: relative;
        }

        .quote-icon {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 3rem;
            color: #f1f5f9;
            font-family: serif;
            line-height: 1;
        }

        .dark .quote-icon {
            color: #374151;
        }

        /* Scroll Animation */
        .scroll-animate {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
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

        /* Dark Mode */
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

        html.dark .hero-gradient {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }

        html.dark .bg-white {
            background: #1c1c1e !important;
        }

        html.dark .bg-ios-bg {
            background: #000 !important;
        }

        html.dark .text-gray-900 {
            color: #fff !important;
        }

        html.dark .text-gray-600,
        html.dark .text-gray-700 {
            color: #a1a1aa !important;
        }

        html.dark .text-gray-500,
        html.dark .text-gray-400 {
            color: #71717a !important;
        }

        html.dark .problem-card,
        html.dark .solution-card {
            background: #1c1c1e;
        }

        html.dark .testimonial-card {
            background: #1c1c1e;
        }

        html.dark .feature-card {
            background: #1c1c1e !important;
            border-color: #333 !important;
        }

        html.dark .dropdown-menu {
            background: #1c1c1e;
            border: 1px solid #333;
        }

        html.dark .dropdown-item {
            color: #e5e5e5;
        }

        html.dark .dropdown-item:hover {
            background: #2c2c2e;
        }

        html.dark .border-gray-100,
        html.dark .border-gray-200 {
            border-color: #333 !important;
        }

        html.dark .faq-item {
            border-color: #333;
        }
    </style>
</head>

<body class="bg-ios-bg antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass border-b border-white/20 header-animate">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo + Nav Links (closer together) -->
                <div class="flex items-center gap-6">
                    <!-- Logo with Ring -->
                    <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;"
                        class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        @php
                            $logo = \App\Models\Setting::get('store_logo');
                        @endphp
                        @if ($logo)
                            <div class="logo-ring">
                                <img src="{{ Storage::url($logo) }}" alt="Logo" class="object-cover">
                            </div>
                        @else
                            <div class="logo-ring">
                                <div
                                    class="bg-gradient-to-br from-ios-blue to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    O
                                </div>
                            </div>
                        @endif
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-ios-blue to-blue-600 bg-clip-text text-transparent">
                            Oboun ERP
                        </span>
                    </a>
                    <!-- Nav Links (closer to logo) -->
                    <div class="hidden md:flex items-center gap-6">
                        <a href="#features" class="text-sm font-medium text-gray-600 hover:text-ios-blue transition"
                            data-i18n="nav_features">Features</a>
                        <a href="#how-it-works" class="text-sm font-medium text-gray-600 hover:text-ios-blue transition"
                            data-i18n="nav_how">How It Works</a>
                        <a href="#testimonials" class="text-sm font-medium text-gray-600 hover:text-ios-blue transition"
                            data-i18n="nav_reviews">Reviews</a>
                        <a href="#faq" class="text-sm font-medium text-gray-600 hover:text-ios-blue transition"
                            data-i18n="nav_faq">FAQ</a>
                    </div>
                </div>

                <!-- CTA + Language + Theme -->
                <div class="flex items-center gap-2">
                    <!-- Language Dropdown -->
                    <div class="dropdown">
                        <button
                            class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <i class="ph ph-globe text-lg text-gray-600"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-item" onclick="setLanguage('en')" id="lang-en">
                                <span>🇺🇸</span> English
                            </div>
                            <div class="dropdown-item" onclick="setLanguage('th')" id="lang-th">
                                <span>🇹🇭</span> ภาษาไทย
                            </div>
                        </div>
                    </div>
                    <!-- Theme Dropdown -->
                    <div class="dropdown">
                        <button
                            class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                            id="themeBtn">
                            <i class="ph ph-sun text-lg text-gray-600" id="themeIcon"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-item" onclick="setTheme('light')" id="theme-light">
                                <i class="ph ph-sun"></i> Light
                            </div>
                            <div class="dropdown-item" onclick="setTheme('dark')" id="theme-dark">
                                <i class="ph ph-moon"></i> Dark
                            </div>
                            <div class="dropdown-item" onclick="setTheme('system')" id="theme-system">
                                <i class="ph ph-desktop"></i> System
                            </div>
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

    <!-- Hero Section -->
    <section class="hero-section pt-32 pb-20 lg:pt-40 lg:pb-32">
        <!-- Floating Background Icons -->
        <div class="hero-icons">
            <!-- Layer 1 - Top -->
            <i class="ph ph-pill hero-icon" style="top: 3%; left: 2%;"></i>
            <i class="ph ph-storefront hero-icon" style="top: 5%; left: 12%; animation-delay: 1s;"></i>
            <i class="ph ph-heart hero-icon" style="top: 8%; left: 22%; animation-delay: 2s;"></i>
            <i class="ph ph-chart-line-up hero-icon" style="top: 4%; left: 32%; animation-delay: 3s;"></i>
            <i class="ph ph-barcode hero-icon" style="top: 10%; left: 42%; animation-delay: 4s;"></i>
            <i class="ph ph-package hero-icon" style="top: 6%; left: 52%; animation-delay: 5s;"></i>
            <i class="ph ph-prescription hero-icon" style="top: 3%; left: 62%; animation-delay: 6s;"></i>
            <i class="ph ph-syringe hero-icon" style="top: 9%; left: 72%; animation-delay: 7s;"></i>
            <i class="ph ph-thermometer hero-icon" style="top: 5%; left: 82%; animation-delay: 8s;"></i>
            <i class="ph ph-first-aid hero-icon" style="top: 8%; left: 92%; animation-delay: 9s;"></i>

            <!-- Layer 2 -->
            <i class="ph ph-calendar-check hero-icon" style="top: 15%; left: 5%; animation-delay: 10s;"></i>
            <i class="ph ph-users-three hero-icon" style="top: 18%; left: 15%; animation-delay: 11s;"></i>
            <i class="ph ph-receipt hero-icon" style="top: 14%; left: 28%; animation-delay: 12s;"></i>
            <i class="ph ph-clipboard-text hero-icon" style="top: 20%; left: 75%; animation-delay: 13s;"></i>
            <i class="ph ph-shield-check hero-icon" style="top: 16%; left: 85%; animation-delay: 14s;"></i>
            <i class="ph ph-lock hero-icon" style="top: 19%; left: 95%; animation-delay: 15s;"></i>

            <!-- Layer 3 -->
            <i class="ph ph-truck hero-icon" style="top: 26%; left: 1%; animation-delay: 16s;"></i>
            <i class="ph ph-credit-card hero-icon" style="top: 30%; left: 10%; animation-delay: 17s;"></i>
            <i class="ph ph-file-text hero-icon" style="top: 28%; left: 20%; animation-delay: 18s;"></i>
            <i class="ph ph-database hero-icon" style="top: 25%; left: 80%; animation-delay: 19s;"></i>
            <i class="ph ph-cloud hero-icon" style="top: 32%; left: 90%; animation-delay: 0s;"></i>

            <!-- Layer 4 -->
            <i class="ph ph-clock hero-icon" style="top: 38%; left: 3%; animation-delay: 1s;"></i>
            <i class="ph ph-wallet hero-icon" style="top: 42%; left: 8%; animation-delay: 2s;"></i>
            <i class="ph ph-tag hero-icon" style="top: 40%; left: 18%; animation-delay: 3s;"></i>
            <i class="ph ph-app-window hero-icon" style="top: 36%; left: 85%; animation-delay: 4s;"></i>
            <i class="ph ph-bell hero-icon" style="top: 44%; left: 92%; animation-delay: 5s;"></i>

            <!-- Layer 5 -->
            <i class="ph ph-gear hero-icon" style="top: 50%; left: 2%; animation-delay: 6s;"></i>
            <i class="ph ph-scan hero-icon" style="top: 55%; left: 12%; animation-delay: 7s;"></i>
            <i class="ph ph-printer hero-icon" style="top: 52%; left: 88%; animation-delay: 8s;"></i>
            <i class="ph ph-desktop hero-icon" style="top: 58%; left: 95%; animation-delay: 9s;"></i>

            <!-- Layer 6 -->
            <i class="ph ph-graph hero-icon" style="top: 62%; left: 4%; animation-delay: 10s;"></i>
            <i class="ph ph-chart-bar hero-icon" style="top: 68%; left: 10%; animation-delay: 11s;"></i>
            <i class="ph ph-money hero-icon" style="top: 65%; left: 20%; animation-delay: 12s;"></i>
            <i class="ph ph-bank hero-icon" style="top: 60%; left: 82%; animation-delay: 13s;"></i>
            <i class="ph ph-calculator hero-icon" style="top: 66%; left: 90%; animation-delay: 14s;"></i>

            <!-- Layer 7 -->
            <i class="ph ph-user hero-icon" style="top: 74%; left: 2%; animation-delay: 15s;"></i>
            <i class="ph ph-identification-card hero-icon" style="top: 78%; left: 15%; animation-delay: 16s;"></i>
            <i class="ph ph-envelope hero-icon" style="top: 72%; left: 25%; animation-delay: 17s;"></i>
            <i class="ph ph-phone hero-icon" style="top: 76%; left: 75%; animation-delay: 18s;"></i>
            <i class="ph ph-chat-circle hero-icon" style="top: 80%; left: 88%; animation-delay: 19s;"></i>
            <i class="ph ph-info hero-icon" style="top: 73%; left: 95%; animation-delay: 0s;"></i>

            <!-- Layer 8 - Bottom -->
            <i class="ph ph-buildings hero-icon" style="top: 84%; left: 5%; animation-delay: 1s;"></i>
            <i class="ph ph-warehouse hero-icon" style="top: 88%; left: 18%; animation-delay: 2s;"></i>
            <i class="ph ph-cube hero-icon" style="top: 86%; left: 30%; animation-delay: 3s;"></i>
            <i class="ph ph-shopping-cart hero-icon" style="top: 90%; left: 42%; animation-delay: 4s;"></i>
            <i class="ph ph-basket hero-icon" style="top: 85%; left: 55%; animation-delay: 5s;"></i>
            <i class="ph ph-coins hero-icon" style="top: 92%; left: 65%; animation-delay: 6s;"></i>
            <i class="ph ph-percent hero-icon" style="top: 87%; left: 78%; animation-delay: 7s;"></i>
            <i class="ph ph-star hero-icon" style="top: 91%; left: 88%; animation-delay: 8s;"></i>
            <i class="ph ph-sparkle hero-icon" style="top: 85%; left: 96%; animation-delay: 9s;"></i>

            <!-- Extra scattered -->
            <i class="ph ph-bandaids hero-icon" style="top: 22%; left: 48%; animation-delay: 10s;"></i>
            <i class="ph ph-activity hero-icon" style="top: 45%; left: 35%; animation-delay: 11s;"></i>
            <i class="ph ph-heartbeat hero-icon" style="top: 55%; left: 65%; animation-delay: 12s;"></i>
            <i class="ph ph-virus hero-icon" style="top: 35%; left: 55%; animation-delay: 13s;"></i>
            <i class="ph ph-drop hero-icon" style="top: 70%; left: 45%; animation-delay: 14s;"></i>
            <i class="ph ph-eye hero-icon" style="top: 48%; left: 25%; animation-delay: 15s;"></i>
            <i class="ph ph-tooth hero-icon" style="top: 33%; left: 68%; animation-delay: 16s;"></i>
            <i class="ph ph-smiley hero-icon" style="top: 58%; left: 38%; animation-delay: 17s;"></i>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto section-fade">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    <span data-i18n="hero_title1">Complete Pharmacy</span><br>
                    <span
                        class="text-highlight bg-gradient-to-r from-ios-blue to-ios-green bg-clip-text text-transparent"
                        data-i18n="hero_title2">Management System</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed"
                    data-i18n="hero_sub">
                    All-in-one solution for modern pharmacies.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                    <a href="{{ route('login') }}"
                        class="btn-primary px-8 py-4 rounded-full text-white text-base font-bold flex items-center gap-2">
                        <i class="ph ph-rocket-launch text-xl"></i>
                        <span data-i18n="hero_cta">Start Free</span>
                    </a>
                    <a href="#features"
                        class="px-8 py-4 rounded-full bg-white border border-gray-200 text-gray-700 text-base font-semibold hover:border-ios-blue hover:text-ios-blue transition flex items-center gap-2">
                        <i class="ph ph-play-circle text-xl"></i>
                        <span data-i18n="hero_cta2">View All Features</span>
                    </a>
                </div>

                <p class="text-sm text-gray-400" data-i18n="hero_note">
                    ✓ No credit card required &nbsp; ✓ Setup in 5 minutes &nbsp; ✓ Thai language support
                </p>
            </div>

            <!-- Hero Image/Preview - macOS Chrome Style -->
            <div class="mt-16 relative">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden max-w-5xl mx-auto border border-gray-200 dark:border-gray-700">
                    <!-- Browser Chrome -->
                    <div
                        class="bg-gray-100 dark:bg-gray-900 px-4 py-3 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700">
                        <!-- Traffic Lights -->
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <!-- Navigation Buttons -->
                        <div class="flex items-center gap-1 ml-2">
                            <button
                                class="w-7 h-7 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400">
                                <i class="ph ph-caret-left text-sm"></i>
                            </button>
                            <button
                                class="w-7 h-7 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400">
                                <i class="ph ph-caret-right text-sm"></i>
                            </button>
                            <button
                                class="w-7 h-7 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400">
                                <i class="ph ph-arrow-clockwise text-sm"></i>
                            </button>
                        </div>
                        <!-- Address Bar -->
                        <div class="flex-1 mx-2">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg px-4 py-1.5 flex items-center gap-2 border border-gray-200 dark:border-gray-600">
                                <i class="ph ph-lock-simple text-green-600 text-xs"></i>
                                <span
                                    class="text-gray-500 dark:text-gray-400 text-sm truncate">app.oboun-erp.com/dashboard</span>
                            </div>
                        </div>
                        <!-- Browser Actions -->
                        <div class="flex items-center gap-1">
                            <button
                                class="w-7 h-7 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400">
                                <i class="ph ph-star text-sm"></i>
                            </button>
                            <button
                                class="w-7 h-7 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400">
                                <i class="ph ph-dots-three-vertical text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Browser Content - Screenshot -->
                    <div
                        class="aspect-video bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <img src="{{ asset('storage/welcomephoto.png') }}" alt="Oboun ERP Dashboard Preview"
                            class="w-full h-full object-cover object-top">
                    </div>
                </div>
                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-20 h-20 bg-ios-green/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-ios-blue/20 rounded-full blur-3xl"></div>
            </div>

            <!-- Testimonials Summary -->
            <div class="mt-16">
                <div class="text-center mb-10">
                    <!-- 5 Stars -->
                    <div class="flex items-center justify-center gap-1 mb-4">
                        <i class="ph-fill ph-star text-yellow-400 text-2xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-2xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-2xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-2xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2" data-i18n="testimonials_hero_title">
                        What our users are saying
                    </h3>
                    <p class="text-gray-500" data-i18n="testimonials_hero_sub">Loved by pharmacies across Thailand</p>
                </div>

                <!-- Testimonial Cards -->
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="testimonial-card">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            "ใช้ง่ายมาก พนักงานเรียนรู้ได้เร็ว ระบบเตือนหมดอายุช่วยลดการทิ้งยาได้เยอะเลยค่ะ"
                        </p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-ios-blue to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                ก
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">ภญ. กนกวรรณ</div>
                                <div class="text-sm text-gray-500">เจ้าของร้านยา จ.เชียงใหม่</div>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            "รายงานยอดขายดูได้ทันที ช่วยให้ตัดสินใจสั่งสินค้าได้ดีขึ้น ไม่ต้องเดาอีกต่อไป"
                        </p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-ios-green to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                ส
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">ภก. สมชาย</div>
                                <div class="text-sm text-gray-500">เจ้าของร้านยา จ.ขอนแก่น</div>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card">
                        <div class="flex items-center gap-1 mb-4">
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                            <i class="ph-fill ph-star text-yellow-400"></i>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            "ระบบสมาชิกทำให้ลูกค้ากลับมาซื้อซ้ำ ดูประวัติการซื้อได้ บริการลูกค้าได้ดีขึ้น"
                        </p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                น
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">ภญ. นิภา</div>
                                <div class="text-sm text-gray-500">เจ้าของร้านยา กรุงเทพฯ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expiry Crisis Section (Inspired by uploaded image) -->
    <section class="py-24 bg-gray-50/50 dark:bg-black/20 overflow-hidden" id="expiry-crisis">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 scroll-animate">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    <span data-i18n="expiry_title1">Your inventory is</span> <br>
                    <span class="text-underline-red" data-i18n="expiry_title2">out of control</span>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-i18n="expiry_sub">
                    Remember when you knew every item? Now expired drugs are eating your profits.
                </p>
            </div>

            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Left Side Features -->
                <div class="flex-1 space-y-12">
                    <div class="flex gap-6 scroll-animate text-left">
                        <div
                            class="w-12 h-12 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center flex-shrink-0 border border-gray-100 dark:border-gray-700">
                            <i class="ph ph-warning-octagon text-2xl text-red-500"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                                data-i18n="expiry_f1_title">Hidden Losses</h4>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed" data-i18n="expiry_f1_desc">
                                Medicines expiring in the back of the shelf represent thousands of Baht in lost revenue
                                every month.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-6 scroll-animate text-left">
                        <div
                            class="w-12 h-12 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center flex-shrink-0 border border-gray-100 dark:border-gray-700">
                            <i class="ph ph-clock-countdown text-2xl text-orange-500"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                                data-i18n="expiry_f2_title">Manual Chaos</h4>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed" data-i18n="expiry_f2_desc">
                                Searching through rows of products for expiry dates is a waste of your valuable staff
                                time.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-6 scroll-animate text-left">
                        <div
                            class="w-12 h-12 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center flex-shrink-0 border border-gray-100 dark:border-gray-700">
                            <i class="ph ph-money text-2xl text-emerald-500"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                                data-i18n="expiry_f3_title">Profit Drain</h4>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed" data-i18n="expiry_f3_desc">
                                Don't let manual errors quietly drain hundreds from your account every month.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Side Preview (Statement Style) -->
                <div class="flex-1 relative scroll-animate">
                    <div class="absolute -inset-4 bg-red-100/50 dark:bg-red-900/10 rounded-3xl blur-3xl"></div>

                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-[2.5rem] expiry-card-shadow border border-gray-100 dark:border-gray-800 p-8 max-w-md mx-auto">
                        <div
                            class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div class="text-left">
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Alert
                                    Report</p>
                                <h5 class="text-lg font-bold text-gray-900 dark:text-white">OBOUN ERP LOG</h5>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Total Loss</p>
                                <p class="text-xl font-bold text-red-500">-฿4,250</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-left">
                                    <i class="ph-fill ph-pill text-red-400 text-xl"></i>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">Aspirin 500mg</p>
                                        <p class="text-xs text-gray-400">EXP: OCT 04</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-red-500">-฿1,200</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-left">
                                    <i class="ph-fill ph-pill text-orange-400 text-xl"></i>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">Amoxicillin</p>
                                        <p class="text-xs text-gray-400">EXP: OCT 12</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-orange-400">-฿850</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-left">
                                    <i class="ph-fill ph-pill text-red-400 text-xl"></i>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">Paracetamol</p>
                                        <p class="text-xs text-gray-400">EXP: OCT 15</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-red-500">-฿1,500</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-left">
                                    <i class="ph-fill ph-pill text-yellow-400 text-xl"></i>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">Vitamin C</p>
                                        <p class="text-xs text-gray-400">EXP: OCT 21</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-yellow-500">-฿700</span>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-dashed border-gray-200 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-400">End of Monthly Statement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Take Back Control Section (3-Step Process) -->
    <section class="py-24 bg-gray-50/30 dark:bg-black/40" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-20 scroll-animate">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    <span data-i18n="step_title1">Here's how you</span>
                    <span class="text-highlight" data-i18n="step_title2">take back control</span>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-i18n="step_sub">
                    Three simple steps to professionalize your pharmacy management.
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="space-y-32">
                <!-- Step 1 -->
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                    <div class="flex-1 text-left scroll-animate">
                        <span
                            class="inline-block px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 text-[10px] font-bold tracking-widest rounded-full mb-6 uppercase"
                            data-i18n="step1_badge">STEP 1</span>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6" data-i18n="step1_title">
                            Smart Inventory Setup</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-8 leading-relaxed"
                            data-i18n="step1_desc">
                            Import your medicines in seconds. Organize them by category, supplier, or shelving that
                            makes sense for you.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <i class="ph-bold ph-check text-orange-500"></i>
                                <span data-i18n="step1_f1">Bulk barcode import</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <i class="ph-bold ph-check text-orange-500"></i>
                                <span data-i18n="step1_f2">Custom shelving categories</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-1 scroll-animate">
                        <div
                            class="aspect-square bg-orange-50 dark:bg-orange-900/10 rounded-[40px] p-12 flex items-center justify-center relative group">
                            <!-- Inventory Mockup Card -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl w-full max-w-[320px] p-8 expiry-card-shadow transform group-hover:scale-105 transition duration-500">
                                <div class="flex justify-between items-center mb-6">
                                    <h5 class="font-bold text-gray-900 dark:text-white">Medicines</h5>
                                    <button
                                        class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                                        <i class="ph ph-plus-bold"></i>
                                    </button>
                                </div>
                                <div class="space-y-4 text-left">
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-xl">
                                            💊</div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Amoxicillin</p>
                                            <div class="h-1.5 w-16 bg-orange-200 dark:bg-orange-900 rounded-full mt-1">
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400">#420</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-xl">
                                            💧</div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Eye Drops</p>
                                            <div class="h-1.5 w-12 bg-blue-200 dark:bg-blue-900 rounded-full mt-1">
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400">#15</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-xl">
                                            📦</div>
                                        <div class="flex-1 text-xs text-gray-400">Add new product...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-16 lg:gap-24">
                    <div class="flex-1 text-left scroll-animate">
                        <span
                            class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-[10px] font-bold tracking-widest rounded-full mb-6 uppercase"
                            data-i18n="step2_badge">STEP 2</span>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6" data-i18n="step2_title">
                            Predictive Expiry Alerts</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-8 leading-relaxed"
                            data-i18n="step2_desc">
                            Never sell an expired drug again. Get automatic alerts months before the deadline. Decide
                            what to do - before you're stuck with it.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <i class="ph-bold ph-check text-purple-500"></i>
                                <span data-i18n="step2_f1">Custom reminder timing</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <i class="ph-bold ph-check text-purple-500"></i>
                                <span data-i18n="step2_f2">Mobile & Push notifications</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-1 scroll-animate">
                        <div
                            class="aspect-square bg-purple-50 dark:bg-purple-900/10 rounded-[40px] p-12 flex items-center justify-center relative group">
                            <!-- Mobile Notification Mockup -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl w-full max-w-[280px] p-8 expiry-card-shadow transform group-hover:-rotate-3 transition duration-500 border-[6px] border-gray-900 dark:border-gray-950">
                                <div class="w-20 h-1.5 bg-gray-900 dark:bg-gray-950 rounded-full mx-auto mb-10"></div>
                                <div
                                    class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-4 shadow-sm text-left mb-6 border border-purple-100 dark:border-purple-800">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div
                                            class="w-5 h-5 bg-ios-blue rounded flex items-center justify-center text-[10px] text-white font-bold">
                                            O</div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">OBOUN
                                            ERP • NOW</p>
                                    </div>
                                    <h6 class="text-xs font-bold text-gray-900 dark:text-white mb-1">Expiry Alert!</h6>
                                    <p class="text-[10px] text-gray-600 dark:text-gray-400">Vitamin C (Batch B-4) will
                                        expire in 3 months.</p>
                                    <div class="flex gap-2 mt-4">
                                        <div
                                            class="flex-1 h-6 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center text-[8px] font-bold dark:text-gray-300 shadow-sm">
                                            Review</div>
                                        <div
                                            class="flex-1 h-6 bg-red-500 rounded-lg flex items-center justify-center text-[8px] font-bold text-white shadow-sm">
                                            Dismiss</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                    <div class="flex-1 text-left scroll-animate">
                        <span
                            class="inline-block px-3 py-1 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 text-[10px] font-bold tracking-widest rounded-full mb-6 uppercase"
                            data-i18n="step3_badge">STEP 3</span>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6" data-i18n="step3_title">See
                            Where It All Goes</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-8 leading-relaxed"
                            data-i18n="step3_desc">
                            View your total sales, spot duplicates, and track trends over time. Smart analytics help you
                            optimize your stock.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <i class="ph-bold ph-check text-cyan-500"></i>
                                <span data-i18n="step3_f1">Category breakdown charts</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                                <i class="ph-bold ph-check text-cyan-500"></i>
                                <span data-i18n="step3_f2">Monthly & Average projections</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-1 scroll-animate">
                        <div
                            class="aspect-square bg-cyan-50 dark:bg-cyan-900/10 rounded-[40px] p-12 flex items-center justify-center relative group">
                            <!-- Analytics Mockup Card -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl w-full max-w-[340px] p-8 expiry-card-shadow transform group-hover:translate-y-[-10px] transition duration-500">
                                <div class="flex items-center justify-between mb-8">
                                    <h5 class="font-bold text-gray-900 dark:text-white text-sm">Sales Analytics</h5>
                                    <i class="ph ph-dots-three-bold text-gray-400"></i>
                                </div>
                                <div class="relative w-40 h-40 mx-auto mb-8">
                                    <!-- Simple Pie Chart CSS Representation -->
                                    <div class="absolute inset-0 rounded-full border-[12px] border-cyan-400"></div>
                                    <div class="absolute inset-0 rounded-full border-[12px] border-purple-400"
                                        style="clip-path: polygon(50% 50%, 50% 0%, 100% 0%, 100% 50%);"></div>
                                    <div class="absolute inset-0 rounded-full border-[12px] border-orange-400"
                                        style="clip-path: polygon(50% 50%, 100% 50%, 100% 100%, 50% 100%);"></div>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Dec Sales</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">฿125,400</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                                            <span class="text-gray-500">Tablets</span>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">65%</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                                            <span class="text-gray-500">Liquids</span>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">20%</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                            <span class="text-gray-500">Other</span>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">15%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="py-20 bg-white" id="problems">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-animate">
                <span class="inline-block px-4 py-1.5 bg-red-50 text-ios-red text-sm font-semibold rounded-full mb-4"
                    data-i18n="problem_badge">
                    Common Problems
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    <span data-i18n="problem_title">Managing a pharmacy</span> <span class="text-ios-red"
                        data-i18n="problem_title2">shouldn't be this hard</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto" data-i18n="problem_sub">
                    Many stores still use old methods that waste time and risk errors.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="problem-card rounded-2xl p-6 scroll-animate">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-stack text-2xl text-ios-red"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" data-i18n="problem1_title">Difficult Stock
                        Counting</h3>
                    <p class="text-gray-600 text-sm leading-relaxed" data-i18n="problem1_desc">
                        Spending time counting products every month, manual recording, hard to find data.
                    </p>
                </div>

                <div class="problem-card rounded-2xl p-6 scroll-animate">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-calendar-x text-2xl text-ios-red"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" data-i18n="problem2_title">Expired Products
                        Unnoticed</h3>
                    <p class="text-gray-600 text-sm leading-relaxed" data-i18n="problem2_desc">
                        Losing money on expired medicines, no advance warning system.
                    </p>
                </div>

                <div class="problem-card rounded-2xl p-6 scroll-animate">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-user-circle-minus text-2xl text-ios-red"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" data-i18n="problem3_title">Lack of Customer Data
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed" data-i18n="problem3_desc">
                        No purchase history, no allergy records, service not as good as it should be.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solution Section -->
    <section class="py-20 bg-ios-bg" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-animate">
                <span
                    class="inline-block px-4 py-1.5 bg-green-50 text-ios-green text-sm font-semibold rounded-full mb-4"
                    data-i18n="sol_badge">
                    The Solution
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Oboun ERP <span class="text-ios-green" data-i18n="sol_title">can help you</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto" data-i18n="sol_sub">
                    A system designed specifically for pharmacies. Easy to use, complete functionality.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="solution-card rounded-2xl p-6 scroll-animate">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-ios-green text-white rounded-full font-bold text-lg mb-4">
                        1</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" data-i18n="sol1_title">POS Sell Instantly</h3>
                    <p class="text-gray-600 text-sm leading-relaxed" data-i18n="sol1_desc">
                        Easy-to-use sales screen, barcode scanning, fast checkout, multiple payment methods.
                    </p>
                </div>

                <div class="solution-card rounded-2xl p-6 scroll-animate">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-ios-green text-white rounded-full font-bold text-lg mb-4">
                        2</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" data-i18n="sol2_title">Expiry Warning System</h3>
                    <p class="text-gray-600 text-sm leading-relaxed" data-i18n="sol2_desc">
                        Advance notifications before products expire, manage immediately, reduce losses.
                    </p>
                </div>

                <div class="solution-card rounded-2xl p-6 scroll-animate">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-ios-green text-white rounded-full font-bold text-lg mb-4">
                        3</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2" data-i18n="sol3_title">Complete Reports</h3>
                    <p class="text-gray-600 text-sm leading-relaxed" data-i18n="sol3_desc">
                        View sales, profits, best sellers anytime. Make better decisions from real data.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-gray-50/50 dark:bg-black/20" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-ios-blue text-sm font-semibold rounded-full mb-4"
                    data-i18n="features_badge">
                    Full Features
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    <span data-i18n="features_title">Everything a pharmacy needs</span> <span class="text-ios-blue"
                        data-i18n="features_title2">in one place</span>
                </h2>
            </div>

            <div class="bento-grid">
                <!-- Card 1: One Dashboard (Large) -->
                <div
                    class="bento-card md:col-span-2 md:row-span-2 bg-gradient-to-br from-blue-50/50 to-white dark:from-blue-900/10 dark:to-gray-900 overflow-hidden">
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <i class="ph-fill ph-layout text-blue-400 text-xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4" data-i18n="f1_title">One
                            Dashboard</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm" data-i18n="f1_desc">Every pharmacy
                            operation in one place. Stop digging through massive logs.</p>

                        <!-- Mini Dashboard Mockup -->
                        <div
                            class="mt-auto bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-4 transform translate-y-4 translate-x-4">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                                <div class="w-2 h-2 rounded-full bg-green-400"></div>
                            </div>
                            <div class="space-y-3">
                                <div
                                    class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-xs">
                                            💊</div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white">Aspirin Plus</p>
                                            <p class="text-[10px] text-gray-400">Stock: low</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">฿120</span>
                                </div>
                                <div
                                    class="flex justify-between items-center p-2 bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs">
                                            🧪</div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white">Vitamin C</p>
                                            <p class="text-[10px] text-blue-500">Inbound</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">฿550</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Alerts (Medium) -->
                <div class="bento-card md:col-span-2 bg-white dark:bg-gray-900">
                    <div class="flex flex-col md:flex-row gap-8 h-full">
                        <div class="flex-1">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 rounded-full text-[10px] font-bold tracking-widest uppercase mb-4">
                                <i class="ph-bold ph-bell"></i> Alerts
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" data-i18n="f2_title">
                                Never miss a deadline</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm" data-i18n="f2_desc">Get notified
                                before every batch expires. Set your own warning period.</p>
                        </div>
                        <div class="flex-1 flex items-center justify-center">
                            <!-- Alert UI Mockup -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 w-full relative">
                                <div class="flex items-center gap-3 mb-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-lg">
                                        💊</div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-gray-900 dark:text-white">Paracetamol</p>
                                        <p class="text-[10px] text-gray-400 italic">Expires Tomorrow</p>
                                    </div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></div>
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        class="flex-1 text-[10px] font-bold py-1.5 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Dismiss</button>
                                    <button
                                        class="flex-1 text-[10px] font-bold py-1.5 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-black">Manage</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Analytics (Medium) -->
                <div class="bento-card md:col-span-2 bg-white dark:bg-gray-900 overflow-hidden">
                    <div class="flex flex-col md:flex-row gap-8 h-full">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" data-i18n="f3_title">
                                Smart Analytics</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6" data-i18n="f3_desc">See exactly
                                where your profit comes from. Track sales trends.</p>
                            <div class="flex items-end gap-6 h-full">
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">฿45k</p>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Revenue</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-green-500">12%<i
                                            class="ph ph-arrow-down-right"></i></p>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">vs Last
                                        Month</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex items-center justify-center relative">
                            <!-- SVG Graph -->
                            <svg viewBox="0 0 100 40" class="w-full h-auto transform translate-y-4">
                                <path d="M0 35 Q 20 35 40 25 T 80 10 T 100 5" fill="none"
                                    class="graph-line stroke-orange-400" stroke-width="2"
                                    vector-effect="non-scaling-stroke"></path>
                                <path d="M0 35 Q 20 35 40 25 T 80 10 T 100 5 L 100 40 L 0 40 Z" fill="url(#grad)"
                                    opacity="0.1"></path>
                                <defs>
                                    <linearGradient id="grad" x1="0%" y1="0%" x2="0%"
                                        y2="100%">
                                        <stop offset="0%" style="stop-color:rgb(251, 146, 60);stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:rgb(251, 146, 60);stop-opacity:0" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Budget (Small) -->
                <div class="bento-card md:col-span-1 bg-gray-50/50 dark:bg-gray-800/30">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-4" data-i18n="f4_title">
                        Inventory Usage</p>
                    <div class="flex items-end gap-2 mb-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">81%</span>
                        <span class="text-xs text-gray-400 mb-1">/ 100%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="w-[81%] h-full bg-gray-900 dark:bg-white"></div>
                    </div>
                </div>

                <!-- Card 5: Duplicates (Small) -->
                <div class="bento-card md:col-span-1 bg-white dark:bg-gray-900 border-dashed">
                    <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-4">● Safety</p>
                    <div
                        class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 mb-2">
                        <p class="text-xs font-bold text-gray-900 dark:text-white">Drug Clash</p>
                        <p class="text-[10px] text-gray-400">Potential duplicate order!</p>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-auto">Saving potentially: ฿2,500/yr</p>
                </div>

                <!-- Card 6: Categories (Medium/Wide) -->
                <div class="bento-card md:col-span-2 bg-white dark:bg-gray-900">
                    <p class="text-xs font-bold text-gray-900 dark:text-white mb-6" data-i18n="f6_title">Smart
                        Categories</p>
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="px-3 py-1 bg-green-50 dark:bg-green-900/20 text-green-600 rounded-full text-[10px] font-bold">●
                            Tablets</span>
                        <span
                            class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-full text-[10px] font-bold">●
                            Liquids</span>
                        <span
                            class="px-3 py-1 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-full text-[10px] font-bold">●
                            External Use</span>
                        <span
                            class="px-3 py-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 rounded-full text-[10px] font-bold">●
                            Controlled</span>
                        <span
                            class="px-3 py-1 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 rounded-full text-[10px] font-bold">●
                            Health</span>
                        <span
                            class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 rounded-full text-[10px] font-bold">●
                            News</span>
                    </div>
                </div>

                <!-- Card 7: Multi-User (Long) -->
                <div
                    class="bento-card md:col-span-3 bg-gray-100 dark:bg-gray-800/50 border-none relative overflow-hidden">
                    <div class="flex flex-col md:flex-row items-center gap-12 relative z-10">
                        <div class="text-left flex-1">
                            <div
                                class="w-10 h-10 bg-white dark:bg-gray-700 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                                <i class="ph-fill ph-users text-blue-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" data-i18n="f8_title">
                                Multi-User Management</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm max-w-sm" data-i18n="f8_desc">Organize
                                your pharmacy by staff roles and track every action across your team.</p>
                        </div>
                        <div class="flex-1 flex justify-center scale-90 md:scale-100">
                            <!-- Team UI Mockup -->
                            <div
                                class="flex items-center gap-4 bg-white/50 dark:bg-gray-900/50 backdrop-blur p-4 rounded-2xl border border-white/20">
                                <div class="flex -space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800 bg-blue-400 flex items-center justify-center text-white font-bold text-xs ring-4 ring-blue-400/10">
                                        A</div>
                                    <div
                                        class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800 bg-green-400 flex items-center justify-center text-white font-bold text-xs ring-4 ring-green-400/10">
                                        S</div>
                                    <div
                                        class="w-10 h-10 rounded-full border-4 border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 text-xs tracking-tighter italic">
                                        Join</div>
                                </div>
                                <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-700 mx-2"></div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">Team Active</p>
                                    <p class="text-[10px] text-green-500">3 Online now</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Background Dots Pattern -->
                    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.1]"
                        style="background-image: radial-gradient(circle, currentColor 1px, transparent 1px); background-size: 20px 20px;">
                    </div>
                </div>

                <!-- Card 8: Foreign Support (Small/Square) -->
                <div
                    class="bento-card col-span-1 bg-gradient-to-br from-orange-400 to-orange-600 border-none text-white justify-center items-center text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-4">Currency</p>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-xl font-bold">฿</span>
                        <i class="ph-bold ph-swap text-white/50"></i>
                        <span class="text-xl font-bold">$</span>
                    </div>
                    <button
                        class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur rounded-xl text-[10px] font-bold transition">Live
                        Rates</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 bg-gray-50/30 dark:bg-black/40" id="testimonials">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 scroll-animate">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-600 text-sm font-bold rounded-full mb-6 border border-orange-100 dark:border-orange-800">
                    <i class="ph-fill ph-heart"></i>
                    <span data-i18n="testimonials_badge">Community Love</span>
                </span>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    <span data-i18n="testimonials_title">Still not convinced?</span> <br>
                    <span class="text-highlight" data-i18n="testimonials_title2">See what users say</span>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-i18n="testimonials_desc">
                    Real feedback from our community. No cherry-picking, just genuine experiences.
                </p>
            </div>

            <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                <!-- Card 1 -->
                <div class="social-testimonial-card scroll-animate">
                    <div class="testimonial-header">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            ก</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">ภญ. กนกวรรณ</p>
                            <p class="text-[10px] text-gray-400">@pharmacist_k • 2 days ago</p>
                        </div>
                        <i class="ph ph-twitter-logo text-blue-400"></i>
                    </div>
                    <div class="testimonial-body">
                        <div class="quote-icon">“</div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed" data-i18n="t1_text">
                            "ใช้ง่ายมาก พนักงานเรียนรู้ได้เร็ว ระบบเตือนหมดอายุช่วยลดการทิ้งยาได้เยอะเลยค่ะ"
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="social-testimonial-card scroll-animate">
                    <div class="testimonial-header">
                        <div
                            class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                            ส</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">ภก. สมชาย</p>
                            <p class="text-[10px] text-gray-400">@somchai_rx • 1 week ago</p>
                        </div>
                        <i class="ph ph-discord-logo text-indigo-400"></i>
                    </div>
                    <div class="testimonial-body">
                        <div class="quote-icon">“</div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed" data-i18n="t2_text">
                            "รายงานยอดขายดูได้ทันที ช่วยให้ตัดสินใจสั่งสินค้าได้ดีขึ้น ไม่ต้องเดาอีกต่อไป"
                        </p>
                    </div>
                </div>

                <!-- Card 3 (Longer text for masonry effect) -->
                <div class="social-testimonial-card scroll-animate">
                    <div class="testimonial-header">
                        <div
                            class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">
                            น</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">ภญ. นิภา</p>
                            <p class="text-[10px] text-gray-400">@nipa_pharma • Mar 12</p>
                        </div>
                        <i class="ph ph-globe text-gray-400"></i>
                    </div>
                    <div class="testimonial-body">
                        <div class="quote-icon">“</div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4" data-i18n="t3_text">
                            "ระบบสมาชิกทำให้ลูกค้ากลับมาซื้อซ้ำ ดูประวัติการซื้อได้ บริการลูกค้าได้ดีขึ้นมากครับ
                            ตอนแรกกังวลว่าจะใช้ยาก แต่พอได้ลองแล้วพบว่าเมนูต่าง ๆ ออกแบบมาให้เข้าใจง่ายมาก
                            พนักงานพาร์ทไทม์ที่ร้านใช้เวลาเรียนรู้ไม่ถึงครึ่งวันก็คล่องแล้วครับ"
                        </p>
                        <div class="flex items-center gap-1 text-yellow-400 text-xs">
                            <i class="ph-fill ph-star"></i>
                            <i class="ph-fill ph-star"></i>
                            <i class="ph-fill ph-star"></i>
                            <i class="ph-fill ph-star"></i>
                            <i class="ph-fill ph-star"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="social-testimonial-card scroll-animate">
                    <div class="testimonial-header">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">
                            ว</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">คุณ วิชัย</p>
                            <p class="text-[10px] text-gray-400">@vichai_store • 3 days ago</p>
                        </div>
                        <i class="ph ph-facebook-logo text-blue-600"></i>
                    </div>
                    <div class="testimonial-body">
                        <div class="quote-icon">“</div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed" data-i18n="t4_text">
                            "คุ้มค่ามากครับ ระบบช่วยเช็กกำไรรายวันได้เป๊ะมาก
                            ทำให้เรารู้ว่าสินค้าตัวไหนควรสั่งเพิ่มหรือตัวไหนขายออกช้า"
                        </p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="social-testimonial-card scroll-animate">
                    <div class="testimonial-header">
                        <div
                            class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center text-white font-bold">
                            ร</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-white">ภญ. รสลิน</p>
                            <p class="text-[10px] text-gray-400">@rosalin_rx • Just now</p>
                        </div>
                        <i class="ph ph-instagram-logo text-pink-400"></i>
                    </div>
                    <div class="testimonial-body">
                        <div class="quote-icon">“</div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed" data-i18n="t5_text">
                            "Oboun ERP คือผู้ช่วยตัวจริงของร้านยาค่ะ แนะนำเพื่อน ๆ เจ้าของร้านยาหลายคนให้ใช้
                            ทุกคนแฮปปี้มาก"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white" id="faq">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-full mb-4"
                    data-i18n="faq_badge">
                    FAQ
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4" data-i18n="faq_title">
                    Frequently Asked Questions
                </h2>
            </div>

            <div class="space-y-0 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="faq-item p-6">
                    <button class="w-full flex items-center justify-between text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900" data-i18n="faq1_q">Is it difficult to use?</span>
                        <i class="ph ph-plus text-gray-400"></i>
                    </button>
                    <div class="faq-answer hidden mt-4 text-gray-600 text-sm leading-relaxed" data-i18n="faq1_a">
                        Not at all! The system is designed to be easy. Most staff learn within 10-15 minutes.
                    </div>
                </div>

                <div class="faq-item p-6">
                    <button class="w-full flex items-center justify-between text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900" data-i18n="faq2_q">Is the data secure?</span>
                        <i class="ph ph-plus text-gray-400"></i>
                    </button>
                    <div class="faq-answer hidden mt-4 text-gray-600 text-sm leading-relaxed" data-i18n="faq2_a">
                        Yes! All data is encrypted and backed up regularly.
                    </div>
                </div>

                <div class="faq-item p-6">
                    <button class="w-full flex items-center justify-between text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900" data-i18n="faq3_q">Does it support barcode scanners
                            and receipt printers?</span>
                        <i class="ph ph-plus text-gray-400"></i>
                    </button>
                    <div class="faq-answer hidden mt-4 text-gray-600 text-sm leading-relaxed" data-i18n="faq3_a">
                        Yes! Works with standard USB barcode scanners and 80mm receipt printers.
                    </div>
                </div>

                <div class="faq-item p-6">
                    <button class="w-full flex items-center justify-between text-left" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900" data-i18n="faq4_q">Is there after-sales
                            support?</span>
                        <i class="ph ph-plus text-gray-400"></i>
                    </button>
                    <div class="faq-answer hidden mt-4 text-gray-600 text-sm leading-relaxed" data-i18n="faq4_a">
                        Yes! Our support team is available every day via LINE or phone.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-white dark:bg-black/20" id="cta">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-blue-50/30 dark:bg-gray-800/50 rounded-[3rem] p-12 lg:p-20 flex flex-col lg:flex-row items-center justify-between gap-12 overflow-hidden border border-blue-100/50 dark:border-gray-700">
                <div class="flex-1 text-left scroll-animate">
                    <h2 class="text-4xl sm:text-6xl font-black text-gray-900 dark:text-white mb-8 leading-tight"
                        data-i18n="cta_title">
                        Ready to take <br>control?
                    </h2>
                    <p class="text-ios-blue text-xl font-bold mb-4" data-i18n="cta_accent">
                        Start tracking your inventory today.
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-10 max-w-lg" data-i18n="cta_desc">
                        Join hundreds of pharmacies who know exactly what they have in stock. Free trial for up to 30
                        days. No credit card required.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-ios-blue text-white font-bold text-lg rounded-2xl hover:bg-blue-600 transition shadow-lg shadow-blue-500/20">
                            <span data-i18n="cta_btn">Start Free Trial</span>
                            <i class="ph ph-arrow-right font-bold"></i>
                        </a>
                        <a href="#features"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-bold text-lg rounded-2xl border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            <span data-i18n="cta_btn2">View Demo</span>
                            <i class="ph ph-sparkle text-blue-400"></i>
                        </a>
                    </div>
                </div>

                <div class="flex-1 scroll-animate hidden lg:block">
                    <div class="relative">
                        <div class="absolute -inset-10 bg-blue-200/20 rounded-full blur-3xl"></div>
                        <img src="{{ asset('images/cta_celebration.png') }}" alt="Success Celebration"
                            class="relative max-w-md mx-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 bg-ios-bg dark:bg-black border-t border-gray-200/50 dark:border-gray-800/30 text-gray-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between gap-16">
                <!-- Brand Section -->
                <div class="space-y-6">
                    <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;"
                        class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer">
                        @php
                            $logo = \App\Models\Setting::get('store_logo');
                        @endphp
                        @if ($logo)
                            <div class="logo-ring">
                                <img src="{{ Storage::url($logo) }}" alt="Logo" class="object-cover">
                            </div>
                        @else
                            <div class="logo-ring">
                                <div
                                    class="bg-gradient-to-br from-ios-blue to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    O
                                </div>
                            </div>
                        @endif
                        <span class="text-gray-900 dark:text-white font-bold text-xl">Oboun ERP</span>
                    </a>
                    <div class="space-y-4">
                        <p class="text-sm max-w-xs leading-relaxed dark:text-gray-400" data-i18n="footer_desc">
                            Complete pharmacy management with smart tracking.
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500" data-i18n="footer_copy">
                            Copyright © 2026 - All rights reserved
                        </p>
                    </div>
                </div>

                <!-- Right Side Columns -->
                <div class="flex flex-wrap gap-12 lg:gap-24">
                    <!-- Links Column -->
                    <div>
                        <h4 class="text-gray-900 dark:text-white font-bold text-sm mb-6 uppercase tracking-wider"
                            data-i18n="footer_links">Links</h4>
                        <ul class="space-y-4 text-sm">
                            <li><a href="{{ route('login') }}"
                                    class="hover:text-ios-blue transition dark:text-gray-400 dark:hover:text-blue-400"
                                    data-i18n="footer_login">Login</a></li>
                            <li><a href="{{ route('login') }}"
                                    class="hover:text-ios-blue transition dark:text-gray-400 dark:hover:text-blue-400"
                                    data-i18n="footer_dashboard">Dashboard</a></li>
                            <li><a href="#features"
                                    class="hover:text-ios-blue transition dark:text-gray-400 dark:hover:text-blue-400"
                                    data-i18n="footer_features">Features</a></li>
                            <li><a href="#"
                                    class="hover:text-ios-blue transition dark:text-gray-400 dark:hover:text-blue-400"
                                    data-i18n="footer_pricing">Pricing</a></li>
                        </ul>
                    </div>

                    <!-- Legal Column -->
                    <div>
                        <h4 class="text-gray-900 dark:text-white font-bold text-sm mb-6 uppercase tracking-wider"
                            data-i18n="footer_legal">Legal</h4>
                        <ul class="space-y-4 text-sm">
                            <li><a href="{{ route('terms') }}"
                                    class="hover:text-ios-blue transition dark:text-gray-400 dark:hover:text-blue-400"
                                    data-i18n="footer_terms">Terms of service</a></li>
                            <li><a href="{{ route('privacy') }}"
                                    class="hover:text-ios-blue transition dark:text-gray-400 dark:hover:text-blue-400"
                                    data-i18n="footer_privacy">Privacy policy</a></li>
                        </ul>
                    </div>

                    <!-- Social Column -->
                    <div>
                        <h4 class="text-gray-900 dark:text-white font-bold text-sm mb-6 uppercase tracking-wider"
                            data-i18n="footer_social">Social</h4>
                        <ul class="space-y-4 text-sm">
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 hover:text-green-500 transition dark:text-gray-400 dark:hover:text-green-400">
                                    <i class="ph-fill ph-chat-circle-dots text-lg text-green-500"></i>
                                    <span>Line</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 hover:text-blue-600 transition dark:text-gray-400 dark:hover:text-blue-500">
                                    <i class="ph-fill ph-facebook-logo text-lg text-blue-600"></i>
                                    <span>Facebook</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 hover:text-red-500 transition dark:text-gray-400 dark:hover:text-red-400">
                                    <i class="ph-fill ph-envelope-simple text-lg text-red-500"></i>
                                    <span>Gmail</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Translations
        const translations = {
            en: {
                nav_features: 'Features',
                nav_how: 'How It Works',
                nav_reviews: 'Reviews',
                nav_faq: 'FAQ',
                login: 'Login',
                get_started: 'Get Started',
                hero_title1: 'Complete Pharmacy',
                hero_title2: 'Management System',
                hero_sub: 'All-in-one solution for modern pharmacies.',
                expiry_title1: 'Your inventory is',
                expiry_title2: 'out of control',
                expiry_sub: 'Remember when you knew every item? Now expired drugs are eating your profits.',
                expiry_f1_title: 'Hidden Losses',
                expiry_f1_desc: 'Medicines expiring in the back of the shelf represent thousands of Baht in lost revenue every month.',
                expiry_f2_title: 'Manual Chaos',
                expiry_f2_desc: 'Searching through rows of products for expiry dates is a waste of your valuable staff time.',
                expiry_f3_title: 'Profit Drain',
                expiry_f3_desc: 'Don\'t let manual errors quietly drain hundreds from your account every month.',
                step_title1: 'Here\'s how you',
                step_title2: 'take back control',
                step_sub: 'Three simple steps to professionalize your pharmacy management.',
                step1_badge: 'STEP 1',
                step1_title: 'Smart Inventory Setup',
                step1_desc: 'Import your medicines in seconds. Organize them by category, supplier, or shelving.',
                step1_f1: 'Bulk barcode import',
                step1_f2: 'Custom shelving categories',
                step2_badge: 'STEP 2',
                step2_title: 'Predictive Expiry Alerts',
                step2_desc: 'Never sell an expired drug again. Get automatic alerts months before the deadline.',
                step2_f1: 'Custom reminder timing',
                step2_f2: 'Mobile & Push notifications',
                step3_badge: 'STEP 3',
                step3_title: 'See Where It All Goes',
                step3_desc: 'View your total sales, spot duplicates, and track trends over time.',
                step3_f1: 'Category breakdown charts',
                step3_f2: 'Monthly & Average projections',
                hero_cta: 'Start Free',
                hero_cta2: 'View All Features',
                hero_note: '✓ No credit card required &nbsp; ✓ Setup in 5 minutes &nbsp; ✓ Thai language support',
                problem_badge: 'Common Problems',
                problem_title: 'Managing a pharmacy',
                problem_title2: 'shouldn\'t be this hard',
                problem1_title: 'Difficult Stock Counting',
                problem1_desc: 'Spending time counting products every month, manual recording, hard to find data.',
                problem2_title: 'Expired Products Unnoticed',
                problem2_desc: 'Losing money on expired medicines, no advance warning system.',
                problem3_title: 'Lack of Customer Data',
                problem3_desc: 'No purchase history, no allergy records, service not as good as it should be.',
                solution_badge: 'The Solution',
                solution_title: 'Oboun ERP',
                solution_title2: 'helps you',
                sol1_title: 'Instant POS',
                sol1_desc: 'Easy-to-use sales screen, barcode scanning, fast checkout.',
                sol2_title: 'Expiry Alerts',
                sol2_desc: 'Advance notification before products expire, act immediately.',
                sol3_title: 'Complete Reports',
                sol3_desc: 'View sales, profits, best sellers anytime.',
                features_badge: 'Full Features',
                features_title: 'Everything a pharmacy needs',
                features_title2: 'in one place',
                f1_title: 'One Dashboard',
                f1_desc: 'Every pharmacy operation in one place. Stop digging through massive logs.',
                f2_title: 'Never miss a deadline',
                f2_desc: 'Get notified before every batch expires. Set your own warning period.',
                f3_title: 'Smart Analytics',
                f3_desc: 'See exactly where your profit comes from. Track sales trends.',
                f4_title: 'Inventory Usage',
                f6_title: 'Smart Categories',
                f8_title: 'Multi-User Management',
                f8_desc: 'Organize your pharmacy by staff roles and track every action across your team.',
                f1: 'POS System',
                f2: 'Inventory',
                f3: 'Expiry Tracking',
                f4: 'Prescriptions',
                f5: 'Controlled Drugs',
                f6: 'Members',
                f7: 'Reports',
                f8: 'Multi-User',
                testimonials_badge: 'Community Love',
                testimonials_title: 'Still not convinced?',
                testimonials_title2: 'See what users say',
                testimonials_desc: 'Real feedback from our community. No cherry-picking, just genuine experiences.',
                t1_text: '"Very easy to use. Staff learn quickly. The expiry alert system significantly reduces drug waste."',
                t2_text: '"Sales reports are available instantly. Helps in better stock ordering decisions. No more guessing."',
                t3_text: '"Membership system keeps customers coming back. Buying history is easy to track. Service improved a lot. I was worried it would be hard, but the menu is very intuitive. Even part-time staff can use it in less than half a day."',
                t4_text: '"Great value. The system tracks daily profit accurately. We know exactly which items to restock."',
                t5_text: '"Oboun ERP is a true assistant for pharmacists. I have recommended it to many fellow pharmacy owners, and everyone is happy."',
                faq_badge: 'FAQ',
                faq_title: 'Frequently Asked Questions',
                faq1_q: 'Is it difficult to use?',
                faq1_a: 'Not at all! The system is designed to be easy. Most staff learn within 10-15 minutes.',
                faq2_q: 'Is the data secure?',
                faq2_a: 'Yes! All data is encrypted and backed up regularly.',
                faq3_q: 'Does it support barcode scanners and receipt printers?',
                faq3_a: 'Yes! Works with standard USB barcode scanners and 80mm receipt printers.',
                faq4_q: 'Is there after-sales support?',
                faq4_a: 'Yes! Our support team is available every day via LINE or phone.',
                cta_title: 'Ready to take <br>control?',
                cta_accent: 'Start tracking your inventory today.',
                cta_desc: 'Join hundreds of pharmacies who know exactly what they have in stock. Free trial for up to 30 days. No credit card required.',
                cta_btn: 'Start Free Trial',
                cta_btn2: 'View Demo',
                footer_login: 'Login',
                footer_features: 'Features',
                footer_faq: 'FAQ',
                footer_links: 'Links',
                footer_legal: 'Legal',
                footer_social: 'Social',
                footer_dashboard: 'Dashboard',
                footer_pricing: 'Pricing',
                footer_terms: 'Terms of service',
                footer_privacy: 'Privacy policy',
                footer_desc: 'Complete pharmacy management with smart tracking.',
                footer_copy: 'Copyright © 2026 - All rights reserved',
            },
            th: {
                nav_features: 'ฟีเจอร์',
                nav_how: 'วิธีใช้งาน',
                nav_reviews: 'รีวิว',
                nav_faq: 'คำถามที่พบบ่อย',
                login: 'เข้าสู่ระบบ',
                get_started: 'เริ่มต้นใช้งาน',
                hero_title1: 'ระบบจัดการร้านขายยา',
                hero_title2: 'ครบวงจร',
                hero_sub: 'โซลูชั่นครบจบสำหรับร้านขายยายุคใหม่',
                expiry_title1: 'สต็อกสินค้าของคุณกำลัง',
                expiry_title2: 'ควบคุมไม่ได้',
                expiry_sub: 'จำได้ไหมตอนที่คุณคุมสินค้าได้ทุกชิ้น? ตอนนี้ยาหมดอายุกำลังกัดกินกำไรของคุณ',
                expiry_f1_title: 'ความสูญเสียที่มองไม่เห็น',
                expiry_f1_desc: 'ยาที่หมดอายุอยู่หลังชั้นวาง คือรายได้ที่หายไปหลายพันบาทในทุกๆ เดือน',
                expiry_f2_title: 'ความวุ่นวายแบบเดิมๆ',
                expiry_f2_desc: 'การไล่เช็กวันหมดอายุทีละชิ้น คือการเสียเวลาที่มีค่าของพนักงานในร้าน',
                expiry_f3_title: 'กำไรรั่วไหล',
                expiry_f3_desc: 'อย่าปล่อยให้ความผิดพลาดจากการเช็กมือ ค่อยๆ ดูดเงินจากบัญชีของคุณไปทุกเดือน',
                step_title1: 'นี่คือวิธีที่จะช่วยคุณ',
                step_title2: 'ทวงคืนการควบคุม',
                step_sub: '3 ขั้นตอนง่ายๆ เพื่อยกระดับการจัดการร้านยาของคุณให้เป็นมืออาชีพ',
                step1_badge: 'ขั้นที่ 1',
                step1_title: 'ตั้งค่าสต็อกอัจฉริยะ',
                step1_desc: 'นำเข้าข้อมูลยาได้ในพริบตา จัดหมวดหมู่ตามประเภท ซัพพลายเออร์ หรือชั้นวาง',
                step1_f1: 'นำเข้าบาร์โค้ดจำนวนมาก',
                step1_f2: 'จัดการชั้นวางยาแยกตามหมวดหมู่',
                step2_badge: 'ขั้นที่ 2',
                step2_title: 'แจ้งเตือนวันหมดอายุล่วงหน้า',
                step2_desc: 'ไม่ต้องเสี่ยงขายยาหมดอายุอีกต่อไป ด้วยระบบแจ้งเตือนอัตโนมัติหลายเดือนล่วงหน้า',
                step2_f1: 'ตั้งเวลาแจ้งเตือนล่วงหน้าได้เอง',
                step2_f2: 'แจ้งเตือนผ่านมือถือและ Push Notifications',
                step3_badge: 'ขั้นที่ 3',
                step3_title: 'วิเคราะห์ยอดขายแม่นยำ',
                step3_desc: 'ดูกำไร สินค้าขายดี และสินค้าสต็อกต่ำได้จากหน้าจอเดียวแบบเรียลไทม์',
                step3_f1: 'กราฟแยกตามหมวดหมู่สินค้า',
                step3_f2: 'ประมาณการยอดขายและกำไรรายเดือน',
                hero_cta: 'เริ่มต้นใช้งานฟรี',
                hero_cta2: 'ดูฟีเจอร์ทั้งหมด',
                hero_note: '✓ ไม่ต้องใช้บัตรเครดิต &nbsp; ✓ ตั้งค่าง่ายใน 5 นาที &nbsp; ✓ รองรับภาษาไทย',
                problem_badge: 'ปัญหาที่พบบ่อย',
                problem_title: 'การจัดการร้านขายยา',
                problem_title2: 'ไม่ควรยากขนาดนี้',
                problem1_title: 'ยากในการนับสต็อก',
                problem1_desc: 'ใช้เวลานับสินค้าทุกเดือน บันทึกด้วยมือ หาข้อมูลลำบาก',
                problem2_title: 'สินค้าหมดอายุไม่ทันรู้',
                problem2_desc: 'ต้องเสียเงินทิ้งยาหมดอายุ ไม่มีระบบเตือนล่วงหน้า',
                problem3_title: 'ขาดข้อมูลลูกค้า',
                problem3_desc: 'ไม่รู้ประวัติการซื้อ ไม่รู้ว่าลูกค้าแพ้ยาอะไร',
                solution_badge: 'วิธีแก้ปัญหา',
                solution_title: 'Oboun ERP',
                solution_title2: 'ช่วยคุณได้',
                sol1_title: 'POS ขายได้ทันที',
                sol1_desc: 'หน้าจอขายสินค้าที่ใช้ง่าย สแกนบาร์โค้ด คิดเงินไว',
                sol2_title: 'ระบบเตือนหมดอายุ',
                sol2_desc: 'แจ้งเตือนล่วงหน้าก่อนสินค้าหมดอายุ จัดการได้ทันที',
                sol3_title: 'รายงานครบถ้วน',
                sol3_desc: 'ดูยอดขาย กำไร สินค้าขายดี ได้ทุกเมื่อ',
                features_badge: 'ฟีเจอร์ครบครัน',
                features_title: 'ทุกสิ่งที่ร้านขายยาต้องการ',
                features_title2: 'ในที่เดียว',
                f1_title: 'แดชบอร์ดเดียวจบ',
                f1_desc: 'ทุกการดำเนินการในที่เดียว ไม่ต้องเสียเวลาไล่หาจากรายงานหลายหน้า',
                f2_title: 'แจ้งเตือนวันหมดอายุ',
                f2_desc: 'แจ้งเตือนล่วงหน้าก่อนยาหมดอายุ ตั้งเวลาล่วงหน้าได้ตามต้องการ',
                f3_title: 'วิเคราะห์ยอดขาย',
                f3_desc: 'ดูแหล่งที่มาของกำไร และติดตามแนวโน้มการขายได้ทันที',
                f4_title: 'ปริมาณสินค้าคงเหลือ',
                f6_title: 'จัดหมวดหมู่อัจฉริยะ',
                f8_title: 'จัดการสิทธิ์ทีมงาน',
                f8_desc: 'แยกสิทธิ์แอดมินและพนักงาน ติดตามทุกความเคลื่อนไหวในร้าน',
                f1: 'POS System',
                f2: 'จัดการสต็อก',
                f3: 'ติดตามหมดอายุ',
                f4: 'ใบสั่งยา',
                f5: 'ยาควบคุม',
                f6: 'สมาชิก/ลูกค้า',
                f7: 'รายงาน',
                f8: 'หลายผู้ใช้',
                testimonials_badge: 'เสียงตอบรับจากชุมชน',
                testimonials_title: 'ยังไม่แน่ใจใช่ไหม?',
                testimonials_title2: 'ลองฟังเสียงจากผู้ใช้จริง',
                testimonials_desc: 'รีวิวจริงจากผู้ประกอบการร้านยา ไม่มีการคัดกรอง เพื่อประสบการณ์การใช้งานที่แท้จริง',
                t1_text: '"ใช้ง่ายมาก พนักงานเรียนรู้ได้เร็ว ระบบเตือนหมดอายุช่วยลดการทิ้งยาได้เยอะเลยค่ะ"',
                t2_text: '"รายงานยอดขายดูได้ทันที ช่วยให้ตัดสินใจสั่งสินค้าได้ดีขึ้น ไม่ต้องเดาอีกต่อไป"',
                t3_text: '"ระบบสมาชิกทำให้ลูกค้ากลับมาซื้อซ้ำ ดูประวัติการซื้อได้ บริการลูกค้าได้ดีขึ้นมากครับ ตอนแรกกังวลว่าจะใช้ยาก แต่พอได้ลองแล้วพบว่าเมนูต่าง ๆ ออกแบบมาให้เข้าใจง่ายมาก พนักงานพาร์ทไทม์ที่ร้านใช้เวลาเรียนรู้ไม่ถึงครึ่งวันก็คล่องแล้วครับ"',
                t4_text: '"คุ้มค่ามากครับ ระบบช่วยเช็กกำไรรายวันได้เป๊ะมาก ทำให้เรารู้ว่าสินค้าตัวไหนควรสั่งเพิ่มหรือตัวไหนขายออกช้า"',
                t5_text: '"Oboun ERP คือผู้ช่วยตัวจริงของร้านยาค่ะ แนะนำเพื่อน ๆ เจ้าของร้านยาหลายคนให้ใช้ ทุกคนแฮปปี้มาก"',
                faq_badge: 'FAQ',
                faq_title: 'คำถามที่พบบ่อย',
                faq1_q: 'ใช้งานยากไหม?',
                faq1_a: 'ไม่ยากเลยครับ/ค่ะ ระบบออกแบบมาให้ใช้ง่าย พนักงานส่วนใหญ่เรียนรู้ได้ภายใน 10-15 นาที',
                faq2_q: 'ข้อมูลปลอดภัยไหม?',
                faq2_a: 'ปลอดภัยครับ/ค่ะ ข้อมูลทั้งหมดถูกเข้ารหัสและสำรองไว้อย่างสม่ำเสมอ',
                faq3_q: 'รองรับเครื่อง Barcode Scanner และเครื่องพิมพ์ใบเสร็จไหม?',
                faq3_a: 'รองรับครับ/ค่ะ ใช้ได้กับ Barcode Scanner มาตรฐาน USB และเครื่องพิมพ์ใบเสร็จขนาด 80mm',
                faq4_q: 'มีบริการหลังการขายไหม?',
                faq4_a: 'มีครับ/ค่ะ เรามีทีม Support พร้อมช่วยเหลือทุกวัน สามารถติดต่อได้ผ่าน LINE หรือโทรศัพท์',
                cta_title: 'พร้อมที่จะ <br>กลับมาควบคุมสต็อก?',
                cta_accent: 'เริ่มจัดการระบบยาของคุณตั้งแต่วันนี้',
                cta_desc: 'เข้าร่วมกับร้านยากว่าร้อยแห่งที่รู้สถานะสินค้าคงคลังอย่างแม่นยำ ทดลองใช้ฟรี 30 วัน ไม่ต้องใช้บัตรเครดิต',
                cta_btn: 'เริ่มต้นทดลองใช้ฟรี',
                cta_btn2: 'ดูเดโมการใช้งาน',
                footer_login: 'เข้าสู่ระบบ',
                footer_features: 'ฟีเจอร์',
                footer_faq: 'คำถามที่พบบ่อย',
                footer_links: 'ลิงก์',
                footer_legal: 'กฎหมาย',
                footer_social: 'โซเชียล',
                footer_dashboard: 'แดชบอร์ด',
                footer_pricing: 'ราคา',
                footer_terms: 'ข้อกำหนดการใช้งาน',
                footer_privacy: 'นโยบายความเป็นส่วนตัว',
                footer_desc: 'จัดการร้านยาครบวงจรด้วยระบบติดตามอัจฉริยะ',
                footer_copy: 'ลิขสิทธิ์ © 2026 - สงวนลิขสิทธิ์ทั้งหมด',
            }
        };
        let currentLang = localStorage.getItem('landing_lang') || 'en';

        // Theme
        function getSystemTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            const isDark = theme === 'dark' || (theme === 'system' && getSystemTheme() === 'dark');
            document.documentElement.classList.toggle('dark', isDark);
            const icon = document.getElementById('themeIcon');
            if (icon) icon.className = 'ph ph-' + (isDark ? 'moon' : 'sun') + ' text-lg text-gray-600';
            document.querySelectorAll('[id^="theme-"]').forEach(el => el.classList.remove('active'));
            const activeEl = document.getElementById('theme-' + theme);
            if (activeEl) activeEl.classList.add('active');
        }

        function setTheme(theme) {
            localStorage.setItem('landing_theme', theme);
            applyTheme(theme);
        }

        // Language
        function applyLanguage(lang) {
            const t = translations[lang];
            if (!t) return;
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (t[key]) el.innerHTML = t[key];
            });
            document.querySelectorAll('[id^="lang-"]').forEach(el => el.classList.remove('active'));
            document.getElementById('lang-' + lang)?.classList.add('active');
        }

        function setLanguage(lang) {
            currentLang = lang;
            localStorage.setItem('landing_lang', lang);
            applyLanguage(lang);
        }

        // Scroll Animation
        function initScrollAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));
        }

        // FAQ Toggle
        function toggleFaq(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('i');
            if (answer.classList.contains('hidden')) {
                answer.classList.remove('hidden');
                icon.classList.replace('ph-plus', 'ph-minus');
            } else {
                answer.classList.add('hidden');
                icon.classList.replace('ph-minus', 'ph-plus');
            }
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('landing_theme') || 'system';
            applyTheme(savedTheme);
            applyLanguage(currentLang);
            initScrollAnimations();
        });
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (localStorage.getItem('landing_theme') === 'system') applyTheme('system');
        });
    </script>
</body>

</html>

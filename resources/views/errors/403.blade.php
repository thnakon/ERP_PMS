<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.403_title') }} | Oboun ERP</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap"
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
                        'ios-red': '#FF3B30',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'IBM Plex Sans Thai', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'IBM Plex Sans Thai', sans-serif;
            background: radial-gradient(circle at top right, #f0f7ff 0%, #ffffff 45%, #fdf2f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border-radius: 2.5rem;
            width: 100%;
            max-width: 540px;
            padding: 3.5rem 2.5rem;
            text-align: center;
            position: relative;
            z-index: 10;
            animation: card-appear 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes card-appear {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .icon-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05), inset 0 2px 4px rgba(255, 255, 255, 0.8);
            position: relative;
        }

        .icon-circle {
            position: absolute;
            inset: -4px;
            border-radius: 2.25rem;
            background: linear-gradient(135deg, #FF3B30, #FF9500);
            opacity: 0.1;
            z-index: -1;
            animation: pulse-ring 3s infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                opacity: 0.1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.05;
            }

            100% {
                transform: scale(0.95);
                opacity: 0.1;
            }
        }

        .btn-gradient {
            background: linear-gradient(135deg, #007AFF 0%, #0056B3 100%);
            box-shadow: 0 8px 16px rgba(0, 122, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 122, 255, 0.3);
            filter: brightness(1.05);
        }

        .btn-outline {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            border-color: #007AFF;
            color: #007AFF;
            background: #f0f7ff;
        }

        .bg-blobs {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 20s infinite alternate;
        }

        @keyframes float {
            from {
                transform: translate(0, 0) rotate(0deg);
            }

            to {
                transform: translate(100px, 100px) rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="bg-blobs">
        <div class="blob w-[500px] h-[500px] bg-blue-100 -top-48 -left-48" style="animation-duration: 30s;"></div>
        <div class="blob w-[600px] h-[600px] bg-red-50 -bottom-64 -right-64"
            style="animation-delay: -5s; animation-duration: 25s;"></div>
        <div class="blob w-[400px] h-[400px] bg-purple-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
            style="animation-delay: -10s; animation-duration: 35s;"></div>
    </div>

    <div class="glass-card">
        <div class="icon-container">
            <div class="icon-circle"></div>
            <i class="ph-fill ph-lock-key text-5xl text-ios-red"></i>
        </div>

        <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">
            {{ __('errors.403_title') }}
        </h1>

        <p class="text-xl font-semibold text-gray-700 mb-2">
            {{ __('errors.403_message') }}
        </p>

        <p class="text-gray-500 mb-10 max-w-sm mx-auto leading-relaxed">
            {{ __('errors.403_instruction') }}
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.history.back()"
                class="btn-outline px-8 py-3.5 rounded-2xl font-bold flex items-center justify-center gap-2">
                <i class="ph ph-arrow-left"></i>
                {{ __('errors.back_to_previous') }}
            </button>
            <a href="{{ url('/') }}"
                class="btn-gradient text-white px-8 py-3.5 rounded-2xl font-bold flex items-center justify-center gap-2">
                <i class="ph ph-house"></i>
                {{ __('errors.back_to_home') }}
            </a>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100/50">
            <div
                class="flex items-center justify-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
                <span class="w-1.5 h-1.5 rounded-full bg-ios-red animate-pulse"></span>
                Access Restricted
            </div>
        </div>
    </div>

    <!-- Scripts to handle language switching if needed -->
    <div class="fixed top-6 right-6 z-50 flex gap-2">
        <a href="{{ route('lang.switch', 'en') }}"
            class="w-10 h-10 flex items-center justify-center rounded-full bg-white/50 backdrop-blur-md border border-gray-200 text-[10px] font-bold hover:bg-white transition-all {{ app()->getLocale() == 'en' ? 'border-ios-blue text-ios-blue ring-2 ring-ios-blue/10' : 'text-gray-500' }}">EN</a>
        <a href="{{ route('lang.switch', 'th') }}"
            class="w-10 h-10 flex items-center justify-center rounded-full bg-white/50 backdrop-blur-md border border-gray-200 text-[10px] font-bold hover:bg-white transition-all {{ app()->getLocale() == 'th' ? 'border-ios-blue text-ios-blue ring-2 ring-ios-blue/10' : 'text-gray-500' }}">TH</a>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Oboun ERP') }} - Forgot Password</title>

    {{-- Favicon --}}
    @php
        $favicon = \App\Models\Setting::get('store_favicon');
    @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    <style>
        .modal-overlay {
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-overlay.active .modal-content {
            transform: scale(1) translateY(0);
        }
    </style>
</head>

<body class="bg-[#F2F2F7] flex items-center justify-center min-h-screen font-sans p-4">

    <!-- Top Navigation -->
    <div class="fixed top-0 left-0 right-0 p-6 flex items-center justify-between z-50">
        {{-- Back Button --}}
        <a href="{{ route('login') }}"
            class="group flex items-center gap-2 px-4 py-2 bg-white/50 backdrop-blur-md rounded-2xl border border-white dark:border-gray-800 text-gray-700 dark:text-gray-300 hover:bg-white transition-all shadow-sm">
            <i class="ph ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-sm font-bold">Back to Login</span>
        </a>

        {{-- Language Switcher --}}
        <div
            class="flex gap-1 p-1 bg-white/50 backdrop-blur-md rounded-2xl border border-white dark:border-gray-800 shadow-sm">
            <a href="{{ route('lang.switch', 'en') }}"
                class="px-4 py-1.5 text-xs font-black rounded-xl transition-all {{ app()->getLocale() === 'en' ? 'bg-ios-blue text-white shadow-md shadow-blue-500/20' : 'text-gray-400 hover:text-gray-600' }}">
                EN
            </a>
            <a href="{{ route('lang.switch', 'th') }}"
                class="px-4 py-1.5 text-xs font-black rounded-xl transition-all {{ app()->getLocale() === 'th' ? 'bg-ios-blue text-white shadow-md shadow-blue-500/20' : 'text-gray-400 hover:text-gray-600' }}">
                TH
            </a>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <div class="w-full max-w-[400px] animate-fade-in-up">
        {{-- Logo Section --}}
        <div class="flex flex-col items-center mb-4">
            @php
                $logo = \App\Models\Setting::get('store_logo');
            @endphp
            @if ($logo)
                <div class="w-24 h-24 rounded-[2rem] bg-white p-2 shadow-2xl mb-6 flex items-center justify-center">
                    <img src="{{ Storage::url($logo) }}" alt="Logo"
                        class="w-full h-full object-cover rounded-[1.5rem]">
                </div>
            @else
                <div
                    class="w-20 h-20 rounded-3xl bg-gradient-to-br from-ios-blue to-blue-600 flex items-center justify-center shadow-xl mb-6 relative group overflow-hidden">
                    <span class="text-white font-black text-4xl">O</span>
                </div>
            @endif
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Forgot Password?</h1>
            <p class="text-gray-500 mt-2 font-medium text-center">No worries, we'll send you reset instructions.</p>
        </div>

        {{-- Reset Password Form --}}
        <div class="p-0">
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="email"
                        class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.email') }}</label>
                    <div class="relative group">
                        <i
                            class="ph ph-envelope absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                        <input type="email" name="email" id="email"
                            class="input-ios has-icon @error('email') border-red-500 @enderror"
                            placeholder="Enter your email address" required autofocus value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-4">
                    <i class="ph ph-envelope-simple text-xl"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>

            {{-- Back to Login --}}
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                    class="text-sm text-gray-500 font-medium hover:text-ios-blue transition-colors flex items-center justify-center gap-2">
                    <i class="ph ph-arrow-left text-sm"></i>
                    <span>Back to Login</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Apple Style Loading Animation -->
    <div id="pill-loading" class="pill-loading-overlay">
        <div class="apple-spinner">
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
            <div class="apple-spinner-blade"></div>
        </div>
        <div class="loading-text">{{ __('loading') }}</div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    {{-- Flash Messages as Toasts --}}
    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('status')), 'success');
                }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('error')), 'error');
                }
            });
        </script>
    @endif
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Oboun ERP') }} - Login</title>

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
        <a href="{{ route('landing') }}"
            class="group flex items-center gap-2 px-4 py-2 bg-white/50 backdrop-blur-md rounded-2xl border border-white dark:border-gray-800 text-gray-700 dark:text-gray-300 hover:bg-white transition-all shadow-sm">
            <i class="ph ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-sm font-bold">Back</span>
        </a>

        {{-- Right Actions --}}
        <div class="flex items-center gap-3">
            {{-- Demo Info Icon --}}
            <button onclick="toggleModal('demo-modal')"
                class="w-10 h-10 flex items-center justify-center bg-white/50 backdrop-blur-md rounded-2xl border border-white dark:border-gray-800 text-gray-700 dark:text-gray-300 hover:bg-white transition-all shadow-sm">
                <i class="ph ph-info text-xl font-bold"></i>
            </button>

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
    </div>

    {{-- Demo Account Modal --}}
    <div id="demo-modal"
        class="modal-overlay fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
        <div
            class="modal-content w-full max-w-[400px] bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/20">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="ph-fill ph-identification-card text-ios-blue text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Demo Accounts</h3>
                    </div>
                    <button onclick="toggleModal('demo-modal')"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i class="ph ph-x text-gray-400"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div
                        class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                        <p class="text-[10px] font-black text-ios-blue uppercase tracking-widest mb-2">Administrator</p>
                        <p class="text-gray-900 dark:text-white font-bold">admin@oboun.local</p>
                    </div>

                    <div
                        class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                        <p
                            class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">
                            Staff Member</p>
                        <p class="text-gray-900 dark:text-white font-bold">staff@oboun.local</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Common Password</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">password</span>
                        </div>
                    </div>
                </div>

                <button onclick="toggleModal('demo-modal')"
                    class="w-full mt-8 py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-2xl font-bold hover:bg-black dark:hover:bg-gray-100 transition-all">
                    Got it
                </button>
            </div>
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
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('auth.welcome_back') }}</h1>
            <p class="text-gray-500 mt-2 font-medium">{{ __('auth.empowering') }}</p>
        </div>

        {{-- Login Card --}}
        <div class="p-0">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">{{ __('auth.sign_in') }}</h2>
            </div>

            <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-3">
                @csrf

                <div class="space-y-1">
                    <label for="email"
                        class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.email') }}</label>
                    <div class="relative group">
                        <i
                            class="ph ph-envelope absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                        <input type="email" name="email" id="email"
                            class="input-ios has-icon @error('email') border-red-500 @enderror"
                            placeholder="admin@oboun.local" required autofocus value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password"
                        class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.password') }}</label>
                    <div class="relative group">
                        <i
                            class="ph ph-lock absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                        <input type="password" name="password" id="password"
                            class="input-ios has-icon pr-14 @error('password') border-red-500 @enderror"
                            placeholder="••••••••" required>
                        <button type="button" id="toggle-password"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-ios-blue transition-colors focus:outline-none">
                            <i class="ph ph-eye" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between py-0">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember"
                            class="checkbox-ios border-gray-300 text-ios-blue focus:ring-ios-blue cursor-pointer">
                        <span class="text-sm text-gray-600 font-medium">{{ __('auth.remember_me') }}</span>
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-medium text-ios-blue hover:underline">Forgot password?</a>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-4">
                    <span>{{ __('auth.sign_in') }}</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </form>

            {{-- Sign Up & Policy --}}
            <div class="mt-6 text-center space-y-4">
                <p class="text-sm text-gray-500 font-medium">
                    Don't have an account? <a href="{{ route('register') }}"
                        class="text-ios-blue font-bold hover:underline">Sign up</a>
                </p>
                <p class="text-[11px] text-gray-500 leading-relaxed max-w-[280px] mx-auto px-4">
                    By continuing, you agree to our <br>
                    <a href="{{ route('terms') }}"
                        class="no-underline text-gray-700 dark:text-gray-300 hover:text-ios-blue hover:underline transition-all font-semibold">Terms
                        of Service</a> and <a href="{{ route('privacy') }}"
                        class="no-underline text-gray-700 dark:text-gray-300 hover:text-ios-blue hover:underline transition-all font-semibold">Privacy
                        Policy</a>
                </p>
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
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('success')), 'success');
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
    <script>
        // Password Toggle logic
        document.getElementById('toggle-password')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('ph-eye');
                eyeIcon.classList.add('ph-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('ph-eye-slash');
                eyeIcon.classList.add('ph-eye');
            }
        });

        // Global function for toggle modal
        window.toggleModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                if (modal.classList.contains('active')) {
                    modal.classList.remove('active');
                } else {
                    modal.classList.add('active');
                }
            }
        };

        // Close modal on background click
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }
        });
    </script>
</body>

</html>

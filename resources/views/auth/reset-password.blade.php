<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Oboun ERP') }} - Reset Password</title>

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
</head>

<body class="bg-[#F2F2F7] flex items-center justify-center min-h-screen font-sans p-4 text-gray-900">

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
            <h1 class="text-3xl font-bold tracking-tight">{{ __('auth.reset_password_title') }}</h1>
            <p class="text-gray-500 mt-2 font-medium text-center">{{ __('auth.reset_password_subtitle') }}</p>
        </div>

        {{-- Reset Password Form --}}
        <div class="bg-white/40 backdrop-blur-xl rounded-[2.5rem] p-8 shadow-2xl border border-white/50">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="space-y-1">
                    <label for="password"
                        class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.new_password') }}</label>
                    <div class="relative group">
                        <i
                            class="ph ph-lock absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                        <input type="password" name="password" id="password"
                            class="input-ios has-icon @error('password') border-red-500 @enderror"
                            placeholder="{{ __('auth.new_password') }}" required autofocus>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 ml-1 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation"
                        class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.confirm_new_password') }}</label>
                    <div class="relative group">
                        <i
                            class="ph ph-lock-key absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="input-ios has-icon" placeholder="{{ __('auth.confirm_new_password') }}" required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-6">
                    <i class="ph ph-check-circle text-xl"></i>
                    <span>{{ __('auth.reset_password_button') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json(session('status')), 'success');
                }
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast(@json($errors->first()), 'error');
                }
            });
        </script>
    @endif
</body>

</html>

@extends('layouts.auth')

@section('title', 'ยืนยันตัวตน')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <div
                    class="w-20 h-20 mx-auto bg-gradient-to-br from-ios-blue to-blue-600 rounded-3xl flex items-center justify-center shadow-xl mb-6">
                    <span class="text-4xl font-black text-white">O</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">ยืนยันตัวตน</h1>
                <p class="text-gray-500 mt-2">กรุณากรอกรหัส 6 หลักที่ส่งไปยังอีเมลของคุณ</p>
            </div>

            {{-- Card --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-6">
                    @csrf

                    {{-- OTP Input --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">รหัสยืนยัน</label>
                        <div class="flex justify-between gap-2">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-ios-blue focus:ring-2 focus:ring-ios-blue/20 transition-all"
                                    data-index="{{ $i }}" inputmode="numeric" pattern="[0-9]*"
                                    autocomplete="off">
                            @endfor
                        </div>
                        <input type="hidden" name="code" id="code-input">
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-ios-blue to-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        ยืนยัน
                    </button>
                </form>

                {{-- Resend --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500 mb-2">ไม่ได้รับรหัส?</p>
                    <form method="POST" action="{{ route('two-factor.resend') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-ios-blue font-semibold hover:underline">
                            ส่งรหัสใหม่
                        </button>
                    </form>
                </div>

                {{-- Back to Login --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                        <i class="ph ph-arrow-left mr-1"></i>
                        กลับไปหน้าเข้าสู่ระบบ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('code-input');

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    updateHiddenInput();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                    paste.split('').forEach((char, i) => {
                        if (inputs[i]) inputs[i].value = char;
                    });
                    updateHiddenInput();
                    if (paste.length === 6) {
                        inputs[5].focus();
                    }
                });
            });

            function updateHiddenInput() {
                let code = '';
                inputs.forEach(input => code += input.value);
                hiddenInput.value = code;
            }

            inputs[0].focus();
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', '2FA Settings')
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('auth.security') }}
        </p>
        <span>การยืนยันตัวตนสองขั้นตอน (2FA)</span>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Status Card --}}
        <div class="card-ios">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl {{ $user->two_factor_enabled ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                        <i
                            class="ph-fill {{ $user->two_factor_enabled ? 'ph-shield-check text-green-600' : 'ph-shield text-gray-400' }} text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">สถานะ 2FA</h3>
                        <p class="text-sm {{ $user->two_factor_enabled ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $user->two_factor_enabled ? '✓ เปิดใช้งานแล้ว' : '✗ ยังไม่เปิดใช้งาน' }}
                        </p>
                        @if ($user->two_factor_confirmed_at)
                            <p class="text-xs text-gray-400 mt-1">เปิดใช้เมื่อ
                                {{ $user->two_factor_confirmed_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    @if ($user->two_factor_enabled)
                        <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-bold">เปิดใช้งาน</span>
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-500 rounded-full text-sm font-bold">ปิดใช้งาน</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-2xl text-green-700 flex items-center gap-3">
                <i class="ph-fill ph-check-circle text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('status'))
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-2xl text-blue-700 flex items-center gap-3">
                <i class="ph-fill ph-info text-xl"></i>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 flex items-center gap-3">
                <i class="ph-fill ph-warning text-xl"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Enable/Disable Card --}}
        <div class="card-ios">
            @if (!$user->two_factor_enabled)
                {{-- Enable 2FA --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900">เปิดใช้งาน 2FA</h3>
                    <p class="text-gray-500">เพิ่มความปลอดภัยให้บัญชีของคุณด้วยการยืนยันตัวตนสองขั้นตอน ระบบจะส่งรหัส 6
                        หลักไปยังอีเมลของคุณทุกครั้งที่เข้าสู่ระบบ</p>

                    @if (session('2fa_setup_secret'))
                        {{-- Step 2: Confirm Code --}}
                        <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสยืนยัน (ส่งไปที่
                                    {{ $user->email }})</label>
                                <input type="text" name="code" maxlength="6"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ios-blue focus:ring-2 focus:ring-ios-blue/20 text-center text-2xl font-bold tracking-widest"
                                    placeholder="000000" inputmode="numeric" pattern="[0-9]*" required>
                            </div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all">
                                <i class="ph-fill ph-check mr-2"></i>
                                ยืนยันและเปิดใช้งาน
                            </button>
                        </form>
                    @else
                        {{-- Step 1: Request Code --}}
                        <form method="POST" action="{{ route('two-factor.enable') }}">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-ios-blue to-blue-600 text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all">
                                <i class="ph-fill ph-shield-plus mr-2"></i>
                                เปิดใช้งาน 2FA
                            </button>
                        </form>
                    @endif
                </div>
            @else
                {{-- Disable 2FA --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900">ปิดใช้งาน 2FA</h3>
                    <p class="text-gray-500">หากคุณต้องการปิดการยืนยันตัวตนสองขั้นตอน กรุณากรอกรหัสผ่านเพื่อยืนยัน</p>

                    <form method="POST" action="{{ route('two-factor.disable') }}" class="space-y-4">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่านปัจจุบัน</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                placeholder="กรอกรหัสผ่านเพื่อยืนยัน" required>
                        </div>
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all">
                            <i class="ph-fill ph-shield-slash mr-2"></i>
                            ปิดใช้งาน 2FA
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Info Card --}}
        <div class="card-ios bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-info text-blue-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-2">ทำไมต้องเปิดใช้ 2FA?</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• ป้องกันการเข้าถึงบัญชีโดยไม่ได้รับอนุญาต</li>
                        <li>• เพิ่มความปลอดภัยแม้รหัสผ่านถูกเปิดเผย</li>
                        <li>• รหัสยืนยันจะถูกส่งไปยังอีเมลของคุณทุกครั้ง</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="text-center">
            <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-700 font-medium">
                <i class="ph ph-arrow-left mr-1"></i>
                กลับไปหน้าโปรไฟล์
            </a>
        </div>
    </div>
@endsection

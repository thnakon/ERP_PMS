@extends('layouts.app')

@section('title', 'จัดการ Sessions')
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('auth.security') }}
        </p>
        <span>จัดการอุปกรณ์ที่เข้าสู่ระบบ</span>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Info Card --}}
        <div class="card-ios bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-devices text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">อุปกรณ์ที่เข้าสู่ระบบ</h3>
                    <p class="text-sm text-gray-600">ดูรายการอุปกรณ์ทั้งหมดที่เข้าสู่ระบบอยู่ในขณะนี้
                        คุณสามารถออกจากระบบอุปกรณ์อื่นได้หากพบการเข้าถึงที่ไม่รู้จัก</p>
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

        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 flex items-center gap-3">
                <i class="ph-fill ph-warning text-xl"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 flex items-center gap-3">
                <i class="ph-fill ph-warning text-xl"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Sessions List --}}
        <div class="card-ios">
            <h3 class="text-lg font-bold text-gray-900 mb-4">อุปกรณ์ที่เชื่อมต่อ ({{ $sessions->count() }})</h3>

            <div class="space-y-4">
                @foreach ($sessions as $session)
                    <div
                        class="flex items-center justify-between p-4 {{ $session->is_current ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }} rounded-2xl">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl {{ $session->is_current ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                @if ($session->device === 'Desktop')
                                    <i
                                        class="ph-fill ph-desktop {{ $session->is_current ? 'text-green-600' : 'text-gray-500' }} text-xl"></i>
                                @elseif($session->device === 'Mobile')
                                    <i
                                        class="ph-fill ph-device-mobile {{ $session->is_current ? 'text-green-600' : 'text-gray-500' }} text-xl"></i>
                                @else
                                    <i
                                        class="ph-fill ph-device-tablet {{ $session->is_current ? 'text-green-600' : 'text-gray-500' }} text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-900">{{ $session->browser }} บน {{ $session->platform }}
                                    </p>
                                    @if ($session->is_current)
                                        <span
                                            class="px-2 py-0.5 bg-green-500 text-white rounded-full text-xs font-bold">อุปกรณ์นี้</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $session->ip_address }} • {{ $session->last_active }}
                                </p>
                            </div>
                        </div>

                        @if (!$session->is_current)
                            <form method="POST" action="{{ route('sessions.destroy', $session->id) }}"
                                onsubmit="return confirm('ต้องการออกจากระบบอุปกรณ์นี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-100 text-red-600 rounded-xl text-sm font-bold hover:bg-red-200 transition-colors">
                                    <i class="ph ph-sign-out mr-1"></i>
                                    ออกจากระบบ
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Logout All Devices --}}
        @if ($sessions->count() > 1)
            <div class="card-ios border-l-4 border-red-500">
                <h3 class="text-lg font-bold text-gray-900 mb-2">ออกจากระบบอุปกรณ์อื่นทั้งหมด</h3>
                <p class="text-sm text-gray-500 mb-4">หากคุณสงสัยว่ามีการเข้าถึงบัญชีโดยไม่ได้รับอนุญาต
                    คุณสามารถออกจากระบบอุปกรณ์อื่นทั้งหมดได้</p>

                <form method="POST" action="{{ route('sessions.destroy-others') }}"
                    class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="password" placeholder="กรอกรหัสผ่านเพื่อยืนยัน"
                        class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200"
                        required>
                    <button type="submit"
                        class="px-6 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-colors whitespace-nowrap">
                        <i class="ph-fill ph-sign-out mr-2"></i>
                        ออกจากระบบทั้งหมด
                    </button>
                </form>
            </div>
        @endif

        {{-- Back Button --}}
        <div class="text-center">
            <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-700 font-medium">
                <i class="ph ph-arrow-left mr-1"></i>
                กลับไปหน้าโปรไฟล์
            </a>
        </div>
    </div>
@endsection

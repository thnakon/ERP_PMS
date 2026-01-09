@extends('layouts.app')

@section('title', __('users.view_user'))
@section('page-title')
    <div class="welcome-container">
        <a href="{{ route('users.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; display: flex; align-items: center; gap: 4px; text-decoration: none;">
            <i class="ph ph-arrow-left"></i>
            {{ __('users.title') }}
        </a>
        <span>{{ __('users.view_user') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('users.edit', $user) }}"
            class="px-4 py-2 bg-ios-blue hover:brightness-110 text-white font-medium rounded-xl transition flex items-center gap-2">
            <i class="ph ph-pencil-simple"></i>
            {{ __('edit') }}
        </a>
        <button type="button"
            onclick="deleteRow({{ $user->id }}, '{{ $user->name }}', '{{ route('users.destroy', $user) }}')"
            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl transition flex items-center gap-2">
            <i class="ph ph-trash"></i>
            {{ __('delete') }}
        </button>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- User Profile Card --}}
        <div class="card-ios p-6">
            <div class="flex items-start gap-6">
                {{-- Avatar --}}
                @if ($user->avatar)
                    <div class="w-24 h-24 rounded-3xl flex items-center justify-center shadow-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                            class="w-full h-full object-cover">
                    </div>
                @else
                    <div
                        class="w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <i class="ph-fill ph-user text-white text-5xl"></i>
                    </div>
                @endif

                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        </div>
                        <div class="flex gap-2">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-bold {{ $user->role_color }}">
                                @if ($user->role === 'admin')
                                    <i class="ph-fill ph-crown"></i>
                                @elseif ($user->role === 'pharmacist')
                                    <i class="ph-fill ph-first-aid-kit"></i>
                                @else
                                    <i class="ph ph-user"></i>
                                @endif
                                {{ __('users.' . $user->role) }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-bold {{ $user->status_color }}">
                                {{ __('users.' . $user->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Contact Info Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        @if ($user->email)
                            <div>
                                <p class="text-xs text-gray-500">{{ __('users.email') }}</p>
                                <p class="font-semibold text-gray-900">{{ $user->email }}</p>
                            </div>
                        @endif
                        @if ($user->phone)
                            <div>
                                <p class="text-xs text-gray-500">{{ __('users.phone') }}</p>
                                <p class="font-semibold text-gray-900">{{ $user->phone }}</p>
                            </div>
                        @endif
                        @if ($user->position)
                            <div>
                                <p class="text-xs text-gray-500">{{ __('users.position') }}</p>
                                <p class="font-semibold text-gray-900">{{ $user->position }}</p>
                            </div>
                        @endif
                        @if ($user->hired_date)
                            <div>
                                <p class="text-xs text-gray-500">{{ __('users.hired_date') }}</p>
                                <p class="font-semibold text-gray-900">{{ $user->hired_date->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Professional Information --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-briefcase text-purple-500"></i>
                    {{ __('users.professional_info') }}
                </h3>

                <div class="space-y-4">
                    @if ($user->pharmacist_license_no)
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <p class="text-xs text-blue-600 font-medium mb-1">{{ __('users.pharmacist_license_no') }}</p>
                            <p class="text-lg font-bold text-blue-900 font-mono">{{ $user->pharmacist_license_no }}</p>

                            @if ($user->license_expiry)
                                <div class="mt-3 pt-3 border-t border-blue-200">
                                    <p class="text-xs text-blue-600 font-medium mb-1">{{ __('users.license_expiry') }}</p>
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-blue-900">{{ $user->license_expiry->format('d M Y') }}
                                        </p>
                                        @if ($user->license_expiry < now())
                                            <span
                                                class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                                                <i class="ph-fill ph-warning-circle"></i> {{ __('users.license_expired') }}
                                            </span>
                                        @elseif ($user->isLicenseExpiringSoon())
                                            <span
                                                class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">
                                                <i class="ph-fill ph-warning"></i> {{ __('users.license_expiring_soon') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                                <i class="ph-fill ph-check-circle"></i> Valid
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-xl text-center text-gray-400">
                            <i class="ph ph-certificate text-2xl mb-2"></i>
                            <p class="text-sm">ไม่มีข้อมูลใบอนุญาตเภสัชกร</p>
                        </div>
                    @endif

                    @if ($user->hired_date)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">{{ __('users.hired_date') }}</span>
                            <span class="font-semibold text-gray-900">{{ $user->hired_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">ระยะเวลาทำงาน</span>
                            <span
                                class="font-semibold text-gray-900">{{ $user->hired_date->diffForHumans(null, true) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Permissions & Notes --}}
            <div class="space-y-6">
                {{-- Permissions --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-shield-check text-purple-500"></i>
                        {{ __('users.permissions') }}
                    </h3>

                    @if ($user->role === 'admin')
                        <div class="p-4 bg-purple-50 rounded-xl border border-purple-100">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="ph-fill ph-crown text-purple-600 text-xl"></i>
                                <p class="font-bold text-purple-900">{{ __('users.admin') }}</p>
                            </div>
                            <p class="text-sm text-purple-700">{{ __('users.admin_desc') }}</p>
                        </div>
                    @elseif ($user->role === 'pharmacist')
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="ph-fill ph-first-aid-kit text-blue-600 text-xl"></i>
                                <p class="font-bold text-blue-900">{{ __('users.pharmacist') }}</p>
                            </div>
                            <p class="text-sm text-blue-700">{{ __('users.pharmacist_desc') }}</p>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="ph ph-user text-gray-600 text-xl"></i>
                                <p class="font-bold text-gray-900">{{ __('users.staff') }}</p>
                            </div>
                            <p class="text-sm text-gray-700">{{ __('users.staff_desc') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Notes --}}
                @if ($user->notes)
                    <div class="card-ios p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-note text-gray-500"></i>
                            {{ __('users.notes') }}
                        </h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $user->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card-ios p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ph-fill ph-info text-gray-500"></i>
                ข้อมูลบัญชี
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">สร้างเมื่อ</p>
                    <p class="font-semibold text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">อัปเดตล่าสุด</p>
                    <p class="font-semibold text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">User ID</p>
                    <p class="font-semibold text-gray-900 font-mono">#{{ $user->id }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">สถานะบัญชี</p>
                    <p class="font-semibold text-gray-900">{{ __('users.' . $user->status) }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

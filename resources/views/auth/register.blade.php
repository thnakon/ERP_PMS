<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Oboun ERP') }} - {{ __('auth.register_title') }}</title>

    {{-- Favicon --}}
    @php
        $favicon = \App\Models\Setting::get('store_favicon');
    @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ Storage::url($favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @endif

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    <style>
        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 12px 0;
        }

        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #E5E7EB;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .step-dot.active {
            width: 24px;
            border-radius: 10px;
            background: #1C1C1E;
        }

        .step-dot.completed {
            background: #1C1C1E;
        }

        .step-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .step-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .radio-card {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .radio-card:hover {
            border-color: #007AFF;
        }

        .radio-card.selected {
            border-color: #007AFF;
            background: rgba(0, 122, 255, 0.05);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .calendar-day:hover:not(.disabled):not(.full) {
            background: rgba(0, 122, 255, 0.1);
        }

        .calendar-day.selected {
            background: #007AFF;
            color: white;
        }

        .calendar-day.disabled {
            color: #D1D5DB;
            cursor: not-allowed;
        }

        .calendar-day.full {
            color: #EF4444;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        .otp-input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 12px;
            border: 2px solid #E5E7EB;
            transition: all 0.2s;
        }

        .otp-input:focus {
            border-color: #007AFF;
            outline: none;
        }
    </style>
</head>

<body class="bg-[#F2F2F7] flex items-center justify-center min-h-screen font-sans p-4">

    <!-- Top Navigation -->
    <div class="fixed top-0 left-0 right-0 p-6 flex items-center justify-between z-50">
        {{-- Back Button --}}
        <a href="{{ route('login') }}"
            class="group flex items-center gap-2 px-4 py-2 bg-white/50 backdrop-blur-md rounded-2xl border border-white text-gray-700 hover:bg-white transition-all shadow-sm">
            <i class="ph ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-sm font-bold">{{ __('auth.back_to_login') }}</span>
        </a>

        {{-- Language Switcher --}}
        <div class="flex gap-1 p-1 bg-white/50 backdrop-blur-md rounded-2xl border border-white shadow-sm">
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

    <div class="w-full max-w-md mx-auto animate-fade-in-up mt-16 px-4">
        {{-- Logo Section --}}
        <div class="flex flex-col items-center mb-6">
            @php
                $logo = \App\Models\Setting::get('store_logo');
            @endphp
            @if ($logo)
                <div class="w-20 h-20 rounded-[1.5rem] bg-white p-2 shadow-xl mb-4 flex items-center justify-center">
                    <img src="{{ Storage::url($logo) }}" alt="Logo"
                        class="w-full h-full object-cover rounded-[1rem]">
                </div>
            @else
                <div
                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-ios-blue to-blue-600 flex items-center justify-center shadow-lg mb-4">
                    <span class="text-white font-black text-2xl">O</span>
                </div>
            @endif
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight text-center">{{ __('auth.register_title') }}
            </h1>
            <p class="text-gray-500 mt-2 text-sm text-center max-w-sm">{{ __('auth.register_subtitle') }}</p>
        </div>

        {{-- Registration Form --}}
        <form id="registrationForm" action="{{ route('register.store') }}" method="POST">
            @csrf

            {{-- Step Indicator Card --}}
            <div class="card-ios p-4 mb-4">
                <div class="step-indicator" id="stepIndicator">
                    <div class="step-dot active" data-step="1"></div>
                    <div class="step-dot" data-step="2"></div>
                    <div class="step-dot" data-step="3"></div>
                    <div class="step-dot" data-step="4"></div>
                    <div class="step-dot" data-step="5"></div>
                </div>
            </div>

            {{-- Step 1: Basic Info --}}
            <div class="step-content active" data-step="1">
                <div class="card-ios p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="ph ph-user-circle text-ios-blue"></i> {{ __('auth.step1_title') }}
                    </h3>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.line_id') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-chat-circle-dots absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="text" name="line_id" id="line_id" class="input-ios has-icon"
                                placeholder="@yourid" required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.registrant_name') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="text" name="registrant_name" id="registrant_name" class="input-ios has-icon"
                                placeholder="{{ app()->getLocale() === 'th' ? 'ชื่อ-นามสกุล' : 'Full Name' }}"
                                required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.email') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="email" name="email" id="email" class="input-ios has-icon"
                                placeholder="your@email.com" required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.contact_phone') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="tel" name="phone" id="phone" class="input-ios has-icon"
                                placeholder="0812345678" required>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="nextStep()"
                    class="w-full mt-6 py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span>{{ __('auth.next') }}</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>

            {{-- Step 2: Business Info --}}
            <div class="step-content" data-step="2">
                <div class="card-ios p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="ph ph-storefront text-ios-blue"></i> {{ __('auth.step2_title') }}
                    </h3>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.business_name') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="text" name="business_name" id="business_name" class="input-ios has-icon"
                                placeholder="{{ app()->getLocale() === 'th' ? 'ชื่อร้านหรือบริษัท' : 'Store or Company Name' }}"
                                required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.business_type') }} <span
                                class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="radio-card p-4 rounded-2xl border-2 border-gray-200 cursor-pointer transition-all"
                                onclick="selectRadioCard(this, 'business_type', 'pharmacy')">
                                <input type="radio" name="business_type" value="pharmacy" class="hidden" required>
                                <div class="flex flex-col items-center gap-2">
                                    <i class="ph ph-first-aid-kit text-3xl text-green-500"></i>
                                    <span class="text-sm font-semibold text-gray-700">{{ __('auth.pharmacy') }}</span>
                                </div>
                            </label>
                            <label
                                class="radio-card p-4 rounded-2xl border-2 border-gray-200 cursor-pointer transition-all"
                                onclick="selectRadioCard(this, 'business_type', 'other')">
                                <input type="radio" name="business_type" value="other" class="hidden">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="ph ph-buildings text-3xl text-gray-400"></i>
                                    <span
                                        class="text-sm font-semibold text-gray-700">{{ __('auth.not_pharmacy') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.tax_id') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="text" name="tax_id" id="tax_id" class="input-ios has-icon"
                                placeholder="{{ app()->getLocale() === 'th' ? 'หมายเลข 13 หลัก' : '13-digit number' }}"
                                required maxlength="13">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.address') }} <span
                                class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="3" class="input-ios"
                            placeholder="{{ app()->getLocale() === 'th' ? 'ที่อยู่เต็ม รวมถึงจังหวัด รหัสไปรษณีย์' : 'Full address including city, postal code' }}"
                            required></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="prevStep()"
                        class="flex-1 py-3.5 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>{{ __('auth.back') }}</span>
                    </button>
                    <button type="button" onclick="nextStep()"
                        class="flex-1 py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 transition-all flex items-center justify-center gap-2">
                        <span>{{ __('auth.next') }}</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- Step 3: Installation --}}
            <div class="step-content" data-step="3">
                <div class="card-ios p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="ph ph-calendar text-ios-blue"></i> {{ __('auth.step3_title') }}
                    </h3>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.device_count') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-desktop absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <select name="device_count" id="device_count" class="input-ios has-icon" required>
                                <option value="1">1 {{ __('auth.device_unit') }}</option>
                                <option value="2">2 {{ __('auth.device_unit') }}</option>
                                <option value="3">3 {{ __('auth.device_unit') }}</option>
                                <option value="4">4 {{ __('auth.device_unit') }}</option>
                                <option value="5+">{{ __('auth.devices_more') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.install_date') }} <span
                                class="text-red-500">*</span></label>
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" onclick="changeMonth(-1)"
                                    class="p-2 hover:bg-gray-200 rounded-full transition">
                                    <i class="ph ph-caret-left"></i>
                                </button>
                                <span id="calendarMonth" class="font-bold text-gray-900"></span>
                                <button type="button" onclick="changeMonth(1)"
                                    class="p-2 hover:bg-gray-200 rounded-full transition">
                                    <i class="ph ph-caret-right"></i>
                                </button>
                            </div>
                            <div class="calendar-grid text-center text-xs font-bold text-gray-400 mb-2"
                                id="calendarHeader"></div>
                            <div class="calendar-grid" id="calendarDays"></div>
                            <input type="hidden" name="install_date" id="install_date" required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.time') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <select name="install_time" id="install_time" class="input-ios has-icon" required>
                                <option value="">{{ __('auth.select_date_first') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="prevStep()"
                        class="flex-1 py-3.5 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>{{ __('auth.back') }}</span>
                    </button>
                    <button type="button" onclick="nextStep()"
                        class="flex-1 py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 transition-all flex items-center justify-center gap-2">
                        <span>{{ __('auth.next') }}</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- Step 4: Additional Info --}}
            <div class="step-content" data-step="4">
                <div class="card-ios p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="ph ph-info text-ios-blue"></i> {{ __('auth.step4_title') }}
                    </h3>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.previous_software') }}
                            <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <label class="radio-card flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-200"
                                onclick="selectRadioCard(this, 'previous_software', 'none')">
                                <input type="radio" name="previous_software" value="none" class="hidden"
                                    required>
                                <div
                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div
                                        class="radio-dot w-3 h-3 rounded-full bg-ios-blue scale-0 transition-transform">
                                    </div>
                                </div>
                                <span class="font-medium text-gray-700">{{ __('auth.no_previous') }}</span>
                            </label>
                            <label class="radio-card flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-200"
                                onclick="selectRadioCard(this, 'previous_software', 'other')">
                                <input type="radio" name="previous_software" value="other" class="hidden">
                                <div
                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div
                                        class="radio-dot w-3 h-3 rounded-full bg-ios-blue scale-0 transition-transform">
                                    </div>
                                </div>
                                <span class="font-medium text-gray-700">{{ __('auth.used_other') }}</span>
                            </label>
                        </div>
                        <input type="text" name="previous_software_name" id="previous_software_name"
                            class="input-ios mt-2 hidden" placeholder="{{ __('auth.specify_software') }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.data_migration') }} <span
                                class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <label class="radio-card flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-200"
                                onclick="selectRadioCard(this, 'data_migration', 'none')">
                                <input type="radio" name="data_migration" value="none" class="hidden" required>
                                <div
                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div
                                        class="radio-dot w-3 h-3 rounded-full bg-ios-blue scale-0 transition-transform">
                                    </div>
                                </div>
                                <span class="font-medium text-gray-700">{{ __('auth.no_migration') }}</span>
                            </label>
                            <label class="radio-card flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-200"
                                onclick="selectRadioCard(this, 'data_migration', 'new')">
                                <input type="radio" name="data_migration" value="new" class="hidden">
                                <div
                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div
                                        class="radio-dot w-3 h-3 rounded-full bg-ios-blue scale-0 transition-transform">
                                    </div>
                                </div>
                                <span class="font-medium text-gray-700 text-sm">{{ __('auth.new_import') }}</span>
                            </label>
                            <label class="radio-card flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-200"
                                onclick="selectRadioCard(this, 'data_migration', 'transfer')">
                                <input type="radio" name="data_migration" value="transfer" class="hidden">
                                <div
                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div
                                        class="radio-dot w-3 h-3 rounded-full bg-ios-blue scale-0 transition-transform">
                                    </div>
                                </div>
                                <span class="font-medium text-gray-700 text-sm">{{ __('auth.transfer_data') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.referral_source') }} <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <i
                                class="ph ph-megaphone absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 group-focus-within:text-ios-blue transition-colors"></i>
                            <select name="referral_source" id="referral_source" class="input-ios has-icon" required>
                                <option value="">{{ __('auth.please_specify') }}</option>
                                <option value="facebook">Facebook</option>
                                <option value="line">LINE</option>
                                <option value="google">Google</option>
                                <option value="friend">{{ __('auth.friend_referral') }}</option>
                                <option value="association">{{ __('auth.pharmacy_association') }}</option>
                                <option value="other">{{ __('auth.other') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('auth.notes') }}</label>
                        <textarea name="notes" id="notes" rows="2" class="input-ios"
                            placeholder="{{ __('auth.notes_optional') }}"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="prevStep()"
                        class="flex-1 py-3.5 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>{{ __('auth.back') }}</span>
                    </button>
                    <button type="button" onclick="nextStep()"
                        class="flex-1 py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 transition-all flex items-center justify-center gap-2">
                        <span>{{ __('auth.next') }}</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- Step 5: Agreement & Submit --}}
            <div class="step-content" data-step="5">
                <div class="card-ios p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="ph ph-shield-check text-ios-blue"></i> {{ __('auth.step5_title') }}
                    </h3>

                    <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 text-sm text-gray-500 space-y-3">
                        <p>{{ __('auth.privacy_intro') }}</p>
                        <p>{{ __('auth.privacy_promise') }}</p>
                        <p><a href="{{ route('privacy') }}" target="_blank"
                                class="text-ios-blue hover:underline">{{ __('auth.read_privacy') }}</a>
                        </p>
                    </div>

                    <label
                        class="flex items-start gap-3 cursor-pointer p-4 rounded-2xl border-2 border-gray-200 hover:border-ios-blue transition-colors">
                        <input type="checkbox" name="terms_accepted" id="terms_accepted" class="checkbox-ios mt-0.5"
                            required>
                        <span class="text-sm text-gray-700">{!! __('auth.terms_agree') !!}</span>
                    </label>

                    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                        <div class="flex items-start gap-3">
                            <i class="ph-fill ph-info text-ios-blue text-xl mt-0.5"></i>
                            <div class="text-sm text-gray-700">
                                <p class="font-semibold mb-1">{{ __('auth.after_register') }}</p>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• {{ __('auth.verify_email_info') }}</li>
                                    <li>• {{ __('auth.credentials_info') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="prevStep()"
                        class="flex-1 py-3.5 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>{{ __('auth.back') }}</span>
                    </button>
                    <button type="submit"
                        class="flex-1 py-3.5 bg-green-500 text-white rounded-2xl font-bold shadow-lg shadow-green-500/25 hover:brightness-110 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                        <span>{{ __('auth.submit_registration') }}</span>
                    </button>
                </div>
            </div>
        </form>

        {{-- Email Verification Modal (Hidden by default) --}}
        <div id="verificationModal"
            class="hidden fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
            <div class="w-full max-w-[400px] bg-white rounded-[2rem] shadow-2xl p-8 text-center">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-6">
                    <i class="ph ph-envelope-simple text-3xl text-ios-blue"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('auth.verify_email_title') }}</h3>
                <p class="text-sm text-gray-500 mb-6">{{ __('auth.verify_email_desc') }}<br><span id="verifyEmail"
                        class="font-semibold text-gray-700"></span></p>

                <div class="flex justify-center gap-2 mb-6">
                    <input type="text" maxlength="1" class="otp-input" data-index="0">
                    <input type="text" maxlength="1" class="otp-input" data-index="1">
                    <input type="text" maxlength="1" class="otp-input" data-index="2">
                    <input type="text" maxlength="1" class="otp-input" data-index="3">
                    <input type="text" maxlength="1" class="otp-input" data-index="4">
                    <input type="text" maxlength="1" class="otp-input" data-index="5">
                </div>

                <button type="button" id="verifyBtn"
                    class="w-full py-3.5 bg-ios-blue text-white rounded-2xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 transition-all">
                    {{ __('auth.verify') }}
                </button>

                <p class="text-sm text-gray-400 mt-4">
                    {{ __('auth.no_code') }} <button type="button"
                        class="text-ios-blue font-semibold hover:underline">{{ __('auth.resend') }}</button>
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <script>
        let currentStep = 1;
        const totalSteps = 5;
        let currentMonth = new Date();
        const locale = '{{ app()->getLocale() }}';

        // Translation data
        const translations = {
            stepOf: '{{ __('auth.step_of') }}',
            selectTime: '{{ __('auth.select_time') }}',
            monthNames: locale === 'th' ? ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม',
                'สิงหาคม', 'กันยายน',
                'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ] : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                'November', 'December'
            ],
            dayNames: locale === 'th' ? ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'] : ['Sun', 'Mon', 'Tue', 'Wed', 'Thu',
                'Fri', 'Sat'
            ]
        };

        function updateStepIndicator() {
            document.querySelectorAll('.step-dot').forEach((dot, index) => {
                dot.classList.remove('active', 'completed');
                if (index + 1 < currentStep) {
                    dot.classList.add('completed');
                } else if (index + 1 === currentStep) {
                    dot.classList.add('active');
                }
            });
        }

        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');
            updateStepIndicator();
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function selectRadioCard(element, name, value) {
            document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
                input.closest('label').classList.remove('selected');
                const dot = input.closest('label').querySelector('.radio-dot');
                if (dot) dot.style.transform = 'scale(0)';
            });
            element.classList.add('selected');
            element.querySelector('input').checked = true;
            const dot = element.querySelector('.radio-dot');
            if (dot) dot.style.transform = 'scale(1)';

            if (name === 'previous_software') {
                const nameField = document.getElementById('previous_software_name');
                if (value === 'other') {
                    nameField.classList.remove('hidden');
                    nameField.required = true;
                } else {
                    nameField.classList.add('hidden');
                    nameField.required = false;
                }
            }
        }

        // Calendar functions
        function renderCalendar() {
            const year = currentMonth.getFullYear();
            const month = currentMonth.getMonth();
            const displayYear = locale === 'th' ? year + 543 : year;
            document.getElementById('calendarMonth').textContent = `${translations.monthNames[month]} ${displayYear}`;

            // Render header
            document.getElementById('calendarHeader').innerHTML = translations.dayNames.map(d => `<span>${d}</span>`).join(
                '');

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let html = '';
            for (let i = 0; i < firstDay; i++) {
                html += '<div class="calendar-day"></div>';
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const isPast = date < today;
                const isFull = [1, 2, 5, 7, 8, 9].includes(day) && month === 0;

                let classes = 'calendar-day';
                if (isPast) classes += ' disabled';
                else if (isFull) classes += ' full';

                html += `<div class="${classes}" onclick="selectDate(${year}, ${month}, ${day}, this)">${day}</div>`;
            }

            document.getElementById('calendarDays').innerHTML = html;
        }

        function changeMonth(delta) {
            currentMonth.setMonth(currentMonth.getMonth() + delta);
            renderCalendar();
        }

        function selectDate(year, month, day, element) {
            if (element.classList.contains('disabled') || element.classList.contains('full')) return;

            document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');

            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            document.getElementById('install_date').value = dateStr;

            const timeSelect = document.getElementById('install_time');
            timeSelect.innerHTML = `
                <option value="">${translations.selectTime}</option>
                <option value="09:00">09:00 - 10:00</option>
                <option value="10:00">10:00 - 11:00</option>
                <option value="11:00">11:00 - 12:00</option>
                <option value="13:00">13:00 - 14:00</option>
                <option value="14:00">14:00 - 15:00</option>
                <option value="15:00">15:00 - 16:00</option>
                <option value="16:00">16:00 - 17:00</option>
            `;
        }

        // OTP Input handling
        document.querySelectorAll('.otp-input').forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Only allow numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value.length === 1) {
                    const nextInput = document.querySelector(`.otp-input[data-index="${index + 1}"]`);
                    if (nextInput) nextInput.focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value) {
                    const prevInput = document.querySelector(`.otp-input[data-index="${index - 1}"]`);
                    if (prevInput) prevInput.focus();
                }
            });
        });

        // Form submission handling
        document.getElementById('registrationForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<i class="ph ph-spinner animate-spin"></i> <span>{{ app()->getLocale() === 'th' ? 'กำลังส่ง...' : 'Sending...' }}</span>';

            try {
                const response = await fetch('{{ route('register.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show email in modal
                    document.getElementById('verifyEmail').textContent = data.email;

                    // Clear OTP inputs
                    document.querySelectorAll('.otp-input').forEach(input => input.value = '');

                    // Show verification modal
                    document.getElementById('verificationModal').classList.remove('hidden');

                    // Focus first OTP input
                    setTimeout(() => {
                        document.querySelector('.otp-input[data-index="0"]').focus();
                    }, 300);

                    // For development - show code in console
                    if (data.dev_code) {
                        console.log('Development OTP Code:', data.dev_code);
                        // Auto-fill for development
                        const code = data.dev_code.split('');
                        document.querySelectorAll('.otp-input').forEach((input, i) => {
                            input.value = code[i] || '';
                        });
                    }
                } else {
                    showToast(data.message || 'เกิดข้อผิดพลาด', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // OTP Verification
        document.getElementById('verifyBtn').addEventListener('click', async function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            let code = '';
            otpInputs.forEach(input => code += input.value);

            if (code.length !== 6) {
                showToast(
                    '{{ app()->getLocale() === 'th' ? 'กรุณากรอกรหัส 6 หลัก' : 'Please enter 6-digit code' }}',
                    'error');
                return;
            }

            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="ph ph-spinner animate-spin"></i>';

            try {
                const response = await fetch('{{ route('register.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        code: code
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Hide verification modal
                    document.getElementById('verificationModal').classList.add('hidden');

                    // Show success modal with credentials
                    showSuccessModal(data.credentials);
                } else {
                    showToast(data.message ||
                        '{{ app()->getLocale() === 'th' ? 'รหัสไม่ถูกต้อง' : 'Invalid code' }}', 'error');
                    // Clear OTP inputs
                    otpInputs.forEach(input => input.value = '');
                    otpInputs[0].focus();
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });

        // Success Modal
        function showSuccessModal(credentials) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm';
            modal.innerHTML = `
                <div class="w-full max-w-sm bg-white rounded-3xl shadow-2xl p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ph-fill ph-check-circle text-4xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ app()->getLocale() === 'th' ? 'ลงทะเบียนสำเร็จ!' : 'Registration Successful!' }}</h3>
                    <p class="text-xs text-gray-500 mb-4">{{ app()->getLocale() === 'th' ? 'ข้อมูลสำหรับเข้าสู่ระบบถูกส่งไปยังอีเมลของท่านแล้ว' : 'Login credentials have been sent to your email' }}</p>

                    <div class="bg-gray-50 rounded-xl p-3 text-left space-y-2 mb-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-semibold text-gray-400 uppercase">Admin</span>
                            <span class="text-xs font-mono font-bold text-gray-800">${credentials.admin_email}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-semibold text-gray-400 uppercase">Staff</span>
                            <span class="text-xs font-mono font-bold text-gray-800">${credentials.staff_email}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                            <span class="text-[10px] font-semibold text-gray-400 uppercase">{{ app()->getLocale() === 'th' ? 'รหัสผ่าน' : 'Password' }}</span>
                            <span class="text-sm font-mono font-bold text-ios-blue">${credentials.password}</span>
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-xl p-3 mb-4 flex items-start gap-2 text-left">
                        <i class="ph-fill ph-warning text-orange-500 text-lg mt-0.5"></i>
                        <p class="text-xs text-orange-700 leading-relaxed">
                            {{ app()->getLocale() === 'th' ? 'กรุณาจดหรือบันทึก ID และรหัสผ่านไว้ให้ดี เพื่อใช้เข้าสู่ระบบในครั้งถัดไป' : 'Please save your ID and password securely for future login.' }}
                        </p>
                    </div>

                    <a href="{{ route('login') }}" class="block w-full py-3 bg-ios-blue text-white rounded-xl font-bold shadow-lg shadow-blue-500/25 hover:brightness-110 transition-all text-sm">
                        {{ app()->getLocale() === 'th' ? 'ไปหน้าเข้าสู่ระบบ' : 'Go to Login' }}
                    </a>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Toast function (if not already defined)
        if (typeof showToast !== 'function') {
            window.showToast = function(message, type = 'info') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                const icons = {
                    success: 'ph-fill ph-check-circle text-green-500',
                    error: 'ph-fill ph-x-circle text-red-500',
                    warning: 'ph-fill ph-warning text-orange-500',
                    info: 'ph-fill ph-info text-blue-500'
                };
                toast.className = 'toast';
                toast.innerHTML = `
                    <i class="${icons[type]} toast-icon"></i>
                    <span class="toast-message">${message}</span>
                `;
                container.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('toast-exit');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            };
        }

        // Initialize
        renderCalendar();
        updateStepIndicator();
    </script>
</body>

</html>

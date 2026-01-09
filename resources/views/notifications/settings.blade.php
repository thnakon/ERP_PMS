@extends('layouts.app')

@section('title', __('notifications.notification_settings'))

@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('notifications.title') }}
        </p>
        <span>{{ __('notifications.notification_settings') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('notifications.index') }}"
        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        <span>{{ __('back') }}</span>
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <form action="{{ route('notifications.settings.save') }}" method="POST">
            @csrf

            {{-- Section 1: Notification Channels Overview --}}
            <div class="bg-white/80 backdrop-blur-md rounded-[2rem] p-6 border border-white shadow-xl mb-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="ph-bold ph-bell-ringing text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-xl text-gray-900">{{ __('notifications.channels') }}</h2>
                        <p class="text-sm text-gray-500">{{ __('notifications.channels_desc') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Push Notifications Card --}}
                    <label
                        class="relative p-5 bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl cursor-pointer hover:shadow-lg transition group border-2 border-transparent has-[:checked]:border-purple-500">
                        <input type="checkbox" name="enable_push" value="1"
                            {{ $settings['enable_push'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="absolute top-4 right-4">
                            <div
                                class="w-6 h-6 rounded-full border-2 border-purple-300 peer-checked:bg-purple-500 peer-checked:border-purple-500 flex items-center justify-center transition">
                                <i class="ph-bold ph-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </div>
                        <div
                            class="w-14 h-14 rounded-2xl bg-purple-500 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <i class="ph-bold ph-device-mobile text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Push Notification</h3>
                        <p class="text-xs text-gray-500">{{ __('notifications.push_desc') }}</p>
                    </label>

                    {{-- Email Card --}}
                    <label
                        class="relative p-5 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl cursor-pointer hover:shadow-lg transition group border-2 border-transparent has-[:checked]:border-blue-500">
                        <input type="checkbox" name="enable_email" value="1"
                            {{ $settings['enable_email'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="absolute top-4 right-4">
                            <div
                                class="w-6 h-6 rounded-full border-2 border-blue-300 peer-checked:bg-blue-500 peer-checked:border-blue-500 flex items-center justify-center transition">
                                <i class="ph-bold ph-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </div>
                        <div
                            class="w-14 h-14 rounded-2xl bg-blue-500 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <i class="ph-bold ph-envelope text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                        <p class="text-xs text-gray-500">{{ __('notifications.email_desc') }}</p>
                    </label>

                    {{-- LINE Card --}}
                    <label
                        class="relative p-5 bg-gradient-to-br from-green-50 to-green-100/50 rounded-2xl cursor-pointer hover:shadow-lg transition group border-2 border-transparent has-[:checked]:border-green-500">
                        <input type="checkbox" name="enable_line" value="1"
                            {{ $settings['enable_line'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="absolute top-4 right-4">
                            <div
                                class="w-6 h-6 rounded-full border-2 border-green-300 peer-checked:bg-green-500 peer-checked:border-green-500 flex items-center justify-center transition">
                                <i class="ph-bold ph-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </div>
                        <div
                            class="w-14 h-14 rounded-2xl bg-green-500 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <i class="ph-bold ph-chat-circle-dots text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">LINE</h3>
                        <p class="text-xs text-gray-500">{{ __('notifications.line_desc') }}</p>
                    </label>
                </div>
            </div>

            {{-- Section 2: Email Configuration --}}
            <div class="bg-white/80 backdrop-blur-md rounded-[2rem] p-6 border border-white shadow-xl mb-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                        <i class="ph-bold ph-envelope text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="font-bold text-xl text-gray-900">{{ __('notifications.email_settings') }}</h2>
                        <p class="text-sm text-gray-500">Gmail SMTP Configuration</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Gmail</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Sender Email --}}
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold text-gray-700 uppercase tracking-widest">{{ __('notifications.sender_email') }}</label>
                        <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-3">
                            <i class="ph ph-at text-gray-400"></i>
                            <input type="email" name="mail_from"
                                value="{{ $settings['mail_from'] ?? 'obounerp@gmail.com' }}"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 font-medium">
                        </div>
                    </div>

                    {{-- Sender Name --}}
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold text-gray-700 uppercase tracking-widest">{{ __('notifications.sender_name') }}</label>
                        <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-3">
                            <i class="ph ph-user text-gray-400"></i>
                            <input type="text" name="mail_from_name"
                                value="{{ $settings['mail_from_name'] ?? 'OBOUN ERP' }}"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 font-medium">
                        </div>
                    </div>

                    {{-- Recipient Email --}}
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold text-gray-700 uppercase tracking-widest">{{ __('notifications.recipient_email') }}</label>
                        <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-3">
                            <i class="ph ph-envelope text-gray-400"></i>
                            <input type="email" name="mail_to" value="{{ $settings['mail_to'] ?? '' }}"
                                placeholder="admin@example.com"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 font-medium">
                        </div>
                        <p class="text-[10px] text-gray-400">{{ __('notifications.recipient_email_help') }}</p>
                    </div>

                    {{-- App Password --}}
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold text-gray-700 uppercase tracking-widest">{{ __('notifications.gmail_app_password') }}</label>
                        <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-3">
                            <i class="ph ph-key text-gray-400"></i>
                            <input type="password" name="mail_password" id="mail_password"
                                value="{{ $settings['mail_password'] ?? '' }}" placeholder="xxxx xxxx xxxx xxxx"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 font-medium font-mono">
                            <button type="button" onclick="toggleTokenVisibility('mail_password', 'eye-mail')"
                                class="text-gray-400 hover:text-gray-600">
                                <i class="ph ph-eye" id="eye-mail"></i>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-400">
                            {{ __('notifications.gmail_app_password_help') }}
                            <a href="https://myaccount.google.com/apppasswords" target="_blank"
                                class="text-blue-500 underline">Google App Passwords</a>
                        </p>
                    </div>
                </div>

                {{-- Test Email Button --}}
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="testEmailConnection()" data-no-loading
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                        {{ __('notifications.test_email') }}
                    </button>
                    <div id="email-test-result" class="hidden mt-3 text-center py-2 rounded-xl font-medium"></div>
                </div>
            </div>

            {{-- Section 3: LINE Configuration --}}
            <div class="bg-white/80 backdrop-blur-md rounded-[2rem] p-6 border border-white shadow-xl mb-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                        <i class="ph-bold ph-chat-circle-dots text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="font-bold text-xl text-gray-900">LINE Messaging API</h2>
                        <p class="text-sm text-gray-500">{{ __('notifications.line_messaging_desc') }}</p>
                    </div>
                    <a href="https://developers.line.biz/console/" target="_blank"
                        class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full hover:bg-green-200 transition">
                        <i class="ph ph-arrow-square-out mr-1"></i>Console
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    {{-- Channel Access Token --}}
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold text-green-700 uppercase tracking-widest">{{ __('notifications.line_channel_token') }}</label>
                        <div class="flex gap-2">
                            <input type="password" name="line_channel_token" id="line_channel_token"
                                value="{{ $settings['line_channel_token'] ?? '' }}"
                                placeholder="Channel Access Token (Long-lived)"
                                class="flex-1 bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-mono text-xs focus:ring-2 focus:ring-green-500/20 focus:border-green-400">
                            <button type="button" onclick="toggleTokenVisibility('line_channel_token', 'eye-icon-token')"
                                class="px-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition">
                                <i class="ph ph-eye text-gray-500" id="eye-icon-token"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Channel Secret --}}
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-green-700 uppercase tracking-widest">{{ __('notifications.line_channel_secret') }}</label>
                            <div class="flex gap-2">
                                <input type="password" name="line_channel_secret" id="line_channel_secret"
                                    value="{{ $settings['line_channel_secret'] ?? '' }}" placeholder="Channel Secret"
                                    class="flex-1 bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-mono text-xs focus:ring-2 focus:ring-green-500/20 focus:border-green-400">
                                <button type="button"
                                    onclick="toggleTokenVisibility('line_channel_secret', 'eye-icon-secret')"
                                    class="px-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition">
                                    <i class="ph ph-eye text-gray-500" id="eye-icon-secret"></i>
                                </button>
                            </div>
                        </div>

                        {{-- User/Group ID --}}
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-green-700 uppercase tracking-widest">{{ __('notifications.line_user_id') }}</label>
                            <input type="text" name="line_user_id" id="line_user_id"
                                value="{{ $settings['line_user_id'] ?? '' }}"
                                placeholder="Uxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-mono text-xs focus:ring-2 focus:ring-green-500/20 focus:border-green-400">
                        </div>
                    </div>

                    <p class="text-xs text-gray-500">
                        <i class="ph ph-info mr-1"></i>{{ __('notifications.line_user_id_help') }}
                    </p>
                </div>

                {{-- Test LINE Button --}}
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="testLineConnection()" data-no-loading
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                        {{ __('notifications.test_line') }}
                    </button>
                    <div id="line-test-result" class="hidden mt-3 text-center py-2 rounded-xl font-medium"></div>
                </div>
            </div>

            {{-- Section 4: Timing Settings --}}
            <div class="bg-white/80 backdrop-blur-md rounded-[2rem] p-6 border border-white shadow-xl mb-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-6">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center">
                        <i class="ph-bold ph-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-xl text-gray-900">{{ __('notifications.timing_settings') }}</h2>
                        <p class="text-sm text-gray-500">{{ __('notifications.timing_desc') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Expiry Days --}}
                    <div class="flex items-center justify-between p-4 bg-orange-50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                                <i class="ph-bold ph-calendar-x text-orange-500"></i>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900">{{ __('notifications.expiry_days_before') }}</span>
                                <p class="text-xs text-gray-500">{{ __('notifications.expiry_days_help') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" name="expiry_days_before"
                                value="{{ $settings['expiry_days_before'] }}"
                                class="w-20 text-center bg-white border border-orange-200 rounded-xl py-2 font-bold text-gray-900 focus:ring-2 focus:ring-orange-500/20">
                            <span class="text-gray-500 text-sm font-medium">{{ __('days') }}</span>
                        </div>
                    </div>

                    {{-- Refill Days --}}
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="ph-bold ph-pill text-blue-500"></i>
                            </div>
                            <div>
                                <span class="font-bold text-gray-900">{{ __('notifications.refill_days_before') }}</span>
                                <p class="text-xs text-gray-500">{{ __('notifications.refill_days_help') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" name="refill_days_before"
                                value="{{ $settings['refill_days_before'] }}"
                                class="w-20 text-center bg-white border border-blue-200 rounded-xl py-2 font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20">
                            <span class="text-gray-500 text-sm font-medium">{{ __('days') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/20 transition active-scale flex items-center gap-3">
                    <i class="ph-bold ph-check text-xl"></i>
                    {{ __('notifications.save_settings') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleTokenVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        }

        async function testLineConnection() {
            const channelToken = document.getElementById('line_channel_token').value;
            const userId = document.getElementById('line_user_id').value;
            const resultDiv = document.getElementById('line-test-result');

            if (!channelToken || !userId) {
                resultDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                resultDiv.classList.add('bg-red-100', 'text-red-700');
                resultDiv.textContent = '{{ __('notifications.line_token_required') }}';
                return;
            }

            resultDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
            resultDiv.classList.add('bg-gray-100', 'text-gray-600');
            resultDiv.textContent = '{{ __('notifications.testing') }}...';

            try {
                const res = await fetch('{{ route('notifications.test-line') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        channel_token: channelToken,
                        user_id: userId
                    })
                });

                const data = await res.json();

                resultDiv.classList.remove('bg-gray-100', 'text-gray-600');
                if (data.success) {
                    resultDiv.classList.add('bg-green-100', 'text-green-700');
                    resultDiv.textContent = '✅ {{ __('notifications.line_test_success') }}';
                } else {
                    resultDiv.classList.add('bg-red-100', 'text-red-700');
                    resultDiv.textContent = '❌ ' + (data.message || '{{ __('notifications.line_test_failed') }}');
                }
            } catch (error) {
                resultDiv.classList.remove('bg-gray-100', 'text-gray-600');
                resultDiv.classList.add('bg-red-100', 'text-red-700');
                resultDiv.textContent = '❌ {{ __('notifications.line_test_failed') }}';
            }
        }

        async function testEmailConnection() {
            const mailTo = document.querySelector('input[name="mail_to"]').value;
            const mailPassword = document.getElementById('mail_password').value;
            const resultDiv = document.getElementById('email-test-result');

            if (!mailTo) {
                resultDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                resultDiv.classList.add('bg-red-100', 'text-red-700');
                resultDiv.textContent = '{{ __('notifications.email_required') }}';
                return;
            }

            resultDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
            resultDiv.classList.add('bg-gray-100', 'text-gray-600');
            resultDiv.textContent = '{{ __('notifications.testing') }}...';

            try {
                const res = await fetch('{{ route('notifications.test-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        mail_to: mailTo
                    })
                });

                const data = await res.json();

                resultDiv.classList.remove('bg-gray-100', 'text-gray-600');
                if (data.success) {
                    resultDiv.classList.add('bg-green-100', 'text-green-700');
                    resultDiv.textContent = '✅ {{ __('notifications.email_test_success') }}';
                } else {
                    resultDiv.classList.add('bg-red-100', 'text-red-700');
                    resultDiv.textContent = '❌ ' + (data.message || '{{ __('notifications.email_test_failed') }}');
                }
            } catch (error) {
                resultDiv.classList.remove('bg-gray-100', 'text-gray-600');
                resultDiv.classList.add('bg-red-100', 'text-red-700');
                resultDiv.textContent = '❌ {{ __('notifications.email_test_failed') }}';
            }
        }
    </script>
@endpush

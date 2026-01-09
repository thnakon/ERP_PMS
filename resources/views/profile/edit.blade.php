@extends('layouts.app')

@section('title', __('profile.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('settings') }}
        </p>
        <span>{{ __('profile.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Profile Info & Settings --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-ios-blue"></i>
                    {{ __('profile.personal_info') }}
                </h3>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="input-ios @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.email') }}</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="input-ios @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.phone') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="input-ios @error('phone') border-red-500 @enderror" placeholder="081-234-5678">
                            @error('phone')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.position') }}</label>
                            <input type="text" value="{{ $user->position ?? '-' }}" class="input-ios bg-gray-50"
                                disabled>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl transition flex items-center gap-2">
                            <i class="ph ph-check"></i>
                            {{ __('profile.update_profile') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-lock text-orange-500"></i>
                    {{ __('profile.change_password') }}
                </h3>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.current_password') }}</label>
                        <div class="relative group">
                            <i
                                class="ph ph-lock absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                            <input type="password" name="current_password"
                                class="input-ios has-icon pr-14 @error('current_password') border-red-500 @enderror"
                                placeholder="••••••••">
                            <button type="button"
                                class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-ios-blue transition-colors focus:outline-none">
                                <i class="ph ph-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.new_password') }}</label>
                            <div class="relative group">
                                <i
                                    class="ph ph-key absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                                <input type="password" name="password"
                                    class="input-ios has-icon pr-14 @error('password') border-red-500 @enderror"
                                    placeholder="••••••••">
                                <button type="button"
                                    class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-ios-blue transition-colors focus:outline-none">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('profile.confirm_password') }}</label>
                            <div class="relative group">
                                <i
                                    class="ph ph-key absolute left-5 top-1/2 -translate-y-1/2 text-xl text-gray-400/80 group-focus-within:text-ios-blue transition-colors"></i>
                                <input type="password" name="password_confirmation" class="input-ios has-icon pr-14"
                                    placeholder="••••••••">
                                <button type="button"
                                    class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-ios-blue transition-colors focus:outline-none">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 ml-1">
                        <i class="ph ph-info"></i> {{ __('profile.password_requirements') }}
                    </p>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
                            <i class="ph ph-shield-check"></i>
                            {{ __('profile.update_password') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Language Preference --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-globe text-purple-500"></i>
                    {{ __('profile.language_preference') }}
                </h3>

                <form action="{{ route('profile.language') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="language" value="th" class="peer sr-only"
                                {{ app()->getLocale() === 'th' ? 'checked' : '' }}>
                            <div
                                class="p-4 rounded-2xl border-2 border-gray-200 bg-white peer-checked:border-ios-blue peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">🇹🇭</span>
                                    <div>
                                        <p class="font-bold text-gray-900">ไทย</p>
                                        <p class="text-xs text-gray-500">Thai Language</p>
                                    </div>
                                </div>
                                <div
                                    class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-ios-blue peer-checked:bg-ios-blue flex items-center justify-center">
                                    <i class="ph-bold ph-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="language" value="en" class="peer sr-only"
                                {{ app()->getLocale() === 'en' ? 'checked' : '' }}>
                            <div
                                class="p-4 rounded-2xl border-2 border-gray-200 bg-white peer-checked:border-ios-blue peer-checked:bg-blue-50 transition-all hover:border-gray-300">
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">🇬🇧</span>
                                    <div>
                                        <p class="font-bold text-gray-900">English</p>
                                        <p class="text-xs text-gray-500">English Language</p>
                                    </div>
                                </div>
                                <div
                                    class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-ios-blue peer-checked:bg-ios-blue flex items-center justify-center">
                                    <i class="ph-bold ph-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
                            <i class="ph ph-translate"></i>
                            {{ __('profile.update_language') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger Zone --}}
            <div class="card-ios p-6 border-2 border-red-200 bg-red-50/30">
                <h3 class="text-lg font-bold text-red-600 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-warning text-red-500"></i>
                    {{ __('profile.danger_zone') }}
                </h3>

                <div class="space-y-4">
                    <div class="p-4 bg-white rounded-xl border border-red-100">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-trash text-red-500 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ __('profile.delete_account') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('profile.delete_account_warning') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-red-100">
                            <button type="button" onclick="openDeleteAccountModal()"
                                class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                                <i class="ph ph-trash"></i>
                                {{ __('profile.delete_my_account') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Activity --}}
        <div class="space-y-6">
            {{-- Profile Card with Avatar Upload --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-camera text-pink-500"></i>
                    {{ __('profile.profile_photo') }}
                </h3>

                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data"
                    id="avatar-form">
                    @csrf
                    @method('PATCH')

                    <div class="text-center">
                        {{-- Avatar Preview --}}
                        <div class="relative inline-block group">
                            <div id="avatar-preview-container"
                                class="w-28 h-28 mx-auto rounded-3xl overflow-hidden shadow-lg mb-4 transition-transform group-hover:scale-105">
                                @if ($user->avatar)
                                    <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}"
                                        alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div id="avatar-default"
                                        class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center">
                                        <i class="ph-fill ph-user text-white text-5xl"></i>
                                    </div>
                                    <img id="avatar-preview" src="" alt=""
                                        class="w-full h-full object-cover hidden">
                                @endif
                            </div>

                            {{-- Overlay for upload --}}
                            <label for="avatar-input"
                                class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <div class="text-white text-center">
                                    <i class="ph-bold ph-camera text-2xl"></i>
                                    <p class="text-xs mt-1">{{ __('profile.change_photo') }}</p>
                                </div>
                            </label>

                            {{-- Remove button --}}
                            @if ($user->avatar)
                                <button type="button" id="remove-avatar-btn"
                                    class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="ph-bold ph-x text-sm"></i>
                                </button>
                            @endif
                        </div>

                        <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/webp"
                            class="hidden">
                        <input type="hidden" name="remove_avatar" id="remove-avatar" value="0">

                        {{-- User Info --}}
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->position ?? '-' }}</p>

                        <div class="flex justify-center gap-2 mt-3">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $user->role_color }}">
                                @if ($user->role === 'admin')
                                    <i class="ph-fill ph-crown"></i>
                                @elseif ($user->role === 'pharmacist')
                                    <i class="ph-fill ph-first-aid-kit"></i>
                                @else
                                    <i class="ph ph-user"></i>
                                @endif
                                {{ __('users.' . $user->role) }}
                            </span>
                        </div>

                        {{-- Drag & Drop Zone --}}
                        <div id="avatar-dropzone"
                            class="mt-4 p-4 border-2 border-dashed border-gray-200 rounded-2xl hover:border-ios-blue hover:bg-blue-50/50 transition-all cursor-pointer">
                            <i class="ph ph-cloud-arrow-up text-2xl text-gray-400"></i>
                            <p class="text-xs text-gray-500 mt-1">{{ __('profile.drag_drop_photo') }}</p>
                            <p class="text-xs text-gray-400">JPG, PNG, WebP • {{ __('profile.max_2mb') }}</p>
                        </div>

                        @error('avatar')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror

                        {{-- Submit Button --}}
                        <button type="submit" id="avatar-submit-btn"
                            class="mt-4 px-6 py-2.5 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-xl transition flex items-center gap-2 mx-auto hidden">
                            <i class="ph ph-upload-simple"></i>
                            {{ __('profile.upload_photo') }}
                        </button>

                        <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-gray-500">
                            <p>{{ __('profile.member_since') }}</p>
                            <p class="font-semibold text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Login History --}}
            <div class="card-ios p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-clock-counter-clockwise text-green-500"></i>
                        {{ __('profile.login_history') }}
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">{{ __('profile.last_30_days') }}</span>
                </div>

                @if ($loginHistory->count() > 0)
                    <div class="space-y-3">
                        @foreach ($loginHistory as $log)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center {{ $log->action === 'login' ? 'bg-green-100' : 'bg-gray-200' }}">
                                    <i
                                        class="{{ $log->action === 'login' ? 'ph-fill ph-sign-in text-green-600' : 'ph-fill ph-sign-out text-gray-500' }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm">
                                        {{ $log->action === 'login' ? __('profile.login') : __('profile.logout') }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">{{ $log->ip_address }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-900">{{ $log->logged_at->format('H:i') }}</p>
                                    <p class="text-xs text-gray-400">{{ $log->logged_at->format('d/m') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="ph ph-clock text-3xl mb-2"></i>
                        <p class="text-sm">{{ __('profile.no_login_history') }}</p>
                    </div>
                @endif
            </div>

            {{-- Recent Activity --}}
            <div class="card-ios p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-activity text-blue-500"></i>
                        {{ __('profile.recent_activity') }}
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">{{ __('profile.last_7_days') }}</span>
                </div>

                @if ($recentActivity->count() > 0)
                    <div class="space-y-3">
                        @foreach ($recentActivity as $activity)
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center {{ $activity->action_color }}">
                                    <i class="{{ $activity->action_icon }} text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 leading-tight">
                                        {{ $activity->description ?? __('activity_logs.' . $activity->action) }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $activity->logged_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('activity-logs.index', ['user' => $user->id]) }}"
                        class="block mt-4 text-center text-sm font-medium text-ios-blue hover:underline">
                        {{ __('profile.view_all') }} →
                    </a>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="ph ph-activity text-3xl mb-2"></i>
                        <p class="text-sm">{{ __('profile.no_recent_activity') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div id="delete-account-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl animate-scale-up mx-4">
            <div class="text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-warning text-red-500 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ __('profile.delete_account') }}</h3>
                <p class="text-gray-500 mt-2">{{ __('profile.delete_confirm_text') }}</p>
            </div>

            <form action="{{ route('profile.destroy') }}" method="POST" id="delete-account-form">
                @csrf
                @method('DELETE')

                <div class="space-y-4">
                    {{-- Password Confirmation --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-sm font-semibold text-gray-700">{{ __('profile.enter_password_to_confirm') }}</label>
                        <div class="relative group">
                            <i
                                class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 group-focus-within:text-red-500 transition-colors"></i>
                            <input type="password" name="password" id="delete-password" required
                                class="input-ios has-icon pr-12 border-red-200 focus:border-red-500 focus:ring-red-500/20"
                                placeholder="{{ __('profile.your_password') }}">
                            <button type="button"
                                class="toggle-password-delete absolute right-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-red-500 transition-colors">
                                <i class="ph ph-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="ph ph-info"></i> {{ __('profile.password_required_for_delete') }}
                        </p>
                    </div>

                    {{-- Type DELETE to confirm --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-sm font-semibold text-gray-700">{{ __('profile.type_delete_to_confirm') }}</label>
                        <input type="text" id="delete-confirm-text"
                            class="input-ios border-red-200 focus:border-red-500 focus:ring-red-500/20"
                            placeholder="DELETE" autocomplete="off">
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeDeleteAccountModal()"
                        class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                        {{ __('cancel') }}
                    </button>
                    <button type="submit" id="confirm-delete-btn" disabled
                        class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ __('profile.delete_permanently') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('ph-eye');
                    icon.classList.add('ph-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('ph-eye-slash');
                    icon.classList.add('ph-eye');
                }
            });
        });

        // Avatar Upload Functionality
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarDefault = document.getElementById('avatar-default');
        const avatarSubmitBtn = document.getElementById('avatar-submit-btn');
        const avatarDropzone = document.getElementById('avatar-dropzone');
        const removeAvatarBtn = document.getElementById('remove-avatar-btn');
        const removeAvatarInput = document.getElementById('remove-avatar');
        const avatarForm = document.getElementById('avatar-form');

        // File input change handler
        avatarInput?.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });

        // Dropzone click
        avatarDropzone?.addEventListener('click', function() {
            avatarInput.click();
        });

        // Drag and drop
        avatarDropzone?.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-ios-blue', 'bg-blue-50');
        });

        avatarDropzone?.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-ios-blue', 'bg-blue-50');
        });

        avatarDropzone?.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-ios-blue', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    // Create a new FileList-like object
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    avatarInput.files = dataTransfer.files;
                    handleFileSelect(file);
                } else {
                    alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น (JPG, PNG, WebP)');
                }
            }
        });

        // Remove avatar button
        removeAvatarBtn?.addEventListener('click', function() {
            if (confirm('ต้องการลบรูปโปรไฟล์หรือไม่?')) {
                removeAvatarInput.value = '1';
                avatarInput.value = '';

                // Show default avatar
                if (avatarDefault) {
                    avatarDefault.classList.remove('hidden');
                }
                avatarPreview.classList.add('hidden');
                avatarPreview.src = '';

                // Show submit button
                avatarSubmitBtn.classList.remove('hidden');
                avatarSubmitBtn.innerHTML = '<i class="ph ph-trash"></i> ลบรูปโปรไฟล์';
                avatarSubmitBtn.classList.remove('bg-pink-500', 'hover:bg-pink-600');
                avatarSubmitBtn.classList.add('bg-red-500', 'hover:bg-red-600');
            }
        });

        function handleFileSelect(file) {
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('ไฟล์มีขนาดใหญ่เกินไป กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2MB');
                avatarInput.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น (JPG, PNG, WebP)');
                avatarInput.value = '';
                return;
            }

            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                if (avatarDefault) {
                    avatarDefault.classList.add('hidden');
                }
                avatarPreview.classList.remove('hidden');
                avatarPreview.src = e.target.result;

                // Reset remove flag
                removeAvatarInput.value = '0';

                // Show uploading message and auto-submit after short delay
                avatarSubmitBtn.classList.remove('hidden');
                avatarSubmitBtn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> กำลังอัปโหลด...';
                avatarSubmitBtn.classList.remove('bg-red-500', 'hover:bg-red-600');
                avatarSubmitBtn.classList.add('bg-pink-500', 'hover:bg-pink-600');
                avatarSubmitBtn.disabled = true;

                // Auto-submit after brief preview (500ms)
                setTimeout(() => {
                    avatarForm.submit();
                }, 500);
            };
            reader.readAsDataURL(file);
        }

        // Delete Account Modal Functions
        function openDeleteAccountModal() {
            const modal = document.getElementById('delete-account-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Reset form
            document.getElementById('delete-password').value = '';
            document.getElementById('delete-confirm-text').value = '';
            document.getElementById('confirm-delete-btn').disabled = true;
        }

        function closeDeleteAccountModal() {
            const modal = document.getElementById('delete-account-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Enable delete button only when "DELETE" is typed
        document.getElementById('delete-confirm-text')?.addEventListener('input', function() {
            const confirmBtn = document.getElementById('confirm-delete-btn');
            const password = document.getElementById('delete-password').value;
            if (this.value === 'DELETE' && password.length > 0) {
                confirmBtn.disabled = false;
            } else {
                confirmBtn.disabled = true;
            }
        });

        document.getElementById('delete-password')?.addEventListener('input', function() {
            const confirmBtn = document.getElementById('confirm-delete-btn');
            const confirmText = document.getElementById('delete-confirm-text').value;
            if (confirmText === 'DELETE' && this.value.length > 0) {
                confirmBtn.disabled = false;
            } else {
                confirmBtn.disabled = true;
            }
        });

        // Toggle password visibility for delete modal
        document.querySelector('.toggle-password-delete')?.addEventListener('click', function() {
            const input = document.getElementById('delete-password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        });

        // Close modal on backdrop click
        document.getElementById('delete-account-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteAccountModal();
            }
        });
    </script>
@endpush

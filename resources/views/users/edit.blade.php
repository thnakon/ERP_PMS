@extends('layouts.app')

@section('title', __('users.edit_user'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('users.title') }}
        </p>
        <span>{{ __('users.edit_user') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('users.show', $user) }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('cancel') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Personal & Account Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Personal Information --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-user-circle text-ios-blue"></i>
                        {{ __('customers.personal_info') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Full Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="input-ios @error('name') border-red-500 @enderror"
                                placeholder="{{ __('users.enter_name') }}">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.username') }}
                            </label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="input-ios" placeholder="{{ __('users.enter_username') }}">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.phone') }}
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="input-ios"
                                placeholder="{{ __('users.enter_phone') }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="input-ios @error('email') border-red-500 @enderror"
                                placeholder="{{ __('users.enter_email') }}">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Avatar --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('users.avatar') }}
                            </label>

                            <div class="flex items-start gap-4">
                                {{-- Current/Preview Avatar --}}
                                <div class="flex-shrink-0">
                                    <div id="avatar-preview-container" class="relative group">
                                        @if ($user->avatar)
                                            <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}"
                                                alt="{{ $user->name }}"
                                                class="w-24 h-24 rounded-2xl object-cover border-2 border-gray-200 shadow-md">
                                            <input type="hidden" name="remove_avatar" id="remove-avatar-input"
                                                value="0">
                                            <button type="button" id="remove-avatar-btn"
                                                class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <i class="ph-bold ph-x text-sm"></i>
                                            </button>
                                        @else
                                            <div id="avatar-preview"
                                                class="w-24 h-24 rounded-2xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center shadow-md">
                                                <i class="ph-fill ph-user text-white text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Upload Area --}}
                                <div class="flex-1">
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-ios-blue transition-colors cursor-pointer bg-gray-50 hover:bg-blue-50"
                                        id="avatar-upload-area">
                                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                                            class="hidden">
                                        <div class="text-center">
                                            <i class="ph-bold ph-upload-simple text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-700">คลิกเพื่ออัปโหลดรูปใหม่</p>
                                            <p class="text-xs text-gray-500 mt-1">หรือลากไฟล์มาวางที่นี่</p>
                                            <p class="text-xs text-gray-400 mt-2">JPG, PNG (Max 2MB)</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="ph ph-info"></i> เว้นว่างไว้หากไม่ต้องการเปลี่ยนรูป
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.password') }}
                            </label>
                            <input type="password" name="password"
                                class="input-ios @error('password') border-red-500 @enderror" placeholder="••••••••">
                            <p class="text-xs text-gray-500 mt-1">เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</p>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.password_confirmation') }}
                            </label>
                            <input type="password" name="password_confirmation" class="input-ios" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                {{-- Professional Information --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-briefcase text-purple-500"></i>
                        {{ __('users.professional_info') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Position --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.position') }}
                            </label>
                            <input type="text" name="position" value="{{ old('position', $user->position) }}"
                                class="input-ios" placeholder="{{ __('users.enter_position') }}">
                        </div>

                        {{-- Hired Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.hired_date') }}
                            </label>
                            <input type="date" name="hired_date"
                                value="{{ old('hired_date', $user->hired_date?->format('Y-m-d')) }}" class="input-ios">
                        </div>

                        {{-- Pharmacist License No --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.pharmacist_license_no') }}
                            </label>
                            <input type="text" name="pharmacist_license_no"
                                value="{{ old('pharmacist_license_no', $user->pharmacist_license_no) }}"
                                class="input-ios" placeholder="{{ __('users.enter_license_no') }}">
                            <p class="text-xs text-gray-500 mt-1">สำหรับเภสัชกรเท่านั้น</p>
                        </div>

                        {{-- License Expiry --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('users.license_expiry') }}
                            </label>
                            <input type="date" name="license_expiry"
                                value="{{ old('license_expiry', $user->license_expiry?->format('Y-m-d')) }}"
                                class="input-ios">
                            @if ($user->license_expiry && $user->isLicenseExpiringSoon())
                                <p class="text-xs text-orange-600 mt-1">
                                    <i class="ph-fill ph-warning"></i> ใบอนุญาตใกล้หมดอายุ
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Role & Status --}}
            <div class="space-y-6">
                {{-- Role & Permissions --}}
                <div class="card-ios p-6 border-2 border-purple-200 bg-purple-50/50">
                    <h3 class="text-lg font-bold text-purple-700 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-shield-check"></i>
                        {{ __('users.permissions') }}
                    </h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('users.role') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required class="input-ios @error('role') border-red-500 @enderror">
                            <option value="">{{ __('users.select_role') }}</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                {{ __('users.admin') }}</option>
                            <option value="pharmacist" {{ old('role', $user->role) === 'pharmacist' ? 'selected' : '' }}>
                                {{ __('users.pharmacist') }}</option>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>
                                {{ __('users.staff') }}</option>
                        </select>
                        @error('role')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role Descriptions --}}
                    <div class="space-y-2 text-xs">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <p class="font-bold text-purple-900">{{ __('users.admin') }}</p>
                            <p class="text-purple-700">{{ __('users.admin_desc') }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <p class="font-bold text-blue-900">{{ __('users.pharmacist') }}</p>
                            <p class="text-blue-700">{{ __('users.pharmacist_desc') }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 rounded-lg">
                            <p class="font-bold text-gray-900">{{ __('users.staff') }}</p>
                            <p class="text-gray-700">{{ __('users.staff_desc') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-info text-gray-500"></i>
                        {{ __('users.status') }}
                    </h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('users.status') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required class="input-ios">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>
                                {{ __('users.active') }}</option>
                            <option value="suspended"
                                {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>
                                {{ __('users.suspended') }}</option>
                            <option value="resigned" {{ old('status', $user->status) === 'resigned' ? 'selected' : '' }}>
                                {{ __('users.resigned') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-note text-gray-500"></i>
                        {{ __('users.notes') }}
                    </h3>
                    <textarea name="notes" rows="4" class="input-ios resize-none" placeholder="{{ __('users.enter_notes') }}">{{ old('notes', $user->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('users.show', $user) }}"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                {{ __('cancel') }}
            </a>
            <button type="submit"
                class="px-6 py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
                <i class="ph-bold ph-check"></i>
                {{ __('save') }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Avatar Upload Functionality
        const avatarInput = document.getElementById('avatar-input');
        const avatarUploadArea = document.getElementById('avatar-upload-area');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarPreviewContainer = document.getElementById('avatar-preview-container');
        const removeAvatarBtn = document.getElementById('remove-avatar-btn');
        const removeAvatarInput = document.getElementById('remove-avatar-input');

        // Click to upload
        avatarUploadArea?.addEventListener('click', () => {
            avatarInput?.click();
        });

        // File input change
        avatarInput?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('ไฟล์ใหญ่เกินไป! กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น (JPG, PNG)');
                    this.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Update preview
                    if (avatarPreview.tagName === 'IMG') {
                        avatarPreview.src = e.target.result;
                    } else {
                        // Replace icon with image
                        const img = document.createElement('img');
                        img.id = 'avatar-preview';
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'w-24 h-24 rounded-2xl object-cover border-2 border-gray-200 shadow-md';
                        avatarPreview.replaceWith(img);

                        // Add remove button if not exists
                        if (!removeAvatarBtn) {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.id = 'remove-avatar-btn';
                            btn.className =
                                'absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center';
                            btn.innerHTML = '<i class="ph-bold ph-x text-sm"></i>';
                            btn.onclick = removeAvatar;
                            avatarPreviewContainer.appendChild(btn);

                            // Add hidden input for remove flag
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'remove_avatar';
                            input.id = 'remove-avatar-input';
                            input.value = '0';
                            avatarPreviewContainer.appendChild(input);
                        }
                    }

                    // Reset remove flag
                    if (removeAvatarInput) {
                        removeAvatarInput.value = '0';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop
        avatarUploadArea?.addEventListener('dragover', (e) => {
            e.preventDefault();
            avatarUploadArea.classList.add('border-ios-blue', 'bg-blue-50');
        });

        avatarUploadArea?.addEventListener('dragleave', (e) => {
            e.preventDefault();
            avatarUploadArea.classList.remove('border-ios-blue', 'bg-blue-50');
        });

        avatarUploadArea?.addEventListener('drop', (e) => {
            e.preventDefault();
            avatarUploadArea.classList.remove('border-ios-blue', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                avatarInput.files = files;
                avatarInput.dispatchEvent(new Event('change'));
            }
        });

        // Remove avatar
        function removeAvatar() {
            if (confirm('ต้องการลบรูปโปรไฟล์หรือไม่?')) {
                // Clear file input
                if (avatarInput) {
                    avatarInput.value = '';
                }

                // Set remove flag
                if (removeAvatarInput) {
                    removeAvatarInput.value = '1';
                }

                // Replace image with default icon
                const defaultIcon = document.createElement('div');
                defaultIcon.id = 'avatar-preview';
                defaultIcon.className =
                    'w-24 h-24 rounded-2xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center shadow-md';
                defaultIcon.innerHTML = '<i class="ph-fill ph-user text-white text-4xl"></i>';

                const currentPreview = document.getElementById('avatar-preview');
                if (currentPreview) {
                    currentPreview.replaceWith(defaultIcon);
                }

                // Remove the remove button
                if (removeAvatarBtn) {
                    removeAvatarBtn.remove();
                }
            }
        }

        // Attach remove function to button if exists
        removeAvatarBtn?.addEventListener('click', removeAvatar);
    </script>
@endpush

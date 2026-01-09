@extends('layouts.app')

@section('title', __('tiers.edit_tier'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('tiers.title') }}
        </p>
        <span>{{ __('tiers.edit_tier') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('member-tiers.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph-bold ph-arrow-left"></i>
        {{ __('general.back') }}
    </a>
@endsection

@section('content')
    <form action="{{ route('member-tiers.update', $memberTier) }}" method="POST" class="max-w-2xl mx-auto">
        @csrf
        @method('PUT')

        <div class="card-ios p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-crown text-yellow-500"></i>
                    {{ __('general.basic_info') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.name') }} *</label>
                        <input type="text" name="name" value="{{ old('name', $memberTier->name) }}" required
                            class="input-ios w-full @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.name_th') }}</label>
                        <input type="text" name="name_th" value="{{ old('name_th', $memberTier->name_th) }}"
                            class="input-ios w-full">
                    </div>
                </div>
            </div>

            {{-- Spending & Discount --}}
            <div>
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-percent text-green-500"></i>
                    {{ __('tiers.min_spending') }} & {{ __('tiers.discount_percent') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.min_spending') }}
                            *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">฿</span>
                            <input type="number" name="min_spending"
                                value="{{ old('min_spending', $memberTier->min_spending) }}" step="0.01" min="0"
                                required class="input-ios w-full pl-8">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.discount_percent') }}
                            *</label>
                        <div class="relative">
                            <input type="number" name="discount_percent"
                                value="{{ old('discount_percent', $memberTier->discount_percent) }}" step="0.01"
                                min="0" max="100" required class="input-ios w-full pr-8">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.points_multiplier') }}</label>
                        <div class="relative">
                            <input type="number" name="points_multiplier"
                                value="{{ old('points_multiplier', $memberTier->points_multiplier) }}" min="1"
                                max="10" class="input-ios w-full pr-8">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">x</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appearance --}}
            <div>
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-palette text-purple-500"></i>
                    Appearance
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.color') }} *</label>
                        <input type="color" name="color" value="{{ old('color', $memberTier->color) }}"
                            class="w-full h-12 rounded-xl border border-gray-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.icon') }}</label>
                        <select name="icon" class="input-ios w-full">
                            <option value="">-- เลือกไอคอน --</option>
                            <option value="ph-medal" {{ old('icon', $memberTier->icon) === 'ph-medal' ? 'selected' : '' }}>
                                🏅 Medal</option>
                            <option value="ph-crown" {{ old('icon', $memberTier->icon) === 'ph-crown' ? 'selected' : '' }}>
                                👑 Crown</option>
                            <option value="ph-crown-simple"
                                {{ old('icon', $memberTier->icon) === 'ph-crown-simple' ? 'selected' : '' }}>💎 Crown
                                Simple</option>
                            <option value="ph-star" {{ old('icon', $memberTier->icon) === 'ph-star' ? 'selected' : '' }}>⭐
                                Star</option>
                            <option value="ph-star-four"
                                {{ old('icon', $memberTier->icon) === 'ph-star-four' ? 'selected' : '' }}>✨ Star Four
                            </option>
                            <option value="ph-diamond"
                                {{ old('icon', $memberTier->icon) === 'ph-diamond' ? 'selected' : '' }}>💠 Diamond</option>
                            <option value="ph-trophy"
                                {{ old('icon', $memberTier->icon) === 'ph-trophy' ? 'selected' : '' }}>🏆 Trophy</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('tiers.sort_order') }}</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $memberTier->sort_order) }}"
                            min="0" class="input-ios w-full">
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                        class="rounded border-gray-300 text-green-500 focus:ring-green-500"
                        {{ old('is_active', $memberTier->is_active) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">{{ __('tiers.is_active') }}</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="pt-4 border-t border-gray-100">
                <button type="submit"
                    class="w-full py-3 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-2xl transition flex items-center justify-center gap-2">
                    <i class="ph-bold ph-check"></i>
                    {{ __('tiers.update_tier') }}
                </button>
            </div>
        </div>
    </form>
@endsection

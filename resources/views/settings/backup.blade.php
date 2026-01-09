@extends('layouts.app')

@section('title', __('backup.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('settings') }}
        </p>
        <span>{{ __('backup.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Page Description --}}
        <div class="flex items-center gap-4 mb-2">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500 flex items-center justify-center shadow-lg">
                <i class="ph-fill ph-database text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ __('backup.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('backup.subtitle') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Database Info Card --}}
                <div class="card-ios p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                            <i class="ph-fill ph-hard-drives text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('backup.database_info') }}</h3>
                            <p class="text-sm text-gray-500">{{ $dbInfo['name'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl text-center">
                            <i class="ph-fill ph-database text-2xl text-blue-500 mb-2"></i>
                            <p class="text-xs text-gray-500">{{ __('backup.database_size') }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $dbInfo['size'] ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl text-center">
                            <i class="ph-fill ph-table text-2xl text-purple-500 mb-2"></i>
                            <p class="text-xs text-gray-500">{{ __('backup.table_count') }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $dbInfo['table_count'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl text-center">
                            <i class="ph-fill ph-users text-2xl text-green-500 mb-2"></i>
                            <p class="text-xs text-gray-500">Users</p>
                            <p class="text-lg font-bold text-gray-900">{{ $dbInfo['record_counts']['users'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl text-center">
                            <i class="ph-fill ph-package text-2xl text-orange-500 mb-2"></i>
                            <p class="text-xs text-gray-500">Products</p>
                            <p class="text-lg font-bold text-gray-900">{{ $dbInfo['record_counts']['products'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                {{-- Backup History --}}
                <div class="card-ios p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">
                                <i class="ph-fill ph-clock-counter-clockwise text-amber-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ __('backup.backup_history') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('backup.backup_history_subtitle') }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($backups->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">
                                            {{ __('backup.filename') }}</th>
                                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">
                                            {{ __('backup.size') }}</th>
                                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">
                                            {{ __('backup.type') }}</th>
                                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">
                                            {{ __('backup.status') }}</th>
                                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">
                                            {{ __('backup.created_at') }}</th>
                                        <th class="text-right py-3 px-2 text-xs font-semibold text-gray-500 uppercase">
                                            {{ __('backup.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($backups as $backup)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="py-3 px-2">
                                                <div class="flex items-center gap-2">
                                                    <i class="ph-fill ph-file-sql text-blue-500"></i>
                                                    <span
                                                        class="text-sm font-medium text-gray-900 truncate max-w-[200px]">{{ $backup->filename }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-2 text-sm text-gray-600">{{ $backup->formatted_size }}</td>
                                            <td class="py-3 px-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $backup->type_badge }}">
                                                    {{ __('backup.' . $backup->type) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $backup->status_badge }}">
                                                    {{ __('backup.' . $backup->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-2 text-sm text-gray-600">
                                                {{ $backup->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="py-3 px-2 text-right">
                                                <div class="flex items-center justify-end gap-1">
                                                    @if ($backup->status === 'completed')
                                                        <a href="{{ route('settings.backup.download', $backup) }}"
                                                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition"
                                                            title="{{ __('backup.download') }}">
                                                            <i class="ph ph-download-simple"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('settings.backup.destroy', $backup) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('{{ __('backup.delete_confirm') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition"
                                                            title="{{ __('backup.delete') }}">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($backups->hasPages())
                            <div class="mt-6 flex justify-center">
                                {{ $backups->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <i class="ph ph-database text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">{{ __('backup.no_backups') }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ __('backup.no_backups_desc') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">
                {{-- Manual Backup Card --}}
                <div class="card-ios p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center">
                            <i class="ph-fill ph-cloud-arrow-up text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('backup.manual_backup') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('backup.manual_backup_subtitle') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.backup.create') }}" method="POST" id="backup-form">
                        @csrf

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-semibold text-gray-500 ml-1">{{ __('backup.backup_notes') }}</label>
                                <textarea name="notes" rows="3" class="input-ios resize-none"
                                    placeholder="{{ __('backup.backup_notes_placeholder') }}"></textarea>
                            </div>

                            <button type="submit" id="backup-btn"
                                class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                                <i class="ph ph-cloud-arrow-up"></i>
                                {{ __('backup.create_backup') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Backup Settings --}}
                <div class="card-ios p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center">
                            <i class="ph-fill ph-gear text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('backup.backup_settings') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('backup.backup_settings_subtitle') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('settings.backup.settings') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Auto Backup Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('backup.auto_backup') }}</p>
                                <p class="text-xs text-gray-500">{{ __('backup.auto_backup_desc') }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="backup_auto_enabled" class="sr-only peer"
                                    {{ $backupSettings['backup_auto_enabled'] ?? false ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500">
                                </div>
                            </label>
                        </div>

                        {{-- Schedule --}}
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('backup.backup_schedule') }}</label>
                            <select name="backup_schedule" class="input-ios">
                                <option value="daily"
                                    {{ ($backupSettings['backup_schedule'] ?? 'daily') === 'daily' ? 'selected' : '' }}>
                                    {{ __('backup.schedule_daily') }}
                                </option>
                                <option value="weekly"
                                    {{ ($backupSettings['backup_schedule'] ?? '') === 'weekly' ? 'selected' : '' }}>
                                    {{ __('backup.schedule_weekly') }}
                                </option>
                                <option value="monthly"
                                    {{ ($backupSettings['backup_schedule'] ?? '') === 'monthly' ? 'selected' : '' }}>
                                    {{ __('backup.schedule_monthly') }}
                                </option>
                            </select>
                        </div>

                        {{-- Time --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('backup.backup_time') }}</label>
                            <input type="time" name="backup_time"
                                value="{{ $backupSettings['backup_time'] ?? '02:00' }}" class="input-ios">
                            <p class="text-xs text-gray-400 ml-1">{{ __('backup.backup_time_desc') }}</p>
                        </div>

                        {{-- Retention Days --}}
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('backup.retention_days') }}</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="backup_retention_days"
                                    value="{{ $backupSettings['backup_retention_days'] ?? 30 }}" min="1"
                                    max="365" class="input-ios flex-1">
                                <span class="text-sm text-gray-500">{{ __('backup.days') }}</span>
                            </div>
                            <p class="text-xs text-gray-400 ml-1">{{ __('backup.retention_days_desc') }}</p>
                        </div>

                        {{-- Include Files Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div>
                                <p class="font-medium text-gray-900">{{ __('backup.include_files') }}</p>
                                <p class="text-xs text-gray-500">{{ __('backup.include_files_desc') }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="backup_include_files" class="sr-only peer"
                                    {{ $backupSettings['backup_include_files'] ?? true ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                                </div>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="ph ph-check"></i>
                            {{ __('backup.save_settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('backup-form')?.addEventListener('submit', function() {
            const btn = document.getElementById('backup-btn');
            btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> {{ __('backup.creating_backup') }}';
            btn.disabled = true;
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', __('activity_logs.activity_details'))
@section('page-title')
    <div class="welcome-container">
        <a href="{{ route('activity-logs.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; display: flex; align-items: center; gap: 4px; text-decoration: none;">
            <i class="ph ph-arrow-left"></i>
            {{ __('activity_logs.title') }}
        </a>
        <span>{{ __('activity_logs.activity_details') }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Activity Header Card --}}
        <div class="card-ios p-6">
            <div class="flex items-start gap-6">
                {{-- Action Icon --}}
                <div
                    class="w-20 h-20 rounded-3xl flex items-center justify-center shadow-lg {{ $activityLog->action_color }}">
                    <i class="{{ $activityLog->action_icon }} text-4xl"></i>
                </div>

                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $activityLog->description ?? __('activity_logs.' . $activityLog->action) }}
                            </h1>
                            <p class="text-gray-500 mt-1">{{ $activityLog->module }}</p>
                        </div>
                        <div class="flex gap-2">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-bold {{ $activityLog->action_color }}">
                                <i class="{{ $activityLog->action_icon }}"></i>
                                {{ __('activity_logs.' . $activityLog->action) }}
                            </span>
                        </div>
                    </div>

                    {{-- Context Info Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div>
                            <p class="text-xs text-gray-500">{{ __('activity_logs.logged_at') }}</p>
                            <p class="font-semibold text-gray-900">{{ $activityLog->logged_at->format('d M Y H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('activity_logs.user') }}</p>
                            <p class="font-semibold text-gray-900">{{ $activityLog->user_name ?? 'System' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('activity_logs.ip_address') }}</p>
                            <p class="font-semibold text-gray-900 font-mono">{{ $activityLog->ip_address ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('activity_logs.module') }}</p>
                            <p class="font-semibold text-gray-900 flex items-center gap-1">
                                <i class="{{ $activityLog->module_icon }} text-gray-400"></i>
                                {{ $activityLog->module }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Actor Information --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-ios-blue"></i>
                    {{ __('activity_logs.actor_info') }}
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">User ID</span>
                        <span class="font-semibold text-gray-900 font-mono">#{{ $activityLog->user_id ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">{{ __('activity_logs.user') }}</span>
                        <span class="font-semibold text-gray-900">{{ $activityLog->user_name ?? 'System' }}</span>
                    </div>
                    @if ($activityLog->user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Email</span>
                            <span class="font-semibold text-gray-900">{{ $activityLog->user->email }}</span>
                        </div>
                        <a href="{{ route('users.show', $activityLog->user) }}"
                            class="block text-center text-ios-blue font-medium hover:underline mt-2">
                            <i class="ph ph-arrow-right"></i> ดูโปรไฟล์ผู้ใช้
                        </a>
                    @endif
                </div>
            </div>

            {{-- Context Information --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-globe text-purple-500"></i>
                    {{ __('activity_logs.context_info') }}
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">{{ __('activity_logs.ip_address') }}</span>
                        <span class="font-semibold text-gray-900 font-mono">{{ $activityLog->ip_address ?? '-' }}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">{{ __('activity_logs.user_agent') }}</p>
                        <p class="text-xs text-gray-700 font-mono break-all">{{ $activityLog->user_agent ?? '-' }}</p>
                    </div>
                    @if ($activityLog->model_type && $activityLog->model_id)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="text-sm text-blue-600">{{ __('activity_logs.related_record') }}</span>
                            <span class="font-semibold text-blue-900 font-mono">
                                {{ class_basename($activityLog->model_type) }} #{{ $activityLog->model_id }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Changes Section --}}
        @if ($activityLog->old_values || $activityLog->new_values)
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-git-diff text-orange-500"></i>
                    {{ __('activity_logs.changes') }}
                </h3>

                @if (count($activityLog->formatted_changes) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                        {{ __('activity_logs.field') }}</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                        {{ __('activity_logs.old_value') }}</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                        {{ __('activity_logs.new_value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activityLog->formatted_changes as $change)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $change['field'] }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-red-50 text-red-700 rounded-lg text-sm font-mono">
                                                {{ is_array($change['old']) ? json_encode($change['old']) : $change['old'] ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-lg text-sm font-mono">
                                                {{ is_array($change['new']) ? json_encode($change['new']) : $change['new'] ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4">
                        @if ($activityLog->old_values)
                            <div>
                                <p class="text-sm font-bold text-gray-500 mb-2">{{ __('activity_logs.old_value') }}</p>
                                <pre class="p-4 bg-red-50 rounded-xl text-xs text-red-800 overflow-auto max-h-60">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                        @if ($activityLog->new_values)
                            <div>
                                <p class="text-sm font-bold text-gray-500 mb-2">{{ __('activity_logs.new_value') }}</p>
                                <pre class="p-4 bg-green-50 rounded-xl text-xs text-green-800 overflow-auto max-h-60">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- Metadata Section --}}
        @if ($activityLog->metadata)
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-info text-gray-500"></i>
                    Metadata
                </h3>
                <pre class="p-4 bg-gray-50 rounded-xl text-xs text-gray-800 overflow-auto max-h-60">{{ json_encode($activityLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif
    </div>
@endsection

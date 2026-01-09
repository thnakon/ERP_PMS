@extends('layouts.app')

@section('title', __('messenger.title'))

@push('styles')
    <style>
        /* Messenger Layout */
        .messenger-container {
            display: grid;
            grid-template-columns: 340px 1fr 340px;
            height: calc(100vh - 80px);
            gap: 16px;
            margin: -24px;
            padding: 16px;
        }

        .messenger-container.no-sidebar {
            grid-template-columns: 340px 1fr;
        }

        /* Sidebar - Chat List */
        .messenger-sidebar {
            background: white;
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .sidebar-search {
            position: relative;
        }

        .sidebar-search input {
            width: 100%;
            padding: 14px 14px 14px 48px;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            font-size: 14px;
            background: #f8fafc;
            outline: none;
            transition: all 0.3s ease;
        }

        .sidebar-search input:focus {
            border-color: var(--ios-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
        }

        .sidebar-search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 18px;
        }

        .sidebar-tabs {
            display: flex;
            padding: 8px;
            gap: 8px;
        }

        .sidebar-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #6B7280;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .sidebar-tab.active {
            color: var(--ios-blue);
            background: rgba(0, 122, 255, 0.1);
        }

        .sidebar-tab:hover:not(.active) {
            background: #f8fafc;
        }

        .chat-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 16px;
            margin-bottom: 4px;
        }

        .chat-item:hover {
            background: #f8fafc;
            transform: translateX(4px);
        }

        .chat-item.active {
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.1) 0%, rgba(0, 122, 255, 0.05) 100%);
            border-left: none;
            box-shadow: inset 3px 0 0 var(--ios-blue);
        }

        .chat-avatar {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            margin-right: 14px;
            position: relative;
        }

        .chat-avatar.online::after {
            content: '';
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 14px;
            height: 14px;
            background: #34C759;
            border: 3px solid white;
            border-radius: 50%;
        }

        .chat-info {
            flex: 1;
            min-width: 0;
        }

        .chat-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-time {
            font-size: 11px;
            color: #9CA3AF;
            font-weight: 500;
        }

        .chat-preview {
            font-size: 13px;
            color: #6B7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-badge {
            min-width: 22px;
            height: 22px;
            background: linear-gradient(135deg, var(--ios-blue), #5856D6);
            color: white;
            border-radius: 11px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 7px;
            margin-left: 8px;
            box-shadow: 0 2px 8px rgba(0, 122, 255, 0.3);
        }

        /* Main Chat Area */
        .messenger-main {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .chat-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .chat-header-avatar {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .chat-header-name {
            font-weight: 700;
            color: #1f2937;
            font-size: 17px;
        }

        .chat-header-status {
            font-size: 13px;
            color: #34C759;
            font-weight: 500;
        }

        .chat-header-actions {
            display: flex;
            gap: 10px;
        }

        .header-action-btn {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: none;
            background: #f3f4f6;
            color: #6B7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .header-action-btn:hover {
            background: #E5E7EB;
            color: var(--ios-blue);
            transform: scale(1.05);
        }

        /* Messages Area */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: linear-gradient(180deg, #fafbfc 0%, #f5f7fa 100%);
        }

        .message-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .message-date-divider {
            text-align: center;
            padding: 16px 0;
        }

        .message-date-divider span {
            background: white;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            color: #6B7280;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .message {
            display: flex;
            gap: 10px;
            max-width: 70%;
            animation: messageSlide 0.3s ease-out;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .message-bubble {
            background: white;
            padding: 14px 18px;
            border-radius: 20px;
            border-bottom-left-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .message.sent .message-bubble {
            background: linear-gradient(135deg, var(--ios-blue) 0%, #5856D6 100%);
            color: white;
            border-radius: 20px;
            border-bottom-right-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.25);
        }

        .message-text {
            font-size: 14px;
            line-height: 1.6;
            word-wrap: break-word;
        }

        .message-time {
            font-size: 10px;
            color: #9CA3AF;
            margin-top: 6px;
            text-align: right;
        }

        .message.sent .message-time {
            color: rgba(255, 255, 255, 0.75);
        }

        .message-image {
            position: relative;
            max-width: 300px;
            border-radius: 18px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .message-image:hover {
            transform: scale(1.02);
            filter: brightness(0.9);
        }

        .message-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Improved File Message */
        .message-file {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px;
            text-decoration: none;
            color: inherit;
        }

        .file-icon {
            width: 44px;
            height: 44px;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #666;
        }

        .sent .file-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .file-info {
            flex: 1;
            min-width: 0;
        }

        .file-name {
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-size {
            font-size: 11px;
            opacity: 0.7;
        }

        .file-download {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.05);
            color: #666;
            transition: all 0.2s;
        }

        .sent .file-download {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .file-download:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Photo Viewer Lightbox */
        #photo-viewer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        #photo-viewer.active {
            opacity: 1;
            visibility: visible;
        }

        .viewer-image-container {
            max-width: 90%;
            max-height: 80%;
            position: relative;
        }

        #viewer-img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .viewer-controls {
            margin-top: 24px;
            display: flex;
            gap: 16px;
        }

        .viewer-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .viewer-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .close-viewer {
            position: absolute;
            top: 30px;
            right: 30px;
        }

        /* Input Area */
        .message-input-container {
            padding: 18px 24px;
            border-top: 1px solid #f3f4f6;
            background: white;
        }

        .message-input-wrapper {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            background: #f8fafc;
            border-radius: 24px;
            padding: 10px 18px;
            border: 2px solid #E5E7EB;
            transition: all 0.3s ease;
        }

        .message-input-wrapper:focus-within {
            border-color: var(--ios-blue);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
        }

        .input-actions {
            display: flex;
            gap: 4px;
        }

        .input-action-btn {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: none;
            background: transparent;
            color: #6B7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .input-action-btn:hover {
            background: #E5E7EB;
            color: var(--ios-blue);
            transform: scale(1.1);
        }

        .message-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 0;
            font-size: 15px;
            resize: none;
            max-height: 120px;
            outline: none;
        }

        .send-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, var(--ios-blue) 0%, #5856D6 100%);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
        }

        .send-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 16px rgba(0, 122, 255, 0.4);
        }

        .send-btn:disabled {
            background: #E5E7EB;
            color: #9CA3AF;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Right Sidebar - Notifications */
        .messenger-right-sidebar {
            background: white;
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .right-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .right-sidebar-title {
            font-weight: 700;
            font-size: 16px;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .delivery-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 16px;
            border-radius: 16px;
            text-align: center;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: #1f2937;
        }

        .stat-label {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
            font-weight: 600;
        }

        .delivery-logs {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .log-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .log-item:hover {
            background: #f8fafc;
        }

        .log-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .log-icon.email {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }

        .log-icon.line {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
        }

        .log-icon.sms {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .log-person-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            color: #64748b;
            position: relative;
            flex-shrink: 0;
        }

        .log-channel-badge {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1.5px solid white;
        }

        .log-channel-badge.line {
            background: #06C755;
            color: white;
        }

        .log-channel-badge.email {
            background: #2563eb;
            color: white;
        }

        .log-channel-badge.sms {
            background: #64748b;
            color: white;
        }

        .log-info {
            flex: 1;
            min-width: 0;
        }

        .log-recipient {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .log-subject {
            font-size: 12px;
            color: #6B7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .log-time {
            font-size: 10px;
            color: #9CA3AF;
            margin-top: 4px;
        }

        .log-status {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .log-status.sent {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }

        .log-status.delivered {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
        }

        .log-status.failed {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .log-status.opened {
            background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
            color: #9333ea;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9CA3AF;
            text-align: center;
            padding: 40px;
        }

        .empty-state i {
            font-size: 72px;
            margin-bottom: 20px;
            opacity: 0.4;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: #4B5563;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            max-width: 280px;
            line-height: 1.6;
        }

        /* Typing indicator */
        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
        }

        .typing-indicator span {
            width: 8px;
            height: 8px;
            background: #9CA3AF;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-8px);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .messenger-container {
                grid-template-columns: 300px 1fr;
            }

            .messenger-right-sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .messenger-container {
                grid-template-columns: 1fr;
            }

            .messenger-sidebar {
                display: none;
            }
        }
    </style>
@endpush

@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('communications') }}
        </p>
        <span>{{ __('messenger.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex items-center gap-3">
        <button onclick="MessengerApp.createGroup()"
            class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
            <i class="ph ph-users-three"></i>
            {{ __('messenger.new_group') }}
        </button>
        <button onclick="MessengerApp.newChat()"
            class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
            <i class="ph-bold ph-plus"></i>
            {{ __('messenger.new_chat') }}
        </button>
    </div>
@endsection

@section('content')
    <div class="messenger-container {{ !$activeRoom || $activeRoom->type !== 'group' ? 'no-sidebar' : '' }}">
        {{-- Left Sidebar - Chat List --}}
        <div class="messenger-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="{{ __('messenger.search_chats') }}" id="search-chats">
                </div>
            </div>

            <div class="sidebar-tabs">
                <div class="sidebar-tab active" data-tab="chats">
                    <i class="ph ph-chats-circle"></i>
                    {{ __('messenger.chats') }}
                </div>
                <div class="sidebar-tab" data-tab="contacts">
                    <i class="ph ph-address-book"></i>
                    {{ __('messenger.contacts') }}
                </div>
            </div>

            <div class="chat-list" id="chat-list-container">
                {{-- Chats Tab Content --}}
                <div id="chats-tab-content">
                    @forelse($chatRooms as $room)
                        <div class="chat-item {{ $activeRoom && $activeRoom->id === $room->id ? 'active' : '' }}"
                            data-room-id="{{ $room->id }}" onclick="MessengerApp.openRoom({{ $room->id }})">
                            <div class="chat-avatar"
                                @if ($room->display_avatar) style="background-image: url('{{ $room->display_avatar }}'); background-size: cover; background-position: center;" @endif>
                                @if (!$room->display_avatar)
                                    @if ($room->type === 'group')
                                        <i class="ph-bold ph-users"></i>
                                    @else
                                        {{ strtoupper(substr($room->display_name, 0, 1)) }}
                                    @endif
                                @endif
                            </div>
                            <div class="chat-info">
                                <div class="chat-name">
                                    <span>{{ $room->display_name }}</span>
                                    @if ($room->latestMessage->first())
                                        <span
                                            class="chat-time">{{ $room->latestMessage->first()->created_at->diffForHumans(null, true) }}</span>
                                    @endif
                                </div>
                                <div class="chat-preview">
                                    @if ($room->latestMessage->first())
                                        {{ Str::limit($room->latestMessage->first()->content, 40) }}
                                    @else
                                        {{ __('messenger.no_messages') }}
                                    @endif
                                </div>
                            </div>
                            @if (($room->messages_count ?? 0) > 0)
                                <div class="chat-badge">{{ $room->messages_count }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 40px 20px;">
                            <i class="ph ph-chats-circle"></i>
                            <h3>{{ __('messenger.no_chats') }}</h3>
                            <p>{{ __('messenger.start_conversation') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Contacts Tab Content --}}
                <div id="contacts-tab-content" class="hidden">
                    @foreach ($allUsers as $u)
                        @php $avatar = $u->avatar ? asset('storage/' . $u->avatar) : null; @endphp
                        <div class="chat-item" onclick="MessengerApp.startChatWith({{ $u->id }})"
                            data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}">
                            <div class="chat-avatar"
                                style="background-image: {{ $avatar ? 'url(' . $avatar . ')' : 'none' }}; background-size: cover; background-position: center;">
                                @if (!$avatar)
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="chat-info">
                                <div class="chat-name">{{ $u->name }}</div>
                                <div class="text-xs text-gray-400">{{ $u->position ?? $u->role }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Main Chat Area --}}
        <div class="messenger-main">
            @if ($activeRoom)
                {{-- Chat Header --}}
                <div class="chat-header">
                    <div class="chat-header-info">
                        <div class="chat-header-avatar"
                            @if ($activeRoom->display_avatar) style="background-image: url('{{ $activeRoom->display_avatar }}'); background-size: cover; background-position: center;" @endif>
                            @if (!$activeRoom->display_avatar)
                                @if ($activeRoom->type === 'group')
                                    <i class="ph-bold ph-users"></i>
                                @else
                                    {{ strtoupper(substr($activeRoom->display_name, 0, 1)) }}
                                @endif
                            @endif
                        </div>
                        <div>
                            <div class="chat-header-name">{{ $activeRoom->display_name }}</div>
                            <div class="chat-header-status">
                                @if ($activeRoom->type === 'group')
                                    {{ $activeRoom->users->count() }} {{ __('messenger.members') }}
                                @else
                                    {{ __('messenger.online') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="chat-header-actions">
                        <button class="header-action-btn" title="{{ __('messenger.search') }}">
                            <i class="ph ph-magnifying-glass"></i>
                        </button>
                        <button class="header-action-btn" title="{{ __('messenger.info') }}">
                            <i class="ph ph-info"></i>
                        </button>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="messages-container" id="messages-container">
                    @php $lastDate = null; @endphp
                    @foreach ($messages as $message)
                        @php
                            $messageDate = $message->created_at->format('Y-m-d');
                            $isSent = $message->sender_id === auth()->id();
                            $senderAvatar =
                                $message->sender && $message->sender->avatar
                                    ? asset('storage/' . $message->sender->avatar)
                                    : null;
                        @endphp

                        @if ($lastDate !== $messageDate)
                            <div class="message-date-divider">
                                <span>{{ $message->created_at->format('d M Y') }}</span>
                            </div>
                            @php $lastDate = $messageDate; @endphp
                        @endif

                        <div class="message {{ $isSent ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                            @unless ($isSent)
                                <div class="message-avatar"
                                    @if ($senderAvatar) style="background-image: url('{{ $senderAvatar }}'); background-size: cover; background-position: center;" @endif>
                                    @if (!$senderAvatar)
                                        {{ strtoupper(substr($message->sender_name, 0, 1)) }}
                                    @endif
                                </div>
                            @endunless

                            <div class="message-bubble">
                                @if ($message->type === 'image')
                                    <div class="message-image"
                                        onclick="MessengerApp.viewImage('{{ asset('storage/' . $message->attachment_url) }}')">
                                        <img src="{{ asset('storage/' . $message->attachment_url) }}"
                                            alt="{{ $message->attachment_name }}">
                                    </div>
                                    @if ($message->content)
                                        <div class="message-text mt-2">{{ $message->content }}</div>
                                    @endif
                                @elseif($message->type === 'file')
                                    <a href="{{ asset('storage/' . $message->attachment_url) }}"
                                        download="{{ $message->attachment_name }}" class="message-file">
                                        <div class="file-icon"><i class="ph ph-file"></i></div>
                                        <div class="file-info">
                                            <div class="file-name">{{ $message->attachment_name }}</div>
                                            <div class="file-size">{{ $message->formatted_size }}</div>
                                        </div>
                                        <div class="file-download">
                                            <i class="ph ph-download-simple"></i>
                                        </div>
                                    </a>
                                @else
                                    <div class="message-text">{{ $message->content }}</div>
                                @endif
                                <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Message Input --}}
                <div class="message-input-container">
                    <form id="message-form" onsubmit="MessengerApp.sendMessage(event)">
                        <div class="message-input-wrapper">
                            <div class="input-actions">
                                <button type="button" class="input-action-btn" onclick="MessengerApp.attachFile()">
                                    <i class="ph ph-paperclip"></i>
                                </button>
                                <button type="button" class="input-action-btn" onclick="MessengerApp.attachImage()">
                                    <i class="ph ph-image"></i>
                                </button>
                            </div>
                            <textarea class="message-input" id="message-input" placeholder="{{ __('messenger.type_message') }}" rows="1"></textarea>
                            <button type="submit" class="send-btn" id="send-btn" disabled>
                                <i class="ph-bold ph-paper-plane-tilt"></i>
                            </button>
                        </div>
                        <input type="file" id="file-input" hidden>
                        <input type="file" id="image-input" accept="image/*" hidden>
                    </form>
                </div>
            @else
                {{-- Empty State --}}
                <div class="empty-state">
                    <i class="ph ph-chats-teardrop"></i>
                    <h3>{{ __('messenger.select_chat') }}</h3>
                    <p>{{ __('messenger.select_chat_desc') }}</p>
                </div>
            @endif
        </div>

        {{-- Right Sidebar - Members (Only for Groups) --}}
        @if ($activeRoom && $activeRoom->type === 'group')
            <div class="messenger-right-sidebar">
                <div class="right-sidebar-header">
                    <div class="right-sidebar-title">
                        <i class="ph-fill ph-users text-ios-blue"></i>
                        {{ __('messenger.members') }}
                    </div>
                </div>

                <div class="participants-section">
                    <div
                        class="section-title px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        {{ __('messenger.group_participants') }} ({{ $activeRoom->users->count() }})
                    </div>
                    <div class="participants-list p-2 custom-scroll" style="overflow-y: auto; flex: 1;">
                        @foreach ($activeRoom->users as $u)
                            @php $avatar = $u->avatar ? asset('storage/' . $u->avatar) : null; @endphp
                            <div
                                class="participant-item flex items-center gap-3 p-3 hover:bg-gray-50 rounded-2xl transition group">
                                <div class="participant-avatar w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-sm shadow-sm border border-white"
                                    style="background-image: {{ $avatar ? 'url(' . $avatar . ')' : 'none' }}; background-size: cover; background-position: center;">
                                    @if (!$avatar)
                                        <span class="text-gray-500">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="participant-info flex-1 min-w-0">
                                    <div class="participant-name text-sm font-bold text-gray-900 truncate">
                                        {{ $u->name }}</div>
                                    <div
                                        class="participant-role text-[10px] font-medium text-gray-400 flex items-center gap-1">
                                        <i
                                            class="ph-fill ph-shield-check {{ $u->pivot->role === 'admin' ? 'text-orange-500' : 'text-gray-300' }}"></i>
                                        {{ ucfirst($u->pivot->role) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- New Chat Modal --}}
    <div id="new-chat-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" style="z-index: 50;"
        onclick="toggleModal(false, 'new-chat-modal')"></div>
    <div id="new-chat-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem; z-index: 51;">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('messenger.new_chat') }}</h3>
            <button onclick="toggleModal(false, 'new-chat-modal')" class="modal-close">
                <i class="ph ph-x"></i>
            </button>
        </div>
        <div class="p-4 border-b">
            <div class="sidebar-search">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" placeholder="{{ __('messenger.search_users') }}" id="search-users-input"
                    onkeyup="MessengerApp.filterUsers(this.value, 'user-list')">
            </div>
        </div>
        <div class="modal-body p-0" style="max-height: 480px; overflow-y: auto;" id="user-list">
            @forelse ($allUsers as $u)
                @php $avatar = $u->avatar ? asset('storage/' . $u->avatar) : null; @endphp
                <div class="chat-item user-search-item" onclick="MessengerApp.startChatWith({{ $u->id }})"
                    data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}">
                    <div class="chat-avatar"
                        style="width: 44px; height: 44px; font-size: 15px; border-radius: 12px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-weight: bold; background-image: {{ $avatar ? 'url(' . $avatar . ')' : 'none' }}; background-size: cover; background-position: center;">
                        @if (!$avatar)
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="chat-info">
                        <div class="chat-name" style="font-size: 15px;">{{ $u->name }}</div>
                        <div class="text-xs text-gray-500">{{ $u->email }}</div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <p>No users found</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- New Group Modal --}}
    <div id="new-group-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" style="z-index: 50;"
        onclick="toggleModal(false, 'new-group-modal')"></div>
    <div id="new-group-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem; z-index: 51;">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('messenger.new_group') }}</h3>
            <button onclick="toggleModal(false, 'new-group-modal')" class="modal-close">
                <i class="ph ph-x"></i>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('messenger.group_name') }}</label>
                <input type="text" id="group-name-input"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition"
                    placeholder="Enter group name..." onkeyup="MessengerApp.validateGroupForm()">
            </div>
            <div class="sidebar-search">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" placeholder="{{ __('messenger.add_members') }}"
                    onkeyup="MessengerApp.filterUsers(this.value, 'group-user-list')">
            </div>
        </div>
        <div class="modal-body p-0" style="max-height: 320px; overflow-y: auto;" id="group-user-list">
            @forelse ($allUsers as $u)
                @php $avatar = $u->avatar ? asset('storage/' . $u->avatar) : null; @endphp
                <div class="chat-item group-user-item p-3"
                    onclick="MessengerApp.toggleGroupMember({{ $u->id }}, this)"
                    data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}">
                    <div class="flex items-center gap-3 w-full">
                        <div class="chat-avatar"
                            style="width: 36px; height: 36px; font-size: 14px; border-radius: 10px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-weight: bold; background-image: {{ $avatar ? 'url(' . $avatar . ')' : 'none' }}; background-size: cover; background-position: center;">
                            @if (!$avatar)
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-sm">{{ $u->name }}</div>
                        </div>
                        <div class="member-checkbox">
                            <i class="ph ph-circle text-gray-300 text-xl"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <p>No users found</p>
                </div>
            @endforelse
        </div>
        <div class="p-4 border-t">
            <button onclick="MessengerApp.submitCreateGroup()" id="btn-create-group" disabled
                class="w-full py-3 bg-ios-blue hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition disabled:opacity-50 disabled:grayscale">
                {{ __('messenger.create_group') }}
            </button>
        </div>
    </div>

    {{-- Photo Viewer Lightbox --}}
    <div id="photo-viewer" onclick="MessengerApp.closeViewer()">
        <div class="viewer-image-container" onclick="event.stopPropagation()">
            <img id="viewer-img" src="" alt="Full size image">
            <div class="viewer-controls">
                <button class="viewer-btn close-viewer" onclick="MessengerApp.closeViewer()">
                    <i class="ph ph-x"></i>
                </button>
                <a id="viewer-download" href="#" download class="viewer-btn">
                    <i class="ph ph-download-simple"></i>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const MessengerApp = {
            activeRoomId: {{ $activeRoom?->id ?? 'null' }},
            pollingInterval: null,
            lastMessageId: 0,
            isPolling: false,
            selectedMembers: [],

            init() {
                this.setupInputHandlers();
                this.setupTabHandlers();
                this.scrollToBottom();
                this.initLastMessageId();

                // Start real-time polling if in a room
                if (this.activeRoomId) {
                    this.startPolling();
                }
            },

            setupTabHandlers() {
                const tabs = document.querySelectorAll('.sidebar-tab');
                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const target = tab.dataset.tab;

                        // Update active state
                        tabs.forEach(t => t.classList.remove('active'));
                        tab.classList.add('active');

                        // Show/hide content
                        if (target === 'chats') {
                            document.getElementById('chats-tab-content').classList.remove('hidden');
                            document.getElementById('contacts-tab-content').classList.add('hidden');
                        } else {
                            document.getElementById('chats-tab-content').classList.add('hidden');
                            document.getElementById('contacts-tab-content').classList.remove('hidden');
                        }
                    });
                });
            },

            initLastMessageId() {
                const messages = document.querySelectorAll('.message[data-message-id]');
                if (messages.length > 0) {
                    const lastMsg = messages[messages.length - 1];
                    this.lastMessageId = parseInt(lastMsg.dataset.messageId) || 0;
                }
            },

            setupInputHandlers() {
                const input = document.getElementById('message-input');
                const sendBtn = document.getElementById('send-btn');
                const fileInput = document.getElementById('file-input');
                const imageInput = document.getElementById('image-input');

                if (input) {
                    input.addEventListener('input', function() {
                        sendBtn.disabled = !this.value.trim();
                        this.style.height = 'auto';
                        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                    });

                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            if (this.value.trim()) {
                                MessengerApp.sendMessage(e);
                            }
                        }
                    });

                    // Focus input when room is active
                    input.focus();
                }

                const chatSearch = document.getElementById('search-chats');
                if (chatSearch) {
                    chatSearch.addEventListener('keyup', (e) => {
                        const activeTab = document.querySelector('.sidebar-tab.active').dataset.tab;
                        const listId = activeTab === 'chats' ? 'chats-tab-content' : 'contacts-tab-content';
                        this.filterUsers(e.target.value, listId);
                    });
                }

                if (fileInput) {
                    fileInput.addEventListener('change', () => this.handleFileSelection('file'));
                }

                if (imageInput) {
                    imageInput.addEventListener('change', () => this.handleFileSelection('image'));
                }
            },

            handleFileSelection(type) {
                const input = type === 'image' ? document.getElementById('image-input') : document.getElementById(
                    'file-input');
                if (input.files && input.files[0]) {
                    // Automatically send the file
                    this.sendMessage(null, input.files[0], type);
                    // Clear the input
                    input.value = '';
                }
            },

            scrollToBottom(smooth = false) {
                const container = document.getElementById('messages-container');
                if (container) {
                    if (smooth) {
                        container.scrollTo({
                            top: container.scrollHeight,
                            behavior: 'smooth'
                        });
                    } else {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            },

            openRoom(roomId) {
                // Stop polling before navigating
                this.stopPolling();
                window.location.href = `{{ route('messenger.index') }}?room=${roomId}`;
            },

            newChat() {
                toggleModal(true, 'new-chat-modal');
            },

            filterUsers(query, listId) {
                const q = query.toLowerCase();
                const items = document.querySelectorAll(`#${listId} .chat-item`);
                items.forEach(item => {
                    const name = item.dataset.name || '';
                    const email = item.dataset.email || '';
                    if (name.includes(q) || email.includes(q)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            },

            startChatWith(userId) {
                fetch('{{ route('messenger.start-chat') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            user_id: userId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            toggleModal(false, 'new-chat-modal');
                            this.openRoom(data.room_id);
                        }
                    });
            },

            createGroup() {
                this.selectedMembers = [];
                const nameInput = document.getElementById('group-name-input');
                if (nameInput) nameInput.value = '';

                document.querySelectorAll('.group-user-item i').forEach(i => {
                    i.className = 'ph ph-circle text-gray-300 text-xl';
                });
                document.querySelectorAll('.group-user-item').forEach(item => {
                    item.classList.remove('bg-blue-50');
                });

                const btn = document.getElementById('btn-create-group');
                if (btn) btn.disabled = true;

                toggleModal(true, 'new-group-modal');
            },

            toggleGroupMember(userId, element) {
                const idx = this.selectedMembers.indexOf(userId);
                const icon = element.querySelector('i');

                if (idx > -1) {
                    this.selectedMembers.splice(idx, 1);
                    icon.className = 'ph ph-circle text-gray-300 text-xl';
                    element.classList.remove('bg-blue-50');
                } else {
                    this.selectedMembers.push(userId);
                    icon.className = 'ph-fill ph-check-circle text-ios-blue text-xl';
                    element.classList.add('bg-blue-50');
                }

                this.validateGroupForm();
            },

            validateGroupForm() {
                const nameInput = document.getElementById('group-name-input');
                const name = nameInput ? nameInput.value.trim() : '';
                const btn = document.getElementById('btn-create-group');
                if (btn) btn.disabled = !(name && this.selectedMembers.length > 0);
            },

            submitCreateGroup() {
                const nameInput = document.getElementById('group-name-input');
                const name = nameInput ? nameInput.value.trim() : '';
                if (!name || this.selectedMembers.length === 0) return;

                const btn = document.getElementById('btn-create-group');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="ph ph-spinner animate-spin"></i>';
                }

                fetch('{{ route('messenger.create-group') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            name: name,
                            user_ids: this.selectedMembers
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            toggleModal(false, 'new-group-modal');
                            this.openRoom(data.room_id);
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        if (btn) {
                            btn.disabled = false;
                            btn.innerText = 'Create Group';
                        }
                    });
            },

            sendMessage(e, attachment = null, type = 'text') {
                if (e) e.preventDefault();
                if (!this.activeRoomId) {
                    showToast('Please select a chat room first', 'warning');
                    return;
                }

                const input = document.getElementById('message-input');
                const sendBtn = document.getElementById('send-btn');
                const content = input.value.trim();

                if (!content && !attachment) return;

                // Disable button while sending
                sendBtn.disabled = true;
                const originalBtnHtml = sendBtn.innerHTML;
                sendBtn.innerHTML = '<i class="ph ph-spinner animate-spin"></i>';

                const formData = new FormData();
                if (content) formData.append('content', content);
                if (attachment) {
                    formData.append('attachment', attachment);
                    formData.append('type', type);
                }

                fetch(`/messenger/${this.activeRoomId}/send`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.appendMessage(data.message, true);
                            this.lastMessageId = data.message.id;
                            input.value = '';
                            input.style.height = 'auto';
                            input.focus();
                        } else {
                            showToast(data.error || 'Failed to send message', 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Send error:', err);
                        showToast('Failed to send message', 'error');
                    })
                    .finally(() => {
                        sendBtn.disabled = !input.value.trim();
                        sendBtn.innerHTML = originalBtnHtml;
                    });
            },
            appendMessage(message, isSent, animate = true) {
                const container = document.getElementById('messages-container');
                const div = document.createElement('div');
                div.className = `message ${isSent ? 'sent' : 'received'}`;
                div.dataset.messageId = message.id;

                if (animate) {
                    div.style.opacity = '0';
                    div.style.transform = 'translateY(20px)';
                }

                const senderInitial = message.sender?.name ? message.sender.name.charAt(0).toUpperCase() : 'U';
                const senderAvatar = message.sender?.avatar ? `/storage/${message.sender.avatar}` : null;
                const time = new Date(message.created_at).toLocaleTimeString('th-TH', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                let contentHtml = '';

                // Handle different message types
                if (message.type === 'image' && message.attachment_url) {
                    contentHtml = `
                        <div class="message-image" onclick="MessengerApp.viewImage('/storage/${message.attachment_url}')">
                            <img src="/storage/${message.attachment_url}" alt="Attachment">
                        </div>
                    `;
                    if (message.content) {
                        contentHtml += `<div class="message-text mt-2">${this.escapeHtml(message.content)}</div>`;
                    }
                } else if (message.type === 'file' && message.attachment_url) {
                    contentHtml = `
                        <a href="/storage/${message.attachment_url}" download="${this.escapeHtml(message.attachment_name || 'File')}" class="message-file">
                            <div class="file-icon"><i class="ph ph-file"></i></div>
                            <div class="file-info">
                                <div class="file-name">${this.escapeHtml(message.attachment_name || 'File')}</div>
                                <div class="file-size">${this.formatBytes(message.attachment_size || 0)}</div>
                            </div>
                            <div class="file-download">
                                <i class="ph ph-download-simple"></i>
                            </div>
                        </a>
                    `;
                } else {
                    contentHtml = `<div class="message-text">${this.escapeHtml(message.content)}</div>`;
                }

                if (isSent) {
                    div.innerHTML = `
                        <div class="message-bubble">
                            ${contentHtml}
                            <div class="message-time">${time}</div>
                        </div>
                    `;
                } else {
                    const avatarStyle = senderAvatar ?
                        `style="background-image: url('${senderAvatar}'); background-size: cover; background-position: center;"` :
                        '';
                    div.innerHTML = `
                        <div class="message-avatar" ${avatarStyle}>
                            ${senderAvatar ? '' : senderInitial}
                        </div>
                        <div class="message-bubble">
                            ${contentHtml}
                            <div class="message-time">${time}</div>
                        </div>
                    `;
                }

                container.appendChild(div);

                if (animate) {
                    requestAnimationFrame(() => {
                        div.style.transition = 'all 0.3s ease-out';
                        div.style.opacity = '1';
                        div.style.transform = 'translateY(0)';
                    });
                }

                this.scrollToBottom(true);
            },

            formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            },

            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            },

            // Real-time polling
            startPolling() {
                if (this.pollingInterval) return;

                this.pollingInterval = setInterval(() => {
                    this.fetchNewMessages();
                }, 3000); // Poll every 3 seconds
            },

            stopPolling() {
                if (this.pollingInterval) {
                    clearInterval(this.pollingInterval);
                    this.pollingInterval = null;
                }
            },

            fetchNewMessages() {
                if (this.isPolling || !this.activeRoomId) return;
                this.isPolling = true;

                fetch(`/messenger/${this.activeRoomId}/messages`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            const newMessages = data.messages.filter(m => m.id > this.lastMessageId);

                            newMessages.forEach(msg => {
                                // Don't add if it's our own message (already added when sent)
                                const isSent = msg.sender_id === {{ auth()->id() }};
                                if (!isSent) {
                                    this.appendMessage(msg, false, true);
                                }
                            });

                            if (newMessages.length > 0) {
                                this.lastMessageId = Math.max(...data.messages.map(m => m.id));

                                // Play notification sound for new messages from others
                                const hasNewFromOthers = newMessages.some(m => m.sender_id !==
                                    {{ auth()->id() }});
                                if (hasNewFromOthers) {
                                    this.playNotificationSound();
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Polling error:', err))
                    .finally(() => {
                        this.isPolling = false;
                    });
            },

            playNotificationSound() {
                // Create a subtle notification sound
                try {
                    const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.frequency.value = 800;
                    oscillator.type = 'sine';
                    gainNode.gain.value = 0.1;

                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + 0.1);
                } catch (e) {
                    // Ignore audio errors
                }
            },

            attachFile() {
                document.getElementById('file-input').click();
            },

            attachImage() {
                document.getElementById('image-input').click();
            },

            viewImage(url) {
                const viewer = document.getElementById('photo-viewer');
                const img = document.getElementById('viewer-img');
                const downloadBtn = document.getElementById('viewer-download');

                img.src = url;
                downloadBtn.href = url;
                viewer.classList.add('active');
            },

            closeViewer() {
                document.getElementById('photo-viewer').classList.remove('active');
            }
        };

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => MessengerApp.init());

        // Stop polling when leaving page
        window.addEventListener('beforeunload', () => MessengerApp.stopPolling());

        // Handle lightbox close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') MessengerApp.closeViewer();
        });

        // Handle visibility change (pause polling when tab is hidden)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                MessengerApp.stopPolling();
            } else if (MessengerApp.activeRoomId) {
                MessengerApp.startPolling();
                MessengerApp.fetchNewMessages(); // Fetch immediately when returning
            }
        });
    </script>
@endpush

<header id="header" class="apple-header">
    {{-- เปลี่ยนกลับเป็น header-left-section เพื่อให้ CSS ทำงานถูกต้อง --}}
    <div class="header-left-section">

        <!-- Global Search Wrapper (รวมช่องค้นหาและผลลัพธ์) -->
        <div class="global-search-wrapper">
            <div class="header-search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>

                <input type="text" id="globalSearch" placeholder="Search or Ai search" autocomplete="off"
                    style="color: #1d1d1f; background: transparent; -webkit-text-fill-color: initial; -webkit-background-clip: border-box;" />
                <i id="aiSearchButton" class="fa-solid fa-atom ai-search-icon"
                    style="
       background: linear-gradient(90deg,#007aff,#7d22ff,#d31aff,#ff3b30,#ff9500);
       -webkit-background-clip: text;
       -webkit-text-fill-color: transparent;
   ">
                </i>

            </div>
            <!-- ผลลัพธ์การค้นหา -->
            <div id="liveSearchResults" class="live-search-results"> </div>
        </div>

        <button class="header-action-btn" title="Speedometer">
            <i class="fa-solid fa-microphone"></i>
        </button>

    </div>

    <div class="header-user-actions">



        <!-- Help/Support Button (NEW - Direct Trigger) -->
        <button class="header-action-btn" title="Help & Support (New)" onclick="openHelpSupportModalForce()">
            <i class="fa-solid fa-headset"></i>
        </button>

        <!-- Notification Button -->
        <div class="relative">
            <button class="header-action-btn" title="Notifications" id="notificationBtn">
                <i class="fa-solid fa-bell"></i>
                @if (isset($unreadCount) && $unreadCount > 0)
                    <span class="notification-badge"
                        id="header-notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>

            <!-- Notification Modal -->
            <div id="notificationModal"
                class="hidden absolute top-12 right-0 w-[380px] bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden animate-fade-in-down">
                <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-white sticky top-0 z-10">
                    <h3 class="font-bold text-lg text-[#1D1D1F]">Notifications</h3>
                    <button id="closeNotificationBtn"
                        class="w-8 h-8 rounded-full bg-[#F2F2F7] hover:bg-[#E5E5EA] flex items-center justify-center text-[#86868B] transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="max-h-[400px] overflow-y-auto">
                    @if (isset($notifications) && $notifications->count() > 0)
                        @foreach ($notifications as $notif)
                            <div
                                class="flex gap-3 p-4 border-b border-gray-50 hover:bg-[#F5F5F7] transition cursor-pointer">
                                <div class="shrink-0">
                                    <img src="{{ $notif->user->profile_photo_path ? asset('storage/' . $notif->user->profile_photo_path) : asset('images/default-avatar.png') }}"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                        alt="Avatar">
                                </div>
                                <div>
                                    <p class="text-[13px] text-[#1D1D1F] leading-snug">
                                        <span class="font-semibold">{{ $notif->user->name ?? 'User' }}</span>
                                        {{ str_replace($notif->user->name ?? '', '', $notif->description) }}
                                    </p>
                                    <span
                                        class="text-[11px] text-[#86868B] mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-[#86868B]">
                            <i class="fa-regular fa-bell-slash text-2xl mb-2"></i>
                            <p class="text-sm">No new notifications</p>
                        </div>
                    @endif
                </div>
                <div class="p-3 bg-[#F9F9F9] text-center border-t border-gray-100">
                    <a href="{{ route('notifications.index') }}"
                        class="text-xs font-medium text-[#007AFF] hover:underline">View all
                        notifications</a>
                </div>
            </div>
        </div>

        <style>
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-down {
                animation: fadeInDown 0.2s ease-out forwards;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('notificationBtn');
                const modal = document.getElementById('notificationModal');
                const closeBtn = document.getElementById('closeNotificationBtn');
                const badge = document.getElementById('header-notif-badge');

                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    modal.classList.toggle('hidden');

                    // Mark as read if opening and badge exists
                    if (!modal.classList.contains('hidden') && badge) {
                        fetch('{{ route('notifications.mark-read') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(response => {
                            if (response.ok) {
                                badge.remove();
                            }
                        });
                    }
                });

                closeBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!modal.contains(e.target) && !btn.contains(e.target)) {
                        modal.classList.add('hidden');
                    }
                });
            });
        </script>

        <div class="user-profile-dropdown-wrapper">

            <div class="user-profile-container" id="userProfileButton">
                <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-avatar.png') }}"
                    alt="User Avatar" class="user-avatar">
                <span class="user-name" style="font-family: -apple-system, BlinkMacSystemFont, "SF Pro
                    Display", "SF Pro Text" , "IBM Plex Sans Thai" , "Noto Sans Thai" , "Segoe UI" ,
                    Roboto, "Helvetica Neue" , Arial, sans-serif;">{{ Auth::user()->name }}</span>
                <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
            </div>

            <div class="profile-dropdown-menu" id="profileDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item profile-header">
                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-avatar.png') }}"
                        alt="User Avatar" class="user-avatar-in-menu">
                    <div>
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>View My Profile</small>
                    </div>
                </a>
                <div class="dropdown-divider"></div>

                {{-- เปลี่ยนเป็น dropdown-item เพื่อให้สไตล์ Dropdown ทำงาน --}}
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fa-solid fa-user-circle"></i>
                    <span>Oboun Account</span>
                </a>

                <a href="#" class="dropdown-item">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Billing & Subscription</span>
                </a>

                {{-- NEW: Added ID for JS handler, English Content --}}
                <a href="#" class="dropdown-item" id="openAppearancePanelBtn">
                    <i class="fa-solid fa-palette"></i>
                    <span>Appearance</span>
                </a>

                {{-- NEW: Added ID for JS handler, English Content --}}
                <a href="#" class="dropdown-item" id="openLanguagePanelBtn">
                    <i class="fa-solid fa-language"></i>
                    <span>Language</span>
                </a>

                <div class="dropdown-divider"></div>

                <a href="{{ route('settings.index') }}" class="dropdown-item">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>

                <div class="dropdown-divider"></div>

                {{-- ปุ่ม Modal Support (เดิม) --}}
                <a href="#" class="dropdown-item" id="openSupportModalBtn">
                    <i class="fa-solid fa-headset"></i>
                    <span>Support</span>
                </a>

                {{-- ปุ่ม Modal Feedback (ใหม่) --}}
                <a href="#" class="dropdown-item " id="openFeedbackModalBtn">
                    <i class="fa-solid fa-comment-dots"></i>
                    <span>Send Feedback</span>
                </a>


                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;" id="logout-form-header">
                    @csrf
                    <a href="#" class="dropdown-item danger" id="open-logout-modal-header">
                        <i class="fa-solid fa-right-from-bracket"></i> Log Out
                    </a>
                </form>

            </div>

        </div>
    </div>

</header>

<!-- === Minimal Apple Modal Structure (Support) === -->
<div id="helpSupportModalOverlay" class="help-support-modal-overlay">
    <div id="helpSupportModal" class="help-support-modal" role="dialog" aria-modal="true"
        aria-labelledby="modalTitle">
        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Help & Support</h3>
            <button class="modal-close-btn" id="closeModalBtn" type="button" title="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <p class="modal-greeting"><strong>Hello, {{ Auth::user()->name }}</strong></p>
            <p class="modal-description">We are here to assist you. Please select an option below or type your question
                to find answers.</p>

            <div id="support-options" class="modal-actions">
                <a href="javascript:void(0)" class="modal-action-link primary" onclick="toggleContactSupport()">
                    <i class="fa-solid fa-headset"></i> Contact Support
                </a>
                <a href="javascript:void(0)" class="modal-action-link" onclick="toggleUserManual()">
                    <i class="fa-solid fa-book-open"></i> User Manual (Docs)
                </a>
                <a href="javascript:void(0)" class="modal-action-link" onclick="toggleBugReportForm()">
                    <i class="fa-solid fa-bug"></i> Report a Bug
                </a>
            </div>

            <!-- Bug Report Form -->
            <div id="bugReportForm" style="display: none; margin-top: 15px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <h4 style="font-weight: 600; font-size: 15px; color: #1D1D1F;">Report a Bug</h4>
                    <button onclick="toggleBugReportForm()" class="back-btn-animated"
                        style="color: #86868B; background: none; border: none; font-size: 13px; cursor: pointer;">
                        <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i> Back
                    </button>
                </div>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <input type="text" id="bug_title" placeholder="Summary of the issue"
                        style="width: 100%; background: #F5F5F7; border: none; border-radius: 12px; padding: 12px 16px; font-size: 14px; outline: none;">

                    <textarea id="bug_description" rows="3" placeholder="Describe what happened..."
                        style="width: 100%; background: #F5F5F7; border: none; border-radius: 12px; padding: 12px 16px; font-size: 14px; outline: none; resize: none; font-family: inherit;"></textarea>

                    <select id="bug_priority"
                        style="width: 100%; background: #F5F5F7; border: none; border-radius: 12px; padding: 12px 16px; font-size: 14px; outline: none; cursor: pointer;">
                        <option value="low">Low Priority</option>
                        <option value="medium" selected>Medium Priority</option>
                        <option value="high">High Priority</option>
                        <option value="critical">Critical</option>
                    </select>

                    <button onclick="submitBugReport()" id="submit_bug_btn"
                        style="width: 100%; background: #007AFF; color: white; border: none; border-radius: 12px; padding: 12px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 5px; box-shadow: 0 4px 12px rgba(0, 122, 255, 0.2); transition: all 0.2s;">
                        Submit Report
                    </button>
                </div>
            </div>

            <!-- Contact Support View -->
            <div id="contactSupportView" style="display: none; margin-top: 15px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <h4 style="font-weight: 600; font-size: 15px; color: #1D1D1F;">Contact Support</h4>
                    <button onclick="toggleContactSupport()" class="back-btn-animated"
                        style="color: #86868B; background: none; border: none; font-size: 13px; cursor: pointer;">
                        <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i> Back
                    </button>
                </div>
                <div style="background: #F5F5F7; border-radius: 16px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div
                            style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <i class="fa-solid fa-phone" style="color: #34C759; font-size: 18px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #86868B; margin-bottom: 2px;">Hotline (24/7)</div>
                            <div style="font-size: 15px; font-weight: 600; color: #1D1D1F;">02-123-4567</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div
                            style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <i class="fa-solid fa-envelope" style="color: #007AFF; font-size: 18px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #86868B; margin-bottom: 2px;">Email Support</div>
                            <div style="font-size: 15px; font-weight: 600; color: #1D1D1F;">support@oboun.com</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <i class="fa-brands fa-line" style="color: #06C755; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #86868B; margin-bottom: 2px;">Line Official</div>
                            <div style="font-size: 15px; font-weight: 600; color: #1D1D1F;">@oboun_erp</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Manual View -->
            <div id="userManualView" style="display: none; margin-top: 15px;">
                <!-- List of Manuals -->
                <div id="manualList">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <h4 style="font-weight: 600; font-size: 15px; color: #1D1D1F;">User Manual</h4>
                        <button onclick="toggleUserManual()" class="back-btn-animated"
                            style="color: #86868B; background: none; border: none; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i> Back
                        </button>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div onclick="showManualDetail('getting-started')" class="manual-item"
                            style="display: flex; align-items: center; padding: 12px 16px; background: #F5F5F7; border-radius: 12px; color: #1D1D1F; cursor: pointer; transition: background 0.2s;">
                            <i class="fa-solid fa-rocket"
                                style="color: #FF9500; font-size: 16px; margin-right: 12px;"></i>
                            <span style="flex: 1; font-size: 14px; font-weight: 500;">Getting Started Guide</span>
                            <i class="fa-solid fa-chevron-right" style="color: #C7C7CC; font-size: 12px;"></i>
                        </div>
                        <div onclick="showManualDetail('inventory')" class="manual-item"
                            style="display: flex; align-items: center; padding: 12px 16px; background: #F5F5F7; border-radius: 12px; color: #1D1D1F; cursor: pointer; transition: background 0.2s;">
                            <i class="fa-solid fa-box"
                                style="color: #34C759; font-size: 16px; margin-right: 12px;"></i>
                            <span style="flex: 1; font-size: 14px; font-weight: 500;">Inventory Management</span>
                            <i class="fa-solid fa-chevron-right" style="color: #C7C7CC; font-size: 12px;"></i>
                        </div>
                        <div onclick="showManualDetail('sales')" class="manual-item"
                            style="display: flex; align-items: center; padding: 12px 16px; background: #F5F5F7; border-radius: 12px; color: #1D1D1F; cursor: pointer; transition: background 0.2s;">
                            <i class="fa-solid fa-cash-register"
                                style="color: #007AFF; font-size: 16px; margin-right: 12px;"></i>
                            <span style="flex: 1; font-size: 14px; font-weight: 500;">Sales & POS System</span>
                            <i class="fa-solid fa-chevron-right" style="color: #C7C7CC; font-size: 12px;"></i>
                        </div>
                        <div onclick="showManualDetail('staff')" class="manual-item"
                            style="display: flex; align-items: center; padding: 12px 16px; background: #F5F5F7; border-radius: 12px; color: #1D1D1F; cursor: pointer; transition: background 0.2s;">
                            <i class="fa-solid fa-users"
                                style="color: #AF52DE; font-size: 16px; margin-right: 12px;"></i>
                            <span style="flex: 1; font-size: 14px; font-weight: 500;">Staff Management</span>
                            <i class="fa-solid fa-chevron-right" style="color: #C7C7CC; font-size: 12px;"></i>
                        </div>
                    </div>
                </div>

                <!-- Detail View (Hidden by default) -->
                <div id="manualDetail" style="display: none;">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <h4 id="manualDetailTitle" style="font-weight: 600; font-size: 15px; color: #1D1D1F;">Detail
                        </h4>
                        <button onclick="hideManualDetail()" class="back-btn-animated"
                            style="color: #86868B; background: none; border: none; font-size: 13px; cursor: pointer;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i> Back
                        </button>
                    </div>
                    <div id="manualDetailContent"
                        style="font-size: 13px; color: #424245; line-height: 1.6; background: #F5F5F7; padding: 16px; border-radius: 12px; max-height: 300px; overflow-y: auto;">
                        <!-- Content will be injected via JS -->
                    </div>
                </div>
            </div>

            <div class="modal-footer-info">
                <small>Oboun ERP v1.0 | Copyright © 2025</small>
            </div>
        </div>
    </div>
</div>

<!-- === NEW: Settings Panel Structure (Appearance) === -->
<div id="appearancePanelOverlay" class="settings-panel-overlay">
    <div class="settings-panel" role="dialog" aria-modal="true" aria-labelledby="appearanceTitle">
        <div class="panel-header">
            <button class="panel-back-btn" id="backToProfileBtnAppearance" type="button" title="Back to Profile">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <h3 id="appearanceTitle" class="panel-title">Appearance</h3>
        </div>
        <div class="panel-body">
            <div class="setting-group">
                <span class="setting-label">Theme</span>
                <div class="setting-options">
                    <button class="setting-option active" data-theme="light">Light</button>
                    <button class="setting-option" data-theme="dark">Dark</button>
                    <button class="setting-option" data-theme="system">System</button>
                </div>
            </div>
            <div class="panel-footer-info">
                <small>Change the visual look of the application.</small>
            </div>
        </div>
    </div>
</div>

<!-- === NEW: Settings Panel Structure (Language) === -->
<div id="languagePanelOverlay" class="settings-panel-overlay">
    <div class="settings-panel" role="dialog" aria-modal="true" aria-labelledby="languageTitle">
        <div class="panel-header">
            <button class="panel-back-btn" id="backToProfileBtnLanguage" type="button" title="Back to Profile">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <h3 id="languageTitle" class="panel-title">Language</h3>
        </div>
        <div class="panel-body">
            <div class="setting-group">
                <span class="setting-label">Display Language</span>
                <div class="language-options">
                    <a href="#" class="lang-option active" data-lang="en">
                        English (EN) <i class="fa-solid fa-check"></i>
                    </a>
                    <a href="#" class="lang-option" data-lang="th">
                        Thai (TH) <i class="fa-solid fa-check"></i>
                    </a>
                    <a href="#" class="lang-option" data-lang="zh">
                        Chinese (ZH) <i class="fa-solid fa-check"></i>
                    </a>
                    <a href="#" class="lang-option" data-lang="ko">
                        Korean (KO) <i class="fa-solid fa-check"></i>
                    </a>
                </div>
            </div>
            <div class="panel-footer-info">
                <small>Choose your preferred system language.</small>
            </div>
        </div>
    </div>
</div>

<!-- === Real-time Toast Container === -->
<div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

<style>
    /* Toast Animation */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .toast-enter {
        animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .toast-exit {
        animation: slideOutRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Toast Styling */
    .mac-toast {
        position: relative;
        pointer-events: auto;
        width: 320px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.4);
        display: flex;
        gap: 12px;
        align-items: flex-start;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
    }

    .mac-toast-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #007AFF;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .mac-toast-content {
        flex: 1;
    }

    .mac-toast-title {
        font-size: 14px;
        font-weight: 600;
        color: #1D1D1F;
        margin-bottom: 2px;
    }

    .mac-toast-message {
        font-size: 13px;
        color: #86868B;
        line-height: 1.4;
    }

    .mac-toast-close {
        color: #86868B;
        font-size: 14px;
        cursor: pointer;
        padding: 4px;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .mac-toast-close:hover {
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let lastChecked = '{{ now()->toDateTimeString() }}';
        const toastContainer = document.getElementById('toast-container');
        const badge = document.getElementById('header-notif-badge');
        const notificationBtn = document.getElementById('notificationBtn');

        function pollNotifications() {
            fetch(`{{ route('notifications.latest') }}?last_checked=${lastChecked}`)
                .then(response => response.json())
                .then(data => {
                    if (data.timestamp) {
                        lastChecked = data.timestamp;
                    }

                    if (data.notifications && data.notifications.length > 0) {
                        // Update badge
                        let currentCount = badge ? parseInt(badge.innerText) : 0;
                        if (isNaN(currentCount)) currentCount = 0; // handle '9+'

                        const newCount = currentCount + data.notifications.length;

                        if (badge) {
                            badge.innerText = newCount > 9 ? '9+' : newCount;
                        } else {
                            // Create badge if not exists
                            const newBadge = document.createElement('span');
                            newBadge.className = 'notification-badge';
                            newBadge.id = 'header-notif-badge';
                            newBadge.innerText = newCount > 9 ? '9+' : newCount;
                            notificationBtn.appendChild(newBadge);
                        }

                        // Show Toasts
                        data.notifications.forEach(notif => {
                            showToast(notif);
                        });
                    }
                })
                .catch(err => console.error('Polling error:', err));
        }

        function showToast(notification) {
            const toast = document.createElement('div');
            toast.className = 'mac-toast toast-enter';

            const userAvatar = notification.user && notification.user.profile_photo_path ?
                `/storage/${notification.user.profile_photo_path}` :
                '/images/default-avatar.png';

            let userName = 'System';
            if (notification.user) {
                if (notification.user.name) {
                    userName = notification.user.name;
                } else if (notification.user.first_name) {
                    userName = notification.user.first_name + (notification.user.last_name ? ' ' + notification
                        .user.last_name : '');
                }
            }

            toast.innerHTML = `
                <div class="mac-toast-icon">
                    <img src="${userAvatar}" class="w-full h-full object-cover rounded-[10px]" alt="Avatar">
                </div>
                <div class="mac-toast-content" style="position: relative; padding-right: 50px;">
                    <div class="mac-toast-title">${userName}</div>
                    <div class="mac-toast-message">${notification.description}</div>
                </div>
                <div style="position: absolute; top: 12px; right: 40px; font-size: 11px; color: #8e8e93;">${new Date(notification.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                <button class="mac-toast-close" onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;

            toastContainer.appendChild(toast);

            // Play sound (optional)
            // const audio = new Audio('/sounds/notification.mp3');
            // audio.play().catch(e => {});

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-exit');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 5000);
        }

        // Poll every 5 seconds
        setInterval(pollNotifications, 5000);
    });
</script>

<!-- === Global Search JavaScript === -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearch');
        const searchResults = document.getElementById('liveSearchResults');

        // Clone the AI button to remove ALL existing event listeners (from bundled header.js)
        const oldAiBtn = document.getElementById('aiSearchButton');
        const aiSearchBtn = oldAiBtn.cloneNode(true);
        oldAiBtn.parentNode.replaceChild(aiSearchBtn, oldAiBtn);

        let searchTimeout;
        let isAiMode = false;

        // --- Live Search ---
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                hideSearchResults();
                return;
            }

            searchTimeout = setTimeout(() => {
                performLiveSearch(query);
            }, 300);
        });

        // Focus event
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchResults.classList.add('show');
            }
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.global-search-wrapper')) {
                hideSearchResults();
            }
        });

        // Enter key for full search
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query.length > 0) {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // AI Search Button
        aiSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const query = searchInput.value.trim();
            if (query.length > 0) {
                performAiSearch(query);
            } else {
                // Show AI Search help modal
                showAiModal('',
                    'สวัสดีครับ! 🤖\n\nผมคือ AI ผู้ช่วยของ Oboun ERP พิมพ์คำถามหรือคำค้นหาแล้วกด AI เพื่อให้ผมช่วยค้นหาและวิเคราะห์ข้อมูลให้ครับ\n\n**ตัวอย่างคำถาม:**\n• "สินค้าที่ stock ใกล้หมด"\n• "ลูกค้าชื่อ สมชาย"\n• "สรุปยอดขายวันนี้"\n• "แนะนำสินค้าขายดี"'
                );
            }
        });

        function performLiveSearch(query) {
            fetch(`/live-search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data, query);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        function displaySearchResults(data, query) {
            let html = '';
            let hasResults = false;

            // Products
            if (data.products && data.products.length > 0) {
                hasResults = true;
                html += `<div class="search-result-header"><i class="fa-solid fa-box"></i> สินค้า</div>`;
                data.products.forEach(product => {
                    const imgSrc = product.image_path ? `/${product.image_path}` :
                        '/images/product-placeholder.png';
                    html += `
                    <a href="/inventorys/manage-products?search=${encodeURIComponent(product.name)}" class="search-result-item">
                        <img src="${imgSrc}" style="width:32px;height:32px;border-radius:6px;object-fit:cover;background:#f5f5f7;">
                        <div style="flex:1;">
                            <div style="font-weight:500;">${highlightMatch(product.name, query)}</div>
                            <div style="font-size:12px;color:#86868b;">${product.generic_name || '-'} • ฿${parseFloat(product.selling_price).toLocaleString()}</div>
                        </div>
                    </a>
                `;
                });
            }

            // Patients/Customers
            if (data.patients && data.patients.length > 0) {
                hasResults = true;
                html += `<div class="search-result-header"><i class="fa-solid fa-users"></i> ลูกค้า</div>`;
                data.patients.forEach(patient => {
                    html += `
                    <a href="/peoples/patients?search=${encodeURIComponent(patient.name)}" class="search-result-item">
                        <div style="width:32px;height:32px;border-radius:50%;background:#e8f2ff;display:flex;align-items:center;justify-content:center;color:#007aff;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:500;">${highlightMatch(patient.name, query)}</div>
                            <div style="font-size:12px;color:#86868b;">${patient.phone || '-'} ${patient.email ? '• ' + patient.email : ''}</div>
                        </div>
                    </a>
                `;
                });
            }

            // Suppliers
            if (data.suppliers && data.suppliers.length > 0) {
                hasResults = true;
                html += `<div class="search-result-header"><i class="fa-solid fa-truck"></i> Suppliers</div>`;
                data.suppliers.forEach(supplier => {
                    html += `
                    <a href="/purchasing/suppliers?search=${encodeURIComponent(supplier.name)}" class="search-result-item">
                        <div style="width:32px;height:32px;border-radius:8px;background:#e5fbeB;display:flex;align-items:center;justify-content:center;color:#34c759;">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:500;">${highlightMatch(supplier.name, query)}</div>
                            <div style="font-size:12px;color:#86868b;">${supplier.contact_person || '-'} • ${supplier.phone || '-'}</div>
                        </div>
                    </a>
                `;
                });
            }

            // Categories
            if (data.categories && data.categories.length > 0) {
                hasResults = true;
                html += `<div class="search-result-header"><i class="fa-solid fa-folder"></i> หมวดหมู่</div>`;
                data.categories.forEach(category => {
                    html += `
                    <a href="/inventorys/categories?search=${encodeURIComponent(category.name)}" class="search-result-item">
                        <div style="width:32px;height:32px;border-radius:8px;background:#fff3cd;display:flex;align-items:center;justify-content:center;color:#ff9500;">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:500;">${highlightMatch(category.name, query)}</div>
                        </div>
                    </a>
                `;
                });
            }

            // Users
            if (data.users && data.users.length > 0) {
                hasResults = true;
                html += `<div class="search-result-header"><i class="fa-solid fa-user-tie"></i> พนักงาน</div>`;
                data.users.forEach(user => {
                    const avatar = user.profile_photo_path ? `/storage/${user.profile_photo_path}` :
                        '/images/default-avatar.png';
                    html += `
                    <a href="/peoples/staff-user?search=${encodeURIComponent(user.name)}" class="search-result-item">
                        <img src="${avatar}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                        <div style="flex:1;">
                            <div style="font-weight:500;">${highlightMatch(user.name, query)}</div>
                            <div style="font-size:12px;color:#86868b;">${user.email}</div>
                        </div>
                    </a>
                `;
                });
            }

            // Purchase Orders
            if (data.purchases && data.purchases.length > 0) {
                hasResults = true;
                html +=
                    `<div class="search-result-header"><i class="fa-solid fa-file-invoice"></i> ใบสั่งซื้อ</div>`;
                data.purchases.forEach(purchase => {
                    const statusClass = purchase.status === 'completed' ? '#34c759' : purchase
                        .status === 'ordered' ? '#007aff' : '#86868b';
                    html += `
                    <a href="/purchasing/purchase-orders?search=${encodeURIComponent(purchase.reference_number)}" class="search-result-item">
                        <div style="width:32px;height:32px;border-radius:8px;background:#f0f0f5;display:flex;align-items:center;justify-content:center;color:${statusClass};">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:500;">${highlightMatch(purchase.reference_number, query)}</div>
                            <div style="font-size:12px;color:#86868b;">฿${parseFloat(purchase.total_amount).toLocaleString()} • ${purchase.status}</div>
                        </div>
                    </a>
                `;
                });
            }

            if (!hasResults) {
                html = `
                <div style="text-align:center;padding:30px;color:#86868b;">
                    <i class="fa-solid fa-search" style="font-size:24px;opacity:0.5;margin-bottom:10px;display:block;"></i>
                    <div>ไม่พบผลลัพธ์สำหรับ "${query}"</div>
                    <div style="font-size:12px;margin-top:8px;">ลองค้นหาด้วย AI <i class="fa-solid fa-atom"></i></div>
                </div>
            `;
            } else {
                // Add footer with AI suggestion
                html += `
                <div style="padding:10px 16px;border-top:1px solid rgba(0,0,0,0.05);display:flex;justify-content:space-between;align-items:center;">
                    <a href="/search?q=${encodeURIComponent(query)}" style="font-size:12px;color:#007aff;text-decoration:none;">ดูผลลัพธ์ทั้งหมด →</a>
                    <button onclick="document.getElementById('aiSearchButton').click()" style="font-size:12px;color:#d31aff;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:4px;">
                        <i class="fa-solid fa-atom"></i> ค้นหาด้วย AI
                    </button>
                </div>
            `;
            }

            searchResults.innerHTML = html;
            searchResults.classList.add('show');
        }

        function highlightMatch(text, query) {
            if (!text) return '-';
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex,
                '<mark style="background:#ffe066;padding:0 2px;border-radius:2px;">$1</mark>');
        }

        function hideSearchResults() {
            searchResults.classList.remove('show');
        }

        // --- AI Search ---
        function performAiSearch(query) {
            showAiModal(query,
                '<div style="text-align:center;padding:20px;"><i class="fa-solid fa-atom fa-spin" style="font-size:32px;background:linear-gradient(90deg,#007aff,#d31aff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;"></i><div style="margin-top:10px;color:#86868b;">กำลังประมวลผล...</div></div>'
            );

            fetch(`/ai-search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateAiModalContent(formatAiResponse(data.response));
                    } else {
                        updateAiModalContent(
                            `<div style="color:#ff3b30;">เกิดข้อผิดพลาด: ${data.message}</div>`);
                    }
                })
                .catch(error => {
                    console.error('AI Search error:', error);
                    updateAiModalContent('<div style="color:#ff3b30;">ไม่สามารถเชื่อมต่อกับ AI ได้</div>');
                });
        }

        function formatAiResponse(response) {
            // Convert markdown-like formatting to HTML
            return response
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>')
                .replace(/• /g, '&bull; ');
        }

        function showAiModal(query, content) {
            // Remove existing modal if any
            const existingModal = document.getElementById('ai-search-modal');
            if (existingModal) existingModal.remove();

            const modal = document.createElement('div');
            modal.id = 'ai-search-modal';
            modal.innerHTML = `
            <div class="ai-modal-overlay" onclick="this.parentElement.remove()">
                <div class="ai-modal" onclick="event.stopPropagation()">
                    <div class="ai-modal-header">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="fa-solid fa-atom" style="font-size:20px;background:linear-gradient(90deg,#007aff,#7d22ff,#d31aff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;"></i>
                            <span style="font-weight:600;">AI Search</span>
                        </div>
                        <button onclick="this.closest('#ai-search-modal').remove()" style="background:none;border:none;font-size:18px;color:#86868b;cursor:pointer;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    ${query ? `<div class="ai-modal-query"><i class="fa-solid fa-search"></i> ${query}</div>` : ''}
                    <div class="ai-modal-content" id="ai-modal-content">
                        ${content}
                    </div>
                    <div class="ai-modal-footer">
                        <input type="text" id="ai-follow-up" placeholder="ถามเพิ่มเติม..." value="${query}" style="flex:1;border:1px solid #e5e5ea;border-radius:20px;padding:10px 16px;font-size:14px;outline:none;">
                        <button onclick="performFollowUpAiSearch()" style="background:linear-gradient(90deg,#007aff,#d31aff);color:white;border:none;border-radius:20px;padding:10px 20px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-paper-plane"></i> ส่ง
                        </button>
                    </div>
                </div>
            </div>
        `;

            document.body.appendChild(modal);

            // Focus on input
            setTimeout(() => {
                document.getElementById('ai-follow-up').focus();
            }, 100);
        }

        function updateAiModalContent(content) {
            const contentDiv = document.getElementById('ai-modal-content');
            if (contentDiv) {
                contentDiv.innerHTML = content;
            }
        }

        // Make available globally
        window.performFollowUpAiSearch = function() {
            const input = document.getElementById('ai-follow-up');
            if (input && input.value.trim()) {
                performAiSearch(input.value.trim());
            }
        };

        // Enter key for AI follow-up
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && document.activeElement.id === 'ai-follow-up') {
                e.preventDefault();
                window.performFollowUpAiSearch();
            }
        });
    });
    // Bug Report Functions
    // Support Toggles
    window.toggleContactSupport = function() {
        const options = document.getElementById('support-options');
        const view = document.getElementById('contactSupportView');
        if (view.style.display === 'none') {
            options.style.display = 'none';
            view.style.display = 'block';
        } else {
            view.style.display = 'none';
            options.style.display = '';
        }
    };

    window.toggleUserManual = function() {
        const options = document.getElementById('support-options');
        const view = document.getElementById('userManualView');
        if (view.style.display === 'none') {
            options.style.display = 'none';
            view.style.display = 'block';
        } else {
            view.style.display = 'none';
            options.style.display = '';
        }
    };

    window.toggleBugReportForm = function() {
        const options = document.getElementById('support-options');
        const form = document.getElementById('bugReportForm');

        if (form.style.display === 'none') {
            options.style.display = 'none';
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
            options.style.display = ''; // Revert to original CSS
        }
    };

    window.submitBugReport = function() {
        const title = document.getElementById('bug_title').value;
        const description = document.getElementById('bug_description').value;
        const priority = document.getElementById('bug_priority').value;
        const submitBtn = document.getElementById('submit_bug_btn');

        if (!title || !description) {
            alert('Please fill in both summary and description.');
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
        submitBtn.style.opacity = '0.7';

        fetch('{{ route('support.report-bug') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    description: description,
                    priority: priority
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const formContainer = document.getElementById('bugReportForm');
                const originalContent = formContainer.innerHTML;

                formContainer.innerHTML = `
                    <div style="text-align: center; padding: 40px 20px;">
                        <div style="width: 60px; height: 60px; background: #E8F5E9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fa-solid fa-check" style="color: #34C759; font-size: 30px;"></i>
                        </div>
                        <h4 style="font-weight: 600; font-size: 18px; margin-bottom: 10px; color: #1D1D1F;">Thank You!</h4>
                        <p style="color: #86868B; font-size: 15px;">Your bug report has been submitted successfully.</p>
                    </div>
                `;

                setTimeout(() => {
                    // Start closing animation
                    const modalOverlay = document.getElementById('helpSupportModalOverlay');
                    if (modalOverlay) {
                        modalOverlay.classList.add('modal-closing');

                        // Wait for animation to finish before hiding
                        setTimeout(() => {
                            modalOverlay.style.display = 'none';
                            modalOverlay.classList.remove('modal-closing');

                            // Reset form state
                            formContainer.innerHTML = originalContent;
                            toggleBugReportForm(); // Switch back to options view
                        }, 300);
                    }
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the report. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Report';
                submitBtn.style.opacity = '1';
            });
    };
</script>
<script>
    // Global function to force open modal (Backup Trigger)
    window.openHelpSupportModalForce = function() {
        const modalOverlay = document.getElementById('helpSupportModalOverlay');
        if (modalOverlay) {
            modalOverlay.style.display = 'flex';

            // Reset views to default state
            const options = document.getElementById('support-options');
            if (options) options.style.display = '';

            ['contactSupportView', 'userManualView', 'bugReportForm'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
        } else {
            console.error('Help Modal Overlay not found!');
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Handle Help/Support Modal
        const helpBtn = document.getElementById('showHelpModalButton');
        const modalOverlay = document.getElementById('helpSupportModalOverlay');
        const closeModalBtn = document.getElementById('closeModalBtn');

        if (helpBtn && modalOverlay) {
            helpBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                modalOverlay.style.display = 'flex';
                // Reset views
                document.getElementById('support-options').style.display =
                    'grid'; // Note: check css, usually flex or grid
                document.getElementById('support-options').style.display = ''; // Reset to css default
                document.getElementById('contactSupportView').style.display = 'none';
                document.getElementById('userManualView').style.display = 'none';
                document.getElementById('bugReportForm').style.display = 'none';
            });
        }

        if (closeModalBtn && modalOverlay) {
            closeModalBtn.addEventListener('click', function() {
                modalOverlay.style.display = 'none';
            });
        }

        // Close on outside click
        if (modalOverlay) {
            modalOverlay.addEventListener('click', function(e) {
                if (e.target === modalOverlay) {
                    modalOverlay.style.display = 'none';
                }
            });
        }
    });

    // Manual Content Data
    const manualData = {
        'getting-started': {
            title: 'Getting Started Guide',
            content: `
                <p>Welcome to Oboun ERP! This guide helps you set up your account and basic settings.</p>
                <ul style="margin-left: 20px; list-style-type: disc; margin-top: 10px;">
                    <li><strong>Dashboard:</strong> Overview of your business stats.</li>
                    <li><strong>Settings:</strong> Configure company info and preferences.</li>
                    <li><strong>Users:</strong> Invite your team members.</li>
                </ul>
            `
        },
        'inventory': {
            title: 'Inventory Management',
            content: `
                <p>Manage your stock efficiently with these features:</p>
                <ul style="margin-left: 20px; list-style-type: disc; margin-top: 10px;">
                    <li><strong>Add Products:</strong> Create new items with barcodes.</li>
                    <li><strong>Stock Adjustment:</strong> Update quantities manually.</li>
                    <li><strong>Low Stock Alerts:</strong> Get notified when items run low.</li>
                </ul>
            `
        },
        'sales': {
            title: 'Sales & POS System',
            content: `
                <p>Process transactions quickly and easily:</p>
                <ul style="margin-left: 20px; list-style-type: disc; margin-top: 10px;">
                    <li><strong>New Sale:</strong> Add items to cart and checkout.</li>
                    <li><strong>Receipts:</strong> Print or email receipts to customers.</li>
                    <li><strong>Daily Report:</strong> View sales summary at end of day.</li>
                </ul>
            `
        },
        'staff': {
            title: 'Staff Management',
            content: `
                <p>Control access and manage your team:</p>
                <ul style="margin-left: 20px; list-style-type: disc; margin-top: 10px;">
                    <li><strong>Roles:</strong> Assign Admin, Manager, or Staff roles.</li>
                    <li><strong>Permissions:</strong> Limit access to sensitive data.</li>
                    <li><strong>Activity Log:</strong> Monitor staff actions.</li>
                </ul>
            `
        }
    };

    window.showManualDetail = function(key) {
        const data = manualData[key];
        if (!data) return;

        document.getElementById('manualList').style.display = 'none';

        const detailView = document.getElementById('manualDetail');
        document.getElementById('manualDetailTitle').innerText = data.title;
        document.getElementById('manualDetailContent').innerHTML = data.content;

        detailView.style.display = 'block';
        detailView.classList.add('manual-slide-enter');
    };

    window.hideManualDetail = function() {
        document.getElementById('manualDetail').style.display = 'none';
        document.getElementById('manualList').style.display = 'block';
        // reset animation class so it plays again next time
        document.getElementById('manualDetail').classList.remove('manual-slide-enter');
    };
</script>

<style>
    /* Apple Support Modal Styles */
    .help-support-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        display: none;
        /* Hidden by default */
        justify-content: center;
        align-items: center;
        z-index: 10000;
        animation: fadeIn 0.2s ease;
    }

    .help-support-modal {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        width: 100%;
        max-width: 500px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 85vh;
        animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.5);
    }

    .modal-title {
        font-size: 17px;
        font-weight: 600;
        color: #1d1d1f;
        margin: 0;
    }

    .modal-close-btn {
        background: #f2f2f7;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #86868b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        background: #e5e5ea;
        color: #1d1d1f;
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
    }

    .modal-greeting {
        font-size: 22px;
        font-weight: 700;
        color: #1d1d1f;
        margin-bottom: 8px;
    }

    .modal-description {
        font-size: 15px;
        color: #86868b;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .modal-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .modal-action-link {
        display: flex;
        align-items: center;
        padding: 16px;
        background: #f5f5f7;
        border-radius: 12px;
        text-decoration: none;
        color: #1d1d1f;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.2s;
    }

    .modal-action-link:hover {
        background: #e5e5ea;
        transform: scale(1.02);
    }

    .modal-action-link i {
        font-size: 20px;
        margin-right: 14px;
        width: 24px;
        text-align: center;
    }

    .modal-action-link.primary {
        background: #007aff;
        color: white;
    }

    .modal-action-link.primary:hover {
        background: #0062cc;
    }

    .modal-action-link.primary i {
        color: white;
    }

    .modal-action-link .fa-headset {
        color: #007aff;
    }

    .modal-action-link.primary .fa-headset {
        color: white;
    }

    .modal-action-link .fa-book-open {
        color: #ff9500;
    }

    .modal-action-link .fa-bug {
        color: #ff3b30;
    }

    .modal-footer-info {
        text-align: center;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        color: #86868b;
        font-size: 12px;
    }

    /* AI Search Modal Styles */
    .ai-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10001;
        animation: fadeIn 0.2s ease;
    }

    /* Back Button Animation */
    .back-btn-animated {
        transition: all 0.2s cubic-bezier(0.25, 0.1, 0.25, 1);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .back-btn-animated:hover {
        transform: translateX(-4px);
        color: #007AFF !important;
    }

    /* Manual Slide Animation */
    .manual-slide-enter {
        animation: slideInRightManual 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes slideInRightManual {
        from {
            transform: translateX(20px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .ai-modal {
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .ai-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        background: linear-gradient(135deg, rgba(0, 122, 255, 0.05) 0%, rgba(211, 26, 255, 0.05) 100%);
    }

    .ai-modal-query {
        padding: 12px 20px;
        background: #f5f5f7;
        font-size: 14px;
        color: #1d1d1f;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-modal-query i {
        color: #86868b;
    }

    .ai-modal-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        font-size: 14px;
        line-height: 1.6;
        color: #1d1d1f;
    }

    .ai-modal-footer {
        display: flex;
        gap: 10px;
        padding: 16px 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        background: #fafafa;
    }

    .ai-modal-footer input:focus {
        border-color: #007aff;
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    /* Modal Closing Animation */
    .modal-closing {
        animation: fadeOut 0.3s ease forwards !important;
    }

    .modal-closing .help-support-modal {
        animation: scaleOut 0.3s cubic-bezier(0.32, 0, 0.67, 0) forwards !important;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes scaleOut {
        from {
            transform: scale(1);
            opacity: 1;
        }

        to {
            transform: scale(0.95);
            opacity: 0;
        }
    }
</style>

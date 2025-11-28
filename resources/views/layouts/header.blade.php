<header id="header" class="apple-header">
    {{-- เปลี่ยนกลับเป็น header-left-section เพื่อให้ CSS ทำงานถูกต้อง --}}
    <div class="header-left-section">

        <!-- Global Search Wrapper (รวมช่องค้นหาและผลลัพธ์) -->
        <div class="global-search-wrapper">
            <div class="header-search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"
                    style="
       background: linear-gradient(90deg,#007aff,#7d22ff,#d31aff,#ff3b30,#ff9500);
       -webkit-background-clip: text;
       -webkit-text-fill-color: transparent;
   ">
                </i>

                <input type="text" id="globalSearch" placeholder="Search or Ai search" autocomplete="off" />
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
            <i class="fa-solid fa-microphone"
                style="
           background: linear-gradient(90deg,#007aff);
           -webkit-background-clip: text;
           -webkit-text-fill-color: transparent;
       ">
            </i>
        </button>

    </div>

    <div class="header-user-actions">

        <!-- Help/Support Button (ปุ่มหลัก: ID ใหม่) -->
        <button class="header-action-btn" title="Help & Support" id="showHelpModalButton">
            <i class="fa-solid fa-comment-medical"></i>
            <span class="help-badge">!</span>
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
<div id="appleModalOverlay" class="apple-modal-overlay">
    <div id="appleModal" class="apple-modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
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

            <div class="modal-actions">
                <a href="#" class="modal-action-link primary">
                    <i class="fa-solid fa-headset"></i> Contact Support
                </a>
                <a href="#" class="modal-action-link">
                    <i class="fa-solid fa-book-open"></i> User Manual (Docs)
                </a>
                <a href="#" class="modal-action-link">
                    <i class="fa-solid fa-bug"></i> Report a Bug
                </a>
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
                <div class="mac-toast-content">
                    <div class="mac-toast-title">${userName}</div>
                    <div class="mac-toast-message">${notification.description}</div>
                    <div style="font-size: 11px; color: #8e8e93; margin-top: 4px;">${new Date(notification.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                </div>
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

<header id="header" class="apple-header">
    {{-- เปลี่ยนกลับเป็น header-left-section เพื่อให้ CSS ทำงานถูกต้อง --}}
    <div class="header-left-section"> 
        
        <!-- Global Search Wrapper (รวมช่องค้นหาและผลลัพธ์) -->
        <div class="global-search-wrapper">
            <div class="header-search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="globalSearch" placeholder="Search or Ai search" autocomplete="off" />
                <i id="aiSearchButton" class="fa-solid fa-atom gradient-icon ai-search-icon"></i>
            </div>
            <!-- ผลลัพธ์การค้นหา -->
            <div id="liveSearchResults" class="live-search-results"> </div>
        </div>
        
        <!-- Filter Button -->
        <button class="header-action-btn" title="Filter Records">
            <i class="fa-solid fa-filter"></i>
        </button>
    </div>

    <div class="header-user-actions">

        <!-- Help/Support Button (ปุ่มหลัก: ID ใหม่) -->
        <button class="header-action-btn" title="Help & Support" id="showHelpModalButton">
            <i class="fa-solid fa-comment-medical"></i>
            <span class="help-badge">!</span>
        </button>

        <!-- Setting/Slider Button -->
        <button class="header-action-btn" title="System Preferences">
            <i class="fa-solid fa-sliders"></i>
        </button>
        
        <!-- Notification Button -->
        <button class="header-action-btn" title="Notifications">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge">9+</span>
        </button>

        <div class="user-profile-dropdown-wrapper">

            <div class="user-profile-container" id="userProfileButton">
                <img src="https://cdn.kbizoom.com/media/2025/05/19013428/go-yoon-jung-2025-1905251-1905251.webp"
                    alt="User Avatar" class="user-avatar">
                <span class="user-name" style="font-family: -apple-system, BlinkMacSystemFont, "SF Pro
                    Display", "SF Pro Text" , "IBM Plex Sans Thai" , "Noto Sans Thai" , "Segoe UI" ,
                    Roboto, "Helvetica Neue" , Arial, sans-serif;">{{ Auth::user()->name }}</span>
                <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
            </div>

            <div class="profile-dropdown-menu" id="profileDropdown">
                <a href="{{ route('profile.edit') }}" class="dropdown-item profile-header">
                    <img src="https://cdn.kbizoom.com/media/2025/05/19013428/go-yoon-jung-2025-1905251-1905251.webp"
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
            <p class="modal-description">We are here to assist you. Please select an option below or type your question to find answers.</p>
            
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
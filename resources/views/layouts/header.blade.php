<header id="header" class="apple-header">


    <div class="header-container">

        <div class="global-search-wrapper">

            <div class="header-search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="text" id="globalSearch" placeholder="Search or Ai search" autocomplete="off" />
                <i id="aiSearchButton" class="fa-solid fa-atom gradient-icon ai-search-icon"></i>
            </div>

            <div id="liveSearchResults" class="live-search-results">
            </div>

        </div>
    </div>

    <div class="header-user-actions">

        <button class="header-action-btn" id="showHelpModalButton">
            <i class="fa-solid fa-comment-medical"></i>

            <span class="help-badge">!</span>
        </button>

        <button class="header-action-btn">
            <i class="fa-solid fa-sliders"></i>
        </button>
        <button class="header-action-btn">
            <i class="fa fa-bell"></i>
            <span class="notification-badge">9+</span>
        </button>

        <div class="user-profile-dropdown-wrapper">

            <div class="user-profile-container" id="userProfileButton">
                <img src="https://cdn.kbizoom.com/media/2025/05/19013428/go-yoon-jung-2025-1905251-1905251.webp"
                    alt="User Avatar" class="user-avatar">
                <span class="user-name" style="font-family: -apple-system, BlinkMacSystemFont, "SF Pro
                    Display", "SF Pro Text" , "IBM Plex Sans Thai" , "Noto Sans Thai" , "Segoe UI" ,
                    Roboto, "Helvetica Neue" , Arial, sans-serif;">{{ Auth::user()->name }}</span>
                <i class="fa fa-chevron-down dropdown-arrow"></i>
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
                <a href="#" class="menu-item">
                    <div style="display: flex; align-items: center; gap: 12px;">

                        <i class="fa-solid fa-gear"></i>
                        <span>Settings</span>
                    </div>
                </a>
                <a href="#" class="menu-item">
                    <div style="display: flex; align-items: center; gap: 12px;">

                        <i class="fa-solid fa-headset"></i>
                        <span>Support</span>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a :href="route('logout')"
                        onclick="event.preventDefault(); 
                    this.closest('form').submit();"
                        class="dropdown-item danger">Log Out</a>
                </form>
            </div>

        </div>
    </div>

</header>

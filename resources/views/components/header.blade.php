<header class="header glass">
    <div class="max-w-[1800px] h-full flex items-center justify-between px-2 lg:px-4">
        <div class="header-logo-section flex items-center gap-4">
            {{-- Global Search Bar --}}
            <div class="relative w-64 md:w-80 group hidden sm:block" id="global-search-container">
                <input type="text" id="global-search" placeholder="{{ __('general.search_everything') }}"
                    class="w-full bg-white/50 hover:bg-white/80 focus:bg-white backdrop-blur-xl border border-black/5 rounded-[1.25rem] py-2.5 pl-11 pr-4 text-sm focus:ring-4 focus:ring-ios-blue/10 transition-all outline-none shadow-[0_2px_10px_-3px_rgba(0,0,0,0.07),inset_0_1px_1px_rgba(255,255,255,0.5)]"
                    autocomplete="off" oninput="handleGlobalSearch(this.value)"
                    onfocus="document.getElementById('global-search-results').classList.remove('hidden')"
                    onkeydown="handleSearchKeydown(event)">
                <i class="ph ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-900 text-lg group-focus-within:text-ios-blue transition-colors pointer-events-none z-10"
                    style="font-style: normal;"></i>
                <div id="search-spinner" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                    <i class="ph ph-spinner-gap animate-spin text-gray-400"></i>
                </div>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"
                    id="search-shortcut">
                    <span
                        class="text-[10px] font-bold text-gray-400 bg-gray-200/50 px-1.5 py-0.5 rounded border border-gray-300/50">⌘
                        K</span>
                </div>

                {{-- Search Results Dropdown --}}
                <div id="global-search-results"
                    class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 max-h-[70vh] overflow-y-auto hidden z-50">
                    <div id="search-results-content" class="p-2">
                        {{-- Results will be inserted here --}}
                    </div>
                    <div id="search-empty" class="p-6 text-center text-gray-400 hidden">
                        <i class="ph ph-magnifying-glass text-3xl mb-2"></i>
                        <p class="text-sm font-medium">{{ __('general.no_results') }}</p>
                    </div>
                    <div id="search-hint" class="p-4 text-center text-gray-400">
                        <p class="text-xs">{{ __('general.search_hint') }}</p>
                    </div>
                </div>
            </div>

            {{-- Apple Intelligence Style Icon - AI Chat --}}
            <button onclick="openAiChat()"
                class="relative flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-50 via-purple-50 to-orange-50 border border-white/50 shadow-sm active-scale transition-all hover:shadow-md mr-1"
                title="{{ __('ai.assistant') }}">
                <i
                    class="ph-fill ph-atom text-xl text-transparent bg-clip-text bg-gradient-to-tr from-blue-500 via-purple-500 to-orange-500"></i>
            </button>

            {{-- Website Home Link --}}
            <a href="{{ route('landing') }}"
                class="flex items-center justify-center w-8 h-8 rounded-full bg-white/50 border border-white/50 shadow-sm active-scale transition-all hover:shadow-md hover:bg-white"
                title="{{ __('general.go_to_website') }}">
                <i class="ph ph-arrow-square-out text-lg text-gray-700"></i>
            </a>
        </div>

        <div class="header-actions">
            {{-- Appearance Toggle (Hover Dropdown) --}}
            <div class="relative" id="appearance-menu-container" onmouseenter="showAppearanceMenu()"
                onmouseleave="hideAppearanceMenu()">
                <button class="notification-btn group" id="appearance-toggle-btn">
                    <i id="appearance-icon" class="ph ph-moon-stars notification-icon"></i>
                </button>
                <div id="appearance-dropdown" class="user-dropdown user-dropdown-hidden hidden !w-44 !mt-2">
                    <div class="p-1 space-y-0.5">
                        <button onclick="setAppearance('light')" class="user-dropdown-link w-full group text-left">
                            <i class="ph ph-sun user-dropdown-link-icon"></i>
                            <span class="font-medium text-sm">{{ __('appearance.light') }}</span>
                            <i id="check-light" class="ph-bold ph-check text-ios-blue ml-auto hidden"></i>
                        </button>
                        <button onclick="setAppearance('dark')" class="user-dropdown-link w-full group text-left">
                            <i class="ph ph-moon user-dropdown-link-icon"></i>
                            <span class="font-medium text-sm">{{ __('appearance.dark') }}</span>
                            <i id="check-dark" class="ph-bold ph-check text-ios-blue ml-auto hidden"></i>
                        </button>
                        <button onclick="setAppearance('system')" class="user-dropdown-link w-full group text-left">
                            <i class="ph ph-desktop user-dropdown-link-icon"></i>
                            <span class="font-medium text-sm">{{ __('appearance.system') }}</span>
                            <i id="check-system" class="ph-bold ph-check text-ios-blue ml-auto hidden"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Keyboard Shortcuts --}}
            <button class="notification-btn group" onclick="openKeyboardShortcuts()">
                <i class="ph ph-keyboard notification-icon"></i>
            </button>

            {{-- Language Switcher (Globe Icon with Dropdown) --}}
            <div class="relative" id="lang-menu-container" onmouseenter="showLangMenu()" onmouseleave="hideLangMenu()">
                <button class="notification-btn group">
                    <i class="ph ph-globe notification-icon"></i>
                </button>
                <div id="lang-dropdown" class="user-dropdown user-dropdown-hidden hidden !w-40 !mt-2">
                    <div class="p-1 space-y-0.5">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="user-dropdown-link group {{ app()->getLocale() === 'en' ? 'bg-ios-blue/5' : '' }}">
                            <span
                                class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-lg text-[10px] font-bold group-hover:bg-white/20 transition-colors">EN</span>
                            <span class="flex-1 font-medium text-sm">English</span>
                            @if (app()->getLocale() === 'en')
                                <i class="ph-bold ph-check text-ios-blue"></i>
                            @endif
                        </a>
                        <a href="{{ route('lang.switch', 'th') }}"
                            class="user-dropdown-link group {{ app()->getLocale() === 'th' ? 'bg-ios-blue/5' : '' }}">
                            <span
                                class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-lg text-[10px] font-bold group-hover:bg-white/20 transition-colors">TH</span>
                            <span class="flex-1 font-medium text-sm">ภาษาไทย</span>
                            @if (app()->getLocale() === 'th')
                                <i class="ph-bold ph-check text-ios-blue"></i>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            {{-- Notification Bell with Dropdown --}}
            <div class="relative" id="notification-menu-container" onmouseenter="showNotificationMenu()"
                onmouseleave="hideNotificationMenu()">
                <button class="notification-btn group">
                    <i class="ph-fill ph-bell notification-icon"></i>
                    @if (isset($notificationCount) && $notificationCount > 0)
                        <span class="notification-badge">
                            {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                        </span>
                    @endif
                </button>
                <div id="notification-dropdown"
                    class="user-dropdown user-dropdown-hidden hidden !w-80 !mt-2 !right-0 !left-auto">
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900">{{ __('notifications.title') }}</h3>
                            @if (isset($notificationCount) && $notificationCount > 0)
                                <span
                                    class="text-xs font-bold text-white bg-ios-red px-2 py-0.5 rounded-full">{{ $notificationCount }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="max-h-80 overflow-y-auto custom-scroll">
                        @if (isset($headerNotifications) && $headerNotifications->count() > 0)
                            @foreach ($headerNotifications as $notification)
                                <a href="{{ $notification['link'] }}"
                                    class="flex items-start gap-3 p-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                    <div
                                        class="w-10 h-10 rounded-xl {{ $notification['color'] }} flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $notification['icon'] }} text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $notification['title'] }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $notification['message'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="p-6 text-center text-gray-400">
                                <i class="ph ph-bell-slash text-3xl mb-2"></i>
                                <p class="text-sm font-medium">{{ __('notifications.no_notifications') }}</p>
                            </div>
                        @endif
                    </div>
                    @if (isset($headerNotifications) && $headerNotifications->count() > 0)
                        <div class="p-3 border-t border-gray-100">
                            <a href="{{ route('activity-logs.index') }}"
                                class="block w-full text-center text-sm font-semibold text-ios-blue hover:underline">
                                {{ __('notifications.view_all') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- User Menu with Hover --}}
            <div class="relative" id="user-menu-container" onmouseenter="showUserMenu()"
                onmouseleave="hideUserMenu()">
                <button class="user-btn group">
                    <div class="user-btn-text hidden md:flex items-end text-right">
                        <span class="user-btn-name">{{ auth()->user()->name }}</span>
                        <span class="user-btn-role">{{ auth()->user()->role ?? 'Staff' }}</span>
                    </div>
                    <div class="relative">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                alt="{{ auth()->user()->name }}" class="user-btn-avatar object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest User') }}&background=007AFF&color=fff"
                                alt="User" class="user-btn-avatar">
                        @endif
                        {{-- Notification dot --}}
                        <div class="user-btn-dot"></div>
                    </div>
                    <i
                        class="ph ph-bold ph-caret-down text-[10px] text-gray-400 mr-1.5 group-hover:text-gray-900 transition-colors"></i>
                </button>

                {{-- User Dropdown Menu --}}
                <div id="user-dropdown" class="user-dropdown user-dropdown-hidden hidden">
                    {{-- User Info Section --}}
                    <div class="user-dropdown-info">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0">
                                @if (auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'GU', 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="user-dropdown-name">{{ auth()->user()->name ?? 'Guest User' }}</h4>
                                <p class="user-dropdown-email">{{ auth()->user()->email ?? 'guest@oboun.local' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    <div class="space-y-0.5">
                        <a href="{{ route('profile.edit') }}" class="user-dropdown-link group">
                            <i class="ph ph-user-circle user-dropdown-link-icon"></i>
                            {{ __('user.my_profile') }}
                        </a>
                        <a href="#" class="user-dropdown-link group relative"
                            onclick="event.preventDefault(); hideUserMenu(); setTimeout(() => { showNotificationMenu(); document.getElementById('notification-menu-container').scrollIntoView({behavior: 'smooth'}); }, 100);">
                            <i class="ph ph-bell user-dropdown-link-icon"></i>
                            {{ __('user.notifications') }}
                            @if (isset($notificationCount) && $notificationCount > 0)
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 min-w-[20px] h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1">
                                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('activity-logs.index') }}" class="user-dropdown-link group relative">
                            <i class="ph ph-clock-counter-clockwise user-dropdown-link-icon"></i>
                            {{ __('user.my_recent') }}
                            @if (isset($recentActivityCount) && $recentActivityCount > 0)
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 min-w-[20px] h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1">
                                    {{ $recentActivityCount > 99 ? '99+' : $recentActivityCount }}
                                </span>
                            @endif
                        </a>
                        <a href="#" class="user-dropdown-link group" onclick="openHelpSupport()">
                            <i class="ph ph-question user-dropdown-link-icon"></i>
                            {{ __('user.help_support') }}
                        </a>
                        <a href="{{ route('settings.index') }}" class="user-dropdown-link group">
                            <i class="ph ph-gear user-dropdown-link-icon"></i>
                            {{ __('user.settings') }}
                        </a>

                        <div class="user-dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" onclick="showToast('{{ __('user.logged_out') }}', 'info')"
                                class="user-dropdown-signout group">
                                <i class="ph ph-sign-out group-hover:translate-x-1 transition-transform"></i>
                                {{ __('user.sign_out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Help & Support Modal --}}
<div id="helpModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="closeHelpSupport()"></div>
<div id="helpModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem;">
    <div class="modal-header">
        <h2 class="modal-title">{{ __('user.help_support') }}</h2>
        <button onclick="closeHelpSupport()" class="modal-close-btn">
            <i class="ph-bold ph-x text-gray-500"></i>
        </button>
    </div>
    <div class="modal-content">
        <div class="grid grid-cols-1 gap-3" id="help-main-menu">
            <button onclick="showHelpContent('docs')"
                class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-ios-blue hover:text-white transition group text-left">
                <div
                    class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-ios-blue group-hover:bg-white/20 group-hover:text-white">
                    <i class="ph ph-book-open text-2xl"></i>
                </div>
                <div>
                    <div class="font-bold">User Manual (Docs)</div>
                    <div class="text-xs opacity-60">Learn how to use the system</div>
                </div>
                <i class="ph ph-caret-right ml-auto opacity-40"></i>
            </button>

            <button onclick="showHelpContent('contact')"
                class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-green-500 hover:text-white transition group text-left">
                <div
                    class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 group-hover:bg-white/20 group-hover:text-white">
                    <i class="ph ph-headset text-2xl"></i>
                </div>
                <div>
                    <div class="font-bold">Contact Support</div>
                    <div class="text-xs opacity-60">Get help from our IT team</div>
                </div>
                <i class="ph ph-caret-right ml-auto opacity-40"></i>
            </button>

            <button onclick="showHelpContent('bug')"
                class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-orange-500 hover:text-white transition group text-left">
                <div
                    class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 group-hover:bg-white/20 group-hover:text-white">
                    <i class="ph ph-bug text-2xl"></i>
                </div>
                <div>
                    <div class="font-bold">Report a Bug</div>
                    <div class="text-xs opacity-60">Help us improve the system</div>
                </div>
                <i class="ph ph-caret-right ml-auto opacity-40"></i>
            </button>
        </div>

        {{-- Help Content Views --}}
        <div id="help-content" class="hidden">
            <button onclick="backToHelpMain()"
                class="mb-4 text-ios-blue font-medium flex items-center gap-1 hover:underline">
                <i class="ph ph-arrow-left"></i> Back to Help
            </button>
            <div id="help-content-inner"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button onclick="closeHelpSupport()"
            class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
            {{ __('close') }}
        </button>
    </div>
</div>

{{-- Keyboard Shortcuts Modal --}}
<div id="shortcutsModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
    onclick="closeKeyboardShortcuts()"></div>
<div id="shortcutsModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem;">
    <div class="modal-header">
        <h2 class="modal-title">Keyboard Shortcuts</h2>
        <button onclick="closeKeyboardShortcuts()" class="modal-close-btn">
            <i class="ph-bold ph-x text-gray-500"></i>
        </button>
    </div>
    <div class="modal-content">
        <div class="space-y-6">
            {{-- POS Section --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">POS & Checkout</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Search Products</span>
                        <kbd
                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded-lg text-xs font-bold shadow-sm">F1</kbd>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Select Customer</span>
                        <kbd
                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded-lg text-xs font-bold shadow-sm">F2</kbd>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Payment / Checkout</span>
                        <kbd
                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded-lg text-xs font-bold shadow-sm">F8</kbd>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Hold Order</span>
                        <kbd
                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded-lg text-xs font-bold shadow-sm">⌘
                            H</kbd>
                    </div>
                </div>
            </div>

            {{-- General Section --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">General</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Global Search</span>
                        <kbd
                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded-lg text-xs font-bold shadow-sm">⌘
                            K</kbd>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Close Modals</span>
                        <kbd
                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded-lg text-xs font-bold shadow-sm">ESC</kbd>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button onclick="closeKeyboardShortcuts()"
            class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
            {{ __('close') }}
    </div>
</div>

{{-- AI Chat Modal --}}
<div id="aiChatModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="closeAiChat()"></div>
<div id="aiChatModal-panel" class="modal-panel modal-panel-hidden"
    style="max-width: 28rem; height: 600px; display: flex; flex-direction: column;">
    <div class="modal-header border-b border-gray-100" style="flex-shrink: 0;">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 via-purple-500 to-orange-500 flex items-center justify-center shadow-lg">
                <i class="ph-fill ph-atom text-white text-xl"></i>
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Oboun AI</h2>
                <p class="text-xs text-gray-400">{{ __('ai.assistant_desc') }}</p>
            </div>
        </div>
        <button onclick="closeAiChat()" class="modal-close-btn">
            <i class="ph-bold ph-x text-gray-500"></i>
        </button>
    </div>

    <div id="ai-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 custom-scroll" style="min-height: 0;">
        {{-- Welcome Message --}}
        <div class="flex gap-3">
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 via-purple-500 to-orange-500 flex items-center justify-center flex-shrink-0">
                <i class="ph-fill ph-atom text-white text-sm"></i>
            </div>
            <div class="flex-1">
                <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-[90%]">
                    <p class="text-sm text-gray-800">{{ __('ai.welcome_greeting') }} <strong>Oboun AI</strong>
                        {{ __('ai.welcome_intro') }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ __('ai.welcome_help') }}</p>
                    <ul class="text-sm text-gray-600 mt-1 space-y-1">
                        <li>{{ __('ai.welcome_drugs') }}</li>
                        <li>{{ __('ai.welcome_interactions') }}</li>
                        <li>{{ __('ai.welcome_erp') }}</li>
                        <li>{{ __('ai.welcome_general') }}</li>
                    </ul>
                    <p class="text-sm text-gray-600 mt-2">{{ __('ai.welcome_cta') }}</p>
                </div>
                <span class="text-[10px] text-gray-400 mt-1 block">Oboun AI</span>
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-gray-100" style="flex-shrink: 0;">
        <form id="ai-chat-form" onsubmit="sendAiMessage(event)" class="flex items-center gap-2">
            <input type="text" id="ai-chat-input" placeholder="{{ __('ai.type_message') }}"
                class="flex-1 bg-gray-100 border-0 rounded-full py-3 px-5 text-sm focus:ring-2 focus:ring-purple-500 outline-none transition"
                autocomplete="off">
            <button type="submit" id="ai-send-btn"
                class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-orange-500 flex items-center justify-center text-white hover:brightness-110 transition disabled:opacity-50 shadow-lg">
                <i class="ph-bold ph-paper-plane-tilt text-base" id="ai-send-icon"></i>
                <i class="ph-bold ph-spinner-gap animate-spin text-base hidden" id="ai-loading-icon"></i>
            </button>
        </form>
        <p class="text-[10px] text-gray-400 text-center mt-2">
            Powered by Google Gemini AI • {{ __('ai.disclaimer') }}
        </p>
    </div>
</div>

<script>
    // =============================================
    // AI Chat Functions
    // =============================================
    let aiChatHistory = [];

    function openAiChat() {
        document.getElementById('aiChatModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
        document.getElementById('aiChatModal-panel').classList.remove('modal-panel-hidden');
        setTimeout(() => {
            document.getElementById('ai-chat-input').focus();
        }, 100);
    }

    function closeAiChat() {
        document.getElementById('aiChatModal-backdrop').classList.add('modal-backdrop-hidden');
        document.getElementById('aiChatModal-panel').classList.add('modal-panel-hidden');
        setTimeout(() => {
            document.getElementById('aiChatModal-backdrop').classList.add('hidden');
        }, 200);
    }

    async function sendAiMessage(event) {
        event.preventDefault();
        event.stopPropagation();

        const input = document.getElementById('ai-chat-input');
        const message = input.value.trim();

        if (!message) return false;

        // Clear input
        input.value = '';

        // Add user message to chat
        addChatMessage(message, 'user');

        // Show loading state
        setAiLoading(true);

        // Add typing indicator
        const typingId = addTypingIndicator();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch('/api/ai-chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message
                })
            });

            // Remove typing indicator
            removeTypingIndicator(typingId);

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                addChatMessage(errorData.error || 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', 'ai', true);
            } else {
                const data = await response.json();
                if (data.success) {
                    addChatMessage(data.message, 'ai');
                } else {
                    addChatMessage(data.error || 'ขออภัย เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', 'ai', true);
                }
            }
        } catch (error) {
            console.error('AI Chat Error:', error);
            removeTypingIndicator(typingId);
            addChatMessage('❌ ไม่สามารถเชื่อมต่อกับ AI ได้ กรุณาลองใหม่อีกครั้ง', 'ai', true);
        } finally {
            setAiLoading(false);
        }

        return false;
    }

    function addChatMessage(message, type, isError = false) {
        const container = document.getElementById('ai-chat-messages');
        const div = document.createElement('div');
        div.className = 'flex gap-3 ' + (type === 'user' ? 'justify-end' : '');

        // Format message with markdown-like formatting
        const formattedMessage = formatAiMessage(message);

        if (type === 'user') {
            div.innerHTML = `
                <div class="flex flex-col items-end max-w-[85%]">
                    <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-orange-500 text-white rounded-2xl rounded-tr-none px-4 py-3">
                        <p class="text-sm">${escapeHtml(message)}</p>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1">{{ __('ai.you') }}</span>
                </div>
            `;
        } else {
            div.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 via-purple-500 to-orange-500 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-atom text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="bg-gray-100 ${isError ? 'bg-red-50 border border-red-200' : ''} rounded-2xl rounded-tl-none px-4 py-3 max-w-[90%]">
                        <div class="text-sm ${isError ? 'text-red-600' : 'text-gray-800'} ai-message-content">${formattedMessage}</div>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block">Oboun AI</span>
                </div>
            `;
        }

        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function addTypingIndicator() {
        const container = document.getElementById('ai-chat-messages');
        const id = 'typing-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex gap-3';
        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 via-purple-500 to-orange-500 flex items-center justify-center flex-shrink-0">
                <i class="ph-fill ph-atom text-white text-sm"></i>
            </div>
            <div class="flex-1">
                <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 inline-block">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function setAiLoading(loading) {
        const sendBtn = document.getElementById('ai-send-btn');
        const sendIcon = document.getElementById('ai-send-icon');
        const loadingIcon = document.getElementById('ai-loading-icon');
        const input = document.getElementById('ai-chat-input');

        if (loading) {
            sendBtn.disabled = true;
            input.disabled = true;
            sendIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');
        } else {
            sendBtn.disabled = false;
            input.disabled = false;
            sendIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');
            input.focus();
        }
    }

    function formatAiMessage(text) {
        // Convert **bold** to <strong>
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Convert *italic* to <em>
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Convert newlines to <br>
        text = text.replace(/\n/g, '<br>');
        // Convert bullet points
        text = text.replace(/^- (.+)$/gm, '<li>$1</li>');
        text = text.replace(/(<li>.*<\/li>)/gs, '<ul class="list-disc list-inside my-2">$1</ul>');
        return text;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Keyboard shortcut for AI Chat (Ctrl/Cmd + J)
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'j') {
            e.preventDefault();
            const modal = document.getElementById('aiChatModal-backdrop');
            if (modal.classList.contains('hidden')) {
                openAiChat();
            } else {
                closeAiChat();
            }
        }
    });

    // =============================================
    // Notification Menu Functions
    // =============================================
    let notificationMenuTimeout;

    function showNotificationMenu() {
        clearTimeout(notificationMenuTimeout);
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.remove('hidden', 'user-dropdown-hidden');
        setTimeout(() => {
            dropdown.classList.add('user-dropdown-visible');
        }, 10);
    }

    function hideNotificationMenu() {
        notificationMenuTimeout = setTimeout(() => {
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.remove('user-dropdown-visible');
            dropdown.classList.add('user-dropdown-hidden');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }, 150);
    }

    // User Menu Functions
    let userMenuTimeout;

    function showUserMenu() {
        clearTimeout(userMenuTimeout);
        const dropdown = document.getElementById('user-dropdown');
        dropdown.classList.remove('hidden', 'user-dropdown-hidden');
        setTimeout(() => {
            dropdown.classList.add('user-dropdown-visible');
        }, 10);
    }

    function hideUserMenu() {
        userMenuTimeout = setTimeout(() => {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.remove('user-dropdown-visible');
            dropdown.classList.add('user-dropdown-hidden');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }, 150);
    }

    // Language Menu Functions
    let langMenuTimeout;

    function showLangMenu() {
        clearTimeout(langMenuTimeout);
        const dropdown = document.getElementById('lang-dropdown');
        dropdown.classList.remove('hidden', 'user-dropdown-hidden');
        setTimeout(() => {
            dropdown.classList.add('user-dropdown-visible');
        }, 10);
    }

    function hideLangMenu() {
        langMenuTimeout = setTimeout(() => {
            const dropdown = document.getElementById('lang-dropdown');
            dropdown.classList.remove('user-dropdown-visible');
            dropdown.classList.add('user-dropdown-hidden');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }, 150);
    }

    // Help & Support Functions
    function openHelpSupport() {
        hideUserMenu();
        document.getElementById('helpModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
        document.getElementById('helpModal-panel').classList.remove('modal-panel-hidden');
    }

    function closeHelpSupport() {
        document.getElementById('helpModal-backdrop').classList.add('modal-backdrop-hidden');
        document.getElementById('helpModal-panel').classList.add('modal-panel-hidden');
        setTimeout(() => {
            document.getElementById('helpModal-backdrop').classList.add('hidden');
            backToHelpMain();
        }, 200);
    }

    // Global Event Listener for Support Modal
    window.addEventListener('open-support-modal', openHelpSupport);

    // Keyboard Shortcuts Functions
    function openKeyboardShortcuts() {
        document.getElementById('shortcutsModal-backdrop').classList.remove('hidden', 'modal-backdrop-hidden');
        document.getElementById('shortcutsModal-panel').classList.remove('modal-panel-hidden');
    }

    function closeKeyboardShortcuts() {
        document.getElementById('shortcutsModal-backdrop').classList.add('modal-backdrop-hidden');
        document.getElementById('shortcutsModal-panel').classList.add('modal-panel-hidden');
        setTimeout(() => {
            document.getElementById('shortcutsModal-backdrop').classList.add('hidden');
        }, 200);
    }

    // Appearance Menu Functions
    let appearanceMenuTimeout;

    function showAppearanceMenu() {
        clearTimeout(appearanceMenuTimeout);
        const dropdown = document.getElementById('appearance-dropdown');
        dropdown.classList.remove('hidden', 'user-dropdown-hidden');
        setTimeout(() => {
            dropdown.classList.add('user-dropdown-visible');
        }, 10);
        updateAppearanceChecks();
    }

    function hideAppearanceMenu() {
        appearanceMenuTimeout = setTimeout(() => {
            const dropdown = document.getElementById('appearance-dropdown');
            dropdown.classList.remove('user-dropdown-visible');
            dropdown.classList.add('user-dropdown-hidden');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }, 150);
    }

    function setAppearance(mode) {
        localStorage.setItem('appearance', mode);
        applyAppearance(mode);
        updateAppearanceChecks();
        hideAppearanceMenu();
    }

    function applyAppearance(mode) {
        const html = document.documentElement;

        if (mode === 'dark') {
            html.classList.add('dark');
        } else if (mode === 'light') {
            html.classList.remove('dark');
        } else {
            // System preference
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }
    }

    function updateAppearanceChecks() {
        const mode = localStorage.getItem('appearance') || 'system';

        // Update checkmarks
        document.getElementById('check-light')?.classList.add('hidden');
        document.getElementById('check-dark')?.classList.add('hidden');
        document.getElementById('check-system')?.classList.add('hidden');

        const checkEl = document.getElementById(`check-${mode}`);
        if (checkEl) checkEl.classList.remove('hidden');

        // Update header icon
        const iconEl = document.getElementById('appearance-icon');
        if (iconEl) {
            iconEl.className = 'notification-icon';
            if (mode === 'light') {
                iconEl.classList.add('ph', 'ph-sun');
            } else if (mode === 'dark') {
                iconEl.classList.add('ph', 'ph-moon');
            } else {
                iconEl.classList.add('ph', 'ph-desktop');
            }
        }
    }

    // Initialize appearance on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedMode = localStorage.getItem('appearance') || 'system';
        applyAppearance(savedMode);
        updateAppearanceChecks();
    });

    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        const mode = localStorage.getItem('appearance') || 'system';
        if (mode === 'system') {
            applyAppearance('system');
        }
    });

    function showHelpContent(type) {
        const mainMenu = document.getElementById('help-main-menu');
        const contentArea = document.getElementById('help-content');
        const inner = document.getElementById('help-content-inner');

        mainMenu.classList.add('hidden');
        contentArea.classList.remove('hidden');

        if (type === 'docs') {
            inner.innerHTML = `
                <div class="space-y-4">
                    <h3 class="font-bold text-lg">System Documentation</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-ios-blue hover:underline flex items-center gap-2"><i class="ph ph-file-text"></i> Getting Started Guide</a></li>
                        <li><a href="#" class="text-ios-blue hover:underline flex items-center gap-2"><i class="ph ph-file-text"></i> POS & Checkout Basics</a></li>
                        <li><a href="#" class="text-ios-blue hover:underline flex items-center gap-2"><i class="ph ph-file-text"></i> Inventory Management</a></li>
                        <li><a href="#" class="text-ios-blue hover:underline flex items-center gap-2"><i class="ph ph-file-text"></i> Customer Loyalty Program</a></li>
                    </ul>
                </div>
            `;
        } else if (type === 'contact') {
            inner.innerHTML = `
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-600 mx-auto mb-4">
                        <i class="ph ph-phone text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Need direct help?</h3>
                    <p class="text-gray-600 mb-6">Our support team is available Mon-Fri, 9am - 6pm</p>
                    <div class="space-y-3">
                        <a href="tel:+6621234567" class="block p-3 bg-gray-50 rounded-xl hover:bg-gray-100 font-medium">
                            Call: 02-123-4567
                        </a>
                        <a href="https://line.me" target="_blank" class="block p-3 bg-[#06C755] text-white rounded-xl hover:brightness-110 font-bold">
                            LINE: @obounerp
                        </a>
                    </div>
                </div>
            `;
        } else if (type === 'bug') {
            inner.innerHTML = `
                <form class="space-y-4" onsubmit="event.preventDefault(); showToast('Thank you! Bug report submitted.', 'success'); closeHelpSupport();">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Issue Description</label>
                        <textarea class="w-full p-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-ios-blue outline-none" rows="3" placeholder="What went wrong?"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select class="w-full p-3 bg-gray-50 rounded-xl border border-gray-200 outline-none">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High (Critical)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full p-3 bg-ios-blue text-white rounded-xl font-bold hover:brightness-110">
                        Submit Report
                    </button>
                </form>
            `;
        }
    }

    function backToHelpMain() {
        document.getElementById('help-main-menu').classList.remove('hidden');
        document.getElementById('help-content').classList.add('hidden');
    }

    // Global Search Functions
    let searchTimeout = null;
    let selectedSearchIndex = -1;

    function handleGlobalSearch(query) {
        clearTimeout(searchTimeout);
        const resultsContainer = document.getElementById('search-results-content');
        const emptyState = document.getElementById('search-empty');
        const hintState = document.getElementById('search-hint');
        const spinner = document.getElementById('search-spinner');
        const shortcut = document.getElementById('search-shortcut');

        if (query.length < 2) {
            resultsContainer.innerHTML = '';
            emptyState.classList.add('hidden');
            hintState.classList.remove('hidden');
            return;
        }

        hintState.classList.add('hidden');
        spinner.classList.remove('hidden');
        shortcut.classList.add('hidden');

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/api/global-search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                renderSearchResults(data, query);
            } catch (error) {
                console.error('Search error:', error);
                resultsContainer.innerHTML = '<div class="p-4 text-center text-red-500">ค้นหาผิดพลาด</div>';
            } finally {
                spinner.classList.add('hidden');
            }
        }, 300);
    }

    function renderSearchResults(data, query) {
        const resultsContainer = document.getElementById('search-results-content');
        const emptyState = document.getElementById('search-empty');
        let html = '';
        let hasResults = false;

        // Products
        if (data.products && data.products.length > 0) {
            hasResults = true;
            html +=
                `<div class="mb-3">
                <div class="px-3 py-1.5 text-xs font-bold text-gray-400 uppercase">{{ __('general.products') }}</div>`;
            data.products.forEach(item => {
                html += `<a href="/products/${item.id}" class="search-result-item flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-pill text-orange-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${highlightMatch(item.name, query)}</p>
                        <p class="text-xs text-gray-500">${item.sku || ''} · ฿${parseFloat(item.unit_price || 0).toFixed(2)}</p>
                    </div>
                </a>`;
            });
            html += `</div>`;
        }

        // Customers
        if (data.customers && data.customers.length > 0) {
            hasResults = true;
            html +=
                `<div class="mb-3">
                <div class="px-3 py-1.5 text-xs font-bold text-gray-400 uppercase">{{ __('general.customers') }}</div>`;
            data.customers.forEach(item => {
                html += `<a href="/customers/${item.id}" class="search-result-item flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-user text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${highlightMatch(item.name, query)}</p>
                        <p class="text-xs text-gray-500">${item.phone || item.email || ''}</p>
                    </div>
                </a>`;
            });
            html += `</div>`;
        }

        // Orders
        if (data.orders && data.orders.length > 0) {
            hasResults = true;
            html += `<div class="mb-3">
                <div class="px-3 py-1.5 text-xs font-bold text-gray-400 uppercase">{{ __('general.orders') }}</div>`;
            data.orders.forEach(item => {
                html += `<a href="/orders/${item.id}" class="search-result-item flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-receipt text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${highlightMatch(item.order_number, query)}</p>
                        <p class="text-xs text-gray-500">฿${parseFloat(item.total_amount || 0).toFixed(2)} · ${item.status}</p>
                    </div>
                </a>`;
            });
            html += `</div>`;
        }

        // Users
        if (data.users && data.users.length > 0) {
            hasResults = true;
            html += `<div class="mb-3">
                <div class="px-3 py-1.5 text-xs font-bold text-gray-400 uppercase">{{ __('general.users') }}</div>`;
            data.users.forEach(item => {
                html += `<a href="/users/${item.id}" class="search-result-item flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-user-circle text-purple-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${highlightMatch(item.name, query)}</p>
                        <p class="text-xs text-gray-500">${item.role || ''} · ${item.email || ''}</p>
                    </div>
                </a>`;
            });
            html += `</div>`;
        }

        // Prescriptions
        if (data.prescriptions && data.prescriptions.length > 0) {
            hasResults = true;
            html +=
                `<div class="mb-3">
                <div class="px-3 py-1.5 text-xs font-bold text-gray-400 uppercase">{{ __('prescriptions.title') }}</div>`;
            data.prescriptions.forEach(item => {
                const customerName = item.customer ? item.customer.name : '';
                html += `<a href="/prescriptions/${item.id}" class="search-result-item flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-prescription text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${highlightMatch(item.prescription_number, query)}</p>
                        <p class="text-xs text-gray-500">${customerName} · ${item.doctor_name || ''} · ${item.status}</p>
                    </div>
                </a>`;
            });
            html += `</div>`;
        }

        // Controlled Drugs
        if (data.controlled_drugs && data.controlled_drugs.length > 0) {
            hasResults = true;
            html +=
                `<div class="mb-3">
                <div class="px-3 py-1.5 text-xs font-bold text-gray-400 uppercase">{{ __('controlled_drugs.title') }}</div>`;
            data.controlled_drugs.forEach(item => {
                const productName = item.product ? item.product.name : '';
                html += `<a href="/controlled-drugs/${item.id}" class="search-result-item flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-shield-warning text-red-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${highlightMatch(item.log_number, query)}</p>
                        <p class="text-xs text-gray-500">${productName} · ${item.customer_name || ''} · ${item.status}</p>
                    </div>
                </a>`;
            });
            html += `</div>`;
        }

        resultsContainer.innerHTML = html;
        if (hasResults) {
            emptyState.classList.add('hidden');
        } else {
            emptyState.classList.remove('hidden');
        }
    }

    function highlightMatch(text, query) {
        if (!text) return '';
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 rounded px-0.5">$1</mark>');
    }

    function handleSearchKeydown(event) {
        if (event.key === 'Escape') {
            document.getElementById('global-search-results').classList.add('hidden');
            document.getElementById('global-search').blur();
        }
    }

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        const container = document.getElementById('global-search-container');
        if (container && !container.contains(e.target)) {
            document.getElementById('global-search-results').classList.add('hidden');
        }
    });

    // Keyboard shortcut ⌘K
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('global-search');
            if (searchInput) {
                searchInput.focus();
                document.getElementById('global-search-results').classList.remove('hidden');
            }
        }
    });
</script>

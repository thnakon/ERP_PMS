{{-- Drawer Overlay (Right Side) --}}
<div id="drawer-backdrop" class="drawer-backdrop drawer-backdrop-hidden hidden" onclick="toggleDrawer(false)"></div>
<div id="drawer-panel" class="drawer-panel drawer-panel-hidden">
    <div class="drawer-header">
        <h2 id="drawer-title" class="drawer-title">{{ __('drawer.details') }}</h2>
        <button onclick="toggleDrawer(false)" class="drawer-close-btn">
            <i class="ph-bold ph-x text-gray-600"></i>
        </button>
    </div>
    <div id="drawer-content" class="drawer-content">
        {{-- Content will be loaded dynamically --}}
        @yield('drawer-content')
    </div>
    <div id="drawer-footer" class="drawer-footer hidden">
        @yield('drawer-footer')
    </div>
</div>

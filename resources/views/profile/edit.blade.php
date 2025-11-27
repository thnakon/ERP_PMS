<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pharmacy Staff Profile & Settings</title>

        <!-- Import Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Import Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Import FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Toastify CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

        <style>
            /* Apple System Font Stack */
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Sarabun", sans-serif;
                background-color: #F5F5F7;
                color: #1D1D1F;
                -webkit-font-smoothing: antialiased;
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 0px;
                background: transparent;
            }

            /* Apple Style Input */
            .apple-input {
                background-color: #F2F2F7;
                border: 1px solid transparent;
                transition: all 0.2s ease;
            }

            .apple-input:focus {
                background-color: #FFFFFF;
                border-color: #007AFF;
                outline: none;
                box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            }

            /* Read-only Input Style */
            .apple-input-readonly {
                background-color: #E5E5EA;
                color: #86868B;
                cursor: not-allowed;
                border-color: transparent;
            }

            /* Apple Switch (Toggle) */
            .apple-switch {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 30px;
            }

            .apple-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #E5E5EA;
                transition: .4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 2px;
                bottom: 2px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            input:checked+.slider {
                background-color: #34C759;
            }

            input:checked+.slider:before {
                transform: translateX(20px);
            }

            /* Soft Shadow */
            .soft-shadow {
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            }

            /* Animations */
            .tab-content {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Timeline Connector */
            .timeline-item::before {
                content: '';
                position: absolute;
                left: 24px;
                top: 48px;
                bottom: -24px;
                width: 2px;
                background-color: #F2F2F7;
                z-index: 0;
            }

            .timeline-item:last-child::before {
                display: none;
            }

            /* Apple-style Pagination */
            .people-pagination {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 24px;
                border-top: 1px solid #e5e5ea;
                margin-top: auto;
                background-color: #fff;
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            .pagination-text {
                font-size: 13px;
                color: #8e8e93;
                font-weight: 500;
            }

            .pagination-controls {
                display: flex;
                gap: 8px;
            }

            .pagination-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 1px solid #e5e5ea;
                background-color: #fff;
                color: #007aff;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
                font-size: 12px;
            }

            .pagination-btn:hover:not(.disabled) {
                background-color: #f2f2f7;
                border-color: #d1d1d6;
            }

            .pagination-btn.disabled {
                color: #c7c7cc;
                cursor: not-allowed;
                border-color: #f2f2f7;
                background-color: #fff;
            }

            /* Apple-style Flash Message */
            .flash-message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 12px 24px;
                border-radius: 99px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                z-index: 9999;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                font-size: 14px;
                font-weight: 500;
                color: #333;
                opacity: 0;
                transition: opacity 0.3s ease, transform 0.3s ease;
                pointer-events: none;
            }

            .flash-message.show {
                opacity: 1;
                transform: translateX(-50%) translateY(10px);
            }

            .flash-message.success i {
                color: #34c759;
            }

            .flash-message.error i {
                color: #ff3b30;
            }
        </style>
    </head>

    <body class="bg-[#F5F5F7] font-sans">
        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message">
            <i class="fa-solid fa-check-circle"></i>
            <span id="flash-text">Operation successful</span>
        </div>



        <!-- Main Container -->
        <div class="os-container">
            {{-- [!!! REFACTORED HEADER !!!] --}}
            <div class="sr-header">
                <div class="sr-header-left">
                    <p class="sr-breadcrumb">
                        Dashboard / <span style="color: #3a3a3c; font-weight: 600;">Profile</span>
                    </p>

                    <h2 class="sr-page-title">My Profile</h2>
                </div>

                <div class="sr-header-right" style="margin-right: 10px">
                    <a href="{{ route('settings.index') }}"><button
                            class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-[#1D1D1F] hover:bg-[#F2F2F7] transition-colors">
                            <i class="fa-solid fa-gear text-lg"></i>
                        </button></a>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- ==============================
                 LEFT COLUMN: Identity & Quick Info
                 ============================== -->
                <div class="lg:col-span-4 xl:col-span-3 space-y-6">

                    <!-- Profile Card -->
                    <div class="bg-white rounded-[24px] p-8 soft-shadow relative overflow-hidden">

                        <!-- Edit Button (Top Right) -->
                        <button onclick="switchTab('settings')"
                            class="absolute top-6 right-6 text-[#007AFF] font-medium text-sm hover:bg-blue-50 px-3 py-1 rounded-full transition">
                            Edit
                        </button>

                        <!-- Avatar Section (With Edit Hint) -->
                        <div class="flex flex-col items-center mb-6 group cursor-pointer"
                            onclick="switchTab('settings')">
                            <div
                                class="w-32 h-32 rounded-full p-1 bg-gradient-to-b from-gray-100 to-gray-200 mb-4 relative shadow-inner">
                                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff' }}"
                                    alt="Profile"
                                    class="w-full h-full rounded-full object-cover border-4 border-white shadow-sm transition group-hover:opacity-90">

                                <!-- Edit Overlay (Hover) -->
                                <div
                                    class="absolute inset-0 flex items-center justify-center bg-black/20 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <i class="fa-solid fa-pen text-white text-lg drop-shadow-md"></i>
                                </div>

                                <div class="absolute bottom-2 right-2 w-6 h-6 bg-[#34C759] border-4 border-white rounded-full shadow-sm"
                                    title="Online"></div>
                            </div>
                            <h2 class="text-2xl font-bold text-[#1D1D1F] text-center">{{ $user->name }}</h2>
                            <p class="text-[#86868B] font-medium">{{ $user->position ?? ucfirst($user->role) }}</p>
                        </div>

                        <!-- Quick Info (Read Only Summary) -->
                        <div class="space-y-3 border-t border-gray-100 pt-6">
                            <!-- Added Fields -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#86868B]">Role</span>
                                <span class="text-sm font-semibold text-[#1D1D1F]">{{ ucfirst($user->role) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#86868B]">Email</span>
                                <span class="text-sm font-semibold text-[#1D1D1F] truncate max-w-[150px]"
                                    title="{{ $user->email }}">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#86868B]">Phone</span>
                                <span
                                    class="text-sm font-semibold text-[#1D1D1F]">{{ $user->phone_number ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#86868B]">Gender</span>
                                <span
                                    class="text-sm font-semibold text-[#1D1D1F]">{{ ucfirst($user->gender) ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#86868B]">Birthday</span>
                                <span
                                    class="text-sm font-semibold text-[#1D1D1F]">{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('M d, Y') : '-' }}</span>
                            </div>

                            <!-- Existing Fields -->
                            <div
                                class="flex items-center justify-between pt-2 mt-2 border-t border-dashed border-gray-100">
                                <span class="text-sm text-[#86868B]">Employee ID</span>
                                <span
                                    class="text-sm font-semibold text-[#1D1D1F]">{{ $user->employee_id ?? '-' }}</span>
                            </div>
                            @if ($user->role === 'pharmacist')
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-[#86868B]">License</span>
                                    <span
                                        class="text-sm font-semibold text-[#007AFF]">{{ $user->pharmacist_license_id ?? '-' }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[#86868B]">Branch</span>
                                <span class="text-sm font-semibold text-[#1D1D1F]">Chiang Mai</span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-6 bg-[#F2F2F7] p-3 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#34C759]"></div>
                                <span class="text-sm font-medium text-[#1D1D1F]">Account Active</span>
                            </div>
                            <i class="fa-solid fa-shield-check text-[#34C759]"></i>
                        </div>
                    </div>

                    <!-- Contact Support Card -->
                    <div
                        class="bg-gradient-to-br from-[#007AFF] to-[#005ECB] rounded-[24px] p-6 soft-shadow text-white relative overflow-hidden">
                        <i class="fa-solid fa-headset absolute -bottom-4 -right-4 text-8xl text-white opacity-10"></i>
                        <h3 class="font-bold text-lg mb-2">Need Help?</h3>
                        <p class="text-white/80 text-sm mb-4">Contact IT Support for urgent system issues.</p>
                        <button
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-4 py-2 rounded-lg text-sm font-medium transition w-full border border-white/20">
                            Call Support
                        </button>
                    </div>

                </div>

                <!-- ==============================
                 RIGHT COLUMN: Main Content & Tabs
                 ============================== -->
                <div class="lg:col-span-8 xl:col-span-9">

                    <!-- Navigation Tabs (Segmented Control) -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px] w-full md:w-auto overflow-x-auto no-scrollbar"
                            style="border-radius: 25px">
                            <button onclick="switchTab('info')" id="btn-info"
                                class="tab-btn bg-white text-[#1D1D1F] shadow-sm px-5 py-2 rounded-[9px] segmented-control-item whitespace-nowrap transition-all"
                                style="border-radius: 25px">Overview</button>
                            <button onclick="switchTab('schedule')" id="btn-schedule"
                                class="tab-btn text-[#86868B] hover:bg-black/5 px-5 py-2 rounded-[9px] segmented-control-item whitespace-nowrap transition-all"
                                style="border-radius: 25px">Schedule</button>
                            <button onclick="switchTab('docs')" id="btn-docs"
                                class="tab-btn text-[#86868B] hover:bg-black/5 px-5 py-2 rounded-[9px] segmented-control-item whitespace-nowrap transition-all"
                                style="border-radius: 25px">Documents</button>
                            <button onclick="switchTab('activity')" id="btn-activity"
                                class="tab-btn text-[#86868B] hover:bg-black/5 px-5 py-2 rounded-[9px] segmented-control-item whitespace-nowrap transition-all"
                                style="border-radius: 25px">Activity</button>
                            <button onclick="switchTab('settings')" id="btn-settings"
                                class="tab-btn text-[#86868B] hover:bg-black/5 px-5 py-2 rounded-[9px] segmented-control-item whitespace-nowrap transition-all flex items-center gap-2"
                                style="border-radius: 25px">
                                <i class="fa-solid fa-gear text-xs"></i> Settings
                            </button>
                        </div>
                    </div>

                    <!-- TAB 1: OVERVIEW (Professional Info) -->
                    <div id="content-info" class="tab-content space-y-8">
                        <!-- Section A: Key Status Cards -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Professional Overview
                                </h3>
                                <button
                                    class="text-[#007AFF] text-sm font-medium hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-download"></i> Download Resume
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <!-- License Card -->
                                @if ($user->role === 'pharmacist')
                                    <div
                                        class="bg-white rounded-[24px] p-6 soft-shadow hover:scale-[1.01] transition duration-300">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#E8F8F0] flex items-center justify-center text-[#34C759] mb-4">
                                            <i class="fa-solid fa-file-medical text-xl"></i>
                                        </div>
                                        <h4 class="text-[#86868B] text-xs font-bold uppercase tracking-wider mb-1">
                                            License
                                        </h4>
                                        <p class="text-xl font-bold text-[#1D1D1F] mb-1">No.
                                            {{ $user->pharmacist_license_id ?? 'N/A' }}</p>
                                        <div
                                            class="flex justify-between items-center mt-4 pt-4 border-t border-gray-50">
                                            <span class="text-sm font-medium text-[#34C759] flex items-center gap-1"><i
                                                    class="fa-solid fa-circle-check"></i> Valid</span>
                                            <span class="text-xs text-[#FF9500] font-medium">Exp: Jan 2025</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Employment Card -->
                                <div
                                    class="bg-white rounded-[24px] p-6 soft-shadow hover:scale-[1.01] transition duration-300">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-[#E5F1FF] flex items-center justify-center text-[#007AFF] mb-4">
                                        <i class="fa-solid fa-user-doctor text-xl"></i>
                                    </div>
                                    <h4 class="text-[#86868B] text-xs font-bold uppercase tracking-wider mb-1">
                                        Position
                                    </h4>
                                    <p class="text-xl font-bold text-[#1D1D1F] mb-1">
                                        {{ $user->position ?? ucfirst($user->role) }}</p>
                                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-50">
                                        <span
                                            class="text-xs bg-[#F2F2F7] px-2 py-1 rounded text-[#1D1D1F]">Full-time</span>
                                        <span class="text-sm font-bold text-[#1D1D1F]">฿45,000</span>
                                    </div>
                                </div>

                                <!-- Permissions Card -->
                                <div
                                    class="bg-white rounded-[24px] p-6 soft-shadow hover:scale-[1.01] transition duration-300 md:col-span-2 xl:col-span-1">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-[#FFF6E5] flex items-center justify-center text-[#FF9500] mb-4">
                                        <i class="fa-solid fa-shield-halved text-xl"></i>
                                    </div>
                                    <h4 class="text-[#86868B] text-xs font-bold uppercase tracking-wider mb-1">
                                        Access
                                        Level</h4>
                                    <p class="text-xl font-bold text-[#1D1D1F] mb-1">Level
                                        {{ $user->role === 'admin' ? '3 (Admin)' : ($user->role === 'pharmacist' ? '2 (Pharmacist)' : '1 (Staff)') }}
                                    </p>
                                    <div class="flex gap-1 mt-5">
                                        <div class="h-1.5 w-full rounded-full bg-[#007AFF]"></div>
                                        <div class="h-1.5 w-full rounded-full bg-[#007AFF]"></div>
                                        <div
                                            class="h-1.5 w-full rounded-full {{ $user->role === 'admin' ? 'bg-[#007AFF]' : 'bg-[#E5E5EA]' }}">
                                        </div>
                                        <div class="h-1.5 w-full rounded-full bg-[#E5E5EA]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section B: Performance Metrics (New) -->
                        <div>
                            <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-[#007AFF]"></i> Performance (November)
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                    <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                        Prescriptions</p>
                                    <p class="text-2xl font-bold text-[#1D1D1F]">1,240</p>
                                    <p class="text-xs text-[#34C759] font-medium mt-1 flex items-center gap-1"><i
                                            class="fa-solid fa-arrow-up"></i> 12%</p>
                                </div>
                                <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                    <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                        Consultations</p>
                                    <p class="text-2xl font-bold text-[#1D1D1F]">85</p>
                                    <p class="text-xs text-[#86868B] font-medium mt-1">Avg 15m/case</p>
                                </div>
                                <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                    <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                        Sales
                                        (Personal)</p>
                                    <p class="text-2xl font-bold text-[#1D1D1F]">฿450k</p>
                                    <p class="text-xs text-[#34C759] font-medium mt-1">On Target</p>
                                </div>
                                <div class="bg-white p-5 rounded-[20px] soft-shadow hover:shadow-md transition">
                                    <p class="text-[10px] font-bold text-[#86868B] uppercase tracking-wider mb-2">
                                        Attendance</p>
                                    <p class="text-2xl font-bold text-[#1D1D1F]">98%</p>
                                    <p class="text-xs text-[#FF9500] font-medium mt-1">1 Late</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section C: Education & CPE (New) -->
                        <div>
                            <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-[#007AFF]"></i> Education & Training
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Education -->
                                <div class="bg-white rounded-[24px] p-6 soft-shadow flex items-start gap-5">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-[#F2F2F7] flex items-center justify-center text-[#86868B] shrink-0">
                                        <i class="fa-solid fa-certificate text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#1D1D1F] text-lg">Academic Background</h4>
                                        <div class="mt-3 space-y-4">
                                            <div class="relative pl-4 border-l-2 border-gray-100">
                                                <p class="font-semibold text-[#1D1D1F] text-sm">Doctor of Pharmacy
                                                    (Pharm.D.)</p>
                                                <p class="text-xs text-[#86868B]">Chulalongkorn University • 2015
                                                </p>
                                            </div>
                                            <div class="relative pl-4 border-l-2 border-gray-100">
                                                <p class="font-semibold text-[#1D1D1F] text-sm">Board Certified
                                                    Pharmacotherapy</p>
                                                <p class="text-xs text-[#86868B]">Pharmacy Council • 2018</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CPE Credits -->
                                <div class="bg-white rounded-[24px] p-6 soft-shadow flex items-start gap-5">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-[#FFF6E5] flex items-center justify-center text-[#FF9500] shrink-0">
                                        <i class="fa-solid fa-star text-xl"></i>
                                    </div>
                                    <div class="w-full">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-bold text-[#1D1D1F] text-lg">CPE Credits (2025)</h4>
                                            <span
                                                class="text-[10px] font-bold bg-[#FFF6E5] text-[#FF9500] px-2 py-0.5 rounded-md">Required</span>
                                        </div>
                                        <div class="flex items-end gap-2 mb-3">
                                            <p class="text-3xl font-bold text-[#1D1D1F]">15.5</p>
                                            <p class="text-sm text-[#86868B] font-medium mb-1">/ 20 Credits</p>
                                        </div>
                                        <div class="w-full bg-[#F2F2F7] rounded-full h-2.5 mb-2">
                                            <div class="bg-gradient-to-r from-[#FF9500] to-[#FFCC00] h-2.5 rounded-full"
                                                style="width: 78%"></div>
                                        </div>
                                        <p class="text-[11px] text-[#86868B]">Last updated: 2 days ago via Pharmacy
                                            Council API</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- TAB 2: SCHEDULE (Static for now as no Schedule model) -->
                    <div id="content-schedule" class="tab-content hidden space-y-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Shift Schedule</h3>
                            <span
                                class="text-sm text-[#86868B] font-medium bg-white px-3 py-1 rounded-full shadow-sm">November
                                2025</span>
                        </div>
                        <!-- ... (Keep static schedule content for now) ... -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                <p class="text-xs text-[#86868B] uppercase font-bold">Total Hours</p>
                                <p class="text-xl font-bold text-[#1D1D1F]">176</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                <p class="text-xs text-[#34C759] uppercase font-bold">On Time</p>
                                <p class="text-xl font-bold text-[#34C759]">100%</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                <p class="text-xs text-[#007AFF] uppercase font-bold">Shifts</p>
                                <p class="text-xl font-bold text-[#007AFF]">22</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl soft-shadow text-center">
                                <p class="text-xs text-[#FF9500] uppercase font-bold">OT</p>
                                <p class="text-xl font-bold text-[#FF9500]">4h</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-[24px] overflow-hidden soft-shadow">
                            <div class="p-8 text-center text-gray-500">
                                <i class="fa-solid fa-calendar-days text-4xl mb-4 opacity-50"></i>
                                <p>Schedule integration coming soon.</p>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: DOCUMENTS (Static) -->
                    <div id="content-docs" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight mb-4">Documents & Contracts
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Doc 1 -->
                            <div
                                class="bg-white p-5 rounded-[20px] soft-shadow border border-transparent hover:border-[#007AFF] transition cursor-pointer group flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-[#FFECEC] flex items-center justify-center text-[#FF3B30] shrink-0">
                                    <i class="fa-solid fa-file-pdf text-xl"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="font-semibold text-[#1D1D1F] text-sm truncate">Employment
                                        Contract.pdf
                                    </h4>
                                    <p class="text-xs text-[#86868B]">2.4 MB • Signed 2020</p>
                                </div>
                                <i class="fa-solid fa-download text-[#86868B] ml-auto group-hover:text-[#007AFF]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: ACTIVITY -->
                    <div id="content-activity" class="tab-content hidden space-y-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Activity Log</h3>
                            <a href="{{ route('profile.logs.export') }}"
                                class="text-[#007AFF] text-sm font-medium hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-download"></i> Export Log
                            </a>
                        </div>
                        <div class="bg-white rounded-[24px] p-6 soft-shadow">
                            <div class="space-y-0">
                                @if (isset($logs) && $logs->count() > 0)
                                    @foreach ($logs as $log)
                                        <div class="flex gap-4 relative pb-8 timeline-item">
                                            <div
                                                class="relative z-10 w-10 h-10 rounded-full bg-[#E5F1FF] flex items-center justify-center border-4 border-white shadow-sm text-[#007AFF] shrink-0">
                                                @if (str_contains(strtolower($log->action), 'login'))
                                                    <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                                                @elseif(str_contains(strtolower($log->action), 'update'))
                                                    <i class="fa-solid fa-pen text-sm"></i>
                                                @elseif(str_contains(strtolower($log->action), 'password'))
                                                    <i class="fa-solid fa-key text-sm"></i>
                                                @else
                                                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                                                @endif
                                            </div>
                                            <div class="pt-1 w-full">
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center sm:gap-2 mb-1 justify-between">
                                                    <h4 class="font-semibold text-[#1D1D1F] text-sm">
                                                        {{ $log->action }}</h4>
                                                    <span
                                                        class="text-[10px] text-[#86868B] bg-[#F2F2F7] px-2 py-0.5 rounded-md w-fit">{{ $log->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-[#86868B] text-xs">{{ $log->description }}</p>
                                                <p class="text-[10px] text-[#86868B] mt-1">IP:
                                                    {{ $log->ip_address }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-[#86868B]">
                                        <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-20"></i>
                                        <p>No activity recorded yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Pagination -->
                            @if (isset($logs) && $logs->hasPages())
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    {{ $logs->links('vendor.pagination.apple') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- TAB 5: ACCOUNT SETTINGS (Merged from Version B) -->
                    <div id="content-settings" class="tab-content hidden space-y-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight">Account Settings</h3>
                            <button onclick="document.getElementById('profile-update-form').submit()"
                                class="bg-[#007AFF] text-white px-5 py-2 rounded-full font-medium text-sm shadow-md hover:bg-[#005ECB] transition">
                                <i class="fa-solid fa-check mr-1"></i> Save Changes
                            </button>
                        </div>

                        <!-- SECTION 1: EDIT PROFILE -->
                        <form id="profile-update-form" method="POST" action="{{ route('profile.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="bg-white rounded-[24px] p-8 soft-shadow">
                                <h2 class="text-lg font-bold text-[#1D1D1F] mb-6 flex items-center gap-2">
                                    <i class="fa-regular fa-id-card text-[#007AFF]"></i> Edit Personal Details
                                </h2>

                                <!-- Avatar Edit Section -->
                                <div class="flex items-center gap-6 mb-8 pb-8 border-b border-gray-100">
                                    <div class="relative group cursor-pointer"
                                        onclick="document.getElementById('profile_photo_input').click()">
                                        <div
                                            class="w-20 h-20 rounded-full p-1 bg-gradient-to-b from-gray-100 to-gray-200 relative shadow-inner">
                                            <img id="profile-preview"
                                                src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff' }}"
                                                alt="Current Profile"
                                                class="w-full h-full rounded-full object-cover border-2 border-white shadow-sm">
                                        </div>
                                        <div
                                            class="absolute bottom-0 right-0 w-6 h-6 bg-[#007AFF] rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-camera text-white text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-[#1D1D1F] mb-1">Profile Photo</h4>
                                        <div class="flex gap-3">
                                            <button type="button"
                                                onclick="document.getElementById('profile_photo_input').click()"
                                                class="text-xs bg-white border border-gray-300 px-3 py-1.5 rounded-lg font-medium text-[#1D1D1F] hover:bg-gray-50 transition shadow-sm">Upload
                                                New</button>
                                            <input type="file" id="profile_photo_input" name="profile_photo"
                                                class="hidden" accept="image/*" onchange="previewImage(event)">
                                        </div>
                                        <p class="text-[11px] text-[#86868B] mt-2">Recommended 300x300 px. JPG,
                                            PNG.
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-5">
                                    <!-- Split Name: First & Last -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="group">
                                            <label
                                                class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">First
                                                Name <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input type="text" name="first_name"
                                                    value="{{ old('first_name', $user->first_name) }}"
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <label
                                                class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Last
                                                Name <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input type="text" name="last_name"
                                                    value="{{ old('last_name', $user->last_name) }}"
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email & Phone -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label
                                                class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Email
                                                <span class="text-red-500">*</span></label>
                                            <input type="email" name="email"
                                                value="{{ old('email', $user->email) }}"
                                                class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                                required>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Phone</label>
                                            <input type="text" name="phone_number"
                                                value="{{ old('phone_number', $user->phone_number) }}"
                                                class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                        </div>
                                    </div>

                                    <!-- Gender & Birthday -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="group">
                                            <label
                                                class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Gender</label>
                                            <div class="relative">
                                                <select name="gender"
                                                    class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                                    <option value="male"
                                                        {{ $user->gender == 'male' ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="female"
                                                        {{ $user->gender == 'female' ? 'selected' : '' }}>Female
                                                    </option>
                                                    <option value="other"
                                                        {{ $user->gender == 'other' ? 'selected' : '' }}>Other
                                                    </option>
                                                </select>
                                                <i
                                                    class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#86868B] text-xs pointer-events-none"></i>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <label
                                                class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Birthday</label>
                                            <input type="date" name="birthdate"
                                                value="{{ old('birthdate', $user->birthdate) }}"
                                                class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                        </div>
                                    </div>

                                    <!-- Role (Read-only) -->
                                    <div class="group">
                                        <label
                                            class="block text-[13px] font-semibold text-[#86868B] mb-1.5 ml-1 flex items-center gap-1">
                                            Role <i class="fa-solid fa-lock text-[10px]"></i>
                                        </label>
                                        <input type="text"
                                            value="{{ ucfirst($user->role) }} - {{ $user->position }}" readonly
                                            class="w-full apple-input-readonly rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                        <p class="text-[11px] text-[#86868B] mt-1 ml-1">Contact Admin to update
                                            Role.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </form>

                        <!-- SECTION 2: SECURITY -->
                        <div class="bg-white rounded-[24px] p-8 soft-shadow">
                            <h2 class="text-lg font-bold text-[#1D1D1F] mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-[#34C759]"></i> Security
                            </h2>

                            <!-- Password -->
                            <form method="post" action="{{ route('password.update') }}" class="mb-8">
                                @csrf
                                @method('put')
                                <h3 class="text-[14px] font-semibold text-[#1D1D1F] mb-4">Update Password</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <input type="password" name="current_password" placeholder="Current Password"
                                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]">
                                        @error('current_password', 'updatePassword')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="password" name="password" placeholder="New Password"
                                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]">
                                        @error('password', 'updatePassword')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="password" name="password_confirmation"
                                            placeholder="Confirm Password"
                                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]">
                                    </div>
                                </div>
                                <div class="mt-4 text-right">
                                    <button type="submit"
                                        class="text-sm font-medium text-[#007AFF] hover:underline">Update
                                        Password</button>
                                </div>
                            </form>

                            <!-- 2FA Switch -->
                            <div class="flex items-center justify-between py-4 border-t border-gray-100">
                                <div>
                                    <h3 class="text-[14px] font-semibold text-[#1D1D1F]">Two-Factor Authentication
                                        (2FA)</h3>
                                    <p class="text-[12px] text-[#86868B]">Require verification code upon login.</p>
                                </div>
                                <label class="apple-switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <!-- Sessions -->
                            <div class="flex items-center justify-between py-4 border-t border-gray-100">
                                <div>
                                    <h3 class="text-[14px] font-semibold text-[#1D1D1F]">Active Sessions</h3>
                                    <p class="text-[12px] text-[#86868B]">Log out from all other devices.</p>
                                </div>
                                <button
                                    class="bg-[#F2F2F7] text-[#FF3B30] border border-gray-200 px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#FFE5E5] transition">
                                    Log Out All
                                </button>
                            </div>
                        </div>

                        <!-- SECTION 3: PREFERENCES -->
                        <div class="bg-white rounded-[24px] p-8 soft-shadow">
                            <h2 class="text-lg font-bold text-[#1D1D1F] mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-[#FF9500]"></i> Preferences
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-2">Language</label>
                                    <select name="language"
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                        <option value="th" {{ $user->language == 'th' ? 'selected' : '' }}>
                                            Thai
                                            (ภาษาไทย)</option>
                                        <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>
                                            English
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-2">Theme</label>

                                    <div class="bg-[#F2F2F7] p-1 rounded-xl flex">
                                        <button
                                            class="flex-1 bg-white shadow-sm rounded-lg py-2 text-sm font-medium text-[#1D1D1F] flex items-center justify-center gap-2"><i
                                                class="fa-regular fa-sun"></i> Light</button>
                                        <button
                                            class="flex-1 text-[#86868B] py-2 text-sm font-medium flex items-center justify-center gap-2 hover:bg-black/5 rounded-lg"><i
                                                class="fa-regular fa-moon"></i> Dark</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: DELETE ACCOUNT (DANGER ZONE) -->
                        <div class="bg-white rounded-[24px] p-8 soft-shadow border border-red-100">
                            <h2 class="text-lg font-bold text-[#FF3B30] mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                            </h2>
                            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                                <div>
                                    <h3 class="text-[14px] font-semibold text-[#1D1D1F]">Delete Account</h3>
                                    <p class="text-[12px] text-[#86868B] mt-1 max-w-md">Once you delete your
                                        account,
                                        there is no going back. Please be certain.</p>
                                </div>
                                <button
                                    onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                                    class="bg-[#FFF5F5] text-[#FF3B30] border border-red-200 px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-[#FFE5E5] transition whitespace-nowrap">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Delete Account Modal -->
        <div id="delete-account-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
                <h3 class="text-xl font-bold text-[#1D1D1F] mb-4">Delete Account</h3>
                <p class="text-sm text-[#86868B] mb-6">Are you sure you want to delete your account? This action
                    cannot
                    be undone. Please enter your password to confirm.</p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="mb-6">
                        <input type="password" name="password" placeholder="Password"
                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F]" required>
                        @error('password', 'userDeletion')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                            onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg text-sm font-medium text-[#1D1D1F] hover:bg-gray-100 transition">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium bg-[#FF3B30] text-white hover:bg-[#D70015] transition">Delete
                            Account</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- JavaScript for Tab Switching & Notifications -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script>
            function switchTab(tabId) {
                // Hide all contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                // Show selected content
                document.getElementById('content-' + tabId).classList.remove('hidden');

                // Reset buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('bg-white', 'shadow-sm', 'text-[#1D1D1F]');
                    btn.classList.add('text-[#86868B]', 'hover:bg-black/5');
                });
                // Highlight active button
                const activeBtn = document.getElementById('btn-' + tabId);
                activeBtn.classList.remove('text-[#86868B]', 'hover:bg-black/5');
                activeBtn.classList.add('bg-white', 'shadow-sm', 'text-[#1D1D1F]');
            }

            // Initialize with 'info' tab active
            window.addEventListener('DOMContentLoaded', () => {
                // Check if there are errors in specific bags to open relevant tabs
                @if ($errors->hasBag('userDeletion'))
                    switchTab('settings');
                    document.getElementById('delete-account-modal').classList.remove('hidden');
                @elseif ($errors->hasBag('updatePassword') || $errors->any())
                    switchTab('settings');
                @else
                    switchTab('info');
                @endif

                // Success Notifications
                @if (session('status') === 'profile-updated')
                    showFlash('Profile updated successfully!', 'success');
                @elseif (session('status') === 'password-updated')
                    showFlash('Password updated successfully!', 'success');
                @endif
            });

            // --- Flash Message Logic ---
            function showFlash(message, type = 'success') {
                const flash = document.getElementById('flash-message');
                const text = document.getElementById('flash-text');
                const icon = flash.querySelector('i');

                text.textContent = message;
                flash.className = 'flash-message show ' + type;

                if (type === 'success') {
                    icon.className = 'fa-solid fa-check-circle';
                } else {
                    icon.className = 'fa-solid fa-circle-exclamation';
                }

                setTimeout(() => {
                    flash.classList.remove('show');
                }, 3000);
            }

            // --- Image Preview Logic ---
            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('profile-preview');
                    output.src = reader.result;
                };
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }
        </script>
    </body>

    </html>
</x-app-layout>

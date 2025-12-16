<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Patient Management - Pharmacy ERP</title>

        <!-- Import Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Import Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Import FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../../css/inventorys.css">

        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Sarabun", sans-serif;
                background-color: #F5F5F7;
                color: #1D1D1F;
                -webkit-font-smoothing: antialiased;
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 0px;
                background: transparent;
            }

            /* Apple Switch */
            .apple-switch {
                position: relative;
                display: inline-block;
                width: 42px;
                height: 24px;
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
                height: 20px;
                width: 20px;
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
                transform: translateX(18px);
            }

            /* Soft Shadow */
            .soft-shadow {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            }

            /* Segmented Control */
            .view-toggle-active {
                background-color: #007AFF;
                color: white;
                box-shadow: 0 2px 8px rgba(0, 122, 255, 0.25);
            }

            .view-toggle-inactive {
                color: #86868B;
                background-color: transparent;
            }

            .view-toggle-inactive:hover {
                color: #1D1D1F;
            }

            /* List Row Hover */
            .list-row {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .list-row:hover {
                transform: scale(1.002);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
                z-index: 10;
                background-color: #FAFAFC;
            }

            /* Apple Inputs */
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

            .apple-input-readonly {
                background-color: #E5E5EA;
                color: #86868B;
                cursor: not-allowed;
                border: none;
            }

            /* Animations */
            .fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Membership Badges */
            .badge-gold {
                background: linear-gradient(135deg, #FFF7E6 0%, #FFF2CC 100%);
                color: #B45309;
                border: 1px solid #FEF3C7;
            }

            .badge-silver {
                background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
                color: #4B5563;
                border: 1px solid #E5E7EB;
            }

            .badge-platinum {
                background: linear-gradient(135deg, #E0F2FE 0%, #BAE6FD 100%);
                color: #0369A1;
                border: 1px solid #BAE6FD;
            }

            /* Modal Specific */
            .modal-open {
                overflow: hidden;
            }

            .modal-panel {
                transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
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

    <body class="min-h-screen p-6 md:p-10">



        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message">
            <i class="fa-solid fa-check-circle"></i>
            <span id="flash-text">Operation successful</span>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ session('success') }}", 'success');
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFlash("{{ $errors->first() }}", 'error');
                });
            </script>
        @endif

        <div class="os-container">

            <!-- WRAPPER: PATIENT LIST PAGE -->
            <div id="page-patient-list" class="fade-in">

                <!-- Header -->
                <header class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">

                    <div class="w-full md:w-auto">
                        <p class="sr-breadcrumb text-sm mb-2 text-[#86868B]">
                            Dashboard / People / <span style="color: #3a3a3c; font-weight: 600;">Profile</span>
                        </p>
                        <h1 class="text-[32px] font-bold tracking-tight text-[#1D1D1F]">
                            Patients <span
                                class="text-[#86868B] font-medium text-2xl ml-1">({{ number_format($patients->total()) }})</span>
                        </h1>
                    </div>

                    <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">

                        <!-- Filter -->
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px]"
                            style="border-radius: 25px">
                            <button onclick="filterList('all')" id="filter-all"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 {{ request('type') == 'all' || !request('type') ? 'view-toggle-active' : 'view-toggle-inactive' }}"
                                style="border-radius: 25px">All</button>
                            <button onclick="filterList('vip')" id="filter-vip"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 {{ request('type') == 'vip' ? 'view-toggle-active' : 'view-toggle-inactive' }}"
                                style="border-radius: 25px">VIP</button>
                            <button onclick="filterList('chronic')" id="filter-chronic"
                                class="px-5 py-1.5 rounded-[9px] transition-all duration-200 {{ request('type') == 'chronic' ? 'view-toggle-active' : 'view-toggle-inactive' }}"
                                style="border-radius: 25px">Chronic</button>
                        </div>

                        <!-- View Toggle -->
                        <div class="bg-[#E5E5EA] p-1 rounded-xl flex font-medium text-[13px]"
                            style="border-radius: 25px">
                            <button onclick="switchView('list')" id="toggle-list"
                                class="px-6 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-active"
                                style="border-radius: 25px">List</button>
                            <button onclick="switchView('activity')" id="toggle-activity"
                                class="px-6 py-1.5 rounded-[9px] transition-all duration-200 view-toggle-inactive"
                                style="border-radius: 25px">Activity</button>
                        </div>

                        <!-- Add Button -->
                        <button onclick="openAddPatientModal()"
                            class="bg-[#007AFF] text-white px-5 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-blue-500/20 hover:bg-[#005ECB] transition flex items-center gap-2"
                            style="border-radius: 25px">
                            <i class="fa-solid fa-user-plus"></i> New Patient
                        </button>
                    </div>
                </header>
                <!-- Controls Row (Search & Bulk Actions) -->
                <div class="inv-filters-wrapper">
                    <!-- Search & Filter Form -->
                    <form method="GET" action="{{ route('peoples.patients-customer') }}" class="inv-search-form"
                        style="display: flex; gap: 10px; align-items: center;">

                        <!-- Sort Filter -->
                        <select name="sort" class="inv-form-input"
                            style="width: 180px; height: 44px; cursor: pointer;" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added
                            </option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added
                            </option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name
                                (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name
                                (Z-A)
                            </option>
                        </select>

                        <!-- Membership Filter -->
                        <select name="membership_tier" class="inv-form-input"
                            style="width: 220px; height: 44px; cursor: pointer;" onchange="this.form.submit()">
                            <option value="all" {{ request('membership_tier') == 'all' ? 'selected' : '' }}>All
                                Tiers</option>
                            <option value="Standard" {{ request('membership_tier') == 'Standard' ? 'selected' : '' }}>
                                Standard</option>
                            <option value="Silver" {{ request('membership_tier') == 'Silver' ? 'selected' : '' }}>
                                Silver</option>
                            <option value="Gold" {{ request('membership_tier') == 'Gold' ? 'selected' : '' }}>Gold
                            </option>
                            <option value="Platinum" {{ request('membership_tier') == 'Platinum' ? 'selected' : '' }}>
                                Platinum</option>
                        </select>

                        <!-- Search Input -->
                        <div style="position: relative;">
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                                placeholder="Search Name, SKU..." class="inv-form-input"
                                style="width: 280px; height: 44px; padding-left: 40px;">
                            <i class="fa-solid fa-magnifying-glass"
                                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem;"></i>
                        </div>
                    </form>

                    <!-- Bulk Actions (Hidden by default) -->
                    <div id="bulk-actions" style="display: none; margin-left: auto; gap: 8px;">
                        <span class="inv-text-sub" style="margin-right: 8px;">Selected: <span
                                id="selected-count">0</span></span>
                        <button class="inv-btn-secondary" style="font-size: 0.8rem; color: #ff3b30;"
                            onclick="confirmBulkDelete()"><i class="fa-solid fa-trash"></i> Delete Selected</button>
                    </div>
                </div>

                <!-- VIEW 1: LIST VIEW (DIV/BOX FORMAT) -->
                <div id="view-list" class="transition-opacity duration-300">
                    <!-- Table Header -->
                    <div class="inv-card-row header grid-products"
                        style="grid-template-columns: 40px 50px 60px 3fr 1fr 1.5fr 1.5fr 2fr 1fr 130px; 
                       padding: 0 16px; 
                       margin-bottom: 10px; 
                       background: transparent; 
                       border: none;">
                        <div class="inv-checkbox-wrapper">
                            <input type="checkbox" class="inv-checkbox" id="select-all">
                        </div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">#
                        </div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Image
                        </div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Patient Name</div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Gender
                        </div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Phone
                        </div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Membership</div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Conditions</div>
                        <div class="inv-col-header"
                            style="font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Points</div>
                        <div class="inv-col-header"
                            style="text-align: right; font-size: 12px; font-weight: 600; color: #86868b; text-transform: uppercase;">
                            Actions</div>
                    </div>

                    <!-- List Container (Dynamic Loop) -->
                    <div id="list-container">
                        @forelse($patients as $index => $patient)
                            <div class="inv-card-row grid-products product-row"
                                style="grid-template-columns: 40px 50px 60px 3fr 1fr 1.5fr 1.5fr 2fr 1fr 130px; 
                               background: #fff; 
                               border-radius: 22px; 
                               margin-bottom: 8px; 
                               padding: 16px 16px;
                               box-shadow: 0 2px 6px rgba(0,0,0,0.02);
                               border: 1px solid #f5f5f7;
                               transition: all 0.2s ease;"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.02)'">

                                <div class="inv-checkbox-wrapper">
                                    <input type="checkbox" class="inv-checkbox item-checkbox"
                                        data-id="{{ $patient->id }}">
                                </div>

                                <div class="inv-text-sub" style="font-weight: 500; font-size: 13px; color: #8e8e93;">
                                    {{ ($patients->currentPage() - 1) * $patients->perPage() + $loop->iteration }}
                                </div>

                                <!-- Image Column -->
                                <div class="inv-product-image"
                                    style="display: flex; align-items: center; justify-content: center;">
                                    @if ($patient->gender === 'Male')
                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background-color: #f0f9ff; display: flex; align-items: center; justify-content: center; color: #007aff; border: 1px solid #e0f2fe;">
                                            <i class="fa-solid fa-person" style="font-size: 18px;"></i>
                                        </div>
                                    @elseif($patient->gender === 'Female')
                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff0f5; display: flex; align-items: center; justify-content: center; color: #ff2d55; border: 1px solid #ffe4e6;">
                                            <i class="fa-solid fa-person-dress" style="font-size: 18px;"></i>
                                        </div>
                                    @else
                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background-color: #f2f2f7; display: flex; align-items: center; justify-content: center; color: #8e8e93; border: 1px solid #e5e5ea;">
                                            <i class="fa-solid fa-user" style="font-size: 16px;"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="inv-product-info" data-label="Patient Name">
                                    <div class="inv-product-details">
                                        <div class="inv-product-name"
                                            style="font-size: 14px; font-weight: 600; color: #1d1d1f;">
                                            {{ $patient->first_name }} {{ $patient->last_name }}</div>
                                        <div class="inv-product-generic" style="font-size: 12px; color: #86868b;">
                                            {{ $patient->hn_number }}</div>
                                    </div>
                                </div>

                                <div class="inv-text-sub" data-label="Gender"
                                    style="font-size: 13px; color: #424245;">
                                    {{ $patient->gender }} <span style="color: #8e8e93;">/
                                        {{ $patient->birthdate ? \Carbon\Carbon::parse($patient->birthdate)->age : '-' }}Y</span>
                                </div>

                                <div class="inv-text-sub" data-label="Phone"
                                    style="font-size: 13px; color: #86868b;">
                                    {{ $patient->phone ?? '-' }}
                                </div>

                                <div class="inv-text-main" data-label="Membership">
                                    @php
                                        $tierClass = match ($patient->membership_tier) {
                                            'Gold' => 'badge-gold',
                                            'Platinum' => 'badge-platinum',
                                            'Silver' => 'badge-silver',
                                            default => 'badge-silver',
                                        };
                                    @endphp
                                    <span class="{{ $tierClass }}"
                                        style="font-size: 11px; padding: 2px 8px; border-radius: 12px; font-weight: 600;">
                                        {{ $patient->membership_tier ?? 'Standard' }}
                                    </span>
                                </div>

                                <div class="inv-text-sub" data-label="Conditions"
                                    style="font-size: 12px; color: #1d1d1f;">
                                    @if ($patient->chronic_diseases && count($patient->chronic_diseases) > 0)
                                        @foreach ($patient->chronic_diseases as $disease)
                                            <span
                                                style="background: #fff5f5; color: #ff3b30; padding: 2px 6px; border-radius: 4px; border: 1px solid #ffdcdc; margin-right: 4px; display: inline-block; margin-bottom: 2px;">{{ $disease }}</span>
                                        @endforeach
                                    @else
                                        <span style="color: #86868b; font-style: italic;">-</span>
                                    @endif
                                </div>

                                <div class="inv-text-sub" data-label="Points"
                                    style="font-size: 13px; color: #1d1d1f; font-weight: 600;">
                                    {{ number_format($patient->points ?? 0) }} pts
                                </div>

                                <div class="inv-action-group" data-label="Actions"
                                    style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                                    <button class="inv-icon-action"
                                        onclick="navigateToProfile({{ json_encode($patient) }})"
                                        style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#007aff'"
                                        onmouseout="this.style.color='#86868b'">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="inv-icon-action"
                                        onclick="openEditModal({{ json_encode($patient) }})"
                                        style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#ff9500'"
                                        onmouseout="this.style.color='#86868b'">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="inv-icon-action btn-delete-row"
                                        onclick="confirmDelete({{ $patient->id }})"
                                        style="color: #86868b; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                                        onmouseover="this.style.color='#ff3b30'"
                                        onmouseout="this.style.color='#86868b'">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="inv-card-row"
                                style="justify-content: center; padding: 40px; background: #fff; border-radius: 12px; margin-bottom: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                                <div style="text-align: center;">
                                    <i class="fa-solid fa-users"
                                        style="font-size: 48px; color: #e5e5ea; margin-bottom: 16px;"></i>
                                    <div class="inv-text-sub" style="font-size: 16px;">No patients found</div>
                                </div>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        {{ $patients->onEachSide(1)->links('vendor.pagination.apple') }}
                    </div>
                </div>
            </div>

            <!-- ==========================================
             VIEW 3: PATIENT PROFILE PAGE (Hidden)
             ========================================== -->
            <div id="page-patient-profile" class="hidden fade-in">

                <!-- Breadcrumb -->
                <div class="mb-6 flex items-center gap-2 text-sm">
                    <button onclick="navigateBackToList()"
                        class="flex items-center gap-1 text-[#86868B] hover:text-[#1D1D1F] transition">
                        <i class="fa-solid fa-arrow-left"></i> Patients
                    </button>
                    <span class="text-[#86868B]">/</span>
                    <span class="font-semibold text-[#1D1D1F]" id="p-header-name"></span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- LEFT COLUMN: Identity Card -->
                    <div class="lg:col-span-4 xl:col-span-3">
                        <div class="bg-white rounded-[24px] p-8 soft-shadow relative text-center">

                            <!-- Edit Button -->
                            <button id="btn-profile-edit"
                                class="absolute top-6 right-6 text-[#007AFF] font-bold text-sm hover:underline">Edit</button>

                            <!-- Avatar Icon -->
                            <div class="relative inline-block mb-4 mt-2">
                                <div class="w-28 h-28 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500"
                                    id="p-avatar-container">
                                    <i class="fa-solid fa-person text-6xl" id="p-avatar-icon"></i>
                                </div>
                            </div>

                            <!-- Name & Member ID -->
                            <h2 class="text-2xl font-bold text-[#1D1D1F] mb-1" id="p-name">Mr. Somchai Meesuk
                            </h2>
                            <div class="flex items-center justify-center gap-2 mb-6">
                                <span class="text-[#86868B] text-sm font-mono tracking-wide"
                                    id="p-id">MB-2023-889</span>
                                <span class="badge-gold px-2 py-0.5 rounded text-[10px] font-bold uppercase"
                                    id="p-tier">Gold Member</span>
                            </div>

                            <div class="w-full h-px bg-gray-100 mb-6"></div>

                            <!-- Details List -->
                            <div class="space-y-4 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="texçt-[#86868B]">E-mail / IDLine</span>
                                    <span class="font-medium text-[#1D1D1F]" id="p-email">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Phone</span>
                                    <span class="font-medium text-[#1D1D1F]" id="p-phone">081-999-8888</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Age</span>
                                    <span class="font-medium text-[#1D1D1F]" id="p-age">45 Years</span>
                                </div>
                                <div class="flex justify-between items-center">ßß
                                    <span class="text-[#86868B]">Gender</span>
                                    <span class="font-medium text-[#1D1D1F]" id="p-gender">Male</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Blood Group</span>
                                    <span class="font-medium text-[#1D1D1F]" id="p-blood-group">-</span>
                                </div>
                                <div
                                    class="flex justify-between items-center border-t border-dotted border-gray-200 pt-4 mt-2">
                                    <span class="text-[#86868B]">Last Visit</span>
                                    <span class="font-bold text-[#1D1D1F]">Yesterday</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[#86868B]">Points</span>
                                    <span class="font-bold text-[#007AFF]" id="p-points-display">0 pts</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Tabs & Content -->
                    <div class="lg:col-span-8 xl:col-span-9">

                        <!-- Tabs -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div
                                class="bg-[#E5E5EA] p-1 rounded-full flex font-medium text-[13px] overflow-x-auto no-scrollbar">
                                <button onclick="switchTab('overview')" id="tab-overview"
                                    class="px-6 py-2 rounded-full bg-white text-[#1D1D1F] shadow-sm transition-all">Overview</button>
                                <button onclick="switchTab('history')" id="tab-history"
                                    class="px-6 py-2 rounded-full text-[#86868B] hover:bg-black/5 transition-all">History</button>
                                <button onclick="switchTab('settings')" id="tab-settings"
                                    class="px-6 py-2 rounded-full text-[#86868B] hover:bg-black/5 transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-gear text-xs"></i> Settings
                                </button>
                            </div>
                        </div>

                        <!-- TAB 1: OVERVIEW -->
                        <div id="content-overview" class="space-y-8">

                            <!-- 1. Medical Alerts (High Priority) -->
                            <div>
                                <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-notes-medical text-[#FF3B30]"></i> Medical Alerts
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white p-6 rounded-[24px] soft-shadow border-l-4 border-[#FF3B30]">
                                        <p class="text-xs text-[#FF3B30] font-bold uppercase tracking-wider mb-2">
                                            DRUG
                                            ALLERGIES</p>
                                        <div class="flex flex-wrap gap-2" id="p-allergies-list">
                                            <!-- Dynamic Content -->
                                        </div>
                                    </div>
                                    <div class="bg-white p-6 rounded-[24px] soft-shadow border-l-4 border-[#F59E0B]">
                                        <p class="text-xs text-[#F59E0B] font-bold uppercase tracking-wider mb-2">
                                            CHRONIC CONDITIONS</p>
                                        <div class="flex flex-wrap gap-2" id="p-conditions-list">
                                            <!-- Dynamic Content -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Health Vitals (Mockup) -->
                            <div>
                                <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-heart-pulse text-[#007AFF]"></i> Recent Vitals
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow text-center">
                                        <p class="text-[10px] text-[#86868B] uppercase font-bold">Blood Pressure
                                        </p>
                                        <p class="text-xl font-bold text-[#1D1D1F] mt-1">135/85</p>
                                        <p class="text-[10px] text-[#F59E0B] mt-1">Slightly High</p>
                                    </div>
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow text-center">
                                        <p class="text-[10px] text-[#86868B] uppercase font-bold">Weight</p>
                                        <p class="text-xl font-bold text-[#1D1D1F] mt-1">72 kg</p>
                                    </div>
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow text-center">
                                        <p class="text-[10px] text-[#86868B] uppercase font-bold">BMI</p>
                                        <p class="text-xl font-bold text-[#1D1D1F] mt-1">24.5</p>
                                        <p class="text-[10px] text-[#34C759] mt-1">Normal</p>
                                    </div>
                                    <div class="bg-white p-5 rounded-[20px] soft-shadow text-center">
                                        <p class="text-[10px] text-[#86868B] uppercase font-bold">Last Check</p>
                                        <p class="text-sm font-bold text-[#1D1D1F] mt-2">Oct 24</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Frequent Purchases -->
                            <div>
                                <h4 class="font-bold text-[#1D1D1F] text-lg mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-bag-shopping text-[#34C759]"></i> Frequent Medications
                                </h4>
                                <div class="bg-white rounded-[24px] soft-shadow overflow-hidden">
                                    <table class="w-full text-left text-sm">
                                        <thead
                                            class="bg-gray-50 border-b border-gray-100 text-xs text-[#86868B] uppercase">
                                            <tr>
                                                <th class="px-6 py-3">Item Name</th>
                                                <th class="px-6 py-3">Category</th>
                                                <th class="px-6 py-3 text-right">Avg. Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            <tr>
                                                <td class="px-6 py-3 font-medium text-[#1D1D1F]">Amlodipine 5mg
                                                </td>
                                                <td class="px-6 py-3 text-[#86868B]">Cardiovascular</td>
                                                <td class="px-6 py-3 text-right">30 Tabs</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3 font-medium text-[#1D1D1F]">Atorvastatin 40mg
                                                </td>
                                                <td class="px-6 py-3 text-[#86868B]">Lipid Lowering</td>
                                                <td class="px-6 py-3 text-right">30 Tabs</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3 font-medium text-[#1D1D1F]">Vitamin B Complex
                                                </td>
                                                <td class="px-6 py-3 text-[#86868B]">Supplements</td>
                                                <td class="px-6 py-3 text-right">1 Bottle</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <!-- TAB 2: HISTORY (Timeline) -->
                        <div id="content-history" class="hidden space-y-6">
                            <h3 class="font-bold text-[#1D1D1F] text-2xl tracking-tight mb-4">Treatment & Purchase
                                History</h3>
                            <div class="bg-white rounded-[24px] p-8 soft-shadow">
                                <div class="relative pl-6 border-l-2 border-gray-100 space-y-8">

                                    <!-- Timeline Item -->
                                    <div class="relative">
                                        <div
                                            class="absolute -left-[31px] bg-[#34C759] w-4 h-4 rounded-full border-2 border-white shadow-sm mt-1">
                                        </div>
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="text-sm font-bold text-[#1D1D1F]">Purchase #INV-9921</p>
                                                <p class="text-xs text-[#86868B]">Yesterday • Pharmacist Evan</p>
                                            </div>
                                            <span class="text-sm font-bold text-[#1D1D1F]">฿1,250</span>
                                        </div>
                                        <div class="bg-[#F5F5F7] p-3 rounded-xl text-xs text-[#1D1D1F]">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Amlodipine 5mg (30s) x 3</li>
                                                <li>Atorvastatin 40mg (30s) x 1</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Timeline Item -->
                                    <div class="relative">
                                        <div
                                            class="absolute -left-[31px] bg-[#007AFF] w-4 h-4 rounded-full border-2 border-white shadow-sm mt-1">
                                        </div>
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="text-sm font-bold text-[#1D1D1F]">Consultation: Blood
                                                    Pressure Check</p>
                                                <p class="text-xs text-[#86868B]">Oct 24, 2025 • Dr. Prasert</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-[#86868B]">
                                            BP 135/85. Advised to reduce sodium intake and continue current
                                            medication.
                                            Scheduled follow-up in 1 month.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: SETTINGS -->
                        <div id="content-settings" class="hidden space-y-6">
                            <div class="bg-white rounded-[24px] p-8 soft-shadow">

                                <!-- Form Row 1 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">First
                                            Name</label>
                                        <input type="text" id="setting-first-name"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Last
                                            Name</label>
                                        <input type="text" id="setting-last-name"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                </div>

                                <!-- Form Row 2 -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Phone</label>
                                        <input type="text" id="setting-phone"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Birthday</label>
                                        <input type="date" id="setting-birthdate"
                                            class="w-full apple-input rounded-lg px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                    </div>
                                </div>

                                <!-- Medical Info Edit -->
                                <div class="mb-6">
                                    <label class="block text-[13px] font-bold text-[#1D1D1F] mb-2">Allergies (Comma
                                        separated)</label>
                                    <input type="text" id="setting-allergies"
                                        class="w-full bg-red-50 border border-red-100 rounded-lg px-4 py-3 text-[#FF3B30] font-medium text-[15px]">
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end pt-4 border-t border-gray-100">
                                    <button
                                        class="bg-[#007AFF] text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-md hover:bg-[#005ECB] transition">Save
                                        Changes</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
     MODAL: EDIT PATIENT
    ========================================== -->
        <div class="inv-modal-overlay" id="modal-edit-patient">
            <div class="inv-modal" style="max-width: 700px; border-radius: 24px; padding: 0; overflow: hidden;">
                <form id="edit-patient-form" method="POST" action="" enctype="multipart/form-data"
                    style="display: flex; flex-direction: column; height: 85vh; max-height: 800px;">
                    @csrf
                    @method('PUT')
                    <div class="inv-modal-header" style="padding: 24px 32px; border-bottom: none;">
                        <div class="inv-modal-title" style="font-size: 24px; font-weight: 700;">Edit Patient</div>
                        <button type="button" class="inv-modal-close" onclick="closeModal('modal-edit-patient')"
                            style="font-size: 28px; color: #86868b;">&times;</button>
                    </div>

                    <div class="inv-modal-body" style="padding: 0 32px 32px; overflow-y: auto; flex: 1;">
                        <!-- 1. Core Info -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            1. Personal Information</div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">First
                                    Name</label>
                                <input type="text" name="first_name" id="edit-first-name" class="inv-form-input"
                                    required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Last
                                    Name</label>
                                <input type="text" name="last_name" id="edit-last-name" class="inv-form-input"
                                    required
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Phone</label>
                                <input type="text" name="phone" id="edit-phone" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Email</label>
                                <input type="email" name="email" id="edit-email" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>
                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Gender</label>
                                <select name="gender" id="edit-gender" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Date
                                    of Birth</label>
                                <input type="date" name="birthdate" id="edit-birthdate" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>

                        <!-- 2. Membership & Medical -->
                        <div class="modal-section-title"
                            style="font-size: 15px; font-weight: 600; color: #1d1d1f; margin-bottom: 16px; border-bottom: 1px solid #e5e5ea; padding-bottom: 8px;">
                            2. Membership & Medical</div>

                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Membership
                                    Tier</label>
                                <select name="membership_tier" id="edit-tier" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                                    <option value="Standard">Standard</option>
                                    <option value="Silver">Silver</option>
                                    <option value="Gold">Gold</option>
                                    <option value="Platinum">Platinum</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Points</label>
                                <input type="number" name="points" id="edit-points" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>

                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Blood
                                    Group</label>
                                <select name="blood_group" id="edit-blood-group" class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px; width: 100%;">
                                    <option value="">Unknown</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                            <div class="inv-form-group" style="flex: 1;">
                                <!-- Empty spacer or another field -->
                            </div>
                        </div>

                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #1d1d1f; margin-bottom: 8px;">Chronic
                                    Diseases (Comma separated)</label>
                                <input type="text" name="chronic_diseases" id="edit-chronic"
                                    class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>

                        <div class="inv-form-row" style="gap: 20px; margin-bottom: 20px;">
                            <div class="inv-form-group" style="flex: 1;">
                                <label class="inv-form-label"
                                    style="font-size: 14px; font-weight: 500; color: #ff3b30; margin-bottom: 8px;">Drug
                                    Allergies (Comma separated)</label>
                                <input type="text" name="drug_allergies" id="edit-allergies"
                                    class="inv-form-input"
                                    style="border-radius: 12px; height: 48px; border: 1px solid #d2d2d7; padding: 0 16px; font-size: 15px;">
                            </div>
                        </div>

                    </div>
                    <div class="inv-modal-footer"
                        style="padding: 24px 32px; border-top: 1px solid #e5e5ea; display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="button" class="inv-btn-secondary" onclick="closeModal('modal-edit-patient')"
                            style="border-radius: 99px; padding: 12px 24px; font-weight: 600; border: 1px solid #d2d2d7; color: #1d1d1f; background: #fff;">Cancel</button>
                        <button type="submit" class="inv-btn-primary"
                            style="border-radius: 99px; padding: 12px 24px; font-weight: 600; background: #007aff; color: #fff; border: none;">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Delete Confirmation -->
        <div class="inv-modal-overlay" id="modal-delete">
            <div class="inv-modal" style="max-width: 400px;">
                <div class="inv-modal-header">
                    <div class="inv-modal-title" style="color: #ff3b30;">Delete Patient</div>
                    <button class="inv-modal-close" onclick="closeModal('modal-delete')">&times;</button>
                </div>
                <div class="inv-modal-body">
                    <p id="delete-confirm-text">Are you sure you want to delete this patient? This action cannot be
                        undone.</p>
                </div>
                <div class="inv-modal-footer">
                    <button class="inv-btn-secondary" onclick="closeModal('modal-delete')">Cancel</button>

                    {{-- Single Delete Form --}}
                    <form id="delete-form" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inv-btn-primary"
                            style="background-color: #ff3b30; border-color: #ff3b30;">Delete</button>
                    </form>

                    {{-- Bulk Delete Button --}}
                    <button id="btn-bulk-delete" type="button" class="inv-btn-primary"
                        style="background-color: #ff3b30; border-color: #ff3b30; display: none;"
                        onclick="executeBulkDelete()">Delete</button>
                </div>
            </div>
        </div>

        <!-- JavaScript Logic -->
        <script>
            // --- Helper Functions ---
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

            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
                document.body.classList.add('modal-open');
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
                document.body.classList.remove('modal-open');
            }

            // --- View/Edit/Delete Logic (List View) ---
            function openViewModal(patient) {
                document.getElementById('view-patient-name').textContent = patient.first_name + ' ' + patient.last_name;
                document.getElementById('view-patient-hn').textContent = patient.hn_number || '-';

                document.getElementById('view-patient-tier').textContent = patient.membership_tier || 'Standard';
                // Adjust badge class
                let badgeClass = 'inv-status-badge';
                if (patient.membership_tier === 'Gold') badgeClass = 'badge-gold';
                else if (patient.membership_tier === 'Platinum') badgeClass = 'badge-platinum';
                else if (patient.membership_tier === 'Silver') badgeClass = 'badge-silver';
                document.getElementById('view-patient-tier').className = badgeClass +
                    ' px-2 py-0.5 rounded text-[11px] font-bold uppercase';

                document.getElementById('view-patient-gender').textContent = patient.gender || '-';

                // Calculate Age
                let age = '-';
                if (patient.birthdate) {
                    const birth = new Date(patient.birthdate);
                    const now = new Date();
                    age = now.getFullYear() - birth.getFullYear();
                }
                document.getElementById('view-patient-age').textContent = age + ' Years';

                document.getElementById('view-patient-phone').textContent = patient.phone || '-';
                document.getElementById('view-patient-email').textContent = patient.email || '-';

                // Chronic Conditions
                let conditionsStr = '-';
                if (patient.chronic_diseases && patient.chronic_diseases.length > 0) {
                    conditionsStr = patient.chronic_diseases.join(', ');
                }
                document.getElementById('view-patient-conditions').textContent = conditionsStr;

                document.getElementById('view-patient-allergies').textContent = patient.allergies ? patient.allergies.join(
                    ', ') : '-';

                openModal('modal-view-patient');
            }

            function openEditModal(patient) {
                document.getElementById('edit-patient-form').action = "/peoples/patients/" + patient.id;

                document.getElementById('edit-first-name').value = patient.first_name || '';
                document.getElementById('edit-last-name').value = patient.last_name || '';
                document.getElementById('edit-phone').value = patient.phone || '';
                document.getElementById('edit-email').value = patient.email || '';
                document.getElementById('edit-gender').value = patient.gender || 'Male';
                document.getElementById('edit-birthdate').value = patient.birthdate ? patient.birthdate.split('T')[0] : '';
                document.getElementById('edit-tier').value = patient.membership_tier || 'Standard';
                document.getElementById('edit-points').value = patient.points !== undefined && patient.points !== null ? patient
                    .points : 0;
                document.getElementById('edit-blood-group').value = patient.blood_group || '';

                // Handle Chronic Diseases
                let diseases = patient.chronic_diseases;
                if (Array.isArray(diseases)) {
                    document.getElementById('edit-chronic').value = diseases.join(', ');
                } else if (typeof diseases === 'string') {
                    // Try parsing if JSON string
                    try {
                        const parsed = JSON.parse(diseases);
                        if (Array.isArray(parsed)) document.getElementById('edit-chronic').value = parsed.join(', ');
                        else document.getElementById('edit-chronic').value = diseases;
                    } catch (e) {
                        document.getElementById('edit-chronic').value = diseases;
                    }
                } else {
                    document.getElementById('edit-chronic').value = '';
                }

                // Handle Drug Allergies
                let allergies = patient.drug_allergies;
                if (Array.isArray(allergies)) {
                    document.getElementById('edit-allergies').value = allergies.join(', ');
                } else if (typeof allergies === 'string') {
                    try {
                        const parsed = JSON.parse(allergies);
                        if (Array.isArray(parsed)) document.getElementById('edit-allergies').value = parsed.join(', ');
                        else document.getElementById('edit-allergies').value = allergies;
                    } catch (e) {
                        document.getElementById('edit-allergies').value = allergies;
                    }
                } else {
                    document.getElementById('edit-allergies').value = '';
                }

                openModal('modal-edit-patient');
            }

            function confirmDelete(id) {
                document.getElementById('delete-form').style.display = 'inline';
                document.getElementById('btn-bulk-delete').style.display = 'none';
                document.getElementById('delete-confirm-text').textContent =
                    'Are you sure you want to delete this patient? This action cannot be undone.';
                document.getElementById('delete-form').action = "/peoples/patients/" + id;
                openModal('modal-delete');
            }

            // --- Bulk Actions Logic ---
            // --- Bulk Actions Logic ---
            const selectAll = document.getElementById('select-all');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCountSpan = document.getElementById('selected-count');

            function updateBulkActions() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count > 0) {
                    bulkActions.style.display = 'flex';
                    selectedCountSpan.textContent = count;
                } else {
                    bulkActions.style.display = 'none';
                }
            }

            function initializeBulkListeners() {
                // Re-select selectAll because it might be replaced by AJAX
                const selectAll = document.getElementById('select-all');

                if (selectAll) {
                    // Clone node to remove old listeners (simplest way without named functions)
                    // Or just use 'onclick' which is easier to overwrite, but let's stick to standard event listeners 
                    // and rely on the fact that the element is new (from AJAX) or we just add a fresh listener.
                    // The issue is on initial page load vs AJAX reload.
                    // For simplicity, we can just assign the onchange handler directly.
                    selectAll.onchange = function() {
                        const isChecked = this.checked;
                        document.querySelectorAll('.item-checkbox').forEach(cb => {
                            cb.checked = isChecked;
                        });
                        updateBulkActions();
                    };
                }

                document.querySelectorAll('.item-checkbox').forEach(cb => {
                    cb.onchange = function() {
                        updateBulkActions();
                        if (selectAll) {
                            const allChecked = document.querySelectorAll('.item-checkbox:checked').length ===
                                document
                                .querySelectorAll('.item-checkbox').length;
                            selectAll.checked = allChecked;
                        }
                    };
                });
            }

            // Initial bind
            initializeBulkListeners();

            // --- Real-time Search ---
            const searchInput = document.getElementById('search-input');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value;
                    const url = new URL(window.location.href);

                    if (query.length > 0) {
                        url.searchParams.set('search', query);
                        url.searchParams.delete('page'); // Reset to page 1
                    } else {
                        url.searchParams.delete('search');
                    }

                    window.history.pushState({}, '', url);

                    searchTimeout = setTimeout(() => {
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                // Replace the list view
                                const newContent = doc.getElementById('view-list').innerHTML;
                                document.getElementById('view-list').innerHTML = newContent;

                                // Re-initialize listeners
                                initializeBulkListeners();
                            })
                            .catch(err => console.error('Search error:', err));
                    }, 400); // 400ms debounce
                });
            }

            function confirmBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const count = checked.length;

                if (count === 0) return;

                document.getElementById('delete-form').style.display = 'none';
                document.getElementById('btn-bulk-delete').style.display = 'inline-block';
                document.getElementById('delete-confirm-text').textContent =
                    `Are you sure you want to delete ${count} selected patients? This action cannot be undone.`;

                openModal('modal-delete');
            }

            function executeBulkDelete() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                const ids = Array.from(checked).map(cb => cb.dataset.id);

                if (ids.length === 0) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector(
                    'meta[name="csrf-token"]').getAttribute('content') : '';

                const btn = document.getElementById('btn-bulk-delete');
                const originalText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Deleting...';

                fetch('/peoples/patients/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const text = await res.text();
                            throw new Error(text || res.statusText);
                        }
                        return res.json();
                    })
                    .then(data => {
                        closeModal('modal-delete');
                        if (data.success || data.message === 'Patients deleted successfully') {
                            // Allow loose success check if backend structure varies slightly
                            showFlash(data.message || 'Patients deleted successfully', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showFlash(data.message || 'Error deleting patients', 'error');
                            btn.disabled = false;
                            btn.textContent = originalText;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        closeModal('modal-delete');
                        showFlash('An error occurred: ' + err.message, 'error');
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            }

            // --- Navigation & Tabs (Restored) ---
            function navigateToProfile(patient) {
                // Handle Real Data Object
                document.getElementById('p-header-name').innerText = patient.first_name + ' ' + (patient.last_name || '');
                document.getElementById('p-name').innerText = patient.first_name + ' ' + (patient.last_name || '');
                document.getElementById('p-id').innerText = patient.hn_number || '-';

                // Tier Badge logic
                let tier = patient.membership_tier || 'Standard';
                let badgeClass = 'inv-status-badge';

                if (tier === 'Gold') badgeClass = 'badge-gold inv-status-badge';
                else if (tier === 'Platinum') badgeClass = 'badge-platinum inv-status-badge';
                else if (tier === 'Silver') badgeClass = 'badge-silver inv-status-badge';
                else badgeClass = 'inv-status-badge';

                let finalClass = "";
                if (tier === 'Gold') finalClass = "badge-gold";
                else if (tier === 'Platinum') finalClass = "badge-platinum";
                else if (tier === 'Silver') finalClass = "badge-silver";
                else finalClass = "badge-silver";

                document.getElementById('p-tier').innerText = tier + (tier.includes('Member') ? '' : ' Member');
                document.getElementById('p-tier').className = finalClass +
                    " px-2 py-0.5 rounded text-[10px] font-bold uppercase";

                document.getElementById('p-gender').innerText = patient.gender || '-';

                // Age
                let age = '-';
                if (patient.birthdate) {
                    const birth = new Date(patient.birthdate);
                    const now = new Date();
                    age = (now.getFullYear() - birth.getFullYear()) + ' Years';
                }
                document.getElementById('p-age').innerText = age;

                // Phone
                if (document.getElementById('p-phone')) {
                    document.getElementById('p-phone').innerText = patient.phone || '-';
                }

                // Email
                if (document.getElementById('p-email')) {
                    document.getElementById('p-email').innerText = patient.email || '-';
                }

                // Blood Group
                if (document.getElementById('p-blood-group')) {
                    document.getElementById('p-blood-group').innerText = patient.blood_group || '-';
                }

                // Points
                if (document.getElementById('p-points-display')) {
                    // Simple formatter
                    const pts = patient.points ? Number(patient.points).toLocaleString() : '0';
                    document.getElementById('p-points-display').innerText = pts + ' pts';
                }

                // Avatar
                const container = document.getElementById('p-avatar-container');
                const icon = document.getElementById('p-avatar-icon');
                // Logic for gender icon
                if (patient.gender === 'Female') {
                    container.className =
                        `w-28 h-28 rounded-full border border-pink-100 bg-pink-50 flex items-center justify-center text-pink-500`;
                    icon.className = `fa-solid fa-person-dress text-6xl`;
                } else if (patient.gender === 'Male') {
                    container.className =
                        `w-28 h-28 rounded-full border border-blue-100 bg-blue-50 flex items-center justify-center text-blue-500`;
                    icon.className = `fa-solid fa-person text-6xl`;
                } else {
                    container.className =
                        `w-28 h-28 rounded-full border border-gray-100 bg-gray-50 flex items-center justify-center text-gray-500`;
                    icon.className = `fa-solid fa-user text-6xl`;
                }

                // Update Edit Button to open Modal with this patient
                document.getElementById('btn-profile-edit').onclick = function() {
                    openEditModal(patient);
                };

                // --- Populate Settings Tab ---
                document.getElementById('setting-first-name').value = patient.first_name || '';
                document.getElementById('setting-last-name').value = patient.last_name || '';
                document.getElementById('setting-phone').value = patient.phone || '';
                document.getElementById('setting-birthdate').value = patient.birthdate || '';

                // Parse allergies for settings input
                let settingsAllergies = patient.drug_allergies;
                if (Array.isArray(settingsAllergies)) {
                    document.getElementById('setting-allergies').value = settingsAllergies.join(', ');
                } else if (typeof settingsAllergies === 'string') {
                    try {
                        const parsed = JSON.parse(settingsAllergies);
                        if (Array.isArray(parsed)) document.getElementById('setting-allergies').value = parsed.join(', ');
                        else document.getElementById('setting-allergies').value = settingsAllergies;
                    } catch (e) {
                        document.getElementById('setting-allergies').value = settingsAllergies;
                    }
                } else {
                    document.getElementById('setting-allergies').value = '';
                }

                // --- Populate Overview Tab: Medical Alerts ---
                const parseArray = (data) => {
                    if (Array.isArray(data)) return data;
                    if (typeof data === 'string') {
                        try {
                            const parsed = JSON.parse(data);
                            if (Array.isArray(parsed)) return parsed;
                            return data.split(',').map(s => s.trim()).filter(s => s);
                        } catch (e) {
                            return data.split(',').map(s => s.trim()).filter(s => s);
                        }
                    }
                    return [];
                };

                const allergiesList = parseArray(patient.drug_allergies);
                const conditionsList = parseArray(patient.chronic_diseases);

                // Allergies HTML
                const allergiesContainer = document.getElementById('p-allergies-list');
                if (allergiesList.length > 0) {
                    allergiesContainer.innerHTML = allergiesList.map(a => `
                        <span class="bg-[#FFF5F5] text-[#FF3B30] px-3 py-1 rounded-lg font-medium text-sm border border-red-100 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i> ${a}
                        </span>
                    `).join('');
                } else {
                    allergiesContainer.innerHTML = '<span class="text-sm text-gray-400 italic">No known allergies</span>';
                }

                // Conditions HTML
                const conditionsContainer = document.getElementById('p-conditions-list');
                if (conditionsList.length > 0) {
                    conditionsContainer.innerHTML = conditionsList.map(c => `
                        <span class="bg-[#FFFBEB] text-[#B45309] px-3 py-1 rounded-lg font-medium text-sm border border-amber-100">
                            ${c}
                        </span>
                    `).join('');
                } else {
                    conditionsContainer.innerHTML = '<span class="text-sm text-gray-400 italic">No chronic conditions</span>';
                }

                document.getElementById('page-patient-list').classList.add('hidden');
                document.getElementById('page-patient-profile').classList.remove('hidden');
                window.scrollTo(0, 0);
            }

            function navigateBackToList() {
                document.getElementById('page-patient-profile').classList.add('hidden');
                document.getElementById('page-patient-list').classList.remove('hidden');
            }

            function switchTab(tabId) {
                ['overview', 'history', 'settings'].forEach(id => {
                    document.getElementById('content-' + id)?.classList.add('hidden');
                    document.getElementById('tab-' + id)?.classList.remove('bg-white', 'text-[#1D1D1F]', 'shadow-sm');
                    document.getElementById('tab-' + id)?.classList.add('text-[#86868B]');
                });
                document.getElementById('content-' + tabId).classList.remove('hidden');
                const activeBtn = document.getElementById('tab-' + tabId);
                activeBtn.classList.remove('text-[#86868B]');
                activeBtn.classList.add('bg-white', 'text-[#1D1D1F]', 'shadow-sm');
            }

            function toggleDropdown(id) {
                const el = document.getElementById(id);
                if (el.classList.contains('hidden')) {
                    document.querySelectorAll('[id^="drop-"]').forEach(d => d.classList.add('hidden'));
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            }
            document.addEventListener('click', function(event) {
                if (!event.target.closest('button')) {
                    document.querySelectorAll('[id^="drop-"]').forEach(d => d.classList.add('hidden'));
                }
            });

            // --- Filter & View ---
            function filterList(type) {
                // Update URL parameters without reloading (optional) or just reload
                const url = new URL(window.location.href);
                if (type === 'all') {
                    url.searchParams.delete('type');
                } else {
                    url.searchParams.set('type', type);
                }
                window.location.href = url.toString();
            }

            function switchView(viewName) {
                const listBtn = document.getElementById('toggle-list');
                const actBtn = document.getElementById('toggle-activity');
                const listView = document.getElementById('view-list');
                const actView = document.getElementById('view-activity');

                if (viewName === 'list') {
                    listBtn.classList.add('view-toggle-active');
                    listBtn.classList.remove('view-toggle-inactive');
                    actBtn.classList.add('view-toggle-inactive');
                    actBtn.classList.remove('view-toggle-active');
                    listView.classList.remove('hidden');
                    actView.classList.add('hidden');
                } else {
                    listBtn.classList.add('view-toggle-inactive');
                    listBtn.classList.remove('view-toggle-active');
                    actBtn.classList.add('view-toggle-active');
                    actBtn.classList.remove('view-toggle-inactive');
                    listView.classList.add('hidden');
                    actView.classList.remove('hidden');
                }
            }

            // --- Add Patient Modal (Legacy/Existing) ---
            function openAddPatientModal() {
                const modal = document.getElementById('add-patient-modal');
                const panel = document.getElementById('add-modal-panel');
                modal.classList.remove('hidden');
                document.body.classList.add('modal-open');
                setTimeout(() => {
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeAddPatientModal() {
                const modal = document.getElementById('add-patient-modal');
                const panel = document.getElementById('add-modal-panel');
                panel.classList.remove('scale-100', 'opacity-100');
                panel.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('modal-open');
                }, 300);
            }
        </script>

        <!-- ==========================================
        MODAL: ADD NEW PATIENT (MOVED TO BOTTOM FOR FIX)
        ========================================== -->
        <div id="add-patient-modal" class="fixed inset-0 z-[9999] hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-md transition-opacity duration-300"
                onclick="closeAddPatientModal()"></div>

            <!-- Modal Content -->
            <div class="absolute inset-0 md:inset-auto md:top-[5%] md:left-1/2 md:-translate-x-1/2 bg-white rounded-none md:rounded-[24px] shadow-2xl overflow-hidden flex flex-col transform transition-all scale-95 opacity-0 modal-panel w-full md:w-[800px] h-full md:h-auto md:max-h-[90vh] z-10"
                id="add-modal-panel">

                <!-- Modal Header -->
                <div
                    class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h2 class="text-xl font-bold text-[#1D1D1F]">New Patient Registration</h2>
                    <button onclick="closeAddPatientModal()"
                        class="w-8 h-8 rounded-full bg-[#F5F5F7] text-[#86868B] hover:text-[#1D1D1F] flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-8 overflow-y-auto bg-[#F9F9FB] flex-1">

                    <form id="add-patient-form" action="{{ route('peoples.patients.store') }}" method="POST">
                        @csrf
                        <!-- Hidden Fields -->
                        <input type="hidden" name="membership_tier" value="Bronze">

                        <!-- Section 1: Personal Info -->
                        <div class="bg-white p-6 rounded-[20px] shadow-sm mb-6">
                            <h3
                                class="text-sm font-bold text-[#1D1D1F] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="fa-regular fa-id-card text-[#007AFF]"></i> Personal Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">First
                                        Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" required
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                        placeholder="e.g. Somchai">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Last
                                        Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name"
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                        placeholder="e.g. Meesuk">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label
                                        class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Gender</label>
                                    <div class="relative">
                                        <select name="gender"
                                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <i
                                            class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#86868B] text-xs pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Birthday</label>
                                    <input type="date" name="birthdate"
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Contact Info -->
                        <div class="bg-white p-6 rounded-[20px] shadow-sm mb-6">
                            <h3
                                class="text-sm font-bold text-[#1D1D1F] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-phone text-[#34C759]"></i> Contact Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Phone
                                        Number <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone"
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                        placeholder="08x-xxx-xxxx">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Line
                                        ID / Email</label>
                                    <input type="text" name="email"
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                        placeholder="Optional">
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Medical Info -->
                        <div class="bg-white p-6 rounded-[20px] shadow-sm mb-6 border border-red-50">
                            <h3
                                class="text-sm font-bold text-[#FF3B30] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-notes-medical"></i> Medical Profile
                            </h3>
                            <div class="mb-5">
                                <label class="block text-[13px] font-bold text-[#1D1D1F] mb-1.5 ml-1">Drug
                                    Allergies</label>
                                <input type="text" name="drug_allergies[]"
                                    class="w-full bg-[#FFF5F5] border border-red-100 rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] focus:outline-none focus:border-red-300 transition"
                                    placeholder="e.g. Penicillin, Aspirin (Leave blank if none)">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Chronic
                                        Conditions</label>
                                    <input type="text" name="chronic_diseases[]"
                                        class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px]"
                                        placeholder="e.g. Diabetes, Hypertension">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-semibold text-[#1D1D1F] mb-1.5 ml-1">Blood
                                        Group</label>
                                    <div class="relative">
                                        <select name="blood_group"
                                            class="w-full apple-input rounded-xl px-4 py-3 text-[#1D1D1F] font-medium text-[15px] appearance-none cursor-pointer">
                                            <option value="">Unknown</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                        <i
                                            class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-[#86868B] text-xs pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 border-t border-gray-100 bg-white flex justify-end gap-3 sticky bottom-0 z-10">
                    <button onclick="closeAddPatientModal()"
                        class="px-6 py-2.5 rounded-xl border border-gray-200 text-[#1D1D1F] font-semibold hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" form="add-patient-form"
                        class="px-8 py-2.5 rounded-xl bg-[#007AFF] text-white font-semibold shadow-lg shadow-blue-500/30 hover:bg-[#005ECB] transition">Save
                        Patient</button>
                </div>

            </div>
        </div>
    </body>

    </html>
</x-app-layout>

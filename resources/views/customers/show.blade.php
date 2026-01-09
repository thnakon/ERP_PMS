@extends('layouts.app')

@section('title', $customer->name)
@section('page-title')
    <div class="welcome-container">
        <a href="{{ route('customers.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; display: flex; align-items: center; gap: 4px; text-decoration: none;">
            <i class="ph ph-arrow-left"></i>
            {{ __('customers.title') }}
        </a>
        <span>{{ __('customers.view_customer') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('customers.edit', $customer) }}"
        class="px-5 py-2.5 bg-orange-500 hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-pencil-simple"></i>
        {{ __('edit') }}
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer Header Card --}}
            <div class="card-ios p-6">
                <div class="flex items-start gap-6">
                    {{-- Avatar --}}
                    @if ($customer->gender === 'male')
                        <div class="w-24 h-24 rounded-3xl flex items-center justify-center shadow-lg"
                            style="background-color: #e3f2fd;">
                            <i class="ph-fill ph-user text-5xl" style="color: #007aff;"></i>
                        </div>
                    @elseif ($customer->gender === 'female')
                        <div class="w-24 h-24 rounded-3xl flex items-center justify-center shadow-lg"
                            style="background-color: #fce4ec;">
                            <i class="ph-fill ph-user text-5xl" style="color: #ff2d55;"></i>
                        </div>
                    @else
                        <div class="w-24 h-24 rounded-3xl bg-gray-100 flex items-center justify-center shadow-lg">
                            <i class="ph-fill ph-user text-5xl text-gray-400"></i>
                        </div>
                    @endif

                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                                @if ($customer->nickname)
                                    <p class="text-gray-500">({{ $customer->nickname }})</p>
                                @endif
                            </div>
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-bold {{ $customer->tier_color }}">
                                @if ($customer->member_tier === 'platinum')
                                    <i class="ph-fill ph-crown"></i>
                                @elseif ($customer->member_tier === 'gold')
                                    <i class="ph-fill ph-medal"></i>
                                @elseif ($customer->member_tier === 'silver')
                                    <i class="ph-fill ph-star"></i>
                                @else
                                    <i class="ph ph-user"></i>
                                @endif
                                {{ ucfirst($customer->member_tier ?? 'regular') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            @if ($customer->age)
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('customers.age') }}</p>
                                    <p class="font-semibold text-gray-900">{{ $customer->age }}
                                        {{ __('customers.years_old') }}</p>
                                </div>
                            @endif
                            @if ($customer->gender)
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('customers.gender') }}</p>
                                    <div class="flex items-center gap-1.5 font-bold"
                                        style="color: {{ $customer->gender === 'female' ? '#ff2d55' : ($customer->gender === 'male' ? '#007aff' : '#111827') }};">
                                        @if ($customer->gender === 'male')
                                            <i class="ph-fill ph-gender-male"></i>
                                        @elseif($customer->gender === 'female')
                                            <i class="ph-fill ph-gender-female"></i>
                                        @endif
                                        {{ __('customers.' . $customer->gender) }}
                                    </div>
                                </div>
                            @endif
                            @if ($customer->birth_date)
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('customers.birth_date') }}</p>
                                    <p class="font-semibold text-gray-900">{{ $customer->birth_date->format('d M Y') }}</p>
                                </div>
                            @endif
                            @if ($customer->national_id)
                                <div>
                                    <p class="text-xs text-gray-500">{{ __('customers.national_id') }}</p>
                                    <p class="font-semibold text-gray-900">{{ $customer->national_id }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Drug Allergy Alert --}}
            @if ($customer->hasDrugAllergies())
                <div class="card-ios p-6 border-2 border-red-300 bg-gradient-to-r from-red-50 to-orange-50">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-red-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-red-500/30">
                            <i class="ph-fill ph-warning text-white text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-700 mb-2">{{ __('customers.allergy_warning') }}</h3>
                            <div class="space-y-2">
                                @if (!empty($customer->drug_allergies))
                                    @foreach ($customer->drug_allergies as $allergy)
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-semibold">
                                                {{ is_array($allergy) ? $allergy['drug_name'] ?? $allergy : $allergy }}
                                            </span>
                                            @if (is_array($allergy) && !empty($allergy['reaction']))
                                                <span class="text-gray-600 text-sm">→ {{ $allergy['reaction'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                                @if (!empty($customer->allergy_notes))
                                    <p class="text-sm text-red-600 mt-2">{{ implode(', ', $customer->allergy_notes) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Contact Information --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-address-book text-ios-blue"></i>
                    {{ __('customers.contact_info') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="ph-fill ph-phone text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('customers.phone') }}</p>
                            <p class="font-semibold text-gray-900">{{ $customer->phone ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($customer->email)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                <i class="ph-fill ph-envelope text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">{{ __('customers.email') }}</p>
                                <p class="font-semibold text-gray-900">{{ $customer->email }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($customer->line_id)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                                <i class="ph-fill ph-chat-circle text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">{{ __('customers.line_id') }}</p>
                                <p class="font-semibold text-gray-900">{{ $customer->line_id }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($customer->address)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl md:col-span-2">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-map-pin text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">{{ __('customers.address') }}</p>
                                <p class="font-semibold text-gray-900">{{ $customer->address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Purchase History --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-receipt text-green-500"></i>
                    {{ __('customers.last_visit') }}
                </h3>

                @if ($customer->orders && $customer->orders->count() > 0)
                    <div class="space-y-3">
                        @foreach ($customer->orders as $order)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                                        <i class="ph ph-shopping-bag text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">#{{ $order->order_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900">฿{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <i class="ph ph-shopping-bag text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500">ยังไม่มีประวัติการซื้อ</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Health & Loyalty --}}
        <div class="space-y-6">
            {{-- Loyalty Stats --}}
            <div class="card-ios p-6 bg-gradient-to-br from-purple-500 to-indigo-600 text-white">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-crown"></i>
                    {{ __('customers.loyalty_info') }}
                </h3>

                <div class="space-y-4">
                    <div class="text-center p-4 bg-white/10 rounded-2xl backdrop-blur">
                        <p class="text-4xl font-bold">{{ number_format($customer->points_balance ?? 0) }}</p>
                        <p class="text-sm text-white/80">{{ __('customers.points_balance') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-white/10 rounded-xl">
                            <p class="text-xl font-bold">{{ number_format($customer->visit_count ?? 0) }}</p>
                            <p class="text-xs text-white/80">{{ __('customers.visits') }}</p>
                        </div>
                        <div class="text-center p-3 bg-white/10 rounded-xl">
                            <p class="text-xl font-bold">฿{{ number_format($customer->total_spent ?? 0) }}</p>
                            <p class="text-xs text-white/80">{{ __('customers.total_spent') }}</p>
                        </div>
                    </div>

                    @if ($customer->member_since)
                        <p class="text-center text-sm text-white/70">
                            {{ __('customers.member_since') }}: {{ $customer->member_since->format('d M Y') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Medical Records --}}
            <div class="card-ios p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-first-aid-kit text-red-500"></i>
                    {{ __('customers.medical_records') }}
                </h3>

                <div class="space-y-4">
                    {{-- Pregnancy Status --}}
                    @if ($customer->pregnancy_status && $customer->pregnancy_status !== 'none')
                        <div class="flex items-center gap-3 p-3 bg-pink-50 rounded-xl border border-pink-200">
                            <i class="ph-fill ph-baby text-pink-500 text-xl"></i>
                            <div>
                                <p class="text-xs text-gray-500">{{ __('customers.pregnancy_status') }}</p>
                                <p class="font-semibold text-pink-700">{{ $customer->pregnancy_status_label }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Chronic Diseases --}}
                    @if (!empty($customer->chronic_diseases))
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ __('customers.chronic_diseases') }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($customer->chronic_diseases as $disease)
                                    <span
                                        class="px-3 py-1.5 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">
                                        {{ $disease }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Medical Notes --}}
                    @if ($customer->medical_notes)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ __('customers.medical_notes') }}</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-xl">{{ $customer->medical_notes }}</p>
                        </div>
                    @endif

                    @if (!$customer->pregnancy_status || $customer->pregnancy_status === 'none')
                        @if (empty($customer->chronic_diseases) && !$customer->medical_notes)
                            <p class="text-gray-400 text-sm text-center py-4">ไม่มีข้อมูลทางการแพทย์ที่บันทึกไว้</p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if ($customer->notes)
                <div class="card-ios p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-note text-gray-500"></i>
                        {{ __('customers.notes') }}
                    </h3>
                    <p class="text-gray-600">{{ $customer->notes }}</p>
                </div>
            @endif

            {{-- Meta Info --}}
            <div class="card-ios p-6">
                <h3 class="text-sm font-bold text-gray-500 mb-3">{{ __('meta_info') }}</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('created_at') }}</span>
                        <span class="font-medium">{{ $customer->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('updated_at') }}</span>
                        <span class="font-medium">{{ $customer->updated_at->format('d M Y H:i') }}</span>
                    </div>
                    @if ($customer->last_visit_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('customers.last_visit') }}</span>
                            <span class="font-medium">{{ $customer->last_visit_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Delete Button --}}
            <button type="button"
                onclick="deleteRow({{ $customer->id }}, '{{ $customer->name }}', '{{ route('customers.destroy', $customer) }}')"
                class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-xl transition flex items-center justify-center gap-2">
                <i class="ph ph-trash"></i>
                {{ __('customers.delete_customer') }}
            </button>
        </div>
    </div>
@endsection

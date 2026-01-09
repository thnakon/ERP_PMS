@extends('layouts.app')

@section('title', __('tiers.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('tiers.subtitle') }}
        </p>
        <span>{{ __('tiers.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <div class="flex gap-2">
        <button type="button" onclick="recalculateAll()"
            class="px-5 py-2.5 bg-white hover:bg-gray-50 text-purple-600 font-semibold rounded-xl shadow-sm border border-purple-100 transition active-scale flex items-center gap-2">
            <i class="ph-bold ph-arrow-clockwise"></i>
            {{ __('tiers.recalculate') }}
        </button>
        <a href="{{ route('member-tiers.create') }}"
            class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
            <i class="ph-bold ph-plus"></i>
            {{ __('tiers.add_tier') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        @if ($tiers->isEmpty())
            <div
                class="bg-white/50 backdrop-blur-sm rounded-3xl p-16 text-center text-gray-400 border border-dashed border-gray-200">
                <i class="ph ph-crown text-5xl mb-4 text-gray-200"></i>
                <h3 class="text-xl font-bold text-gray-500 mb-2">{{ __('tiers.no_tiers') }}</h3>
                <p class="text-sm mb-6">{{ __('tiers.no_tiers_desc') }}</p>
                <a href="{{ route('member-tiers.create') }}"
                    class="inline-block px-6 py-3 bg-ios-blue text-white rounded-2xl font-bold transition active-scale shadow-lg shadow-blue-500/20">
                    {{ __('tiers.add_tier') }}
                </a>
            </div>
        @else
            {{-- Tier Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                @foreach ($tiers as $tier)
                    <div
                        class="bg-white/80 backdrop-blur-md rounded-3xl overflow-hidden border border-white shadow-sm hover-ios transition-all group">
                        {{-- Tier Color Header --}}
                        <div class="h-24 flex items-center justify-center relative overflow-hidden"
                            style="background: {{ $tier->color }}">
                            {{-- Decorative Background Icon --}}
                            <i
                                class="ph-fill {{ $tier->icon ?? 'ph-crown' }} absolute -right-4 -bottom-4 text-8xl text-black/5 rotate-12"></i>

                            <div
                                class="z-10 bg-white/20 backdrop-blur-md w-14 h-14 rounded-2xl flex items-center justify-center border border-white/30 shadow-lg">
                                <i class="ph-fill {{ $tier->icon ?? 'ph-crown' }} text-3xl text-white drop-shadow-md"></i>
                            </div>
                        </div>

                        {{-- Tier Info --}}
                        <div class="p-5 text-center">
                            <h3 class="text-lg font-black text-gray-900 leading-tight">{{ $tier->name }}</h3>
                            @if ($tier->name_th)
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                    {{ $tier->name_th }}</p>
                            @endif

                            <div class="mt-5 grid grid-cols-1 gap-1.5">
                                <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-xl">
                                    <span
                                        class="text-[10px] uppercase font-black text-gray-400 tracking-tighter">Discount</span>
                                    <span class="text-sm font-black text-green-600">{{ $tier->discount_percent }}%</span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-xl">
                                    <span
                                        class="text-[10px] uppercase font-black text-gray-400 tracking-tighter">Multiplier</span>
                                    <span class="text-sm font-black text-blue-600">{{ $tier->points_multiplier }}x</span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-xl">
                                    <span class="text-[10px] uppercase font-black text-gray-400 tracking-tighter">Min
                                        Buy</span>
                                    <span
                                        class="text-sm font-black text-gray-700">฿{{ number_format($tier->min_spending, 0) }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100/50">
                                <span class="text-2xl font-black block" style="color: {{ $tier->color }}">
                                    {{ number_format($tier->customers_count) }}
                                </span>
                                <span
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('tiers.customers_count') }}</span>
                            </div>

                            <div class="mt-5 flex gap-2">
                                <a href="{{ route('member-tiers.edit', $tier) }}"
                                    class="flex-1 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl transition flex items-center justify-center active-scale">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                <button type="button" onclick="deleteTier({{ $tier->id }}, '{{ $tier->name }}')"
                                    class="flex-1 py-2.5 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl transition flex items-center justify-center active-scale">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </div>

                        @if (!$tier->is_active)
                            <div
                                class="absolute top-3 right-3 px-2 py-1 bg-black/50 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                                Inactive
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Comparison Table Style - Stack View --}}
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-chart-bar text-ios-blue"></i>
                        Tier Comparison & Stats
                    </h3>
                </div>

                <div class="stack-container view-list">
                    @php $totalSpending = 0; @endphp
                    @foreach ($tiers as $tier)
                        @php
                            $tierSpending = $tier->customers->sum('total_spent') ?? 0;
                            $totalSpending += $tierSpending;
                        @endphp
                        <div class="stack-item" onclick="window.location.href='{{ route('member-tiers.edit', $tier) }}'">
                            <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 shadow-sm"
                                style="background: {{ $tier->color }}">
                                <i class="ph-fill {{ $tier->icon ?? 'ph-crown' }} text-white text-xl"></i>
                            </div>

                            <div class="stack-col stack-main">
                                <span class="stack-label">{{ __('tiers.name') }}</span>
                                <div class="stack-value text-lg font-black">{{ $tier->name }}</div>
                            </div>

                            <div class="stack-col stack-data hidden md:flex">
                                <span class="stack-label">{{ __('tiers.min_spending') }}</span>
                                <span class="stack-value text-sm font-bold text-gray-600 text-right w-full">
                                    ฿{{ number_format($tier->min_spending, 0) }}
                                </span>
                            </div>

                            <div class="stack-col stack-data hidden lg:flex">
                                <span class="stack-label">Benefits</span>
                                <div class="flex gap-1.5">
                                    <span
                                        class="px-2 py-0.5 bg-green-50 text-green-700 rounded-lg text-[10px] font-black uppercase">{{ $tier->discount_percent }}%
                                        OFF</span>
                                    <span
                                        class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-black uppercase">{{ $tier->points_multiplier }}x
                                        Points</span>
                                </div>
                            </div>

                            <div class="stack-col stack-data">
                                <span class="stack-label">{{ __('tiers.customers_count') }}</span>
                                <span class="stack-value text-lg font-black text-gray-900 text-right w-full">
                                    {{ number_format($tier->customers_count) }}
                                </span>
                            </div>

                            <div class="stack-col stack-data">
                                <span class="stack-label">Total Gained</span>
                                <span class="stack-value text-lg font-black text-green-600 text-right w-full">
                                    ฿{{ number_format($tierSpending, 0) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary Row --}}
                <div
                    class="mt-4 bg-white/80 backdrop-blur-md rounded-3xl p-6 border border-white shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Revenue from
                            Members</p>
                        <h2 class="text-3xl font-black text-gray-900">฿{{ number_format($totalSpending, 0) }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Active Members
                        </p>
                        <h2 class="text-3xl font-black text-ios-blue">{{ number_format($tiers->sum('customers_count')) }}
                        </h2>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        async function recalculateAll() {
            if (!confirm('{{ __('tiers.confirm_recalculate') }}')) return;

            try {
                const response = await fetch('{{ route('member-tiers.recalculate-all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function deleteTier(id, name) {
            if (confirm(`คุณต้องการลบระดับสมาชิก "${name}" ใช่หรือไม่? ลุกค้าในระดับนี้จะถูกเปลี่ยนเป็นระดับเริ่มต้น`)) {
                const form = document.getElementById('deleteForm');
                form.action = `/member-tiers/${id}`;
                form.submit();
            }
        }
    </script>
@endpush

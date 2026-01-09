@extends('layouts.app')

@section('title', __('bundles.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('bundles.subtitle') }}
        </p>
        <span>{{ __('bundles.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('bundles.create') }}"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('bundles.add_bundle') }}
    </a>
@endsection

@section('content')
    @php
        $totalBundles = $bundles->total();
        $activeCount = \App\Models\Bundle::available()->count();
        $totalSold = \App\Models\Bundle::sum('sold_count');
        $totalRevenue = \App\Models\Bundle::withTrashed()->get()->sum(fn($b) => $b->sold_count * $b->bundle_price);
    @endphp

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            {{-- Total Bundles --}}
            <div class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="ph-bold ph-package text-purple-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">{{ __('bundles.total_bundles') }}</span>
                </div>
                <h3 class="text-xl font-black text-purple-600">{{ number_format($totalBundles) }}</h3>
            </div>

            {{-- Active Bundles --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-green-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-green-500 uppercase tracking-wider">{{ __('bundles.active_bundles') }}</span>
                </div>
                <h3 class="text-xl font-black text-green-600">{{ number_format($activeCount) }}</h3>
            </div>

            {{-- Total Sold --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="ph-bold ph-shopping-cart text-blue-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ __('bundles.total_sold') }}</span>
                </div>
                <h3 class="text-xl font-black text-blue-600">{{ number_format($totalSold) }}</h3>
            </div>

            {{-- Revenue --}}
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="ph-bold ph-coins text-orange-500 text-sm"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">{{ __('bundles.revenue') }}</span>
                </div>
                <h3 class="text-xl font-black text-orange-600">฿{{ number_format($totalRevenue, 0) }}</h3>
            </div>
        </div>

        {{-- Toolbar Row --}}
        <div class="flex items-center justify-between gap-4">
            {{-- Left: Search --}}
            <div class="flex items-center gap-2 flex-1 max-w-md">
                <form action="{{ route('bundles.index') }}" method="GET" class="flex-1 relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('bundles.search_bundles') }}"
                        class="w-full bg-white border border-gray-200 rounded-full py-2.5 pl-12 pr-12 focus:ring-2 focus:ring-ios-blue/20 outline-none transition-all shadow-sm">
                    <button type="submit"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-ios-blue transition-colors flex items-center">
                        <i class="ph ph-arrow-right text-xl"></i>
                    </button>
                </form>
            </div>

            {{-- Selection Tool (Optional for Bundles, but keeping UI consistent) --}}
            <div class="flex items-center gap-2">
                <div class="text-sm font-medium text-gray-400">
                    <span class="text-gray-900 font-bold">{{ $bundles->total() }}</span>
                    {{ __('bundles.total_bundles') }}
                </div>
            </div>
        </div>

        {{-- Bundles List (Stack View) --}}
        <div id="bundle-list-container">
            @if ($bundles->isEmpty())
                <div
                    class="bg-white/50 backdrop-blur-sm rounded-3xl p-16 text-center text-gray-400 border border-dashed border-gray-200">
                    <i class="ph ph-package text-5xl mb-4 text-gray-200"></i>
                    <h3 class="text-xl font-bold text-gray-500 mb-2">{{ __('bundles.no_bundles') }}</h3>
                    <p class="text-sm mb-6">{{ __('bundles.no_bundles_desc') }}</p>
                    <a href="{{ route('bundles.create') }}"
                        class="inline-block px-6 py-3 bg-ios-blue text-white rounded-2xl font-bold transition active-scale shadow-lg shadow-blue-500/20">
                        {{ __('bundles.add_bundle') }}
                    </a>
                </div>
            @else
                <div class="stack-container view-list">
                    @foreach ($bundles as $bundle)
                        <div class="stack-item cursor-pointer hover:bg-gray-50/50 transition-colors"
                            onclick="window.location.href='{{ route('bundles.edit', $bundle) }}'">

                            {{-- Image/Icon --}}
                            <div
                                class="w-16 h-16 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 bg-gray-100 overflow-hidden relative shadow-sm border border-gray-200">
                                @if ($bundle->image_path)
                                    <img src="{{ asset('storage/' . $bundle->image_path) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="ph-fill ph-package text-gray-300 text-3xl"></i>
                                @endif

                                @if ($bundle->savings > 0)
                                    <div
                                        class="absolute bottom-0 inset-x-0 bg-red-500 text-white text-[8px] font-black uppercase text-center py-0.5">
                                        -{{ $bundle->savings_percent }}%
                                    </div>
                                @endif
                            </div>

                            {{-- Main Info --}}
                            <div class="stack-col stack-main">
                                <span class="stack-label">{{ __('bundles.name') }}</span>
                                <div class="stack-value text-lg leading-tight">{{ $bundle->name }}</div>
                                <div class="text-xs text-gray-400 font-medium mt-0.5">
                                    {{ $bundle->products->count() }} Items Included
                                </div>
                            </div>

                            {{-- Products Preview --}}
                            <div class="stack-col stack-data hidden md:flex">
                                <span class="stack-label">Items</span>
                                <div class="flex -space-x-2">
                                    @foreach ($bundle->products->take(4) as $product)
                                        <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center shadow-sm overflow-hidden"
                                            title="{{ $product->name }}">
                                            @if ($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                                    class="w-full h-full object-cover text-[8px]">
                                            @else
                                                <span
                                                    class="text-[8px] font-bold text-gray-300">{{ substr($product->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if ($bundle->products->count() > 4)
                                        <div
                                            class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center shadow-sm">
                                            <span
                                                class="text-[10px] font-black text-gray-400">+{{ $bundle->products->count() - 4 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Pricing --}}
                            <div class="stack-col stack-data">
                                <span class="stack-label">Price / Savings</span>
                                <div class="flex flex-col">
                                    <span
                                        class="stack-value font-black text-green-600 text-lg">฿{{ number_format($bundle->bundle_price, 0) }}</span>
                                    @if ($bundle->original_price)
                                        <span
                                            class="text-[10px] text-gray-400 line-through">฿{{ number_format($bundle->original_price, 0) }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Sales & Stock --}}
                            <div class="stack-col stack-data hidden lg:flex">
                                <span class="stack-label">Stats (Sold / Stock)</span>
                                <div class="flex flex-col items-center">
                                    <span class="stack-value font-bold text-gray-700">
                                        {{ number_format($bundle->sold_count) }}
                                    </span>
                                    @if ($bundle->stock_limit)
                                        <div class="w-16 h-1 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                            <div class="h-full bg-blue-400 rounded-full"
                                                style="width:{{ ($bundle->remaining_stock / $bundle->stock_limit) * 100 }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[8px] text-gray-400 font-black mt-0.5 uppercase">{{ $bundle->remaining_stock }}
                                            Left</span>
                                    @else
                                        <span class="text-[8px] text-gray-400 font-black mt-0.5 uppercase">UNLIMITED</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="stack-col stack-data">
                                <span class="stack-label">Status</span>
                                @if ($bundle->isAvailable())
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        {{ __('bundles.available') }}
                                    </span>
                                @elseif(!$bundle->is_active)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-500">
                                        {{ __('bundles.inactive') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-600">
                                        {{ __('bundles.out_of_stock') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Actions Dropdown --}}
                            <div class="stack-actions" onclick="event.stopPropagation()">
                                <div class="ios-dropdown">
                                    <button type="button" class="stack-action-circle">
                                        <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                    </button>
                                    <div class="ios-dropdown-menu">
                                        <button type="button" onclick="toggleBundle({{ $bundle->id }})"
                                            class="ios-dropdown-item">
                                            @if ($bundle->is_active)
                                                <i class="ph ph-toggle-left ios-dropdown-icon text-gray-400"></i>
                                                <span>{{ __('promotions.deactivate') }}</span>
                                            @else
                                                <i class="ph ph-toggle-right ios-dropdown-icon text-green-500"></i>
                                                <span>{{ __('promotions.activate') }}</span>
                                            @endif
                                        </button>
                                        <a href="{{ route('bundles.edit', $bundle) }}" class="ios-dropdown-item">
                                            <i class="ph ph-pencil-simple ios-dropdown-icon text-orange-500"></i>
                                            <span>{{ __('edit') }}</span>
                                        </a>
                                        <div class="h-px bg-gray-100 my-1"></div>
                                        <button type="button" onclick="deleteBundle({{ $bundle->id }})"
                                            class="ios-dropdown-item ios-dropdown-item-danger">
                                            <i class="ph ph-trash ios-dropdown-icon"></i>
                                            <span>{{ __('delete') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($bundles->hasPages())
                    <div class="mt-8">
                        {{ $bundles->appends(request()->query())->links('pagination.apple') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        async function toggleBundle(id) {
            try {
                const response = await fetch(`/bundles/${id}/toggle`, {
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

        function deleteBundle(id) {
            if (confirm('{{ __('bundles.confirm_delete') }}')) {
                const form = document.getElementById('deleteForm');
                form.action = `/bundles/${id}`;
                form.submit();
            }
        }
    </script>
@endpush

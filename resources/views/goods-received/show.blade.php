@extends('layouts.app')

@section('title', $receipt->gr_number)
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('goods-received.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            ← {{ __('gr.title') }}
        </a>
        <span>{{ $receipt->gr_number }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Status Bar --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-bold ph-package text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <span class="badge badge-success text-sm">{{ __('gr.completed') }}</span>
                        <p class="text-sm text-gray-500 mt-1">{{ __('gr.received_date') }}:
                            {{ $receipt->received_date->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ __('gr.total_amount') }}</p>
                    <p class="text-3xl font-black text-green-600">฿{{ number_format($receipt->total_amount, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Receipt Info --}}
        <div class="grid grid-cols-2 gap-6">
            {{-- Supplier Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-buildings text-ios-blue"></i>
                    {{ __('gr.supplier') }}
                </h3>
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-ios-blue to-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($receipt->supplier->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $receipt->supplier->name }}</p>
                        @if ($receipt->supplier->contact_person)
                            <p class="text-sm text-gray-500">{{ $receipt->supplier->contact_person }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Receipt Details --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-info text-ios-blue"></i>
                    {{ __('gr.receipt_info') }}
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">{{ __('gr.po_reference') }}</p>
                        @if ($receipt->purchaseOrder)
                            <a href="{{ route('purchase-orders.show', $receipt->purchaseOrder) }}"
                                class="text-ios-blue font-semibold hover:underline">
                                {{ $receipt->purchaseOrder->po_number }}
                            </a>
                        @else
                            <p class="font-semibold text-gray-900">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500">{{ __('gr.invoice_no') }}</p>
                        <p class="font-semibold text-gray-900">{{ $receipt->invoice_no ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">{{ __('gr.received_by') }}</p>
                        <p class="font-semibold text-gray-900">{{ $receipt->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">{{ __('created_at') }}</p>
                        <p class="font-semibold text-gray-900">{{ $receipt->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <i class="ph ph-package text-ios-blue"></i>
                {{ __('gr.items') }} ({{ $receipt->items->count() }})
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            <th class="pb-3">#</th>
                            <th class="pb-3">{{ __('gr.product') }}</th>
                            <th class="pb-3 text-center">{{ __('gr.received_qty') }}</th>
                            <th class="pb-3 text-center">{{ __('gr.rejected_qty') }}</th>
                            <th class="pb-3 text-right">{{ __('gr.unit_cost') }}</th>
                            <th class="pb-3">{{ __('gr.lot_number') }}</th>
                            <th class="pb-3">{{ __('gr.expiry_date') }}</th>
                            <th class="pb-3 text-right">{{ __('gr.line_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receipt->items as $i => $item)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-500">{{ $i + 1 }}</td>
                                <td class="py-3">
                                    <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->product->sku }}</p>
                                </td>
                                <td class="py-3 text-center font-semibold text-green-600">
                                    {{ number_format($item->received_qty, 0) }}</td>
                                <td class="py-3 text-center">
                                    @if ($item->rejected_qty > 0)
                                        <span
                                            class="text-red-500 font-semibold">{{ number_format($item->rejected_qty, 0) }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">฿{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="py-3">
                                    @if ($item->lot_number)
                                        <span
                                            class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">{{ $item->lot_number }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if ($item->expiry_date)
                                        <span
                                            class="{{ $item->expiry_date->isPast() ? 'text-red-500' : 'text-gray-900' }}">
                                            {{ $item->expiry_date->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right font-bold">฿{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-gray-200">
                        <tr>
                            <td colspan="7" class="py-3 text-right font-bold text-gray-900">{{ __('gr.total_amount') }}
                            </td>
                            <td class="py-3 text-right text-xl font-black text-green-600">
                                ฿{{ number_format($receipt->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if ($receipt->notes)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-2">{{ __('gr.notes') }}</h3>
                <p class="text-gray-600">{{ $receipt->notes }}</p>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex justify-end">
            <a href="{{ route('goods-received.index') }}"
                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                {{ __('back') }}
            </a>
        </div>
    </div>
@endsection

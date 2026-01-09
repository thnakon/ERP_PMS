@extends('layouts.app')

@section('title', $order->po_number)
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('purchase-orders.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            ← {{ __('po.title') }}
        </a>
        <span>{{ $order->po_number }}</span>
    </div>
@endsection

@section('header-actions')
    @if ($order->status === 'draft')
        <form action="{{ route('purchase-orders.send', $order) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-primary flex items-center gap-2 active-scale">
                <i class="ph-bold ph-paper-plane-tilt"></i>
                {{ __('po.send_order') }}
            </button>
        </form>
    @elseif(in_array($order->status, ['sent', 'partial']))
        <a href="{{ route('goods-received.create-from-po', $order) }}"
            class="btn-primary flex items-center gap-2 active-scale">
            <i class="ph-bold ph-package"></i>
            {{ __('po.receive_goods') }}
        </a>
    @endif
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Status Bar --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @switch($order->status)
                        @case('draft')
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                <i class="ph-bold ph-pencil-simple text-gray-500 text-xl"></i>
                            </div>
                            <div>
                                <span class="badge badge-gray text-sm">{{ __('po.draft') }}</span>
                                <p class="text-sm text-gray-500 mt-1">{{ __('po.order_date') }}:
                                    {{ $order->order_date->format('d M Y') }}</p>
                            </div>
                        @break

                        @case('sent')
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="ph-bold ph-paper-plane-tilt text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <span class="badge badge-info text-sm">{{ __('po.sent') }}</span>
                                <p class="text-sm text-gray-500 mt-1">{{ __('po.expected_date') }}:
                                    {{ $order->expected_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                        @break

                        @case('partial')
                            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                                <i class="ph-bold ph-clock text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <span class="badge badge-warning text-sm">{{ __('po.partial') }}</span>
                                <p class="text-sm text-gray-500 mt-1">{{ __('po.remaining') }}:
                                    {{ $order->items->sum('remaining_qty') }} {{ __('items') }}</p>
                            </div>
                        @break

                        @case('completed')
                            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                                <i class="ph-bold ph-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <span class="badge badge-success text-sm">{{ __('po.completed') }}</span>
                                <p class="text-sm text-gray-500 mt-1">{{ $order->completed_at?->format('d M Y H:i') }}</p>
                            </div>
                        @break

                        @case('cancelled')
                            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                                <i class="ph-bold ph-x-circle text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <span class="badge badge-danger text-sm">{{ __('po.cancelled') }}</span>
                            </div>
                        @break
                    @endswitch
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ __('po.grand_total') }}</p>
                    <p class="text-3xl font-black text-ios-blue">฿{{ number_format($order->grand_total, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Order Info --}}
        <div class="grid grid-cols-2 gap-6">
            {{-- Supplier Info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-buildings text-ios-blue"></i>
                    {{ __('po.supplier_info') }}
                </h3>
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-ios-blue to-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($order->supplier->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $order->supplier->name }}</p>
                        @if ($order->supplier->contact_person)
                            <p class="text-sm text-gray-500">{{ $order->supplier->contact_person }}</p>
                        @endif
                        @if ($order->supplier->mobile)
                            <p class="text-sm text-ios-blue">{{ $order->supplier->mobile }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Order Details --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-info text-ios-blue"></i>
                    {{ __('po.order_info') }}
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">{{ __('po.order_date') }}</p>
                        <p class="font-semibold text-gray-900">{{ $order->order_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">{{ __('po.expected_date') }}</p>
                        <p class="font-semibold text-gray-900">{{ $order->expected_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">{{ __('po.created_by') }}</p>
                        <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">{{ __('created_at') }}</p>
                        <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <i class="ph ph-package text-ios-blue"></i>
                {{ __('po.items_list') }} ({{ $order->items->count() }} {{ __('items') }})
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            <th class="pb-3">#</th>
                            <th class="pb-3">{{ __('po.product') }}</th>
                            <th class="pb-3 text-center">{{ __('po.ordered_qty') }}</th>
                            <th class="pb-3 text-center">{{ __('po.received_qty') }}</th>
                            <th class="pb-3 text-right">{{ __('po.unit_cost') }}</th>
                            <th class="pb-3 text-center">{{ __('po.discount') }}</th>
                            <th class="pb-3 text-right">{{ __('po.line_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $i => $item)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-500">{{ $i + 1 }}</td>
                                <td class="py-3">
                                    <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->product->sku }}</p>
                                </td>
                                <td class="py-3 text-center font-semibold">{{ number_format($item->ordered_qty, 0) }}</td>
                                <td class="py-3 text-center">
                                    <span
                                        class="{{ $item->is_fully_received ? 'text-green-600' : 'text-orange-600' }} font-semibold">
                                        {{ number_format($item->received_qty, 0) }}
                                    </span>
                                    @if (!$item->is_fully_received && $item->remaining_qty > 0)
                                        <span class="text-xs text-gray-400">({{ $item->remaining_qty }} left)</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">฿{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="py-3 text-center">{{ $item->discount_percent }}%</td>
                                <td class="py-3 text-right font-bold">฿{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-gray-200">
                        <tr>
                            <td colspan="6" class="py-3 text-right text-gray-500">{{ __('po.subtotal') }}</td>
                            <td class="py-3 text-right font-semibold">฿{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="py-1 text-right text-gray-500">{{ __('po.vat') }}</td>
                            <td class="py-1 text-right font-semibold">฿{{ number_format($order->vat_amount, 2) }}</td>
                        </tr>
                        @if ($order->discount_amount > 0)
                            <tr>
                                <td colspan="6" class="py-1 text-right text-gray-500">{{ __('po.discount_amount') }}
                                </td>
                                <td class="py-1 text-right font-semibold text-red-500">
                                    -฿{{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="6" class="py-3 text-right font-bold text-gray-900">{{ __('po.grand_total') }}
                            </td>
                            <td class="py-3 text-right text-xl font-black text-ios-blue">
                                ฿{{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if ($order->notes)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-2">{{ __('po.notes') }}</h3>
                <p class="text-gray-600">{{ $order->notes }}</p>
            </div>
        @endif

        {{-- Receiving History --}}
        @if ($order->goodsReceived->count() > 0)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-clock-counter-clockwise text-ios-blue"></i>
                    {{ __('po.history') }}
                </h3>
                <div class="space-y-2">
                    @foreach ($order->goodsReceived as $gr)
                        <a href="{{ route('goods-received.show', $gr) }}"
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="ph ph-package text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $gr->gr_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $gr->received_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-gray-400"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex justify-between">
            @if ($order->status === 'draft')
                <div class="flex gap-2">
                    <form action="{{ route('purchase-orders.destroy', $order) }}" method="POST"
                        onsubmit="return confirm('{{ __('delete_item_confirm') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-red-500 hover:bg-red-50 rounded-xl transition flex items-center gap-2">
                            <i class="ph ph-trash"></i>
                            {{ __('delete') }}
                        </button>
                    </form>
                    <a href="{{ route('purchase-orders.edit', $order) }}"
                        class="px-4 py-2 text-ios-blue hover:bg-ios-blue/10 rounded-xl transition flex items-center gap-2">
                        <i class="ph ph-pencil-simple"></i>
                        {{ __('edit') }}
                    </a>
                </div>
            @elseif(in_array($order->status, ['sent', 'partial']))
                <form action="{{ route('purchase-orders.cancel', $order) }}" method="POST"
                    onsubmit="return confirm('{{ __('po.cancel_order') }}?')">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-red-500 hover:bg-red-50 rounded-xl transition flex items-center gap-2">
                        <i class="ph ph-x-circle"></i>
                        {{ __('po.cancel_order') }}
                    </button>
                </form>
            @else
                <div></div>
            @endif
            <a href="{{ route('purchase-orders.index') }}"
                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                {{ __('back') }}
            </a>
        </div>
    </div>
@endsection

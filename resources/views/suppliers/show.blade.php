@extends('layouts.app')

@section('title', $supplier->name)
@section('page-title')
    <div class="flex flex-col">
        <a href="{{ route('suppliers.index') }}"
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; text-decoration: none;"
            class="hover:opacity-80 transition-opacity">
            ← {{ __('suppliers.title') }}
        </a>
        <span>{{ $supplier->name }}</span>
    </div>
@endsection

@section('header-actions')
    <button type="button" onclick="toggleModal(true, 'edit-modal')"
        class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition active-scale flex items-center gap-2">
        <i class="ph ph-pencil"></i>
        {{ __('edit') }}
    </button>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Company Info Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-ios-blue to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h2>
                        @if ($supplier->tax_id)
                            <p class="text-gray-500">Tax ID: {{ $supplier->tax_id }}</p>
                        @endif
                    </div>
                </div>
                @if ($supplier->is_active)
                    <span class="badge badge-success">
                        <span class="badge-dot badge-dot-success"></span>
                        {{ __('suppliers.active') }}
                    </span>
                @else
                    <span class="badge badge-gray">
                        <span class="badge-dot badge-dot-gray"></span>
                        {{ __('suppliers.inactive') }}
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Contact Info --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <i class="ph ph-user text-ios-blue"></i>
                        {{ __('suppliers.contact_info') }}
                    </h3>
                    <div class="space-y-3">
                        @if ($supplier->contact_person)
                            <div class="flex items-center gap-3">
                                <i class="ph ph-user-circle text-gray-400 text-lg"></i>
                                <span class="text-gray-900">{{ $supplier->contact_person }}</span>
                            </div>
                        @endif
                        @if ($supplier->mobile)
                            <div class="flex items-center gap-3">
                                <i class="ph ph-device-mobile text-gray-400 text-lg"></i>
                                <a href="tel:{{ $supplier->mobile }}"
                                    class="text-ios-blue hover:underline">{{ $supplier->mobile }}</a>
                            </div>
                        @endif
                        @if ($supplier->phone)
                            <div class="flex items-center gap-3">
                                <i class="ph ph-phone text-gray-400 text-lg"></i>
                                <a href="tel:{{ $supplier->phone }}"
                                    class="text-ios-blue hover:underline">{{ $supplier->phone }}</a>
                            </div>
                        @endif
                        @if ($supplier->email)
                            <div class="flex items-center gap-3">
                                <i class="ph ph-envelope text-gray-400 text-lg"></i>
                                <a href="mailto:{{ $supplier->email }}"
                                    class="text-ios-blue hover:underline">{{ $supplier->email }}</a>
                            </div>
                        @endif
                        @if ($supplier->line_id)
                            <div class="flex items-center gap-3">
                                <i class="ph ph-chat-circle text-gray-400 text-lg"></i>
                                <span class="text-gray-900">LINE: {{ $supplier->line_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Trade Terms --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <i class="ph ph-handshake text-ios-blue"></i>
                        {{ __('suppliers.trade_terms') }}
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-black text-gray-900">{{ $supplier->credit_term }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ __('suppliers.credit_term') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-black text-gray-900">{{ $supplier->lead_time }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ __('suppliers.lead_time') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-black text-gray-900">{{ number_format($supplier->min_order_qty) }}</p>
                            <p class="text-xs text-gray-500 font-medium">MOQ</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($supplier->address)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-2">{{ __('suppliers.address') }}</h3>
                    <p class="text-gray-600">{{ $supplier->address }}</p>
                </div>
            @endif

            @if ($supplier->bank_name)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ph ph-bank text-ios-blue"></i>
                        {{ __('suppliers.banking_info') }}
                    </h3>
                    <div class="flex items-center gap-6">
                        <div>
                            <p class="text-xs text-gray-500">{{ __('suppliers.bank_name') }}</p>
                            <p class="text-gray-900 font-medium">{{ $supplier->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('suppliers.bank_account_no') }}</p>
                            <p class="text-gray-900 font-medium font-mono">{{ $supplier->bank_account_no }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('suppliers.bank_account_name') }}</p>
                            <p class="text-gray-900 font-medium">{{ $supplier->bank_account_name }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Recent Purchase Orders --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('suppliers.recent_orders') }}</h3>
                <a href="{{ route('purchase-orders.create') }}?supplier={{ $supplier->id }}"
                    class="px-4 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-plus"></i>
                    {{ __('po.add_new') }}
                </a>
            </div>

            @if ($recentPOs->count() > 0)
                <div class="space-y-2">
                    @foreach ($recentPOs as $po)
                        <a href="{{ route('purchase-orders.show', $po) }}"
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-ios-blue/10 flex items-center justify-center">
                                    <i class="ph ph-file-text text-ios-blue"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $po->po_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $po->order_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="badge {{ $po->status_badge_class }}">{{ __('po.' . $po->status) }}</span>
                                <span
                                    class="text-lg font-bold text-gray-900">฿{{ number_format($po->grand_total, 2) }}</span>
                                <i class="ph ph-caret-right text-gray-400"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($recentPOs->hasPages())
                    <div class="mt-4 flex justify-center">
                        {{ $recentPOs->links('pagination.apple') }}
                    </div>
                @endif
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="ph ph-file-dashed text-4xl mb-2"></i>
                    <p>{{ __('po.no_orders') }}</p>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex justify-between">
            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                onsubmit="return confirm('{{ __('delete_item_confirm') }}')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-red-500 hover:bg-red-50 rounded-xl transition flex items-center gap-2">
                    <i class="ph ph-trash"></i>
                    {{ __('delete') }}
                </button>
            </form>
            <a href="{{ route('suppliers.index') }}"
                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                {{ __('back') }}
            </a>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="toggleModal(false, 'edit-modal')"></div>
    <div id="edit-modal-panel" class="modal-panel modal-panel-hidden" style="max-width: 48rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('suppliers.edit') }}</h2>
            <button type="button" onclick="toggleModal(false, 'edit-modal')" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')

            <div class="modal-content max-h-[70vh] overflow-y-auto">
                {{-- Reuse same form structure as index --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="form-label">{{ __('suppliers.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="form-input" value="{{ $supplier->name }}" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.tax_id') }}</label>
                        <input type="text" name="tax_id" class="form-input" value="{{ $supplier->tax_id }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.contact_person') }}</label>
                        <input type="text" name="contact_person" class="form-input"
                            value="{{ $supplier->contact_person }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.phone') }}</label>
                        <input type="text" name="phone" class="form-input" value="{{ $supplier->phone }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.mobile') }}</label>
                        <input type="text" name="mobile" class="form-input" value="{{ $supplier->mobile }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.email') }}</label>
                        <input type="email" name="email" class="form-input" value="{{ $supplier->email }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.line_id') }}</label>
                        <input type="text" name="line_id" class="form-input" value="{{ $supplier->line_id }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.credit_term') }}</label>
                        <input type="number" name="credit_term" class="form-input"
                            value="{{ $supplier->credit_term }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.lead_time') }}</label>
                        <input type="number" name="lead_time" class="form-input" value="{{ $supplier->lead_time }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.min_order_qty') }}</label>
                        <input type="number" name="min_order_qty" class="form-input"
                            value="{{ $supplier->min_order_qty }}" step="0.01">
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">{{ __('suppliers.address') }}</label>
                        <textarea name="address" class="form-input">{{ $supplier->address }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.bank_name') }}</label>
                        <input type="text" name="bank_name" class="form-input" value="{{ $supplier->bank_name }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.bank_account_no') }}</label>
                        <input type="text" name="bank_account_no" class="form-input"
                            value="{{ $supplier->bank_account_no }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('suppliers.bank_account_name') }}</label>
                        <input type="text" name="bank_account_name" class="form-input"
                            value="{{ $supplier->bank_account_name }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="apple-toggle">
                            <input type="checkbox" name="is_active" {{ $supplier->is_active ? 'checked' : '' }}>
                            <span class="toggle-track">
                                <span class="toggle-thumb"></span>
                            </span>
                        </label>
                        <span class="text-sm font-medium text-gray-700">{{ __('suppliers.is_active') }}</span>
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">{{ __('suppliers.notes') }}</label>
                        <textarea name="notes" class="form-input">{{ $supplier->notes }}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="toggleModal(false, 'edit-modal')"
                    class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                    {{ __('cancel') }}
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-check-circle"></i>
                    {{ __('save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

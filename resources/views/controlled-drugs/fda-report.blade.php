@extends('layouts.app')

@section('title', __('controlled_drugs.fda_report'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('controlled_drugs.title') }}
        </p>
        <span>{{ __('controlled_drugs.fda_report') }}</span>
    </div>
@endsection

@section('header-actions')
    <a href="{{ route('controlled-drugs.index') }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-arrow-left"></i>
        {{ __('back') }}
    </a>
    <button onclick="window.print()"
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
        <i class="ph-bold ph-printer"></i>
        {{ __('controlled_drugs.print_report') }}
    </button>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Date Filter --}}
        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border border-white shadow-sm">
            <form action="{{ route('controlled-drugs.fda-report') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label
                        class="text-xs font-semibold text-gray-500 ml-1 mb-1 block">{{ __('controlled_drugs.start_date') }}</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="bg-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-ios-blue/20 outline-none">
                </div>
                <div>
                    <label
                        class="text-xs font-semibold text-gray-500 ml-1 mb-1 block">{{ __('controlled_drugs.end_date') }}</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="bg-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-ios-blue/20 outline-none">
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-ios-blue text-white font-semibold rounded-xl hover:bg-blue-600 transition flex items-center gap-2">
                    <i class="ph ph-calendar"></i>
                    {{ __('controlled_drugs.generate_report') }}
                </button>
            </form>
        </div>

        {{-- Summary Cards (Categories Style) --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all">
                <div class="text-center">
                    <p class="text-2xl font-black text-gray-900">{{ number_format($summary['total_transactions']) }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        {{ __('controlled_drugs.total_transactions') }}</p>
                </div>
            </div>
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all border-l-4 border-l-orange-500">
                <div class="text-center">
                    <p class="text-2xl font-black text-orange-600">{{ number_format($summary['dangerous']['count']) }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        {{ __('controlled_drugs.schedule_dangerous') }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($summary['dangerous']['quantity']) }}
                        {{ __('units') }}</p>
                </div>
            </div>
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all border-l-4 border-l-red-500">
                <div class="text-center">
                    <p class="text-2xl font-black text-red-600">
                        {{ number_format($summary['specially_controlled']['count']) }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        {{ __('controlled_drugs.schedule_specially_controlled') }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($summary['specially_controlled']['quantity']) }}
                        {{ __('units') }}</p>
                </div>
            </div>
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all border-l-4 border-l-purple-700">
                <div class="text-center">
                    <p class="text-2xl font-black text-purple-700">{{ number_format($summary['narcotic']['count']) }}</p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        {{ __('controlled_drugs.schedule_narcotic') }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($summary['narcotic']['quantity']) }}
                        {{ __('units') }}</p>
                </div>
            </div>
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl p-3 border border-white shadow-sm hover-ios transition-all border-l-4 border-l-violet-600">
                <div class="text-center">
                    <p class="text-2xl font-black text-violet-600">{{ number_format($summary['psychotropic']['count']) }}
                    </p>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        {{ __('controlled_drugs.schedule_psychotropic') }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($summary['psychotropic']['quantity']) }}
                        {{ __('units') }}</p>
                </div>
            </div>
        </div>

        {{-- Report Header (Print Only) --}}
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-xl font-bold">{{ __('controlled_drugs.fda_report_title') }}</h1>
            <p class="text-sm text-gray-500">{{ __('controlled_drugs.report_period') }}:
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>

        {{-- Product Movement Summary --}}
        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
            <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                <i class="ph-fill ph-chart-bar text-ios-blue"></i>
                {{ __('controlled_drugs.movement_summary') }}
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left p-3 font-semibold text-gray-600 text-sm rounded-l-xl">
                                {{ __('controlled_drugs.drug') }}</th>
                            <th class="text-center p-3 font-semibold text-gray-600 text-sm">
                                {{ __('controlled_drugs.drug_type') }}</th>
                            <th class="text-center p-3 font-semibold text-gray-600 text-sm">
                                {{ __('controlled_drugs.dispensed_out') }}</th>
                            <th class="text-center p-3 font-semibold text-gray-600 text-sm">
                                {{ __('controlled_drugs.received_in') }}</th>
                            <th class="text-center p-3 font-semibold text-gray-600 text-sm">
                                {{ __('controlled_drugs.disposed') }}</th>
                            <th class="text-center p-3 font-semibold text-gray-600 text-sm rounded-r-xl">
                                {{ __('controlled_drugs.transaction_count') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productMovement as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="font-medium text-gray-900">{{ $item['product']->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $item['product']->sku }}</div>
                                </td>
                                <td class="p-3 text-center">{!! $item['product']->drug_schedule_badge !!}</td>
                                <td class="p-3 text-center font-semibold text-red-600">
                                    {{ number_format($item['total_out']) }}</td>
                                <td class="p-3 text-center font-semibold text-green-600">
                                    {{ number_format($item['total_in']) }}</td>
                                <td class="p-3 text-center font-semibold text-gray-500">
                                    {{ number_format($item['disposed']) }}</td>
                                <td class="p-3 text-center font-medium text-gray-900">{{ $item['transactions'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400">
                                    {{ __('controlled_drugs.no_data_in_period') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detailed Transaction Log --}}
        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm">
            <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                <i class="ph-fill ph-clipboard-text text-purple-500"></i>
                {{ __('controlled_drugs.detailed_log') }}
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left p-3 font-semibold text-gray-600 rounded-l-xl">
                                {{ __('controlled_drugs.date') }}</th>
                            <th class="text-left p-3 font-semibold text-gray-600">{{ __('controlled_drugs.log_number') }}
                            </th>
                            <th class="text-left p-3 font-semibold text-gray-600">{{ __('controlled_drugs.drug') }}</th>
                            <th class="text-center p-3 font-semibold text-gray-600">{{ __('controlled_drugs.quantity') }}
                            </th>
                            <th class="text-left p-3 font-semibold text-gray-600">{{ __('controlled_drugs.recipient') }}
                            </th>
                            <th class="text-left p-3 font-semibold text-gray-600">{{ __('controlled_drugs.id_card') }}</th>
                            <th class="text-left p-3 font-semibold text-gray-600">
                                {{ __('controlled_drugs.prescription_info') }}</th>
                            <th class="text-left p-3 font-semibold text-gray-600 rounded-r-xl">
                                {{ __('controlled_drugs.approved_by') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i') }}</div>
                                </td>
                                <td class="p-3">
                                    <span class="font-mono text-ios-blue">{{ $log->log_number }}</span>
                                </td>
                                <td class="p-3">
                                    <div class="font-medium">{{ $log->product->name }}</div>
                                    <div class="text-xs">{!! $log->product->drug_schedule_badge !!}</div>
                                </td>
                                <td class="p-3 text-center font-bold">{{ number_format($log->quantity, 0) }}</td>
                                <td class="p-3">{{ $log->customer_name }}</td>
                                <td class="p-3 font-mono text-xs">{{ $log->customer_id_card ?: '-' }}</td>
                                <td class="p-3">
                                    @if ($log->doctor_name)
                                        <div class="text-sm">{{ $log->doctor_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->prescription_number }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3">{{ $log->approvedBy?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-gray-400">
                                    {{ __('controlled_drugs.no_data_in_period') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Legal Notice --}}
        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white shadow-sm bg-gray-50">
            <div class="flex items-start gap-4">
                <i class="ph-fill ph-stamp text-gray-400 text-3xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">{{ __('controlled_drugs.legal_certification') }}</h4>
                    <p class="text-sm text-gray-600">
                        {{ __('controlled_drugs.certification_text') }}
                    </p>
                    <div class="mt-4 grid grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="h-20 border-b border-dashed border-gray-300 mb-2"></div>
                            <p class="text-sm text-gray-500">{{ __('controlled_drugs.pharmacist_signature') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ __('controlled_drugs.signature_date') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="h-20 border-b border-dashed border-gray-300 mb-2"></div>
                            <p class="text-sm text-gray-500">{{ __('controlled_drugs.authorized_signature') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ __('controlled_drugs.signature_date') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {

                .sidebar,
                header,
                .header-actions,
                form {
                    display: none !important;
                }

                body {
                    background: white !important;
                }

                .bg-white\/80,
                .card-ios {
                    box-shadow: none !important;
                    border: 1px solid #e5e7eb !important;
                }
            }
        </style>
    @endpush
@endsection

@extends('layouts.app')

@section('title', __('hardware.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('settings') }}
        </p>
        <span>{{ __('hardware.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Page Description --}}
        <div class="flex items-center gap-4 mb-2">
            <div class="w-14 h-14 rounded-2xl bg-cyan-500 flex items-center justify-center shadow-lg">
                <i class="ph-fill ph-printer text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ __('hardware.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('hardware.subtitle') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Printer Settings --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center">
                        <i class="ph-fill ph-printer text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('hardware.printer') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('hardware.printer_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.hardware.printer') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Enable Printer Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('hardware.printer_enabled') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.printer_enabled_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="printer_enabled" class="sr-only peer"
                                {{ $printerSettings['printer_enabled'] ?? false ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                            </div>
                        </label>
                    </div>

                    {{-- Connection Type --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.printer_type') }}</label>
                        <select name="printer_type" class="input-ios">
                            <option value="network"
                                {{ ($printerSettings['printer_type'] ?? '') === 'network' ? 'selected' : '' }}>
                                {{ __('hardware.printer_type_network') }}
                            </option>
                            <option value="usb"
                                {{ ($printerSettings['printer_type'] ?? '') === 'usb' ? 'selected' : '' }}>
                                {{ __('hardware.printer_type_usb') }}
                            </option>
                        </select>
                    </div>

                    {{-- IP and Port --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.printer_ip') }}</label>
                            <div class="relative group">
                                <i
                                    class="ph ph-wifi-high absolute left-4 top-1/2 -translate-y-1/2 text-xl text-gray-400 group-focus-within:text-ios-blue"></i>
                                <input type="text" name="printer_ip"
                                    value="{{ $printerSettings['printer_ip'] ?? '192.168.1.100' }}"
                                    class="input-ios has-icon font-mono"
                                    placeholder="{{ __('hardware.printer_ip_placeholder') }}">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.printer_port') }}</label>
                            <input type="number" name="printer_port"
                                value="{{ $printerSettings['printer_port'] ?? 9100 }}" class="input-ios font-mono"
                                placeholder="{{ __('hardware.printer_port_placeholder') }}">
                        </div>
                    </div>

                    {{-- Paper Size --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.printer_paper_size') }}</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="printer_paper_size" value="58" class="peer sr-only"
                                    {{ ($printerSettings['printer_paper_size'] ?? '80') === '58' ? 'checked' : '' }}>
                                <div
                                    class="p-4 rounded-xl border-2 border-gray-200 bg-white peer-checked:border-ios-blue peer-checked:bg-blue-50 transition-all text-center">
                                    <i class="ph ph-receipt text-2xl text-gray-400 peer-checked:text-ios-blue"></i>
                                    <p class="font-bold text-gray-900 mt-1">58mm</p>
                                    <p class="text-xs text-gray-500">{{ __('hardware.printer_paper_58mm') }}</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="printer_paper_size" value="80" class="peer sr-only"
                                    {{ ($printerSettings['printer_paper_size'] ?? '80') === '80' ? 'checked' : '' }}>
                                <div
                                    class="p-4 rounded-xl border-2 border-gray-200 bg-white peer-checked:border-ios-blue peer-checked:bg-blue-50 transition-all text-center">
                                    <i class="ph ph-receipt text-2xl text-gray-400 peer-checked:text-ios-blue"></i>
                                    <p class="font-bold text-gray-900 mt-1">80mm</p>
                                    <p class="text-xs text-gray-500">{{ __('hardware.printer_paper_80mm') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Number of Copies --}}
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.printer_copies') }}</label>
                        <select name="printer_copies" class="input-ios">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}"
                                    {{ ($printerSettings['printer_copies'] ?? 1) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i === 1 ? 'copy' : 'copies' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Auto Print Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ __('hardware.printer_auto_print') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.printer_auto_print_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="printer_auto_print" class="sr-only peer"
                                {{ $printerSettings['printer_auto_print'] ?? false ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                            </div>
                        </label>
                    </div>

                    {{-- Cut Paper Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ __('hardware.printer_cut_paper') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.printer_cut_paper_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="printer_cut_paper" class="sr-only peer"
                                {{ $printerSettings['printer_cut_paper'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                            </div>
                        </label>
                    </div>

                    {{-- Beep Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ __('hardware.printer_beep') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.printer_beep_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="printer_beep" class="sr-only peer"
                                {{ $printerSettings['printer_beep'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                            </div>
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="testPrinter()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="ph ph-plugs-connected"></i>
                            {{ __('hardware.printer_test') }}
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="ph ph-check"></i>
                            {{ __('hardware.save_settings') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Cash Drawer Settings --}}
            <div class="card-ios p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">
                        <i class="ph-fill ph-coins text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('hardware.cash_drawer') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('hardware.cash_drawer_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.hardware.cash-drawer') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Enable Cash Drawer Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-semibold text-gray-900">{{ __('hardware.cash_drawer_enabled') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.cash_drawer_enabled_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="cash_drawer_enabled" class="sr-only peer"
                                {{ $cashDrawerSettings['cash_drawer_enabled'] ?? false ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                            </div>
                        </label>
                    </div>

                    {{-- Connected via Printer --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ __('hardware.cash_drawer_connected_to_printer') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.cash_drawer_connected_to_printer_desc') }}
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="cash_drawer_connected_to_printer" class="sr-only peer"
                                {{ $cashDrawerSettings['cash_drawer_connected_to_printer'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                            </div>
                        </label>
                    </div>

                    {{-- Auto Open Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <p class="font-medium text-gray-900">{{ __('hardware.cash_drawer_auto_open') }}</p>
                            <p class="text-xs text-gray-500">{{ __('hardware.cash_drawer_auto_open_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="cash_drawer_auto_open" class="sr-only peer"
                                {{ $cashDrawerSettings['cash_drawer_auto_open'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                            </div>
                        </label>
                    </div>

                    {{-- ESC/POS Command --}}
                    <div class="space-y-1.5">
                        <label
                            class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.cash_drawer_command') }}</label>
                        <input type="text" name="cash_drawer_command"
                            value="{{ $cashDrawerSettings['cash_drawer_command'] ?? '\x1b\x70\x00\x19\xfa' }}"
                            class="input-ios font-mono text-sm">
                        <p class="text-xs text-gray-400 ml-1">{{ __('hardware.cash_drawer_command_desc') }}</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="testCashDrawer()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="ph ph-plugs-connected"></i>
                            {{ __('hardware.cash_drawer_test') }}
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="ph ph-check"></i>
                            {{ __('hardware.save_settings') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Barcode Scanner Settings --}}
            <div class="card-ios p-6 lg:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-barcode text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('hardware.barcode_scanner') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('hardware.barcode_scanner_subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('settings.hardware.barcode-scanner') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Enable Scanner Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('hardware.barcode_scanner_enabled') }}</p>
                                <p class="text-xs text-gray-500">{{ __('hardware.barcode_scanner_enabled_desc') }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="barcode_scanner_enabled" class="sr-only peer"
                                    {{ $barcodeScannerSettings['barcode_scanner_enabled'] ?? true ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500">
                                </div>
                            </label>
                        </div>

                        {{-- Suffix Character --}}
                        <div class="space-y-1.5">
                            <label
                                class="text-xs font-semibold text-gray-500 ml-1">{{ __('hardware.barcode_scanner_suffix') }}</label>
                            <select name="barcode_scanner_suffix" class="input-ios">
                                <option value="\r\n"
                                    {{ ($barcodeScannerSettings['barcode_scanner_suffix'] ?? '\r\n') === '\r\n' ? 'selected' : '' }}>
                                    Enter (CR+LF)
                                </option>
                                <option value="\r"
                                    {{ ($barcodeScannerSettings['barcode_scanner_suffix'] ?? '') === '\r' ? 'selected' : '' }}>
                                    Enter (CR)
                                </option>
                                <option value="\t"
                                    {{ ($barcodeScannerSettings['barcode_scanner_suffix'] ?? '') === '\t' ? 'selected' : '' }}>
                                    Tab
                                </option>
                            </select>
                            <p class="text-xs text-gray-400 ml-1">{{ __('hardware.barcode_scanner_suffix_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit"
                            class="px-6 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
                            <i class="ph ph-check"></i>
                            {{ __('hardware.save_settings') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function testPrinter() {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> {{ __('hardware.testing') }}';
            btn.disabled = true;

            try {
                const response = await fetch('{{ route('settings.hardware.test-printer') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('{{ __('hardware.printer_connection_failed') }}', 'error');
            }

            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }

        async function testCashDrawer() {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="ph ph-spinner-gap animate-spin"></i> {{ __('hardware.testing') }}';
            btn.disabled = true;

            try {
                const response = await fetch('{{ route('settings.hardware.test-cash-drawer') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                showToast(data.message, data.success ? 'success' : 'error');
            } catch (error) {
                showToast('Test failed', 'error');
            }

            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    </script>
@endpush

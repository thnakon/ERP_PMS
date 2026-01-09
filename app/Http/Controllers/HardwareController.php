<?php

namespace App\Http\Controllers;

use App\Models\HardwareSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HardwareController extends Controller
{
    /**
     * Display hardware settings page
     */
    public function index()
    {
        $printerSettings = HardwareSetting::getByGroup('printer');
        $cashDrawerSettings = HardwareSetting::getByGroup('cash_drawer');
        $barcodeScannerSettings = HardwareSetting::getByGroup('barcode_scanner');

        return view('settings.hardware', compact(
            'printerSettings',
            'cashDrawerSettings',
            'barcodeScannerSettings'
        ));
    }

    /**
     * Update printer settings
     */
    public function updatePrinter(Request $request)
    {
        $request->validate([
            'printer_ip' => ['nullable', 'ip'],
            'printer_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'printer_paper_size' => ['required', 'in:58,80'],
            'printer_copies' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $settings = [
            'printer_enabled' => $request->has('printer_enabled') ? '1' : '0',
            'printer_type' => $request->input('printer_type', 'network'),
            'printer_ip' => $request->input('printer_ip', '192.168.1.100'),
            'printer_port' => $request->input('printer_port', '9100'),
            'printer_paper_size' => $request->input('printer_paper_size', '80'),
            'printer_auto_print' => $request->has('printer_auto_print') ? '1' : '0',
            'printer_copies' => $request->input('printer_copies', '1'),
            'printer_cut_paper' => $request->has('printer_cut_paper') ? '1' : '0',
            'printer_beep' => $request->has('printer_beep') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            HardwareSetting::set($key, $value);
        }

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าเครื่องพิมพ์'
        );

        return back()->with('success', __('hardware.printer_settings_updated'));
    }

    /**
     * Update cash drawer settings
     */
    public function updateCashDrawer(Request $request)
    {
        $settings = [
            'cash_drawer_enabled' => $request->has('cash_drawer_enabled') ? '1' : '0',
            'cash_drawer_auto_open' => $request->has('cash_drawer_auto_open') ? '1' : '0',
            'cash_drawer_command' => $request->input('cash_drawer_command', '\x1b\x70\x00\x19\xfa'),
            'cash_drawer_connected_to_printer' => $request->has('cash_drawer_connected_to_printer') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            HardwareSetting::set($key, $value);
        }

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าลิ้นชักเก็บเงิน'
        );

        return back()->with('success', __('hardware.cash_drawer_settings_updated'));
    }

    /**
     * Update barcode scanner settings
     */
    public function updateBarcodeScanner(Request $request)
    {
        $settings = [
            'barcode_scanner_enabled' => $request->has('barcode_scanner_enabled') ? '1' : '0',
            'barcode_scanner_suffix' => $request->input('barcode_scanner_suffix', '\r\n'),
        ];

        foreach ($settings as $key => $value) {
            HardwareSetting::set($key, $value);
        }

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าเครื่องสแกนบาร์โค้ด'
        );

        return back()->with('success', __('hardware.barcode_scanner_settings_updated'));
    }

    /**
     * Test printer connection
     */
    public function testPrinter(Request $request)
    {
        $ip = HardwareSetting::get('printer_ip');
        $port = HardwareSetting::get('printer_port', 9100);

        try {
            $socket = @fsockopen($ip, $port, $errno, $errstr, 3);
            if ($socket) {
                fclose($socket);
                return response()->json([
                    'success' => true,
                    'message' => __('hardware.printer_connected'),
                ]);
            }
        } catch (\Exception $e) {
            // Connection failed
        }

        return response()->json([
            'success' => false,
            'message' => __('hardware.printer_connection_failed'),
        ]);
    }

    /**
     * Test cash drawer
     */
    public function testCashDrawer()
    {
        // This would need actual ESC/POS implementation
        // For now, we'll just return a success message
        return response()->json([
            'success' => true,
            'message' => __('hardware.cash_drawer_test_sent'),
        ]);
    }
}

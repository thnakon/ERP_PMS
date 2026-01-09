<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $storeSettings = Setting::getByGroup('store');
        $financialSettings = Setting::getByGroup('financial');
        $notificationSettings = Setting::getByGroup('notifications');
        $loyaltySettings = Setting::getByGroup('loyalty');
        $receiptSettings = Setting::getByGroup('receipt');

        return view('settings.index', compact(
            'storeSettings',
            'financialSettings',
            'notificationSettings',
            'loyaltySettings',
            'receiptSettings'
        ));
    }

    /**
     * Update store settings
     */
    public function updateStore(Request $request)
    {
        $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_address' => ['nullable', 'string', 'max:500'],
            'store_phone' => ['nullable', 'string', 'max:20'],
            'store_email' => ['nullable', 'email', 'max:255'],
            'store_tax_id' => ['nullable', 'string', 'max:50'],
            'store_logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'store_favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:512'],
        ]);

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('store_logo')->store('logos', 'public');
            Setting::set('store_logo', $path);
        }

        // Handle logo removal
        if ($request->remove_logo === '1') {
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('store_logo', '');
        }

        // Handle favicon upload
        if ($request->hasFile('store_favicon')) {
            // Delete old favicon
            $oldFavicon = Setting::get('store_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $path = $request->file('store_favicon')->store('favicons', 'public');
            Setting::set('store_favicon', $path);
        }

        // Handle favicon removal
        if ($request->remove_favicon === '1') {
            $oldFavicon = Setting::get('store_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::set('store_favicon', '');
        }

        Setting::updateMultiple([
            'store_name' => $request->input('store_name'),
            'store_address' => $request->input('store_address', ''),
            'store_phone' => $request->input('store_phone', ''),
            'store_email' => $request->input('store_email', ''),
            'store_tax_id' => $request->input('store_tax_id', ''),
        ], 'store');

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตข้อมูลร้านค้า'
        );

        return back()->with('success', __('settings.store_updated'));
    }

    /**
     * Update financial settings
     */
    public function updateFinancial(Request $request)
    {
        $request->validate([
            'vat_rate' => ['required', 'integer', 'min:0', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'currency_symbol' => ['required', 'string', 'max:5'],
        ]);

        Setting::updateMultiple([
            'vat_rate' => $request->input('vat_rate'),
            'currency' => $request->input('currency'),
            'currency_symbol' => $request->input('currency_symbol'),
            'price_includes_vat' => $request->has('price_includes_vat') ? '1' : '0',
        ], 'financial');

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าการเงิน'
        );

        return back()->with('success', __('settings.financial_updated'));
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'low_stock_threshold' => ['required', 'integer', 'min:1', 'max:1000'],
            'expiry_alert_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        Setting::updateMultiple([
            'low_stock_threshold' => $request->input('low_stock_threshold'),
            'expiry_alert_days' => $request->input('expiry_alert_days'),
            'enable_low_stock_alert' => $request->has('enable_low_stock_alert') ? '1' : '0',
            'enable_expiry_alert' => $request->has('enable_expiry_alert') ? '1' : '0',
        ], 'notifications');

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าการแจ้งเตือน'
        );

        return back()->with('success', __('settings.notifications_updated'));
    }

    /**
     * Update loyalty settings
     */
    public function updateLoyalty(Request $request)
    {
        $request->validate([
            'points_earn_rate' => ['required', 'integer', 'min:1', 'max:100'],
            'points_redeem_rate' => ['required', 'integer', 'min:1', 'max:1000'],
            'points_min_redeem' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        Setting::updateMultiple([
            'loyalty_enabled' => $request->has('loyalty_enabled') ? '1' : '0',
            'points_earn_rate' => $request->input('points_earn_rate'),
            'points_redeem_rate' => $request->input('points_redeem_rate'),
            'points_min_redeem' => $request->input('points_min_redeem'),
        ], 'loyalty');

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าระบบสมาชิก'
        );

        return back()->with('success', __('settings.loyalty_updated'));
    }

    /**
     * Update receipt settings
     */
    public function updateReceipt(Request $request)
    {
        $request->validate([
            'receipt_header' => ['nullable', 'string', 'max:500'],
            'receipt_footer' => ['nullable', 'string', 'max:500'],
            'receipt_thank_you' => ['nullable', 'string', 'max:100'],
            'receipt_return_policy' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::updateMultiple([
            'receipt_header' => $request->input('receipt_header', ''),
            'receipt_footer' => $request->input('receipt_footer', ''),
            'receipt_thank_you' => $request->input('receipt_thank_you', 'ขอบคุณครับ!'),
            'receipt_return_policy' => $request->input('receipt_return_policy', 'สามารถคืนสินค้าได้ภายใน 7 วัน พร้อมใบเสร็จ'),
            'receipt_show_tax' => $request->has('receipt_show_tax') ? '1' : '0',
            'receipt_show_logo' => $request->has('receipt_show_logo') ? '1' : '0',
            'receipt_show_barcode' => $request->has('receipt_show_barcode') ? '1' : '0',
            'receipt_show_store_info' => $request->has('receipt_show_store_info') ? '1' : '0',
        ], 'receipt');

        ActivityLog::log(
            action: 'update',
            module: 'Settings',
            description: 'อัปเดตการตั้งค่าใบเสร็จ'
        );

        return back()->with('success', __('settings.receipt_updated'));
    }
}

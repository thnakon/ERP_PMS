<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Customer;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display the notifications dashboard.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        // Stats
        $stats = [
            'expiring_soon' => $this->getExpiringProducts()->count(),
            'low_stock' => $this->getLowStockProducts()->count(),
            'refill_reminders' => $this->getRefillReminders()->count(),
            'total' => 0,
        ];
        $stats['total'] = $stats['expiring_soon'] + $stats['low_stock'] + $stats['refill_reminders'];

        // Get notifications based on filter
        $notifications = collect();

        if ($filter === 'all' || $filter === 'expiring') {
            $notifications = $notifications->concat($this->getExpiringProductsAsNotifications());
        }

        if ($filter === 'all' || $filter === 'low_stock') {
            $notifications = $notifications->concat($this->getLowStockAsNotifications());
        }

        if ($filter === 'all' || $filter === 'refill') {
            $notifications = $notifications->concat($this->getRefillRemindersAsNotifications());
        }

        // Sort by priority and date
        $notifications = $notifications->sortByDesc('priority')->values();

        return view('notifications.index', compact('notifications', 'stats', 'filter'));
    }

    /**
     * Get products expiring soon (within 90 days).
     */
    private function getExpiringProducts()
    {
        return ProductLot::where('expiry_date', '<=', Carbon::now()->addDays(90))
            ->where('expiry_date', '>', Carbon::now())
            ->where('quantity', '>', 0)
            ->with('product')
            ->orderBy('expiry_date')
            ->get();
    }

    /**
     * Get products with low stock.
     */
    private function getLowStockProducts()
    {
        return Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->orderBy('stock_qty')
            ->get();
    }

    /**
     * Get customers due for refill.
     */
    private function getRefillReminders()
    {
        // Customers with prescriptions that should be refilled
        return Prescription::where('status', 'dispensed')
            ->whereColumn('refill_count', '<', 'refill_allowed')
            ->where('next_refill_date', '<=', Carbon::now()->addDays(7))
            ->where('next_refill_date', '>=', Carbon::now()->subDays(3))
            ->with(['customer', 'items.product'])
            ->get();
    }

    /**
     * Convert expiring products to notification format.
     */
    private function getExpiringProductsAsNotifications()
    {
        return $this->getExpiringProducts()->map(function ($lot) {
            $daysUntilExpiry = Carbon::now()->diffInDays($lot->expiry_date, false);
            $priority = $daysUntilExpiry <= 30 ? 'high' : ($daysUntilExpiry <= 60 ? 'medium' : 'low');

            return [
                'id' => 'exp_' . $lot->id,
                'type' => 'expiring',
                'priority' => $priority === 'high' ? 3 : ($priority === 'medium' ? 2 : 1),
                'priority_label' => $priority,
                'icon' => 'ph-calendar-x',
                'icon_bg' => $priority === 'high' ? 'bg-red-100' : ($priority === 'medium' ? 'bg-orange-100' : 'bg-yellow-100'),
                'icon_color' => $priority === 'high' ? 'text-red-500' : ($priority === 'medium' ? 'text-orange-500' : 'text-yellow-500'),
                'title' => $lot->product->name,
                'subtitle' => __('notifications.lot') . ': ' . $lot->lot_number,
                'message' => trans_choice('notifications.expires_in_days', $daysUntilExpiry, ['days' => $daysUntilExpiry]),
                'detail' => __('notifications.qty_remaining') . ': ' . number_format($lot->quantity),
                'date' => $lot->expiry_date,
                'link' => route('products.show', $lot->product_id),
            ];
        });
    }

    /**
     * Convert low stock products to notification format.
     */
    private function getLowStockAsNotifications()
    {
        return $this->getLowStockProducts()->map(function ($product) {
            $stockPercent = $product->min_stock > 0 ? ($product->stock_qty / $product->min_stock) * 100 : 0;
            $priority = $stockPercent <= 25 ? 'high' : ($stockPercent <= 50 ? 'medium' : 'low');

            return [
                'id' => 'stock_' . $product->id,
                'type' => 'low_stock',
                'priority' => $priority === 'high' ? 3 : ($priority === 'medium' ? 2 : 1),
                'priority_label' => $priority,
                'icon' => 'ph-package',
                'icon_bg' => $priority === 'high' ? 'bg-red-100' : ($priority === 'medium' ? 'bg-orange-100' : 'bg-blue-100'),
                'icon_color' => $priority === 'high' ? 'text-red-500' : ($priority === 'medium' ? 'text-orange-500' : 'text-blue-500'),
                'title' => $product->name,
                'subtitle' => $product->sku,
                'message' => __('notifications.stock_below_minimum'),
                'detail' => __('notifications.current_stock') . ': ' . number_format($product->stock_qty) . ' / ' . number_format($product->min_stock),
                'date' => now(),
                'link' => route('products.show', $product->id),
            ];
        });
    }

    /**
     * Convert refill reminders to notification format.
     */
    private function getRefillRemindersAsNotifications()
    {
        return $this->getRefillReminders()->map(function ($prescription) {
            $daysUntil = Carbon::now()->diffInDays($prescription->next_refill_date, false);
            $isOverdue = $daysUntil < 0;

            return [
                'id' => 'refill_' . $prescription->id,
                'type' => 'refill',
                'priority' => $isOverdue ? 3 : ($daysUntil <= 3 ? 2 : 1),
                'priority_label' => $isOverdue ? 'high' : ($daysUntil <= 3 ? 'medium' : 'low'),
                'icon' => 'ph-user-circle',
                'icon_bg' => $isOverdue ? 'bg-red-100' : 'bg-green-100',
                'icon_color' => $isOverdue ? 'text-red-500' : 'text-green-500',
                'title' => $prescription->customer->name ?? __('notifications.unknown_customer'),
                'subtitle' => $prescription->customer->phone ?? '',
                'message' => $isOverdue
                    ? __('notifications.refill_overdue')
                    : trans_choice('notifications.refill_due_in', $daysUntil, ['days' => $daysUntil]),
                'detail' => __('notifications.prescription') . ': ' . $prescription->prescription_number,
                'date' => $prescription->next_refill_date,
                'link' => route('prescriptions.show', $prescription->id),
            ];
        });
    }

    /**
     * Mark notification as read/dismissed.
     */
    public function dismiss(Request $request, $type, $id)
    {
        // In a full implementation, this would update a notifications table
        // For now, we'll just redirect back
        return back()->with('success', __('notifications.dismissed'));
    }

    /**
     * Get notification count for header badge.
     */
    public function count()
    {
        $expiring = $this->getExpiringProducts()->count();
        $lowStock = $this->getLowStockProducts()->count();
        $refill = $this->getRefillReminders()->count();

        return response()->json([
            'total' => $expiring + $lowStock + $refill,
            'expiring' => $expiring,
            'low_stock' => $lowStock,
            'refill' => $refill,
        ]);
    }

    /**
     * Display notification settings page.
     */
    public function settings()
    {
        // Get current settings from database or config
        $settings = [
            'enable_push' => config('notifications.push', true),
            'enable_email' => config('notifications.email', true),
            'enable_line' => config('notifications.line', false),
            'expiry_days_before' => config('notifications.expiry_days', 90),
            'refill_days_before' => config('notifications.refill_days', 7),
            // Email settings
            'mail_from' => config('mail.from.address', 'obounerp@gmail.com'),
            'mail_from_name' => config('mail.from.name', 'OBOUN ERP'),
            'mail_to' => config('notifications.mail_to', ''),
            'mail_password' => '', // Don't expose password
            // LINE settings
            'line_channel_token' => config('services.line_messaging.channel_access_token') ? '••••••••' : '',
            'line_channel_secret' => config('services.line_messaging.channel_secret') ? '••••••••' : '',
            'line_user_id' => config('services.line_messaging.user_id', ''),
        ];

        return view('notifications.settings', compact('settings'));
    }

    /**
     * Save notification settings.
     */
    public function saveSettings(Request $request)
    {
        // In a full implementation, this would update settings in database
        // For now, just redirect with success message
        return redirect()
            ->route('notifications.settings')
            ->with('success', __('notifications.settings_saved'));
    }

    /**
     * Test Line Messaging API connection.
     */
    public function testLine(Request $request)
    {
        $channelToken = $request->input('channel_token');
        $userId = $request->input('user_id');

        if (!$channelToken || !$userId) {
            return response()->json([
                'success' => false,
                'message' => __('notifications.line_token_required'),
            ]);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $channelToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.line.me/v2/bot/message/push', [
                'to' => $userId,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => "✅ ทดสอบการเชื่อมต่อ LINE Messaging API\n━━━━━━━━━━━━━━\nระบบ OBOUN ERP\nเชื่อมต่อสำเร็จ!\n━━━━━━━━━━━━━━\nเวลา: " . now()->format('d/m/Y H:i:s'),
                    ]
                ]
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => __('notifications.line_test_success'),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('notifications.line_test_failed') . ' (HTTP ' . $response->status() . ')',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Test Email connection.
     */
    public function testEmail(Request $request)
    {
        $mailTo = $request->input('mail_to');

        if (!$mailTo) {
            return response()->json([
                'success' => false,
                'message' => __('notifications.email_required'),
            ]);
        }

        try {
            \Illuminate\Support\Facades\Mail::raw(
                "✅ ทดสอบการส่งอีเมลจาก OBOUN ERP\n\n" .
                    "━━━━━━━━━━━━━━\n" .
                    "ระบบแจ้งเตือนทำงานปกติ!\n" .
                    "━━━━━━━━━━━━━━\n\n" .
                    "เวลา: " . now()->format('d/m/Y H:i:s') . "\n\n" .
                    "🏥 OBOUN ERP",
                function ($message) use ($mailTo) {
                    $message->to($mailTo)
                        ->subject('✅ ทดสอบการแจ้งเตือน - OBOUN ERP');
                }
            );

            return response()->json([
                'success' => true,
                'message' => __('notifications.email_test_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}

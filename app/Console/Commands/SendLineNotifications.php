<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Prescription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendLineNotifications extends Command
{
    protected $signature = 'notifications:send-line {--type=all : Type of notification (all, daily, low_stock, expiring, refill)}';
    protected $description = 'Send LINE notifications for alerts';

    protected $channelToken;
    protected $userId;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->channelToken = config('services.line_messaging.channel_access_token');
        $this->userId = config('services.line_messaging.user_id');

        if (!$this->channelToken || !$this->userId) {
            $this->error('LINE Messaging API not configured. Please set LINE_CHANNEL_ACCESS_TOKEN and LINE_USER_ID in .env');
            return 1;
        }

        $type = $this->option('type');

        switch ($type) {
            case 'daily':
                $this->sendDailySummary();
                break;
            case 'low_stock':
                $this->sendLowStockAlerts();
                break;
            case 'expiring':
                $this->sendExpiryAlerts();
                break;
            case 'refill':
                $this->sendRefillReminders();
                break;
            default:
                $this->sendAllAlerts();
        }

        return 0;
    }

    /**
     * Send all alerts
     */
    protected function sendAllAlerts()
    {
        $this->info('Checking for alerts...');

        $lowStock = $this->sendLowStockAlerts();
        $expiring = $this->sendExpiryAlerts();
        $refill = $this->sendRefillReminders();

        $this->info("Sent: {$lowStock} low stock, {$expiring} expiring, {$refill} refill alerts");
    }

    /**
     * Send daily summary
     */
    protected function sendDailySummary()
    {
        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->count();

        $expiringCount = ProductLot::where('expiry_date', '<=', Carbon::now()->addDays(90))
            ->where('expiry_date', '>', Carbon::now())
            ->where('quantity', '>', 0)
            ->count();

        $refillCount = Prescription::where('status', 'dispensed')
            ->whereColumn('refill_count', '<', 'refill_allowed')
            ->where('next_refill_date', '<=', Carbon::now()->addDays(7))
            ->where('next_refill_date', '>=', Carbon::now()->subDays(3))
            ->count();

        $total = $lowStockCount + $expiringCount + $refillCount;

        $message = "📊 สรุปรายวัน - " . now()->format('d/m/Y') . "\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "🔔 การแจ้งเตือนทั้งหมด: {$total}\n";
        $message .= "⏰ ใกล้หมดอายุ: {$expiringCount}\n";
        $message .= "📦 สต๊อกต่ำ: {$lowStockCount}\n";
        $message .= "💊 ลูกค้ารับยา: {$refillCount}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "🏥 OBOUN ERP";

        if ($this->sendLineMessage($message)) {
            $this->info('Daily summary sent successfully');
        }
    }

    /**
     * Send low stock alerts
     */
    protected function sendLowStockAlerts(): int
    {
        $products = Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->where('stock_qty', '>', 0) // Only alert if not completely out
            ->orderBy('stock_qty')
            ->limit(5) // Limit to avoid spam
            ->get();

        $sent = 0;
        foreach ($products as $product) {
            $message = "🚨 แจ้งเตือนสต๊อกต่ำ\n";
            $message .= "━━━━━━━━━━━━━━\n";
            $message .= "📦 สินค้า: {$product->name}\n";
            $message .= "📊 คงเหลือ: " . number_format($product->stock_qty) . " {$product->unit}\n";
            $message .= "⚠️ ขั้นต่ำ: " . number_format($product->min_stock) . " {$product->unit}\n";
            $message .= "━━━━━━━━━━━━━━\n";
            $message .= "กรุณาสั่งซื้อเพิ่มเติม\n\n";
            $message .= "🏥 OBOUN ERP";

            if ($this->sendLineMessage($message)) {
                $sent++;
            }

            // Small delay to avoid rate limiting
            usleep(100000); // 100ms
        }

        return $sent;
    }

    /**
     * Send expiry alerts
     */
    protected function sendExpiryAlerts(): int
    {
        $lots = ProductLot::where('expiry_date', '<=', Carbon::now()->addDays(30)) // Only urgent ones
            ->where('expiry_date', '>', Carbon::now())
            ->where('quantity', '>', 0)
            ->with('product')
            ->orderBy('expiry_date')
            ->limit(5)
            ->get();

        $sent = 0;
        foreach ($lots as $lot) {
            $daysLeft = Carbon::now()->diffInDays($lot->expiry_date, false);
            $urgency = $daysLeft <= 7 ? '🔴 ด่วนมาก' : ($daysLeft <= 14 ? '🟠 ด่วน' : '🟡 เตือน');

            $message = "⏰ แจ้งเตือนใกล้หมดอายุ\n";
            $message .= "━━━━━━━━━━━━━━\n";
            $message .= "{$urgency}\n";
            $message .= "💊 สินค้า: {$lot->product->name}\n";
            $message .= "🏷️ Lot: {$lot->lot_number}\n";
            $message .= "📅 หมดอายุ: " . $lot->expiry_date->format('d/m/Y') . "\n";
            $message .= "⏳ เหลือ: {$daysLeft} วัน\n";
            $message .= "📦 จำนวน: " . number_format($lot->quantity) . "\n";
            $message .= "━━━━━━━━━━━━━━\n";
            $message .= "🏥 OBOUN ERP";

            if ($this->sendLineMessage($message)) {
                $sent++;
            }

            usleep(100000);
        }

        return $sent;
    }

    /**
     * Send refill reminders
     */
    protected function sendRefillReminders(): int
    {
        $prescriptions = Prescription::where('status', 'dispensed')
            ->whereColumn('refill_count', '<', 'refill_allowed')
            ->where('next_refill_date', '<=', Carbon::now()->addDays(3)) // Due within 3 days
            ->where('next_refill_date', '>=', Carbon::now()->subDays(1))
            ->with('customer')
            ->limit(5)
            ->get();

        $sent = 0;
        foreach ($prescriptions as $prescription) {
            $customerName = $prescription->customer->name ?? 'ไม่ระบุชื่อ';
            $phone = $prescription->customer->phone ?? '-';
            $daysUntil = Carbon::now()->diffInDays($prescription->next_refill_date, false);
            $status = $daysUntil < 0 ? '🔴 เกินกำหนด!' : ($daysUntil == 0 ? '🟠 วันนี้' : "🟢 อีก {$daysUntil} วัน");

            $message = "💊 แจ้งเตือนลูกค้ารับยา\n";
            $message .= "━━━━━━━━━━━━━━\n";
            $message .= "{$status}\n";
            $message .= "👤 ลูกค้า: {$customerName}\n";
            $message .= "📞 โทร: {$phone}\n";
            $message .= "📋 ใบสั่งยา: {$prescription->prescription_number}\n";
            $message .= "📅 กำหนด: " . ($prescription->next_refill_date ? $prescription->next_refill_date->format('d/m/Y') : '-') . "\n";
            $message .= "━━━━━━━━━━━━━━\n";
            $message .= "กรุณาติดต่อลูกค้า\n\n";
            $message .= "🏥 OBOUN ERP";

            if ($this->sendLineMessage($message)) {
                $sent++;
            }

            usleep(100000);
        }

        return $sent;
    }

    /**
     * Send message via LINE Messaging API
     */
    protected function sendLineMessage(string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->channelToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.line.me/v2/bot/message/push', [
                'to' => $this->userId,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $message,
                    ]
                ]
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('LINE notification failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('LINE notification error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}

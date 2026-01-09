<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineNotifyService
{
    protected $token;
    protected $apiUrl = 'https://notify-api.line.me/api/notify';

    public function __construct()
    {
        $this->token = config('services.line_notify.token');
    }

    /**
     * Send a notification via Line Notify.
     */
    public function send(string $message, ?string $imageUrl = null): bool
    {
        if (!$this->token) {
            Log::warning('Line Notify token not configured');
            return false;
        }

        try {
            $data = ['message' => $message];

            if ($imageUrl) {
                $data['imageThumbnail'] = $imageUrl;
                $data['imageFullsize'] = $imageUrl;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->asForm()->post($this->apiUrl, $data);

            if ($response->successful()) {
                Log::info('Line notification sent successfully');
                return true;
            }

            Log::error('Line notification failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Line notification error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send stock alert notification.
     */
    public function sendLowStockAlert(string $productName, int $currentStock, int $minStock): bool
    {
        $message = "\n🚨 แจ้งเตือนสต๊อกต่ำ\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "📦 สินค้า: {$productName}\n";
        $message .= "📊 คงเหลือ: {$currentStock} ชิ้น\n";
        $message .= "⚠️ ขั้นต่ำ: {$minStock} ชิ้น\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "กรุณาสั่งซื้อเพิ่มเติม";

        return $this->send($message);
    }

    /**
     * Send expiry alert notification.
     */
    public function sendExpiryAlert(string $productName, string $lotNumber, string $expiryDate, int $daysLeft): bool
    {
        $urgency = $daysLeft <= 30 ? '🔴 ด่วนมาก' : ($daysLeft <= 60 ? '🟠 ด่วน' : '🟡 เตือน');

        $message = "\n⏰ แจ้งเตือนสินค้าใกล้หมดอายุ\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "{$urgency}\n";
        $message .= "💊 สินค้า: {$productName}\n";
        $message .= "🏷️ Lot: {$lotNumber}\n";
        $message .= "📅 หมดอายุ: {$expiryDate}\n";
        $message .= "⏳ เหลือ: {$daysLeft} วัน\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "กรุณาตรวจสอบและดำเนินการ";

        return $this->send($message);
    }

    /**
     * Send refill reminder notification.
     */
    public function sendRefillReminder(string $customerName, string $phone, string $prescriptionNumber, string $dueDate): bool
    {
        $message = "\n💊 แจ้งเตือนลูกค้ารับยาต่อเนื่อง\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "👤 ลูกค้า: {$customerName}\n";
        $message .= "📞 โทร: {$phone}\n";
        $message .= "📋 ใบสั่งยา: {$prescriptionNumber}\n";
        $message .= "📅 กำหนด: {$dueDate}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "กรุณาติดต่อลูกค้า";

        return $this->send($message);
    }

    /**
     * Send daily summary notification.
     */
    public function sendDailySummary(array $stats): bool
    {
        $message = "\n📊 สรุปรายวัน - " . now()->format('d/m/Y') . "\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "🔔 การแจ้งเตือนทั้งหมด: {$stats['total']}\n";
        $message .= "⏰ ใกล้หมดอายุ: {$stats['expiring']}\n";
        $message .= "📦 สต๊อกต่ำ: {$stats['low_stock']}\n";
        $message .= "💊 ลูกค้ารับยา: {$stats['refill']}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "เข้าดูรายละเอียดที่ระบบ";

        return $this->send($message);
    }

    /**
     * Test the Line Notify connection.
     */
    public function test(): bool
    {
        $message = "\n✅ ทดสอบการเชื่อมต่อ Line Notify\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "ระบบ OBOUN ERP\n";
        $message .= "เชื่อมต่อสำเร็จ!\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "เวลา: " . now()->format('d/m/Y H:i:s');

        return $this->send($message);
    }
}

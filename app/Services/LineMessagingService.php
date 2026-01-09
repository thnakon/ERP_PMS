<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineMessagingService
{
    protected $channelAccessToken;
    protected $apiUrl = 'https://api.line.me/v2/bot/message';

    public function __construct()
    {
        $this->channelAccessToken = config('services.line_messaging.channel_access_token');
    }

    /**
     * Send a push message to a specific user or group.
     */
    public function pushMessage(string $to, string $message): bool
    {
        if (!$this->channelAccessToken) {
            Log::warning('Line Messaging API channel access token not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->channelAccessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/push', [
                'to' => $to,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $message,
                    ]
                ]
            ]);

            if ($response->successful()) {
                Log::info('Line push message sent successfully');
                return true;
            }

            Log::error('Line push message failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Line push message error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Broadcast message to all followers.
     */
    public function broadcastMessage(string $message): bool
    {
        if (!$this->channelAccessToken) {
            Log::warning('Line Messaging API channel access token not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->channelAccessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/broadcast', [
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $message,
                    ]
                ]
            ]);

            if ($response->successful()) {
                Log::info('Line broadcast message sent successfully');
                return true;
            }

            Log::error('Line broadcast message failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Line broadcast message error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send Flex Message (rich content).
     */
    public function pushFlexMessage(string $to, array $flexContent, string $altText = 'Notification'): bool
    {
        if (!$this->channelAccessToken) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->channelAccessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/push', [
                'to' => $to,
                'messages' => [
                    [
                        'type' => 'flex',
                        'altText' => $altText,
                        'contents' => $flexContent,
                    ]
                ]
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Line flex message error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send stock alert notification.
     */
    public function sendLowStockAlert(string $to, string $productName, int $currentStock, int $minStock): bool
    {
        $message = "🚨 แจ้งเตือนสต๊อกต่ำ\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "📦 สินค้า: {$productName}\n";
        $message .= "📊 คงเหลือ: {$currentStock} ชิ้น\n";
        $message .= "⚠️ ขั้นต่ำ: {$minStock} ชิ้น\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "กรุณาสั่งซื้อเพิ่มเติม";

        return $this->pushMessage($to, $message);
    }

    /**
     * Send expiry alert notification.
     */
    public function sendExpiryAlert(string $to, string $productName, string $lotNumber, string $expiryDate, int $daysLeft): bool
    {
        $urgency = $daysLeft <= 30 ? '🔴 ด่วนมาก' : ($daysLeft <= 60 ? '🟠 ด่วน' : '🟡 เตือน');

        $message = "⏰ แจ้งเตือนสินค้าใกล้หมดอายุ\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "{$urgency}\n";
        $message .= "💊 สินค้า: {$productName}\n";
        $message .= "🏷️ Lot: {$lotNumber}\n";
        $message .= "📅 หมดอายุ: {$expiryDate}\n";
        $message .= "⏳ เหลือ: {$daysLeft} วัน\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "กรุณาตรวจสอบและดำเนินการ";

        return $this->pushMessage($to, $message);
    }

    /**
     * Send refill reminder notification.
     */
    public function sendRefillReminder(string $to, string $customerName, string $phone, string $prescriptionNumber, string $dueDate): bool
    {
        $message = "💊 แจ้งเตือนลูกค้ารับยาต่อเนื่อง\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "👤 ลูกค้า: {$customerName}\n";
        $message .= "📞 โทร: {$phone}\n";
        $message .= "📋 ใบสั่งยา: {$prescriptionNumber}\n";
        $message .= "📅 กำหนด: {$dueDate}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "กรุณาติดต่อลูกค้า";

        return $this->pushMessage($to, $message);
    }

    /**
     * Send daily summary notification.
     */
    public function sendDailySummary(string $to, array $stats): bool
    {
        $message = "📊 สรุปรายวัน - " . now()->format('d/m/Y') . "\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "🔔 การแจ้งเตือนทั้งหมด: {$stats['total']}\n";
        $message .= "⏰ ใกล้หมดอายุ: {$stats['expiring']}\n";
        $message .= "📦 สต๊อกต่ำ: {$stats['low_stock']}\n";
        $message .= "💊 ลูกค้ารับยา: {$stats['refill']}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "เข้าดูรายละเอียดที่ระบบ";

        return $this->pushMessage($to, $message);
    }

    /**
     * Test the Line Messaging API connection.
     */
    public function test(string $to): bool
    {
        $message = "✅ ทดสอบการเชื่อมต่อ Line Messaging API\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "ระบบ OBOUN ERP\n";
        $message .= "เชื่อมต่อสำเร็จ!\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "เวลา: " . now()->format('d/m/Y H:i:s');

        return $this->pushMessage($to, $message);
    }

    /**
     * Reply to a message using a reply token.
     */
    public function replyMessage(string $replyToken, string $message): bool
    {
        if (!$this->channelAccessToken) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->channelAccessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/reply', [
                'replyToken' => $replyToken,
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

            Log::error('Line reply message failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Line reply message error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Verify webhook signature.
     */
    public function verifySignature(string $body, string $signature): bool
    {
        $channelSecret = config('services.line_messaging.channel_secret');
        $hash = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));
        return hash_equals($hash, $signature);
    }
}

<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductStockObserver
{
    /**
     * Handle the Product "updated" event.
     * Send LINE notification when stock falls below minimum.
     */
    public function updated(Product $product): void
    {
        // Check if stock was updated and is now below minimum
        if ($product->wasChanged('stock_qty') && $product->stock_qty <= $product->min_stock) {
            // Only send if it just crossed the threshold (was above before)
            $originalStock = $product->getOriginal('stock_qty');

            if ($originalStock > $product->min_stock) {
                $this->sendLowStockAlert($product);
            }
        }
    }

    /**
     * Send low stock alert via LINE.
     */
    protected function sendLowStockAlert(Product $product): void
    {
        $channelToken = config('services.line_messaging.channel_access_token');
        $userId = config('services.line_messaging.user_id');

        if (!$channelToken || !$userId) {
            Log::warning('LINE not configured for low stock alert');
            return;
        }

        $message = "🚨 แจ้งเตือนสต๊อกต่ำ (Realtime)\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "📦 สินค้า: {$product->name}\n";
        $message .= "📊 คงเหลือ: " . number_format($product->stock_qty) . " {$product->unit}\n";
        $message .= "⚠️ ขั้นต่ำ: " . number_format($product->min_stock) . " {$product->unit}\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "⏰ เวลา: " . now()->format('H:i:s') . "\n";
        $message .= "กรุณาสั่งซื้อเพิ่มเติม\n\n";
        $message .= "🏥 OBOUN ERP";

        try {
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $channelToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.line.me/v2/bot/message/push', [
                'to' => $userId,
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $message,
                    ]
                ]
            ]);

            Log::info('Low stock alert sent for: ' . $product->name);
        } catch (\Exception $e) {
            Log::error('Failed to send low stock alert', ['error' => $e->getMessage()]);
        }
    }
}

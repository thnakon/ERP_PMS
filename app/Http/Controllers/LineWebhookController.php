<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineMessagingService;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Prescription;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LineWebhookController extends Controller
{
    protected $lineService;

    public function __construct(LineMessagingService $lineService)
    {
        $this->lineService = $lineService;
    }

    /**
     * Handle incoming LINE webhook.
     */
    public function handle(Request $request)
    {
        $signature = $request->header('X-Line-Signature');
        $body = $request->getContent();

        if (!$signature || !$this->lineService->verifySignature($body, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $data = json_decode($body, true);
        $events = $data['events'] ?? [];

        foreach ($events as $event) {
            if ($event['type'] === 'message' && $event['message']['type'] === 'text') {
                $this->handleTextMessage($event);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Process text messages and reply based on keywords.
     */
    protected function handleTextMessage($event)
    {
        $text = trim($event['message']['text']);
        $replyToken = $event['replyToken'];
        $userId = $event['source']['userId'] ?? null;

        // Check if user is authorized (optional security measure)
        $authorizedId = config('services.line_messaging.user_id');
        if ($authorizedId && $userId !== $authorizedId) {
            Log::warning('Unauthorized LINE message from: ' . $userId);
            return;
        }

        if (str_contains($text, 'สรุปรายวัน')) {
            $this->replyDailySummary($replyToken);
        } elseif (str_contains($text, 'เช็คสต๊อก') || str_contains($text, 'สต๊อกต่ำ')) {
            $this->replyLowStock($replyToken);
        } elseif (str_contains($text, 'สินค้าใกล้หมดอายุ') || str_contains($text, 'เช็คหมดอายุ') || str_contains($text, 'ใกล้หมดอายุ')) {
            $this->replyExpiring($replyToken);
        } elseif (str_contains($text, 'ช่วย') || str_contains($text, 'คำสั่ง') || str_contains($text, 'เมนู')) {
            $this->replyHelp($replyToken);
        }
    }

    /**
     * Reply with daily summary.
     */
    protected function replyDailySummary($replyToken)
    {
        $today = Carbon::today();

        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->count();

        $expiringCount = ProductLot::where('expiry_date', '<=', $today->copy()->addDays(90))
            ->where('expiry_date', '>', $today)
            ->where('quantity', '>', 0)
            ->count();

        $refillCount = Prescription::where('status', 'dispensed')
            ->whereColumn('refill_count', '<', 'refill_allowed')
            ->where('next_refill_date', '<=', $today->copy()->addDays(7))
            ->count();

        $todayOrders = Order::whereDate('created_at', $today)->count();

        $message = "📊 สรุปรายงานประจำวัน\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "📅 วันที่: " . $today->format('d/m/Y') . "\n";
        $message .= "🛒 ออเดอร์วันนี้: " . number_format($todayOrders) . " รายการ\n";
        $message .= "📦 สินค้าสต๊อกต่ำ: " . number_format($lowStockCount) . " รายการ\n";
        $message .= "⏰ ใกล้หมดอายุ: " . number_format($expiringCount) . " รายการ\n";
        $message .= "💊 เตือนรับยา: " . number_format($refillCount) . " คน\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "🏥 OBOUN ERP";

        $this->lineService->replyMessage($replyToken, $message);
    }

    /**
     * Reply with list of low stock products.
     */
    protected function replyLowStock($replyToken)
    {
        $products = Product::where('is_active', true)
            ->whereColumn('stock_qty', '<=', 'min_stock')
            ->orderBy('stock_qty')
            ->limit(10)
            ->get();

        if ($products->isEmpty()) {
            $this->lineService->replyMessage($replyToken, "✅ ไม่มีสินค้าสต๊อกต่ำในขณะนี้");
            return;
        }

        $message = "🚨 รายการสินค้าสต๊อกต่ำ\n";
        $message .= "━━━━━━━━━━━━━━\n";
        foreach ($products as $product) {
            $message .= "• {$product->name}: Remaining " . number_format($product->stock_qty) . " (Min " . number_format($product->min_stock) . ")\n";
        }
        $message .= "━━━━━━━━━━━━━━\n";

        $totalLow = Product::where('is_active', true)->whereColumn('stock_qty', '<=', 'min_stock')->count();
        if ($totalLow > 10) {
            $message .= "...และอื่นๆ อีก " . ($totalLow - 10) . " รายการ\n";
        }
        $message .= "🏥 OBOUN ERP";

        $this->lineService->replyMessage($replyToken, $message);
    }

    /**
     * Reply with list of expiring products.
     */
    protected function replyExpiring($replyToken)
    {
        $today = Carbon::today();
        $lots = ProductLot::where('expiry_date', '<=', $today->copy()->addDays(90))
            ->where('expiry_date', '>', $today)
            ->where('quantity', '>', 0)
            ->with('product')
            ->orderBy('expiry_date')
            ->limit(10)
            ->get();

        if ($lots->isEmpty()) {
            $this->lineService->replyMessage($replyToken, "✅ ไม่มีสินค้าใกล้หมดอายุภายใน 90 วัน");
            return;
        }

        $message = "⏰ สินค้าใกล้หมดอายุ (90 วัน)\n";
        $message .= "━━━━━━━━━━━━━━\n";
        foreach ($lots as $lot) {
            $daysLeft = $today->diffInDays($lot->expiry_date);
            $message .= "• {$lot->product->name}\n  (Expires in {$daysLeft} days / Lot: {$lot->lot_number})\n";
        }
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "🏥 OBOUN ERP";

        $this->lineService->replyMessage($replyToken, $message);
    }

    /**
     * Reply with help menu.
     */
    protected function replyHelp($replyToken)
    {
        $message = "❓ คำสั่งที่ใช้งานได้:\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "• สรุปรายวัน\n";
        $message .= "• เช็คสต๊อก\n";
        $message .= "• เช็คหมดอายุ\n";
        $message .= "━━━━━━━━━━━━━━\n";
        $message .= "พิมคำสั่งที่ต้องการได้เลยครับ\n";
        $message .= "🏥 OBOUN ERP";

        $this->lineService->replyMessage($replyToken, $message);
    }
}

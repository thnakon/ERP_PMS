<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * Show the AI Assistant Page
     */
    public function index()
    {
        return view('ai.index');
    }

    /**
     * Process AI chat message with Mock logic Fallback
     */
    public function chat(Request $request)

    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = $request->input('message');
        $locale = app()->getLocale();

        // Get user info for context
        $storeName = \App\Models\Setting::get('store_name', 'Oboun ERP');
        $userName = auth()->user()->name ?? 'Staff';

        try {
            // Call Python FastAPI Backend instead of direct Gemini API
            // Use host.docker.internal for Docker container to reach host machine
            $response = Http::timeout(5)->post('http://host.docker.internal:8001/chat', [
                'message' => $message,
                'store_name' => $storeName,
                'user_name' => $userName,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'message' => $data['reply'] ?? $this->getErrorMessage('no_response', $locale)
                ]);
            } else {
                // If backend returns error, use mock fallback
                return $this->getMockResponse($message, $locale);
            }
        } catch (\Exception $e) {
            // If backend is down, use mock fallback
            return $this->getMockResponse($message, $locale);
        }
    }

    /**
     * Mock Response Logic for Pharmacy Assistant
     * Now with REAL data insights!
     */
    private function getMockResponse(string $message, string $locale)
    {
        $message = mb_strtolower($message);
        $reply = "";

        // 1. Check for Expiry Questions
        if (str_contains($message, 'หมดอายุ') || str_contains($message, 'expiry') || str_contains($message, 'expire')) {
            $expiringLots = \App\Models\ProductLot::with('product')
                ->where('quantity', '>', 0)
                ->where('expiry_date', '<=', now()->addMonths(3))
                ->orderBy('expiry_date', 'asc')
                ->limit(5)
                ->get();

            if ($expiringLots->count() > 0) {
                if ($locale === 'th') {
                    $reply = "จากการตรวจสอบข้อมูลคลังสินค้า พบยาที่ **ใกล้หมดอายุ (ภายใน 3 เดือน)** ดังนี้ครับ:\n\n";
                    foreach ($expiringLots as $lot) {
                        $days = $lot->days_until_expiry;
                        $status = $days <= 0 ? "❌ หมดอายุแล้ว" : "⏳ อีก $days วัน";
                        $reply .= "- **{$lot->product->name}** (Lot: {$lot->lot_number}) - $status\n";
                    }
                    $reply .= "\nแนะนำให้รีบจัดทำรายการระบายสต็อก หรือติดต่อคืนผู้จำหน่าย (ถ้าสะดวกรุ่น) นะครับ";
                } else {
                    $reply = "I found these items **expiring soon (within 3 months)**:\n\n";
                    foreach ($expiringLots as $lot) {
                        $days = $lot->days_until_expiry;
                        $status = $days <= 0 ? "❌ Expired" : "⏳ $days days left";
                        $reply .= "- **{$lot->product->name}** (Lot: {$lot->lot_number}) - $status\n";
                    }
                }
            } else {
                $reply = ($locale === 'th')
                    ? "ยินดีด้วยครับ! ขณะนี้ยังไม่พบรายการยาที่ใกล้หมดอายุในสต็อกของคุณ"
                    : "Great news! No items are nearing expiry at the moment.";
            }
        }

        // 2. Check for Low Stock Questions
        elseif (str_contains($message, 'ของหมด') || str_contains($message, 'สต็อกน้อย') || str_contains($message, 'low stock') || str_contains($message, 'สั่งของ')) {
            $lowStockProducts = \App\Models\Product::where('stock_qty', '<=', \Illuminate\Support\Facades\DB::raw('min_stock'))
                ->where('is_active', true)
                ->limit(5)
                ->get();

            if ($lowStockProducts->count() > 0) {
                if ($locale === 'th') {
                    $reply = "รายการยาที่ **สต็อกใกล้หมด (Low Stock)** ที่ควรสั่งเพิ่มครับ:\n\n";
                    foreach ($lowStockProducts as $p) {
                        $reply .= "- **{$p->name}** (คงเหลือ: {$p->stock_qty} {$p->unit} / ขั้นต่ำ: {$p->min_stock})\n";
                    }
                    $reply .= "\nคุณสามารถสร้างรายการ **Purchase Order** ได้ที่เมนูจัดซื้อครับ";
                } else {
                    $reply = "These items are below your **Minimum Stock Level**:\n\n";
                    foreach ($lowStockProducts as $p) {
                        $reply .= "- **{$p->name}** (Stock: {$p->stock_qty} / Min: {$p->min_stock})\n";
                    }
                }
            } else {
                $reply = ($locale === 'th')
                    ? "ระดับสต็อกยาในปัจจุบันยังอยู่ในเกณฑ์ที่ตั้งไว้ครับ ยังไม่พบรายการที่ต้องสั่งเพิ่มด่วน"
                    : "Your inventory levels are healthy! No items require urgent reordering.";
            }
        }

        // 3. Check for Sales/Performance Questions
        elseif (str_contains($message, 'ขายดี') || str_contains($message, 'ยอดขาย') || str_contains($message, 'best seller') || str_contains($message, 'sales')) {
            $topSelling = \App\Models\OrderItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->with('product')
                ->limit(3)
                ->get();

            if ($topSelling->count() > 0) {
                if ($locale === 'th') {
                    $reply = "รายการยาที่ **มียอดจำหน่ายสูงสุด (Top 3)** ในช่วงนี้ครับ:\n\n";
                    foreach ($topSelling as $item) {
                        $reply .= "- 🏆 **{$item->product?->name}** (ขายได้ {$item->total_sold} รายการ)\n";
                    }
                    $reply .= "\nต้องการดูรายงานยอดขายแบบละเอียดเชิงลึกไหมครับ?";
                } else {
                    $reply = "Your **Top 3 Selling Products** are:\n\n";
                    foreach ($topSelling as $item) {
                        $reply .= "- 🏆 **{$item->product?->name}** ({$item->total_sold} items sold)\n";
                    }
                }
            } else {
                $reply = ($locale === 'th')
                    ? "ช่วงนี้ยังมียอดขายไหลเข้าสม่ำเสมอครับ หากต้องการดูสรุปยอดรายวัน สามารถแจ้งผมได้เลย"
                    : "Sales are steady! I can provide a daily summary if you'd like.";
            }
        }

        // 4. Fallback to existing mock Logic
        if (empty($reply)) {
            if ($locale === 'th') {
                if (str_contains($message, 'ยา') || str_contains($message, 'medicine')) {
                    $reply = "💊 สำหรับข้อมูลยาในระบบ คุณสามารถตรวจสอบได้ที่เมนู **คลังสินค้า > ยาและเวชภัณฑ์** ครับ หากต้องการทราบวิธีใช้ยาตัวไหนเป็นพิเศษ แจ้งชื่อยาได้เลยครับ";
                } elseif (str_contains($message, 'แพ้') || str_contains($message, 'allerg')) {
                    $reply = "⚠️ ระบบแจ้งเตือนการแพ้ยาจะทำงานอัตโนมัติในหน้า **POS** เมื่อคุณเลือกคนไข้ที่มีประวัติแพ้ยาครับ คุณสามารถบันทึกประวัติการแพ้ได้ที่หน้า **ข้อมูลลูกค้า**";
                } elseif (str_contains($message, 'สวัสดี') || str_contains($message, 'hello') || str_contains($message, 'hi')) {
                    $reply = "สวัสดีครับ! ผม **Oboun AI** ผู้ช่วยอัจฉริยะของคุณ มีอะไรให้ผมช่วยดูแลระบบร้านยาในวันนี้ไหมครับ?";
                } else {
                    $reply = "เข้าใจแลัวครับ! ในฐานะผู้ช่วยร้านยา ผมขอแนะนำให้คุณตรวจสอบข้อมูลที่ถูกต้องในระบบ หรือหากต้องการความช่วยเหลือด้านเทคนิค สามารถสอบถามผมเพิ่มเติมได้เกี่ยวกับ การขาย, สต็อกยา หรือการดูรายงานครับ";
                }
            } else {
                if (str_contains($message, 'drug') || str_contains($message, 'medicine')) {
                    $reply = "💊 You can manage drug information in the **Inventory > Products** menu. If you need specific dosage or indications for a drug, please let me know the name!";
                } elseif (str_contains($message, 'allergy') || str_contains($message, 'allergic')) {
                    $reply = "⚠️ Allergy alerts work automatically in the **POS** when selecting patients with recorded allergies. Record these in the **Customer Profile**.";
                } elseif (str_contains($message, 'hello') || str_contains($message, 'hi')) {
                    $reply = "Hello! I'm **Oboun AI**, your intelligent pharmacy assistant. How can I help you manage your pharmacy today?";
                } else {
                    $reply = "I understand! As your pharmacy assistant, I recommend checking the system's recorded data. I can help with sales operations, inventory tracking, or reporting queries!";
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => $reply,
            'is_mock' => true
        ]);
    }



    /**
     * Get error message in the appropriate language
     */
    private function getErrorMessage(string $key, string $locale): string
    {
        $messages = [
            'th' => [
                'no_response' => 'ขออภัย ไม่สามารถตอบคำถามได้ในขณะนี้',
                'unavailable' => '⚠️ บริการ AI ไม่พร้อมใช้งานชั่วคราว',
                'quota_exceeded' => '⚠️ API ถูกใช้งานมากเกินไป กรุณารอสักครู่แล้วลองใหม่อีกครั้ง (ประมาณ 1 นาที)',
                'connection_failed' => '🔌 ไม่สามารถเชื่อมต่อกับ AI Service ได้ กรุณาตรวจสอบว่า Python AI Backend กำลังทำงานอยู่',
                'error' => '❌ เกิดข้อผิดพลาด',
                'generic_error' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง',
            ],
            'en' => [
                'no_response' => 'Sorry, unable to respond at this time',
                'unavailable' => '⚠️ AI service temporarily unavailable',
                'quota_exceeded' => '⚠️ API quota exceeded. Please wait a moment and try again (about 1 minute)',
                'connection_failed' => '🔌 Cannot connect to AI Service. Please ensure Python AI Backend is running',
                'error' => '❌ An error occurred',
                'generic_error' => 'An error occurred. Please try again',
            ],
        ];

        // Default to Thai if locale not found
        $lang = isset($messages[$locale]) ? $locale : 'th';

        return $messages[$lang][$key] ?? $messages['th'][$key];
    }
}

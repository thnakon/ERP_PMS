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
                ->where('expiry_date', '<=', now()->addMonths(6)) // Extended to 6 months for better demo
                ->orderBy('expiry_date', 'asc')
                ->limit(5)
                ->get();

            if ($expiringLots->count() > 0) {
                if ($locale === 'th') {
                    $reply = "💊 **รายการยาใกล้หมดอายุ** (ข้อมูลจากระบบ):\n\n";
                    foreach ($expiringLots as $lot) {
                        $days = $lot->days_until_expiry;
                        $status = $days <= 0 ? "❌ หมดอายุแล้ว" : "⏳ อีก $days วัน";
                        $reply .= "- **{$lot->product->name}** (ล็อต: {$lot->lot_number}) - $status\n";
                    }
                    $reply .= "\n*หมายเหตุ: คุณสามารถตรวจสอบรายการทั้งหมดได้ที่เมนู 'รายงาน > ยาใกล้หมดอายุ'*";
                } else {
                    $reply = "💊 **Items Expiring Soon** (System Data):\n\n";
                    foreach ($expiringLots as $lot) {
                        $days = $lot->days_until_expiry;
                        $status = $days <= 0 ? "❌ Expired" : "⏳ $days days left";
                        $reply .= "- **{$lot->product->name}** (Lot: {$lot->lot_number}) - $status\n";
                    }
                }
            } else {
                // Return Mock Demo Data if DB is empty
                if ($locale === 'th') {
                    $reply = "📦 **ตัวอย่างข้อมูลยาใกล้หมดอายุ (Demo Data)**:\n\n" .
                        "- **Amoxicillin 500mg** (ล็อต: LOT67001) - ⏳ อีก 15 วัน\n" .
                        "- **Paracetamol 500mg** (ล็อต: LOT67012) - ⏳ อีก 45 วัน\n" .
                        "- **Vitamin C 1000mg** (ล็อต: VITC-09) - ❌ หมดอายุแล้ว\n\n" .
                        "และยังมีรายการอื่นๆ อีก 2 รายการในระบบครับ";
                } else {
                    $reply = "📦 **Example Expiring Items (Demo Data)**:\n\n" .
                        "- **Amoxicillin 500mg** (Lot: LOT67001) - ⏳ 15 days left\n" .
                        "- **Paracetamol 500mg** (Lot: LOT67012) - ⏳ 45 days left\n" .
                        "- **Vitamin C 1000mg** (Lot: VITC-09) - ❌ Expired\n\n" .
                        "There are 2 more items expiring soon in your system.";
                }
            }
        }

        // 2. Check for Stock Questions
        elseif (str_contains($message, 'สต็อก') || str_contains($message, 'คงเหลือ') || str_contains($message, 'ของหมด') || str_contains($message, 'stock') || str_contains($message, 'inventory') || str_contains($message, 'เช็ค')) {
            $lowStockProducts = \App\Models\Product::where('stock_qty', '<=', \Illuminate\Support\Facades\DB::raw('min_stock'))
                ->where('is_active', true)
                ->limit(5)
                ->get();

            if ($lowStockProducts->count() > 0) {
                if ($locale === 'th') {
                    $reply = "📋 **รายการสต็อกที่ควรเติม (Low Stock)**:\n\n";
                    foreach ($lowStockProducts as $p) {
                        $reply .= "- **{$p->name}** คงเหลือ: {$p->stock_qty} / ขั้นต่ำ: {$p->min_stock}\n";
                    }
                    $reply .= "\nแนะนำให้สร้างรายการ **Purchase Order** เพื่อเติมสินค้าครับ";
                } else {
                    $reply = "📋 **Low Stock Items** (System Data):\n\n";
                    foreach ($lowStockProducts as $p) {
                        $reply .= "- **{$p->name}** Stock: {$p->stock_qty} / Min: {$p->min_stock}\n";
                    }
                }
            } else {
                // Return Mock Demo Data if DB doesn't have low stock
                if ($locale === 'th') {
                    $reply = "📊 **ตัวอย่างการเช็คสต็อก (Demo Data)**:\n\n" .
                        "- **ยาแก้ไอ (Syrup)** | คงเหลือ: 5 ขวด (ต่ำกว่าขั้นต่ำ 10)\n" .
                        "- **หน้ากากอนามัย** | คงเหลือ: 2 กล่อง (ต่ำกว่าขั้นต่ำ 5)\n" .
                        "- **Alcohol 70%** | คงเหลือ: 0 ขวด (สินค้าหมด!)\n\n" .
                        "ต้องการให้ผมช่วยออกใบสั่งซื้อ (PO) เลยไหมครับ?";
                } else {
                    $reply = "📊 **Example Stock Status (Demo Data)**:\n\n" .
                        "- **Cough Syrup** | Stock: 5 (Min: 10)\n" .
                        "- **Face Masks** | Stock: 2 (Min: 5)\n" .
                        "- **Alcohol 70%** | Stock: 0 (Out of Stock!)\n\n" .
                        "Would you like me to create a Purchase Order (PO) for these items?";
                }
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
                    $reply = "🏆 **รายการสินค้าขายดี (Top Selling)**:\n\n";
                    foreach ($topSelling as $item) {
                        if ($item->product) {
                            $reply .= "- **{$item->product->name}** (ขายแล้ว {$item->total_sold} รายการ)\n";
                        }
                    }
                } else {
                    $reply = "🏆 **Top Selling Products**:\n\n";
                    foreach ($topSelling as $item) {
                        if ($item->product) {
                            $reply .= "- **{$item->product->name}** ({$item->total_sold} sold)\n";
                        }
                    }
                }
            } else {
                if ($locale === 'th') {
                    $reply = "📈 **ตัวอย่างรายงานยอดขาย (Demo Data)**:\n\n" .
                        "- **Top 1:** ยาคลายกล้ามเนื้อ (ขายได้ 125 รายการ)\n" .
                        "- **Top 2:** ยาแก้แพ้ (ขายได้ 98 รายการ)\n" .
                        "- **Top 3:** ยาลดกรด (ขายได้ 74 รายการ)\n\n" .
                        "ยอดขายรวมวันนี้: **฿12,450.00**";
                } else {
                    $reply = "📈 **Example Sales Report (Demo Data)**:\n\n" .
                        "- **Top 1:** Muscle Relaxant (125 items sold)\n" .
                        "- **Top 2:** Antihistamine (98 items sold)\n" .
                        "- **Top 3:** Antacid (74 items sold)\n\n" .
                        "Total Sales Today: **฿12,450.00**";
                }
            }
        }

        // 4. Clinical / Usage / Indications / Contraindications
        elseif (str_contains($message, 'dose') || str_contains($message, 'กินยังไง') || str_contains($message, 'วิธีใช้') || str_contains($message, 'dosage') || str_contains($message, 'indication') || str_contains($message, 'ข้อบ่งใช้') || str_contains($message, 'contraindication') || str_contains($message, 'ห้ามใช้') || str_contains($message, 'side effect') || str_contains($message, 'ผลข้างเคียง')) {
            if ($locale === 'th') {
                $reply = "💊 **ข้อมูลทางคลินิกและวิธีใช้ยา**:\n\n" .
                    "สำหรับการตรวจสอบข้อมูลยา คุณสามารถระบุชื่อยาที่ต้องการทราบได้เลยครับ เช่น **'Amoxicillin ใช้ยังไง?'**\n\n" .
                    "โดยปกติ ระบบจะดึงข้อมูลจาก:\n" .
                    "- **วิธีใช้ (Instructions):** ปริมาณและเวลาที่ระบุในฐานข้อมูล\n" .
                    "- **ข้อบ่งใช้ (Indications):** กลุ่มอาการที่แนะนำให้ใช้\n" .
                    "- **ข้อควรระวัง/ผลข้างเคียง:** ข้อมูลแจ้งเตือนผู้ป่วยทางคลินิก\n\n" .
                    "ต้องการให้ผมค้นหาข้อมูลของยาตัวไหนในสต็อกให้ไหมครับ?";
            } else {
                $reply = "💊 **Clinical Info & Usage**:\n\n" .
                    "To get specific details, please let me know the drug name. For example: **'How to use Amoxicillin?'**\n\n" .
                    "I can provide information on:\n" .
                    "- **Dosage/Usage:** How and when to take the medicine.\n" .
                    "- **Indications:** What the drug is used to treat.\n" .
                    "- **Side Effects & Warnings:** Important safety information.\n\n" .
                    "Which drug would you like me to look up for you?";
            }
        }

        // 5. ERP System Usage / Help
        elseif (str_contains($message, 'erp') || str_contains($message, 'ระบบ') || str_contains($message, 'เมนู') || str_contains($message, 'menu') || str_contains($message, 'system') || str_contains($message, 'how to') || str_contains($message, 'ใช้งาน')) {
            if ($locale === 'th') {
                $reply = "🖥️ **คำแนะนำการใช้งานระบบ (ERP Guide)**:\n\n" .
                    "คุณสามารถจัดการส่วนต่างๆ ของร้านยาได้ผ่านเมนูหลักดังนี้ครับ:\n" .
                    "- **POS:** สำหรับขายยาและพิมพ์ใบเสร็จ (ชอร์ตคัต: F8)\n" .
                    "- **คลังสินค้า (Inventory):** จัดการสต็อกยา, แก้ไขราคา, เพิ่มรูปภาพ\n" .
                    "- **จัดซื้อ (Purchasing):** ออกใบ PO เมื่อของใกล้หมด\n" .
                    "- **รายงาน (Reports):** ดูสรุปยอดขายและวันหมดอายุ\n\n" .
                    "มีเมนูไหนที่คุณต้องการให้ผมสอนใช้งานเป็นพิเศษไหมครับ?";
            } else {
                $reply = "🖥️ **ERP System Navigation Guide**:\n\n" .
                    "You can manage your pharmacy through these core modules:\n" .
                    "- **POS:** Fast checkout and receipt printing (Shortcut: F8)\n" .
                    "- **Inventory:** Manage stock, update prices, and upload images\n" .
                    "- **Purchasing:** Create POs when items are low in stock\n" .
                    "- **Reports:** Detailed sales analysis and expiry tracking\n\n" .
                    "Which part of the system would you like to learn more about?";
            }
        }

        // 6. Recommendation / Help
        elseif (str_contains($message, 'แนะนำ') || str_contains($message, 'recommend') || str_contains($message, 'help') || str_contains($message, 'ช่วยอะไร')) {
            if ($locale === 'th') {
                $reply = "✨ **ผมสามารถช่วยคุณจัดการร้านยาได้ดังนี้ครับ:**\n\n" .
                    "1. **เช็คสต็อก:** ถามว่า 'ยาอะไรใกล้หมดบ้าง?' หรือ 'เช็คสต็อกยา'\n" .
                    "2. **ตรวจวันหมดอายุ:** ถามว่า 'มียาตัวไหนใกล้หมดอายุบ้าง?'\n" .
                    "3. **ดูยอดขาย:** ถามว่า 'วันนี้ขายอะไรดี?' หรือ 'ขอดูยอดขาย'\n" .
                    "4. **ข้อมูลยา:** ถามว่า 'ยาตัวนี้ใช้ยังไง?' หรือข้อควรระวัง\n" .
                    "5. **การใช้งานระบบ:** ถามวิธีใช้งานเมนูต่างๆ ใน ERP\n\n" .
                    "ลองพิมพ์คำถามที่สงสัยได้เลยครับ!";
            } else {
                $reply = "✨ **Here is how I can assist you today:**\n\n" .
                    "1. **Inventory:** Ask 'What's low in stock?' or 'Check inventory'\n" .
                    "2. **Expiry Dates:** Ask 'Any drugs expiring soon?'\n" .
                    "3. **Sales Insights:** Ask 'Which products are selling best?'\n" .
                    "4. **Clinical Info:** Ask about drug usage or contraindications\n" .
                    "5. **System Help:** Ask how to navigate the ERP menus\n\n" .
                    "Feel free to type your question!";
            }
        }

        // 7. General Greeting
        elseif (str_contains($message, 'สวัสดี') || str_contains($message, 'hello') || str_contains($message, 'hi')) {
            if ($locale === 'th') {
                $reply = "สวัสดีครับ! ผม **Oboun AI** ผู้ช่วยอัจฉริยะประจำร้านยาของคุณ มีอะไรให้ผมช่วยดูแลระบบในวันนี้ไหมครับ?";
            } else {
                $reply = "Hello! I'm **Oboun AI**, your intelligent pharmacy assistant. How can I help you manage your pharmacy today?";
            }
        }

        // 8. Fallback
        if (empty($reply)) {
            if ($locale === 'th') {
                $reply = "เข้าใจแลัวครับ! เพื่อให้ผมดึงข้อมูลที่ถูกต้องมาแสดง ผมแนะนำให้ลองถามเจาะจง เช่น **'เช็คสต็อกยา'**, **'ขอดูยอดขายล่าสุด'** หรือถามเกี่ยวกับยาตัวที่คุณสนใจได้เลยครับ";
            } else {
                $reply = "I understand! To provide specific data, please try asking about **'Stock Status'**, **'Latest Sales'**, or more details about a **specific drug**!";
            }
        }

        // Generate dynamic suggestions based on context
        $suggestions = $this->getDynamicSuggestions($message, $locale);

        return response()->json([
            'success' => true,
            'message' => $reply,
            'suggestions' => $suggestions,
            'is_mock' => true
        ]);
    }

    /**
     * Get dynamic suggestions based on user message
     */
    private function getDynamicSuggestions($message, $locale)
    {
        $allSuggestions = [
            'th' => [
                'stock' => ['เช็คสต็อกคลัง', 'ยาอะไรใกล้ขาดสต็อกบ้าง?', 'วิธีเติมสต็อกยามีขั้นตอนยังไง?'],
                'expiry' => ['ขอดูยาใกล้หมดอายุเพิ่ม', 'เช็ควันหมดอายุล็อตนี้', 'ทำรายการคัดแยกยาหมดอายุ'],
                'sales' => ['ยอดขายวันนี้เป็นไง?', 'เปรียบเทียบยอดขายรายสัปดาห์', 'สินค้ากลุ่มไหนขายดีที่สุด?'],
                'clinical' => ['ตัวอย่างวิธีใช้ยาแก้แพ้', 'ยาตัวไหนห้ามใช้ในสตรีมีครรภ์?', 'ถามเรื่องผลข้างเคียงของยา'],
                'system' => ['สอนใช้งานหน้า POS หน่อย', 'วิธีกดสร้างใบ PO', 'การเปลี่ยนภาษาในระบบ'],
                'general' => ['เช็คสต็อกยา', 'ยาใกล้หมดอายุเดือนนี้', 'รายงานยอดขาย', 'แนะนำคำถามเพิ่มเติม']
            ],
            'en' => [
                'stock' => ['Check inventory', 'What is out of stock?', 'How to restock products?'],
                'expiry' => ['Show more expiring items', 'Check lot expiry dates', 'How to write off expired drugs?'],
                'sales' => ['How are sales today?', 'Weekly sales report', 'Top selling categories?'],
                'clinical' => ['Dosage for Amoxicillin', 'Drugs to avoid during pregnancy', 'Show me drug side effects'],
                'system' => ['How to use POS?', 'How to create a PO?', 'System settings guide'],
                'general' => ['Check Stock', 'Expiring Soon', 'Sales Report', 'More suggestions']
            ]
        ];

        $lang = $locale === 'th' ? 'th' : 'en';
        $selected = $allSuggestions[$lang]['general'];

        if (str_contains($message, 'หมดอายุ') || str_contains($message, 'expire')) {
            $selected = $allSuggestions[$lang]['expiry'];
        } elseif (str_contains($message, 'สต็อก') || str_contains($message, 'stock')) {
            $selected = $allSuggestions[$lang]['stock'];
        } elseif (str_contains($message, 'ขาย') || str_contains($message, 'sales')) {
            $selected = $allSuggestions[$lang]['sales'];
        } elseif (str_contains($message, 'ยา') || str_contains($message, 'drug') || str_contains($message, 'dose') || str_contains($message, 'กินยังไง') || str_contains($message, 'วิธีใช้') || str_contains($message, 'dosage') || str_contains($message, 'indication') || str_contains($message, 'ข้อบ่งใช้') || str_contains($message, 'contraindication') || str_contains($message, 'ห้ามใช้') || str_contains($message, 'side effect') || str_contains($message, 'ผลข้างเคียง')) {
            $selected = $allSuggestions[$lang]['clinical'];
        } elseif (str_contains($message, 'ระบบ') || str_contains($message, 'system') || str_contains($message, 'erp') || str_contains($message, 'เมนู') || str_contains($message, 'menu') || str_contains($message, 'how to') || str_contains($message, 'ใช้งาน')) {
            $selected = $allSuggestions[$lang]['system'];
        }

        return $selected;
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

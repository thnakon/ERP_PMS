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
     */
    private function getMockResponse(string $message, string $locale)
    {
        $message = mb_strtolower($message);
        $reply = "";

        if ($locale === 'th') {
            if (str_contains($message, 'ยา') || str_contains($message, 'medicine')) {
                $reply = "💊 สำหรับข้อมูลยาในระบบ คุณสามารถตรวจสอบได้ที่เมนู **คลังสินค้า > ยาและเวชภัณฑ์** ครับ หากต้องการทราบวิธีใช้ยาตัวไหนเป็นพิเศษ แจ้งชื่อยาได้เลยครับ";
            } elseif (str_contains($message, 'แพ้') || str_contains($message, 'allerg')) {
                $reply = "⚠️ ระบบแจ้งเตือนการแพ้ยาจะทำงานอัตโนมัติในหน้า **POS** เมื่อคุณเลือกคนไข้ที่มีประวัติแพ้ยาครับ คุณสามารถบันทึกประวัติการแพ้ได้ที่หน้า **ข้อมูลลูกค้า**";
            } elseif (str_contains($message, 'รายงาน') || str_contains($message, 'report')) {
                $reply = "📊 ระบบมีรายงานครอบคลุมทั้ง **ยอดขาย (Sales), สต็อก (Inventory) และ การเงิน (Finance)** เข้าดูได้ที่เมนูรายงานทางด้านซ้ายครับ (เฉพาะ Admin)";
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
            } elseif (str_contains($message, 'report') || str_contains($message, 'sales')) {
                $reply = "📊 We provide comprehensive reports for **Sales, Inventory, and Finance**. Check the Reports section in the sidebar for details.";
            } elseif (str_contains($message, 'hello') || str_contains($message, 'hi')) {
                $reply = "Hello! I'm **Oboun AI**, your intelligent pharmacy assistant. How can I help you manage your pharmacy today?";
            } else {
                $reply = "I understand! As your pharmacy assistant, I recommend checking the system's recorded data. I can help with sales operations, inventory tracking, or reporting queries!";
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

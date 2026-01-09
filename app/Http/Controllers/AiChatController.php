<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * Process AI chat message using Python FastAPI Backend
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
            $response = Http::timeout(60)->post('http://host.docker.internal:8001/chat', [
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
                $status = $response->status();
                $body = $response->body();

                Log::error('AI Backend Error', [
                    'status' => $status,
                    'body' => $body
                ]);

                // Parse error from Python backend
                $errorMessage = $this->getErrorMessage('unavailable', $locale);

                if ($status === 429 || str_contains($body, '429')) {
                    $errorMessage = $this->getErrorMessage('quota_exceeded', $locale);
                } elseif ($status === 500) {
                    // Try to extract error message from JSON
                    $errorData = json_decode($body, true);
                    if (isset($errorData['detail'])) {
                        if (str_contains($errorData['detail'], '429')) {
                            $errorMessage = $this->getErrorMessage('quota_exceeded', $locale);
                        } else {
                            $errorMessage = $this->getErrorMessage('error', $locale) . ': ' . substr($errorData['detail'], 0, 100);
                        }
                    }
                }

                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ], 500);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('AI Backend Connection Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $this->getErrorMessage('connection_failed', $locale)
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $this->getErrorMessage('generic_error', $locale)
            ], 500);
        }
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

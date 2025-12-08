<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Patient;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    /**
     * Live Search - Returns JSON for real-time dropdown results
     */
    public function liveSearch(Request $request)
    {
        $query = trim($request->input('q'));

        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'patients' => [],
                'suppliers' => [],
                'categories' => [],
                'users' => [],
                'purchases' => [],
            ]);
        }

        try {
            // Search Products (using correct columns)
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('generic_name', 'LIKE', "%{$query}%")
                ->orWhere('barcode', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'generic_name', 'selling_price', 'image_path']);

            // Search Patients/Customers
            $patients = Patient::where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'phone', 'email']);

            // Search Suppliers
            $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
                ->orWhere('contact_person', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'contact_person', 'phone']);

            // Search Categories
            $categories = Category::where('name', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name']);

            // Search Users/Staff
            $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'email', 'profile_photo_path']);

            // Search Purchase Orders
            $purchases = Purchase::where('reference_number', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'reference_number', 'status', 'total_amount']);

            return response()->json([
                'products' => $products,
                'patients' => $patients,
                'suppliers' => $suppliers,
                'categories' => $categories,
                'users' => $users,
                'purchases' => $purchases,
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            Log::error('Live Search Error: ' . $e->getMessage());
            return response()->json([
                'products' => [],
                'patients' => [],
                'suppliers' => [],
                'categories' => [],
                'users' => [],
                'purchases' => [],
                'error' => 'เกิดข้อผิดพลาดในการค้นหา',
            ]);
        }
    }

    /**
     * Full Search - Returns a view with comprehensive search results
     */
    public function fullSearch(Request $request)
    {
        $query = trim($request->input('q'));

        try {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('generic_name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->get();

            $patients = Patient::where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->get();

            $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
                ->orWhere('contact_person', 'LIKE', "%{$query}%")
                ->get();
        } catch (\Exception $e) {
            Log::error('Full Search Error: ' . $e->getMessage());
            $products = collect([]);
            $patients = collect([]);
            $suppliers = collect([]);
        }

        return view('search.search-results', [
            'query' => $query,
            'products' => $products,
            'patients' => $patients,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * AI Search - Uses AI to provide intelligent search assistance
     */
    public function aiSearch(Request $request)
    {
        $query = trim($request->input('q'));

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณาใส่คำถามหรือคำค้นหา'
            ]);
        }

        // Gather context from the database
        $context = $this->gatherSearchContext($query);

        // Build AI prompt with ERP context
        $systemPrompt = $this->buildSystemPrompt($context);

        try {
            // Try to use Gemini API (free tier available)
            $aiResponse = $this->callGeminiAPI($query, $systemPrompt, $context);

            return response()->json([
                'success' => true,
                'query' => $query,
                'response' => $aiResponse,
                'context' => $context,
            ]);
        } catch (\Exception $e) {
            Log::error('AI Search Error: ' . $e->getMessage());

            // Fallback to local intelligent search
            $fallbackResponse = $this->generateFallbackResponse($query, $context);

            return response()->json([
                'success' => true,
                'query' => $query,
                'response' => $fallbackResponse,
                'context' => $context,
                'fallback' => true,
            ]);
        }
    }

    /**
     * Gather relevant data from the database for AI context
     */
    private function gatherSearchContext(string $query): array
    {
        $context = [];

        try {
            // Search Products (using correct columns)
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('generic_name', 'LIKE', "%{$query}%")
                ->orWhere('barcode', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get(['id', 'name', 'generic_name', 'selling_price']);
            if ($products->count() > 0) {
                $context['products'] = $products->toArray();
            }

            // Search Patients
            $patients = Patient::where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get(['id', 'name', 'phone', 'email']);
            if ($patients->count() > 0) {
                $context['patients'] = $patients->toArray();
            }

            // Search Suppliers
            $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get(['id', 'name', 'contact_person', 'phone']);
            if ($suppliers->count() > 0) {
                $context['suppliers'] = $suppliers->toArray();
            }

            // Get system stats for general queries
            $context['stats'] = [
                'total_products' => Product::count(),
                'total_patients' => Patient::count(),
                'total_suppliers' => Supplier::count(),
                'low_stock_products' => Product::whereHas('batches', function ($q) {
                    $q->where('quantity', '<=', 10);
                })->count(),
            ];
        } catch (\Exception $e) {
            Log::error('gatherSearchContext Error: ' . $e->getMessage());
            $context['stats'] = [
                'total_products' => 0,
                'total_patients' => 0,
                'total_suppliers' => 0,
                'low_stock_products' => 0,
            ];
        }

        return $context;
    }

    /**
     * Build system prompt for AI
     */
    private function buildSystemPrompt(array $context): string
    {
        $statsInfo = '';
        if (isset($context['stats'])) {
            $stats = $context['stats'];
            $statsInfo = "ข้อมูลระบบ ERP ปัจจุบัน:
- สินค้าทั้งหมด: {$stats['total_products']} รายการ
- ลูกค้าทั้งหมด: {$stats['total_patients']} คน
- Suppliers ทั้งหมด: {$stats['total_suppliers']} ราย
- สินค้าที่ stock ต่ำ (≤10): {$stats['low_stock_products']} รายการ";
        }

        return "คุณคือผู้ช่วย AI ของระบบ Oboun ERP ระบบจัดการร้านขายยาและคลังสินค้า
        
หน้าที่ของคุณ:
1. ช่วยค้นหาข้อมูลในระบบ (สินค้า, ลูกค้า, Suppliers, คำสั่งซื้อ)
2. ให้คำแนะนำเกี่ยวกับการจัดการสินค้าและสต็อก
3. ตอบคำถามเกี่ยวกับการใช้งานระบบ ERP
4. แนะนำ actions ที่เกี่ยวข้องกับคำถามของผู้ใช้

{$statsInfo}

ตอบเป็นภาษาไทย กระชับ ชัดเจน และเป็นมิตร ใช้ emoji เพื่อเพิ่มความน่าสนใจ";
    }

    /**
     * Call Gemini API
     */
    private function callGeminiAPI(string $query, string $systemPrompt, array $context): string
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            throw new \Exception('Gemini API key not configured');
        }

        $contextText = '';
        if (!empty($context['products'])) {
            $contextText .= "\n\nสินค้าที่พบ:\n" . json_encode($context['products'], JSON_UNESCAPED_UNICODE);
        }
        if (!empty($context['patients'])) {
            $contextText .= "\n\nลูกค้าที่พบ:\n" . json_encode($context['patients'], JSON_UNESCAPED_UNICODE);
        }
        if (!empty($context['suppliers'])) {
            $contextText .= "\n\nSuppliers ที่พบ:\n" . json_encode($context['suppliers'], JSON_UNESCAPED_UNICODE);
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $systemPrompt . $contextText . "\n\nคำถามผู้ใช้: " . $query]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1024,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'ไม่สามารถประมวลผลได้';
        }

        throw new \Exception('Gemini API request failed: ' . $response->body());
    }

    /**
     * Generate fallback response when AI is not available
     */
    private function generateFallbackResponse(string $query, array $context): string
    {
        $response = "🔍 **ผลการค้นหาสำหรับ: \"{$query}\"**\n\n";

        $hasResults = false;

        if (!empty($context['products'])) {
            $hasResults = true;
            $count = count($context['products']);
            $response .= "📦 **สินค้า** ({$count} รายการ)\n";
            foreach (array_slice($context['products'], 0, 5) as $product) {
                $response .= "• {$product['name']} ({$product['generic_name']}) - ฿" . number_format($product['selling_price'], 2) . "\n";
            }
            $response .= "\n";
        }

        if (!empty($context['patients'])) {
            $hasResults = true;
            $count = count($context['patients']);
            $response .= "👥 **ลูกค้า** ({$count} คน)\n";
            foreach (array_slice($context['patients'], 0, 5) as $patient) {
                $response .= "• {$patient['name']} - {$patient['phone']}\n";
            }
            $response .= "\n";
        }

        if (!empty($context['suppliers'])) {
            $hasResults = true;
            $count = count($context['suppliers']);
            $response .= "🏭 **Suppliers** ({$count} ราย)\n";
            foreach (array_slice($context['suppliers'], 0, 5) as $supplier) {
                $response .= "• {$supplier['name']} ({$supplier['contact_person']})\n";
            }
            $response .= "\n";
        }

        if (!$hasResults) {
            $response .= "❌ ไม่พบข้อมูลที่ตรงกับคำค้นหา\n\n";
            $response .= "💡 **คำแนะนำ:**\n";
            $response .= "• ลองค้นหาด้วยคำอื่น\n";
            $response .= "• ตรวจสอบการสะกดคำ\n";
            $response .= "• ใช้คำค้นหาสั้นลง\n";
        }

        // Add quick stats
        if (isset($context['stats'])) {
            $stats = $context['stats'];
            $response .= "\n📊 **สถิติระบบ:**\n";
            $response .= "• สินค้าทั้งหมด: {$stats['total_products']} รายการ\n";
            $response .= "• ลูกค้าทั้งหมด: {$stats['total_patients']} คน\n";
            if ($stats['low_stock_products'] > 0) {
                $response .= "⚠️ สินค้า Stock ต่ำ: {$stats['low_stock_products']} รายการ\n";
            }
        }

        return $response;
    }
}

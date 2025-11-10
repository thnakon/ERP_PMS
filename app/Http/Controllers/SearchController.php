<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // สมมติ
use App\Models\Patient; // สมมติ
use Illuminate\Support\Facades\Log; // [!!! เพิ่มอันนี้]
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    // Controller สำหรับ Live Search (ต้องคืนค่าเป็น JSON)
    public function liveSearch(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'LIKE', "%{$query}%")->limit(3)->get();
        $patients = Patient::where('name', 'LIKE', "%{$query}%")->limit(3)->get();

        // คืนค่าเป็น JSON ให้ JavaScript
        return response()->json([
            'products' => $products,
            'patients' => $patients,
        ]);
    }

    // Controller สำหรับ Search ปกติ (คืนค่าเป็น View)
    public function fullSearch(Request $request)
    {
        $query = $request->input('q');

        // [!!! เพิ่ม 2 บรรทัดนี้]
        $products = \App\Models\Product::where('name', 'LIKE', "%{$query}%")->get();
        $patients = \App\Models\Patient::where('name', 'LIKE', "%{$query}%")->get();

        return view('search.search-results', [
            'query' => $query,
            'products' => $products, // <--- ส่งตัวแปรที่ Query ได้
            'patients' => $patients  // <--- ส่งตัวแปรที่ Query ได้
        ]);
    }

    // Controller สำหรับ AI Search (คืนค่าเป็น View)
    public function aiSearch(Request $request)
    {
        $query = $request->input('q');

        // [!!! เพิ่มบรรทัดนี้] (นี่คือข้อมูลจำลองสำหรับเทส)
        $aiResult = "นี่คือคำตอบจาก AI สำหรับคำถาม: '" . $query . "'\n\nนี่คือบรรทัดที่สองของคำตอบครับ";

        return view('search.ai-search-results', [
            'query' => $query,
            'aiResult' => $aiResult // <--- ส่งตัวแปร AI ที่สร้างไว้
        ]);
    }

    
}

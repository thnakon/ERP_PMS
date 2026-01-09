<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductLot;
use Illuminate\Http\Request;

class ProductLotApiController extends Controller
{
    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        $status = $request->get('status', 'all');

        $query = ProductLot::with('product')
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc');

        if ($status === 'expired') {
            $query->expired();
        } elseif ($status !== 'all') {
            $query->expiringWithin($days);
        }

        return response()->json($query->limit(100)->get());
    }

    public function export(Request $request)
    {
        // CSV export handled by web route
        return response()->json(['error' => 'Use web route for export'], 400);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with('product');

        if ($request->has('expiring_soon')) {
            $query->where('expiry_date', '<=', now()->addDays(30));
        }

        return response()->json($query->paginate(20));
    }

    // Manual adjustment
    public function update(Request $request, Batch $batch)
    {
        $request->validate(['quantity' => 'required|integer']);
        $batch->update(['quantity' => $request->quantity]);
        return response()->json($batch);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProductLot;
use Illuminate\Http\Request;

class ExpiryController extends Controller
{
    /**
     * Show expiry management page.
     */
    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = ProductLot::with(['product', 'supplierRel'])
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc');

        // Search by product name, sku, or lot number
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_th', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                })->orWhere('lot_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status === 'expired') {
            $query->expired();
        } elseif ($status !== 'all') {
            $query->expiringWithin($days);
        }

        $lots = $query->paginate(12)->withQueryString();

        // Stats (filtered by search if exists)
        $statsQuery = ProductLot::where('quantity', '>', 0);
        if ($search) {
            $statsQuery->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_th', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                })->orWhere('lot_number', 'like', "%{$search}%");
            });
        }

        $stats = [
            'expired' => (clone $statsQuery)->expired()->count(),
            'critical' => (clone $statsQuery)->expiringWithin(7)->count(),
            'warning' => (clone $statsQuery)->expiringWithin(30)->count() - (clone $statsQuery)->expiringWithin(7)->count(),
            'notice' => (clone $statsQuery)->expiringWithin(90)->count() - (clone $statsQuery)->expiringWithin(30)->count(),
            'total' => $lots->total(),
        ];

        return view('expiry.index', compact('lots', 'stats', 'days', 'status', 'search'));
    }

    /**
     * Export expiry report.
     */
    public function export(Request $request)
    {
        $days = $request->get('days', 30);

        $lots = ProductLot::with('product')
            ->expiringWithin($days)
            ->orderBy('expiry_date', 'asc')
            ->get();

        // Simple CSV export
        $filename = 'expiry_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($lots) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Product',
                'SKU',
                'Lot Number',
                'Expiry Date',
                'Days Until Expiry',
                'Quantity',
                'Status'
            ]);

            // Data rows
            foreach ($lots as $lot) {
                fputcsv($file, [
                    $lot->product->name,
                    $lot->product->sku,
                    $lot->lot_number,
                    $lot->expiry_date->format('Y-m-d'),
                    $lot->days_until_expiry,
                    $lot->quantity,
                    $lot->expiry_status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

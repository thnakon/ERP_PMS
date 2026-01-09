<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BarcodeController extends Controller
{
    /**
     * Show the barcode scanner page
     */
    public function index()
    {
        return view('barcode.index');
    }

    /**
     * Show the label printing page
     */
    public function labels()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->select('id', 'name', 'name_th', 'sku', 'barcode', 'unit_price')
            ->get();

        return view('barcode.labels', compact('products'));
    }

    /**
     * Lookup product by barcode/SKU/name/ID via API
     */
    public function lookup(Request $request)
    {
        $code = trim($request->get('code', ''));

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => __('barcode.enter_code')
            ]);
        }

        // First, try exact match on barcode or SKU (case-insensitive)
        $product = Product::where(function ($query) use ($code) {
            $query->whereRaw('LOWER(barcode) = ?', [strtolower($code)])
                ->orWhereRaw('LOWER(sku) = ?', [strtolower($code)]);
        })->first();

        // If not found, try matching by ID (if numeric)
        if (!$product && is_numeric($code)) {
            $product = Product::find((int) $code);
        }

        // If still not found, try partial match on name or generic name
        if (!$product) {
            $product = Product::where('is_active', true)
                ->where(function ($query) use ($code) {
                    $query->where('name', 'like', "%{$code}%")
                        ->orWhere('name_th', 'like', "%{$code}%")
                        ->orWhere('generic_name', 'like', "%{$code}%")
                        ->orWhere('barcode', 'like', "%{$code}%")
                        ->orWhere('sku', 'like', "%{$code}%");
                })
                ->first();
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => __('barcode.product_not_found')
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'name_th' => $product->name_th,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'unit_price' => $product->unit_price,
                'member_price' => $product->member_price,
                'stock_qty' => $product->stock_qty,
                'category' => $product->category?->name,
                'drug_class' => $product->drug_class,
                'image_url' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                'url' => route('products.show', $product->id),
            ]
        ]);
    }

    /**
     * Generate label data for printing
     */
    public function generateLabels(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:100',
            'label_size' => 'required|in:small,medium,large',
            'show_barcode' => 'boolean',
            'show_price' => 'boolean',
            'show_sku' => 'boolean',
        ]);

        $labelData = [];

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $labelData[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'name_th' => $product->name_th,
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'unit_price' => $product->unit_price,
                        'member_price' => $product->member_price,
                    ];
                }
            }
        }

        return view('barcode.print-labels', [
            'labels' => $labelData,
            'labelSize' => $request->label_size,
            'showBarcode' => $request->boolean('show_barcode', true),
            'showPrice' => $request->boolean('show_price', true),
            'showSku' => $request->boolean('show_sku', true),
        ]);
    }

    /**
     * Quick add to POS cart via barcode scan
     */
    public function addToCart(Request $request)
    {
        $code = trim($request->get('code', ''));

        // First, try exact match on barcode or SKU (case-insensitive)
        $product = Product::where(function ($query) use ($code) {
            $query->whereRaw('LOWER(barcode) = ?', [strtolower($code)])
                ->orWhereRaw('LOWER(sku) = ?', [strtolower($code)]);
        })->first();

        // If not found, try matching by ID (if numeric)
        if (!$product && is_numeric($code)) {
            $product = Product::find((int) $code);
        }

        // If still not found, try partial match
        if (!$product) {
            $product = Product::where('is_active', true)
                ->where(function ($query) use ($code) {
                    $query->where('name', 'like', "%{$code}%")
                        ->orWhere('name_th', 'like', "%{$code}%")
                        ->orWhere('barcode', 'like', "%{$code}%")
                        ->orWhere('sku', 'like', "%{$code}%");
                })
                ->first();
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => __('barcode.product_not_found')
            ]);
        }

        if ($product->stock_qty <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('barcode.out_of_stock'),
                'product' => $product->name
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'unit_price' => $product->unit_price,
                'stock_qty' => $product->stock_qty,
            ]
        ]);
    }
}

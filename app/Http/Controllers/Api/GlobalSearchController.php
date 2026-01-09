<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Prescription;
use App\Models\ControlledDrugLog;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'customers' => [],
                'orders' => [],
                'users' => [],
                'prescriptions' => [],
                'controlled_drugs' => [],
            ]);
        }

        // Search Products
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('name_th', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%")
                    ->orWhere('generic_name', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'name_th', 'sku', 'unit_price', 'stock_qty')
            ->limit(5)
            ->get();

        // Search Customers
        $customers = Customer::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('nickname', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        })
            ->select('id', 'name', 'phone', 'email', 'member_tier')
            ->limit(5)
            ->get();

        // Search Orders
        $orders = Order::where(function ($q) use ($query) {
            $q->where('order_number', 'like', "%{$query}%");
        })
            ->select('id', 'order_number', 'total_amount', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Search Users (staff)
        $users = User::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%");
        })
            ->select('id', 'name', 'email', 'role')
            ->limit(5)
            ->get();

        // Search Prescriptions
        $prescriptions = Prescription::with('customer:id,name')
            ->where(function ($q) use ($query) {
                $q->where('prescription_number', 'like', "%{$query}%")
                    ->orWhere('doctor_name', 'like', "%{$query}%")
                    ->orWhere('hospital_clinic', 'like', "%{$query}%")
                    ->orWhereHas('customer', function ($c) use ($query) {
                        $c->where('name', 'like', "%{$query}%");
                    });
            })
            ->select('id', 'prescription_number', 'customer_id', 'doctor_name', 'status', 'prescription_date')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Search Controlled Drug Logs
        $controlledDrugs = ControlledDrugLog::with(['product:id,name', 'customer:id,name'])
            ->where(function ($q) use ($query) {
                $q->where('log_number', 'like', "%{$query}%")
                    ->orWhere('customer_name', 'like', "%{$query}%")
                    ->orWhere('customer_id_card', 'like', "%{$query}%")
                    ->orWhereHas('product', function ($p) use ($query) {
                        $p->where('name', 'like', "%{$query}%");
                    });
            })
            ->select('id', 'log_number', 'product_id', 'customer_id', 'customer_name', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'products' => $products,
            'customers' => $customers,
            'orders' => $orders,
            'users' => $users,
            'prescriptions' => $prescriptions,
            'controlled_drugs' => $controlledDrugs,
        ]);
    }
}

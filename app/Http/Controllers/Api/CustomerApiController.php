<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function index()
    {
        return response()->json(
            Customer::where('is_active', true)
                ->orderBy('name')
                ->limit(100)
                ->get()
        );
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $customers = Customer::where('name', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    public function show(Customer $customer)
    {
        return response()->json($customer->load('orders'));
    }
}

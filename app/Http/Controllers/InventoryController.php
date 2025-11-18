<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function manageProducts()
    {
        return view('inventorys.manage-products',);
    }

    public function categories()
    {
        return view('inventorys.categories',);
    }

    public function expiryManagement()
    {
        return view('inventorys.expiry-management',);
    }

    public function stockAdjustments()
    {
        return view('inventorys.stock-adjustments',);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function suppliers()
    {
        return view('purchasing.suppliers',);
    }

    public function purchaseOrders()
    {
        return view('purchasing.purchase-orders',);
    }

    public function goodsReceived()
    {
        return view('purchasing.goods-received',);
    }
}

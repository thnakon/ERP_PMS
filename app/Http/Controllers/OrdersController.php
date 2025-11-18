<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * แสดงหน้าประวัติการขาย (รายการบิล) ทั้งหมด
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('orders-sales');
    }
}
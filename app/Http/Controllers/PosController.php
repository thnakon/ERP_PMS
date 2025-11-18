<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * แสดงหน้าจอหลักของระบบขายหน้าร้าน (Point of Sale - POS)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pos',);
    }
}
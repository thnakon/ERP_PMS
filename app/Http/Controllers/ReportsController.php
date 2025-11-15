<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display the sales report page.
     * [!!!] แก้ไข: เปลี่ยนจาก index() เป็น sales() ให้ตรงกับ web.php
     *
     * @return \Illuminate\View\View
     */
    public function sales()
    {
        // นี่คือตัวอย่างข้อมูล Mockup ที่คุณสามารถส่งไปยัง View
        // ในสถานการณ์จริง คุณจะดึงข้อมูลนี้จาก Database
        $staffList = [
            ['id' => 1, 'name' => 'เภสัชกร ก.'],
            ['id' => 2, 'name' => 'เภสัชกร ข.'],
            ['id' => 3, 'name' => 'พนักงาน ค.'],
        ];

        $categoriesList = [
            ['id' => 'danger', 'name' => 'ยาอันตราย'],
            ['id' => 'cosmetic', 'name' => 'เวชสำอาง'],
            ['id' => 'medical_eq', 'name' => 'อุปกรณ์การแพทย์'],
            ['id' => 'supplement', 'name' => 'อาหารเสริม'],
        ];

        // ส่งข้อมูลไปยัง View
        return view('reports.sale-report', [
            'staffList' => $staffList,
            'categoriesList' => $categoriesList
        ]);
    }

    /**
     * [!!!] เพิ่ม: Method นี้ขาดหายไปในไฟล์เดิม
     * Display the finance report page.
     *
     * @return \Illuminate\View\View
     */
    public function finance()
    {
        // TODO: ส่งข้อมูลที่จำเป็นสำหรับหน้ารายงานการเงิน
        return view('reports.finance-report');
    }

    /**
     * [!!!] เพิ่ม: Method นี้ขาดหายไปในไฟล์เดิม
     * Display the inventory report page.
     *
     * @return \Illuminate\View\View
     */
    public function inventory()
    {
        // TODO: ส่งข้อมูลที่จำเป็นสำหรับหน้ารายงานสต็อก
        return view('reports.inventory-report');
    }
}
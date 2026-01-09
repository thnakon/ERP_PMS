<?php

return [
    // Page titles
    'title' => 'ใบสั่งซื้อ',
    'add_new' => 'สร้างใบสั่งซื้อ',
    'edit' => 'แก้ไขใบสั่งซื้อ',
    'view' => 'ดูใบสั่งซื้อ',

    // Stats
    'total_orders' => 'ใบสั่งซื้อทั้งหมด',
    'draft_orders' => 'ร่าง',
    'sent_orders' => 'ส่งแล้ว/รอของ',
    'completed_orders' => 'รับครบแล้ว',

    // Status
    'status' => 'สถานะ',
    'draft' => 'ร่าง',
    'sent' => 'ส่งแล้ว',
    'partial' => 'รับบางส่วน',
    'completed' => 'รับครบแล้ว',
    'cancelled' => 'ยกเลิก',

    // Form labels
    'po_number' => 'เลขที่ใบสั่งซื้อ',
    'supplier' => 'ผู้จำหน่าย',
    'select_supplier' => 'เลือกผู้จำหน่าย',
    'order_date' => 'วันที่สั่งซื้อ',
    'expected_date' => 'วันที่คาดว่าจะได้รับ',
    'created_by' => 'ผู้สร้าง',

    // Items
    'items' => 'รายการสินค้า',
    'add_item' => 'เพิ่มรายการ',
    'product' => 'สินค้า',
    'select_product' => 'เลือกสินค้า',
    'ordered_qty' => 'จำนวนที่สั่ง',
    'received_qty' => 'จำนวนที่รับแล้ว',
    'unit_cost' => 'ราคาต่อหน่วย',
    'discount' => 'ส่วนลด %',
    'line_total' => 'รวม',
    'remove_item' => 'ลบ',

    // Summary
    'subtotal' => 'รวมก่อนภาษี',
    'vat' => 'ภาษีมูลค่าเพิ่ม (7%)',
    'discount_amount' => 'ส่วนลด',
    'grand_total' => 'ยอดสุทธิ',
    'notes' => 'หมายเหตุ',

    // Actions
    'save_draft' => 'บันทึกเป็นร่าง',
    'send_order' => 'ส่งใบสั่งซื้อ',
    'cancel_order' => 'ยกเลิกใบสั่งซื้อ',
    'receive_goods' => 'รับสินค้า',
    'print' => 'พิมพ์',

    // Filters
    'all_status' => 'ทุกสถานะ',
    'sort_newest' => 'ใหม่สุดก่อน',
    'sort_oldest' => 'เก่าสุดก่อน',

    // Messages
    'created' => 'สร้างใบสั่งซื้อเรียบร้อยแล้ว',
    'updated' => 'แก้ไขใบสั่งซื้อเรียบร้อยแล้ว',
    'deleted' => 'ลบใบสั่งซื้อเรียบร้อยแล้ว',
    'sent' => 'ส่งใบสั่งซื้อเรียบร้อยแล้ว',
    'cancelled' => 'ยกเลิกใบสั่งซื้อแล้ว',
    'cannot_edit_non_draft' => 'ไม่สามารถแก้ไขใบสั่งซื้อที่ไม่ใช่ร่างได้',
    'cannot_delete_non_draft' => 'ไม่สามารถลบใบสั่งซื้อที่ไม่ใช่ร่างได้',
    'cannot_cancel' => 'ไม่สามารถยกเลิกใบสั่งซื้อที่รับครบหรือยกเลิกแล้วได้',
    'already_sent' => 'ใบสั่งซื้อถูกส่งไปแล้ว',

    // Empty state
    'no_orders' => 'ไม่พบใบสั่งซื้อ',
    'no_items' => 'ยังไม่มีรายการสินค้า',

    // Details
    'order_info' => 'ข้อมูลใบสั่งซื้อ',
    'supplier_info' => 'ข้อมูลผู้จำหน่าย',
    'items_list' => 'รายการสินค้า',
    'remaining' => 'คงเหลือ',
    'history' => 'ประวัติการรับของ',
];

<?php

return [
    // Page titles
    'title' => 'โปรโมชั่นและส่วนลด',
    'subtitle' => 'จัดการโปรโมชั่นและแคมเปญส่วนลด',
    'create_title' => 'สร้างโปรโมชั่น',
    'edit_title' => 'แก้ไขโปรโมชั่น',

    // Actions
    'add_promotion' => 'เพิ่มโปรโมชั่น',
    'edit_promotion' => 'แก้ไขโปรโมชั่น',
    'delete_promotion' => 'ลบโปรโมชั่น',
    'view_promotion' => 'ดูรายละเอียด',
    'activate' => 'เปิดใช้งาน',
    'deactivate' => 'ปิดใช้งาน',

    // Status
    'active' => 'ใช้งานอยู่',
    'inactive' => 'ปิดใช้งาน',
    'expired' => 'หมดอายุแล้ว',
    'scheduled' => 'กำหนดไว้',
    'featured' => 'โปรโมชั่นเด่น',

    // Types
    'type' => 'ประเภทโปรโมชั่น',
    'type_percentage' => 'ส่วนลดเปอร์เซ็นต์',
    'type_fixed' => 'ส่วนลดคงที่',
    'type_buy_x_get_y' => 'ซื้อ X แถม Y',
    'type_bundle' => 'ดีลชุดสินค้า',
    'type_free_item' => 'ของแถมฟรี',
    'type_tier' => 'ส่วนลดตามระดับสมาชิก',

    // Form fields
    'name' => 'ชื่อโปรโมชั่น',
    'name_th' => 'ชื่อภาษาไทย',
    'code' => 'รหัสโปรโมชั่น',
    'description' => 'รายละเอียด',
    'discount_value' => 'มูลค่าส่วนลด',
    'min_purchase' => 'ยอดซื้อขั้นต่ำ',
    'max_discount' => 'ส่วนลดสูงสุด',
    'buy_quantity' => 'จำนวนที่ต้องซื้อ',
    'get_quantity' => 'จำนวนที่ได้ฟรี',
    'start_date' => 'วันที่เริ่มต้น',
    'end_date' => 'วันที่สิ้นสุด',
    'active_days' => 'วันที่ใช้งานได้',
    'start_time' => 'เวลาเริ่มต้น',
    'end_time' => 'เวลาสิ้นสุด',
    'usage_limit' => 'จำนวนการใช้งานสูงสุด',
    'per_customer_limit' => 'ต่อลูกค้า',
    'select_tier' => 'เลือกระดับสมาชิก',
    'new_customers_only' => 'สำหรับลูกค้าใหม่เท่านั้น',
    'stackable' => 'ใช้ร่วมกับโปรโมชั่นอื่นได้',
    'is_featured' => 'โปรโมชั่นเด่น',
    'select_products' => 'เลือกสินค้า',
    'select_categories' => 'เลือกหมวดหมู่',
    'all_products' => 'สินค้าทั้งหมด',

    // Days
    'sunday' => 'วันอาทิตย์',
    'monday' => 'วันจันทร์',
    'tuesday' => 'วันอังคาร',
    'wednesday' => 'วันพุธ',
    'thursday' => 'วันพฤหัสบดี',
    'friday' => 'วันศุกร์',
    'saturday' => 'วันเสาร์',

    // Messages
    'created' => 'สร้างโปรโมชั่นสำเร็จ',
    'updated' => 'อัปเดตโปรโมชั่นสำเร็จ',
    'deleted' => 'ลบโปรโมชั่นสำเร็จ',
    'activated' => 'เปิดใช้งานโปรโมชั่นแล้ว',
    'deactivated' => 'ปิดใช้งานโปรโมชั่นแล้ว',
    'code_applied' => 'ใช้รหัสโปรโมชั่นสำเร็จ!',
    'code_not_found' => 'รหัสโปรโมชั่นไม่ถูกต้อง',
    'code_not_valid' => 'รหัสนี้ไม่สามารถใช้กับคำสั่งซื้อนี้ได้',
    'min_purchase_not_met' => 'ยอดซื้อขั้นต่ำ ฿:amount',
    'member_discount' => 'ส่วนลดสมาชิก :tier',

    // Statistics
    'total_promotions' => 'โปรโมชั่นทั้งหมด',
    'active_promotions' => 'โปรโมชั่นที่ใช้งานอยู่',
    'usage_count' => 'จำนวนการใช้งาน',
    'total_discount' => 'ยอดส่วนลดรวม',
    'expires_in' => 'หมดอายุใน :days วัน',
    'remaining_uses' => 'เหลือ :count ครั้ง',

    // Filters
    'filter_by_type' => 'กรองตามประเภท',
    'filter_by_status' => 'กรองตามสถานะ',
    'all_types' => 'ทุกประเภท',
    'all_statuses' => 'ทุกสถานะ',
    'search_promotions' => 'ค้นหาโปรโมชั่น...',

    // Empty state
    'no_promotions' => 'ไม่พบโปรโมชั่น',
    'no_promotions_desc' => 'สร้างโปรโมชั่นแรกเพื่อเพิ่มยอดขาย',

    // Confirm
    'confirm_delete' => 'คุณแน่ใจหรือไม่ที่จะลบโปรโมชั่นนี้?',
    'confirm_deactivate' => 'การปิดใช้งานจะหยุดโปรโมชั่นนี้ทันที',
];

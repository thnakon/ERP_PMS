<?php

return [
    // Page titles
    'title' => 'คนไข้ | ลูกค้า',
    'subtitle' => 'จัดการข้อมูลสุขภาพและระบบสมาชิก',

    // Stats
    'total_customers' => 'ลูกค้าทั้งหมด',
    'active_customers' => 'ใช้งานอยู่',
    'customers_with_allergies' => 'มีประวัติแพ้ยา',
    'platinum_members' => 'แพลทินัม',

    // Actions
    'add_customer' => 'เพิ่มลูกค้า',
    'edit_customer' => 'แก้ไขข้อมูล',
    'view_customer' => 'ดูข้อมูล',
    'delete_customer' => 'ลบลูกค้า',
    'all_customers' => 'ลูกค้าทั้งหมด',

    // Form sections
    'personal_info' => 'ข้อมูลส่วนตัว',
    'contact_info' => 'ข้อมูลติดต่อ',
    'medical_records' => 'ประวัติสุขภาพ',
    'drug_safety' => 'ความปลอดภัยด้านยา',
    'loyalty_info' => 'ระบบสมาชิก',

    // Fields
    'name' => 'ชื่อ-นามสกุล',
    'nickname' => 'ชื่อเล่น',
    'phone' => 'เบอร์โทรศัพท์',
    'email' => 'อีเมล',
    'birth_date' => 'วันเกิด',
    'age' => 'อายุ',
    'gender' => 'เพศ',
    'select_gender' => 'เลือกเพศ',
    'male' => 'ชาย',
    'female' => 'หญิง',
    'other' => 'อื่นๆ',
    'national_id' => 'เลขบัตรประชาชน',
    'address' => 'ที่อยู่',
    'line_id' => 'ไลน์ไอดี',

    // Medical
    'drug_allergies' => 'ประวัติการแพ้ยา',
    'allergy_notes' => 'ประวัติการแพ้ยา',
    'allergies' => 'แพ้ยา',
    'add_allergy' => 'เพิ่มยาที่แพ้',
    'drug_name' => 'ชื่อยา',
    'reaction' => 'อาการแพ้',
    'chronic_diseases' => 'โรคประจำตัว',
    'add_disease' => 'เพิ่มโรคประจำตัว',
    'pregnancy_status' => 'สถานะการตั้งครรภ์',
    'not_applicable' => 'ไม่เกี่ยวข้อง',
    'pregnant' => 'ตั้งครรภ์',
    'breastfeeding' => 'ให้นมบุตร',
    'medical_notes' => 'บันทึกทางการแพทย์',
    'allergy_warning' => '⚠️ แจ้งเตือน: มีประวัติแพ้ยา',
    'allergy_placeholder' => 'ระบุยาที่แพ้ (เช่น Penicillin, Aspirin, Sulfa)',
    'allergy_hint' => 'แยกรายการด้วยเครื่องหมายจุลภาค',

    // Loyalty
    'points_balance' => 'คะแนนสะสม',
    'member_tier' => 'ระดับสมาชิก',
    'regular' => 'ทั่วไป',
    'silver' => 'ซิลเวอร์',
    'gold' => 'โกลด์',
    'platinum' => 'แพลทินัม',
    'member_since' => 'สมาชิกตั้งแต่',
    'total_spent' => 'ยอดใช้จ่ายสะสม',
    'visit_count' => 'จำนวนครั้งที่มา',
    'visits' => 'ครั้ง',
    'last_visit' => 'มาครั้งล่าสุด',

    // Table headers
    'customer' => 'ลูกค้า',
    'contact' => 'ติดต่อ',
    'health_status' => 'สถานะสุขภาพ',
    'membership' => 'สมาชิก',
    'actions' => 'จัดการ',

    // Filters
    'filter_customers' => 'กรองลูกค้า',
    'all_tiers' => 'ทุกระดับ',
    'has_allergies' => 'มีประวัติแพ้ยา',

    // Messages
    'created' => 'เพิ่มลูกค้าเรียบร้อยแล้ว',
    'created_success' => 'เพิ่มลูกค้าเรียบร้อยแล้ว!',
    'updated' => 'อัปเดตข้อมูลลูกค้าเรียบร้อยแล้ว',
    'updated_success' => 'อัปเดตข้อมูลลูกค้าเรียบร้อยแล้ว!',
    'deleted' => 'ลบลูกค้าเรียบร้อยแล้ว',
    'deleted_success' => 'ลบลูกค้าเรียบร้อยแล้ว!',
    'no_customers' => 'ไม่พบข้อมูลลูกค้า',

    // Search
    'search_placeholder' => 'ค้นหาชื่อ, เบอร์โทร, หรือเลขบัตร...',

    // Placeholders
    'enter_name' => 'กรอกชื่อ-นามสกุล',
    'enter_nickname' => 'กรอกชื่อเล่น (ถ้ามี)',
    'enter_phone' => 'กรอกเบอร์โทรศัพท์',
    'enter_email' => 'กรอกอีเมล',
    'enter_national_id' => 'กรอกเลขบัตรประชาชน (ถ้ามี)',
    'enter_address' => 'กรอกที่อยู่',
    'enter_line_id' => 'กรอกไลน์ไอดี',
    'enter_drug_name' => 'กรอกชื่อยาที่แพ้',
    'enter_reaction' => 'อธิบายอาการแพ้',
    'enter_disease' => 'กรอกชื่อโรคประจำตัว',
    'enter_medical_notes' => 'กรอกบันทึกทางการแพทย์...',
    'enter_notes' => 'หมายเหตุเพิ่มเติม...',

    // Notes
    'notes' => 'หมายเหตุ',
    'years_old' => 'ปี',
];

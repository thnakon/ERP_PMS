<?php

return [
    // Page titles
    'title' => 'ยาควบคุม',
    'page_subtitle' => 'เภสัชกรรม',

    // Buttons & Actions
    'add_new' => 'บันทึกการจ่ายยา',
    'pending_approvals' => 'รออนุมัติ',
    'fda_report' => 'รายงาน อย.',
    'generate_report' => 'สร้างรายงาน',
    'print_report' => 'พิมพ์รายงาน',
    'view_details' => 'ดูรายละเอียด',
    'approve' => 'อนุมัติ',
    'reject' => 'ไม่อนุมัติ',
    'confirm_reject' => 'ยืนยันไม่อนุมัติ',

    // Stats
    'stat_total' => 'รายการทั้งหมด',
    'stat_pending' => 'รออนุมัติ',
    'stat_approved_today' => 'อนุมัติวันนี้',
    'stat_dangerous' => 'ยาอันตราย',
    'stat_specially_controlled' => 'ยาควบคุมพิเศษ',

    // Table headers
    'log_number' => 'เลขที่บันทึก',
    'drug' => 'ยา/เวชภัณฑ์',
    'drug_type' => 'ประเภทยา',
    'recipient' => 'ผู้รับยา',
    'id_card' => 'เลขบัตรประชาชน',
    'quantity' => 'จำนวน',
    'transaction_type' => 'ประเภทรายการ',
    'date' => 'วันที่',
    'status' => 'สถานะ',
    'approved_by' => 'ผู้อนุมัติ',
    'created_by' => 'ผู้บันทึก',

    // Status
    'status_pending' => 'รออนุมัติ',
    'status_approved' => 'อนุมัติแล้ว',
    'status_rejected' => 'ไม่อนุมัติ',
    'status_cancelled' => 'ยกเลิก',

    // Drug schedules
    'schedule_normal' => 'ยาสามัญประจำบ้าน',
    'schedule_dangerous' => 'ยาอันตราย',
    'schedule_specially_controlled' => 'ยาควบคุมพิเศษ',
    'schedule_narcotic' => 'ยาเสพติดให้โทษ',
    'schedule_psychotropic' => 'วัตถุออกฤทธิ์ต่อจิตประสาท',

    // Transaction types
    'trans_sale' => 'ขาย',
    'trans_dispense' => 'จ่ายตามใบสั่งยา',
    'trans_receive' => 'รับเข้า',
    'trans_return' => 'รับคืน',
    'trans_dispose' => 'ทำลาย',
    'trans_transfer' => 'โอนย้าย',

    // Form labels - Drug info
    'drug_info' => 'ข้อมูลยาควบคุม',
    'select_drug' => 'เลือกยา/เวชภัณฑ์',
    'drug_warning_dangerous' => '⚠️ ยาอันตราย - ต้องขายโดยเภสัชกร',
    'drug_warning_specially' => '⚠️ ยาควบคุมพิเศษ - ต้องมีใบสั่งแพทย์',
    'drug_warning_narcotic' => '⚠️ ยาเสพติดให้โทษ - ต้องมีใบสั่งแพทย์และบันทึกตามกฎหมาย',
    'drug_warning_psychotropic' => '⚠️ วัตถุออกฤทธิ์ต่อจิตประสาท - ต้องได้รับอนุมัติจากเภสัชกร',

    // Form labels - Recipient info  
    'recipient_info' => 'ข้อมูลผู้รับยา',
    'recipient_legal_note' => '(จำเป็นตามกฎหมาย)',
    'select_from_customers' => 'เลือกจากลูกค้าในระบบ',
    'or_enter_new' => '-- หรือกรอกข้อมูลใหม่ --',
    'full_name' => 'ชื่อ-นามสกุล',
    'id_card_number' => 'เลขบัตรประชาชน',
    'phone' => 'โทรศัพท์',
    'age' => 'อายุ',
    'address' => 'ที่อยู่',

    // Form labels - Prescription info
    'prescription_info' => 'ข้อมูลใบสั่งยา',
    'if_applicable' => '(ถ้ามี)',
    'prescription_number' => 'เลขที่ใบสั่งยา',
    'doctor_name' => 'ชื่อแพทย์ผู้สั่ง',
    'license_number' => 'เลขที่ใบประกอบวิชาชีพ',
    'hospital_clinic' => 'โรงพยาบาล/คลินิก',

    // Form labels - Purpose
    'purpose_section' => 'วัตถุประสงค์',
    'purpose' => 'วัตถุประสงค์การใช้ยา',
    'indication' => 'ข้อบ่งใช้',
    'notes' => 'หมายเหตุ',

    // Legal confirmation
    'legal_confirm' => 'ข้าพเจ้าขอรับรองว่าข้อมูลข้างต้นเป็นความจริง และเข้าใจว่าการจ่ายยาควบคุมต้องปฏิบัติตามกฎหมาย',
    'submit_log' => 'บันทึกการจ่ายยาควบคุม',

    // Details page
    'drug_details' => 'รายละเอียดยา',
    'recipient_details' => 'รายละเอียดผู้รับยา',
    'prescription_details' => 'รายละเอียดใบสั่งยา',
    'rejection_reason' => 'เหตุผลที่ไม่อนุมัติ',
    'rejection_reason_placeholder' => 'กรุณาระบุเหตุผลที่ไม่อนุมัติ (อย่างน้อย 10 ตัวอักษร)',

    // FDA Report
    'fda_report_title' => 'รายงานยาควบคุม อย.',
    'report_period' => 'ช่วงเวลารายงาน',
    'start_date' => 'วันที่เริ่มต้น',
    'end_date' => 'วันที่สิ้นสุด',
    'total_transactions' => 'รายการทั้งหมด',
    'movement_summary' => 'สรุปการเคลื่อนไหวยาควบคุม',
    'detailed_log' => 'รายละเอียดการจ่ายยา',
    'dispensed_out' => 'จ่ายออก',
    'received_in' => 'รับเข้า',
    'disposed' => 'ทำลาย',
    'transaction_count' => 'จำนวนรายการ',
    'legal_certification' => 'คำรับรอง',
    'certification_text' => 'ข้าพเจ้าขอรับรองว่ารายงานนี้ถูกต้องตามความเป็นจริง และเป็นไปตามพระราชบัญญัติยา พ.ศ. 2510 และประกาศกระทรวงสาธารณสุขที่เกี่ยวข้อง',
    'pharmacist_signature' => 'ลงชื่อ เภสัชกรผู้รับผิดชอบ',
    'authorized_signature' => 'ลงชื่อ ผู้มีอำนาจ',
    'signature_date' => 'วันที่ ___/___/______',

    // Pending page
    'pending_title' => 'รอเภสัชกรอนุมัติ',
    'pending_info' => 'รายการเหล่านี้เป็นการจ่ายยาอันตราย/ยาควบคุมที่ต้องได้รับการอนุมัติจากเภสัชกรก่อนดำเนินการ',
    'no_pending' => 'ไม่มีรายการรออนุมัติ',
    'no_pending_desc' => 'ขณะนี้ไม่มีรายการจ่ายยาควบคุมที่รอการอนุมัติ',
    'back_to_main' => 'กลับไปหน้าหลัก',

    // Filters
    'search_placeholder' => 'ค้นหาเลขที่บันทึก, ชื่อ, เลขบัตรประชาชน...',
    'filter_all' => 'ทั้งหมด',
    'filter_by_status' => 'กรองตามสถานะ',
    'filter_by_drug_type' => 'กรองตามประเภทยา',

    // Messages
    'logged_and_approved' => 'บันทึกการจ่ายยาควบคุมและอนุมัติเรียบร้อยแล้ว',
    'logged_pending_approval' => 'บันทึกการจ่ายยาควบคุมเรียบร้อยแล้ว รอเภสัชกรอนุมัติ',
    'approved_successfully' => 'อนุมัติการจ่ายยาเรียบร้อยแล้ว',
    'rejected_successfully' => 'ไม่อนุมัติการจ่ายยาเรียบร้อยแล้ว',
    'already_processed' => 'รายการนี้ได้รับการดำเนินการแล้ว',
    'not_authorized' => 'คุณไม่มีสิทธิ์ดำเนินการนี้ ต้องเป็นเภสัชกรหรือผู้ดูแลระบบเท่านั้น',

    // Empty states
    'no_records' => 'ไม่พบบันทึกการจ่ายยาควบคุม',
    'no_data_in_period' => 'ไม่มีข้อมูลในช่วงเวลาที่เลือก',

    // Sort options
    'sort_newest' => 'ใหม่สุดก่อน',
    'sort_oldest' => 'เก่าสุดก่อน',
];

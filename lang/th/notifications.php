<?php

return [
    'title' => 'การแจ้งเตือน',
    'page_subtitle' => 'ระบบอัตโนมัติ',
    'settings' => 'ตั้งค่า',

    // Stats
    'total_alerts' => 'การแจ้งเตือนทั้งหมด',
    'expiring_soon' => 'ใกล้หมดอายุ',
    'low_stock' => 'สต๊อกต่ำ',
    'refill_reminders' => 'ยาต่อเนื่อง',

    // Filters
    'filter_all' => 'ทั้งหมด',
    'expiring' => 'หมดอายุ',
    'stock' => 'สต๊อก',
    'refill' => 'ยาต่อเนื่อง',
    'items' => 'รายการ',

    // Types
    'type_expiring' => 'สินค้าใกล้หมดอายุ',
    'type_stock' => 'สต๊อกต่ำกว่ากำหนด',
    'type_refill' => 'ลูกค้ารับยาต่อเนื่อง',

    // Labels
    'status' => 'สถานะ',
    'details' => 'รายละเอียด',
    'priority' => 'ความสำคัญ',
    'priority_high' => 'ด่วน',
    'priority_medium' => 'ปานกลาง',
    'priority_low' => 'ปกติ',

    // Messages
    'expires_in_days' => '{0} หมดอายุวันนี้|{1} หมดอายุพรุ่งนี้|[2,*] หมดอายุใน :days วัน',
    'stock_below_minimum' => 'สต๊อกต่ำกว่าขั้นต่ำที่กำหนด',
    'current_stock' => 'คงเหลือ',
    'qty_remaining' => 'จำนวนคงเหลือ',
    'lot' => 'ล็อต',
    'refill_overdue' => 'เกินกำหนดรับยาแล้ว!',
    'refill_due_in' => '{0} ถึงกำหนดรับยาวันนี้|{1} รับยาพรุ่งนี้|[2,*] รับยาใน :days วัน',
    'prescription' => 'ใบสั่งยา',
    'unknown_customer' => 'ลูกค้าไม่ระบุชื่อ',

    // Actions
    'dismiss' => 'ปิดการแจ้งเตือน',
    'dismissed' => 'ปิดการแจ้งเตือนแล้ว',

    // Empty state
    'all_clear' => 'ไม่มีการแจ้งเตือน!',
    'no_alerts' => 'ระบบของคุณทำงานปกติ ไม่มีรายการที่ต้องดำเนินการ',

    // Settings
    'notification_settings' => 'ตั้งค่าการแจ้งเตือน',
    'line_token' => 'Line Notify Token',
    'line_token_help' => 'รับ Token ได้ที่',
    'line_token_required' => 'กรุณาใส่ Line Token',
    'test_line' => 'ทดสอบการเชื่อมต่อ Line',
    'testing' => 'กำลังทดสอบ',
    'line_test_success' => 'เชื่อมต่อ Line สำเร็จ!',
    'line_test_failed' => 'ไม่สามารถเชื่อมต่อ Line ได้',
    'line_messaging_desc' => 'ใช้สำหรับส่งข้อความแจ้งเตือนผ่าน LINE',
    'line_channel_token' => 'Channel Access Token',
    'line_channel_secret' => 'Channel Secret',
    'line_user_id' => 'User ID / Group ID',
    'line_user_id_help' => 'User ID หรือ Group ID ที่ต้องการรับแจ้งเตือน',
    'line_messaging_help' => 'สร้าง Messaging API Channel ได้ที่',
    'enable_push' => 'เปิดการแจ้งเตือน Push',
    'enable_email' => 'เปิดการแจ้งเตือนทาง Email',
    'enable_line' => 'เปิดการแจ้งเตือนทาง Line',
    'channels' => 'ช่องทางการแจ้งเตือน',
    'channels_desc' => 'เลือกช่องทางที่ต้องการรับการแจ้งเตือน',
    'push_desc' => 'แจ้งเตือนผ่านเบราว์เซอร์',
    'email_desc' => 'ส่งอีเมลไปยังผู้ดูแล',
    'line_desc' => 'ส่งข้อความผ่าน LINE',
    'email_settings' => 'ตั้งค่าอีเมล',
    'sender_email' => 'อีเมลผู้ส่ง',
    'sender_name' => 'ชื่อผู้ส่ง',
    'recipient_email' => 'อีเมลผู้รับ',
    'recipient_email_help' => 'อีเมลที่จะได้รับการแจ้งเตือน',
    'gmail_app_password' => 'App Password',
    'gmail_app_password_help' => 'สร้าง App Password ได้ที่',
    'test_email' => 'ทดสอบส่งอีเมล',
    'email_required' => 'กรุณากรอกอีเมลผู้รับ',
    'email_test_success' => 'ส่งอีเมลสำเร็จ!',
    'email_test_failed' => 'ไม่สามารถส่งอีเมลได้',
    'timing_settings' => 'ตั้งค่าเวลา',
    'timing_desc' => 'กำหนดระยะเวลาการแจ้งเตือนล่วงหน้า',
    'expiry_days_help' => 'แจ้งเตือนก่อนสินค้าหมดอายุ',
    'refill_days_help' => 'แจ้งเตือนก่อนถึงกำหนดรับยา',
    'expiry_days_before' => 'แจ้งเตือนก่อนหมดอายุ (วัน)',
    'refill_days_before' => 'แจ้งเตือนก่อนวันรับยา (วัน)',
    'save_settings' => 'บันทึกการตั้งค่า',
    'settings_saved' => 'บันทึกการตั้งค่าเรียบร้อยแล้ว',
];

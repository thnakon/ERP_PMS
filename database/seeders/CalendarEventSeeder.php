<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('status', 'active')->get();
        $customers = Customer::take(10)->get();

        $adminUser = User::where('role', 'admin')->first();
        $createdBy = $adminUser ? $adminUser->id : 1;

        // Current month and next month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonth()->endOfMonth();

        // ===== SHIFT EVENTS (ตารางเวร) =====
        // Create daily shifts for pharmacists
        $shifts = [
            ['start' => '08:00', 'end' => '16:00', 'name' => 'รอบเช้า'],
            ['start' => '16:00', 'end' => '22:00', 'name' => 'รอบบ่าย'],
        ];

        $day = $startDate->copy();
        while ($day <= $endDate) {
            // Skip Sundays for shifts (ร้านหยุดวันอาทิตย์)
            if ($day->dayOfWeek !== Carbon::SUNDAY) {
                foreach ($shifts as $index => $shift) {
                    $staff = $users->count() > 0 ? $users->random() : null;
                    CalendarEvent::create([
                        'type' => 'shift',
                        'title' => $shift['name'] . ($staff ? ': ' . $staff->name : ''),
                        'description' => 'ตารางเวรประจำวัน',
                        'start_time' => $day->copy()->setTimeFromTimeString($shift['start']),
                        'end_time' => $day->copy()->setTimeFromTimeString($shift['end']),
                        'staff_id' => $staff?->id,
                        'color' => CalendarEvent::$typeColors['shift'],
                        'status' => $day->isPast() ? 'completed' : 'confirmed',
                        'created_by' => $createdBy,
                    ]);
                }
            }
            $day->addDay();
        }

        // ===== HOLIDAY EVENTS (วันหยุด) =====
        $holidays = [
            ['date' => '2026-01-01', 'title' => 'วันขึ้นปีใหม่'],
            ['date' => '2026-01-18', 'title' => 'วันกองทัพไทย'],
            ['date' => '2026-02-14', 'title' => 'วันวาเลนไทน์'],
            ['date' => '2026-02-26', 'title' => 'วันมาฆบูชา'],
        ];

        foreach ($holidays as $holiday) {
            $holidayDate = Carbon::parse($holiday['date']);
            if ($holidayDate >= $startDate && $holidayDate <= $endDate) {
                CalendarEvent::create([
                    'type' => 'holiday',
                    'title' => $holiday['title'],
                    'description' => 'วันหยุดราชการ',
                    'start_time' => $holidayDate->startOfDay(),
                    'end_time' => $holidayDate->endOfDay(),
                    'all_day' => true,
                    'color' => CalendarEvent::$typeColors['holiday'],
                    'status' => 'confirmed',
                    'created_by' => $createdBy,
                ]);
            }
        }

        // ===== APPOINTMENT EVENTS (นัดหมายลูกค้า) =====
        if ($customers->count() > 0) {
            $appointmentTypes = [
                'ตรวจสุขภาพประจำปี',
                'รับยาต่อเนื่อง',
                'ปรึกษาเภสัชกร',
                'ฉีดวัคซีน',
                'วัดความดัน',
                'ตรวจน้ำตาลในเลือด',
            ];

            // Add 15 random appointments
            for ($i = 0; $i < 15; $i++) {
                $appointmentDate = Carbon::now()->addDays(rand(1, 45));
                $hour = rand(9, 18);
                $customer = $customers->random();

                CalendarEvent::create([
                    'type' => 'appointment',
                    'title' => $appointmentTypes[array_rand($appointmentTypes)],
                    'description' => 'นัดหมายลูกค้า: ' . $customer->name,
                    'start_time' => $appointmentDate->copy()->setHour($hour)->setMinute(0),
                    'end_time' => $appointmentDate->copy()->setHour($hour)->setMinute(30),
                    'customer_id' => $customer->id,
                    'color' => CalendarEvent::$typeColors['appointment'],
                    'status' => 'pending',
                    'created_by' => $createdBy,
                ]);
            }
        }

        // ===== REMINDER EVENTS (แจ้งเตือน) =====
        $reminders = [
            ['offset' => 2, 'title' => 'ตรวจนับสต็อกรายสัปดาห์', 'time' => '09:00'],
            ['offset' => 5, 'title' => 'ส่งรายงานยอดขายประจำสัปดาห์', 'time' => '17:00'],
            ['offset' => 7, 'title' => 'ตรวจสอบยาใกล้หมดอายุ', 'time' => '10:00'],
            ['offset' => 10, 'title' => 'ประชุมทีมประจำเดือน', 'time' => '14:00'],
            ['offset' => 14, 'title' => 'ทำความสะอาดตู้เย็นยา', 'time' => '08:00'],
            ['offset' => 20, 'title' => 'อัปเดตราคายา', 'time' => '11:00'],
            ['offset' => 25, 'title' => 'ต่ออายุใบอนุญาตร้าน', 'time' => '09:00'],
            ['offset' => 30, 'title' => 'สั่งซื้อเวชภัณฑ์ประจำเดือน', 'time' => '10:00'],
        ];

        foreach ($reminders as $reminder) {
            $reminderDate = Carbon::now()->addDays($reminder['offset']);
            CalendarEvent::create([
                'type' => 'reminder',
                'title' => $reminder['title'],
                'description' => 'การแจ้งเตือนอัตโนมัติ',
                'start_time' => $reminderDate->copy()->setTimeFromTimeString($reminder['time']),
                'end_time' => $reminderDate->copy()->setTimeFromTimeString($reminder['time'])->addHour(),
                'color' => CalendarEvent::$typeColors['reminder'],
                'status' => 'pending',
                'created_by' => $createdBy,
            ]);
        }

        // ===== OTHER EVENTS (อื่นๆ) =====
        $otherEvents = [
            ['offset' => 3, 'title' => 'ซ่อมแอร์', 'time' => '10:00', 'desc' => 'ช่างมาซ่อมแอร์ห้องยา'],
            ['offset' => 8, 'title' => 'ถ่ายรูปโปรโมชั่น', 'time' => '15:00', 'desc' => 'ถ่ายรูปสินค้าสำหรับโพสโซเชียล'],
            ['offset' => 12, 'title' => 'ติดตั้งระบบ POS', 'time' => '09:00', 'desc' => 'ติดตั้งเครื่อง POS ใหม่'],
            ['offset' => 18, 'title' => 'ตรวจจากกรมอาหารและยา', 'time' => '14:00', 'desc' => 'เจ้าหน้าที่ อย. มาตรวจ'],
            ['offset' => 22, 'title' => 'ทำบุญร้าน', 'time' => '07:00', 'desc' => 'ทำบุญเลี้ยงพระประจำปี'],
            ['offset' => 35, 'title' => 'อบรมพนักงาน', 'time' => '13:00', 'desc' => 'อบรมการใช้งานระบบใหม่'],
        ];

        foreach ($otherEvents as $event) {
            $eventDate = Carbon::now()->addDays($event['offset']);
            CalendarEvent::create([
                'type' => 'other',
                'title' => $event['title'],
                'description' => $event['desc'],
                'start_time' => $eventDate->copy()->setTimeFromTimeString($event['time']),
                'end_time' => $eventDate->copy()->setTimeFromTimeString($event['time'])->addHours(2),
                'color' => CalendarEvent::$typeColors['other'],
                'status' => 'pending',
                'created_by' => $createdBy,
            ]);
        }

        $this->command->info('Created calendar events for all types: Shift, Holiday, Appointment, Reminder, Other');
    }
}

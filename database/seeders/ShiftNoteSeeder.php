<?php

namespace Database\Seeders;

use App\Models\ShiftNote;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShiftNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $notes = [
            [
                'content' => "ลูกค้าคุณสมชาย จะมารับยาตอนเย็นนะครับ ฝากจัดเตรียมไว้ให้ด้วย",
                'color' => 'yellow',
                'is_pinned' => true,
            ],
            [
                'content' => "เบิกยา Amoxicillin 500mg มาเพิ่มแล้ว 10 กล่อง อยู่ในตู้เก็บยาสำรอง",
                'color' => 'green',
                'is_pinned' => false,
            ],
            [
                'content' => "เครื่องปริ้นท์ใบเสร็จกระดาษใกล้หมด มีสำรองอยู่ในลิ้นชักล่างสุด",
                'color' => 'pink',
                'is_pinned' => false,
            ],
            [
                'content' => "พรุ่งนี้เช้ามีนัดส่งของจากซัพพลายเออร์ A ตอน 9 โมงตรง",
                'color' => 'blue',
                'is_pinned' => true,
            ],
            [
                'content' => "อย่าลืมเช็คอุณหภูมิตู้เย็นเก็บยาก่อนปิดร้านด้วยนะครับ",
                'color' => 'purple',
                'is_pinned' => false,
            ],
        ];

        foreach ($notes as $note) {
            ShiftNote::create([
                'user_id' => $user->id,
                'content' => $note['content'],
                'color' => $note['color'],
                'is_pinned' => $note['is_pinned'],
            ]);
        }
    }
}

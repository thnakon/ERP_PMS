<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $actions = ['login', 'logout', 'create', 'update', 'delete', 'view', 'print', 'export'];
        $modules = ['Products', 'Customers', 'Orders', 'Inventory', 'Users', 'Settings', 'POS', 'Categories', 'Suppliers'];

        $descriptions = [
            'login' => ['เข้าสู่ระบบสำเร็จ', 'Login successful', 'เข้าสู่ระบบจากอุปกรณ์ใหม่'],
            'logout' => ['ออกจากระบบ', 'Logout successful', 'Session timeout'],
            'create' => ['เพิ่มสินค้าใหม่: พาราเซตามอล 500mg', 'สร้างใบสั่งซื้อ PO-2026-001', 'เพิ่มลูกค้าใหม่: คุณสมชาย', 'เพิ่มหมวดหมู่: ยาแก้ปวด'],
            'update' => ['แก้ไขราคาสินค้า: พาราเซตามอล', 'อัปเดตข้อมูลลูกค้า', 'แก้ไขสถานะใบสั่งซื้อ', 'เปลี่ยนรหัสผ่านผู้ใช้'],
            'delete' => ['ลบสินค้า: ยาหมดอายุ', 'ลบข้อมูลลูกค้า', 'ยกเลิกใบสั่งซื้อ'],
            'view' => ['ดูรายละเอียดสินค้า', 'ดูประวัติการสั่งซื้อ', 'ดูรายงานยอดขาย'],
            'print' => ['พิมพ์ใบเสร็จ #ORD-2026-001', 'พิมพ์รายงานสต็อก', 'พิมพ์ใบสั่งซื้อ'],
            'export' => ['ส่งออกรายงาน Excel', 'Export ข้อมูลสินค้า', 'ส่งออกรายชื่อลูกค้า'],
        ];

        $ipAddresses = ['192.168.1.100', '192.168.1.101', '10.0.0.50', '127.0.0.1', '192.168.0.15'];
        $userAgents = [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15',
            'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15',
        ];

        // Create logs for the past 30 days
        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $action = $actions[array_rand($actions)];
            $module = $modules[array_rand($modules)];
            $description = $descriptions[$action][array_rand($descriptions[$action])] ?? null;

            $loggedAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $oldValues = null;
            $newValues = null;

            if ($action === 'update') {
                $oldValues = ['price' => rand(10, 100), 'stock' => rand(50, 200)];
                $newValues = ['price' => rand(10, 100), 'stock' => rand(50, 200)];
            }

            ActivityLog::create([
                'logged_at' => $loggedAt,
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ]);
        }

        $this->command->info('Created 150 activity logs!');
    }
}

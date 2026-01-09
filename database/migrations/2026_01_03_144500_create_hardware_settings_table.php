<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hardware_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // printer, cash_drawer, barcode_scanner, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $defaults = [
            // Printer Settings
            ['key' => 'printer_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'printer', 'description' => 'Enable receipt printer'],
            ['key' => 'printer_type', 'value' => 'network', 'type' => 'string', 'group' => 'printer', 'description' => 'Printer connection type (network/usb)'],
            ['key' => 'printer_ip', 'value' => '192.168.1.100', 'type' => 'string', 'group' => 'printer', 'description' => 'Printer IP address'],
            ['key' => 'printer_port', 'value' => '9100', 'type' => 'integer', 'group' => 'printer', 'description' => 'Printer port'],
            ['key' => 'printer_paper_size', 'value' => '80', 'type' => 'string', 'group' => 'printer', 'description' => 'Paper width in mm (58/80)'],
            ['key' => 'printer_auto_print', 'value' => '0', 'type' => 'boolean', 'group' => 'printer', 'description' => 'Auto print receipt after sale'],
            ['key' => 'printer_copies', 'value' => '1', 'type' => 'integer', 'group' => 'printer', 'description' => 'Number of copies to print'],
            ['key' => 'printer_cut_paper', 'value' => '1', 'type' => 'boolean', 'group' => 'printer', 'description' => 'Auto cut paper after print'],
            ['key' => 'printer_beep', 'value' => '1', 'type' => 'boolean', 'group' => 'printer', 'description' => 'Beep after print'],

            // Cash Drawer Settings
            ['key' => 'cash_drawer_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'cash_drawer', 'description' => 'Enable cash drawer'],
            ['key' => 'cash_drawer_auto_open', 'value' => '1', 'type' => 'boolean', 'group' => 'cash_drawer', 'description' => 'Auto open after cash sale'],
            ['key' => 'cash_drawer_command', 'value' => '\x1b\x70\x00\x19\xfa', 'type' => 'string', 'group' => 'cash_drawer', 'description' => 'ESC/POS command to open drawer'],
            ['key' => 'cash_drawer_connected_to_printer', 'value' => '1', 'type' => 'boolean', 'group' => 'cash_drawer', 'description' => 'Cash drawer connected via printer'],

            // Barcode Scanner Settings
            ['key' => 'barcode_scanner_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'barcode_scanner', 'description' => 'Enable barcode scanner'],
            ['key' => 'barcode_scanner_suffix', 'value' => '\r\n', 'type' => 'string', 'group' => 'barcode_scanner', 'description' => 'Barcode scanner suffix character'],
        ];

        foreach ($defaults as $setting) {
            DB::table('hardware_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardware_settings');
    }
};

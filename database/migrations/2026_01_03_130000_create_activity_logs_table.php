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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Context - เวลาและที่มา
            $table->timestamp('logged_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            // Actor - ผู้กระทำ
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable(); // Snapshot ณ เวลานั้น

            // Action - กิจกรรม
            $table->enum('action', ['login', 'logout', 'create', 'update', 'delete', 'print', 'export', 'view', 'other'])->default('other');
            $table->string('module', 100); // Inventory, POS, Settings, Users, etc.
            $table->string('model_type')->nullable(); // App\Models\Product
            $table->unsignedBigInteger('model_id')->nullable(); // ID ของ record ที่เกี่ยวข้อง

            // Description
            $table->string('description')->nullable(); // คำอธิบายสั้นๆ

            // Changes - การเปลี่ยนแปลง
            $table->json('old_values')->nullable(); // ข้อมูลเดิม
            $table->json('new_values')->nullable(); // ข้อมูลใหม่

            // Additional metadata
            $table->json('metadata')->nullable(); // ข้อมูลเพิ่มเติม

            $table->timestamps();

            // Indexes for faster queries
            $table->index('logged_at');
            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

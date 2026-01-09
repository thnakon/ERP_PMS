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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('line_id');
            $table->string('registrant_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('business_name');
            $table->enum('business_type', ['pharmacy', 'other']);
            $table->string('tax_id', 13);
            $table->text('address');
            $table->string('device_count');
            $table->date('install_date');
            $table->string('install_time');
            $table->enum('previous_software', ['none', 'other']);
            $table->string('previous_software_name')->nullable();
            $table->enum('data_migration', ['none', 'new', 'transfer']);
            $table->string('referral_source');
            $table->text('notes')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ['pending', 'verified', 'installed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

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
        // Chat Rooms Table
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('type', ['direct', 'group', 'customer'])->default('direct');
            $table->string('avatar')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        // Chat Room Participants (Many-to-Many)
        Schema::create('chat_room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->unique(['chat_room_id', 'user_id']);
        });

        // Messages Table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('sender_type', ['user', 'customer', 'system', 'bot'])->default('user');
            $table->text('content')->nullable();
            $table->enum('type', ['text', 'image', 'file', 'audio', 'video', 'receipt', 'notification'])->default('text');
            $table->string('attachment_url')->nullable();
            $table->string('attachment_name')->nullable();
            $table->integer('attachment_size')->nullable();
            $table->foreignId('reply_to_id')->nullable()->constrained('messages')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['chat_room_id', 'created_at']);
        });

        // Message Read Status (for tracking who has read)
        Schema::create('message_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at')->useCurrent();

            $table->unique(['message_id', 'user_id']);
        });

        // Delivery Logs (Email & LINE)
        Schema::create('delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('channel', ['email', 'line', 'sms', 'push'])->default('email');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient'); // email address or LINE ID
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->enum('type', ['receipt', 'promotion', 'notification', 'reminder'])->default('notification');
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'opened', 'clicked'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['channel', 'status']);
            $table->index(['customer_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_logs');
        Schema::dropIfExists('message_reads');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chat_room_participants');
        Schema::dropIfExists('chat_rooms');
    }
};

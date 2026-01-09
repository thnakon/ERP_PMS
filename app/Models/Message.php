<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'customer_id',
        'sender_type',
        'content',
        'type',
        'attachment_url',
        'attachment_name',
        'attachment_size',
        'reply_to_id',
        'metadata',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    /**
     * Get the chat room
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Get the sender (user)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the customer sender
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the message being replied to
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    /**
     * Get replies to this message
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    /**
     * Get read receipts
     */
    public function reads(): HasMany
    {
        return $this->hasMany(MessageRead::class);
    }

    /**
     * Check if message is read by a user
     */
    public function isReadBy(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    /**
     * Mark as read by a user
     */
    public function markAsReadBy(int $userId): void
    {
        $this->reads()->firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Get sender display name
     */
    public function getSenderNameAttribute(): string
    {
        return match ($this->sender_type) {
            'user' => $this->sender?->name ?? 'Unknown User',
            'customer' => $this->customer?->name ?? 'Customer',
            'system' => 'System',
            'bot' => 'AI Assistant',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted attachment size
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->attachment_size) {
            return '';
        }

        $bytes = $this->attachment_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return round($bytes, 2) . ' ' . $units[$index];
    }
}

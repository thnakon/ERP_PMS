<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatRoomParticipant extends Model
{
    protected $fillable = [
        'chat_room_id',
        'user_id',
        'customer_id',
        'role',
        'joined_at',
        'last_read_at',
        'is_muted',
        'is_pinned',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Get the chat room
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    protected $fillable = [
        'name',
        'type',
        'avatar',
        'created_by',
        'settings',
        'is_active',
        'last_message_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the creator of the room
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the messages in this room
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the participants (users)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_room_participants')
            ->withPivot(['role', 'joined_at', 'last_read_at', 'is_muted', 'is_pinned'])
            ->withTimestamps();
    }

    /**
     * Get participant records
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ChatRoomParticipant::class);
    }

    /**
     * Get the latest message
     */
    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->limit(1);
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCountFor(int $userId): int
    {
        $participant = $this->participants()->where('user_id', $userId)->first();

        if (!$participant || !$participant->last_read_at) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $participant->last_read_at)
            ->where('sender_id', '!=', $userId)
            ->count();
    }

    /**
     * Mark as read for a user
     */
    public function markAsReadFor(int $userId): void
    {
        $this->participants()
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);
    }

    /**
     * Get display avatar URL
     */
    public function getDisplayAvatarAttribute(): ?string
    {
        if ($this->type === 'direct') {
            $otherUser = $this->users()
                ->where('user_id', '!=', auth()->id())
                ->first();

            return $otherUser?->avatar ? asset('storage/' . $otherUser->avatar) : null;
        }

        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    /**
     * Get display name based on room type
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->name) {
            return $this->name;
        }

        if ($this->type === 'direct') {
            // For direct chat, show the other participant's name
            $otherUser = $this->users()
                ->where('user_id', '!=', auth()->id())
                ->first();

            return $otherUser?->name ?? 'Unknown';
        }

        return 'Chat Room #' . $this->id;
    }

    /**
     * Create or get direct chat room between two users
     */
    public static function getOrCreateDirect(int $userId1, int $userId2): self
    {
        // Find existing direct room
        $room = self::where('type', 'direct')
            ->whereHas('participants', fn($q) => $q->where('user_id', $userId1))
            ->whereHas('participants', fn($q) => $q->where('user_id', $userId2))
            ->first();

        if ($room) {
            return $room;
        }

        // Create new room
        $room = self::create([
            'type' => 'direct',
            'created_by' => $userId1,
        ]);

        $room->participants()->createMany([
            ['user_id' => $userId1, 'role' => 'member'],
            ['user_id' => $userId2, 'role' => 'member'],
        ]);

        return $room;
    }
}

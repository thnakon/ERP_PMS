<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryLog extends Model
{
    protected $fillable = [
        'channel',
        'order_id',
        'customer_id',
        'recipient',
        'subject',
        'content',
        'type',
        'status',
        'error_message',
        'sent_at',
        'delivered_at',
        'opened_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
    ];

    /**
     * Get the order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Mark as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    /**
     * Mark as opened
     */
    public function markAsOpened(): void
    {
        $this->update([
            'status' => 'opened',
            'opened_at' => now(),
        ]);
    }

    /**
     * Get channel icon
     */
    public function getChannelIconAttribute(): string
    {
        return match ($this->channel) {
            'email' => 'ph-envelope',
            'line' => 'ph-chat-circle-dots',
            'sms' => 'ph-device-mobile-message',
            'push' => 'ph-bell',
            default => 'ph-paper-plane-tilt',
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'sent' => 'blue',
            'delivered' => 'green',
            'opened' => 'purple',
            'clicked' => 'indigo',
            'failed' => 'red',
            default => 'gray',
        };
    }
}

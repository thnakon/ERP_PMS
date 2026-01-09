<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyAlert extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'order_id',
        'user_id',
        'allergy_type',
        'alert_level',
        'message',
        'acknowledged',
        'acknowledged_at',
        'pharmacist_notes',
        'action_taken',
    ];

    protected $casts = [
        'acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    /**
     * Get the customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who acknowledged.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Acknowledge the alert.
     */
    public function acknowledge(string $action, ?string $notes = null): bool
    {
        $this->acknowledged = true;
        $this->acknowledged_at = now();
        $this->action_taken = $action;
        $this->pharmacist_notes = $notes;

        return $this->save();
    }

    /**
     * Check if alert is critical.
     */
    public function isCritical(): bool
    {
        return $this->alert_level === 'critical';
    }

    /**
     * Get alert level color.
     */
    public function getLevelColorAttribute(): string
    {
        return match ($this->alert_level) {
            'critical' => '#FF3B30',
            'danger' => '#FF9500',
            'warning' => '#FFCC00',
            default => '#8E8E93',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'reason',
        'type',
        'status',
        'stock_adjusted',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'stock_adjusted' => 'boolean',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the order this refund belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who processed the refund.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark as processed.
     */
    public function markAsProcessed(): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);
    }
}

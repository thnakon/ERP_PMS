<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosShift extends Model
{
    protected $fillable = [
        'user_id',
        'opening_balance',
        'expected_cash',
        'closing_balance',
        'variance',
        'total_sales',
        'total_cash',
        'total_card',
        'total_transfer',
        'total_qr',
        'transactions_count',
        'opened_at',
        'closed_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'variance' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_cash' => 'decimal:2',
        'total_card' => 'decimal:2',
        'total_transfer' => 'decimal:2',
        'total_qr' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this shift.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all orders for this shift.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if shift is currently open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Get current open shift for a user.
     */
    public static function getCurrentShift($userId = null)
    {
        $query = static::where('status', 'open');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->latest('opened_at')->first();
    }

    /**
     * Close the shift.
     */
    public function closeShift(float $closingBalance, ?string $notes = null): bool
    {
        $this->closing_balance = $closingBalance;
        $this->variance = $closingBalance - $this->expected_cash;
        $this->closed_at = now();
        $this->status = 'closed';
        $this->notes = $notes;

        return $this->save();
    }

    /**
     * Update expected cash after a transaction.
     */
    public function addCashTransaction(float $amount): void
    {
        $this->increment('expected_cash', $amount);
        $this->increment('total_cash', $amount);
        $this->increment('total_sales', $amount);
        $this->increment('transactions_count');
    }

    /**
     * Update totals after a card transaction.
     */
    public function addCardTransaction(float $amount): void
    {
        $this->increment('total_card', $amount);
        $this->increment('total_sales', $amount);
        $this->increment('transactions_count');
    }
}

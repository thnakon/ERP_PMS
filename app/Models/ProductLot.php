<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'lot_number',
        'expiry_date',
        'manufactured_date',
        'quantity',
        'initial_quantity',
        'cost_price',
        'supplier', // Legacy text field
        'gr_reference',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'manufactured_date' => 'date',
        'received_at' => 'date',
        'quantity' => 'integer',
        'initial_quantity' => 'integer',
        'cost_price' => 'decimal:2',
    ];

    /**
     * Get the product this lot belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the supplier this lot belongs to.
     */
    public function supplierRel(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Scope: Order by FEFO (First Expired, First Out)
     */
    public function scopeFefo($query)
    {
        return $query->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc');
    }

    /**
     * Check if this lot is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        return (int) now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Get expiry status.
     */
    public function getExpiryStatusAttribute(): string
    {
        $days = $this->days_until_expiry;

        if ($days <= 0) return 'expired';
        if ($days <= 7) return 'critical';
        if ($days <= 30) return 'warning';
        if ($days <= 90) return 'notice';
        return 'good';
    }

    /**
     * Scope: Get lots expiring within X days.
     */
    public function scopeExpiringWithin($query, int $days)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now())
            ->where('quantity', '>', 0);
    }

    /**
     * Scope: Get expired lots.
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<=', now())
            ->where('quantity', '>', 0);
    }
}

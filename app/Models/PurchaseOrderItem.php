<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'ordered_qty',
        'received_qty',
        'unit_cost',
        'discount_percent',
        'discount_amount',
        'line_total',
        'notes',
    ];

    protected $casts = [
        'ordered_qty' => 'decimal:2',
        'received_qty' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Get the purchase order.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate line total.
     */
    public function calculateLineTotal(): void
    {
        $subtotal = $this->ordered_qty * $this->unit_cost;

        if ($this->discount_percent > 0) {
            $this->discount_amount = $subtotal * ($this->discount_percent / 100);
        }

        $this->line_total = $subtotal - $this->discount_amount;
    }

    /**
     * Get remaining quantity to receive.
     */
    public function getRemainingQtyAttribute(): float
    {
        return max(0, $this->ordered_qty - $this->received_qty);
    }

    /**
     * Check if fully received.
     */
    public function getIsFullyReceivedAttribute(): bool
    {
        return $this->received_qty >= $this->ordered_qty;
    }
}

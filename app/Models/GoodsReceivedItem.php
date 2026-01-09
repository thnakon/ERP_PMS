<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceivedItem extends Model
{
    protected $fillable = [
        'goods_received_id',
        'product_id',
        'purchase_order_item_id',
        'ordered_qty',
        'received_qty',
        'rejected_qty',
        'unit_cost',
        'line_total',
        'lot_number',
        'expiry_date',
        'manufactured_date',
        'notes',
    ];

    protected $casts = [
        'ordered_qty' => 'decimal:2',
        'received_qty' => 'decimal:2',
        'rejected_qty' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'line_total' => 'decimal:2',
        'expiry_date' => 'date',
        'manufactured_date' => 'date',
    ];

    /**
     * Get the goods received.
     */
    public function goodsReceived(): BelongsTo
    {
        return $this->belongsTo(GoodsReceived::class);
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the PO item.
     */
    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    /**
     * Calculate line total.
     */
    public function calculateLineTotal(): void
    {
        $this->line_total = $this->received_qty * $this->unit_cost;
    }

    /**
     * Get actual received (minus rejected).
     */
    public function getActualReceivedAttribute(): float
    {
        return max(0, $this->received_qty - $this->rejected_qty);
    }
}

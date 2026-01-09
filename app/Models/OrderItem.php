<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_lot_id',
        'product_name',
        'unit_price',
        'quantity',
        'discount',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Calculate subtotal if not set
            if (empty($item->subtotal)) {
                $item->subtotal = ($item->unit_price * $item->quantity) - $item->discount;
            }

            // Snapshot product name
            if (empty($item->product_name) && $item->product) {
                $item->product_name = $item->product->name;
            }
        });

        static::created(function ($item) {
            // Deduct stock when item is created
            if ($item->product) {
                $item->product->deductStock($item->quantity, $item->product_lot_id);
            }
        });
    }

    /**
     * Get the order this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product lot used.
     */
    public function productLot(): BelongsTo
    {
        return $this->belongsTo(ProductLot::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'adjustment_number',
        'product_id',
        'product_lot_id',
        'user_id',
        'type',
        'quantity',
        'before_quantity',
        'after_quantity',
        'reason',
        'notes',
        'adjusted_at',
    ];

    protected $casts = [
        'adjusted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function lot()
    {
        return $this->belongsTo(ProductLot::class, 'product_lot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

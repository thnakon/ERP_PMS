<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'tax_id',
        'contact_person',
        'phone',
        'mobile',
        'email',
        'line_id',
        'address',
        'shipping_address',
        'credit_term',
        'lead_time',
        'min_order_qty',
        'bank_name',
        'bank_account_no',
        'bank_account_name',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'credit_term' => 'integer',
        'lead_time' => 'integer',
        'min_order_qty' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get product lots from this supplier.
     */
    public function lots(): HasMany
    {
        return $this->hasMany(ProductLot::class);
    }

    /**
     * Get purchase orders for this supplier.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get goods received for this supplier.
     */
    public function goodsReceived(): HasMany
    {
        return $this->hasMany(GoodsReceived::class);
    }

    /**
     * Scope: Active suppliers only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

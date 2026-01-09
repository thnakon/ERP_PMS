<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceived extends Model
{
    use SoftDeletes;

    protected $table = 'goods_received';

    protected $fillable = [
        'gr_number',
        'purchase_order_id',
        'supplier_id',
        'user_id',
        'invoice_no',
        'received_date',
        'status',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Status labels
     */
    public static $statuses = [
        'pending' => 'Pending',
        'partial' => 'Partial',
        'completed' => 'Completed',
    ];

    /**
     * Get the purchase order.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who received.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceivedItem::class);
    }

    /**
     * Generate next GR number.
     */
    public static function generateGrNumber(): string
    {
        $prefix = 'GR-' . date('Ymd') . '-';
        $last = self::where('gr_number', 'like', $prefix . '%')
            ->orderBy('gr_number', 'desc')
            ->first();

        if ($last) {
            $num = (int) substr($last->gr_number, -4);
            $nextNum = str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }

    /**
     * Calculate total amount.
     */
    public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum('line_total');
        $this->save();
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'partial' => 'badge-info',
            'completed' => 'badge-success',
            default => 'badge-gray',
        };
    }
}

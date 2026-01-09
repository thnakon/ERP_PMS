<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'order_date',
        'expected_date',
        'status',
        'subtotal',
        'vat_amount',
        'discount_amount',
        'grand_total',
        'notes',
        'sent_at',
        'completed_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Status labels
     */
    public static $statuses = [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'partial' => 'Partially Received',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Get the supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created this PO.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in this PO.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get goods received for this PO.
     */
    public function goodsReceived(): HasMany
    {
        return $this->hasMany(GoodsReceived::class);
    }

    /**
     * Generate next PO number.
     */
    public static function generatePoNumber(): string
    {
        $prefix = 'PO-' . date('Ymd') . '-';
        $last = self::where('po_number', 'like', $prefix . '%')
            ->orderBy('po_number', 'desc')
            ->first();

        if ($last) {
            $num = (int) substr($last->po_number, -4);
            $nextNum = str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }

    /**
     * Calculate totals.
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('line_total');
        $vatAmount = $subtotal * 0.07; // 7% VAT

        $this->subtotal = $subtotal;
        $this->vat_amount = $vatAmount;
        $this->grand_total = $subtotal + $vatAmount - $this->discount_amount;
        $this->save();
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'badge-gray',
            'sent' => 'badge-info',
            'partial' => 'badge-warning',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-gray',
        };
    }
}

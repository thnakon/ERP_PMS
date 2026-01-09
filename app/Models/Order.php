<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'pos_shift_id',
        'subtotal',
        'discount',
        'discount_amount',
        'discount_percent',
        'tax',
        'vat_amount',
        'total_amount',
        'payment_method',
        'payment_details',
        'payment_status',
        'amount_paid',
        'change_amount',
        'status',
        'paid_at',
        'notes',
        'prescription_notes',
        'requires_prescription',
        'pharmacist_id',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'payment_details' => 'array',
        'requires_prescription' => 'boolean',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Get the customer for this order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the cashier (user) for this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all refunds for this order.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Get the POS shift for this order.
     */
    public function posShift(): BelongsTo
    {
        return $this->belongsTo(PosShift::class);
    }

    /**
     * Get the pharmacist who verified.
     */
    public function pharmacist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    /**
     * Get allergy alerts for this order.
     */
    public function allergyAlerts(): HasMany
    {
        return $this->hasMany(AllergyAlert::class);
    }

    /**
     * Calculate totals from items.
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('subtotal');
        $this->subtotal = $subtotal;
        $this->total_amount = $subtotal - $this->discount + $this->tax;
        $this->save();
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted(float $amountPaid): void
    {
        $this->update([
            'status' => 'completed',
            'amount_paid' => $amountPaid,
            'change_amount' => max(0, $amountPaid - $this->total_amount),
            'paid_at' => now(),
        ]);

        // Record customer visit if applicable
        if ($this->customer) {
            $this->customer->recordVisit($this->total_amount);
        }
    }

    /**
     * Process full refund.
     */
    public function processRefund(string $reason, ?int $userId = null): Refund
    {
        $refund = $this->refunds()->create([
            'user_id' => $userId,
            'amount' => $this->total_amount,
            'reason' => $reason,
            'type' => 'full',
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        // Restore stock
        foreach ($this->items as $item) {
            if ($item->product) {
                $item->product->addStock($item->quantity, $item->product_lot_id);
            }
        }

        $refund->update(['stock_adjusted' => true]);
        $this->update(['status' => 'refunded']);

        return $refund;
    }

    /**
     * Check if order can be refunded.
     */
    public function canBeRefunded(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get refunded amount.
     */
    public function getRefundedAmountAttribute(): float
    {
        return (float) $this->refunds()->where('status', 'processed')->sum('amount');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlledDrugLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'log_number',
        'product_id',
        'quantity',
        'product_lot_id',
        'transaction_type',
        'customer_id',
        'customer_name',
        'customer_id_card',
        'customer_phone',
        'customer_address',
        'customer_age',
        'prescription_id',
        'prescription_number',
        'doctor_name',
        'doctor_license_no',
        'hospital_clinic',
        'purpose',
        'indication',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'created_by',
        'order_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (empty($log->log_number)) {
                $log->log_number = self::generateLogNumber();
            }
        });
    }

    /**
     * Generate unique log number
     */
    public static function generateLogNumber(): string
    {
        $prefix = 'CDL'; // Controlled Drug Log
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Relationships
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productLot()
    {
        return $this->belongsTo(ProductLot::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge badge-warning"><span class="badge-dot badge-dot-warning"></span>' . __('controlled_drugs.status_pending') . '</span>',
            'approved' => '<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span>' . __('controlled_drugs.status_approved') . '</span>',
            'rejected' => '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span>' . __('controlled_drugs.status_rejected') . '</span>',
            'cancelled' => '<span class="badge badge-gray"><span class="badge-dot badge-dot-gray"></span>' . __('controlled_drugs.status_cancelled') . '</span>',
            default => '<span class="badge badge-gray">' . $this->status . '</span>',
        };
    }

    /**
     * Get transaction type label (localized)
     */
    public function getTransactionTypeLabelAttribute(): string
    {
        return match ($this->transaction_type) {
            'sale' => __('controlled_drugs.trans_sale'),
            'dispense' => __('controlled_drugs.trans_dispense'),
            'receive' => __('controlled_drugs.trans_receive'),
            'return' => __('controlled_drugs.trans_return'),
            'dispose' => __('controlled_drugs.trans_dispose'),
            'transfer' => __('controlled_drugs.trans_transfer'),
            default => $this->transaction_type,
        };
    }

    /**
     * Get drug schedule label for the product
     */
    public function getDrugScheduleLabelAttribute(): string
    {
        return $this->product?->drug_schedule_label ?? '-';
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeForDrugSchedule($query, $schedule)
    {
        return $query->whereHas('product', function ($q) use ($schedule) {
            $q->where('drug_schedule', $schedule);
        });
    }

    /**
     * Approve the log
     */
    public function approve($userId, $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'notes' => $notes ?? $this->notes,
        ]);
    }

    /**
     * Reject the log
     */
    public function reject($userId, $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $userId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Get transaction types
     */
    public static function getTransactionTypes(): array
    {
        return [
            'sale' => 'ขาย',
            'dispense' => 'จ่ายตามใบสั่งยา',
            'receive' => 'รับเข้า',
            'return' => 'รับคืน',
            'dispose' => 'ทำลาย',
            'transfer' => 'โอนย้าย',
        ];
    }
}

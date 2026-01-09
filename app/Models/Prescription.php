<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prescription_number',
        'customer_id',
        'user_id',
        'doctor_name',
        'doctor_license_no',
        'hospital_clinic',
        'doctor_phone',
        'prescription_date',
        'expiry_date',
        'diagnosis',
        'notes',
        'status',
        'dispensed_at',
        'refill_allowed',
        'refill_count',
        'next_refill_date',
        'refill_reminder_sent',
        'order_id',
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'expiry_date' => 'date',
        'dispensed_at' => 'datetime',
        'next_refill_date' => 'date',
        'refill_reminder_sent' => 'boolean',
        'refill_allowed' => 'integer',
        'refill_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prescription) {
            if (empty($prescription->prescription_number)) {
                $prescription->prescription_number = self::generatePrescriptionNumber();
            }
        });
    }

    /**
     * Generate unique prescription number
     */
    public static function generatePrescriptionNumber(): string
    {
        $prefix = 'RX';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));

        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Relationships
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge badge-warning"><span class="badge-dot badge-dot-warning"></span>' . __('prescriptions.status_pending') . '</span>',
            'dispensed' => '<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span>' . __('prescriptions.status_dispensed') . '</span>',
            'partially_dispensed' => '<span class="badge badge-info"><span class="badge-dot badge-dot-info"></span>' . __('prescriptions.status_partially_dispensed') . '</span>',
            'cancelled' => '<span class="badge badge-gray"><span class="badge-dot badge-dot-gray"></span>' . __('prescriptions.status_cancelled') . '</span>',
            'expired' => '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span>' . __('prescriptions.status_expired') . '</span>',
            default => '<span class="badge badge-gray">' . $this->status . '</span>',
        };
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    public function getCanRefillAttribute(): bool
    {
        return $this->refill_count < $this->refill_allowed
            && !$this->is_expired
            && $this->status === 'dispensed';
    }

    public function getRemainingRefillsAttribute(): int
    {
        return max(0, $this->refill_allowed - $this->refill_count);
    }

    public function getTotalAmountAttribute(): float
    {
        return $this->items->sum('subtotal');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDispensed($query)
    {
        return $query->where('status', 'dispensed');
    }

    public function scopeNeedsRefillReminder($query)
    {
        return $query->where('status', 'dispensed')
            ->where('refill_count', '<', DB::raw('refill_allowed'))
            ->where('next_refill_date', '<=', now()->addDays(3))
            ->where('refill_reminder_sent', false);
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Check for drug interactions with current prescription items
     */
    public function checkDrugInteractions(): array
    {
        $productIds = $this->items->pluck('product_id')->toArray();
        $interactions = [];

        if (count($productIds) < 2) {
            return $interactions;
        }

        // Check interactions between products in this prescription
        $drugInteractions = DrugInteraction::where(function ($query) use ($productIds) {
            $query->whereIn('drug_a_id', $productIds)
                ->whereIn('drug_b_id', $productIds);
        })->orWhere(function ($query) use ($productIds) {
            $query->whereIn('drug_b_id', $productIds)
                ->whereIn('drug_a_id', $productIds);
        })->where('is_active', true)->get();

        foreach ($drugInteractions as $interaction) {
            $interactions[] = [
                'severity' => $interaction->severity,
                'drug_a' => $interaction->drugA?->name ?? $interaction->drug_a_name,
                'drug_b' => $interaction->drugB?->name ?? $interaction->drug_b_name,
                'description' => $interaction->description,
                'management' => $interaction->management,
            ];
        }

        return $interactions;
    }

    /**
     * Mark prescription as dispensed
     */
    public function markAsDispensed(): void
    {
        $this->update([
            'status' => 'dispensed',
            'dispensed_at' => now(),
        ]);

        // Update all items as dispensed
        $this->items()->update([
            'is_dispensed' => true,
            'quantity_dispensed' => DB::raw('quantity'),
        ]);
    }

    /**
     * Calculate next refill date based on duration
     */
    public function calculateNextRefillDate(): ?Carbon
    {
        if ($this->refill_allowed <= $this->refill_count) {
            return null;
        }

        // Get the longest duration from items
        $maxDays = 0;
        foreach ($this->items as $item) {
            $days = $this->parseDurationToDays($item->duration);
            if ($days > $maxDays) {
                $maxDays = $days;
            }
        }

        if ($maxDays > 0) {
            return now()->addDays($maxDays);
        }

        return now()->addDays(30); // Default 30 days
    }

    private function parseDurationToDays(?string $duration): int
    {
        if (!$duration) {
            return 0;
        }

        $duration = strtolower($duration);

        if (preg_match('/(\d+)\s*(day|วัน)/i', $duration, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/(\d+)\s*(week|สัปดาห์)/i', $duration, $matches)) {
            return (int) $matches[1] * 7;
        }
        if (preg_match('/(\d+)\s*(month|เดือน)/i', $duration, $matches)) {
            return (int) $matches[1] * 30;
        }

        return 0;
    }
}

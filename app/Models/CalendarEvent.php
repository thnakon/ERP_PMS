<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalendarEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'description',
        'start_time',
        'end_time',
        'all_day',
        'color',
        'staff_id',
        'customer_id',
        'product_id',
        'product_lot_id',
        'created_by',
        'status',
        'notes',
        'is_recurring',
        'recurrence_type',
        'recurrence_end',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
        'is_recurring' => 'boolean',
        'recurrence_end' => 'date',
    ];

    /**
     * Event type colors
     */
    public static $typeColors = [
        'shift' => '#3B82F6',       // Blue
        'expiry' => '#EF4444',       // Red
        'appointment' => '#22C55E',  // Green
        'holiday' => '#F59E0B',      // Amber
        'reminder' => '#8B5CF6',     // Purple
        'other' => '#6B7280',        // Gray
    ];

    /**
     * Get the staff member for this event.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the customer for this event.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product for this event.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product lot for this event.
     */
    public function productLot(): BelongsTo
    {
        return $this->belongsTo(ProductLot::class);
    }

    /**
     * Get the user who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: Get events for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    /**
     * Scope: Get events for a specific date range.
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_time', [$start, $end])
                ->orWhereBetween('end_time', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                });
        });
    }

    /**
     * Scope: Get events by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get shift events for today.
     */
    public function scopeCurrentShift($query)
    {
        return $query->where('type', 'shift')
            ->where('start_time', '<=', now())
            ->where(function ($q) {
                $q->where('end_time', '>=', now())
                    ->orWhereNull('end_time');
            })
            ->where('status', '!=', 'cancelled');
    }

    /**
     * Get the color for this event type.
     */
    public function getTypeColorAttribute(): string
    {
        return self::$typeColors[$this->type] ?? self::$typeColors['other'];
    }

    /**
     * Get display color (use custom color if set, otherwise type color).
     */
    public function getDisplayColorAttribute(): string
    {
        return $this->color !== '#3B82F6' ? $this->color : $this->type_color;
    }
}

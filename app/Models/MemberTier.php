<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_th',
        'min_spending',
        'discount_percent',
        'points_multiplier',
        'color',
        'icon',
        'benefits',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'min_spending' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get customers in this tier
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get promotions specific to this tier
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    /**
     * Get the display name (localized)
     */
    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'th' && $this->name_th ? $this->name_th : $this->name;
    }

    /**
     * Scope for active tiers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get tier for a spending amount
     */
    public static function getTierForSpending(float $totalSpent): ?self
    {
        return static::active()
            ->where('min_spending', '<=', $totalSpent)
            ->orderByDesc('min_spending')
            ->first();
    }
}

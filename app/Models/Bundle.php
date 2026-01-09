<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Bundle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_th',
        'description',
        'bundle_price',
        'original_price',
        'savings',
        'start_date',
        'end_date',
        'stock_limit',
        'sold_count',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'bundle_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'savings' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get bundle products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bundle_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get display name (localized)
     */
    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'th' && $this->name_th ? $this->name_th : $this->name;
    }

    /**
     * Calculate original price from products
     */
    public function calculateOriginalPrice(): float
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->unit_price * $product->pivot->quantity;
        }
        return round($total, 2);
    }

    /**
     * Calculate savings
     */
    public function calculateSavings(): float
    {
        $original = $this->original_price ?? $this->calculateOriginalPrice();
        return max(0, $original - $this->bundle_price);
    }

    /**
     * Get savings percentage
     */
    public function getSavingsPercentAttribute(): float
    {
        $original = $this->original_price ?? $this->calculateOriginalPrice();
        if ($original <= 0) return 0;
        return round(($this->calculateSavings() / $original) * 100, 1);
    }

    /**
     * Check if bundle is currently available
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->stock_limit && $this->sold_count >= $this->stock_limit) {
            return false;
        }

        // Check product stock
        foreach ($this->products as $product) {
            if ($product->stock_qty < $product->pivot->quantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get remaining stock
     */
    public function getRemainingStockAttribute(): ?int
    {
        if (!$this->stock_limit) {
            return null;
        }
        return max(0, $this->stock_limit - $this->sold_count);
    }

    /**
     * Increment sold count
     */
    public function incrementSold(int $quantity = 1): void
    {
        $this->increment('sold_count', $quantity);
    }

    /**
     * Scope for active bundles
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();

        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });
    }

    /**
     * Scope for available bundles (active and in stock)
     */
    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('stock_limit')
                    ->orWhereRaw('sold_count < stock_limit');
            });
    }
}

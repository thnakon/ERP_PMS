<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_th',
        'code',
        'description',
        'description_th',
        'type',
        'discount_value',
        'min_purchase',
        'max_discount',
        'buy_quantity',
        'get_quantity',
        'start_date',
        'end_date',
        'active_days',
        'start_time',
        'end_time',
        'usage_limit',
        'usage_count',
        'per_customer_limit',
        'member_tier_id',
        'new_customers_only',
        'stackable',
        'is_active',
        'is_featured',
        'image_path',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'active_days' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'new_customers_only' => 'boolean',
        'stackable' => 'boolean',
    ];

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED_AMOUNT = 'fixed_amount';
    const TYPE_BUY_X_GET_Y = 'buy_x_get_y';
    const TYPE_BUNDLE = 'bundle';
    const TYPE_FREE_ITEM = 'free_item';
    const TYPE_TIER_DISCOUNT = 'tier_discount';

    /**
     * Get the member tier this promotion is for
     */
    public function memberTier(): BelongsTo
    {
        return $this->belongsTo(MemberTier::class);
    }

    /**
     * Get included products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products')
            ->withPivot('type', 'quantity')
            ->withTimestamps();
    }

    /**
     * Get included categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'promotion_categories')
            ->withPivot('type')
            ->withTimestamps();
    }

    /**
     * Get usage records
     */
    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
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
     * Get display description (localized)
     */
    public function getDisplayDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'th' && $this->description_th ? $this->description_th : $this->description;
    }

    /**
     * Check if promotion is currently active
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        // Check date range
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        // Check day of week
        if ($this->active_days && !in_array($now->dayOfWeek, $this->active_days)) {
            return false;
        }

        // Check time of day
        if ($this->start_time && $this->end_time) {
            $currentTime = $now->format('H:i:s');
            if ($currentTime < $this->start_time || $currentTime > $this->end_time) {
                return false;
            }
        }

        // Check usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if customer can use this promotion
     */
    public function canCustomerUse(?Customer $customer): bool
    {
        if (!$this->isCurrentlyActive()) {
            return false;
        }

        // Check tier restriction
        if ($this->member_tier_id) {
            if (!$customer || $customer->member_tier_id !== $this->member_tier_id) {
                return false;
            }
        }

        // Check new customer restriction
        if ($this->new_customers_only && $customer) {
            $orderCount = $customer->orders()->where('status', 'completed')->count();
            if ($orderCount > 0) {
                return false;
            }
        }

        // Check per-customer limit
        if ($this->per_customer_limit && $customer) {
            $usedCount = $this->usages()->where('customer_id', $customer->id)->count();
            if ($usedCount >= $this->per_customer_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount for a cart
     */
    public function calculateDiscount(array $cartItems, float $subtotal, ?Customer $customer = null): array
    {
        if (!$this->canCustomerUse($customer)) {
            return ['amount' => 0, 'applied_items' => []];
        }

        if ($subtotal < $this->min_purchase) {
            return ['amount' => 0, 'applied_items' => []];
        }

        $discountAmount = 0;
        $appliedItems = [];

        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                $discountAmount = $subtotal * ($this->discount_value / 100);
                break;

            case self::TYPE_FIXED_AMOUNT:
                $discountAmount = $this->discount_value;
                break;

            case self::TYPE_BUY_X_GET_Y:
                $result = $this->calculateBuyXGetY($cartItems);
                $discountAmount = $result['amount'];
                $appliedItems = $result['items'];
                break;

            case self::TYPE_TIER_DISCOUNT:
                if ($customer && $customer->memberTier) {
                    $discountAmount = $subtotal * ($customer->memberTier->discount_percent / 100);
                }
                break;
        }

        // Apply max discount cap
        if ($this->max_discount && $discountAmount > $this->max_discount) {
            $discountAmount = $this->max_discount;
        }

        return [
            'amount' => round($discountAmount, 2),
            'applied_items' => $appliedItems,
            'promotion' => $this,
        ];
    }

    /**
     * Calculate buy X get Y discount
     */
    protected function calculateBuyXGetY(array $cartItems): array
    {
        $discountAmount = 0;
        $appliedItems = [];

        // Get applicable products
        $productIds = $this->products()->wherePivot('type', 'included')->pluck('products.id')->toArray();

        foreach ($cartItems as $item) {
            if (empty($productIds) || in_array($item['id'], $productIds)) {
                $qty = $item['quantity'];
                $totalItems = $this->buy_quantity + $this->get_quantity;
                $sets = floor($qty / $totalItems);

                if ($sets > 0) {
                    $freeItems = $sets * $this->get_quantity;
                    $freeValue = $freeItems * $item['price'];
                    $discountAmount += $freeValue;
                    $appliedItems[] = [
                        'product_id' => $item['id'],
                        'free_qty' => $freeItems,
                        'free_value' => $freeValue,
                    ];
                }
            }
        }

        return ['amount' => $discountAmount, 'items' => $appliedItems];
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Scope for currently active promotions
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
     * Scope for featured promotions
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get promotion type label
     */
    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'percentage' => __('promotions.type_percentage'),
            'fixed_amount' => __('promotions.type_fixed'),
            'buy_x_get_y' => __('promotions.type_buy_x_get_y'),
            'bundle' => __('promotions.type_bundle'),
            'free_item' => __('promotions.type_free_item'),
            'tier_discount' => __('promotions.type_tier'),
        ];

        return $labels[$this->type] ?? $this->type;
    }
}

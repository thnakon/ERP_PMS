<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Identity
        'sku',
        'barcode',
        'name',
        'name_th',
        'generic_name',
        'image_path',

        'category_id',
        'drug_class',
        'manufacturer',
        'description',
        'description_th',

        // Pricing & Units
        'unit_price',
        'member_price',
        'cost_price',
        'vat_applicable',
        'unit',
        'base_unit',
        'sell_unit',
        'conversion_factor',

        // Inventory Control
        'stock_qty',
        'min_stock',
        'reorder_point',
        'max_stock',
        'location',

        // Clinical Info
        'requires_prescription',
        'precautions',
        'precautions_th',
        'side_effects',
        'side_effects_th',
        'default_instructions',
        'default_instructions_th',

        'is_active',

        // Drug Schedule (Controlled Drugs)
        'drug_schedule',
        'requires_pharmacist_approval',
        'fda_registration_no',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'member_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_qty' => 'integer',
        'min_stock' => 'integer',
        'reorder_point' => 'integer',
        'max_stock' => 'integer',
        'conversion_factor' => 'integer',
        'vat_applicable' => 'boolean',
        'requires_prescription' => 'boolean',
        'requires_pharmacist_approval' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all lots for this product.
     */
    public function lots(): HasMany
    {
        return $this->hasMany(ProductLot::class);
    }

    /**
     * Get order items for this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get controlled drug logs for this product.
     */
    public function controlledDrugLogs(): HasMany
    {
        return $this->hasMany(ControlledDrugLog::class);
    }

    /**
     * Check if stock is low.
     */
    public function isLowStock(): bool
    {
        return $this->stock_qty <= $this->min_stock;
    }

    /**
     * Check if this is a controlled drug.
     */
    public function isControlledDrug(): bool
    {
        return in_array($this->drug_schedule, ['dangerous', 'specially_controlled', 'narcotic', 'psychotropic']);
    }

    /**
     * Check if this drug requires pharmacist approval.
     */
    public function needsPharmacistApproval(): bool
    {
        return $this->requires_pharmacist_approval || $this->isControlledDrug();
    }

    /**
     * Get drug schedule label in Thai.
     */
    public function getDrugScheduleLabelAttribute(): string
    {
        return match ($this->drug_schedule ?? 'normal') {
            'normal' => 'ยาสามัญประจำบ้าน',
            'dangerous' => 'ยาอันตราย',
            'specially_controlled' => __('controlled_drugs.schedule_specially_controlled'),
            'narcotic' => __('controlled_drugs.schedule_narcotic'),
            'psychotropic' => __('controlled_drugs.schedule_psychotropic'),
            default => __('controlled_drugs.schedule_normal'),
        };
    }

    /**
     * Get drug schedule badge HTML.
     */
    public function getDrugScheduleBadgeAttribute(): string
    {
        return match ($this->drug_schedule ?? 'normal') {
            'normal' => '<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span>' . __('controlled_drugs.schedule_normal') . '</span>',
            'dangerous' => '<span class="badge badge-warning"><span class="badge-dot badge-dot-warning"></span>' . __('controlled_drugs.schedule_dangerous') . '</span>',
            'specially_controlled' => '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span>' . __('controlled_drugs.schedule_specially_controlled') . '</span>',
            'narcotic' => '<span class="badge" style="background: #7f1d1d; color: white;">' . __('controlled_drugs.schedule_narcotic') . '</span>',
            'psychotropic' => '<span class="badge" style="background: #581c87; color: white;">' . __('controlled_drugs.schedule_psychotropic') . '</span>',
            default => '<span class="badge badge-gray">' . __('controlled_drugs.schedule_normal') . '</span>',
        };
    }

    /**
     * Get localized name based on current locale.
     */
    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'th' && $this->name_th
            ? $this->name_th
            : $this->name;
    }

    /**
     * Get active (non-expired) lots with stock.
     */
    public function activeLots()
    {
        return $this->lots()
            ->where('expiry_date', '>', now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc'); // FEFO - First Expiry First Out
    }

    /**
     * Deduct stock from oldest expiring lot (FEFO).
     */
    public function deductStock(int $quantity, ?int $lotId = null): bool
    {
        if ($lotId) {
            $lot = $this->lots()->find($lotId);
            if ($lot && $lot->quantity >= $quantity) {
                $lot->decrement('quantity', $quantity);
                $this->decrement('stock_qty', $quantity);
                return true;
            }
        }

        // FEFO - First Expiry First Out
        $remaining = $quantity;
        foreach ($this->activeLots()->get() as $lot) {
            if ($remaining <= 0) break;

            $deduct = min($remaining, $lot->quantity);
            $lot->decrement('quantity', $deduct);
            $remaining -= $deduct;
        }

        if ($remaining <= 0) {
            $this->decrement('stock_qty', $quantity);
            return true;
        }

        return false;
    }

    /**
     * Add stock to a specific lot or create new lot.
     */
    public function addStock(int $quantity, ?int $lotId = null): void
    {
        if ($lotId) {
            $lot = $this->lots()->find($lotId);
            if ($lot) {
                $lot->increment('quantity', $quantity);
            }
        }

        $this->increment('stock_qty', $quantity);
    }

    /**
     * Get drug schedule options.
     */
    public static function getDrugScheduleOptions(): array
    {
        return [
            'normal' => 'ยาสามัญประจำบ้าน',
            'dangerous' => 'ยาอันตราย',
            'specially_controlled' => 'ยาควบคุมพิเศษ',
            'narcotic' => 'ยาเสพติดให้โทษ',
            'psychotropic' => 'วัตถุออกฤทธิ์ต่อจิตประสาท',
        ];
    }

    /**
     * Scope for controlled drugs only.
     */
    public function scopeControlled($query)
    {
        return $query->whereIn('drug_schedule', ['dangerous', 'specially_controlled', 'narcotic', 'psychotropic']);
    }

    /**
     * Scope for drugs requiring pharmacist approval.
     */
    public function scopeRequiresApproval($query)
    {
        return $query->where(function ($q) {
            $q->where('requires_pharmacist_approval', true)
                ->orWhereIn('drug_schedule', ['dangerous', 'specially_controlled', 'narcotic', 'psychotropic']);
        });
    }
}

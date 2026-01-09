<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_a_id',
        'drug_a_name',
        'drug_b_id',
        'drug_b_name',
        'severity',
        'description',
        'mechanism',
        'management',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function drugA()
    {
        return $this->belongsTo(Product::class, 'drug_a_id');
    }

    public function drugB()
    {
        return $this->belongsTo(Product::class, 'drug_b_id');
    }

    /**
     * Get severity badge HTML
     */
    public function getSeverityBadgeAttribute(): string
    {
        return match ($this->severity) {
            'minor' => '<span class="badge badge-success"><span class="badge-dot badge-dot-success"></span>เล็กน้อย</span>',
            'moderate' => '<span class="badge badge-warning"><span class="badge-dot badge-dot-warning"></span>ปานกลาง</span>',
            'major' => '<span class="badge badge-danger"><span class="badge-dot badge-dot-danger"></span>รุนแรง</span>',
            'contraindicated' => '<span class="badge" style="background: #7f1d1d; color: white;">ห้ามใช้ร่วมกัน</span>',
            default => '<span class="badge badge-gray">' . $this->severity . '</span>',
        };
    }

    /**
     * Get severity color for UI
     */
    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity) {
            'minor' => '#22C55E',
            'moderate' => '#F59E0B',
            'major' => '#EF4444',
            'contraindicated' => '#7F1D1D',
            default => '#6B7280',
        };
    }

    /**
     * Get drug A display name
     */
    public function getDrugADisplayNameAttribute(): string
    {
        return $this->drugA?->name ?? $this->drug_a_name ?? 'Unknown';
    }

    /**
     * Get drug B display name
     */
    public function getDrugBDisplayNameAttribute(): string
    {
        return $this->drugB?->name ?? $this->drug_b_name ?? 'Unknown';
    }

    /**
     * Check if two products have interaction
     */
    public static function checkInteraction($productIdA, $productIdB): ?self
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($productIdA, $productIdB) {
                $query->where(function ($q) use ($productIdA, $productIdB) {
                    $q->where('drug_a_id', $productIdA)
                        ->where('drug_b_id', $productIdB);
                })->orWhere(function ($q) use ($productIdA, $productIdB) {
                    $q->where('drug_a_id', $productIdB)
                        ->where('drug_b_id', $productIdA);
                });
            })
            ->first();
    }

    /**
     * Get all interactions for a product
     */
    public static function getInteractionsForProduct($productId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($productId) {
                $query->where('drug_a_id', $productId)
                    ->orWhere('drug_b_id', $productId);
            })
            ->with(['drugA', 'drugB'])
            ->get();
    }

    /**
     * Severity levels for dropdown
     */
    public static function getSeverityLevels(): array
    {
        return [
            'minor' => 'เล็กน้อย (Minor)',
            'moderate' => 'ปานกลาง (Moderate)',
            'major' => 'รุนแรง (Major)',
            'contraindicated' => 'ห้ามใช้ร่วมกัน (Contraindicated)',
        ];
    }
}

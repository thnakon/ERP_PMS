<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Personal Info
        'name',
        'nickname',
        'phone',
        'email',
        'birth_date',
        'gender',
        'national_id',

        // Contact Info
        'address',
        'line_id',

        // Medical Records - Critical for Drug Safety
        'allergy_notes',
        'drug_allergies',    // JSON: [{drug_name: '', reaction: ''}]
        'chronic_diseases',  // JSON: array of conditions
        'pregnancy_status',
        'medical_notes',

        // Loyalty Program
        'points_balance',
        'member_tier',
        'member_since',

        // Stats
        'total_spent',
        'visit_count',
        'last_visit_at',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'member_since' => 'date',
        'allergy_notes' => 'array',
        'drug_allergies' => 'array',
        'chronic_diseases' => 'array',
        'total_spent' => 'decimal:2',
        'points_balance' => 'decimal:2',
        'visit_count' => 'integer',
        'last_visit_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get all orders for this customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the customer's age from birth date.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    /**
     * Check if customer has any drug allergies.
     */
    public function hasDrugAllergies(): bool
    {
        return !empty($this->drug_allergies) || !empty($this->allergy_notes);
    }

    /**
     * Get formatted drug allergy list for display.
     */
    public function getDrugAllergyListAttribute(): string
    {
        $allergies = [];

        // From drug_allergies JSON
        if (!empty($this->drug_allergies)) {
            foreach ($this->drug_allergies as $allergy) {
                $allergies[] = $allergy['drug_name'] ?? $allergy;
            }
        }

        // From legacy allergy_notes
        if (!empty($this->allergy_notes)) {
            $allergies = array_merge($allergies, $this->allergy_notes);
        }

        return implode(', ', array_unique($allergies));
    }

    /**
     * Get chronic diseases as string.
     */
    public function getChronicDiseaseListAttribute(): string
    {
        if (empty($this->chronic_diseases)) {
            return '';
        }
        return implode(', ', $this->chronic_diseases);
    }

    /**
     * Check if customer is allergic to a specific drug/ingredient.
     */
    public function isAllergicTo(string $drugName): bool
    {
        $drugLower = strtolower($drugName);

        // Check drug_allergies JSON
        if (!empty($this->drug_allergies)) {
            foreach ($this->drug_allergies as $allergy) {
                $allergyName = is_array($allergy) ? ($allergy['drug_name'] ?? '') : $allergy;
                if (
                    str_contains($drugLower, strtolower($allergyName)) ||
                    str_contains(strtolower($allergyName), $drugLower)
                ) {
                    return true;
                }
            }
        }

        // Check legacy allergy_notes
        if (!empty($this->allergy_notes)) {
            foreach ($this->allergy_notes as $allergy) {
                if (
                    str_contains($drugLower, strtolower($allergy)) ||
                    str_contains(strtolower($allergy), $drugLower)
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if customer is allergic to a product.
     */
    public function isAllergicToProduct(Product $product): bool
    {
        return $this->isAllergicTo($product->name) ||
            ($product->generic_name && $this->isAllergicTo($product->generic_name));
    }

    /**
     * Get pregnancy status label.
     */
    public function getPregnancyStatusLabelAttribute(): string
    {
        return match ($this->pregnancy_status) {
            'pregnant' => __('customers.pregnant'),
            'breastfeeding' => __('customers.breastfeeding'),
            default => __('customers.not_applicable'),
        };
    }

    /**
     * Get member tier badge color.
     */
    public function getTierColorAttribute(): string
    {
        return match ($this->member_tier) {
            'platinum' => 'bg-purple-100 text-purple-700',
            'gold' => 'bg-yellow-100 text-yellow-700',
            'silver' => 'bg-gray-200 text-gray-700',
            default => 'bg-blue-100 text-blue-700',
        };
    }

    /**
     * Record a visit (purchase) and add points.
     */
    public function recordVisit(float $amount = 0, float $pointsEarned = 0): void
    {
        $this->increment('visit_count');
        $this->increment('total_spent', $amount);
        $this->increment('points_balance', $pointsEarned);
        $this->update(['last_visit_at' => now()]);

        // Auto-upgrade tier based on total spent
        $this->updateTier();
    }

    /**
     * Update member tier based on total spent.
     */
    public function updateTier(): void
    {
        $tier = match (true) {
            $this->total_spent >= 50000 => 'platinum',
            $this->total_spent >= 20000 => 'gold',
            $this->total_spent >= 5000 => 'silver',
            default => 'regular',
        };

        if ($this->member_tier !== $tier) {
            $this->update(['member_tier' => $tier]);
        }
    }

    /**
     * Use points (deduct from balance).
     */
    public function usePoints(float $points): bool
    {
        if ($this->points_balance >= $points) {
            $this->decrement('points_balance', $points);
            return true;
        }
        return false;
    }
}

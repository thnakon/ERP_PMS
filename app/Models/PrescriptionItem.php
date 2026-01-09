<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'product_id',
        'quantity',
        'dosage',
        'frequency',
        'duration',
        'route',
        'instructions',
        'quantity_dispensed',
        'is_dispensed',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'quantity_dispensed' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'is_dispensed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Snapshot the price from product
            if ($item->product && !$item->unit_price) {
                $item->unit_price = $item->product->unit_price;
            }
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        static::updating(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });
    }

    /**
     * Relationships
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted dosage instructions
     */
    public function getFormattedInstructionsAttribute(): string
    {
        $parts = [];

        if ($this->dosage) {
            $parts[] = $this->dosage;
        }
        if ($this->frequency) {
            $parts[] = $this->frequency;
        }
        if ($this->duration) {
            $parts[] = "เป็นเวลา {$this->duration}";
        }
        if ($this->route) {
            $parts[] = "({$this->route})";
        }

        return implode(' ', $parts);
    }

    /**
     * Get remaining quantity to dispense
     */
    public function getRemainingQuantityAttribute(): float
    {
        return max(0, $this->quantity - $this->quantity_dispensed);
    }

    /**
     * Common routes for Thai pharmacy
     */
    public static function getRoutes(): array
    {
        return [
            'oral' => 'รับประทาน',
            'topical' => 'ทาภายนอก',
            'injection' => 'ฉีด',
            'sublingual' => 'อมใต้ลิ้น',
            'inhalation' => 'สูดดม',
            'ophthalmic' => 'หยอดตา',
            'otic' => 'หยอดหู',
            'nasal' => 'พ่นจมูก',
            'rectal' => 'เหน็บทวาร',
            'vaginal' => 'สอดช่องคลอด',
        ];
    }

    /**
     * Common frequencies for Thai pharmacy
     */
    public static function getFrequencies(): array
    {
        return [
            'od' => 'วันละ 1 ครั้ง',
            'bid' => 'วันละ 2 ครั้ง',
            'tid' => 'วันละ 3 ครั้ง',
            'qid' => 'วันละ 4 ครั้ง',
            'q4h' => 'ทุก 4 ชั่วโมง',
            'q6h' => 'ทุก 6 ชั่วโมง',
            'q8h' => 'ทุก 8 ชั่วโมง',
            'q12h' => 'ทุก 12 ชั่วโมง',
            'prn' => 'เมื่อมีอาการ',
            'hs' => 'ก่อนนอน',
            'ac' => 'ก่อนอาหาร',
            'pc' => 'หลังอาหาร',
            'stat' => 'ทันที',
        ];
    }
}

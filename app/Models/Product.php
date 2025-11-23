<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'strength',
        'dosage_form',
        'registration_number',
        'cost_price',
        'selling_price',
        'primary_indication',
        'regulatory_class',
        'image_path',
        'barcode',
        'description',
        'category_id',
        'unit_id',
        'supplier_id',
        'min_stock_level',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helper to get total stock
    public function getTotalStockAttribute()
    {
        return $this->batches()->sum('quantity');
    }
}

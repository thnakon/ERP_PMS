<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'patient_id',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class); // Assuming Patient model exists
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}

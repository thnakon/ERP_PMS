<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'line_id',
        'registrant_name',
        'email',
        'phone',
        'business_name',
        'business_type',
        'tax_id',
        'address',
        'device_count',
        'install_date',
        'install_time',
        'previous_software',
        'previous_software_name',
        'data_migration',
        'referral_source',
        'notes',
        'terms_accepted',
        'verified_at',
        'status',
    ];

    protected $casts = [
        'install_date' => 'date',
        'terms_accepted' => 'boolean',
        'verified_at' => 'datetime',
    ];
}

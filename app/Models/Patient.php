<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'birthdate',
        'hn_number',
        'phone',
        'email',
        'membership_tier',
        'chronic_diseases',
        'drug_allergies',
        'blood_group',
        'points',
        'last_visit_at',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'last_visit_at' => 'datetime',
        'chronic_diseases' => 'array',
        'drug_allergies' => 'array',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->birthdate ? $this->birthdate->age : '-';
    }
}

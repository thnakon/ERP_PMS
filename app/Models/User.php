<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'position',
        'pharmacist_license_no',
        'license_expiry',
        'status',
        'hired_date',
        'notes',
    ];

    /**
     * Role checking helpers
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff' || $this->role === 'admin';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'license_expiry' => 'date',
            'hired_date' => 'date',
        ];
    }

    /**
     * Check if user is a pharmacist
     */
    public function isPharmacist(): bool
    {
        return $this->role === 'pharmacist' || $this->role === 'admin';
    }

    /**
     * Check if license is expired or expiring soon
     */
    public function isLicenseExpiringSoon(int $days = 30): bool
    {
        if (!$this->license_expiry) {
            return false;
        }

        return $this->license_expiry->diffInDays(now(), false) <= $days;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-green-100 text-green-700',
            'suspended' => 'bg-orange-100 text-orange-700',
            'resigned' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get role badge color
     */
    public function getRoleColorAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'bg-purple-100 text-purple-700',
            'pharmacist' => 'bg-blue-100 text-blue-700',
            'staff' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

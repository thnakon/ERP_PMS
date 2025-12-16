<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'category',
        'description',
        'ip_address',
        'user_agent',
        'status',
        'subject_type',
        'subject_id',
    ];

    /**
     * The user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related subject model (polymorphic)
     */
    public function subject()
    {
        if ($this->subject_type && $this->subject_id) {
            return $this->morphTo('subject', 'subject_type', 'subject_id');
        }
        return null;
    }

    /**
     * Scope to filter by category
     */
    public function scopeCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        return $query;
    }

    /**
     * Helper to log an activity
     */
    public static function log($action, $category = 'system', $description = null, $subjectType = null, $subjectId = null, $status = 'success')
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'category' => $category,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
        ]);
    }

    /**
     * Get category badge color
     */
    public function getCategoryBadgeAttribute()
    {
        return match ($this->category) {
            'sales' => ['bg' => '#E5F1FF', 'color' => '#007AFF', 'icon' => 'fa-basket-shopping'],
            'inventory' => ['bg' => '#FFF7E6', 'color' => '#FF9500', 'icon' => 'fa-boxes-stacked'],
            'security' => ['bg' => '#FFF5F5', 'color' => '#FF3B30', 'icon' => 'fa-shield-halved'],
            'user' => ['bg' => '#E8F8F0', 'color' => '#34C759', 'icon' => 'fa-user'],
            default => ['bg' => '#F2F2F7', 'color' => '#86868B', 'icon' => 'fa-gears'],
        };
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'success' => ['icon' => 'fa-circle-check', 'color' => '#34C759'],
            'error' => ['icon' => 'fa-circle-exclamation', 'color' => '#FF3B30'],
            'warning' => ['icon' => 'fa-circle-exclamation', 'color' => '#FF9500'],
            default => ['icon' => 'fa-circle-info', 'color' => '#007AFF'],
        };
    }
}

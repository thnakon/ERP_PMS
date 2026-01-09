<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'logged_at',
        'ip_address',
        'user_agent',
        'user_id',
        'user_name',
        'action',
        'module',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
            'old_values' => 'array',
            'new_values' => 'array',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model
     */
    public function subject()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Get action icon
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'login' => 'ph-fill ph-sign-in',
            'logout' => 'ph-fill ph-sign-out',
            'create' => 'ph-fill ph-plus-circle',
            'update' => 'ph-fill ph-pencil-simple',
            'delete' => 'ph-fill ph-trash',
            'print' => 'ph-fill ph-printer',
            'export' => 'ph-fill ph-file-arrow-down',
            'view' => 'ph-fill ph-eye',
            default => 'ph-fill ph-activity',
        };
    }

    /**
     * Get action color class
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'login' => 'text-green-500 bg-green-100',
            'logout' => 'text-gray-500 bg-gray-100',
            'create' => 'text-blue-500 bg-blue-100',
            'update' => 'text-orange-500 bg-orange-100',
            'delete' => 'text-red-500 bg-red-100',
            'print' => 'text-purple-500 bg-purple-100',
            'export' => 'text-teal-500 bg-teal-100',
            'view' => 'text-indigo-500 bg-indigo-100',
            default => 'text-gray-500 bg-gray-100',
        };
    }

    /**
     * Get module icon
     */
    public function getModuleIconAttribute(): string
    {
        return match ($this->module) {
            'Inventory', 'Products' => 'ph-fill ph-package',
            'POS', 'Sales' => 'ph-fill ph-storefront',
            'Settings' => 'ph-fill ph-gear',
            'Users' => 'ph-fill ph-users',
            'Customers' => 'ph-fill ph-users-three',
            'Suppliers' => 'ph-fill ph-truck',
            'Categories' => 'ph-fill ph-squares-four',
            'Orders' => 'ph-fill ph-receipt',
            'Auth' => 'ph-fill ph-shield-check',
            default => 'ph-fill ph-folder',
        };
    }

    /**
     * Static helper to log activity
     */
    public static function log(
        string $action,
        string $module,
        ?string $description = null,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null
    ): self {
        $user = Auth::user();

        return self::create([
            'logged_at' => now(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'action' => $action,
            'module' => $module,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get formatted changes for display
     */
    public function getFormattedChangesAttribute(): array
    {
        $changes = [];

        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[] = [
                        'field' => $key,
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeInDateRange($query, string $range)
    {
        return match ($range) {
            '7days' => $query->where('logged_at', '>=', now()->subDays(7)),
            '30days', 'month' => $query->where('logged_at', '>=', now()->subDays(30)),
            'today' => $query->whereDate('logged_at', today()),
            'week' => $query->where('logged_at', '>=', now()->startOfWeek()),
            default => $query,
        };
    }
}

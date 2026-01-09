<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'path',
        'size',
        'type',
        'status',
        'notes',
        'created_by',
    ];

    /**
     * Get the user who created the backup
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            'in_progress' => 'bg-yellow-100 text-yellow-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'manual' => 'bg-blue-100 text-blue-700',
            'scheduled' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

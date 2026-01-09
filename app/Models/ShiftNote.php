<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'color',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    /**
     * Get the user who created the note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

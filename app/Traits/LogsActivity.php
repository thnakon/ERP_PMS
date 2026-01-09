<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        // Log when model is created
        static::created(function (Model $model) {
            ActivityLog::log(
                action: 'create',
                module: static::getActivityModule(),
                description: static::getActivityDescription('create', $model),
                model: $model,
                newValues: $model->getAttributes()
            );
        });

        // Log when model is updated
        static::updated(function (Model $model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();

            // Remove timestamps from changes
            unset($newValues['updated_at']);

            if (!empty($newValues)) {
                ActivityLog::log(
                    action: 'update',
                    module: static::getActivityModule(),
                    description: static::getActivityDescription('update', $model),
                    model: $model,
                    oldValues: array_intersect_key($oldValues, $newValues),
                    newValues: $newValues
                );
            }
        });

        // Log when model is deleted
        static::deleted(function (Model $model) {
            ActivityLog::log(
                action: 'delete',
                module: static::getActivityModule(),
                description: static::getActivityDescription('delete', $model),
                model: $model,
                oldValues: $model->getAttributes()
            );
        });
    }

    /**
     * Get the module name for activity logging
     */
    protected static function getActivityModule(): string
    {
        return class_basename(static::class);
    }

    /**
     * Get the description for activity logging
     */
    protected static function getActivityDescription(string $action, Model $model): string
    {
        $modelName = class_basename($model);
        $identifier = $model->name ?? $model->title ?? $model->id;

        return match ($action) {
            'create' => "สร้าง {$modelName}: {$identifier}",
            'update' => "แก้ไข {$modelName}: {$identifier}",
            'delete' => "ลบ {$modelName}: {$identifier}",
            default => "{$action} {$modelName}: {$identifier}",
        };
    }
}

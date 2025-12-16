<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Log an activity to the system
     *
     * @param string $action Short description of what happened
     * @param string $category One of: sales, inventory, system, security, user
     * @param string|null $description Detailed description
     * @param string $status One of: success, error, warning
     * @return ActivityLog
     */
    protected function logActivity($action, $category = 'system', $description = null, $status = 'success')
    {
        return ActivityLog::log(
            $action,
            $category,
            $description,
            get_class($this),
            null,
            $status
        );
    }

    /**
     * Log a model-related activity
     *
     * @param string $action Short description
     * @param object $model The model being acted upon
     * @param string $category Category
     * @param string|null $description Detailed description
     * @param string $status Status
     * @return ActivityLog
     */
    protected function logModelActivity($action, $model, $category = 'system', $description = null, $status = 'success')
    {
        return ActivityLog::log(
            $action,
            $category,
            $description,
            get_class($model),
            $model->id ?? null,
            $status
        );
    }
}

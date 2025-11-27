<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GlobalActivityObserver
{
    public function created(Model $model)
    {
        $this->logActivity($model, 'Created');
    }

    public function updated(Model $model)
    {
        $this->logActivity($model, 'Updated');
    }

    public function deleted(Model $model)
    {
        $this->logActivity($model, 'Deleted');
    }

    protected function logActivity(Model $model, string $action)
    {
        if (!Auth::check()) {
            return;
        }

        $modelName = class_basename($model);
        $description = "$action $modelName";

        // Optional: Add more details for specific models
        if ($modelName === 'Product') {
            $description .= ": " . ($model->name ?? 'Unknown Product');
        } elseif ($modelName === 'Category') {
            $description .= ": " . ($model->name ?? 'Unknown Category');
        } elseif ($modelName === 'User') {
            $description .= ": " . ($model->name ?? $model->first_name ?? 'Unknown User');
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => "$action $modelName",
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('logged_at');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter by action
        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }

        // Filter by module
        if ($module = $request->get('module')) {
            $query->where('module', $module);
        }

        // Filter by user
        if ($userId = $request->get('user')) {
            $query->where('user_id', $userId);
        }

        // Filter by date range
        if ($range = $request->get('range')) {
            $query->inDateRange($range);
        }

        $logs = $query->paginate(15);

        // Stats
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('logged_at', today())->count(),
            'week' => ActivityLog::where('logged_at', '>=', now()->subDays(7))->count(),
            'logins' => ActivityLog::where('action', 'login')->whereDate('logged_at', today())->count(),
        ];

        // Get unique modules and actions for filters
        $modules = ActivityLog::distinct()->pluck('module');
        $actions = ['login', 'logout', 'create', 'update', 'delete', 'print', 'export', 'view'];

        return view('activity-logs.index', compact('logs', 'stats', 'modules', 'actions'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Clear old logs (admin only)
     */
    public function clear(Request $request)
    {
        $days = $request->get('days', 30);

        $deleted = ActivityLog::where('logged_at', '<', now()->subDays($days))->delete();

        ActivityLog::log('delete', 'ActivityLogs', "ลบ Activity Logs เก่ากว่า {$days} วัน ({$deleted} รายการ)");

        return back()->with('success', "ลบ Activity Logs {$deleted} รายการเรียบร้อยแล้ว");
    }
}

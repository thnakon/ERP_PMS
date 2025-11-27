<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Mark as read
        $user = Auth::user();
        if ($user) {
            $user->last_read_notifications_at = now();
            $user->save();
        }

        // Fetch all logs, paginated
        $notifications = ActivityLog::with('user')->latest()->paginate(10);
        return view('notifications', compact('notifications'));
    }

    public function markAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->last_read_notifications_at = now();
            $user->save();
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        // Admin only check
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $log = ActivityLog::findOrFail($id);
        $log->update([
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Notification updated successfully.');
    }

    public function destroy($id)
    {
        // Admin only check
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}

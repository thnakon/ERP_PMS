<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class SessionController extends Controller
{
    /**
     * Show all active sessions for the user
     */
    public function index()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $agent = new Agent();
                $agent->setUserAgent($session->user_agent);

                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'browser' => $agent->browser() ?: 'Unknown',
                    'platform' => $agent->platform() ?: 'Unknown',
                    'device' => $agent->isDesktop() ? 'Desktop' : ($agent->isMobile() ? 'Mobile' : 'Tablet'),
                    'is_current' => $session->id === session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });

        return view('profile.sessions', compact('sessions'));
    }

    /**
     * Logout from a specific session
     */
    public function destroy(Request $request, $sessionId)
    {
        // Don't allow deleting current session through this method
        if ($sessionId === session()->getId()) {
            return back()->with('error', 'ไม่สามารถลบ Session ปัจจุบันได้');
        }

        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->delete();

        if ($deleted) {
            return back()->with('success', 'ออกจากระบบอุปกรณ์ที่เลือกแล้ว');
        }

        return back()->with('error', 'ไม่พบ Session ที่ต้องการ');
    }

    /**
     * Logout from all other sessions
     */
    public function destroyOthers(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'รหัสผ่านไม่ถูกต้อง']);
        }

        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return back()->with('success', 'ออกจากระบบอุปกรณ์อื่นทั้งหมดแล้ว');
    }
}

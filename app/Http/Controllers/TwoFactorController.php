<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA settings page
     */
    public function settings()
    {
        $user = auth()->user();
        return view('profile.two-factor', compact('user'));
    }

    /**
     * Enable 2FA for user
     */
    public function enable(Request $request)
    {
        $user = auth()->user();

        // Generate a random secret (6-digit)
        $secret = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store secret temporarily in session
        session(['2fa_setup_secret' => $secret]);

        // Send verification code
        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($secret, $user->name));
        } catch (\Exception $e) {
            return back()->with('error', 'ไม่สามารถส่งอีเมลได้ กรุณาลองใหม่');
        }

        return back()->with('status', 'รหัสยืนยันถูกส่งไปที่อีเมลของคุณแล้ว');
    }

    /**
     * Confirm 2FA setup
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $secret = session('2fa_setup_secret');

        if (!$secret || $request->code !== $secret) {
            return back()->withErrors(['code' => 'รหัสไม่ถูกต้อง กรุณาลองใหม่']);
        }

        $user = auth()->user();
        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        session()->forget('2fa_setup_secret');

        return back()->with('success', 'เปิดใช้งาน 2FA เรียบร้อยแล้ว!');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'รหัสผ่านไม่ถูกต้อง']);
        }

        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return back()->with('success', 'ปิดใช้งาน 2FA เรียบร้อยแล้ว');
    }

    /**
     * Show 2FA challenge page (during login)
     */
    public function challenge()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify 2FA code during login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_user_id');
        $storedCode = session('2fa_code');
        $expiresAt = session('2fa_expires_at');

        if (!$userId || !$storedCode) {
            return redirect()->route('login')->with('error', 'Session หมดอายุ กรุณาเข้าสู่ระบบใหม่');
        }

        if (now()->isAfter($expiresAt)) {
            session()->forget(['2fa_user_id', '2fa_code', '2fa_expires_at']);
            return redirect()->route('login')->with('error', 'รหัสหมดอายุแล้ว กรุณาเข้าสู่ระบบใหม่');
        }

        if ($request->code !== $storedCode) {
            return back()->withErrors(['code' => 'รหัสไม่ถูกต้อง']);
        }

        // Clear 2FA session
        session()->forget(['2fa_user_id', '2fa_code', '2fa_expires_at']);

        // Login the user
        $user = User::find($userId);
        Auth::login($user, session('2fa_remember', false));
        session()->forget('2fa_remember');

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Resend 2FA code
     */
    public function resend()
    {
        $userId = session('2fa_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        session([
            '2fa_code' => $code,
            '2fa_expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($code, $user->name));
        } catch (\Exception $e) {
            return back()->with('error', 'ไม่สามารถส่งอีเมลได้');
        }

        return back()->with('status', 'รหัสใหม่ถูกส่งไปที่อีเมลของคุณแล้ว');
    }
}

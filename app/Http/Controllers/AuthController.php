<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VerificationCodeMail;
use App\Mail\RegistrationCredentialsMail;
use App\Mail\PasswordResetMail;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle authentication attempt
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard')->with('success', __('welcome_back', ['name' => Auth::user()->name]));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', __('logout_success'));
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            try {
                Mail::to($request->email)->send(new PasswordResetMail(
                    route('password.reset', ['token' => $token, 'email' => $request->email]),
                    $user->name
                ));
            } catch (\Exception $e) {
                Log::error('Failed to send password reset email: ' . $e->getMessage());
            }
        }

        // Always show success message to prevent user enumeration
        return back()->with('status', 'If an account exists with this email, you will receive a password reset link shortly.');
    }

    /**
     * Show the reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle the password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is older than 60 minutes
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Reset token has expired.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'line_id' => ['required', 'string', 'max:100'],
            'registrant_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:registrations,email'],
            'phone' => ['required', 'string', 'max:20'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'in:pharmacy,other'],
            'tax_id' => ['required', 'string', 'max:13'],
            'address' => ['required', 'string'],
            'device_count' => ['required', 'string'],
            'install_date' => ['required', 'date'],
            'install_time' => ['required', 'string'],
            'previous_software' => ['required', 'in:none,other'],
            'data_migration' => ['required', 'in:none,new,transfer'],
            'referral_source' => ['required', 'string'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store registration data in session for verification
        session([
            'pending_registration' => $validated,
            'verification_code' => $verificationCode,
            'verification_email' => $validated['email'],
            'verification_expires_at' => now()->addMinutes(15),
        ]);

        // Send verification email
        try {
            Mail::to($validated['email'])->send(
                new VerificationCodeMail($verificationCode, $validated['registrant_name'])
            );
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถส่งอีเมลได้ กรุณาลองใหม่อีกครั้ง',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email',
            'email' => $validated['email'],
        ]);
    }

    /**
     * Verify email with OTP code
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $storedCode = session('verification_code');
        $pendingData = session('pending_registration');
        $expiresAt = session('verification_expires_at');

        if (!$storedCode || !$pendingData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please register again.',
            ], 400);
        }

        // Check if code has expired
        if ($expiresAt && now()->isAfter($expiresAt)) {
            session()->forget(['pending_registration', 'verification_code', 'verification_email', 'verification_expires_at']);
            return response()->json([
                'success' => false,
                'message' => 'รหัสยืนยันหมดอายุแล้ว กรุณาลงทะเบียนใหม่',
            ], 400);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'รหัสยืนยันไม่ถูกต้อง',
            ], 400);
        }

        // Create the registration record
        // Convert terms_accepted from 'on' to boolean
        $pendingData['terms_accepted'] = $pendingData['terms_accepted'] === 'on' || $pendingData['terms_accepted'] === true || $pendingData['terms_accepted'] === '1';

        $registration = \App\Models\Registration::create(array_merge($pendingData, [
            'verified_at' => now(),
            'status' => 'verified',
        ]));

        // Generate credentials
        $lineId = str_replace('@', '', $pendingData['line_id']);
        $lineId = preg_replace('/[^a-zA-Z0-9]/', '', $lineId); // Clean special chars
        $adminEmail = $lineId . '.admin@oboun.local';
        $staffEmail = $lineId . '.staff@oboun.local';
        $password = $pendingData['phone'];

        // Create admin user
        $admin = \App\Models\User::create([
            'name' => $pendingData['registrant_name'] . ' (Admin)',
            'email' => $adminEmail,
            'password' => bcrypt($password),
            'role' => 'admin',
        ]);

        // Create staff user
        $staff = \App\Models\User::create([
            'name' => $pendingData['registrant_name'] . ' (Staff)',
            'email' => $staffEmail,
            'password' => bcrypt($password),
            'role' => 'staff',
        ]);

        // Send credentials email
        try {
            Mail::to($pendingData['email'])->send(
                new RegistrationCredentialsMail(
                    $pendingData['registrant_name'],
                    $pendingData['business_name'],
                    $adminEmail,
                    $staffEmail,
                    $password
                )
            );
        } catch (\Exception $e) {
            Log::error('Failed to send credentials email: ' . $e->getMessage());
            // Don't fail the registration, just log the error
        }

        // Clear session data
        session()->forget(['pending_registration', 'verification_code', 'verification_email', 'verification_expires_at']);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Check your email for login credentials.',
            'credentials' => [
                'admin_email' => $adminEmail,
                'staff_email' => $staffEmail,
                'password' => $password,
            ],
        ]);
    }
}

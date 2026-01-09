<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the profile page
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get user's login history (last 30 days)
        $loginHistory = ActivityLog::where('user_id', $user->id)
            ->whereIn('action', ['login', 'logout'])
            ->where('logged_at', '>=', now()->subDays(30))
            ->latest('logged_at')
            ->limit(20)
            ->get();

        // Get user's recent activity (last 7 days)
        $recentActivity = ActivityLog::where('user_id', $user->id)
            ->where('logged_at', '>=', now()->subDays(7))
            ->latest('logged_at')
            ->limit(10)
            ->get();

        return view('profile.edit', compact('user', 'loginHistory', 'recentActivity'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => __('profile.current_password_incorrect'),
            'password.confirmed' => __('profile.password_confirmation_mismatch'),
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        // Log the password change
        ActivityLog::log(
            action: 'update',
            module: 'Profile',
            description: 'เปลี่ยนรหัสผ่าน',
            model: $user
        );

        return back()->with('success', __('profile.password_updated'));
    }

    /**
     * Update language preference
     */
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => ['required', 'in:en,th'],
        ]);

        // Store language preference in session
        session()->put('locale', $request->language);
        app()->setLocale($request->language);

        // Log the language change
        ActivityLog::log(
            action: 'update',
            module: 'Profile',
            description: 'เปลี่ยนภาษาเป็น ' . ($request->language === 'th' ? 'ไทย' : 'English')
        );

        return back()->with('success', __('profile.language_updated'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        // Log the profile update
        ActivityLog::log(
            action: 'update',
            module: 'Profile',
            description: 'อัปเดตข้อมูลส่วนตัว',
            model: $user,
            oldValues: $oldData,
            newValues: [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        );

        return back()->with('success', __('profile.profile_updated'));
    }

    /**
     * Update avatar
     */
    public function updateAvatar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Handle avatar removal
        if ($request->remove_avatar === '1') {
            // Delete old avatar file
            if ($user->avatar) {
                $oldPath = storage_path('app/public/' . $user->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $user->avatar = null;
            $user->save();

            ActivityLog::log(
                action: 'update',
                module: 'Profile',
                description: 'ลบรูปโปรไฟล์'
            );

            return back()->with('success', __('profile.avatar_removed'));
        }

        // Handle avatar upload
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        // Delete old avatar
        if ($user->avatar) {
            $oldPath = storage_path('app/public/' . $user->avatar);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        ActivityLog::log(
            action: 'update',
            module: 'Profile',
            description: 'อัปโหลดรูปโปรไฟล์ใหม่'
        );

        return back()->with('success', __('profile.avatar_updated'));
    }

    /**
     * Delete user account
     */
    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validate password
        $request->validate([
            'password' => ['required'],
        ]);

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => __('profile.password_incorrect'),
            ]);
        }

        // Prevent admin from deleting themselves if they are the only admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors([
                    'password' => __('profile.last_admin_cannot_delete'),
                ]);
            }
        }

        // Delete avatar file if exists
        if ($user->avatar) {
            $avatarPath = storage_path('app/public/' . $user->avatar);
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }

        // Log before deletion
        ActivityLog::log(
            action: 'delete',
            module: 'Profile',
            description: 'ลบบัญชีผู้ใช้: ' . $user->email
        );

        // Logout and delete
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', __('profile.account_deleted'));
    }
}

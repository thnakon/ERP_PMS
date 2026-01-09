<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users/staff.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name, username, email, or license no
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('pharmacist_license_no', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $users = $query->paginate(12);

        // Stats
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'pharmacists' => User::whereIn('role', ['admin', 'pharmacist'])->count(),
            'expiring_licenses' => User::whereNotNull('license_expiry')
                ->where('license_expiry', '<=', now()->addDays(30))
                ->where('license_expiry', '>=', now())
                ->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pharmacist,staff',
            'position' => 'nullable|string|max:100',
            'pharmacist_license_no' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'status' => 'required|in:active,suspended,resigned',
            'hired_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($validated);

        return redirect()->route('users.show', $user)
            ->with('success', __('users.user_created'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:100', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,pharmacist,staff',
            'position' => 'nullable|string|max:100',
            'pharmacist_license_no' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'status' => 'required|in:active,suspended,resigned',
            'hired_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'remove_avatar' => 'nullable|in:0,1',
        ]);

        // Handle avatar removal
        if ($request->input('remove_avatar') == '1') {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }
        // Handle avatar upload
        elseif ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Remove the remove_avatar flag from validated data
        unset($validated['remove_avatar']);

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', __('users.user_updated'));
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', __('users.cannot_delete_self'));
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('users.user_deleted'));
    }
}

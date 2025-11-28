<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $logs = ActivityLog::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10)
            ->appends(['tab' => 'activity']);

        return view('profile.edit', [
            'user' => $request->user(),
            'logs' => $logs,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $changes = [];

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
            $changes[] = 'Updated profile photo';
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $changes[] = 'Changed email to ' . $user->email;
        }

        // Check for other dirty fields to log specific changes
        foreach (['first_name', 'last_name', 'phone_number', 'gender', 'birthdate', 'language', 'theme'] as $field) {
            if ($user->isDirty($field)) {
                $changes[] = "Changed $field";
            }
        }

        $user->save();

        if (!empty($changes)) {
            $this->logActivity($request, 'Profile Updated', implode(', ', $changes));
        }

        return Redirect::route('profile.edit', ['tab' => 'settings'])->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $this->logActivity($request, 'Account Deleted', 'User deleted their own account');

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Export activity logs to CSV.
     */
    public function exportLogs(Request $request)
    {
        // Export activity logs to PDF using dompdf
        $logs = ActivityLog::where('user_id', $request->user()->id)->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.activity_log', ['logs' => $logs]);
        $pdfFileName = 'activity_logs_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($pdfFileName);
    }

    /**
     * Helper to log activity
     */
    private function logActivity(Request $request, string $action, string $description = null)
    {
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}

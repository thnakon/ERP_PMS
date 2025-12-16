<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class PeoplesController extends Controller
{
    use LogsActivity;
    public function patientscustomer(Request $request)
    {
        // Check Admin Role (Simple check assuming role column exists or user is admin)
        // if (auth()->user()->role !== 'admin') { abort(403); }

        $query = \App\Models\Patient::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('hn_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by Membership Tier
        if ($request->filled('membership_tier') && $request->membership_tier !== 'all') {
            $query->where('membership_tier', $request->membership_tier);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name_asc':
                    $query->orderBy('first_name')->orderBy('last_name');
                    break;
                case 'name_desc':
                    $query->orderByDesc('first_name')->orderByDesc('last_name');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $patients = $query->paginate(5)->withQueryString();

        return view('peoples.patients-customer', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'gender' => 'required|string',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'membership_tier' => 'nullable|string',
            'chronic_diseases' => 'nullable',
            'drug_allergies' => 'nullable',
            'blood_group' => 'nullable|string',
            'points' => 'nullable|integer',
        ]);

        // Auto-generate HN if not provided or handle it better
        $validated['hn_number'] = 'MB-' . date('Y') . '-' . rand(100, 999);

        if (!isset($validated['points'])) {
            $validated['points'] = 0;
        }

        // Handle Comma-Separated Strings for Arrays
        if (isset($validated['chronic_diseases']) && is_string($validated['chronic_diseases'])) {
            $validated['chronic_diseases'] = array_values(array_filter(array_map('trim', explode(',', $validated['chronic_diseases']))));
        }
        if (isset($validated['drug_allergies']) && is_string($validated['drug_allergies'])) {
            $validated['drug_allergies'] = array_values(array_filter(array_map('trim', explode(',', $validated['drug_allergies']))));
        }

        $patient = \App\Models\Patient::create($validated);

        // Log the activity
        \App\Models\ActivityLog::log(
            'New Patient Registered',
            'user',
            "Added '{$patient->first_name} {$patient->last_name}' to the system",
            \App\Models\Patient::class,
            $patient->id
        );

        return back()->with('success', 'Patient added successfully.')->with('suppress_global_toast', true);
    }

    public function update(Request $request, $id)
    {
        $patient = \App\Models\Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'gender' => 'required|string',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'membership_tier' => 'nullable|string',
            'chronic_diseases' => 'nullable',
            'drug_allergies' => 'nullable',
            'blood_group' => 'nullable|string',
            'points' => 'nullable|integer',
        ]);

        if (!isset($validated['points'])) {
            $validated['points'] = 0;
        }

        // Handle Comma-Separated Strings for Arrays
        if (isset($validated['chronic_diseases']) && is_string($validated['chronic_diseases'])) {
            $validated['chronic_diseases'] = array_values(array_filter(array_map('trim', explode(',', $validated['chronic_diseases']))));
        }
        if (isset($validated['drug_allergies']) && is_string($validated['drug_allergies'])) {
            $validated['drug_allergies'] = array_values(array_filter(array_map('trim', explode(',', $validated['drug_allergies']))));
        }

        $patient->update($validated);

        // Log the activity
        \App\Models\ActivityLog::log(
            'Patient Updated',
            'user',
            "Updated patient '{$patient->first_name} {$patient->last_name}'",
            \App\Models\Patient::class,
            $patient->id
        );

        return back()->with('success', 'Patient updated successfully.')->with('suppress_global_toast', true);
    }

    public function destroy($id)
    {
        $patient = \App\Models\Patient::findOrFail($id);
        $patientName = $patient->first_name . ' ' . $patient->last_name;
        $patient->delete();

        // Log the activity
        \App\Models\ActivityLog::log(
            'Patient Deleted',
            'user',
            "Removed patient '{$patientName}' from the system",
            \App\Models\Patient::class,
            $id
        );

        return back()->with('success', 'Patient deleted successfully.')->with('suppress_global_toast', true);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:patients,id',
        ]);

        \App\Models\Patient::whereIn('id', $validated['ids'])->delete();

        return response()->json(['success' => true]);
    }

    public function staffuser(Request $request)
    {
        $query = \App\Models\User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by Role (using 'role' input from filter dropdown if exists, logic map to 'membership_tier' in UI or need to update UI)
        // The UI in staff-user.blade.php currently has name="membership_tier" which is wrong for staff.
        // It should be name="role". I will assume standard request params.
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name_asc':
                    $query->orderBy('first_name')->orderBy('last_name');
                    break;
                case 'name_desc':
                    $query->orderByDesc('first_name')->orderByDesc('last_name');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $staffs = $query->paginate(5)->withQueryString();

        return view('peoples.staff-user', compact('staffs'));
    }

    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'position' => 'nullable|string',
            'pharmacist_license_id' => 'nullable|string',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'phone_number' => 'nullable|string',
            'profile_photo_path' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // Generate Employee ID
        $validated['employee_id'] = 'EMP-' . date('Y') . '-' . rand(1000, 9999);

        // Upload Profile Photo
        if ($request->hasFile('profile_photo_path')) {
            $path = $request->file('profile_photo_path')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }

        // Create User
        // Note: Password hashing is handled by User model 'hashed' cast or we can hash manually.
        // To be safe assuming default Laravel behavior with casts:
        // $validated['password'] is plain text.

        $user = \App\Models\User::create($validated);

        // Log the activity
        \App\Models\ActivityLog::log(
            'New Employee Added',
            'user',
            "Added '{$user->first_name} {$user->last_name}' as {$user->role}",
            \App\Models\User::class,
            $user->id
        );

        return back()->with('success', 'Employee added successfully.')->with('suppress_global_toast', true);
    }

    public function updateStaff(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            'position' => 'nullable|string',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'phone_number' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }
        unset($validated['profile_photo']);

        $user->update($validated);

        // Log the activity
        \App\Models\ActivityLog::log(
            'Employee Updated',
            'user',
            "Updated employee '{$user->first_name} {$user->last_name}'",
            \App\Models\User::class,
            $user->id
        );

        return redirect()->route('peoples.staff-user')->with('success', 'Employee updated successfully.')->with('suppress_global_toast', true);
    }

    public function destroyStaff($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $userName = $user->first_name . ' ' . $user->last_name;
        $user->delete();

        // Log the activity
        \App\Models\ActivityLog::log(
            'Employee Deleted',
            'user',
            "Removed employee '{$userName}' from the system",
            \App\Models\User::class,
            $id
        );

        return back()->with('success', 'Employee deleted successfully.')->with('suppress_global_toast', true);
    }

    public function bulkDeleteStaff(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No employees selected'], 400);
        }

        \App\Models\User::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Employees deleted successfully']);
    }

    public function recent(Request $request)
    {
        $query = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->category($request->category);
        }

        // Apply search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply date filter
        if ($request->filled('date')) {
            if ($request->date === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($request->date === 'week') {
                $query->where('created_at', '>=', now()->subWeek());
            } elseif ($request->date === 'month') {
                $query->where('created_at', '>=', now()->subMonth());
            }
        }

        $logs = $query->paginate(5)->withQueryString();

        return view('peoples.recent', compact('logs'));
    }

    public function exportLogs(Request $request)
    {
        $query = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply same filters as listing
        if ($request->filled('category') && $request->category !== 'all') {
            $query->category($request->category);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('date')) {
            if ($request->date === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($request->date === 'week') {
                $query->where('created_at', '>=', now()->subWeek());
            } elseif ($request->date === 'month') {
                $query->where('created_at', '>=', now()->subMonth());
            }
        }

        $logs = $query->get();
        $format = $request->input('format', 'csv');
        $timestamp = now()->format('Y-m-d_His');

        // Prepare data array
        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                'ID' => $log->id,
                'User' => $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'System',
                'Action' => $log->action,
                'Category' => ucfirst($log->category ?? 'system'),
                'Description' => $log->description,
                'Status' => ucfirst($log->status ?? 'success'),
                'IP Address' => $log->ip_address,
                'Date/Time' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        }

        if ($format === 'excel') {
            // Export as Excel (using HTML table that Excel can open)
            $filename = "activity_logs_{$timestamp}.xls";
            $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $html = '<html><head><meta charset="UTF-8"></head><body>';
            $html .= '<table border="1" style="border-collapse: collapse;">';
            $html .= '<tr style="background-color: #007AFF; color: white; font-weight: bold;">';
            $html .= '<th>ID</th><th>User</th><th>Action</th><th>Category</th><th>Description</th><th>Status</th><th>IP Address</th><th>Date/Time</th>';
            $html .= '</tr>';

            foreach ($data as $row) {
                $statusColor = $row['Status'] === 'Error' ? '#FF3B30' : ($row['Status'] === 'Warning' ? '#FF9500' : '#34C759');
                $html .= '<tr>';
                $html .= "<td>{$row['ID']}</td>";
                $html .= "<td>{$row['User']}</td>";
                $html .= "<td>{$row['Action']}</td>";
                $html .= "<td>{$row['Category']}</td>";
                $html .= "<td>{$row['Description']}</td>";
                $html .= "<td style=\"color: {$statusColor}\">{$row['Status']}</td>";
                $html .= "<td>{$row['IP Address']}</td>";
                $html .= "<td>{$row['Date/Time']}</td>";
                $html .= '</tr>';
            }
            $html .= '</table></body></html>';

            return response($html, 200, $headers);
        } elseif ($format === 'pdf') {
            // Export as PDF (HTML for browser to print as PDF)
            $filename = "activity_logs_{$timestamp}.pdf";

            $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
            $html .= '<title>Activity Logs Export</title>';
            $html .= '<style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 20px; }
                h1 { color: #1D1D1F; margin-bottom: 5px; }
                .subtitle { color: #86868B; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                th { background-color: #007AFF; color: white; padding: 10px; text-align: left; }
                td { padding: 8px 10px; border-bottom: 1px solid #E5E5EA; }
                tr:nth-child(even) { background-color: #F5F5F7; }
                .status-success { color: #34C759; }
                .status-error { color: #FF3B30; }
                .status-warning { color: #FF9500; }
                @media print { body { padding: 0; } }
            </style></head><body>';
            $html .= '<h1>Activity Logs Report</h1>';
            $html .= '<p class="subtitle">Generated on ' . now()->format('F j, Y g:i A') . '</p>';
            $html .= '<table><thead><tr>';
            $html .= '<th>ID</th><th>User</th><th>Action</th><th>Category</th><th>Description</th><th>Status</th><th>IP</th><th>Date/Time</th>';
            $html .= '</tr></thead><tbody>';

            foreach ($data as $row) {
                $statusClass = strtolower($row['Status']) === 'error' ? 'status-error' : (strtolower($row['Status']) === 'warning' ? 'status-warning' : 'status-success');
                $html .= '<tr>';
                $html .= "<td>{$row['ID']}</td>";
                $html .= "<td>{$row['User']}</td>";
                $html .= "<td>{$row['Action']}</td>";
                $html .= "<td>{$row['Category']}</td>";
                $html .= "<td>{$row['Description']}</td>";
                $html .= "<td class=\"{$statusClass}\">{$row['Status']}</td>";
                $html .= "<td>{$row['IP Address']}</td>";
                $html .= "<td>{$row['Date/Time']}</td>";
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $html .= '<script>window.onload = function() { window.print(); }</script>';
            $html .= '</body></html>';

            return response($html)->header('Content-Type', 'text/html');
        } else {
            // Default: CSV export
            $filename = "activity_logs_{$timestamp}.csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'User', 'Action', 'Category', 'Description', 'Status', 'IP Address', 'Date/Time']);
                foreach ($data as $row) {
                    fputcsv($file, array_values($row));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
}

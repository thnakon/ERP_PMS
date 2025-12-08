<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeoplesController extends Controller
{
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

        \App\Models\Patient::create($validated);

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

        return back()->with('success', 'Patient updated successfully.')->with('suppress_global_toast', true);
    }

    public function destroy($id)
    {
        \App\Models\Patient::findOrFail($id)->delete();
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

    public function staffuser()
    {
        return view('peoples.staff-user');
    }

    public function recent()
    {
        return view('peoples.recent');
    }
}

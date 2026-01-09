<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers/patients.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search by name, phone, or national ID
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");

                // Check if newer columns exist
                if (Schema::hasColumn('customers', 'nickname')) {
                    $q->orWhere('nickname', 'like', "%{$search}%");
                }
                if (Schema::hasColumn('customers', 'national_id')) {
                    $q->orWhere('national_id', 'like', "%{$search}%");
                }
            });
        }

        // Filter by member tier (if column exists)
        if ($tier = $request->get('tier')) {
            if (Schema::hasColumn('customers', 'member_tier')) {
                $query->where('member_tier', $tier);
            }
        }

        // Filter by pregnancy status (if column exists)
        if ($pregnancy = $request->get('pregnancy')) {
            if (Schema::hasColumn('customers', 'pregnancy_status')) {
                $query->where('pregnancy_status', $pregnancy);
            }
        }

        // Filter by allergy status
        if ($request->get('has_allergies') === 'yes') {
            $query->where(function ($q) {
                if (Schema::hasColumn('customers', 'drug_allergies')) {
                    $q->whereNotNull('drug_allergies');
                }
                $q->orWhereNotNull('allergy_notes');
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $customers = $query->paginate(12);

        // Stats for dashboard cards - handle missing columns gracefully
        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('is_active', true)->count(),
            'with_allergies' => Customer::where(function ($q) {
                if (Schema::hasColumn('customers', 'drug_allergies')) {
                    $q->whereNotNull('drug_allergies');
                }
                $q->orWhereNotNull('allergy_notes');
            })->count(),
            'platinum' => Schema::hasColumn('customers', 'member_tier')
                ? Customer::where('member_tier', 'platinum')->count()
                : 0,
        ];

        return view('customers.index', compact('customers', 'stats'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'national_id' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'line_id' => 'nullable|string|max:100',
            'drug_allergies' => 'nullable|array',
            'drug_allergies.*.drug_name' => 'required_with:drug_allergies|string',
            'drug_allergies.*.reaction' => 'nullable|string',
            'chronic_diseases' => 'nullable|array',
            'pregnancy_status' => 'nullable|in:none,pregnant,breastfeeding',
            'medical_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['member_since'] = now();
        $validated['is_active'] = true;

        $customer = Customer::create($validated);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', __('customers.created_success'));
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['orders' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'national_id' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'line_id' => 'nullable|string|max:100',
            'drug_allergies' => 'nullable|array',
            'drug_allergies.*.drug_name' => 'required_with:drug_allergies|string',
            'drug_allergies.*.reaction' => 'nullable|string',
            'chronic_diseases' => 'nullable|array',
            'pregnancy_status' => 'nullable|in:none,pregnant,breastfeeding',
            'medical_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', __('customers.updated_success'));
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', __('customers.deleted_success'));
    }

    /**
     * Search customers via AJAX (for POS).
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $customers = Customer::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('nickname', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'nickname', 'phone', 'drug_allergies', 'member_tier', 'points_balance']);

        return response()->json($customers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\DrugInteraction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['customer', 'user', 'items.product']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('prescription_number', 'like', "%{$search}%")
                    ->orWhere('doctor_name', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Date filter
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('prescription_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('prescription_date', '<=', $endDate);
        }

        // Customer filter
        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        $prescriptions = $query->latest('prescription_date')->paginate(15);

        // Stats
        $stats = [
            'total' => Prescription::count(),
            'pending' => Prescription::pending()->count(),
            'dispensed_today' => Prescription::dispensed()
                ->whereDate('dispensed_at', today())
                ->count(),
            'needs_refill' => Prescription::needsRefillReminder()->count(),
        ];

        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone']);

        return view('prescriptions.index', compact('prescriptions', 'stats', 'customers'));
    }

    /**
     * Show the form for creating a new prescription.
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'unit_price', 'stock_qty']);

        $frequencies = PrescriptionItem::getFrequencies();
        $routes = PrescriptionItem::getRoutes();

        $selectedCustomer = null;
        if ($customerId = $request->input('customer_id')) {
            $selectedCustomer = Customer::find($customerId);
        }

        return view('prescriptions.create', compact(
            'customers',
            'products',
            'frequencies',
            'routes',
            'selectedCustomer'
        ));
    }

    /**
     * Store a newly created prescription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'doctor_name' => 'required|string|max:255',
            'doctor_license_no' => 'nullable|string|max:50',
            'hospital_clinic' => 'nullable|string|max:255',
            'doctor_phone' => 'nullable|string|max:20',
            'prescription_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:prescription_date',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'refill_allowed' => 'nullable|integer|min:0|max:12',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.dosage' => 'required|string|max:100',
            'items.*.frequency' => 'required|string|max:100',
            'items.*.duration' => 'nullable|string|max:50',
            'items.*.route' => 'nullable|string|max:50',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create prescription
            $prescription = Prescription::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'doctor_name' => $validated['doctor_name'],
                'doctor_license_no' => $validated['doctor_license_no'] ?? null,
                'hospital_clinic' => $validated['hospital_clinic'] ?? null,
                'doctor_phone' => $validated['doctor_phone'] ?? null,
                'prescription_date' => $validated['prescription_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'refill_allowed' => $validated['refill_allowed'] ?? 0,
                'status' => 'pending',
            ]);

            // Create prescription items
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'dosage' => $itemData['dosage'],
                    'frequency' => $itemData['frequency'],
                    'duration' => $itemData['duration'] ?? null,
                    'route' => $itemData['route'] ?? null,
                    'instructions' => $itemData['instructions'] ?? null,
                    'unit_price' => $product->unit_price,
                ]);
            }

            // Check for drug interactions
            $prescription->load('items.product');
            $interactions = $prescription->checkDrugInteractions();

            DB::commit();

            $message = __('prescriptions.created_successfully');
            if (count($interactions) > 0) {
                $message .= ' ' . __('prescriptions.drug_interactions_found', ['count' => count($interactions)]);
            }

            return redirect()
                ->route('prescriptions.show', $prescription)
                ->with('success', $message)
                ->with('interactions', $interactions);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', __('prescriptions.create_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified prescription.
     */
    public function show(Prescription $prescription)
    {
        $prescription->load(['customer', 'user', 'items.product', 'order']);

        // Check drug interactions
        $interactions = $prescription->checkDrugInteractions();

        // Get customer's prescription history
        $customerHistory = Prescription::where('customer_id', $prescription->customer_id)
            ->where('id', '!=', $prescription->id)
            ->with('items.product')
            ->latest('prescription_date')
            ->limit(5)
            ->get();

        return view('prescriptions.show', compact('prescription', 'interactions', 'customerHistory'));
    }

    /**
     * Show the form for editing the specified prescription.
     */
    public function edit(Prescription $prescription)
    {
        if ($prescription->status === 'dispensed') {
            return back()->with('error', __('prescriptions.cannot_edit_dispensed'));
        }

        $prescription->load('items.product');

        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'unit_price', 'stock_qty']);

        $frequencies = PrescriptionItem::getFrequencies();
        $routes = PrescriptionItem::getRoutes();

        return view('prescriptions.edit', compact(
            'prescription',
            'customers',
            'products',
            'frequencies',
            'routes'
        ));
    }

    /**
     * Update the specified prescription.
     */
    public function update(Request $request, Prescription $prescription)
    {
        if ($prescription->status === 'dispensed') {
            return back()->with('error', __('prescriptions.cannot_edit_dispensed'));
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'doctor_name' => 'required|string|max:255',
            'doctor_license_no' => 'nullable|string|max:50',
            'hospital_clinic' => 'nullable|string|max:255',
            'doctor_phone' => 'nullable|string|max:20',
            'prescription_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:prescription_date',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'refill_allowed' => 'nullable|integer|min:0|max:12',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.dosage' => 'required|string|max:100',
            'items.*.frequency' => 'required|string|max:100',
            'items.*.duration' => 'nullable|string|max:50',
            'items.*.route' => 'nullable|string|max:50',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update prescription
            $prescription->update([
                'customer_id' => $validated['customer_id'],
                'doctor_name' => $validated['doctor_name'],
                'doctor_license_no' => $validated['doctor_license_no'] ?? null,
                'hospital_clinic' => $validated['hospital_clinic'] ?? null,
                'doctor_phone' => $validated['doctor_phone'] ?? null,
                'prescription_date' => $validated['prescription_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'refill_allowed' => $validated['refill_allowed'] ?? 0,
            ]);

            // Delete existing items and recreate
            $prescription->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'dosage' => $itemData['dosage'],
                    'frequency' => $itemData['frequency'],
                    'duration' => $itemData['duration'] ?? null,
                    'route' => $itemData['route'] ?? null,
                    'instructions' => $itemData['instructions'] ?? null,
                    'unit_price' => $product->unit_price,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('prescriptions.show', $prescription)
                ->with('success', __('prescriptions.updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', __('prescriptions.update_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified prescription.
     */
    public function destroy(Prescription $prescription)
    {
        if ($prescription->status === 'dispensed') {
            return back()->with('error', __('prescriptions.cannot_delete_dispensed'));
        }

        $prescription->delete();

        return redirect()
            ->route('prescriptions.index')
            ->with('success', __('prescriptions.deleted_successfully'));
    }

    /**
     * Dispense a prescription.
     */
    public function dispense(Request $request, Prescription $prescription)
    {
        if ($prescription->status !== 'pending' && $prescription->status !== 'partially_dispensed') {
            return back()->with('error', __('prescriptions.cannot_dispense'));
        }

        if ($prescription->is_expired) {
            return back()->with('error', __('prescriptions.prescription_expired'));
        }

        DB::beginTransaction();

        try {
            // Mark as dispensed
            $prescription->markAsDispensed();

            // Calculate next refill date if applicable
            if ($prescription->refill_allowed > 0) {
                $nextRefillDate = $prescription->calculateNextRefillDate();
                $prescription->update(['next_refill_date' => $nextRefillDate]);
            }

            // TODO: Optionally create an order and deduct stock

            DB::commit();

            return redirect()
                ->route('prescriptions.show', $prescription)
                ->with('success', __('prescriptions.dispensed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('prescriptions.dispense_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Process a refill for the prescription.
     */
    public function refill(Prescription $prescription)
    {
        if (!$prescription->can_refill) {
            return back()->with('error', __('prescriptions.cannot_refill'));
        }

        DB::beginTransaction();

        try {
            // Increment refill count
            $prescription->increment('refill_count');

            // Calculate new next refill date
            $nextRefillDate = $prescription->calculateNextRefillDate();
            $prescription->update([
                'next_refill_date' => $nextRefillDate,
                'refill_reminder_sent' => false,
            ]);

            // TODO: Create a new order for the refill

            DB::commit();

            return redirect()
                ->route('prescriptions.show', $prescription)
                ->with('success', __('prescriptions.refill_processed', [
                    'count' => $prescription->refill_count,
                    'total' => $prescription->refill_allowed,
                ]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('prescriptions.refill_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * API: Check drug interactions for given product IDs.
     */
    public function checkInteractions(Request $request)
    {
        $productIds = $request->input('product_ids', []);

        if (count($productIds) < 2) {
            return response()->json(['interactions' => []]);
        }

        $interactions = [];

        // Check all combinations
        for ($i = 0; $i < count($productIds); $i++) {
            for ($j = $i + 1; $j < count($productIds); $j++) {
                $interaction = DrugInteraction::checkInteraction($productIds[$i], $productIds[$j]);

                if ($interaction) {
                    $interactions[] = [
                        'severity' => $interaction->severity,
                        'severity_color' => $interaction->severity_color,
                        'drug_a' => $interaction->drug_a_display_name,
                        'drug_b' => $interaction->drug_b_display_name,
                        'description' => $interaction->description,
                        'management' => $interaction->management,
                    ];
                }
            }
        }

        // Sort by severity (most severe first)
        usort($interactions, function ($a, $b) {
            $order = ['contraindicated' => 0, 'major' => 1, 'moderate' => 2, 'minor' => 3];
            return ($order[$a['severity']] ?? 4) - ($order[$b['severity']] ?? 4);
        });

        return response()->json(['interactions' => $interactions]);
    }

    /**
     * API: Search products for prescription.
     */
    public function searchProducts(Request $request)
    {
        $search = $request->input('q', '');

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'sku', 'unit_price', 'stock_qty']);

        return response()->json($products);
    }

    /**
     * Get prescriptions needing refill reminders.
     */
    public function refillReminders()
    {
        $prescriptions = Prescription::needsRefillReminder()
            ->with(['customer', 'items.product'])
            ->get();

        return view('prescriptions.refill-reminders', compact('prescriptions'));
    }
}

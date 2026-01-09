<?php

namespace App\Http\Controllers;

use App\Models\ControlledDrugLog;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ControlledDrugController extends Controller
{
    /**
     * Display a listing of controlled drug logs.
     */
    public function index(Request $request)
    {
        $query = ControlledDrugLog::with(['product', 'customer', 'createdBy', 'approvedBy']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('log_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_id_card', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Drug schedule filter
        if ($schedule = $request->input('drug_schedule')) {
            $query->whereHas('product', function ($q) use ($schedule) {
                $q->where('drug_schedule', $schedule);
            });
        }

        // Transaction type filter
        if ($type = $request->input('transaction_type')) {
            $query->where('transaction_type', $type);
        }

        // Date filter
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->latest()->paginate(15);

        // Stats
        $stats = [
            'total' => ControlledDrugLog::count(),
            'pending' => ControlledDrugLog::pending()->count(),
            'approved_today' => ControlledDrugLog::approved()->whereDate('approved_at', today())->count(),
            'dangerous_count' => ControlledDrugLog::forDrugSchedule('dangerous')->count(),
            'specially_controlled_count' => ControlledDrugLog::forDrugSchedule('specially_controlled')->count(),
        ];

        // Controlled products for filter
        $controlledProducts = Product::controlled()->orderBy('name')->get(['id', 'name', 'drug_schedule']);

        return view('controlled-drugs.index', compact('logs', 'stats', 'controlledProducts'));
    }

    /**
     * Display pending approvals.
     */
    public function pending()
    {
        $logs = ControlledDrugLog::pending()
            ->with(['product', 'customer', 'createdBy'])
            ->latest()
            ->get();

        return view('controlled-drugs.pending', compact('logs'));
    }

    /**
     * Show the form for creating a new log.
     */
    public function create(Request $request)
    {
        $products = Product::controlled()->where('is_active', true)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $transactionTypes = ControlledDrugLog::getTransactionTypes();

        $selectedProduct = null;
        if ($productId = $request->input('product_id')) {
            $selectedProduct = Product::find($productId);
        }

        return view('controlled-drugs.create', compact('products', 'customers', 'transactionTypes', 'selectedProduct'));
    }

    /**
     * Store a newly created log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:sale,dispense,receive,return,dispose,transfer',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_id_card' => 'nullable|string|max:20',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'customer_age' => 'nullable|string|max:10',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'prescription_number' => 'nullable|string|max:50',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_license_no' => 'nullable|string|max:50',
            'hospital_clinic' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
            'indication' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $product = Product::find($validated['product_id']);

        // Check if pharmacist approval is needed
        $status = 'pending';
        if (!$product->needsPharmacistApproval()) {
            $status = 'approved';
        }

        // If user is admin/pharmacist, auto-approve
        if (auth()->user()->isAdmin() || auth()->user()->role === 'pharmacist') {
            $status = 'approved';
        }

        $log = ControlledDrugLog::create([
            ...$validated,
            'status' => $status,
            'created_by' => auth()->id(),
            'approved_by' => $status === 'approved' ? auth()->id() : null,
            'approved_at' => $status === 'approved' ? now() : null,
        ]);

        $message = $status === 'approved'
            ? __('controlled_drugs.logged_and_approved')
            : __('controlled_drugs.logged_pending_approval');

        return redirect()
            ->route('controlled-drugs.show', $log)
            ->with('success', $message);
    }

    /**
     * Display the specified log.
     */
    public function show(ControlledDrugLog $controlledDrug)
    {
        $controlledDrug->load(['product', 'customer', 'prescription', 'createdBy', 'approvedBy', 'productLot']);

        return view('controlled-drugs.show', compact('controlledDrug'));
    }

    /**
     * Approve a controlled drug log.
     */
    public function approve(Request $request, ControlledDrugLog $controlledDrug)
    {
        if ($controlledDrug->status !== 'pending') {
            return back()->with('error', __('controlled_drugs.already_processed'));
        }

        // Only admin or pharmacist can approve
        if (!auth()->user()->isAdmin() && auth()->user()->role !== 'pharmacist') {
            return back()->with('error', __('controlled_drugs.not_authorized'));
        }

        $controlledDrug->approve(auth()->id(), $request->input('notes'));

        return back()->with('success', __('controlled_drugs.approved_successfully'));
    }

    /**
     * Reject a controlled drug log.
     */
    public function reject(Request $request, ControlledDrugLog $controlledDrug)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($controlledDrug->status !== 'pending') {
            return back()->with('error', __('controlled_drugs.already_processed'));
        }

        // Only admin or pharmacist can reject
        if (!auth()->user()->isAdmin() && auth()->user()->role !== 'pharmacist') {
            return back()->with('error', __('controlled_drugs.not_authorized'));
        }

        $controlledDrug->reject(auth()->id(), $validated['rejection_reason']);

        return back()->with('success', __('controlled_drugs.rejected_successfully'));
    }

    /**
     * Generate FDA report (รายงาน อย.)
     */
    public function fdaReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all approved controlled drug logs in date range
        $logs = ControlledDrugLog::approved()
            ->forDateRange($startDate . ' 00:00:00', $endDate . ' 23:59:59')
            ->with(['product', 'customer', 'createdBy', 'approvedBy'])
            ->orderBy('created_at')
            ->get();

        // Group by drug schedule
        $bySchedule = $logs->groupBy(fn($log) => $log->product->drug_schedule);

        // Summary statistics
        $summary = [
            'total_transactions' => $logs->count(),
            'total_quantity' => $logs->sum('quantity'),
            'dangerous' => [
                'count' => $bySchedule->get('dangerous', collect())->count(),
                'quantity' => $bySchedule->get('dangerous', collect())->sum('quantity'),
            ],
            'specially_controlled' => [
                'count' => $bySchedule->get('specially_controlled', collect())->count(),
                'quantity' => $bySchedule->get('specially_controlled', collect())->sum('quantity'),
            ],
            'narcotic' => [
                'count' => $bySchedule->get('narcotic', collect())->count(),
                'quantity' => $bySchedule->get('narcotic', collect())->sum('quantity'),
            ],
            'psychotropic' => [
                'count' => $bySchedule->get('psychotropic', collect())->count(),
                'quantity' => $bySchedule->get('psychotropic', collect())->sum('quantity'),
            ],
        ];

        // Get products with movement
        $productMovement = $logs->groupBy('product_id')->map(function ($items) {
            return [
                'product' => $items->first()->product,
                'total_out' => $items->whereIn('transaction_type', ['sale', 'dispense'])->sum('quantity'),
                'total_in' => $items->whereIn('transaction_type', ['receive', 'return'])->sum('quantity'),
                'disposed' => $items->where('transaction_type', 'dispose')->sum('quantity'),
                'transactions' => $items->count(),
            ];
        });

        return view('controlled-drugs.fda-report', compact(
            'logs',
            'bySchedule',
            'summary',
            'productMovement',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export FDA report to PDF.
     */
    public function exportFdaReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $logs = ControlledDrugLog::approved()
            ->forDateRange($startDate . ' 00:00:00', $endDate . ' 23:59:59')
            ->with(['product', 'customer', 'createdBy', 'approvedBy'])
            ->orderBy('created_at')
            ->get();

        $bySchedule = $logs->groupBy(fn($log) => $log->product->drug_schedule);

        $summary = [
            'total_transactions' => $logs->count(),
            'total_quantity' => $logs->sum('quantity'),
            'dangerous' => [
                'count' => $bySchedule->get('dangerous', collect())->count(),
                'quantity' => $bySchedule->get('dangerous', collect())->sum('quantity'),
            ],
            'specially_controlled' => [
                'count' => $bySchedule->get('specially_controlled', collect())->count(),
                'quantity' => $bySchedule->get('specially_controlled', collect())->sum('quantity'),
            ],
            'narcotic' => [
                'count' => $bySchedule->get('narcotic', collect())->count(),
                'quantity' => $bySchedule->get('narcotic', collect())->sum('quantity'),
            ],
            'psychotropic' => [
                'count' => $bySchedule->get('psychotropic', collect())->count(),
                'quantity' => $bySchedule->get('psychotropic', collect())->sum('quantity'),
            ],
        ];

        return response()->view('controlled-drugs.fda-report-pdf', compact(
            'logs',
            'summary',
            'startDate',
            'endDate'
        ))->header('Content-Type', 'text/html');
    }

    /**
     * Get controlled products list (API)
     */
    public function getProducts()
    {
        $products = Product::controlled()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'drug_schedule', 'stock_qty']);

        return response()->json($products);
    }

    /**
     * Remove the specified log from storage.
     */
    public function destroy(ControlledDrugLog $controlledDrug)
    {
        $controlledDrug->delete();

        return redirect()
            ->route('controlled-drugs.index')
            ->with('success', __('general.deleted_successfully') ?: 'Deleted successfully');
    }
}

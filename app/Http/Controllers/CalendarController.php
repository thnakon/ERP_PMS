<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\ProductLot;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar page.
     */
    public function index(Request $request)
    {
        $view = $request->input('view', 'month');
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);

        // Get date range based on view
        if ($view === 'month') {
            $start = $currentDate->copy()->startOfMonth()->startOfWeek();
            $end = $currentDate->copy()->endOfMonth()->endOfWeek();
        } elseif ($view === 'week') {
            $start = $currentDate->copy()->startOfWeek();
            $end = $currentDate->copy()->endOfWeek();
        } else {
            $start = $currentDate->copy()->startOfDay();
            $end = $currentDate->copy()->endOfDay();
        }

        // Initialize empty collections
        $events = collect();
        $todayEvents = collect();
        $currentShift = null;

        // Try to get calendar events (table might not exist yet)
        try {
            $events = CalendarEvent::dateRange($start, $end)
                ->with(['staff', 'customer', 'product'])
                ->orderBy('start_time')
                ->get();

            $currentShift = CalendarEvent::currentShift()->with('staff')->first();
            $todayEvents = CalendarEvent::today()->with(['staff', 'customer'])->orderBy('start_time')->get();
        } catch (\Exception $e) {
            // Table doesn't exist yet - migration pending
        }

        // Get expiring products (auto-generate events)
        $expiryEvents = $this->getExpiryEvents($start, $end);

        // Merge events
        $allEvents = $events->concat($expiryEvents);

        // Get staff for shift assignment
        $staff = User::where('status', 'active')->get();

        // Get customers for appointments
        $customers = Customer::orderBy('name')->get();

        return view('calendar.index', compact(
            'events',
            'expiryEvents',
            'allEvents',
            'currentDate',
            'view',
            'start',
            'end',
            'staff',
            'customers',
            'currentShift',
            'todayEvents'
        ));
    }

    /**
     * Store a new calendar event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:shift,expiry,appointment,holiday,reminder,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'all_day' => 'boolean',
            'color' => 'nullable|string|max:20',
            'staff_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'product_id' => 'nullable|exists:products,id',
            'status' => 'in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['color'] = $validated['color'] ?? CalendarEvent::$typeColors[$validated['type']];

        $event = CalendarEvent::create($validated);

        return redirect()->route('calendar.index')
            ->with('success', __('calendar.event_created'));
    }

    /**
     * Update a calendar event.
     */
    public function update(Request $request, CalendarEvent $event)
    {
        $validated = $request->validate([
            'type' => 'required|in:shift,expiry,appointment,holiday,reminder,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'all_day' => 'boolean',
            'color' => 'nullable|string|max:20',
            'staff_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:customers,id',
            'product_id' => 'nullable|exists:products,id',
            'status' => 'in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $event->update($validated);

        return redirect()->route('calendar.index')
            ->with('success', __('calendar.event_updated'));
    }

    /**
     * Delete a calendar event.
     */
    public function destroy(CalendarEvent $event)
    {
        $event->delete();

        return redirect()->route('calendar.index')
            ->with('success', __('calendar.event_deleted'));
    }

    /**
     * Get events as JSON for AJAX requests.
     */
    public function getEvents(Request $request)
    {
        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));

        $events = CalendarEvent::dateRange($start, $end)
            ->with(['staff', 'customer', 'product'])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_time->toISOString(),
                    'end' => $event->end_time ? $event->end_time->toISOString() : null,
                    'allDay' => $event->all_day,
                    'color' => $event->display_color,
                    'type' => $event->type,
                    'status' => $event->status,
                    'staff' => $event->staff ? $event->staff->name : null,
                    'customer' => $event->customer ? $event->customer->name : null,
                ];
            });

        // Add expiry events
        $expiryEvents = $this->getExpiryEvents($start, $end)->map(function ($event) {
            return [
                'id' => 'expiry_' . $event['lot_id'],
                'title' => $event['title'],
                'start' => $event['start_time']->toISOString(),
                'end' => null,
                'allDay' => true,
                'color' => '#EF4444',
                'type' => 'expiry',
                'status' => 'auto',
                'product' => $event['product_name'],
            ];
        });

        return response()->json($events->concat($expiryEvents));
    }

    /**
     * Generate expiry events from product lots.
     */
    private function getExpiryEvents(Carbon $start, Carbon $end)
    {
        $lots = ProductLot::with('product')
            ->whereBetween('expiry_date', [$start, $end])
            ->where('quantity', '>', 0)
            ->get();

        return $lots->map(function ($lot) {
            return [
                'lot_id' => $lot->id,
                'title' => $lot->product->name . ' - ' . __('calendar.expires'),
                'start_time' => Carbon::parse($lot->expiry_date)->startOfDay(),
                'product_name' => $lot->product->name,
                'product_id' => $lot->product_id,
                'quantity' => $lot->quantity,
                'type' => 'expiry',
                'color' => '#EF4444',
            ];
        });
    }

    /**
     * Get current shift (pharmacist on duty) - API endpoint.
     */
    public function getCurrentShift()
    {
        $currentShift = CalendarEvent::currentShift()->with('staff')->first();

        if ($currentShift && $currentShift->staff) {
            return response()->json([
                'on_duty' => true,
                'staff_name' => $currentShift->staff->name,
                'staff_id' => $currentShift->staff->id,
                'shift_title' => $currentShift->title,
                'start_time' => $currentShift->start_time->format('H:i'),
                'end_time' => $currentShift->end_time ? $currentShift->end_time->format('H:i') : null,
            ]);
        }

        return response()->json([
            'on_duty' => false,
            'staff_name' => null,
        ]);
    }

    /**
     * Quick add shift.
     */
    public function addShift(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $staff = User::findOrFail($validated['staff_id']);
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        CalendarEvent::create([
            'type' => 'shift',
            'title' => __('calendar.shift') . ': ' . $staff->name,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'staff_id' => $staff->id,
            'color' => CalendarEvent::$typeColors['shift'],
            'status' => 'confirmed',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('calendar.index')
            ->with('success', __('calendar.shift_added'));
    }

    /**
     * Quick add appointment.
     */
    public function addAppointment(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endTime = $validated['end_time']
            ? Carbon::parse($validated['date'] . ' ' . $validated['end_time'])
            : $startTime->copy()->addHour();

        CalendarEvent::create([
            'type' => 'appointment',
            'title' => $validated['title'],
            'description' => $validated['notes'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'customer_id' => $customer->id,
            'color' => CalendarEvent::$typeColors['appointment'],
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('calendar.index')
            ->with('success', __('calendar.appointment_added'));
    }
}

@extends('layouts.app')

@section('title', __('calendar.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('schedule') }}
        </p>
        <span>{{ __('calendar.calendar') }}</span>
    </div>
@endsection

@section('header-actions')
    <button onclick="openAddEventModal()" data-no-loading
        class="px-4 py-2 bg-ios-blue hover:bg-blue-600 text-white font-semibold rounded-xl transition flex items-center gap-2">
        <i class="ph ph-plus"></i>
        {{ __('calendar.add_event') }}
    </button>
@endsection

@section('content')
    <style>
        .cal-weekdays {
            display: grid !important;
            grid-template-columns: repeat(7, 1fr) !important;
            gap: 4px !important;
            margin-bottom: 8px !important;
        }

        .cal-grid {
            display: grid !important;
            grid-template-columns: repeat(7, 1fr) !important;
            gap: 4px !important;
        }

        .cal-day {
            min-height: 100px;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cal-day:hover {
            border-color: #3B82F6;
        }

        .cal-day.today {
            background: #EFF6FF;
            border-color: #3B82F6;
        }

        .cal-day.other-month {
            background: #f9fafb;
            border-color: #e5e7eb;
        }
    </style>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Main Calendar --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Calendar Header --}}
            <div class="card-ios p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('calendar.index', ['view' => $view, 'date' => $currentDate->copy()->subMonth()->format('Y-m-d')]) }}"
                            class="p-2 hover:bg-gray-100 rounded-xl transition">
                            <i class="ph ph-caret-left text-gray-600"></i>
                        </a>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $currentDate->translatedFormat('F Y') }}
                        </h2>
                        <a href="{{ route('calendar.index', ['view' => $view, 'date' => $currentDate->copy()->addMonth()->format('Y-m-d')]) }}"
                            class="p-2 hover:bg-gray-100 rounded-xl transition">
                            <i class="ph ph-caret-right text-gray-600"></i>
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('calendar.index', ['view' => $view, 'date' => now()->format('Y-m-d')]) }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                            {{ __('calendar.today') }}
                        </a>
                        <div class="flex bg-gray-100 rounded-xl p-1">
                            <a href="{{ route('calendar.index', ['view' => 'month', 'date' => $currentDate->format('Y-m-d')]) }}"
                                class="px-3 py-1.5 text-sm font-semibold rounded-lg transition {{ $view === 'month' ? 'bg-white shadow text-ios-blue' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ __('calendar.month') }}
                            </a>
                            <a href="{{ route('calendar.index', ['view' => 'week', 'date' => $currentDate->format('Y-m-d')]) }}"
                                class="px-3 py-1.5 text-sm font-semibold rounded-lg transition {{ $view === 'week' ? 'bg-white shadow text-ios-blue' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ __('calendar.week') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="card-ios p-6">
                {{-- Weekday Headers --}}
                <div class="cal-weekdays">
                    @foreach (['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'] as $day)
                        <div class="text-center py-2 text-xs font-bold text-gray-500 uppercase">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                {{-- Calendar Days --}}
                @php
                    $calendarStart = $currentDate->copy()->startOfMonth()->startOfWeek(Carbon\Carbon::SUNDAY);
                    $calendarEnd = $currentDate->copy()->endOfMonth()->endOfWeek(Carbon\Carbon::SATURDAY);
                    $day = $calendarStart->copy();
                @endphp

                <div class="cal-grid">
                    @while ($day <= $calendarEnd)
                        @php
                            $isToday = $day->isToday();
                            $isCurrentMonth = $day->month === $currentDate->month;
                            $dayEvents = $allEvents->filter(function ($event) use ($day) {
                                if (isset($event['start_time'])) {
                                    return $event['start_time']->isSameDay($day);
                                }
                                return $event->start_time->isSameDay($day);
                            });
                            $dayFormatted = $day->format('Y-m-d');
                        @endphp
                        <div style="min-height: 100px; padding: 8px; border-radius: 12px; border: 1px solid {{ $isToday ? '#3B82F6' : ($isCurrentMonth ? '#f3f4f6' : '#e5e7eb') }}; background: {{ $isToday ? '#EFF6FF' : ($isCurrentMonth ? 'white' : '#f9fafb') }}; cursor: pointer; transition: all 0.2s;"
                            onclick="showDayDetails('{{ $dayFormatted }}', '{{ $day->translatedFormat('l, d F Y') }}')"
                            onmouseover="this.style.borderColor='#3B82F6'"
                            onmouseout="this.style.borderColor='{{ $isToday ? '#3B82F6' : ($isCurrentMonth ? '#f3f4f6' : '#e5e7eb') }}'">
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                <span
                                    style="font-size: 14px; font-weight: 600; color: {{ $isToday ? '#3B82F6' : ($isCurrentMonth ? '#111827' : '#9CA3AF') }};">
                                    {{ $day->day }}
                                </span>
                                @if ($dayEvents->count() > 0)
                                    <span style="font-size: 10px; color: #9CA3AF;">{{ $dayEvents->count() }}</span>
                                @endif
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                @foreach ($dayEvents->take(3) as $event)
                                    @php
                                        $eventColor = isset($event['color']) ? $event['color'] : $event->display_color;
                                        $eventTitle = isset($event['title']) ? $event['title'] : $event->title;
                                    @endphp
                                    <div
                                        style="font-size: 10px; padding: 2px 6px; border-radius: 4px; color: white; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background-color: {{ $eventColor }};">
                                        {{ Str::limit($eventTitle, 12) }}
                                    </div>
                                @endforeach
                                @if ($dayEvents->count() > 3)
                                    <div style="font-size: 10px; color: #9CA3AF; text-align: center;">
                                        +{{ $dayEvents->count() - 3 }} {{ __('more') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @php $day->addDay(); @endphp
                    @endwhile
                </div>

                {{-- Legend --}}
                <div class="flex flex-wrap items-center gap-4 mt-6 pt-4 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase">{{ __('calendar.event_type') }}:</span>
                    @foreach (App\Models\CalendarEvent::$typeColors as $type => $color)
                        <div class="flex items-center gap-1.5">
                            <div
                                style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $color }};">
                            </div>
                            <span class="text-xs text-gray-600">{{ __('calendar.' . $type) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Pharmacist on Duty --}}
            <div class="card-ios p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph-fill ph-user-circle text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('calendar.pharmacist_on_duty') }}</h3>
                        <p class="text-xs text-gray-500">{{ now()->translatedFormat('l, d M Y') }}</p>
                    </div>
                </div>
                @if ($currentShift && $currentShift->staff)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-200">
                        @if ($currentShift->staff->avatar)
                            <img src="{{ asset('storage/' . $currentShift->staff->avatar) }}"
                                class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($currentShift->staff->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900">{{ $currentShift->staff->name }}</p>
                            <p class="text-xs text-green-600">
                                {{ $currentShift->start_time->format('H:i') }} -
                                {{ $currentShift->end_time ? $currentShift->end_time->format('H:i') : '--:--' }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4 text-gray-400">
                        <i class="ph ph-user-circle text-3xl mb-2"></i>
                        <p class="text-sm">{{ __('calendar.no_pharmacist') }}</p>
                    </div>
                @endif
            </div>

            {{-- Today's Schedule --}}
            @php
                // Combine today's calendar events with today's expiry events
                $todayExpiryEvents = $expiryEvents->filter(function ($event) {
                    return $event['start_time']->isToday();
                });
                $todayAllEvents = $todayEvents
                    ->map(function ($event) {
                        return [
                            'title' => $event->title,
                            'type' => $event->type,
                            'color' => $event->display_color,
                            'time' => $event->start_time->format('H:i'),
                            'end_time' => $event->end_time ? $event->end_time->format('H:i') : null,
                            'is_expiry' => false,
                        ];
                    })
                    ->concat(
                        $todayExpiryEvents->map(function ($event) {
                            return [
                                'title' => $event['title'],
                                'type' => 'expiry',
                                'color' => $event['color'],
                                'time' => '00:00',
                                'end_time' => null,
                                'is_expiry' => true,
                            ];
                        }),
                    );
            @endphp
            <div class="card-ios p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph-fill ph-calendar-check text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('calendar.todays_schedule') }}</h3>
                        <p class="text-xs text-gray-500">{{ $todayAllEvents->count() }} {{ __('events') }}</p>
                    </div>
                </div>
                <div class="space-y-2 max-h-[300px] overflow-y-auto">
                    @forelse($todayAllEvents as $event)
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition">
                            <div class="w-1 h-10 rounded-full" style="background-color: {{ $event['color'] }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate text-sm">{{ $event['title'] }}</p>
                                <p class="text-xs text-gray-500">
                                    @if ($event['is_expiry'])
                                        <i class="ph ph-warning text-red-500"></i> {{ __('calendar.expiry_alert') }}
                                    @else
                                        {{ $event['time'] }}
                                        @if ($event['end_time'])
                                            - {{ $event['end_time'] }}
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full"
                                style="background-color: {{ $event['color'] }}20; color: {{ $event['color'] }}">
                                {{ __('calendar.' . $event['type']) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-400">
                            <i class="ph ph-calendar-blank text-3xl mb-2"></i>
                            <p class="text-sm">{{ __('calendar.no_events_today') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Add Shift --}}
            <div class="card-ios p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="ph-fill ph-clock text-amber-600 text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">{{ __('calendar.add_shift') }}</h3>
                </div>
                <form action="{{ route('calendar.add-shift') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <select name="staff_id" class="input-ios text-sm" required>
                            <option value="">{{ __('calendar.select_staff') }}</option>
                            @foreach ($staff as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="date" name="date" class="input-ios text-sm" value="{{ now()->format('Y-m-d') }}"
                            required>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="time" name="start_time" class="input-ios text-sm" value="08:00" required>
                        <input type="time" name="end_time" class="input-ios text-sm" value="17:00" required>
                    </div>
                    <button type="submit"
                        class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition">
                        {{ __('calendar.add_shift') }}
                    </button>
                </form>
            </div>

            {{-- Quick Add Appointment --}}
            <div class="card-ios p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="ph-fill ph-calendar-plus text-green-600 text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">{{ __('calendar.add_appointment') }}</h3>
                </div>
                <form action="{{ route('calendar.add-appointment') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <select name="customer_id" class="input-ios text-sm" required>
                            <option value="">{{ __('calendar.select_customer') }}</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="text" name="title" class="input-ios text-sm"
                            placeholder="{{ __('calendar.event_title') }}" required>
                    </div>
                    <div>
                        <input type="date" name="date" class="input-ios text-sm"
                            value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="time" name="start_time" class="input-ios text-sm" value="10:00" required>
                        <input type="time" name="end_time" class="input-ios text-sm" value="11:00">
                    </div>
                    <button type="submit"
                        class="w-full py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition">
                        {{ __('calendar.add_appointment') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Event Modal --}}
    <div id="addEventModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden" onclick="closeAddEventModal()">
    </div>
    <div id="addEventModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem;">
        <div class="modal-header">
            <h2 class="modal-title">{{ __('calendar.add_event') }}</h2>
            <button onclick="closeAddEventModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <form action="{{ route('calendar.store') }}" method="POST">
            @csrf
            <div class="modal-content space-y-4">
                <div class="form-group">
                    <label class="form-label">{{ __('calendar.event_type') }}</label>
                    <select name="type" class="form-input" required>
                        <option value="shift">{{ __('calendar.shift') }}</option>
                        <option value="appointment">{{ __('calendar.appointment') }}</option>
                        <option value="holiday">{{ __('calendar.holiday') }}</option>
                        <option value="reminder">{{ __('calendar.reminder') }}</option>
                        <option value="other">{{ __('calendar.other') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('calendar.event_title') }} <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" class="form-input" required
                        placeholder="{{ __('calendar.event_title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('calendar.event_description') }}</label>
                    <textarea name="description" class="form-input" rows="2"
                        placeholder="{{ __('calendar.event_description') }}"></textarea>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">{{ __('calendar.start_time') }} <span
                                class="text-red-500">*</span></label>
                        <input type="datetime-local" name="start_time" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('calendar.end_time') }}</label>
                        <input type="datetime-local" name="end_time" class="form-input">
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">{{ __('calendar.staff') }}</label>
                        <select name="staff_id" class="form-input">
                            <option value="">{{ __('calendar.select_staff') }}</option>
                            @foreach ($staff as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('calendar.customer') }}</label>
                        <select name="customer_id" class="form-input">
                            <option value="">{{ __('calendar.select_customer') }}</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAddEventModal()"
                    class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                    {{ __('calendar.cancel') }}
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-ios-blue hover:brightness-110 text-white text-sm font-medium rounded-xl transition active-scale flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-sm"></i>
                    {{ __('calendar.save_event') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Day Details Modal --}}
    <div id="dayDetailsModal-backdrop" class="modal-backdrop modal-backdrop-hidden hidden"
        onclick="closeDayDetailsModal()"></div>
    <div id="dayDetailsModal-panel" class="modal-panel modal-panel-hidden" style="max-width: 32rem;">
        <div class="modal-header">
            <div>
                <h2 id="dayDetailsTitle" class="modal-title" style="margin-bottom: 0;">
                    {{ __('calendar.todays_schedule') }}</h2>
                <p id="dayDetailsDate" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeDayDetailsModal()" class="modal-close-btn">
                <i class="ph-bold ph-x text-gray-500"></i>
            </button>
        </div>
        <div class="modal-content">
            <div id="dayEventsList" class="space-y-3" style="max-height: 400px; overflow-y: auto;">
                {{-- Events will be loaded here via JavaScript --}}
            </div>
            <div id="noEventsMessage" class="hidden text-center py-8">
                <i class="ph ph-calendar-blank text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-400">{{ __('calendar.no_events_today') }}</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeDayDetailsModal()"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                {{ __('calendar.close') }}
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openAddEventModal() {
            const backdrop = document.getElementById('addEventModal-backdrop');
            const panel = document.getElementById('addEventModal-panel');
            backdrop.classList.remove('hidden', 'modal-backdrop-hidden');
            panel.classList.remove('modal-panel-hidden');
        }

        function closeAddEventModal() {
            const backdrop = document.getElementById('addEventModal-backdrop');
            const panel = document.getElementById('addEventModal-panel');
            backdrop.classList.add('modal-backdrop-hidden');
            panel.classList.add('modal-panel-hidden');
            setTimeout(() => backdrop.classList.add('hidden'), 200);
        }

        // Events data from server
        @php
            $eventsForJs = $allEvents
                ->map(function ($event) {
                    if (is_array($event)) {
                        return [
                            'date' => $event['start_time']->format('Y-m-d'),
                            'title' => $event['title'],
                            'type' => $event['type'],
                            'color' => $event['color'],
                            'time' => $event['start_time']->format('H:i'),
                            'end_time' => null,
                            'description' => null,
                            'status' => null,
                            'staff' => null,
                            'customer' => null,
                            'product' => $event['product_name'] ?? null,
                        ];
                    }
                    return [
                        'date' => $event->start_time->format('Y-m-d'),
                        'title' => $event->title,
                        'type' => $event->type,
                        'color' => $event->display_color,
                        'time' => $event->start_time->format('H:i'),
                        'end_time' => $event->end_time ? $event->end_time->format('H:i') : null,
                        'description' => $event->description,
                        'status' => $event->status,
                        'staff' => $event->staff ? $event->staff->name : null,
                        'customer' => $event->customer ? $event->customer->name : null,
                        'product' => null,
                    ];
                })
                ->values()
                ->toArray();
        @endphp
        const allEventsData = @json($eventsForJs);

        const typeLabels = {
            'shift': '{{ __('calendar.shift') }}',
            'expiry': '{{ __('calendar.expiry') }}',
            'appointment': '{{ __('calendar.appointment') }}',
            'holiday': '{{ __('calendar.holiday') }}',
            'reminder': '{{ __('calendar.reminder') }}',
            'other': '{{ __('calendar.other') }}'
        };

        function showDayDetails(dateStr, dateDisplay) {
            const backdrop = document.getElementById('dayDetailsModal-backdrop');
            const panel = document.getElementById('dayDetailsModal-panel');
            const titleEl = document.getElementById('dayDetailsTitle');
            const dateEl = document.getElementById('dayDetailsDate');
            const listEl = document.getElementById('dayEventsList');
            const noEventsEl = document.getElementById('noEventsMessage');

            titleEl.textContent = '{{ __('calendar.todays_schedule') }}';
            dateEl.textContent = dateDisplay;

            // Filter events for this day
            const dayEvents = allEventsData.filter(e => e.date === dateStr);

            if (dayEvents.length === 0) {
                listEl.classList.add('hidden');
                noEventsEl.classList.remove('hidden');
            } else {
                listEl.classList.remove('hidden');
                noEventsEl.classList.add('hidden');

                listEl.innerHTML = dayEvents.map(event => `
                    <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; border-radius: 12px; background: ${event.color}10; border-left: 4px solid ${event.color};">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: ${event.color}20; display: flex; align-items: center; justify-content: center;">
                            <i class="ph-fill ${getTypeIcon(event.type)}" style="color: ${event.color}; font-size: 18px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <span style="font-weight: 600; color: #111827;">${event.title}</span>
                                <span style="font-size: 10px; padding: 2px 8px; border-radius: 20px; background: ${event.color}20; color: ${event.color}; font-weight: 600;">
                                    ${typeLabels[event.type] || event.type}
                                </span>
                            </div>
                            <div style="color: #6B7280; font-size: 13px;">
                                <i class="ph ph-clock" style="margin-right: 4px;"></i>
                                ${event.time}${event.end_time ? ' - ' + event.end_time : ''}
                            </div>
                            ${event.description ? `<p style="color: #9CA3AF; font-size: 12px; margin-top: 4px;">${event.description}</p>` : ''}
                            ${event.staff ? `<p style="color: #6B7280; font-size: 12px; margin-top: 4px;"><i class="ph ph-user" style="margin-right: 4px;"></i>${event.staff}</p>` : ''}
                            ${event.customer ? `<p style="color: #6B7280; font-size: 12px; margin-top: 4px;"><i class="ph ph-user-circle" style="margin-right: 4px;"></i>${event.customer}</p>` : ''}
                        </div>
                    </div>
                `).join('');
            }

            backdrop.classList.remove('hidden', 'modal-backdrop-hidden');
            panel.classList.remove('modal-panel-hidden');
        }

        function getTypeIcon(type) {
            const icons = {
                'shift': 'ph-clock',
                'expiry': 'ph-warning',
                'appointment': 'ph-calendar-check',
                'holiday': 'ph-sun',
                'reminder': 'ph-bell',
                'other': 'ph-calendar'
            };
            return icons[type] || 'ph-calendar';
        }

        function closeDayDetailsModal() {
            const backdrop = document.getElementById('dayDetailsModal-backdrop');
            const panel = document.getElementById('dayDetailsModal-panel');
            backdrop.classList.add('modal-backdrop-hidden');
            panel.classList.add('modal-panel-hidden');
            setTimeout(() => backdrop.classList.add('hidden'), 200);
        }
    </script>
@endpush

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CalendarController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the calendar and list of upcoming events.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Event::class);

        $category = $request->query('category');
        $viewMode = $request->query('view', 'month'); // 'month', 'week', 'agenda'

        $query = Event::with('creator');

        // Children can only see family-shared events
        if ($user->isChild()) {
            $query->where('is_family_event', true);
        }

        if ($category && array_key_exists($category, Event::CATEGORIES)) {
            $query->where('category', $category);
        }

        $upcomingEvents = (clone $query)
            ->where('start_at', '>=', now()->startOfDay())
            ->orderBy('start_at')
            ->take(10)
            ->get();

        $allEvents = $query->orderBy('start_at')->get();

        return view('calendar.index', [
            'upcomingEvents' => $upcomingEvents,
            'allEvents' => $allEvents,
            'categories' => Event::CATEGORIES,
            'selectedCategory' => $category,
            'viewMode' => $viewMode,
            'isPartner' => $user->isPartner(),
        ]);
    }

    /**
     * JSON feed for calendar components and monthly navigation.
     */
    public function feed(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->authorize('viewAny', Event::class);

        $query = Event::query();

        if ($user->isChild()) {
            $query->where('is_family_event', true);
        }

        if ($request->filled('start')) {
            $query->where('start_at', '>=', Carbon::parse($request->input('start')));
        }

        if ($request->filled('end')) {
            $query->where('start_at', '<=', Carbon::parse($request->input('end')));
        }

        $events = $query->get()->map(function (Event $event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start_at->toIso8601String(),
                'end' => $event->end_at?->toIso8601String() ?? $event->start_at->toIso8601String(),
                'color' => $event->color,
                'category' => $event->category,
                'category_label' => $event->category_label,
                'category_emoji' => $event->category_emoji,
                'is_all_day' => $event->is_all_day,
                'is_family_event' => $event->is_family_event,
            ];
        });

        return response()->json($events);
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', Rule::in(array_keys(Event::CATEGORIES))],
            'color' => ['nullable', 'string', 'max:30'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'is_all_day' => ['nullable', 'boolean'],
            'is_family_event' => ['nullable', 'boolean'],
        ]);

        $defaultColor = Event::CATEGORIES[$validated['category']]['default_color'] ?? '#f43f5e';

        Event::create([
            'couple_id' => $user->couple_id,
            'created_by_user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'color' => $validated['color'] ?: $defaultColor,
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'] ?? null,
            'is_all_day' => (bool) ($validated['is_all_day'] ?? false),
            // If created by child, it is automatically a family event
            'is_family_event' => $user->isChild() ? true : (bool) ($validated['is_family_event'] ?? true),
        ]);

        return redirect()->route('events.index')->with(
            'success',
            'Agenda baru berhasil ditambahkan ke kalender bersama!'
        );
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', Rule::in(array_keys(Event::CATEGORIES))],
            'color' => ['nullable', 'string', 'max:30'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'is_all_day' => ['nullable', 'boolean'],
            'is_family_event' => ['nullable', 'boolean'],
        ]);

        $defaultColor = Event::CATEGORIES[$validated['category']]['default_color'] ?? '#f43f5e';

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'color' => ($validated['color'] ?? null) ?: ($event->color ?? $defaultColor),
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'] ?? null,
            'is_all_day' => (bool) ($validated['is_all_day'] ?? false),
            'is_family_event' => (bool) ($validated['is_family_event'] ?? $event->is_family_event),
        ]);

        return redirect()->route('events.index')->with(
            'success',
            'Agenda berhasil diperbarui.'
        );
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')->with(
            'success',
            'Agenda telah dihapus dari kalender.'
        );
    }
}

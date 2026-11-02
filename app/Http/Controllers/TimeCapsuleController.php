<?php

namespace App\Http\Controllers;

use App\Models\TimeCapsule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimeCapsuleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of time capsules / sealed letters.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', TimeCapsule::class);

        $user = $request->user();

        // Locked capsules hide their content at query level
        $capsules = TimeCapsule::with(['sender', 'recipient'])
            ->latest('unlock_at')
            ->paginate(12);

        $partners = $user->couple->partners()->where('id', '!=', $user->id)->get();

        return view('time_capsules.index', [
            'capsules' => $capsules,
            'partners' => $partners,
            'currentUser' => $user,
        ]);
    }

    /**
     * Store a newly created sealed time capsule.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', TimeCapsule::class);

        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'unlock_at' => ['required', 'date', 'after:now'],
            'recipient_id' => ['nullable', 'exists:users,id'],
        ]);

        $capsule = TimeCapsule::create([
            'couple_id' => $user->couple_id,
            'sender_id' => $user->id,
            'recipient_id' => $validated['recipient_id'] ?? null,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'unlock_at' => $validated['unlock_at'],
            'is_notified' => false,
        ]);

        return redirect()->route('time-capsules.index')->with(
            'success',
            "Surat masa depan '{$capsule->title}' berhasil disegel! Surat terkunci hingga {$capsule->unlock_at->format('d M Y H:i')} dan tidak dapat dibuka sebelum waktunya."
        );
    }

    /**
     * Display the specified sealed letter or unlocked time capsule.
     */
    public function show(TimeCapsule $timeCapsule): View
    {
        $this->authorize('view', $timeCapsule);

        $isUnlocked = $timeCapsule->isUnlocked();

        // If unlocked and recipient views for the first time, record opened_at
        if ($isUnlocked && !$timeCapsule->opened_at) {
            $timeCapsule->update(['opened_at' => now()]);
        }

        return view('time_capsules.show', [
            'capsule' => $timeCapsule,
            'isUnlocked' => $isUnlocked,
            // CRITICAL: Content is STRICTLY NULL if time has not arrived
            'safeContent' => $isUnlocked ? $timeCapsule->content : null,
        ]);
    }

    /**
     * Remove the specified time capsule (sender only).
     */
    public function destroy(TimeCapsule $timeCapsule): RedirectResponse
    {
        $this->authorize('delete', $timeCapsule);

        $timeCapsule->delete();

        return redirect()->route('time-capsules.index')->with(
            'success',
            'Surat masa depan berhasil dihapus.'
        );
    }
}

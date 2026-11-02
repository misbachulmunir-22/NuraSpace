<?php

namespace App\Http\Controllers;

use App\Models\Countdown;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountdownController extends Controller
{
    /**
     * Display a listing of shared countdowns.
     */
    public function index(Request $request): View
    {
        $countdowns = Countdown::with('creator')
            ->orderByDesc('is_pinned')
            ->orderBy('target_date')
            ->get();

        return view('countdowns.index', [
            'countdowns' => $countdowns,
            'isPartner' => $request->user()->isPartner(),
        ]);
    }

    /**
     * Store a newly created countdown.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'target_date' => ['required', 'date'],
            'icon_emoji' => ['nullable', 'string', 'max:10'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        Countdown::create([
            'couple_id' => $user->couple_id,
            'created_by_user_id' => $user->id,
            'title' => $validated['title'],
            'target_date' => $validated['target_date'],
            'icon_emoji' => $validated['icon_emoji'] ?: '❤️',
            'is_pinned' => (bool) ($validated['is_pinned'] ?? false),
        ]);

        return redirect()->route('countdowns.index')->with(
            'success',
            'Countdown baru berhasil dibuat!'
        );
    }

    /**
     * Toggle pinned status of countdown.
     */
    public function togglePin(Countdown $countdown): RedirectResponse
    {
        $countdown->update([
            'is_pinned' => !$countdown->is_pinned,
        ]);

        $status = $countdown->is_pinned ? 'disematkan ke dashboard' : 'dilepas dari dashboard';

        return redirect()->route('countdowns.index')->with(
            'success',
            "Countdown {$status}."
        );
    }

    /**
     * Remove the specified countdown.
     */
    public function destroy(Countdown $countdown): RedirectResponse
    {
        $countdown->delete();

        return redirect()->route('countdowns.index')->with(
            'success',
            'Countdown telah dihapus.'
        );
    }
}

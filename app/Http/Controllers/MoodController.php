<?php

namespace App\Http\Controllers;

use App\Events\MoodUpdatedEvent;
use App\Models\Mood;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MoodController extends Controller
{
    /**
     * Store or update today's mood for the authenticated partner.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner()) {
            abort(403, 'Hanya partner yang dapat memperbarui status mood.');
        }

        $validated = $request->validate([
            'mood_type' => ['required', 'string', Rule::in(array_keys(Mood::MOOD_TYPES))],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $today = now()->format('Y-m-d');

        $mood = Mood::where('couple_id', $user->couple_id)
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $today)
            ->first();

        if ($mood) {
            $mood->update([
                'mood_type' => $validated['mood_type'],
                'note' => $validated['note'] ?? null,
            ]);
        } else {
            $mood = Mood::create([
                'couple_id' => $user->couple_id,
                'user_id' => $user->id,
                'logged_date' => $today,
                'mood_type' => $validated['mood_type'],
                'note' => $validated['note'] ?? null,
            ]);
        }

        $mood->load('user');

        // Broadcast to partner in real-time
        broadcast(new MoodUpdatedEvent($mood))->toOthers();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status mood berhasil diperbarui.',
                'data' => [
                    'id' => $mood->id,
                    'mood_type' => $mood->mood_type,
                    'label' => $mood->label,
                    'emoji' => $mood->emoji,
                    'color' => $mood->color,
                    'note' => $mood->note,
                    'logged_date' => $mood->logged_date->format('Y-m-d'),
                ],
            ]);
        }

        return back()->with('success', "Status mood hari ini ({$mood->emoji} {$mood->label}) berhasil disimpan!");
    }
}

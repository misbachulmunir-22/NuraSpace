<?php

namespace App\Http\Controllers;

use App\Events\NudgeSentEvent;
use App\Models\Nudge;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NudgeController extends Controller
{
    /**
     * Send a real-time virtual nudge (pelukan, ciuman, kangen).
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nudge_type' => ['required', 'string', Rule::in(array_keys(Nudge::TYPES))],
            'recipient_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $recipient = null;

        if (!empty($validated['recipient_id'])) {
            $recipient = User::where('couple_id', $user->couple_id)
                ->where('id', $validated['recipient_id'])
                ->where('id', '!=', $user->id)
                ->first();

            if (!$recipient) {
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Penerima sentuhan tidak valid dalam keluarga ini.'], 422);
                }
                return back()->withErrors(['recipient_id' => 'Penerima sentuhan tidak valid dalam keluarga ini.']);
            }
        } else {
            // Default recipient for partner is the other partner
            if ($user->isPartner()) {
                $recipient = $user->couple->partners()
                    ->where('id', '!=', $user->id)
                    ->first();
            } else {
                // If child, send to first partner (parent)
                $recipient = $user->couple->partners()->first();
            }
        }

        if (!$recipient) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Tidak ditemukan pasangan/penerima untuk dikirimi sentuhan.'], 422);
            }
            return back()->with('error', 'Tidak ditemukan pasangan/penerima untuk dikirimi sentuhan.');
        }

        $nudge = Nudge::create([
            'couple_id' => $user->couple_id,
            'sender_id' => $user->id,
            'recipient_id' => $recipient->id,
            'nudge_type' => $validated['nudge_type'],
        ]);

        // Broadcast real-time event
        event(new NudgeSentEvent($nudge));

        $successMsg = "Berhasil mengirim {$nudge->label} {$nudge->emoji} kepada {$recipient->name}!";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'nudge' => [
                    'id' => $nudge->id,
                    'nudge_type' => $nudge->nudge_type,
                    'label' => $nudge->label,
                    'verb' => $nudge->verb,
                    'emoji' => $nudge->emoji,
                    'recipient_name' => $recipient->name,
                ],
            ]);
        }

        return back()->with('status', $successMsg);
    }

    /**
     * Get recent unread nudges for the authenticated user (polling fallback).
     */
    public function recent(Request $request): JsonResponse
    {
        $user = $request->user();

        $nudges = Nudge::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->with('sender')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Nudge $n) => [
                'id' => $n->id,
                'nudge_type' => $n->nudge_type,
                'label' => $n->label,
                'verb' => $n->verb,
                'emoji' => $n->emoji,
                'animation' => $n->animation,
                'sender_name' => $n->sender->name,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'count' => $nudges->count(),
            'nudges' => $nudges,
        ]);
    }

    /**
     * Mark a nudge as read / dismissed.
     */
    public function markRead(Request $request, Nudge $nudge): JsonResponse
    {
        if ($nudge->recipient_id !== $request->user()->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $nudge->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
        ]);
    }
}

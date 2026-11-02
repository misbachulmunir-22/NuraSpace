<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JournalController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of journals (shared and private for current user).
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Journal::class);

        $user = $request->user();
        $tab = $request->query('tab', 'all'); // 'all', 'shared', 'private'

        $query = Journal::with('user')
            ->where(function ($q) use ($user) {
                // Must be either shared OR private belonging to current user
                $q->where('visibility', 'shared')
                  ->orWhere(function ($sub) use ($user) {
                      $sub->where('visibility', 'private')
                          ->where('user_id', $user->id);
                  });
            });

        if ($tab === 'shared') {
            $query->where('visibility', 'shared');
        } elseif ($tab === 'private') {
            $query->where('visibility', 'private')->where('user_id', $user->id);
        }

        $journals = $query->latest('entry_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('journals.index', [
            'journals' => $journals,
            'currentTab' => $tab,
        ]);
    }

    /**
     * Store a newly created journal entry in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Journal::class);

        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'visibility' => ['required', Rule::in(['private', 'shared'])],
            'mood_tag' => ['nullable', 'string', 'max:50'],
            'entry_date' => ['nullable', 'date'],
        ]);

        $journal = Journal::create([
            'couple_id' => $user->couple_id,
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
            'mood_tag' => $validated['mood_tag'] ?? null,
            'entry_date' => $validated['entry_date'] ?? now()->toDateString(),
        ]);

        $visibilityLabel = $journal->isPrivate() ? 'Jurnal pribadi (hanya Anda yang dapat membaca)' : 'Jurnal bersama (dapat dibaca pasangan)';

        return redirect()->route('journals.index')->with(
            'success',
            "Catatan curhatan berhasil disimpan sebagai {$visibilityLabel}."
        );
    }

    /**
     * Display the specified journal.
     */
    public function show(Journal $journal): View
    {
        $this->authorize('view', $journal);

        return view('journals.show', [
            'journal' => $journal,
        ]);
    }

    /**
     * Show the form for editing the specified journal.
     */
    public function edit(Journal $journal): View
    {
        $this->authorize('update', $journal);

        return view('journals.edit', [
            'journal' => $journal,
        ]);
    }

    /**
     * Update the specified journal in storage.
     */
    public function update(Request $request, Journal $journal): RedirectResponse
    {
        $this->authorize('update', $journal);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'visibility' => ['required', Rule::in(['private', 'shared'])],
            'mood_tag' => ['nullable', 'string', 'max:50'],
            'entry_date' => ['required', 'date'],
        ]);

        $journal->update($validated);

        return redirect()->route('journals.show', $journal)->with(
            'success',
            'Catatan curhatan berhasil diperbarui.'
        );
    }

    /**
     * Remove the specified journal from storage.
     */
    public function destroy(Journal $journal): RedirectResponse
    {
        $this->authorize('delete', $journal);

        $journal->delete();

        return redirect()->route('journals.index')->with(
            'success',
            'Catatan curhatan telah dihapus.'
        );
    }
}

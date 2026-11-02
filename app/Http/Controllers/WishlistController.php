<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the Couple Wishlist board.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Wishlist::where('couple_id', $user->couple_id)
            ->with('user')
            ->orderBy('target_date', 'asc')
            ->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $wishlists = $query->paginate(12)->withQueryString();

        // Summary statistics
        $all = Wishlist::where('couple_id', $user->couple_id)->get();
        $totalItems = $all->count();
        $achievedCount = $all->where('status', 'achieved')->count();
        $inProgressCount = $all->where('status', 'in_progress')->count();
        $totalEstimatedCost = $all->where('status', '!=', 'cancelled')->sum('estimated_cost');

        return view('wishlists.index', [
            'wishlists' => $wishlists,
            'totalItems' => $totalItems,
            'achievedCount' => $achievedCount,
            'inProgressCount' => $inProgressCount,
            'totalEstimatedCost' => $totalEstimatedCost,
            'selectedCategory' => $request->category,
            'selectedStatus' => $request->status,
        ]);
    }

    /**
     * Store a new wishlist dream.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', 'string', Rule::in(array_keys(Wishlist::CATEGORIES))],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store("wishlists/{$user->couple_id}", 'local');
        }

        Wishlist::create([
            'couple_id' => $user->couple_id,
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'estimated_cost' => !empty($validated['estimated_cost']) ? (int) $validated['estimated_cost'] : null,
            'target_date' => $validated['target_date'] ?? null,
            'status' => 'pending',
            'image_path' => $imagePath,
        ]);

        return redirect()->route('wishlists.index')->with('status', 'Impian baru berhasil ditambahkan ke Wishlist Cinta! 🌟💖');
    }

    /**
     * Update an existing wishlist dream.
     */
    public function update(Request $request, Wishlist $wishlist): RedirectResponse
    {
        Gate::authorize('update', $wishlist);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', 'string', Rule::in(array_keys(Wishlist::CATEGORIES))],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(array_keys(Wishlist::STATUSES))],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $imagePath = $wishlist->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('local')->exists($imagePath)) {
                Storage::disk('local')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store("wishlists/{$wishlist->couple_id}", 'local');
        }

        $achievedAt = $wishlist->achieved_at;
        if ($validated['status'] === 'achieved' && $wishlist->status !== 'achieved') {
            $achievedAt = now();
        } elseif ($validated['status'] !== 'achieved') {
            $achievedAt = null;
        }

        $wishlist->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'estimated_cost' => !empty($validated['estimated_cost']) ? (int) $validated['estimated_cost'] : null,
            'target_date' => $validated['target_date'] ?? null,
            'status' => $validated['status'],
            'achieved_at' => $achievedAt,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('wishlists.index')->with('status', 'Wishlist berhasil diperbarui!');
    }

    /**
     * Quick status update (e.g. mark as achieved).
     */
    public function toggleStatus(Request $request, Wishlist $wishlist): RedirectResponse
    {
        Gate::authorize('update', $wishlist);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys(Wishlist::STATUSES))],
        ]);

        $newStatus = $validated['status'];
        $achievedAt = ($newStatus === 'achieved') ? now() : null;

        $wishlist->update([
            'status' => $newStatus,
            'achieved_at' => $achievedAt,
        ]);

        $msg = $newStatus === 'achieved'
            ? 'Selamat! Satu impian cinta kalian telah tercapai! 🎉🥂'
            : 'Status impian berhasil diubah.';

        return redirect()->route('wishlists.index')->with('status', $msg);
    }

    /**
     * Delete a wishlist item.
     */
    public function destroy(Wishlist $wishlist): RedirectResponse
    {
        Gate::authorize('delete', $wishlist);

        $wishlist->delete();

        return redirect()->route('wishlists.index')->with('status', 'Impian telah dihapus dari wishlist.');
    }
}

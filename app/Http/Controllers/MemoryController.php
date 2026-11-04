<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\MemoryMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MemoryController extends Controller
{
    /**
     * Display Memory Lane (Timeline & Gallery).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Memory::where('couple_id', $user->couple_id)
            ->with(['media', 'user'])
            ->orderByDesc('memory_date')
            ->orderByDesc('created_at');

        // Filter: Milestones only
        if ($request->boolean('milestone')) {
            $query->where('is_milestone', true);
        }

        // Filter by Year
        if ($request->filled('year')) {
            $query->whereYear('memory_date', $request->year);
        }

        $memories = $query->paginate(12)->withQueryString();

        // Get list of available years for quick filter (database-agnostic across SQLite, MySQL, and PostgreSQL)
        $availableYears = Memory::where('couple_id', $user->couple_id)
            ->whereNotNull('memory_date')
            ->pluck('memory_date')
            ->map(function ($date) {
                return $date instanceof \Carbon\CarbonInterface 
                    ? $date->format('Y') 
                    : \Carbon\Carbon::parse($date)->format('Y');
            })
            ->unique()
            ->sortDesc()
            ->values();


        $totalMilestones = Memory::where('couple_id', $user->couple_id)
            ->where('is_milestone', true)
            ->count();

        return view('memories.index', [
            'memories' => $memories,
            'availableYears' => $availableYears,
            'totalMilestones' => $totalMilestones,
            'selectedYear' => $request->year,
            'isMilestoneFilter' => $request->boolean('milestone'),
        ]);
    }

    /**
     * Store a new memory with media uploads.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'memory_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_milestone' => ['nullable', 'boolean'],
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:51200'], // max 50MB
        ]);

        $memory = Memory::create([
            'couple_id' => $user->couple_id,
            'user_id' => $user->id,
            'title' => $validated['title'],
            'memory_date' => $validated['memory_date'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_milestone' => !empty($validated['is_milestone']),
        ]);

        // Process media uploads into private storage disk
        if ($request->hasFile('media')) {
            $order = 0;
            foreach ($request->file('media') as $file) {
                $mime = $file->getMimeType();
                $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';

                $folder = "memories/{$user->couple_id}/{$memory->id}";
                $path = $file->store($folder, 'local');

                MemoryMedia::create([
                    'memory_id' => $memory->id,
                    'file_path' => $path,
                    'media_type' => $mediaType,
                    'order_index' => $order++,
                ]);
            }
        }

        return redirect()->route('memories.index')->with('status', 'Kenangan indah berhasil diabadikan ke dalam Galeri Cinta! 📸✨');
    }

    /**
     * Display a single memory with full story and media gallery.
     */
    public function show(Memory $memory): View
    {
        Gate::authorize('view', $memory);

        $memory->load(['media', 'user']);

        return view('memories.show', [
            'memory' => $memory,
        ]);
    }

    /**
     * Delete a memory and all its associated media.
     */
    public function destroy(Memory $memory): RedirectResponse
    {
        Gate::authorize('delete', $memory);

        $memory->delete();

        return redirect()->route('memories.index')->with('status', 'Kenangan telah dihapus dari galeri.');
    }
}

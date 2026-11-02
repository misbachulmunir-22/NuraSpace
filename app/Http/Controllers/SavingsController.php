<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use App\Models\SavingsTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SavingsController extends Controller
{
    /**
     * Display the Family & Couple Savings Dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $goals = SavingsGoal::where('couple_id', $user->couple_id)
            ->with(['transactions' => function ($q) {
                $q->latest()->take(5);
            }, 'user'])
            ->orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END")
            ->orderBy('target_date', 'asc')
            ->get();

        $totalTarget = $goals->where('status', 'active')->sum('target_amount');
        $totalCollected = $goals->sum('current_amount');
        $overallProgress = $totalTarget > 0 ? round(min(100, ($totalCollected / $totalTarget) * 100), 1) : 0;
        $achievedCount = $goals->where('status', 'achieved')->count();

        return view('savings.index', [
            'goals' => $goals,
            'totalTarget' => $totalTarget,
            'totalCollected' => $totalCollected,
            'overallProgress' => $overallProgress,
            'achievedCount' => $achievedCount,
        ]);
    }

    /**
     * Create a new savings target goal.
     */
    public function storeGoal(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:1000'],
            'target_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', Rule::in(SavingsGoal::ICONS)],
            'color' => ['nullable', 'string', Rule::in(array_keys(SavingsGoal::COLORS))],
        ]);

        SavingsGoal::create([
            'couple_id' => $user->couple_id,
            'user_id' => $user->id,
            'name' => $validated['name'],
            'target_amount' => (int) $validated['target_amount'],
            'target_date' => $validated['target_date'] ?? null,
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? '💰',
            'color' => $validated['color'] ?? 'pink',
            'status' => 'active',
        ]);

        return redirect()->route('savings.index')->with('status', 'Target tabungan baru berhasil dibuat! Mari bersemangat mencapainya bersama! 🎯💰');
    }

    /**
     * Display a specific savings goal with detailed ledger and deposit form.
     */
    public function show(SavingsGoal $savingsGoal): View
    {
        Gate::authorize('view', $savingsGoal);

        $savingsGoal->load(['transactions.user', 'user']);

        return view('savings.show', [
            'goal' => $savingsGoal,
            'transactions' => $savingsGoal->transactions()->paginate(15),
        ]);
    }

    /**
     * Record a new deposit / savings transaction.
     */
    public function storeTransaction(Request $request, SavingsGoal $savingsGoal): RedirectResponse
    {
        Gate::authorize('update', $savingsGoal);
        $user = $request->user();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        SavingsTransaction::create([
            'savings_goal_id' => $savingsGoal->id,
            'couple_id' => $user->couple_id,
            'user_id' => $user->id,
            'amount' => (int) $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $savingsGoal->refresh();

        if ($savingsGoal->status === 'achieved') {
            $msg = "Hore! Target tabungan '{$savingsGoal->name}' TELAH TERCAPAI! Selamat untuk kerja keras kalian berdua! 🎉🏆🥂";
        } else {
            $msg = 'Setoran tabungan sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil dicatat! Terus melangkah maju! 💰✨';
        }

        return redirect()->route('savings.show', $savingsGoal)->with('status', $msg);
    }

    /**
     * Delete a single transaction.
     */
    public function destroyTransaction(SavingsTransaction $savingsTransaction): RedirectResponse
    {
        if ($savingsTransaction->couple_id !== auth()->user()->couple_id || !auth()->user()->isPartner()) {
            abort(403);
        }

        $goal = $savingsTransaction->savingsGoal;
        $savingsTransaction->delete();

        return redirect()->route('savings.show', $goal)->with('status', 'Catatan setoran telah dihapus.');
    }

    /**
     * Delete a savings goal and all associated ledger entries.
     */
    public function destroyGoal(SavingsGoal $savingsGoal): RedirectResponse
    {
        Gate::authorize('delete', $savingsGoal);

        $savingsGoal->delete();

        return redirect()->route('savings.index')->with('status', 'Target tabungan telah dihapus.');
    }
}

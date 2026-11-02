<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([CoupleScope::class])]
class SavingsGoal extends Model
{
    use HasFactory;

    public const ICONS = ['💰', '🏠', '✈️', '🚗', '👶', '💍', '🎓', '🏥', '🎉', '🌟'];

    public const COLORS = [
        'pink' => 'from-pink-500 to-rose-500',
        'purple' => 'from-purple-500 to-indigo-500',
        'blue' => 'from-blue-500 to-cyan-500',
        'emerald' => 'from-emerald-500 to-teal-500',
        'amber' => 'from-amber-500 to-orange-500',
    ];

    protected $fillable = [
        'couple_id',
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'target_date',
        'description',
        'color',
        'icon',
        'status',
        'achieved_at',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'integer',
            'current_amount' => 'integer',
            'target_date' => 'date',
            'achieved_at' => 'datetime',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(SavingsTransaction::class)->orderByDesc('transaction_date')->orderByDesc('id');
    }

    public function getFormattedTargetAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCurrentAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->current_amount, 0, ',', '.');
    }

    public function getRemainingAmountAttribute(): int
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getFormattedRemainingAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 100;
        }

        return round(min(100, ($this->current_amount / $this->target_amount) * 100), 1);
    }

    /**
     * Estimate months remaining to achieve goal based on average monthly deposit history.
     */
    public function getEstimatedMonthsLeftAttribute(): ?int
    {
        if ($this->remaining_amount <= 0) {
            return 0;
        }

        $transactions = $this->transactions;
        if ($transactions->isEmpty()) {
            return null;
        }

        // Group deposits by year and month
        $monthlyTotals = $transactions->groupBy(function ($trx) {
            return Carbon::parse($trx->transaction_date)->format('Y-m');
        })->map(fn ($group) => $group->sum('amount'));

        $avgMonthlyDeposit = $monthlyTotals->avg();

        if (!$avgMonthlyDeposit || $avgMonthlyDeposit <= 0) {
            return null;
        }

        return (int) ceil($this->remaining_amount / $avgMonthlyDeposit);
    }

    /**
     * Recalculate and persist current amount from transaction history.
     */
    public function recalculateBalance(): void
    {
        $sum = $this->transactions()->sum('amount');
        $this->current_amount = $sum;

        if ($this->current_amount >= $this->target_amount && $this->status === 'active') {
            $this->status = 'achieved';
            $this->achieved_at = now();
        } elseif ($this->current_amount < $this->target_amount && $this->status === 'achieved') {
            $this->status = 'active';
            $this->achieved_at = null;
        }

        $this->save();
    }
}

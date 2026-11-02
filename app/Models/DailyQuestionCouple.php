<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([CoupleScope::class])]
class DailyQuestionCouple extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'daily_question_id',
        'served_date',
    ];

    protected function casts(): array
    {
        return [
            'served_date' => 'date',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(DailyQuestion::class, 'daily_question_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(DailyQuestionAnswer::class, 'daily_question_couple_id');
    }

    /**
     * Check whether both partners in the couple have answered.
     */
    public function areBothAnswersSubmitted(): bool
    {
        $partnerIds = $this->couple->partners()->pluck('id')->toArray();

        if (count($partnerIds) < 2) {
            return false;
        }

        $answersCount = $this->answers()->whereIn('user_id', $partnerIds)->count();

        return $answersCount >= 2;
    }
}

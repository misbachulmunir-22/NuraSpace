<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([CoupleScope::class])]
class DailyQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'daily_question_couple_id',
        'user_id',
        'answer_text',
    ];

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function dailyQuestionCouple(): BelongsTo
    {
        return $this->belongsTo(DailyQuestionCouple::class, 'daily_question_couple_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

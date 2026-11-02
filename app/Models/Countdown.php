<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([CoupleScope::class])]
class Countdown extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'created_by_user_id',
        'title',
        'target_date',
        'icon_emoji',
        'is_pinned',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'datetime',
            'is_pinned' => 'boolean',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function isPast(): bool
    {
        return now()->greaterThan($this->target_date);
    }

    public function daysRemaining(): int
    {
        return (int) now()->diffInDays($this->target_date, false);
    }
}

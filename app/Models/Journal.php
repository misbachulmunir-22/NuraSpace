<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([CoupleScope::class])]
class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'user_id',
        'title',
        'content',
        'visibility', // 'private', 'shared'
        'mood_tag',
        'entry_date',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
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

    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }

    public function isShared(): bool
    {
        return $this->visibility === 'shared';
    }
}

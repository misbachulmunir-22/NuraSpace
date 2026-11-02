<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([CoupleScope::class])]
class Mood extends Model
{
    use HasFactory;

    public const MOOD_TYPES = [
        'happy' => ['label' => 'Bahagia', 'emoji' => '😊', 'color' => 'emerald'],
        'sad' => ['label' => 'Sedih', 'emoji' => '🥺', 'color' => 'blue'],
        'tired' => ['label' => 'Lelah', 'emoji' => '😴', 'color' => 'slate'],
        'angry' => ['label' => 'Kesal', 'emoji' => '😤', 'color' => 'rose'],
        'needy' => ['label' => 'Manja / Butuh Dipeluk', 'emoji' => '🫂', 'color' => 'pink'],
        'excited' => ['label' => 'Bersemangat', 'emoji' => '✨', 'color' => 'amber'],
    ];

    protected $fillable = [
        'couple_id',
        'user_id',
        'mood_type',
        'note',
        'logged_date',
    ];

    protected function casts(): array
    {
        return [
            'logged_date' => 'date',
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

    public function getLabelAttribute(): string
    {
        return self::MOOD_TYPES[$this->mood_type]['label'] ?? ucfirst($this->mood_type);
    }

    public function getEmojiAttribute(): string
    {
        return self::MOOD_TYPES[$this->mood_type]['emoji'] ?? '❤️';
    }

    public function getColorAttribute(): string
    {
        return self::MOOD_TYPES[$this->mood_type]['color'] ?? 'rose';
    }
}

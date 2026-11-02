<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([CoupleScope::class])]
class Nudge extends Model
{
    use HasFactory;

    public const TYPES = [
        'hug' => [
            'label' => 'Pelukan Hangat',
            'verb' => 'memelukmu erat',
            'emoji' => '🤗',
            'animation' => 'hug-glow',
        ],
        'kiss' => [
            'label' => 'Kecupan Manis',
            'verb' => 'memberikan kecupan manis untukmu',
            'emoji' => '😘',
            'animation' => 'kiss-hearts',
        ],
        'miss_you' => [
            'label' => 'Aku Kangen Kamu',
            'verb' => 'sedang sangat merindukanmu',
            'emoji' => '🥺❤️',
            'animation' => 'miss-sparkles',
        ],
    ];

    protected $fillable = [
        'couple_id',
        'sender_id',
        'recipient_id',
        'nudge_type',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function getLabelAttribute(): string
    {
        return self::TYPES[$this->nudge_type]['label'] ?? 'Sentuhan Virtual';
    }

    public function getVerbAttribute(): string
    {
        return self::TYPES[$this->nudge_type]['verb'] ?? 'mengirimkan sentuhan';
    }

    public function getEmojiAttribute(): string
    {
        return self::TYPES[$this->nudge_type]['emoji'] ?? '❤️';
    }

    public function getAnimationAttribute(): string
    {
        return self::TYPES[$this->nudge_type]['animation'] ?? 'hug-glow';
    }
}

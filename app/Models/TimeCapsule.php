<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

#[ScopedBy([CoupleScope::class])]
class TimeCapsule extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'sender_id',
        'recipient_id',
        'title',
        'content',
        'unlock_at',
        'is_notified',
        'opened_at',
    ];

    protected function casts(): array
    {
        return [
            'unlock_at' => 'datetime',
            'opened_at' => 'datetime',
            'is_notified' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (TimeCapsule $capsule) {
            if ($capsule->isDirty('unlock_at')) {
                throw new RuntimeException('Waktu buka surat masa depan (unlock_at) tidak dapat diubah setelah disegel.');
            }
        });
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

    public function isUnlocked(): bool
    {
        return now()->greaterThanOrEqualTo($this->unlock_at);
    }

    /**
     * Get safe content. If locked, never disclose the raw content.
     */
    public function getSafeContent(): ?string
    {
        if (!$this->isUnlocked()) {
            return null;
        }

        return $this->content;
    }
}

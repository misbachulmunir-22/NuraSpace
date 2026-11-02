<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[ScopedBy([CoupleScope::class])]
class Memory extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'user_id',
        'title',
        'description',
        'memory_date',
        'location',
        'is_milestone',
    ];

    protected function casts(): array
    {
        return [
            'memory_date' => 'date',
            'is_milestone' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Cleanup all media files from private storage when memory is deleted
        static::deleting(function (Memory $memory) {
            foreach ($memory->media as $media) {
                if (Storage::disk('local')->exists($media->file_path)) {
                    Storage::disk('local')->delete($media->file_path);
                }
            }
        });
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(MemoryMedia::class)->orderBy('order_index');
    }

    public function primaryMedia(): ?MemoryMedia
    {
        return $this->media->first();
    }
}

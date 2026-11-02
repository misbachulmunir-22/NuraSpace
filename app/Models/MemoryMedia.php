<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

class MemoryMedia extends Model
{
    use HasFactory;

    protected $table = 'memory_media';

    protected $fillable = [
        'memory_id',
        'file_path',
        'media_type',
        'caption',
        'order_index',
    ];

    public function memory(): BelongsTo
    {
        return $this->belongsTo(Memory::class);
    }

    /**
     * Generate secure signed temporary URL for media delivery from private disk.
     */
    public function getSignedUrlAttribute(): string
    {
        return URL::temporarySignedRoute(
            'media.memory',
            now()->addMinutes(60),
            ['memoryMedia' => $this->id]
        );
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }
}

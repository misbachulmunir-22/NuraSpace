<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

#[ScopedBy([CoupleScope::class])]
class Wishlist extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'travel' => [
            'label' => 'Jalan-jalan & Liburan',
            'icon' => '✈️',
            'color' => 'blue',
        ],
        'item' => [
            'label' => 'Barang Impian',
            'icon' => '🎁',
            'color' => 'amber',
        ],
        'date_idea' => [
            'label' => 'Ide Kencan Romantis',
            'icon' => '🍷',
            'color' => 'pink',
        ],
        'milestone_goal' => [
            'label' => 'Rencana & Target Besar',
            'icon' => '🏆',
            'color' => 'purple',
        ],
        'other' => [
            'label' => 'Impian Lainnya',
            'icon' => '✨',
            'color' => 'emerald',
        ],
    ];

    public const STATUSES = [
        'pending' => 'Belum Dimulai',
        'in_progress' => 'Sedang Berjalan',
        'achieved' => 'Tercapai 🎉',
        'cancelled' => 'Dibatalkan',
    ];

    protected $fillable = [
        'couple_id',
        'user_id',
        'title',
        'description',
        'category',
        'estimated_cost',
        'target_date',
        'status',
        'achieved_at',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'achieved_at' => 'datetime',
            'estimated_cost' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Wishlist $wishlist) {
            if ($wishlist->image_path && Storage::disk('local')->exists($wishlist->image_path)) {
                Storage::disk('local')->delete($wishlist->image_path);
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

    public function getFormattedCostAttribute(): ?string
    {
        if ($this->estimated_cost === null) {
            return null;
        }

        return 'Rp ' . number_format($this->estimated_cost, 0, ',', '.');
    }

    public function getCategoryMetaAttribute(): array
    {
        return self::CATEGORIES[$this->category] ?? self::CATEGORIES['other'];
    }

    public function getSignedImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return URL::temporarySignedRoute(
            'media.wishlist',
            now()->addMinutes(60),
            ['wishlist' => $this->id]
        );
    }
}

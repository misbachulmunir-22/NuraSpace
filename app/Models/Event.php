<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([CoupleScope::class])]
class Event extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'date_night' => ['label' => 'Kencan & Romantis', 'emoji' => '🥂', 'default_color' => '#f43f5e'],
        'family' => ['label' => 'Acara Keluarga', 'emoji' => '👨‍👩‍👧‍👦', 'default_color' => '#8b5cf6'],
        'work_exam' => ['label' => 'Kerja & Ujian', 'emoji' => '💼', 'default_color' => '#3b82f6'],
        'anniversary' => ['label' => 'Anniversary', 'emoji' => '💍', 'default_color' => '#ec4899'],
        'birthday' => ['label' => 'Ulang Tahun', 'emoji' => '🎂', 'default_color' => '#f59e0b'],
        'other' => ['label' => 'Agenda Lainnya', 'emoji' => '📌', 'default_color' => '#6b7280'],
    ];

    protected $fillable = [
        'couple_id',
        'created_by_user_id',
        'title',
        'description',
        'category',
        'color',
        'start_at',
        'end_at',
        'is_all_day',
        'is_family_event',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'is_all_day' => 'boolean',
            'is_family_event' => 'boolean',
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

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category]['label'] ?? 'Agenda';
    }

    public function getCategoryEmojiAttribute(): string
    {
        return self::CATEGORIES[$this->category]['emoji'] ?? '📌';
    }
}

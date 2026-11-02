<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'anniversary_date',
        'avatar_cover_path',
    ];

    protected function casts(): array
    {
        return [
            'anniversary_date' => 'date',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'partner');
    }

    public function children(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'child');
    }
}

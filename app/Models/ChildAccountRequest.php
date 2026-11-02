<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([CoupleScope::class])]
class ChildAccountRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'requested_by_user_id',
        'name',
        'email',
        'birth_date',
        'gender',
        'initial_password',
        'status',
        'created_child_user_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function createdChild(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_child_user_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ChildAccountApproval::class, 'request_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}

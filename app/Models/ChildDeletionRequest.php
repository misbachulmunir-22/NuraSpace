<?php

namespace App\Models;

use App\Models\Scopes\CoupleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([CoupleScope::class])]
class ChildDeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'child_user_id',
        'child_name',
        'requested_by_user_id',
        'reason',
        'status',
    ];

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(User::class, 'child_user_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ChildDeletionApproval::class, 'deletion_request_id');
    }
}

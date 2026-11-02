<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildDeletionApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'deletion_request_id',
        'partner_user_id',
        'status',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function deletionRequest(): BelongsTo
    {
        return $this->belongsTo(ChildDeletionRequest::class, 'deletion_request_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_user_id');
    }
}

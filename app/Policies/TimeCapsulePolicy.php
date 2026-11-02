<?php

namespace App\Policies;

use App\Models\TimeCapsule;
use App\Models\User;

class TimeCapsulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isPartner();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeCapsule $capsule): bool
    {
        return $user->isPartner() && $user->couple_id === $capsule->couple_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPartner();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeCapsule $capsule): bool
    {
        return $user->isPartner() && $user->id === $capsule->sender_id;
    }
}

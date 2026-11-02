<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->couple_id !== null && $user->couple_id === $model->couple_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->couple_id !== $model->couple_id) {
            return false;
        }

        // Partner can update self or children
        if ($user->isPartner()) {
            return true;
        }

        // Child can only update self
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Partner accounts can NEVER be deleted.
     */
    public function delete(User $user, User $model): bool
    {
        // Block deletion of partner account unconditionally
        if ($model->isPartner()) {
            return false;
        }

        // Child accounts can only be deleted by partners with both-parent approval flow
        return $user->isPartner() && $user->couple_id === $model->couple_id;
    }
}

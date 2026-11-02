<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;

class JournalPolicy
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
     * Enforce: Private journal can NEVER be viewed by partner.
     * Child role can NEVER view journals.
     */
    public function view(User $user, Journal $journal): bool
    {
        if (!$user->isPartner() || $user->couple_id !== $journal->couple_id) {
            return false;
        }

        // If private, ONLY author can view
        if ($journal->visibility === 'private') {
            return $user->id === $journal->user_id;
        }

        // If shared, both partners can view
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPartner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Journal $journal): bool
    {
        return $user->isPartner() && $user->id === $journal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Journal $journal): bool
    {
        return $user->isPartner() && $user->id === $journal->user_id;
    }
}

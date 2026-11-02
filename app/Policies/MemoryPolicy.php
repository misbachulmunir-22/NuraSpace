<?php

namespace App\Policies;

use App\Models\Memory;
use App\Models\User;

class MemoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->couple_id !== null;
    }

    public function view(User $user, Memory $memory): bool
    {
        return $user->couple_id === $memory->couple_id;
    }

    public function create(User $user): bool
    {
        return $user->couple_id !== null;
    }

    public function update(User $user, Memory $memory): bool
    {
        if ($user->couple_id !== $memory->couple_id) {
            return false;
        }

        // Creator or any partner in the couple can edit
        return $user->id === $memory->user_id || $user->isPartner();
    }

    public function delete(User $user, Memory $memory): bool
    {
        if ($user->couple_id !== $memory->couple_id) {
            return false;
        }

        // Creator or any partner in the couple can delete
        return $user->id === $memory->user_id || $user->isPartner();
    }
}

<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return $user->couple_id !== null;
    }

    /**
     * Determine whether the user can view the event.
     * Child can ONLY view family events.
     */
    public function view(User $user, Event $event): bool
    {
        if ($user->couple_id !== $event->couple_id) {
            return false;
        }

        if ($user->isChild() && !$event->is_family_event) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->couple_id !== null;
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        if ($user->couple_id !== $event->couple_id) {
            return false;
        }

        // Partner can update any event in couple, child can only update own
        return $user->isPartner() || $user->id === $event->created_by_user_id;
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        if ($user->couple_id !== $event->couple_id) {
            return false;
        }

        return $user->isPartner() || $user->id === $event->created_by_user_id;
    }
}

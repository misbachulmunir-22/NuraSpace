<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;

class WishlistPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->couple_id !== null && $user->isPartner();
    }

    public function view(User $user, Wishlist $wishlist): bool
    {
        return $user->couple_id === $wishlist->couple_id && $user->isPartner();
    }

    public function create(User $user): bool
    {
        return $user->couple_id !== null && $user->isPartner();
    }

    public function update(User $user, Wishlist $wishlist): bool
    {
        return $user->couple_id === $wishlist->couple_id && $user->isPartner();
    }

    public function delete(User $user, Wishlist $wishlist): bool
    {
        return $user->couple_id === $wishlist->couple_id && $user->isPartner();
    }
}

<?php

namespace App\Policies;

use App\Models\SavingsGoal;
use App\Models\User;

class SavingsGoalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->couple_id !== null && $user->isPartner();
    }

    public function view(User $user, SavingsGoal $goal): bool
    {
        return $user->couple_id === $goal->couple_id && $user->isPartner();
    }

    public function create(User $user): bool
    {
        return $user->couple_id !== null && $user->isPartner();
    }

    public function update(User $user, SavingsGoal $goal): bool
    {
        return $user->couple_id === $goal->couple_id && $user->isPartner();
    }

    public function delete(User $user, SavingsGoal $goal): bool
    {
        return $user->couple_id === $goal->couple_id && $user->isPartner();
    }
}

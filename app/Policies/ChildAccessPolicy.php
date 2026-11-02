<?php

namespace App\Policies;

use App\Models\User;

class ChildAccessPolicy
{
    /**
     * Partner only: can manage family and child accounts.
     */
    public function manageFamily(User $user): bool
    {
        return $user->isPartner();
    }

    /**
     * Check if user can access a specific feature based on role and configuration.
     */
    public function accessFeature(User $user, string $featureKey): bool
    {
        if ($user->isPartner()) {
            return true;
        }

        $childPermissions = config('nuraspace.child_permissions', []);

        return (bool) ($childPermissions[$featureKey] ?? false);
    }
}

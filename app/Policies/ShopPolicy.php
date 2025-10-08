<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Only admin / super_admin can view lists.
     */
    public function viewAny(?User $user): bool
    {
        // Allow anyone (including guests) to view lists
        return true;
    }

    public function view(?User $user, $model = null): bool
    {
        // Allow anyone (including guests) to view a single resource
        return true;
    }

    public function create(?User $user): bool
    {
        // Allow any authenticated user to create their own resources
        return $user !== null;
    }

    public function update(?User $user, $model = null): bool
    {
        if (! $user) {
            return false;
        }

        // Admins can update everything
        if ($user->isAdmin()) {
            return true;
        }

        // Owners can update their own models (model must have user_id)
        if ($model && isset($model->user_id)) {
            return $model->user_id === $user->id;
        }

        return false;
    }

    public function delete(?User $user, $model = null): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($model && isset($model->user_id)) {
            return $model->user_id === $user->id;
        }

        return false;
    }

    public function restore(User $user, $model = null): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, $model = null): bool
    {
        return $user->isAdmin();
    }
}

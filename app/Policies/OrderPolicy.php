<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        // Admins can view all orders; guests cannot
        if ($user && $user->isAdmin()) {
            return true;
        }

        return $user !== null; // authenticated users can view their orders list
    }

    public function view(?User $user, Order $order)
    {
        if ($user && $user->isAdmin()) {
            return true;
        }

        return $user && $order->user_id === $user->id;
    }

    public function create(?User $user)
    {
        return $user !== null;
    }

    public function update(?User $user, Order $order)
    {
        if (! $user) {
            return false;
        }
        return $user->isAdmin() || $order->user_id === $user->id;
    }

    public function delete(?User $user, Order $order)
    {
        if (! $user) {
            return false;
        }
        return $user->isAdmin() || $order->user_id === $user->id;
    }
}

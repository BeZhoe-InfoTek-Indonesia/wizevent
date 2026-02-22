<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Order');
    }

    public function view(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('orders.view') || $authUser->id === $order->user_id;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Order');
    }

    public function update(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('orders.edit') || $authUser->id === $order->user_id;
    }

    public function delete(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('orders.delete') || $authUser->id === $order->user_id;
    }

    public function restore(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('orders.delete') || $authUser->id === $order->user_id;
    }

    public function forceDelete(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('orders.delete') || $authUser->id === $order->user_id;
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('orders.delete');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('orders.delete');
    }

    public function replicate(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('orders.create') || $authUser->id === $order->user_id;
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('orders.edit');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('events.view') || $authUser->can('events.edit');
    }

    public function view(AuthUser $authUser, Event $event): bool
    {
        if ($authUser->can('events.view') || $authUser->can('events.edit')) {
            return true;
        }

        return $authUser->hasRole('Super Admin') ||
               $authUser->id === $event->created_by;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('events.create');
    }

    public function update(AuthUser $authUser, Event $event): bool
    {
        if ($authUser->can('events.edit')) {
            return true;
        }

        return $authUser->hasRole('Super Admin') ||
               $authUser->id === $event->created_by;
    }

    public function delete(AuthUser $authUser, Event $event): bool
    {
        if (! $event->canBeDeleted()) {
            return false;
        }

        if ($authUser->can('events.delete')) {
            return true;
        }

        return $authUser->hasRole('Super Admin') ||
               $authUser->id === $event->created_by;
    }

    public function publish(AuthUser $authUser, Event $event): bool
    {
        if (! $event->canBePublished()) {
            return false;
        }

        if ($authUser->can('events.publish')) {
            return true;
        }

        return $authUser->hasRole('Super Admin') ||
               $authUser->id === $event->created_by;
    }

    public function cancel(AuthUser $authUser, Event $event): bool
    {
        if (! $event->canBeCancelled()) {
            return false;
        }

        if ($authUser->can('events.cancel')) {
            return true;
        }

        return $authUser->hasRole('Super Admin') ||
               $authUser->id === $event->created_by;
    }

    public function restore(AuthUser $authUser, Event $event): bool
    {
        if ($authUser->can('events.edit')) {
            return true;
        }

        return $authUser->hasRole('Super Admin') ||
               $authUser->id === $event->created_by;
    }

    public function forceDelete(AuthUser $authUser, Event $event): bool
    {
        return $authUser->hasRole('Super Admin');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Super Admin');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Super Admin') ||
               $authUser->can('events.view') ||
               $authUser->can('events.edit');
    }

    public function replicate(AuthUser $authUser, Event $event): bool
    {
        return $this->create($authUser);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Super Admin');
    }
}

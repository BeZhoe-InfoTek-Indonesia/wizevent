<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TicketType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('tickets.view');
    }

    public function view(User $authUser, TicketType $ticketType): bool
    {
        if ($authUser->can('tickets.view')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('tickets.create');
    }

    public function update(User $authUser, TicketType $ticketType): bool
    {
        if ($authUser->can('tickets.edit')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function delete(User $authUser, TicketType $ticketType): bool
    {
        if ($ticketType->sold_count > 0) {
            return false;
        }

        if ($authUser->can('tickets.delete')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function restore(User $authUser, TicketType $ticketType): bool
    {
        if ($authUser->can('tickets.edit')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function forceDelete(User $authUser, TicketType $ticketType): bool
    {
        return $authUser->hasRole('Super Admin');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->hasRole('Super Admin');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->hasRole('Super Admin') ||
               $authUser->can('tickets.view');
    }

    public function replicate(User $authUser, TicketType $ticketType): bool
    {
        return $this->create($authUser);
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->hasRole('Super Admin');
    }

    protected function ownsEvent(User $authUser, TicketType $ticketType): bool
    {
        return $authUser->hasRole('Super Admin') ||
               ($ticketType->event && $authUser->id === $ticketType->event->created_by);
    }
}

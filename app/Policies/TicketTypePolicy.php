<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TicketType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TicketTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('tickets.view');
    }

    public function view(AuthUser $authUser, TicketType $ticketType): bool
    {
        if ($authUser->can('tickets.view')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('tickets.create');
    }

    public function update(AuthUser $authUser, TicketType $ticketType): bool
    {
        if ($authUser->can('tickets.edit')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function delete(AuthUser $authUser, TicketType $ticketType): bool
    {
        if ($ticketType->sold_count > 0) {
            return false;
        }

        if ($authUser->can('tickets.delete')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function restore(AuthUser $authUser, TicketType $ticketType): bool
    {
        if ($authUser->can('tickets.edit')) {
            return true;
        }

        return $this->ownsEvent($authUser, $ticketType);
    }

    public function forceDelete(AuthUser $authUser, TicketType $ticketType): bool
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
               $authUser->can('tickets.view');
    }

    public function replicate(AuthUser $authUser, TicketType $ticketType): bool
    {
        return $this->create($authUser);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Super Admin');
    }

    protected function ownsEvent(AuthUser $authUser, TicketType $ticketType): bool
    {
        return $authUser->hasRole('Super Admin') ||
               ($ticketType->event && $authUser->id === $ticketType->event->created_by);
    }
}

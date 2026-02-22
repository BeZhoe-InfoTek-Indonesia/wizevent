<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Organizer;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Organizer');
    }

    public function view(AuthUser $authUser, Organizer $organizer): bool
    {
        return $authUser->can('View:Organizer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Organizer');
    }

    public function update(AuthUser $authUser, Organizer $organizer): bool
    {
        return $authUser->can('Update:Organizer');
    }

    public function delete(AuthUser $authUser, Organizer $organizer): bool
    {
        return $authUser->can('Delete:Organizer');
    }

    public function restore(AuthUser $authUser, Organizer $organizer): bool
    {
        return $authUser->can('Restore:Organizer');
    }

    public function forceDelete(AuthUser $authUser, Organizer $organizer): bool
    {
        return $authUser->can('ForceDelete:Organizer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Organizer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Organizer');
    }

    public function replicate(AuthUser $authUser, Organizer $organizer): bool
    {
        return $authUser->can('Replicate:Organizer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Organizer');
    }

}
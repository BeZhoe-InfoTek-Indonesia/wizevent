<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Performer;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerformerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Performer');
    }

    public function view(AuthUser $authUser, Performer $performer): bool
    {
        return $authUser->can('View:Performer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Performer');
    }

    public function update(AuthUser $authUser, Performer $performer): bool
    {
        return $authUser->can('Update:Performer');
    }

    public function delete(AuthUser $authUser, Performer $performer): bool
    {
        return $authUser->can('Delete:Performer');
    }

    public function restore(AuthUser $authUser, Performer $performer): bool
    {
        return $authUser->can('Restore:Performer');
    }

    public function forceDelete(AuthUser $authUser, Performer $performer): bool
    {
        return $authUser->can('ForceDelete:Performer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Performer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Performer');
    }

    public function replicate(AuthUser $authUser, Performer $performer): bool
    {
        return $authUser->can('Replicate:Performer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Performer');
    }

}
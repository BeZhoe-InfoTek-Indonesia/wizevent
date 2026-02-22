<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EventPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPlanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EventPlan');
    }

    public function view(AuthUser $authUser, EventPlan $eventPlan): bool
    {
        return $authUser->can('View:EventPlan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EventPlan');
    }

    public function update(AuthUser $authUser, EventPlan $eventPlan): bool
    {
        return $authUser->can('Update:EventPlan');
    }

    public function delete(AuthUser $authUser, EventPlan $eventPlan): bool
    {
        return $authUser->can('Delete:EventPlan');
    }

    public function restore(AuthUser $authUser, EventPlan $eventPlan): bool
    {
        return $authUser->can('Restore:EventPlan');
    }

    public function forceDelete(AuthUser $authUser, EventPlan $eventPlan): bool
    {
        return $authUser->can('ForceDelete:EventPlan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EventPlan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EventPlan');
    }

    public function replicate(AuthUser $authUser, EventPlan $eventPlan): bool
    {
        return $authUser->can('Replicate:EventPlan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EventPlan');
    }

}
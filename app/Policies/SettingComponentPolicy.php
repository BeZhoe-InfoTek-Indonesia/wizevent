<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SettingComponent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingComponentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('setting-components.view') || $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function view(User $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.view') || $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->can('setting-components.create') || $user->hasRole('Super Admin');
    }

    public function update(User $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.delete') || $user->hasRole('Super Admin');
    }

    public function restore(User $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function forceDelete(User $user, SettingComponent $settingComponent): bool
    {
        return $user->hasRole('Super Admin');
    }
}

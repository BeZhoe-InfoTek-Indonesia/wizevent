<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SettingComponent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SettingComponentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $user): bool
    {
        return $user->can('setting-components.view') || $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function view(AuthUser $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.view') || $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function create(AuthUser $user): bool
    {
        return $user->can('setting-components.create') || $user->hasRole('Super Admin');
    }

    public function update(AuthUser $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function delete(AuthUser $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.delete') || $user->hasRole('Super Admin');
    }

    public function restore(AuthUser $user, SettingComponent $settingComponent): bool
    {
        return $user->can('setting-components.edit') || $user->hasRole('Super Admin');
    }

    public function forceDelete(AuthUser $user, SettingComponent $settingComponent): bool
    {
        return $user->hasRole('Super Admin');
    }
}

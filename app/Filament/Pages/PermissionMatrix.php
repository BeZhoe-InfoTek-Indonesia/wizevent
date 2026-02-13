<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UnitEnum;
use BackedEnum;
class PermissionMatrix extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-lock-closed';

    protected string $view = 'filament.pages.permission-matrix';

    protected static UnitEnum|string|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 2;

    protected function getViewData(): array
    {
        return [
            'roles' => Role::with('permissions')->where('name', '!=', 'Super Admin')->get(),
            'permissions' => Permission::all()
                ->groupBy(function ($permission) {
                    return explode('.', $permission->name)[0] ?? 'Other';
                })
                ->sortKeys(),
        ];
    }

    public function togglePermission($roleId, $permissionName)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findByName($permissionName);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        Notification::make()
            ->title(__('permission.notifications.permission_updated'))
            ->success()
            ->send();
    }

    public function toggleRole($roleId)
    {
        $role = Role::findById($roleId);
        $permissions = Permission::all();
        
        $hasMissing = $permissions->contains(fn ($p) => !$role->hasPermissionTo($p));

        if ($hasMissing) {
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        Notification::make()
            ->title(__('permission.notifications.permissions_synced', ['role' => $role->name]))
            ->success()
            ->send();
    }

    public function toggleGroup($groupName)
    {
        $groupPermissions = Permission::where('name', 'like', "{$groupName}.%")->get();
        $roles = Role::where('name', '!=', 'Super Admin')->get();

        $allHaveAll = true;
        foreach ($roles as $role) {
            foreach ($groupPermissions as $permission) {
                if (!$role->hasPermissionTo($permission)) {
                    $allHaveAll = false;
                    break 2;
                }
            }
        }

        foreach ($roles as $role) {
            if ($allHaveAll) {
                $role->revokePermissionTo($groupPermissions);
            } else {
                $role->givePermissionTo($groupPermissions);
            }
        }

        Notification::make()
            ->title(__('permission.notifications.group_updated', ['group' => $groupName]))
            ->success()
            ->send();
    }

    public function getCategoryIcon($category): string
    {
        return match (strtolower($category)) {
            'events' => 'heroicon-o-calendar',
            'finance' => 'heroicon-o-credit-card',
            'permissions' => 'heroicon-o-key',
            'roles' => 'heroicon-o-user-group',
            'system' => 'heroicon-o-cog-6-tooth',
            'users' => 'heroicon-o-users',
            'tickets' => 'heroicon-o-ticket',
            default => 'heroicon-o-squares-2x2',
        };
    }
}

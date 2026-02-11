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

    public $roles;
    public $permissions;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->roles = Role::with('permissions')->where('name', '!=', 'Super Admin')->get(); 
        $this->permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0] ?? 'Other'; // Group by prefix (users.create -> users)
            })
            ->sortKeys();
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
            ->title('Permission updated')
            ->success()
            ->send();
    }
}

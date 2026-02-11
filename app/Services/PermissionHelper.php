<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if the current user has a specific permission.
     */
    public static function hasPermission(string $permission): bool
    {
        $user = Auth::user();

        return $user && $user->hasPermissionTo($permission);
    }

    /**
     * Check if the current user has any of the given permissions.
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        $user = Auth::user();

        return $user && $user->hasAnyPermission($permissions);
    }

    /**
     * Check if the current user has all of the given permissions.
     */
    public static function hasAllPermissions(array $permissions): bool
    {
        $user = Auth::user();

        return $user && $user->hasAllPermissions($permissions);
    }

    /**
     * Check if the current user has a specific role.
     */
    public static function hasRole(string $role): bool
    {
        $user = Auth::user();

        return $user && $user->hasRole($role);
    }

    /**
     * Check if the current user is a Super Admin.
     */
    public static function isSuperAdmin(): bool
    {
        return self::hasRole('Super Admin');
    }

    /**
     * Check if the current user can manage events.
     */
    public static function canManageEvents(): bool
    {
        return self::hasAnyPermission([
            'events.create',
            'events.edit',
            'events.delete',
        ]);
    }

    /**
     * Check if the current user can manage tickets.
     */
    public static function canManageTickets(): bool
    {
        return self::hasAnyPermission([
            'tickets.create',
            'tickets.edit',
            'tickets.delete',
        ]);
    }

    /**
     * Check if the current user can manage users.
     */
    public static function canManageUsers(): bool
    {
        return self::hasAnyPermission([
            'users.edit',
            'users.delete',
            'users.assign-roles',
        ]);
    }

    /**
     * Check if the current user can access financial features.
     */
    public static function canAccessFinance(): bool
    {
        return self::hasAnyPermission([
            'finance.view-reports',
            'finance.verify-payments',
            'finance.process-refunds',
        ]);
    }

    /**
     * Check if the current user can access system settings.
     */
    public static function canAccessSystem(): bool
    {
        return self::hasAnyPermission([
            'system.manage-settings',
            'system.view-logs',
        ]);
    }

    /**
     * Get all permissions for the current user.
     */
    public static function getCurrentUserPermissions(): array
    {
        $user = Auth::user();

        return $user ? $user->getAllPermissions()->pluck('name')->toArray() : [];
    }

    /**
     * Get all roles for the current user.
     */
    public static function getCurrentUserRoles(): array
    {
        $user = Auth::user();

        return $user ? $user->getRoleNames()->toArray() : [];
    }

    /**
     * Check if a user can perform an action on a specific resource type.
     */
    public static function canPerformAction(string $action, string $resource): bool
    {
        $permission = $resource.'.'.$action;

        return self::hasPermission($permission);
    }
}

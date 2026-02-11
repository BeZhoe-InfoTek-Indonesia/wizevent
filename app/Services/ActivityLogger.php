<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Contracts\Activity;

class ActivityLogger
{
    /**
     * Log a successful login.
     */
    public static function logLogin($user): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'successful' => true,
                'event' => 'login',
            ])
            ->log('User logged in successfully');
    }

    /**
     * Log a failed login attempt.
     */
    public static function logFailedLogin($credentials, $errorMessage = null): Activity
    {
        return activity()
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'email' => $credentials['email'] ?? 'unknown',
                'successful' => false,
                'error' => $errorMessage,
                'event' => 'login_failed',
            ])
            ->log('Failed login attempt');
    }

    /**
     * Log a user logout.
     */
    public static function logLogout($user): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'event' => 'logout',
            ])
            ->log('User logged out');
    }

    /**
     * Log a password change.
     */
    public static function logPasswordChange($user): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'event' => 'password_changed',
            ])
            ->log('User changed password');
    }

    /**
     * Log profile update.
     */
    public static function logProfileUpdate($user, $changes): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'changes' => $changes,
                'event' => 'profile_updated',
            ])
            ->log('User updated profile');
    }

    /**
     * Log role assignment.
     */
    public static function logRoleAssignment($user, $role, $action = 'assigned'): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'role' => $role,
                'action' => $action,
                'event' => 'role_'.$action,
            ])
            ->log("Role {$action} to user: {$role}");
    }

    /**
     * Log permission changes.
     */
    public static function logPermissionChange($user, $permissions, $action = 'updated'): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'permissions' => $permissions,
                'action' => $action,
                'event' => 'permissions_'.$action,
            ])
            ->log("User permissions {$action}");
    }

    /**
     * Log user creation.
     */
    public static function logUserCreation($user, $creator = null): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy($creator ?? auth()->user())
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'event' => 'user_created',
            ])
            ->log('User account created');
    }

    /**
     * Log user deletion.
     */
    public static function logUserDeletion($user, $deleter = null): Activity
    {
        return activity()
            ->performedOn($user)
            ->causedBy($deleter ?? auth()->user())
            ->withProperties([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'event' => 'user_deleted',
            ])
            ->log('User account deleted');
    }

    /**
     * Log suspicious activity.
     */
    public static function logSuspiciousActivity($description, $user = null, $properties = []): Activity
    {
        return activity()
            ->performedOn($user ?? auth()->user())
            ->causedBy($user ?? auth()->user())
            ->withProperties(array_merge([
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'event' => 'suspicious_activity',
                'description' => $description,
            ], $properties))
            ->log('Suspicious activity detected: '.$description);
    }
}

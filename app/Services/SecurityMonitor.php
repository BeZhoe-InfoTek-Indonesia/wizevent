<?php

namespace App\Services;

use App\Mail\SecurityAlert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class SecurityMonitor
{
    /**
     * Check for suspicious login patterns.
     */
    public static function checkLoginAttempt($email, $successful): void
    {
        $ip = Request::ip();
        $key = "login_attempts:{$ip}";

        // Get recent attempts from cache
        $attempts = Cache::get($key, []);
        $attempts[] = [
            'email' => $email,
            'successful' => $successful,
            'timestamp' => now(),
            'user_agent' => Request::userAgent(),
        ];

        // Keep only last 20 attempts
        $attempts = array_slice($attempts, -20);
        Cache::put($key, $attempts, now()->addHours(1));

        // Check for suspicious patterns
        self::analyzeLoginAttempts($ip, $attempts);
    }

    /**
     * Analyze login attempts for suspicious patterns.
     */
    private static function analyzeLoginAttempts($ip, $attempts): void
    {
        $recentAttempts = collect($attempts)->filter(function ($attempt) {
            return $attempt['timestamp']->gt(now()->subMinutes(15));
        });

        // Multiple failed attempts from same IP
        $failedAttempts = $recentAttempts->where('successful', false)->count();
        if ($failedAttempts >= 5) {
            self::alertSuspiciousActivity('Multiple failed login attempts', [
                'ip' => $ip,
                'failed_count' => $failedAttempts,
                'timeframe' => '15 minutes',
            ]);
        }

        // Multiple different emails from same IP
        $uniqueEmails = $recentAttempts->pluck('email')->unique()->count();
        if ($uniqueEmails >= 3 && $failedAttempts >= 3) {
            self::alertSuspiciousActivity('Credential stuffing attempt', [
                'ip' => $ip,
                'unique_emails' => $uniqueEmails,
                'failed_count' => $failedAttempts,
                'timeframe' => '15 minutes',
            ]);
        }

        // Rapid successful logins from different accounts (possible account takeover)
        $successfulAttempts = $recentAttempts->where('successful', true);
        $uniqueSuccessfulEmails = $successfulAttempts->pluck('email')->unique()->count();
        if ($uniqueSuccessfulEmails >= 2) {
            self::alertSuspiciousActivity('Multiple successful logins from different accounts', [
                'ip' => $ip,
                'unique_accounts' => $uniqueSuccessfulEmails,
                'timeframe' => '15 minutes',
            ]);
        }
    }

    /**
     * Check for suspicious user activity.
     */
    public static function checkUserActivity($user, $action): void
    {
        $key = "user_activity:{$user->id}";

        // Get recent user activities
        $activities = Cache::get($key, []);
        $activities[] = [
            'action' => $action,
            'timestamp' => now(),
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];

        // Keep only last 50 activities
        $activities = array_slice($activities, -50);
        Cache::put($key, $activities, now()->addHours(24));

        // Check for suspicious patterns
        self::analyzeUserActivity($user, $activities);
    }

    /**
     * Analyze user activity for suspicious patterns.
     */
    private static function analyzeUserActivity($user, $activities): void
    {
        $recentActivities = collect($activities)->filter(function ($activity) {
            return $activity['timestamp']->gt(now()->subMinutes(30));
        });

        // Rapid role/permission changes
        $sensitiveActions = $recentActivities->filter(function ($activity) {
            return in_array($activity['action'], ['role_assigned', 'role_removed', 'permissions_updated']);
        })->count();

        if ($sensitiveActions >= 3) {
            self::alertSuspiciousActivity('Rapid sensitive permission changes', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'sensitive_actions' => $sensitiveActions,
                'timeframe' => '30 minutes',
            ]);
        }

        // Activity from multiple IPs in short time
        $uniqueIPs = $recentActivities->pluck('ip')->unique()->count();
        if ($uniqueIPs >= 3) {
            self::alertSuspiciousActivity('User activity from multiple IPs', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'unique_ips' => $uniqueIPs,
                'timeframe' => '30 minutes',
            ]);
        }
    }

    /**
     * Check for unusual admin activity.
     */
    public static function checkAdminActivity($admin, $action, $target = null): void
    {
        // Log all admin actions for monitoring
        $key = "admin_activity:{$admin->id}";

        $activities = Cache::get($key, []);
        $activities[] = [
            'action' => $action,
            'target' => $target ? get_class($target).':'.$target->id : null,
            'timestamp' => now(),
            'ip' => Request::ip(),
        ];

        Cache::put($key, $activities, now()->addDays(7));

        // Check for mass operations
        if (in_array($action, ['bulk_delete', 'bulk_role_assign', 'bulk_permissions_update'])) {
            self::alertSuspiciousActivity('Mass admin operation performed', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'action' => $action,
                'target' => $target ? get_class($target).':'.$target->id : null,
            ]);
        }
    }

    /**
     * Send security alert for suspicious activity.
     */
    private static function alertSuspiciousActivity($description, $context): void
    {
        // Log the suspicious activity
        activity()
            ->withProperties(array_merge($context, [
                'alert_type' => 'security',
                'description' => $description,
                'event' => 'security_alert',
            ]))
            ->log("Security Alert: {$description}");

        // Send email alert to admins (in production)
        if (app()->environment('production')) {
            $adminUsers = \App\Models\User::role('Super Admin')->get();

            foreach ($adminUsers as $admin) {
                try {
                    Mail::to($admin->email)->send(new SecurityAlert($description, $context));
                } catch (\Exception $e) {
                    // Log email failure but don't expose it
                    \Log::error('Failed to send security alert email', [
                        'admin_id' => $admin->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Cache the alert to prevent spam
        $alertKey = 'security_alert:'.md5($description.serialize($context));
        Cache::put($alertKey, true, now()->addMinutes(30));
    }

    /**
     * Check if IP is blocked.
     */
    public static function isIPBlocked($ip): bool
    {
        return Cache::has("blocked_ip:{$ip}");
    }

    /**
     * Block an IP address temporarily.
     */
    public static function blockIP($ip, $duration = 3600): void
    {
        Cache::put("blocked_ip:{$ip}", true, now()->addSeconds($duration));

        self::alertSuspiciousActivity('IP address blocked', [
            'ip' => $ip,
            'duration' => $duration,
            'reason' => 'Automatic block due to suspicious activity',
        ]);
    }

    /**
     * Get security statistics.
     */
    public static function getSecurityStats(): array
    {
        return [
            'blocked_ips' => Cache::getStore()->getPrefix().'blocked_ip:*',
            'recent_alerts' => activity()
                ->where('properties->alert_type', 'security')
                ->where('created_at', '>', now()->subHours(24))
                ->count(),
            'failed_logins_today' => activity()
                ->where('event', 'login_failed')
                ->whereDate('created_at', today())
                ->count(),
        ];
    }
}

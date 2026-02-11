# Change: Implement EPIC-002 Remaining Authentication & Authorization

## Why
The add-laravel-breeze-auth change established foundational authentication (login, registration, basic roles, Google OAuth), but EPIC-002 requires comprehensive authorization features for enterprise-grade security. Critical missing components include: granular permission system, admin management interfaces, rate limiting, Sanctum session management, user profile management, and activity logging. These features are essential for the event management system to be production-ready and to enable subsequent EPICs that depend on proper authorization.

## What Changes
- Implement granular permission system with all EPIC-defined permissions (events, tickets, users, finance, system)
- Create PermissionSeeder and update RolePermissionSeeder with comprehensive permission assignments
- Build admin interfaces for managing users, roles, and permissions using Blade + Alpine.js
- Add rate limiting to authentication endpoints (5 attempts per minute)
- Integrate Laravel Sanctum for enhanced session management and API preparation
- Create user profile management system with avatar upload and password change
- Implement activity logging for all authentication events using spatie/laravel-activitylog
- Add permission-based UI element visibility in templates and components
- Create middleware for fine-grained route protection
- Configure session timeout (2 hours inactivity) and CSRF enforcement

## Impact
- **Affected Specs**: `authentication` (enhanced), `user-management` (new), `admin-interface` (new)
- **New Capabilities**: Complete RBAC system, admin management tools, security enhancements
- **Dependencies**: Enhanced spatie/laravel-permission usage, Laravel Sanctum, intervention/image
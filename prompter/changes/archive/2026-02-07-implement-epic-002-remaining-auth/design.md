## Context
The add-laravel-breeze-auth change successfully implemented foundational authentication features (Laravel Breeze, Livewire components, basic roles, Google OAuth). However, EPIC-002 requires a comprehensive authorization system with granular permissions, admin management interfaces, and enterprise-grade security features. The current implementation handles basic user authentication but lacks the sophisticated RBAC system, admin tools, and security enhancements needed for a production event management platform.

## Goals / Non-Goals
- **Goals**:
    - Complete EPIC-002 requirements for enterprise-grade authentication and authorization
    - Implement granular permission system covering all EPIC-defined categories
    - Create comprehensive admin interfaces for user, role, and permission management
    - Add security enhancements (rate limiting, Sanctum sessions, activity logging)
    - Provide user profile management capabilities
    - Enable permission-based UI rendering and route protection
- **Non-Goals**:
    - Multi-factor authentication (deferred to MVP 2.0 per EPIC)
    - IP-based access restrictions (deferred per EPIC)
    - CAPTCHA on login (deferred per EPIC)
    - Advanced permission inheritance hierarchies (not in EPIC scope)

## Decisions

### Decision: Granular Permission Implementation
- **What**: Implement exact permission structure defined in EPIC-002 with 19 permissions across 5 categories.
- **Why**: EPIC provides comprehensive permission matrix covering all expected use cases. Following this structure ensures alignment with business requirements and prevents permission scope creep.
- **Implementation**: Create PermissionSeeder with all permissions and assign appropriate sets to each role.

### Decision: Admin Interface Architecture
- **What**: Use Blade templates + Alpine.js for admin interfaces, not Livewire.
- **Why**: Admin interfaces involve complex CRUD forms, data tables, and permission matrices. Blade provides better form handling and Alpine.js offers the needed interactivity without the complexity of Livewire for admin use cases.
- **Alternative considered**: Livewire for admin - rejected due to form complexity and better Blade performance for data-heavy interfaces.

### Decision: Sanctum Integration Strategy
- **What**: Use Sanctum alongside existing sessions for enhanced security and future API preparation.
- **Why**: Sanctum provides better token management, CSRF protection, and prepares the system for future mobile/API requirements without breaking existing session-based authentication.
- **Implementation**: Gradual migration - keep sessions for web, add Sanctum for enhanced features.

### Decision: Rate Limiting Approach
- **What**: Implement 5 attempts per minute on login/password reset using Laravel's built-in rate limiting.
- **Why**: Matches EPIC-002 requirements exactly, prevents brute force attacks, and maintains user experience.
- **Implementation**: Use throttle middleware with custom error messages for better UX.

## Risks / Trade-offs
- **Risk**: Complex permission system may lead to access control bugs.
    - **Mitigation**: Comprehensive testing matrix, clear permission names, admin interface validation, detailed logging.
- **Risk**: Admin interface complexity may overwhelm new administrators.
    - **Mitigation**: Intuitive UI design, contextual help, role-based UI simplification, comprehensive documentation.
- **Trade-off**: Using Blade for admin vs Livewire means less dynamic but better performance for complex forms.
- **Trade-off**: Comprehensive permission system increases implementation complexity but provides necessary enterprise security.

## Migration Plan
1. **Database Setup**: Create PermissionSeeder, update role assignments
2. **Core Services**: Implement permission checking helpers, rate limiting middleware
3. **Admin Interfaces**: Build progressive interfaces (users → roles → permissions)
4. **Security Features**: Add Sanctum, rate limiting, activity logging
5. **User Features**: Implement profile management, avatar handling
6. **Integration**: Add permission checks to existing components and routes
7. **Testing**: Comprehensive security and permission testing
8. **Rollback**: Backup existing auth system before implementing changes

## Open Questions
- Should permission changes take effect immediately or require session refresh?
- How should we handle users with multiple roles for permission conflicts?
- Should activity logs be automatically cleaned up or retained indefinitely?
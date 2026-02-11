# EPIC-002: User Authentication & Authorization

## Business Value Statement

Enable secure user access control with role-based permissions, allowing different user types (Super Admin, Event Manager, Finance Admin, Check-in Staff, Visitors) to access appropriate features while protecting sensitive operations. This EPIC delivers the foundation for all user-specific functionality and ensures enterprise-grade security.

## Description

Implement comprehensive authentication and authorization using Laravel Breeze + Sanctum for session management, Google OAuth 2.0 for social login, and Spatie Laravel Permission for granular role-based access control (RBAC). This includes user registration, login, password reset, email verification, role assignment, permission management, and middleware for route protection.

## Source Traceability

| Document | Reference | Section/Page |
|----------|-----------|--------------|
| PRD | Technical Stack | Google OAuth 2.0, Laravel Sanctum |
| PRD | Admin Features | Role-based access control (Spatie Laravel Permission) |
| PRD | Security | CSRF protection, rate limiting, 2FA option |

## Scope Definition

| In Scope | Out of Scope |
|----------|--------------|
| User registration with email/password | Multi-factor authentication (2FA) - deferred to MVP 2.0 |
| Email verification workflow | SMS-based authentication |
| Google OAuth 2.0 social login | Facebook/Twitter/Apple social login |
| Password reset via email | Security questions |
| Role management (CRUD for roles) | Dynamic role creation by non-admins |
| Permission management (assign/revoke) | Permission inheritance hierarchies |
| Middleware for route protection | IP-based access restrictions |
| Rate limiting on auth endpoints | CAPTCHA on login |
| Session management with Sanctum | API token management (deferred) |
| User profile management | Avatar upload and cropping |
| Admin user management interface | Bulk user import |
| Role-based UI component rendering | Advanced audit trail for auth events |

## High-Level Acceptance Criteria

- [ ] Users can register with email/password and receive verification email
- [ ] Users can log in with email/password or Google OAuth 2.0
- [ ] Password reset workflow functional via email link
- [ ] Spatie Laravel Permission installed with roles and permissions seeded
- [ ] Admin interface for managing users, roles, and permissions
- [ ] Middleware protects admin routes (requires authentication + admin role)
- [ ] Rate limiting applied to login endpoint (5 attempts per minute)
- [ ] User profile page allows updating name, email, and password
- [ ] Google OAuth integration functional with user creation/linking
- [ ] Role-based access control enforced on all protected routes
- [ ] Permission checks implemented in Blade templates and Livewire components
- [ ] Session timeout configured (2 hours of inactivity)
- [ ] CSRF protection enabled on all forms
- [ ] Logout functionality clears session and redirects appropriately

## Dependencies

- **Prerequisite EPICs:** EPIC-001 (Platform Foundation)
- **External Dependencies:**
  - Google Cloud Console project for OAuth 2.0 credentials
  - SMTP server for email delivery
  - Laravel Socialite package
  - Spatie Laravel Permission package
- **Technical Prerequisites:**
  - User table migration from EPIC-001
  - Email queue configuration from EPIC-001

## Complexity Assessment

- **Size:** L (Large)
- **Technical Complexity:** Medium
  - OAuth 2.0 integration
  - Permission system configuration
  - Middleware chain setup
- **Integration Complexity:** Medium
  - Google OAuth callback handling
  - Email delivery for verification/reset
  - Session management across admin/visitor interfaces
- **Estimated Story Count:** 8-10 stories

## Technical Details

### Roles & Permissions Structure

**Default Roles:**
- `super_admin` - Full system access
- `event_manager` - Event CRUD, publishing, analytics
- `finance_admin` - Payment verification, financial reports
- `checkin_staff` - QR scanning, check-in operations
- `visitor` - Public event browsing, ticket purchase

**Permission Categories:**

```php
// Event Permissions
'events.view', 'events.create', 'events.edit', 'events.delete',
'events.publish', 'events.analytics', 'events.manage-seating'

// Ticket Permissions
'tickets.view', 'tickets.create', 'tickets.edit',
'tickets.validate', 'tickets.manual-checkin', 'tickets.view-qr'

// User Permissions
'users.view', 'users.edit', 'users.delete',
'users.assign-roles', 'users.manage-permissions'

// Financial Permissions
'finance.view-reports', 'finance.verify-payments',
'finance.process-refunds', 'finance.manage-pricing'

// System Permissions
'system.view-logs', 'system.view-activity',
'system.manage-settings', 'system.manage-notifications'
```

### Authentication Flow

```
[Registration]
  ↓
[Email Verification] → [Verified] → [Assign Default Role: visitor]
  ↓
[Login] → [Session Created] → [Redirect to Dashboard]

[Google OAuth]
  ↓
[Redirect to Google] → [User Authorizes] → [Callback]
  ↓
[Create/Link User] → [Assign Default Role: visitor] → [Session Created]
```

### Middleware Stack

```php
// Admin Routes
Route::middleware(['auth', 'role:super_admin|event_manager'])->group(function () {
    // Admin routes
});

// Finance Routes
Route::middleware(['auth', 'role:finance_admin'])->group(function () {
    // Finance routes
});

// Visitor Protected Routes
Route::middleware(['auth'])->group(function () {
    // User dashboard, profile, etc.
});
```

## Risks & Assumptions

**Assumptions:**
- Google OAuth 2.0 credentials can be obtained for development and production
- Email delivery is reliable for verification and password reset
- Users will verify their email addresses (or manual verification process exists)
- Default role assignment is sufficient (no complex role logic required)

**Risks:**
- **Risk:** Google OAuth service downtime prevents social login
  - **Mitigation:** Email/password login always available as fallback
- **Risk:** Email verification emails marked as spam
  - **Mitigation:** Configure SPF/DKIM records, use reputable SMTP provider
- **Risk:** Permission system complexity leads to access control bugs
  - **Mitigation:** Comprehensive testing of all role/permission combinations
- **Risk:** Session fixation or hijacking vulnerabilities
  - **Mitigation:** Laravel's built-in session security, HTTPS enforcement

## Related EPICs

- **Depends On:** EPIC-001 (Platform Foundation)
- **Blocks:** EPIC-003, 006, 008, 009, 010, 011, 012 (all user-specific features)
- **Related:** EPIC-012 (Activity logging for auth events)

## User Stories Covered

- Implicit: User registration, login, profile management (foundational for all other user stories)

## Definition of Done

- [ ] All acceptance criteria met and verified
- [ ] Unit tests for authentication logic (registration, login, password reset)
- [ ] Feature tests for OAuth flow and role assignment
- [ ] Permission seeder creates all roles and permissions
- [ ] Admin UI for user/role/permission management functional
- [ ] Rate limiting tested and enforced
- [ ] Email verification tested with real SMTP server
- [ ] Google OAuth tested with test Google account
- [ ] Security review completed (CSRF, XSS, SQL injection prevention)
- [ ] Documentation for role/permission management
- [ ] Code reviewed and approved

---

**EPIC Owner:** Tech Lead  
**Estimated Effort:** 2 sprints (4 weeks)  
**Priority:** P0 (Critical Path)

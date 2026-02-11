# Security Features and Audit Trail procedures

## Security Features

### Authentication
- **Rate Limiting:**
    - Login: 5 attempts per minute.
    - Password Reset: 3 attempts per minute.
    - Registration: 5 attempts per minute.
- **Session Management:**
    - Sessions timeout after 2 hours of inactivity.
    - Secure logout invalidates the session on the server.
    - CSRF protection enabled on all forms.
- **Password Security:**
    - Passwords must be confirmed for sensitive actions (Deletion, Password Change).
    - Hashed using Bcrypt.

### Authorization
- **RBAC:** Fine-grained permissions (see `permissions-and-roles.md`).
- **Middleware:** Routes are protected by `role`, `permission`, and `auth` middleware.
- **Sanctum:** Integrated for API token management and secure SPA authentication.

### Monitoring
- **Activity Logging:**
    - Powered by `spatie/laravel-activitylog`.
    - Logs all "dirty" changes on models (User, Event, etc.).
    - Logs specific security events: Login, Logout, Failed Login.

## Audit Trail Procedures

### Regular Audits
Admins should review the **Activity Log** (`/admin/activity`) weekly to identify:
- Repeated failed login attempts (Brute force indicators).
- Unauthorized role changes (Privilege escalation attempts).
- Unusual deletion of data.

### Incident Response
If suspicious activity is detected:
1. **Identify the User:** Use the Activity Log to find the User ID and IP.
2. **Lock Account:** Change the user's password or remove roles immediately.
3. **Analyze Scope:** Filter logs by that User ID to see all recent actions.
4. **Restore Data:** Use the "Old" values in the Activity Log to manually revert changes if necessary.

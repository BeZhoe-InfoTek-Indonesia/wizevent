## MODIFIED Requirements

### Requirement: Complete Authentication System
The system SHALL provide comprehensive user authentication flows using Laravel Breeze with Livewire components.

#### Scenario: User registration with role assignment
- **WHEN** a user registers for an account
- **THEN** they can select their role type (Visitor, Event Manager, etc.)
- **AND** appropriate permissions are automatically assigned via spatie/laravel-permission
- **AND** they receive an email verification request

#### Scenario: User login with role-based redirect
- **WHEN** a user successfully logs in
- **THEN** they are redirected to the appropriate portal based on their role
- **AND** Admin users go to `/admin/dashboard`
- **AND** Visitors go to the main visitor portal

#### Scenario: Password reset workflow
- **WHEN** a user requests a password reset
- **THEN** they receive a secure reset email
- **AND** can reset their password via a Livewire component
- **AND** are automatically logged in after successful reset

#### Scenario: Email verification
- **WHEN** a user registers for an account
- **THEN** they must verify their email address before full access
- **AND** unverified users have limited functionality

### Requirement: Google OAuth Integration
The system SHALL support Google OAuth authentication via Socialite integration.

#### Scenario: Google OAuth registration
- **WHEN** a user chooses to register with Google
- **THEN** they are redirected to Google for authentication
- **AND** upon return, their account is created with google_id and avatar populated
- **AND** they can select their role type during first-time setup

#### Scenario: Google OAuth login
- **WHEN** an existing Google user chooses to login
- **THEN** they are authenticated and redirected based on their role
- **AND** no additional password entry is required

### Requirement: Dual-Interface Authentication Views
The system SHALL provide authentication interfaces appropriate for each user portal.

#### Scenario: Admin authentication interface
- **WHEN** an admin user accesses `/admin/login`
- **THEN** the authentication interface uses the admin layout and styling
- **AND** matches the Blade-based admin interface conventions

#### Scenario: Visitor authentication interface
- **WHEN** a visitor accesses authentication routes
- **THEN** the interface uses the visitor layout and Livewire components
- **AND** maintains the SPA-like experience consistent with the visitor portal
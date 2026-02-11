## ADDED Requirements

### Requirement: Role-Based Access Control (RBAC)
The system SHALL strictly enforce access control using Roles and Permissions.

#### Scenario: Role assignment
- **WHEN** a user is assigned the 'Super Admin' role
- **THEN** they inherit all permissions defined for that role
- **AND** can access protected Admin routes

### Requirement: User Identity
The system SHALL store user identity including authentication provider details.

#### Scenario: User creation with Socialite
- **WHEN** a user registers via Google
- **THEN** a `users` record is created
- **AND** the `google_id` and `avatar` fields are populated

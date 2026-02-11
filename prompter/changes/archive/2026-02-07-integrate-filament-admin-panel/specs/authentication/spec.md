# Authentication Specification

## MODIFIED Requirements

### Requirement: Dual-Interface Authentication Views

The authentication system SHALL support both visitor authentication (Laravel Breeze Livewire) and admin authentication (Filament integration).

**Rationale**: Maintains separate authentication experiences for visitors and admins while using the same underlying authentication system.

**Previous**: Single authentication flow for all users  
**Updated**: Visitor auth via Breeze Livewire, admin auth via Filament (both using `web` guard)

#### Scenario: Admin Authentication via Filament
**Given** an admin user navigates to `/admin`  
**When** they are not authenticated  
**Then** they should be redirected to Filament's login page  
**And** the login page should use Filament's styled interface  
**And** successful login should redirect to Filament dashboard  
**And** the session should use the `web` guard  
**And** activity should be logged via Spatie Activity Log

#### Scenario: Visitor Authentication via Breeze
**Given** a visitor navigates to a protected visitor page  
**When** they are not authenticated  
**Then** they should be redirected to Breeze login page (Livewire)  
**And** the login page should use visitor interface styling  
**And** successful login should redirect to visitor dashboard  
**And** the session should use the `web` guard  
**And** Google OAuth should be available

#### Scenario: Cross-Interface Authentication
**Given** a user is authenticated in the visitor interface  
**When** they navigate to `/admin` and have admin permissions  
**Then** they should access Filament dashboard without re-authentication  
**And** vice versa (admin to visitor interface)  
**And** the same session should be valid for both interfaces

## ADDED Requirements

### Requirement: Filament Authentication Configuration

The system SHALL configure Filament to use the existing Laravel Breeze authentication system without replacing it.

**Rationale**: Maintains consistency with existing authentication while leveraging Filament's admin-specific features.

#### Scenario: Filament Auth Guard Configuration
**Given** Filament is installed  
**When** authentication is configured  
**Then** Filament should use the `web` guard (not create a separate admin guard)  
**And** Filament should respect existing authentication middleware  
**And** Filament should integrate with Laravel Breeze session management  
**And** Filament should not override Breeze routes

#### Scenario: Filament Login Customization
**Given** Filament authentication is configured  
**When** an unauthenticated user accesses `/admin`  
**Then** they should see Filament's login page  
**And** the login page should display project branding  
**And** the login page should support "Remember Me"  
**And** failed login attempts should be rate-limited  
**And** login activity should be logged

## Related Capabilities
- **admin-interface**: Filament provides admin authentication UI
- **user-management**: User model unchanged, works with both Breeze and Filament
- **frontend-architecture**: Authentication views styled differently per interface

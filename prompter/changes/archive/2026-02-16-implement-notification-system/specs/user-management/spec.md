## MODIFIED Requirements

### Requirement: User Profile Management
The system SHALL provide comprehensive user profile management capabilities for account self-service.

#### Scenario: Profile information updates
- **WHEN** a user accesses their profile page
- **THEN** they can update their name and email address with proper validation
- **AND** they can change their password by entering current password for verification
- **AND** they can upload and update their profile picture with size and format validation
- **AND** profile changes are saved with success confirmation

#### Scenario: Avatar management and processing
- **WHEN** a user uploads a profile picture
- **THEN** image is validated for file type (JPG, PNG, WebP) and size (≤5MB)
- **AND** image is automatically cropped and resized to standard dimensions
- **AND** both Google OAuth avatars and manual uploads are supported
- **AND** avatar is displayed consistently across the platform

#### Scenario: Account security and deletion
- **WHEN** a user wants to delete their account
- **THEN** they must confirm their password for security verification
- **AND** they receive clear warnings about permanent data loss
- **AND** their account and all associated data are permanently deleted
- **AND** all active sessions are immediately terminated

## ADDED Requirements

### Requirement: Profile Notification Preferences
The system SHALL allow users to manage their notification preferences from their profile.

#### Scenario: User accesses notification preferences from profile
- **WHEN** a user navigates to their profile page
- **THEN** they see a "Notification Preferences" section or link
- **AND** clicking it takes them to the notification preferences page
- **AND** the notification preferences page displays their current settings

#### Scenario: Notification preferences link in profile menu
- **WHEN** a user is on any page with the user menu in navigation
- **THEN** they can access "Notification Preferences" from the dropdown menu
- **AND** clicking it takes them to the notification preferences page
- **AND** the link is clearly labeled and discoverable

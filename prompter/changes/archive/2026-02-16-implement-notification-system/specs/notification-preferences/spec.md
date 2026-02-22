## ADDED Requirements

### Requirement: Notification Preferences Management
The system MUST allow users to manage their notification preferences per channel and notification type.

#### Scenario: User accesses notification preferences
- **Given** I am logged in
- **When** I navigate to notification preferences (via profile or direct URL)
- **Then** I see preference toggles organized by channel (Email, In-App)
- **And** each channel shows toggles for all notification types
- **And** preferences reflect my current settings

#### Scenario: User enables/disables email notifications
- **Given** I am on the notification preferences page
- **When** I toggle email notification for "Payment" to off
- **Then** the preference is saved to database
- **And** I will not receive email notifications for payment events
- **And** I may still receive in-app notifications if in-app preference is enabled

#### Scenario: User enables/disables in-app notifications
- **Given** I am on the notification preferences page
- **When** I toggle in-app notification for "Events" to off
- **Then** the preference is saved to database
- **And** I will not see event notifications in the notification center
- **And** I may still receive email notifications if email preference is enabled

#### Scenario: User disables all notifications for a type
- **Given** I am on the notification preferences page
- **When** I disable both email and in-app notifications for "Promotions"
- **Then** I will not receive any promotional notifications
- **And** email and in-app channels respect this preference

#### Scenario: User saves preferences with validation
- **Given** I am on the notification preferences page
- **When** I modify preferences and click "Save"
- **Then** my preferences are validated
- **And** valid preferences are saved successfully
- **And** a success message is displayed
- **And** the preferences page reflects the new settings

### Requirement: Default Notification Preferences
The system MUST provide default notification preferences for new users.

#### Scenario: New user has all notifications enabled by default
- **Given** I create a new account
- **When** my account is created
- **Then** all notification types are enabled by default for both email and in-app channels
- **And** I will receive notifications until I opt-out
- **And** this follows the opt-out model for better engagement

#### Scenario: Existing user has existing preferences preserved
- **Given** I am an existing user with custom preferences
- **When** the notification system is deployed
- **Then** my existing preferences are not changed
- **And** my preferences work correctly with the new system
- **And** I am not forced to re-configure my preferences

### Requirement: Granular Channel Control
The system MUST allow separate control over email and in-app notification channels.

#### Scenario: User enables email only for a type
- **Given** I want email notifications for payments but not in-app
- **When** I enable email "Payment" and disable in-app "Payment"
- **Then** I receive email notifications for payments
- **And** I do not see payment notifications in the notification center

#### Scenario: User enables in-app only for a type
- **Given** I want in-app notifications for events but not email
- **When** I enable in-app "Events" and disable email "Events"
- **Then** I see event notifications in the notification center
- **And** I do not receive email notifications for events

#### Scenario: User enables both channels for a type
- **Given** I want both email and in-app notifications for loved events
- **When** I enable both email "Loved Events" and in-app "Loved Events"
- **Then** I receive email notifications for loved event updates
- **And** I see loved event notifications in the notification center

# in-app-notifications Specification

## Purpose
TBD - created by archiving change implement-notification-system. Update Purpose after archive.
## Requirements
### Requirement: In-App Notification Center
The system MUST provide an in-app notification center where users can view and manage their notifications.

#### Scenario: User views notification center
- **Given** I am logged in
- **When** I navigate to the notification center
- **Then** I see a list of all my notifications
- **And** notifications are ordered by date (newest first)
- **And** I can filter by: All, Unread, Payment, Events, Promotions
- **And** each notification shows: title, message, timestamp, read/unread status

#### Scenario: User marks notification as read
- **Given** I have unread notifications
- **When** I click on a notification or mark it as read
- **Then** the notification is marked as read (`read_at` is set)
- **And** the unread badge count is decremented
- **And** the notification visual state changes to read

#### Scenario: User marks all notifications as read
- **Given** I have multiple unread notifications
- **When** I click "Mark all as read"
- **Then** all my unread notifications are marked as read
- **And** the unread badge count becomes 0
- **And** the notification center refreshes

#### Scenario: User dismisses notification
- **Given** I am viewing my notifications
- **When** I click delete/dismiss on a notification
- **Then** the notification is removed from the list
- **And** the badge count is updated if it was unread

### Requirement: Notification Badge
The system MUST display a notification badge showing the count of unread notifications.

#### Scenario: Unread badge displays correct count
- **Given** I have 5 unread notifications
- **When** I view any page with the navigation
- **Then** the notification bell shows a badge with "5"
- **And** the badge is visible and prominent

#### Scenario: Badge updates in real-time
- **Given** I have 3 unread notifications
- **When** a new notification arrives
- **Then** the badge count updates to "4"
- **And** the bell may animate or flash to indicate new notification

#### Scenario: Badge hides when no unread notifications
- **Given** I have 0 unread notifications
- **When** I view any page with the navigation
- **Then** the notification bell does not show a badge
- **Or** the badge shows "0" and is hidden via CSS

### Requirement: Notification Dropdown
The system MUST provide a quick-access notification dropdown from the navigation.

#### Scenario: User opens notification dropdown
- **Given** I am logged in and viewing any page
- **When** I click the notification bell in navigation
- **Then** a dropdown/modal appears showing recent notifications (max 10)
- **And** each notification shows: title, preview message, timestamp, read/unread indicator
- **And** clicking a notification marks it as read
- **And** clicking a notification links to the relevant page (order details, event page, etc.)

#### Scenario: Dropdown shows link to full notification center
- **Given** I have more than 10 notifications
- **When** I open the notification dropdown
- **Then** I see a "View all notifications" link at the bottom
- **And** clicking it takes me to the full notification center page

### Requirement: Notification Types
The system MUST support multiple notification types with appropriate content and actions.

#### Scenario: Payment approved notification
- **Given** My payment has been approved
- **When** I receive the notification
- **Then** the notification type is `PAYMENT_APPROVED`
- **And** the title is "Payment Approved"
- **And** the message includes the order number
- **And** clicking takes me to order details page

#### Scenario: Event updated notification
- **Given** An event I've interacted with has been updated
- **When** I receive the notification
- **Then** the notification type is `EVENT_UPDATE`
- **And** the title is "Event Updated"
- **And** the message mentions what changed
- **And** clicking takes me to the event details page

#### Scenario: Loved event update notification
- **Given** An event I've loved has changed details or price
- **When** I receive the notification
- **Then** the notification type is `LOVED_EVENT_UPDATE`
- **And** the title is "Loved Event Update"
- **And** the message includes the event name and what changed
- **And** clicking takes me to the event details page

#### Scenario: Promotion notification
- **Given** Admin sends a promotional notification
- **When** I receive the notification
- **Then** the notification type is `PROMOTION`
- **And** the title is the promotion title
- **And** the message includes the promotion details
- **And** clicking takes me to the relevant page (event list, specific event, etc.)


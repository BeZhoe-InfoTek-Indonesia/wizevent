# Change: Implement Notification System

## Why
Users currently lack visibility into important system events (payment approvals, event updates, loved event changes). Implementing a multi-channel notification system with email and in-app notifications will improve user engagement, reduce support inquiries, and keep users informed in real-time.

## What Changes
- **ADDED**: In-app notification center for displaying user notifications
- **ADDED**: Notification badge showing unread count
- **ADDED**: Mark as read/dismiss functionality
- **ADDED**: Notification preferences management (email/in-app toggles per type)
- **ADDED**: Database schema for notifications (notifications table)
- **ADDED**: Queue-based email delivery for notification types
- **MODIFIED**: Email notification capability expanded to cover event updates, loved events, and promotional notifications
- **MODIFIED**: User profile to include notification preferences section
- **MODIFIED**: Navigation to show notification bell with unread count

## Impact
- Affected specs: `email-notification`, `user-management`, plus two new specs: `in-app-notifications`, `notification-preferences`
- Affected code:
  - `app/Models/Notification.php` (new)
  - `app/Jobs/SendNotificationEmail.php` (new)
  - `app/Services/NotificationService.php` (new)
  - `app/Livewire/User/NotificationCenter.php` (new)
  - `app/Livewire/User/NotificationPreferences.php` (new)
  - `app/Http/Controllers/NotificationController.php` (new)
  - `resources/views/livewire/user/notification-center.blade.php` (new)
  - `resources/views/livewire/user/notification-preferences.blade.php` (new)
  - `resources/views/livewire/layouts/navigation.blade.php` (modified)
  - `resources/views/livewire/profile/profile-component.blade.php` (modified)

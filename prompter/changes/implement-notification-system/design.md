# Design: Notification System

## Context
The platform currently has basic email notification for payment verification. Users need a comprehensive notification system to stay informed about important events across the platform, including payment approvals, event updates, loved event changes, and promotional content.

## Goals / Non-Goals
- **Goals**:
    - Multi-channel notifications (email + in-app)
    - User control over notification preferences
    - Queue-based email delivery for performance
    - Real-time notification badges
    - Notification center with filtering
- **Non-Goals**:
    - SMS notifications
    - Push notifications (browser/mobile)
    - Notification grouping/threading
    - Notification scheduling
    - Notification digests (daily/weekly)
    - Email open/click tracking
    - A/B testing for notifications

## Decisions
- **Decision: Use Laravel's Native Notifications**
    - Laravel provides a built-in notification system with database and email channels
    - Leverages existing queue infrastructure
    - Reduces custom code and maintenance burden
    - Supports multiple channels with a single notification class

- **Decision: Notification Storage Pattern**
    - Store notifications in database using Laravel's `notifications` table pattern
    - Include polymorphic relations for notifiable entities (User)
    - JSON data field for flexible payload storage
    - Separate `read_at` timestamp for read/unread tracking

- **Decision: Notification Type Constants**
    - Define notification types as PHP constants for type safety
    - Types: `PAYMENT_APPROVED`, `PAYMENT_REJECTED`, `EVENT_UPDATE`, `EVENT_CANCELLED`, `LOVED_EVENT_UPDATE`, `PROMOTION`
    - Each type has its own mailable class for email

- **Decision: User Preferences Storage**
    - Store preferences as JSON columns in `users` table
    - Structure: `email_notifications: {payment: true, events: true, promotions: false}` and `in_app_notifications: {payment: true, events: true, promotions: false}`
    - Default all preferences to `true` (opt-out model)
    - Allows granular control per notification type and channel

- **Decision: Badge Count Strategy**
    - Calculate unread count on-the-fly via database query (`read_at IS NULL`)
    - Cache count in session for performance (refresh every 5 minutes)
    - Live update via Livewire poll or Alpine.js when viewing notification center

- **Decision: Email Queue Strategy**
    - Use existing database queue driver (consistent with current setup)
    - Create dedicated `SendNotificationEmail` job extending Laravel's `ShouldQueue`
    - Configure retry attempts: 3 with exponential backoff
    - Failed jobs logged to `notifications_failed_jobs` table

- **Decision: Notification Center UI Pattern**
    - Modal/dropdown for quick access from navigation
    - Full-page notification center for detailed view
    - Tabs: All, Unread, Payment, Events, Promotions
    - Infinite scroll for large notification lists
    - Swipe/click to mark as read

## Data Model

### `notifications` Table
- `id` (primary key)
- `type` (notification class name)
- `notifiable_type` (morph: App\Models\User)
- `notifiable_id` (morph: user_id)
- `data` (JSON: notification payload)
- `read_at` (timestamp or null)
- `created_at`, `updated_at`

### `users` Table (MODIFIED)
- Add `email_notifications` (JSON)
- Add `in_app_notifications` (JSON)

## Notification Types

| Type | Trigger | Email | In-App | Default Preference |
|------|----------|--------|---------|------------------|
| PAYMENT_APPROVED | Payment verified | Yes | Yes | true |
| PAYMENT_REJECTED | Payment rejected | Yes | Yes | true |
| EVENT_UPDATE | Event details changed | Yes | Yes | true |
| EVENT_CANCELLED | Event cancelled | Yes | Yes | true |
| LOVED_EVENT_UPDATE | Event details/price changed | Yes | Yes | true |
| PROMOTION | Admin-initiated campaign | Yes | Yes | true |

## Email Templates
- `PaymentApprovedNotification` - Already exists (from payment verification)
- `PaymentRejectedNotification` - New
- `EventUpdatedNotification` - New
- `EventCancelledNotification` - New
- `LovedEventUpdateNotification` - New
- `PromotionNotification` - New

## API & Routes

### Notification Controller
- `GET /notifications` - List user's notifications with filters
- `GET /notifications/{id}` - Get notification details
- `POST /notifications/{id}/read` - Mark as read
- `POST /notifications/read-all` - Mark all as read
- `DELETE /notifications/{id}` - Dismiss/delete notification

### Notification Preferences Routes
- `GET /profile/notifications` - Notification preferences page
- `POST /profile/notifications` - Update preferences

## Risks / Trade-offs
- **Risk**: Database query performance for large notification history
    - **Mitigation**: Add index on `read_at` and `notifiable_id`, implement pagination, consider archiving old notifications
- **Risk**: Email queue backlog during high volume
    - **Mitigation**: Implement queue monitoring, configure retry strategy, consider multiple queue workers
- **Risk**: User notification fatigue
    - **Mitigation**: Granular preferences, rate limiting promotional notifications, respect user opt-out
- **Trade-off**: Real-time vs. Polling for badge updates
    - **Decision**: Use polling (every 60s) instead of WebSocket for simplicity and reduced infrastructure

## Migration Plan
1. Create `notifications` table migration
2. Add notification preferences columns to `users` table
3. Run migration and backfill existing users with default preferences
4. Create notification mailable classes
5. Implement NotificationService
6. Create Livewire components
7. Update navigation and profile views
8. Test email delivery with different preferences
9. Deploy and monitor queue performance

## Open Questions
- Should notification history be limited (e.g., last 30 days) or kept indefinitely?
  - **Recommendation**: Keep 90 days, archive older to cold storage
- Should admin notifications be included in this system?
  - **Recommendation**: No, keep admin notifications separate (EPIC-002 scope)
- Should email notifications be grouped (e.g., "You have 5 new notifications")?
  - **Recommendation**: No, individual emails as per out-of-scope decision

## Performance Considerations
- Database indexes: `notifiable_type_id`, `read_at`, `created_at`
- Query optimization: Eager load related data, use select only needed columns
- Caching: Badge count in session (5min TTL), notification preferences in cache (10min TTL)
- Queue: Configure 3-5 workers for notification jobs during peak hours
- Pagination: Use cursor-based pagination for infinite scroll (better than offset)

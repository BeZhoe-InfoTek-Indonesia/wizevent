# email-notification Specification

## Purpose
TBD - created by archiving change implement-successful-payment-email. Update Purpose after archive.
## Requirements
### Requirement: Payment Verification Email
The system MUST send an email notification upon successful payment verification.

#### Scenario: Payment Verification Approved
- **Given** an admin approves a payment proof OR manually approves an order
- **Then** `PaymentVerificationApproved` email is sent.
- **And** email is sent only if user has email "Payment" preference enabled.
- **And** email includes:
    - "Download Invoice" button/link.
    - "Download Ticket" button/link for each ticket (or a "View All Tickets" link).
    - A QR code image embedded for the primary ticket (or all if feasible).
    - Event Date, Time, and Venue details.

#### Scenario: Fallback Mechanism
- **Given** PDF generation fails
- **Then** system should log error
- **And** retry the job (standard queue behavior)
- **And** NOT send a broken email.

### Requirement: Payment Rejected Email
The system MUST send an email notification when payment verification is rejected.

#### Scenario: Payment Verification Rejected
- **Given** an admin rejects a payment proof
- **Then** `PaymentVerificationRejected` email is sent.
- **And** email is sent only if user has email "Payment" preference enabled.
- **And** email includes:
    - Rejection reason provided by admin.
    - Order number and details.
    - Link to upload new payment proof.
    - Instructions on next steps.
    - Support contact information.

### Requirement: Event Update Email
The system MUST send email notifications when events users have interacted with are updated.

#### Scenario: Event Details Changed
- **Given** an event user has purchased a ticket for is updated (date, time, venue, etc.)
- **Then** `EventUpdatedNotification` email is sent to all ticket holders.
- **And** email is sent only if user has email "Events" preference enabled.
- **And** email includes:
    - Event name and summary of changes.
    - New event details (date, time, venue).
    - Link to view event page.
    - "View Your Tickets" link.

#### Scenario: Event Cancelled
- **Given** an event user has purchased a ticket for is cancelled
- **Then** `EventCancelledNotification` email is sent to all ticket holders.
- **And** email is sent only if user has email "Events" preference enabled.
- **And** email includes:
    - Event name and cancellation notice.
    - Refund information (if applicable).
    - Contact information for support.
    - Link to order details for refund status.

### Requirement: Loved Event Update Email
The system MUST send email notifications when loved events are updated.

#### Scenario: Loved Event Changed
- **Given** an event user has loved is updated (price change, new details, etc.)
- **Then** `LovedEventUpdateNotification` email is sent.
- **And** email is sent only if user has email "Loved Events" preference enabled.
- **And** email includes:
    - Event name and summary of changes.
    - New event details if applicable.
    - Link to view event page.
    - Option to purchase tickets (if available).

### Requirement: Promotional Email
The system MUST support sending promotional notification emails.

#### Scenario: Admin Sends Promotion
- **Given** an admin creates and sends a promotional campaign
- **Then** `PromotionNotification` email is sent to targeted users.
- **And** email is sent only if user has email "Promotions" preference enabled.
- **And** email includes:
    - Promotion title and description.
    - Promotional details (discount, offer, event info).
    - Call-to-action button/link.
    - Unsubscribe/opt-out link.
    - Campaign tracking parameters.

### Requirement: Email Queue Delivery
The system MUST send email notifications via queue for asynchronous processing.

#### Scenario: Email Added to Queue
- **Given** a notification triggers an email
- **Then** the email job is dispatched to the queue
- **And** email is not sent immediately (synchronously)
- **And** the job includes notification type and recipient information

#### Scenario: Queue Processes Email Job
- **Given** an email job is in the queue
- **When** the queue worker processes the job
- **Then** the email is sent using Laravel's Mail facade
- **And** the email is sent to the correct recipient
- **And** the job is marked as completed

#### Scenario: Email Job Fails and Retries
- **Given** an email job fails to send (SMTP error, etc.)
- **Then** the job is marked as failed
- **And** the job is retried up to 3 times with exponential backoff
- **And** failure is logged for troubleshooting

#### Scenario: Email Job Exhausts Retries
- **Given** an email job has failed 3 times
- **When** the 3rd retry attempt fails
- **Then** the job is marked as failed permanently
- **And** no further retries are attempted
- **And** admin may be notified of persistent failures

### Requirement: Email Preferences Enforcement
The system MUST respect user email preferences when sending notifications.

#### Scenario: Email Preference Disabled for Type
- **Given** user has disabled email "Events" preference
- **When** an event update notification triggers
- **Then** no email is sent to the user
- **And** in-app notification may still be sent (if preference enabled)
- **And** the notification job checks preferences before queueing email

#### Scenario: Email Preference Enabled for Type
- **Given** user has enabled email "Payment" preference
- **When** a payment verification notification triggers
- **Then** email is sent to the user
- **And** email contains the expected content
- **And** email is queued and sent asynchronously

### Requirement: Responsive Email Templates
The system MUST provide responsive email templates that display correctly on all devices.

#### Scenario: Email Renders on Mobile
- **Given** I receive a notification email on my mobile device
- **When** I open the email
- **Then** the email layout is responsive and readable on mobile
- **And** buttons and links are tappable
- **And** images scale appropriately

#### Scenario: Email Renders on Desktop
- **Given** I receive a notification email on my desktop
- **When** I open the email
- **Then** the email layout is visually appealing on desktop
- **And** images and formatting display correctly
- **And** links and buttons work as expected


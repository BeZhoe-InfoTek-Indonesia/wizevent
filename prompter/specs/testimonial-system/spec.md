# testimonial-system Specification

## Purpose
TBD - created by archiving change develop-testimonial-system. Update Purpose after archive.
## Requirements
### Requirement: Automated Feedback Collection
The system MUST trigger a feedback invitation to attendees 24 hours after an event concludes.

#### Scenario: Post-event notification trigger
- **GIVEN** an event ended at 8:00 PM yesterday
- **AND** a user purchased a ticket and checked in
- **WHEN** the scheduled task runs
- **THEN** the user receives an email/WhatsApp with a unique link to the review page

### Requirement: Verified Review Submission
The system MUST restrict review submissions to users who purchased a valid ticket and were checked in at the event.

#### Scenario: Unauthorized review attempt
- **GIVEN** I am a logged-in user who did NOT purchase a ticket for Event A
- **WHEN** I attempt to access the review page for Event A
- **THEN** I am redirected to the event page with an error message

#### Scenario: Successful review submission
- **GIVEN** I am a logged-in user who attended Event A
- **WHEN** I submit my review with a 4-star rating, a message, and an optional photo
- **THEN** the review is saved in a "Pending" status
- **AND** I see a success message

### Requirement: Admin Moderation Panel
Organizers/Admins MUST be able to review, approve, reject, and feature testimonials via the management dashboard.

#### Scenario: Admin approves a testimonial
- **GIVEN** there is a pending testimonial from User X
- **WHEN** the Admin clicks "Approve" in the dashboard
- **THEN** the testimonial status changes to "Published"
- **AND** it becomes visible on the public event page

#### Scenario: Admin features a testimonial
- **WHEN** the Admin marks a published testimonial as "Featured"
- **THEN** it is displayed at the top of the testimonial list on the event page

### Requirement: Testimonial Data Integrity
The system MUST track rating, content, and optional media for each testimonial.

#### Scenario: Database schema enhancement
- **WHEN** a testimonial is created
- **THEN** it stores `rating` (1-5), `content` (text), `image_path` (nullable string), `is_published` (boolean), and `is_featured` (boolean)


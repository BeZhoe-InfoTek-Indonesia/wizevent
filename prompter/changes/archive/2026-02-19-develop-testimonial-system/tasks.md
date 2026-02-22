# Tasks: Testimonial Management System

## Phase 1: Foundation & Database
- [x] Create migration to add `is_published`, `is_featured`, and `image_path` to `testimonials` table.
- [x] Update `Testimonial` model with new fields and casts.
- [x] Update `Event` and `User` models for relationships if needed.

## Phase 2: Visitor Submission Flow
- [x] Create `TestimonialSubmission` Livewire component.
- [x] Design the review page UI (Star rating, text area, image upload).
- [x] Implement backend validation (ticket valid, checked-in).
- [x] Add route for the review page.

## Phase 3: Automated Notifications
- [x] Implement `sendFeedbackReminder` in `NotificationService`.
- [x] Create `SendTestimonialReminders` scheduled job.
- [x] Create Email notification template for feedback request.
- [x] Implement WhatsApp notification if a provider is configured (using existing service patterns).
- [x] Schedule the job in `routes/console.php` to run daily.

## Phase 4: Admin Moderation
- [x] Create `TestimonialResource` using Filament.
- [x] Implement table columns and filters (Rating, Status, Featured).
- [x] Implement toggle actions for Publish and Featured status.
- [x] Setup dashboard notifications for new submissions.

## Phase 5: Display & Integration
- [x] Update Event Detail page to display approved testimonials.
- [x] Ensure Featured testimonials are prioritized in the list.
- [x] Localization for all new strings (English/Indonesian).

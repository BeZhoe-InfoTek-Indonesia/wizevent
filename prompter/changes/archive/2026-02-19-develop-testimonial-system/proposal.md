# Proposal: Testimonial Management System

## Problem Statement
The application currently lacks a formal way for attendees to provide feedback and for organizers to showcase social proof. This is critical for building trust and improving future events.

## Proposed Solution
Implement a comprehensive testimonial lifecycle:
1.  **Collect**: Automated reminders 24h post-event for checked-in attendees.
2.  **Validate**: Proof of attendance and purchase required.
3.  **Moderate**: Admin dashboard to review, approve, and feature testimonials.
4.  **Display**: Published testimonials on event pages.

## Scope
- Database schema updates for `testimonials`.
- Automated notification job and template.
- Livewire submission form on the visitor side.
- Filament moderation resource in the admin panel.

## Success Criteria
- Attendees receive a reminder email/WA 24h after an event they attended.
- Only verified attendees can submit testimonials.
- Admins can manage testimonials from the dashboard.
- Featured testimonials are displayed prominently.

# Change: Add Social Engagement Features

## Why
Increase event visibility and user engagement by allowing visitors to favorite events, share them on social media, add them to their calendars, and provide/rate testimonials.

## What Changes
- **ADDED**: Event "love"/favorite system (toggle on cards/details, list in dashboard).
- **ADDED**: Social sharing buttons on event detail pages with UTM tracking.
- **ADDED**: ".ics" file generation for Google Calendar integration.
- **ADDED**: Testimonial system including creation, listing, and "helpful/not helpful" voting.
- **MODIFIED**: Event detail page to include sharing, calendar, and testimonials.
- **MODIFIED**: Visitor dashboard to include a "Loved Events" section.

## Impact
- Affected specs: `event-details`, `event-listing`, `visitor-dashboard` (new), `social-engagement` (new).
- Affected code: `Event` model, `User` model, `EventController`, `VisitorDashboardController`, new `Testimonial` and `Favorite` models.

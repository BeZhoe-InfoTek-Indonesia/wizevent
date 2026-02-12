# Design: Social Engagement System

## Context
The application needs features to drive organic growth. This involves social integration and user-generated content (testimonials).

## Goals / Non-Goals
- **Goals**:
    - Allow users to "favorite" events for later viewing.
    - Provide easy social sharing with tracking.
    - Standardized calendar format (.ics).
    - Feedback loop via testimonials and voting.
- **Non-Goals**:
    - Full social media API integration (just share links).
    - Complex recommendation engine.

## Decisions
- **Decision: Polymorphic Testimonials/Loves?**
    - No, we'll keep it simple: `favorites` and `testimonials` will link directly to `events` and `users`.
- **Decision: Share Link Generation**
    - Use a dedicated helper or service to append UTM parameters consistently.
    - `utm_source`: platform (facebook, twitter, etc.)
    - `utm_medium`: social
    - `utm_campaign`: event_share
- **Decision: Calendar Integration**
    - Use `spatie/icalendar-generator` or similar to generate `.ics` files.
- **Decision: Testimonial Voting**
    - `testimonial_votes` table to ensure one vote per user per testimonial.

## Data Model
### `favorites`
- `id`, `user_id`, `event_id`, `created_at`

### `testimonials`
- `id`, `user_id`, `event_id`, `content`, `rating` (1-5), `status` (pending/approved), `created_at`

### `testimonial_votes`
- `id`, `testimonial_id`, `user_id`, `is_helpful` (boolean), `created_at`

## Risks / Trade-offs
- **Spam**: Testimonials will require a `status` field for moderation, although the EPIC doesn't explicitly mention a moderation UI yet, we should have the data structure ready.
- **Privacy**: Sharing links might include user-specific data if not careful. We will keep it anonymous event links.

## Migration Plan
1. Create `favorites` table.
2. Create `testimonials` table.
3. Create `testimonial_votes` table.
4. Update `events` table (optional: add `love_count` cache for performance).

## Open Questions
- Should we allow anonymous favorites? (Decision: No, require login).
- Should we allow anonymous testimonials? (Decision: No, require login and ticket purchase).

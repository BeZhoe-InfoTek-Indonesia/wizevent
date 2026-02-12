# Tasks: Add Social Engagement Features

## Phase 1: Database & Models
- [x] 1.1 Create migration for `favorites` table (user_id, event_id).
- [x] 1.2 Create migration for `testimonials` table (user_id, event_id, content, rating, status).
- [x] 1.3 Create migration for `testimonial_votes` table (testimonial_id, user_id, is_helpful).
- [x] 1.4 Implement `Favorite`, `Testimonial`, and `TestimonialVote` models with relationships.
- [x] 1.5 Add `isLovedBy(User $user)` method to `Event` model.

## Phase 2: Social & Calendar Logic
- [x] 2.1 Create `SocialShareService` to generate UTM-tracked links.
- [x] 2.2 Create `CalendarService` to generate `.ics` file content.
- [x] 2.3 Implement download route/controller for `.ics` files.

## Phase 3: Frontend - Event Details
- [x] 3.1 Add Social Share buttons to `EventDetail` Livewire component.
- [ ] 3.2 Add "Add to Calendar" button.
- [ ] 3.3 Add heart icon toggle for favoriting (Livewire action).
- [ ] 3.4 Implement Testimonials list on event page.
- [ ] 3.5 Implement Testimonial submission form (gated by ticket purchase).
- [ ] 3.6 Implement helpful/not helpful voting toggle on testimonials.

## Phase 4: Frontend - Dashboard
- [ ] 4.1 Create `LovedEventsList` Livewire component.
- [ ] 4.2 Integrate `LovedEventsList` into the visitor dashboard.

## Phase 5: Validation & Polish
- [ ] 5.1 Write unit tests for `SocialShareService` and `CalendarService`.
- [ ] 5.2 Write feature tests for favoriting and testimonial voting.
- [ ] 5.3 Manual verification of `.ics` file in Google Calendar.
- [ ] 5.4 Verify UTM parameters in share links.

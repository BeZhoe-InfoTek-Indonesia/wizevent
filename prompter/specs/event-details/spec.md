# event-details Specification

## Purpose
TBD - created by archiving change implement-event-discovery. Update Purpose after archive.
## Requirements
### Requirement: Detailed Event View
The system MUST provide a dedicated page for each published event displaying comprehensive information, including social engagement features.

#### Scenario: User views an event detail page
- **Given** I am on the public website
- **When** I click "View Tickets" on an event card
- **Then** I should be taken to `/events/{slug}`
- **And** the page title and meta tags (og:title, og:image) should reflect the event
- **And** I should see the full event banner
- **And** I should see:
  - Event title (h1)
  - Date and time
  - Venue name and address
  - Rich text description
  - Ticket types list with prices and availability status
  - Buy tickets button (links to checkout or selection flow)
  - **Social share buttons (WhatsApp, Twitter, Facebook, Email)**
  - **"Add to Calendar" button (generates .ics file)**
  - **"Love/Favorite" heart toggle (must be logged in)**
  - **Testimonials section with ratings and "helpful" voting**

### Requirement: Venue Map
The system MUST display a visual map for events with coordinate data to assist users in locating the venue.

#### Scenario: Map Integration
- **Given** the event has coordinates (lat/lng)
- **Then** I should see an interactive map (e.g., Google Maps or Leaflet) showing the venue location
- **And** a "Get Directions" link

### Requirement: Sold Out Indicator
The system MUST clearly indicate when ticket inventory is depleted, preventing further purchase attempts, while still allowing social engagement.

#### Scenario: Sold Out Event
- **Given** an event has status "Sold Out"
- **Then** the "Buy Tickets" button should be disabled and say "Sold Out"
- **And** a visible badge "SOLD OUT" should be displayed on the page
- **And** social sharing and "Love/Favorite" buttons MUST remain functional

### Requirement: Cancellation Notice
The system MUST prominently display a cancellation notice and restrict purchase actions for cancelled events.

#### Scenario: Event Cancelled
- **Given** an event has status "Cancelled"
- **Then** the "Buy Tickets" button should be hidden
- **And** a banner "Event Cancelled: [Reason]" should be displayed clearly at the top
- **And** the ticket purchasing form remains inaccessible

### Requirement: Social Sharing
The system MUST provide social sharing links for events with UTM tracking.

#### Scenario: User shares an event via WhatsApp
- **WHEN** user clicks the WhatsApp share button
- **THEN** a new window opens with a link to the event
- **AND** the link includes `utm_source=whatsapp&utm_medium=social&utm_campaign=event_share`

### Requirement: Testimonial Management
The system MUST allow users who have attended an event to post testimonials and other users to rate them.

#### Scenario: Attendee posts a testimonial
- **GIVEN** I have a 'used' ticket for the event
- **WHEN** I submit a testimonial with a rating and comment
- **THEN** the testimonial is saved as "pending" for moderation
- **AND** once approved, it appears on the event detail page

#### Scenario: User votes on a testimonial
- **GIVEN** I am logged in
- **WHEN** I click "Helpful" on a testimonial
- **THEN** my vote is recorded
- **AND** I cannot vote more than once per testimonial


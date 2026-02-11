## ADDED Requirements

### Requirement: Event Grid Listing
The system MUST display a responsive grid of published, upcoming events with key summary information (image, title, date, location).

#### Scenario: User visits the public event listings page
- **Given** I navigate to `/events`
- **Then** I should see a grid of upcoming events
- **And** for each event card, I should see:
  - Banners image (medium thumbnail)
  - Event title
  - Event date (formatted: `Day, Mon DD | Time`)
  - Location/Venue
  - "From [Price]" if multiple tickets or single price
  - "Free" if price is 0
- **And** I should see pagination controls if there are many events

### Requirement: Empty State Feedback
The system MUST display a user-friendly message when a search or filter combination yields zero results.

#### Scenario: No events found
- **Given** I search for "NonExistingEvent999"
- **Then** I should see a clear "No events found" message
- **And** I should see a "Clear Search" or "Browse All Events" button

### Requirement: Loading Indicator
The system MUST indicate background processing to the user during asynchronous actions to maintain perceived responsiveness.

#### Scenario: Loading state
- **Given** I am on the events page
- **When** I trigger a search or filter
- **Then** the event grid area should show a loading skeleton or spinner
- **And** the search input should remain interactive
- **And** previous results should be dimmed or replaced once new data arrives

# social-engagement Specification

## Purpose
TBD - created by archiving change add-social-engagement. Update Purpose after archive.
## Requirements
### Requirement: Event Favoriting
The system MUST allow logged-in users to save events to a favorites list.

#### Scenario: User favorites an event
- **GIVEN** I am logged in
- **WHEN** I click the heart icon on an event card or detail page
- **THEN** the event is added to my favorites list
- **AND** the icon state changes to filled

### Requirement: Calendar Integration
The system MUST generate standard iCalendar (.ics) files for event dates.

#### Scenario: User adds event to calendar
- **WHEN** I click "Add to Calendar"
- **THEN** an `.ics` file is downloaded
- **AND** it contains the correct event title, start time, end time, location, and description

### Requirement: Social Sharing UTM Tracking
The system MUST append tracking parameters to shared event links.

#### Scenario: Link generation for social sharing
- **WHEN** a share link is generated for "Twitter"
- **THEN** it includes `utm_source=twitter&utm_medium=social&utm_campaign=event_share`


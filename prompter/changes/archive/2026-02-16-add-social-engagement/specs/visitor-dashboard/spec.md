# visitor-dashboard Specification

## Purpose
Provide a centralized area for users to manage their event-related activities.

## ADDED Requirements

### Requirement: Dashboard Overview
The system MUST provide a dashboard view for logged-in users.

#### Scenario: User visits dashboard
- **WHEN** I navigate to `/dashboard`
- **THEN** I see my recent orders
- **AND** I see my loved events

### Requirement: Loved Events List
The system MUST display a list of events the user has favorited.

#### Scenario: Viewing loved events
- **GIVEN** I have loved 3 events
- **WHEN** I view the "Loved Events" section in my dashboard
- **THEN** I see all 3 events with links to their detail pages

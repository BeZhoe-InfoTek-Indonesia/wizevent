## MODIFIED Requirements

### Requirement: Event Categorization
Events MUST support assignment to categories, tags, and organizers for organization and filtering purposes.

#### Scenario: Assign category to event
**Given** an authenticated user with `events.edit` permission
**When** they assign a category to an event
**Then** category relationship is established
**And** event can be filtered by category

#### Scenario: Assign tags to event
**Given** an authenticated user with `events.edit` permission
**When** they assign multiple tags to an event
**Then** tag relationships are established
**And** event can be filtered by tags

#### Scenario: Assign organizers to event
**Given** an authenticated user with `events.edit` permission
**When** they assign one or more organizers to an event
**Then** organizer relationships are established via `event_organizer` pivot table
**And** event can be filtered by organizer
**And** multiple organizers can be associated with a single event (co-hosting)

#### Scenario: Remove organizer from event
**Given** an authenticated user with `events.edit` permission
**And** an event with associated organizers
**When** they remove an organizer from the event
**Then** organizer relationship is detached
**And** other organizer associations remain intact

#### Scenario: Sync organizers for event
**Given** an authenticated user with `events.edit` permission
**And** an event with existing organizer associations
**When** they use the syncOrganizers() method with a new array of organizer IDs
**Then** organizers not in the array are detached
**And** organizers in the array but not associated are attached
**And** existing associations are maintained
**And** all changes are persisted to the database

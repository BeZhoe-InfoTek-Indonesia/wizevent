# pfa-sync-to-live Specification

## Purpose
Provide a controlled, auditable mechanism to push planned data from the Event Plan workspace to the live Event entity. The "Sync to Live" feature is the bridge between the PLAN and ACTION phases — it creates or updates Event records, ticket types, performer assignments, and rundown entries with diff preview and selective sync.

## ADDED Requirements

### Requirement: Sync to Live Service
The system SHALL provide a `SyncToLiveService` that orchestrates data transfer from an event plan to its linked live event.

#### Scenario: Sync prerequisites
- **GIVEN** an event plan
- **WHEN** the user triggers "Sync to Live"
- **THEN** the system validates:
  - The plan has a linked event (`event_id` is not null)
  - The user has both `event-plans.edit` and `events.edit` permissions
  - The plan status is `active` or `draft`
- **AND** if validation fails, an error message is shown explaining the issue

#### Scenario: Sync to Live without linked event
- **GIVEN** an event plan with `event_id = NULL`
- **WHEN** the user clicks "Sync to Live"
- **THEN** the action is blocked with: "Link this plan to an event first, or create a new event from this plan."
- **AND** an option is presented: "Create Event from Plan" which creates a new draft event and links it

### Requirement: Diff Preview
The system SHALL generate a comparison between current plan data and the live event's current state before syncing.

#### Scenario: Generate diff report
- **GIVEN** an event plan linked to an event
- **WHEN** the user clicks "Sync to Live"
- **THEN** a modal displays a diff report showing:
  - **Concept**: plan description vs event description (changed/unchanged)
  - **Metadata**: plan title/date/location vs event title/date/location (field-by-field)
  - **Performers**: plan talents (confirmed only) vs current `event_performer` entries (added/removed/unchanged)
  - **Ticket Types**: plan pricing tiers vs current `ticket_types` (added/existing)
  - **Rundown**: plan rundown items (preview, noting these are new — no live rundown table exists yet)
- **AND** each section has a checkbox to include/exclude from sync
- **AND** a summary count shows "X items to create, Y items to update, Z items unchanged"

#### Scenario: Selective sync execution
- **GIVEN** a diff report with selected sections
- **WHEN** the user clicks "Confirm Sync"
- **THEN** the system executes the selected syncs within a database transaction:
  - **Concept sync**: Updates `events.description` with `ai_concept_result`; sets `concept_status` to `synced`; sets `concept_synced_at`
  - **Metadata sync**: Updates selected event fields (title, event_date, location, venue_name) from plan
  - **Performer sync**: Creates `event_performer` entries for confirmed plan talents not already linked; does NOT remove existing performers
  - **Ticket sync**: Creates `ticket_type` records from selected pricing scenario tiers; does NOT modify existing ticket types
- **AND** an activity log entry records: "Synced [section names] from Event Plan #{plan_id} to Event #{event_id}"
- **AND** the plan's `status` transitions to `active` if it was `draft`

#### Scenario: Conflict detection for published events
- **GIVEN** an event plan linked to a published event
- **AND** the event's description was manually edited after the last sync
- **WHEN** the diff report is generated
- **THEN** the concept section shows a warning: "Event description was modified after last sync. Syncing will overwrite those changes."
- **AND** the user must explicitly acknowledge the warning before the concept section is included

### Requirement: Create Event from Plan
The system SHALL allow creating a new draft event directly from an event plan that has no linked event.

#### Scenario: Create event from standalone plan
- **GIVEN** an event plan with `event_id = NULL` and status `draft` or `active`
- **AND** the plan has at minimum: title, event_date, and location
- **WHEN** the user clicks "Create Event from Plan"
- **THEN** a new `Event` record is created with:
  - `title` = plan title
  - `event_date` = plan event_date
  - `location` = plan location
  - `description` = plan `ai_concept_result` (if available) or plan `description`
  - `status` = "draft"
  - `created_by` = current user
- **AND** the plan's `event_id` is set to the new event's ID
- **AND** a success notification is shown: "Event created and linked to this plan."
- **AND** an activity log entry is created

### Requirement: Sync Audit Trail
The system SHALL maintain an audit trail of all sync operations.

#### Scenario: Sync history on plan view
- **GIVEN** an event plan that has been synced at least once
- **WHEN** the organizer views the plan
- **THEN** a "Sync History" section shows:
  - Timestamp of each sync
  - User who performed the sync
  - Sections that were synced
  - Event ID that was the target
- **AND** the data is sourced from Spatie Activity Log entries with log_name "sync_to_live"

### Requirement: Sync to Live UI Action
The system SHALL provide a header action on the EventPlan Edit page for "Sync to Live."

#### Scenario: Sync to Live button
- **GIVEN** an authenticated user on the Event Plan edit page
- **AND** the plan has a linked event
- **THEN** a "Sync to Live" button appears in the header actions with icon `heroicon-o-arrow-up-on-square`
- **AND** clicking it opens the diff preview modal
- **AND** the button label uses `__('event_plan.sync_to_live')` translation

#### Scenario: Sync to Live button disabled state
- **GIVEN** an event plan with `event_id = NULL`
- **THEN** the "Sync to Live" button is visible but shows a tooltip: "Link an event first"
- **AND** clicking it offers the "Create Event from Plan" option instead

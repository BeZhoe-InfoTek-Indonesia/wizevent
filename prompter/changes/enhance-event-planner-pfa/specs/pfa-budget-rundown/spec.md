# pfa-budget-rundown Specification

## Purpose
Integrate event rundown/agenda management into the Event Planner, linking timeline items with talent slots and budget line items. Enable AI-assisted rundown generation and time-block visualization.

## ADDED Requirements

### Requirement: Event Plan Rundown Data Model
The system SHALL store time-blocked agenda items for each event plan in a dedicated `event_plan_rundowns` table.

#### Scenario: Database schema for event_plan_rundowns
- **GIVEN** the migration is executed
- **THEN** the `event_plan_rundowns` table contains:
  - `id` (primary key)
  - `event_plan_id` (FK → `event_plans.id`, cascade on delete)
  - `title` (string, required)
  - `description` (text, nullable)
  - `start_time` (time, required)
  - `end_time` (time, required)
  - `type` (string, default 'other') — values: ceremony, performance, break, setup, networking, registration, other
  - `event_plan_talent_id` (FK → `event_plan_talents.id`, nullable, set null on delete)
  - `notes` (text, nullable)
  - `sort_order` (integer, default 0)
  - `created_at`, `updated_at`
  - INDEX on (`event_plan_id`, `sort_order`)

### Requirement: Rundown CRUD within Event Plan
The system SHALL allow organizers to create, edit, reorder, and delete rundown items within an event plan.

#### Scenario: Add rundown item
- **GIVEN** an authenticated user with `event-plans.edit` permission
- **AND** an event plan in `draft` or `active` status
- **WHEN** they add a rundown item via the Rundown relation manager
- **THEN** an `event_plan_rundown` record is created
- **AND** title, start_time, end_time, type, and optional talent link are set

#### Scenario: Link rundown item to talent
- **GIVEN** a rundown item of type "performance"
- **AND** the plan has confirmed talents
- **WHEN** the organizer selects a talent from the dropdown
- **THEN** the `event_plan_talent_id` is set
- **AND** the talent's `slot_time` and `slot_duration_minutes` are auto-populated from the rundown times
- **AND** the display shows the performer name next to the rundown item

#### Scenario: Time overlap validation
- **GIVEN** existing rundown items for a plan
- **WHEN** the organizer creates a new item with start_time/end_time overlapping an existing item
- **THEN** a warning is displayed: "Time overlap detected with [existing item title]"
- **AND** the item is still saved (warning only, not blocking — allows parallel tracks)

#### Scenario: Reorder rundown items
- **GIVEN** multiple rundown items for a plan
- **WHEN** the organizer changes the `sort_order`
- **THEN** items are reordered accordingly
- **AND** the timeline visualization updates to reflect the new order

### Requirement: AI Rundown Generator
The system SHALL generate a suggested rundown/agenda using AI based on event plan parameters and confirmed talents.

#### Scenario: Generate rundown from plan data
- **GIVEN** an event plan with event_category, event_date, target_audience_size
- **AND** optionally: confirmed talents with slot durations
- **WHEN** the user clicks "AI Rundown Generator"
- **THEN** the AI generates a time-blocked agenda including:
  - Registration/check-in period
  - Opening ceremony/welcome
  - Performance slots for confirmed talents (using their slot_duration_minutes)
  - Break periods (appropriate for audience size and event duration)
  - Networking/intermission slots
  - Closing ceremony
- **AND** the result is displayed in a preview modal
- **AND** the user can click "Apply to Rundown" to create `event_plan_rundown` records from the AI output

#### Scenario: AI Rundown with no talents
- **GIVEN** an event plan with no confirmed talents
- **WHEN** the AI Rundown Generator runs
- **THEN** performance slots are generated as placeholder items with generic titles (e.g., "Performance Slot 1", "Performance Slot 2")
- **AND** no `event_plan_talent_id` links are created

#### Scenario: AI Rundown appends to existing items
- **GIVEN** an event plan with existing rundown items
- **WHEN** the user applies AI-generated rundown
- **THEN** existing rundown items are NOT deleted
- **AND** new items are appended with sort_order continuing from the highest existing value
- **AND** a notification confirms "X rundown items added from AI suggestion"

### Requirement: Rundown Relation Manager UI
The system SHALL provide a Filament RelationManager for managing rundown items within the EventPlan Edit page.

#### Scenario: Rundown table display
- **GIVEN** an authenticated user viewing the Event Plan edit page
- **THEN** the Rundown relation manager displays a table with columns:
  - Sort Order (reorderable)
  - Title
  - Start Time — End Time
  - Duration (calculated)
  - Type (badge with color coding)
  - Linked Talent (performer name or "—")
- **AND** the table is sorted by `sort_order` then `start_time`

#### Scenario: Create rundown item via modal
- **WHEN** the organizer clicks "Add Rundown Item"
- **THEN** a modal appears with:
  - Title (text input, required)
  - Start Time (time picker, required)
  - End Time (time picker, required)
  - Type (select with options: ceremony, performance, break, setup, networking, registration, other)
  - Linked Talent (searchable select, shows only plan's talents, optional)
  - Description (textarea, optional)
  - Notes (textarea, optional)
- **AND** all labels and placeholders use `__()` translations

### Requirement: Budget-Rundown Correlation View
The system SHALL provide a consolidated view showing how budget line items correlate with rundown activities and talent costs.

#### Scenario: Budget correlation summary
- **GIVEN** an event plan with budget line items, talents, and rundown items
- **THEN** the budget section shows:
  - Total Talent Fees (sum of `planned_fee` from `event_plan_talents`)
  - Talent Fees as % of Total Budget
  - Non-Talent Expenses (total planned expenses - talent fees)
  - Revenue items (unchanged)
- **AND** talent-linked line items are visually grouped and labeled

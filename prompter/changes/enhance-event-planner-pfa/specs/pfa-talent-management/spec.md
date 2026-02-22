# pfa-talent-management Specification

## Purpose
Enable event organizers to manage performer lineups, fees, time slots, contract status, and rider requirements within the Event Plan — before committing talent to a live event. Talent data correlates with the plan budget and syncs to the live event on demand.

## ADDED Requirements

### Requirement: Event Plan Talent Data Model
The system SHALL store talent assignments per event plan in a dedicated `event_plan_talents` pivot table linking plans to performers.

#### Scenario: Database schema for event_plan_talents
- **GIVEN** the migration is executed
- **THEN** the `event_plan_talents` table contains:
  - `id` (primary key)
  - `event_plan_id` (FK → `event_plans.id`, cascade on delete)
  - `performer_id` (FK → `performers.id`, cascade on delete)
  - `planned_fee` (decimal 15,2, nullable)
  - `actual_fee` (decimal 15,2, nullable)
  - `slot_time` (time, nullable)
  - `slot_duration_minutes` (integer, nullable)
  - `performance_order` (integer, default 0)
  - `contract_status` (string, default 'draft') — values: draft, negotiating, confirmed, cancelled
  - `rider_notes` (text, nullable)
  - `notes` (text, nullable)
  - `budget_line_item_id` (FK → `event_plan_line_items.id`, nullable, set null on delete)
  - `created_at`, `updated_at`
  - UNIQUE constraint on (`event_plan_id`, `performer_id`)

### Requirement: Talent CRUD within Event Plan
The system SHALL allow organizers to add, edit, and remove performers from an event plan with fee and scheduling metadata.

#### Scenario: Add performer to plan
- **GIVEN** an authenticated user with `event-plans.edit` permission
- **AND** an event plan in `draft` or `active` status
- **WHEN** they add a performer via the Talent relation manager
- **THEN** an `event_plan_talent` record is created linking the performer to the plan
- **AND** planned_fee, slot_time, slot_duration_minutes, and performance_order can be set
- **AND** the performer selector shows only active performers (`is_active = true`)

#### Scenario: Edit talent details
- **GIVEN** an existing event plan talent entry
- **WHEN** the organizer updates the planned_fee or slot_time
- **THEN** the changes are saved
- **AND** if a `budget_line_item_id` is linked, the associated line item's `planned_amount` is updated to match the new `planned_fee`

#### Scenario: Remove performer from plan
- **GIVEN** an event plan talent entry
- **WHEN** the organizer removes the performer
- **THEN** the `event_plan_talent` record is deleted
- **AND** the associated budget line item (if any) is NOT deleted but its link is nullified

#### Scenario: Prevent duplicate performer assignment
- **GIVEN** a performer already assigned to an event plan
- **WHEN** the organizer attempts to add the same performer again
- **THEN** the action is blocked with error "This performer is already assigned to this plan."

### Requirement: Contract Status Workflow
The system SHALL track the negotiation lifecycle of each talent engagement.

#### Scenario: Status transitions
- **GIVEN** an event plan talent entry
- **WHEN** the contract_status is updated
- **THEN** the following transitions are valid:
  - `draft` → `negotiating`
  - `negotiating` → `confirmed`
  - `negotiating` → `cancelled`
  - `confirmed` → `cancelled`
  - `draft` → `cancelled`
- **AND** a status badge displays the current state with appropriate colors (draft=gray, negotiating=warning, confirmed=success, cancelled=danger)

### Requirement: Talent-Budget Correlation
The system SHALL optionally link each talent entry to a budget line item so that talent fees are reflected in the overall budget.

#### Scenario: Auto-create budget line item for talent
- **GIVEN** a talent entry with `planned_fee` set and no `budget_line_item_id`
- **WHEN** the organizer clicks "Link to Budget"
- **THEN** a new `event_plan_line_item` is created with:
  - `category` = "Talent"
  - `description` = performer name
  - `type` = "expense"
  - `planned_amount` = talent's `planned_fee`
- **AND** the talent's `budget_line_item_id` is set to the new line item
- **AND** the plan's total planned expenses are recalculated

#### Scenario: Fee change propagates to budget
- **GIVEN** a talent entry linked to a budget line item
- **WHEN** the organizer changes the `planned_fee`
- **THEN** the linked line item's `planned_amount` is updated to the new fee value
- **AND** the plan's total planned expenses are recalculated

#### Scenario: Actual fee tracking
- **GIVEN** a talent entry with `actual_fee` set
- **AND** a linked budget line item
- **WHEN** the `actual_fee` is updated
- **THEN** the linked line item's `actual_amount` is updated to match
- **AND** the variance is recalculated

### Requirement: Talent Relation Manager UI
The system SHALL provide a Filament RelationManager for managing talents within the EventPlan Edit page.

#### Scenario: Talent table display
- **GIVEN** an authenticated user viewing the Event Plan edit page
- **THEN** the Talent relation manager displays a table with columns:
  - Performer name (with photo thumbnail)
  - Planned Fee (formatted as IDR currency)
  - Actual Fee (formatted as IDR currency)
  - Slot Time
  - Duration (minutes)
  - Contract Status (badge)
  - Budget Linked (boolean icon)
- **AND** the table supports inline editing of contract_status

#### Scenario: Create talent via modal
- **WHEN** the organizer clicks "Add Talent" in the relation manager
- **THEN** a modal appears with:
  - Performer select (searchable, shows active performers only)
  - Planned Fee (money input, IDR locale)
  - Slot Time (time picker)
  - Slot Duration (numeric, minutes)
  - Performance Order (numeric)
  - Contract Status (select)
  - Rider Notes (textarea)
  - Notes (textarea)
- **AND** all labels and placeholders use `__()` translations

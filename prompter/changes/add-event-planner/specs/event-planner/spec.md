# event-planner Specification

## Purpose
Provide a structured, AI-assisted planning workspace where event organizers can draft event concepts, model budgets, configure pricing strategies, and assess risks before committing to a live event.

## ADDED Requirements

### Requirement: Event Plan CRUD
The system SHALL allow Super Admin users to create, read, update, and delete Event Plans as persistent planning workspaces. All `event-plans.*` permissions are assigned exclusively to the Super Admin role.

#### Scenario: Create a new event plan
- **GIVEN** an authenticated user with `event-plans.create` permission
- **WHEN** they navigate to "Event Planner" and click "Create Plan"
- **THEN** a new `event_plan` record is created with status `draft`
- **AND** the plan is associated with the creating user via `created_by`

#### Scenario: Create event plan linked to existing event
- **GIVEN** an authenticated user with `event-plans.create` permission
- **AND** an existing event in `draft` status
- **WHEN** they create a plan and select the event from a dropdown
- **THEN** the plan's `event_id` FK is set to the selected event
- **AND** basic information (title, description, date, location) is pre-filled from the event

#### Scenario: Create standalone concept plan
- **GIVEN** an authenticated user with `event-plans.create` permission
- **WHEN** they create a plan without selecting an existing event
- **THEN** the plan is created with `event_id = NULL`
- **AND** the plan title, description, and other fields are entered manually

#### Scenario: Edit event plan
- **GIVEN** an authenticated user with `event-plans.edit` permission
- **AND** an existing event plan in `draft` or `active` status
- **WHEN** they update plan fields (title, budget target, revenue target, notes)
- **THEN** the changes are saved
- **AND** `updated_by` and `updated_at` are updated

#### Scenario: Delete event plan
- **GIVEN** an authenticated user with `event-plans.delete` permission
- **AND** an event plan in `draft` status
- **WHEN** they delete the plan
- **THEN** the plan is soft-deleted
- **AND** all associated line items are soft-deleted (cascade)

#### Scenario: Cannot delete active plan with linked event
- **GIVEN** an event plan in `active` status linked to a published event
- **WHEN** the user attempts to delete the plan
- **THEN** deletion is blocked with error "Cannot delete an active plan linked to a published event. Archive it instead."

### Requirement: Event Plan Status Workflow
Event plans SHALL follow a defined status workflow to track planning progress.

#### Scenario: Status transitions
- **GIVEN** an event plan
- **WHEN** the status is updated
- **THEN** the following transitions are valid:
  - `draft` → `active` (when organizer begins executing the plan)
  - `active` → `completed` (when the linked event ends)
  - `active` → `archived` (when organizer manually archives)
  - `draft` → `archived` (when organizer abandons the plan)
- **AND** invalid transitions are blocked with a validation error

### Requirement: Budget Line Items
The system SHALL allow organizers to define granular budget line items within an event plan.

#### Scenario: Add expense line item
- **GIVEN** an authenticated user editing an event plan
- **WHEN** they add a line item with category "Venue", description "Main Hall Rental", planned_amount = 5000, type = "expense"
- **THEN** the line item is created and linked to the event plan
- **AND** the plan's total planned expenses are recalculated

#### Scenario: Add revenue line item
- **GIVEN** an authenticated user editing an event plan
- **WHEN** they add a line item with category "Sponsorship", description "Gold Sponsor Package", planned_amount = 10000, type = "revenue"
- **THEN** the line item is created
- **AND** the plan's total planned revenue is recalculated

#### Scenario: Update actual amounts
- **GIVEN** an event plan in `active` status
- **WHEN** the organizer enters an actual_amount for a line item
- **THEN** the actual amount is saved
- **AND** the plan's total actual expenses/revenue are recalculated
- **AND** the variance (planned - actual) is displayed

### Requirement: Navigation Placement
The Event Planner SHALL be the first sub-menu item under the "Event Management" navigation group.

#### Scenario: Navigation ordering
- **GIVEN** an authenticated user accessing the admin panel
- **WHEN** they expand the "Event Management" navigation group
- **THEN** "Event Planner" appears as the first item in the group
- **AND** it is positioned above "Events" and other existing sub-menus

### Requirement: Event Plan Data Model
The `event_plans` table SHALL store the following attributes.

#### Scenario: Required schema fields
- **GIVEN** the `event_plans` migration is executed
- **THEN** the table contains:
  - `id` (primary key)
  - `event_id` (nullable FK → `events.id`)
  - `title` (string, required)
  - `description` (text, nullable)
  - `event_category` (string, nullable — for AI context)
  - `target_audience_size` (integer, nullable)
  - `target_audience_description` (text, nullable)
  - `budget_target` (decimal 15,2, nullable)
  - `revenue_target` (decimal 15,2, nullable)
  - `event_date` (date, nullable)
  - `location` (string, nullable)
  - `status` (enum: draft, active, completed, archived — default: draft)
  - `ai_concept_result` (longText, nullable — cached AI output)
  - `ai_budget_result` (JSON, nullable — cached AI output)
  - `ai_pricing_result` (JSON, nullable — cached AI output)
  - `ai_risk_result` (JSON, nullable — cached AI output)
  - `notes` (text, nullable)
  - `created_by` (FK → `users.id`)
  - `updated_by` (FK → `users.id`, nullable)
  - `deleted_at`, `created_at`, `updated_at`

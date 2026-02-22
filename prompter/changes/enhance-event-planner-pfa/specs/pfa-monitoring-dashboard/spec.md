# pfa-monitoring-dashboard Specification

## Purpose
Provide a unified real-time monitoring dashboard on the Event Plan View page that consolidates talent pipeline status, rundown progress, sales velocity by phase, and enhanced financial KPIs with alert indicators.

## ADDED Requirements

### Requirement: Talent Status Board Widget
The system SHALL display a kanban-style talent pipeline overview showing performers grouped by contract status.

#### Scenario: Talent status board display
- **GIVEN** an event plan with talent assignments
- **WHEN** the organizer views the Event Plan view page
- **THEN** the Talent Status Board widget shows:
  - Four columns: Draft, Negotiating, Confirmed, Cancelled
  - Each column lists performers with: name, photo thumbnail, planned_fee
  - Column headers show count (e.g., "Confirmed (3)")
  - Total confirmed fees displayed at the bottom
- **AND** empty columns show "No performers in this stage"

#### Scenario: Talent cost summary
- **GIVEN** an event plan with confirmed talents
- **THEN** the widget footer shows:
  - Total Planned Talent Fees (sum of all `planned_fee`)
  - Total Confirmed Fees (sum of `planned_fee` where `contract_status = confirmed`)
  - Talent Budget Utilization (confirmed fees / budget target × 100%)

### Requirement: Rundown Timeline Widget
The system SHALL display a visual timeline of the event agenda from the plan's rundown items.

#### Scenario: Timeline visualization
- **GIVEN** an event plan with rundown items
- **WHEN** the organizer views the Event Plan view page
- **THEN** the Rundown Timeline widget displays:
  - A vertical timeline with each rundown item as a card
  - Time range (start_time — end_time) on the left
  - Title, type badge, and linked talent name on the right
  - Color coding by type (performance=purple, break=gray, ceremony=gold, setup=blue, networking=green, registration=teal, other=default)
- **AND** items are sorted by `sort_order` then `start_time`

#### Scenario: Empty rundown state
- **GIVEN** an event plan with no rundown items
- **THEN** the widget shows: "No rundown items yet. Use the AI Rundown Generator to get started."

### Requirement: Sales Phase Tracker Widget
The system SHALL display ticket sales progress broken down by ticket type (sales phase) for plans linked to live events.

#### Scenario: Sales phase tracker display
- **GIVEN** an event plan linked to an event with ticket types and completed orders
- **WHEN** the organizer views the Event Plan view page
- **THEN** the Sales Phase Tracker widget shows for each ticket type:
  - Ticket type name
  - Sales window (start — end dates)
  - Phase status badge: "Upcoming" (before sales_start), "Active" (within window), "Ended" (after sales_end)
  - Progress bar: tickets sold / total quantity
  - Revenue earned (sum of `order_items.total_price` for completed orders)
  - Fill rate percentage with color coding (green >70%, amber 40-70%, red <40%)

#### Scenario: No linked event
- **GIVEN** an event plan with `event_id = NULL`
- **THEN** the Sales Phase Tracker widget shows: "Link an event to track live sales data."

#### Scenario: No sales data yet
- **GIVEN** an event plan linked to an event with ticket types but no completed orders
- **THEN** the widget shows ticket types with 0% fill rate and "No sales yet" message per type

### Requirement: Enhanced KPI Stats (extends PlanningVsRealizationWidget)
The existing Planning vs Realization widget SHALL be enhanced with additional KPIs and alert indicators.

#### Scenario: Additional KPI stats
- **GIVEN** the existing PlanningVsRealizationWidget on the View page
- **THEN** the following stats are added:
  - **Talent Confirmation Rate**: confirmed talents / total talents × 100%
  - **Rundown Completeness**: rundown items count > 0 indicator
  - **Days Until Event**: countdown from today to `event_date`
- **AND** existing KPIs (Revenue Achievement Rate, Budget Utilization Rate, Net Margin, Tickets Sold vs Target) remain unchanged

#### Scenario: Alert indicators
- **GIVEN** KPI values that cross thresholds
- **THEN** alerts are shown as colored badges:
  - Budget Utilization > 100%: red "Over Budget" badge
  - Revenue Achievement < 50%: red "Low Revenue" badge  
  - Days Until Event < 14 and Talent Confirmation Rate < 80%: amber "Unconfirmed Talent" alert
  - Days Until Event < 7 and Rundown Completeness = false: red "No Rundown" alert
- **AND** alerts appear as description text under the affected stat card

### Requirement: Widget Layout on View Page
The system SHALL arrange all widgets on the Event Plan View page in a logical, scannable layout.

#### Scenario: Widget arrangement
- **GIVEN** the Event Plan view page with all widgets
- **THEN** widgets are arranged as:
  - **Row 1**: Enhanced KPI Stats (full width, 4-6 stat cards)
  - **Row 2**: Revenue Comparison Chart (left half) + Expense by Category Chart (right half)
  - **Row 3**: Sales Phase Tracker (full width)
  - **Row 4**: Talent Status Board (left half) + Rundown Timeline (right half)
- **AND** all widgets respect Filament's responsive grid system
- **AND** widgets with no data show appropriate empty states

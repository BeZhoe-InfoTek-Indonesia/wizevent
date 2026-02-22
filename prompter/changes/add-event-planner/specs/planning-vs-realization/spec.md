# planning-vs-realization Specification

## Purpose
Provide a financial tracking module that compares planned budgets and revenue targets against real-time actuals, giving organizers actionable visibility into their event's financial health.

## ADDED Requirements

### Requirement: Planning Data Aggregation
The system SHALL aggregate planning data from event plans and their line items into summary metrics.

#### Scenario: Calculate planned totals
- **GIVEN** an event plan with multiple line items
- **WHEN** the planning-vs-realization view is loaded
- **THEN** the system calculates:
  - Total Planned Revenue = SUM of line items where type = 'revenue'
  - Total Planned Expenses = SUM of line items where type = 'expense'
  - Planned Net Profit = Total Planned Revenue - Total Planned Expenses
- **AND** these are displayed as summary cards at the top of the view

#### Scenario: Planning data includes revenue target
- **GIVEN** an event plan with a `revenue_target` set
- **WHEN** the planning view is loaded
- **THEN** the revenue_target is displayed alongside the calculated planned revenue
- **AND** a variance indicator shows whether line-item revenue exceeds or falls short of the target

### Requirement: Realization Data Aggregation
The system SHALL pull real-time realization data from the transaction system for events linked to plans.

#### Scenario: Revenue realization from orders
- **GIVEN** an event plan linked to an event (`event_id` is set)
- **AND** the event has paid orders
- **WHEN** the realization view is loaded
- **THEN** Actual Revenue = SUM of `orders.total_amount` WHERE `orders.event_id` = plan's `event_id` AND `orders.status` = 'paid'
- **AND** Actual Tickets Sold = SUM of `order_items.quantity` for those orders

#### Scenario: Expense realization from line items
- **GIVEN** an event plan with line items where `actual_amount` has been entered
- **WHEN** the realization view is loaded
- **THEN** Actual Expenses = SUM of `event_plan_line_items.actual_amount` WHERE type = 'expense'
- **AND** Actual Net Profit = Actual Revenue - Actual Expenses

#### Scenario: No linked event
- **GIVEN** an event plan with `event_id = NULL`
- **WHEN** the realization view is loaded
- **THEN** Revenue Realization shows "No linked event — link an event to track revenue"
- **AND** only expense actuals (manually entered) are available

### Requirement: Comparison Dashboard
The system SHALL display a side-by-side comparison of planned vs actual financial data.

#### Scenario: Revenue comparison chart
- **GIVEN** an active event plan linked to an event with orders
- **WHEN** the comparison dashboard is rendered
- **THEN** a bar or area chart displays:
  - Planned Revenue (from line items)
  - Actual Revenue (from paid orders)
  - Revenue Target (from `revenue_target` field)
- **AND** the chart uses distinct colors for each series (e.g., teal for plan, purple for actual, dashed for target)

#### Scenario: Expense comparison by category
- **GIVEN** an event plan with categorized expense line items
- **WHEN** the comparison dashboard is rendered
- **THEN** expenses are grouped by category (Venue, Talent, Security, etc.)
- **AND** each category shows planned vs actual amounts
- **AND** variances are color-coded (green = under budget, red = over budget)

#### Scenario: Monthly breakdown chart
- **GIVEN** an event plan spanning multiple months
- **WHEN** the comparison dashboard is rendered
- **THEN** a line chart shows planned vs actual revenue by month
- **AND** this enables trend analysis for progressive ticket sales

#### Scenario: Summary KPIs
- **GIVEN** complete planning and realization data
- **WHEN** the comparison dashboard is rendered
- **THEN** the following KPIs are displayed:
  - Revenue Achievement Rate = (Actual Revenue / Planned Revenue) × 100%
  - Budget Utilization Rate = (Actual Expenses / Planned Expenses) × 100%
  - Net Margin = ((Actual Revenue - Actual Expenses) / Actual Revenue) × 100%
  - Tickets Sold vs Target = Actual tickets / target_audience_size
- **AND** each KPI has a color-coded status indicator

### Requirement: Real-Time Updates
The realization data SHALL reflect the latest transaction state on page load.

#### Scenario: New order impacts realization
- **GIVEN** a visitor purchases tickets for the linked event
- **AND** payment is verified and order status becomes `paid`
- **WHEN** the organizer refreshes the planning-vs-realization dashboard
- **THEN** the Actual Revenue and Tickets Sold figures are updated to include the new order
- **AND** KPIs and charts reflect the updated values

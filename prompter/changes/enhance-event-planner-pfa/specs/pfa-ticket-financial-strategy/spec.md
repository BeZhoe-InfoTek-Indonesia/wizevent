# pfa-ticket-financial-strategy Specification

## Purpose
Enhance the ticket and financial strategy module to support multi-scenario pricing (pessimistic/realistic/optimistic), sales-phase tracking, and tighter integration between AI-generated pricing and the financial monitoring dashboard.

## MODIFIED Requirements

### Requirement: Multi-Scenario Pricing Strategy (extends Dynamic Pricing Strategy)
The system SHALL generate and store three pricing scenarios (pessimistic, realistic, optimistic) from a single AI call, allowing organizers to compare and select the best-fit strategy.

#### Scenario: Generate multi-scenario pricing
- **GIVEN** an event plan with revenue_target, target_audience_size, and event_category
- **WHEN** the user clicks "AI Pricing Strategy"
- **THEN** the AI generates three scenarios:
  - **Pessimistic** (60-70% capacity utilization, conservative pricing)
  - **Realistic** (80-90% capacity utilization, market-rate pricing)
  - **Optimistic** (95-100% capacity utilization, premium pricing)
- **AND** each scenario contains the existing tier structure (name, price, quantity, sales window, projected revenue)
- **AND** all three scenarios are stored in `ai_pricing_result` JSON as `{ "scenarios": { "pessimistic": {...}, "realistic": {...}, "optimistic": {...} }, "selected_scenario": "realistic" }`
- **AND** the UI displays a tabbed view with the three scenarios

#### Scenario: Select active scenario
- **GIVEN** a generated multi-scenario pricing result
- **WHEN** the organizer clicks "Select This Scenario" on one of the tabs
- **THEN** the `selected_scenario` key in `ai_pricing_result` is updated
- **AND** the "Apply to Event Tickets" action uses only the selected scenario's tiers

#### Scenario: Revenue target comparison per scenario
- **GIVEN** three pricing scenarios
- **THEN** each scenario tab displays:
  - Total projected revenue
  - Revenue vs target indicator (green = met, amber = within 10% deficit, red = >10% deficit)
  - Fill rate percentage
  - Average ticket price

### Requirement: Sales Phase Tracking
The system SHALL track ticket sales progress by phase (Early Bird, Presale, General Admission, etc.) for plans linked to live events with active ticket sales.

#### Scenario: Sales phase data aggregation
- **GIVEN** an event plan linked to an event with ticket types
- **AND** the ticket types have `sales_start_at` and `sales_end_at` defining phases
- **WHEN** the monitoring dashboard is viewed
- **THEN** the system aggregates:
  - Tickets sold per ticket type (from `order_items` where `orders.status = completed`)
  - Revenue earned per ticket type
  - Fill rate per ticket type (sold / quantity)
  - Current active phase (based on current datetime vs sales windows)
- **AND** data is displayed in the Sales Phase Tracker widget

#### Scenario: Phase progress indicators
- **GIVEN** a ticket type with quantity 100 and 75 sold
- **THEN** the progress bar shows 75% fill rate
- **AND** color coding: green (>70%), amber (40-70%), red (<40%)
- **AND** the "Active" badge is shown if the current date falls within the sales window

### Requirement: Financial Summary Card
The system SHALL display a consolidated financial summary comparing the selected pricing scenario projections against actual ticket revenue.

#### Scenario: Financial summary display
- **GIVEN** an event plan with selected pricing scenario and linked event with sales data
- **THEN** the financial summary card shows:
  - Projected Revenue (from selected scenario)
  - Actual Revenue (from completed orders)
  - Revenue Achievement Rate (actual / projected × 100%)
  - Budget Utilization (actual expenses / budget target × 100%)
  - Projected Net Profit (projected revenue - budget target)
  - Actual Net Profit (actual revenue - actual expenses)
- **AND** trend arrows indicate improvement or decline compared to previous data points

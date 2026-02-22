# Change: Add Event Planner — AI-Powered Draft & Planning Module

## Why

Event organizers currently jump straight from event creation to publishing with no structured planning stage. There is no way to forecast budgets, stress-test pricing strategies, or evaluate risk _before_ committing to a live event. This leads to unoptimized ticket prices, budget overruns, and avoidable operational issues (e.g., weather, audience mismatch). Introducing an "Event Planner" sub-module gives organizers a strategic playground that reduces input errors, improves financial outcomes, and leverages the existing AI infrastructure (`AiService` + Gemini/OpenAI) already in place.

## What Changes

### New Capability: `event-planner`
- **Event Planner Filament Resource** — First sub-menu item under "Event Management" navigation group, titled "Event Planner."
- **`event_plans` table** — Stores draft planning data: budget estimates, revenue targets, pricing strategy snapshots, risk assessment results, and links to the parent `Event` (nullable for pure-concept drafts).
- **`event_plan_line_items` table** — Individual cost/revenue line items for granular budget tracking.

### New Capability: `ai-planning-tools`
- **AI Concept Builder** — Extends the existing `AiService::generateDescription()` pattern to produce polished event concepts from rough notes (title, vibe, target audience, rough budget).
- **AI Budget Forecaster** — Estimates operational costs (venue, talent, security, logistics) based on event category, target audience size, and location.
- **Dynamic Pricing Strategy** — Suggests ticket tier structures (Early Bird, Presale, General, VIP) with price points calibrated against a user-defined revenue target.
- **Risk Assessment Tool** — Evaluates the plan for potential risks (weather, audience-target mismatch, budget gaps, timeline compression) and rates risk severity.

### New Capability: `planning-vs-realization`
- **Planning Data Source** — Reads budget estimates and revenue targets from `event_plans` / `event_plan_line_items`.
- **Realization Data Source** — Pulls real-time actuals from `orders` (status = `paid`) and `order_items` for ticket revenue, and from `event_plan_line_items` where `is_actual = true` for expense actuals.
- **Comparison Dashboard** — Side-by-side or overlay chart comparing planned vs actual revenue and expenses, displayed as a Filament widget on the Event Planner detail page.

### Modified Capability: `event-management`
- Events gain an optional `hasOne EventPlan` relationship for linking a plan to a published event.
- No schema changes on the `events` table itself (relationship is owned by `event_plans.event_id`).

## Access Control
- **Super Admin only** — all Event Planner permissions (`event-plans.view`, `event-plans.create`, `event-plans.edit`, `event-plans.delete`) are assigned exclusively to the Super Admin role.
- Event Manager and other roles do NOT have access to the Event Planner module.`

## AI Pricing → Ticket Type Population
- When AI generates a pricing strategy, the user can click "Apply to Event Tickets" to automatically create/update `TicketType` records on the linked event.
- This creates new ticket types matching the AI-suggested tiers (name, price, quantity, sales window).
- Existing ticket types on the event are NOT deleted or modified — new tiers are appended.
- The user is shown a confirmation dialog listing the ticket types that will be created before applying.

## Impact
- **Affected specs**: `event-management` (MODIFIED — add relationship), `ticket-type-management` (MODIFIED — AI-driven creation), plus 3 new specs
- **Affected code**:
  - `app/Models/EventPlan.php`, `app/Models/EventPlanLineItem.php` (new models)
  - `app/Filament/Resources/EventPlanResource.php` (new Filament resource)
  - `app/Services/AiService.php` (extended with new methods)
  - `app/Services/BudgetForecastService.php` (new service)
  - `app/Services/PricingStrategyService.php` (new service)
  - `app/Services/RiskAssessmentService.php` (new service)
  - `database/migrations/` (2 new migrations)
  - Navigation group ordering in `AdminPanelProvider.php`
- **No breaking changes** — purely additive
- **Dependencies**: Existing `AiService` (Gemini/OpenAI), existing `Order`/`OrderItem`/`TicketType` models

## Context

The Event Ticket Management platform currently allows organizers to create events and configure tickets through a Filament wizard, then publish directly. There is no structured "pre-publish" phase where organizers can draft concepts, model budgets, optimize pricing, or assess risks. The existing `AiService` already supports Gemini and OpenAI with a fallback-mock pattern, and the Revenue Calculator (EPIC-009) provides a read-only "what-if" modal — but neither offers persistent planning or planning-vs-realization tracking.

## Goals / Non-Goals

### Goals
- Provide a persistent planning workspace (CRUD) that organizers can iterate on before linking to a real event.
- Leverage the existing AI stack (`AiService`, Gemini, OpenAI) to offer concept building, budget forecasting, pricing strategy, and risk assessment — all accessible as Filament actions.
- Introduce a `planning-vs-realization` comparison module that pulls planned data from `event_plans`/`event_plan_line_items` and realization data from `orders`/`order_items`.
- Integrate cleanly as the first sub-menu under the "Event Management" navigation group.

### Non-Goals
- Real-time push updates (WebSocket) for the realization dashboard — polling or page-refresh is sufficient for MVP.
- Replacing the existing Revenue Calculator modal on the Event list page — both can coexist.
- Implementing AI model fine-tuning or custom training.
- Full budget reconciliation or accounting-grade ledger — this is for strategic insight, not bookkeeping.

## Decisions

### 1. Separate `event_plans` table instead of adding columns to `events`
- **Decision**: Create a dedicated `event_plans` table with an optional FK to `events.id`.
- **Rationale**: Decouples planning data from the core event schema, allowing plans to exist without a published event. Organizers can create plans as "concept drafts" before committing to event creation.
- **Alternatives considered**: Adding JSON columns to `events` — rejected because it prevents proper querying, indexing, and relationship management.

### 2. Line-item granularity for budgets
- **Decision**: Create `event_plan_line_items` table with `category`, `description`, `planned_amount`, `actual_amount`, `type` (expense or revenue).
- **Rationale**: Enables granular budget tracking and the planning-vs-realization comparison at the line-item level, not just totals.
- **Alternatives considered**: Single JSON column for budget items — rejected for the same indexing/querying concerns.

### 3. AI methods as extensions on `AiService`
- **Decision**: Add new public methods to `AiService` (`generateBudgetForecast()`, `suggestPricingStrategy()`, `assessRisks()`) that follow the same Gemini → OpenAI → mock fallback pattern.
- **Rationale**: Consistent architecture, single configuration point, reuses error handling and logging.
- **Alternatives considered**: Separate AI services per tool — rejected because the underlying HTTP client logic and provider rotation are identical.

### 4. Dedicated service classes for business logic
- **Decision**: `BudgetForecastService`, `PricingStrategyService`, `RiskAssessmentService` wrap the AI calls and apply domain-specific validation, defaults, and formatting.
- **Rationale**: Keeps `AiService` focused on AI provider communication while domain services handle business rules (e.g., "Early Bird must be at least 15% cheaper than General").

### 5. Filament Resource with custom pages
- **Decision**: `EventPlanResource` with List, Create, Edit, and a custom `ViewEventPlan` page that renders the planning-vs-realization dashboard.
- **Rationale**: Follows existing Filament conventions in the project. The view page provides a natural home for the comparison chart and AI action buttons.

### 6. Planning-vs-Realization data flow
- **Decision**: Planning data comes from `event_plan_line_items`, realization data comes from `orders` (paid) + `order_items` for revenue, and from `event_plan_line_items.actual_amount` for expenses (manually entered or updated).
- **Rationale**: Revenue realization is already tracked automatically via the order system. Expense realization requires manual input since the platform doesn't process expense payments.

## Risks / Trade-offs

- **AI API costs**: Each AI action triggers an API call. → Mitigation: Results are cached on the `event_plans` record (e.g., `ai_concept_result`, `ai_budget_result`). Users must explicitly click "Regenerate" to trigger a new call.
- **Mock fallback quality**: If no AI key is configured, the mock responses may not be useful for real planning. → Mitigation: Mock responses are clearly labeled as "demo data" and the UI shows a warning badge.
- **Expense tracking is manual**: The platform doesn't handle expense payments, so actual expenses must be entered manually. → Mitigation: Clear UX with "Update Actuals" inline editing on line items.

## Migration Plan

1. Create `event_plans` migration.
2. Create `event_plan_line_items` migration.
3. Run `php artisan migrate`.
4. No data migration needed (greenfield feature).
5. Rollback: `php artisan migrate:rollback --step=2`.

## Resolved Questions

1. **Access control**: Event Planner is accessible only to **Super Admin**. Permissions are assigned exclusively to the Super Admin role. Event Manager and other roles do not have access.
2. **AI pricing → Ticket Types**: AI-generated pricing suggestions CAN automatically populate ticket types on the linked event. New `TicketType` records are created matching the AI-suggested tiers (name, price, quantity, sales window). Existing ticket types are NOT modified or deleted — new tiers are appended. A confirmation dialog is shown before applying.
3. **Chart library**: Use Filament's built-in `ChartWidget` with Chart.js (consistent with the rest of the project).

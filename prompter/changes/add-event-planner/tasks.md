## 1. Database & Models
- [x] 1.1 Create migration: `event_plans` table (see `event-planner` spec for schema)
- [x] 1.2 Create migration: `event_plan_line_items` table (`id`, `event_plan_id` FK, `category` string, `description` string, `type` enum[expense,revenue], `planned_amount` decimal(15,2), `actual_amount` decimal(15,2) nullable, `notes` text nullable, `sort_order` int default 0, `deleted_at`, `created_at`, `updated_at`)
- [x] 1.3 Create `EventPlan` model with relationships (`belongsTo Event`, `belongsTo User` creator/updater, `hasMany EventPlanLineItem`), soft deletes, and activity logging
- [x] 1.4 Create `EventPlanLineItem` model with relationships (`belongsTo EventPlan`), soft deletes
- [x] 1.5 Add `hasOne EventPlan` relationship to `Event` model
- [x] 1.6 Run migrations and generate IDE helper

## 2. Permissions & Authorization
- [x] 2.1 Add permissions: `event-plans.view`, `event-plans.create`, `event-plans.edit`, `event-plans.delete`
- [x] 2.2 Assign permissions exclusively to Super Admin role via seeder update (no Event Manager access)
- [x] 2.3 Create `EventPlanPolicy` with authorization checks

## 3. Filament Resource — Event Planner
- [x] 3.1 Create `EventPlanResource` with List, Create, Edit pages
- [x] 3.2 Configure navigation: first item under "Event Management" group with `sort = -1`
- [x] 3.3 Implement form schema: title, description, event_category, target_audience_size, target_audience_description, budget_target, revenue_target, event_date, location, event selector (optional), notes, status
- [x] 3.4 Implement table columns: title, status badge, event link, budget_target, revenue_target, created_at
- [x] 3.5 Implement table filters: status, has linked event
- [x] 3.6 Add line items management via Filament Repeater or RelationManager on Edit page
- [x] 3.7 Add i18n translation keys for all labels

## 4. AI Planning Tools — Service Layer
- [x] 4.1 Extend `AiService` with `generateConcept(array $data): ?string` method (follows Gemini → OpenAI → mock pattern)
- [x] 4.2 Extend `AiService` with `generateBudgetForecast(array $data): ?array` method
- [x] 4.3 Extend `AiService` with `suggestPricingStrategy(array $data): ?array` method
- [x] 4.4 Extend `AiService` with `assessRisks(array $data): ?array` method
- [x] 4.5 Create `BudgetForecastService` — wraps AI call, validates inputs, formats output, populates line items
- [x] 4.6 Create `PricingStrategyService` — wraps AI call, computes revenue projections, validates against target
- [x] 4.7 Create `RiskAssessmentService` — wraps AI call, computes overall risk score, ranks risks by severity

## 5. AI Planning Tools — Filament Actions
- [x] 5.1 Add "AI Concept Builder" header action on Edit page — triggers `AiService::generateConcept()`, stores result, shows preview in modal
- [x] 5.2 Add "Apply Concept to Event" action — copies concept to linked event's description (with confirmation)
- [x] 5.3 Add "AI Budget Forecast" header action — triggers forecast, displays structured table in modal, offers "Apply to Budget" to populate line items
- [x] 5.4 Add "AI Pricing Strategy" header action — triggers pricing suggestion, displays tier cards in modal
- [x] 5.5 Add "Apply to Event Tickets" action — creates TicketType records on linked event from AI pricing result (with confirmation dialog listing tiers to be created, appends new tiers without modifying existing ones)
- [x] 5.6 Add "AI Risk Assessment" header action — triggers assessment, displays risk cards with severity badges

## 6. Planning vs Realization Module
- [x] 6.1 Create custom `ViewEventPlan` Filament page with planning-vs-realization dashboard
- [x] 6.2 Create `PlanningVsRealizationWidget` — fetches planned data from line items, actual revenue from orders, actual expenses from line items
- [x] 6.3 Create revenue comparison ChartWidget (bar/area) — planned vs actual vs target
- [x] 6.4 Create expense comparison by category widget (grouped bars)
- [x] 6.5 Create KPI summary cards: Revenue Achievement Rate, Budget Utilization Rate, Net Margin, Tickets Sold vs Target
- [x] 6.6 Handle "no linked event" gracefully with informational UI

## 7. Integration & Polish
- [x] 7.1 Add i18n translation files (en, id) for event planner labels, AI actions, dashboard metrics
- [x] 7.2 Verify Shield integration — ensure permissions appear in Shield's permission matrix
- [x] 7.3 Add activity logging for plan create/update/delete/status changes
- [x] 7.4 Test navigation ordering — confirm "Event Planner" appears first in "Event Management" group

## Post-Implementation
- [x] Update AGENTS.md in the project root for new changes in this spec
- [x] Run `prompter validate add-event-planner --strict --no-interactive` to confirm specs pass

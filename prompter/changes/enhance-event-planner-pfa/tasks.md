# Tasks: enhance-event-planner-pfa

Ordered implementation plan. Each task is small, verifiable, and delivers incremental progress. Dependencies are noted where applicable.

---

## Phase 1: Database & Models (Foundation)

- [ ] **1.1** Create migration for `event_plan_talents` table (schema per pfa-talent-management spec)
- [ ] **1.2** Create migration for `event_plan_rundowns` table (schema per pfa-budget-rundown spec)
- [ ] **1.3** Create migration to add concept fields to `event_plans` table (`concept_status`, `theme`, `tagline`, `narrative_summary`, `concept_synced_at`)
- [ ] **1.4** Create `EventPlanTalent` model with relationships (`belongsTo EventPlan`, `belongsTo Performer`, `belongsTo EventPlanLineItem`), fillable, casts, and activity logging
- [ ] **1.5** Create `EventPlanRundown` model with relationships (`belongsTo EventPlan`, `belongsTo EventPlanTalent`), fillable, casts, scopes (ordered)
- [ ] **1.6** Add `talents()` and `rundowns()` HasMany relationships to `EventPlan` model
- [ ] **1.7** Add `eventPlanTalents()` relationship to `Performer` model
- [ ] **1.8** Update `EventPlan` model: add new columns to `$fillable` and `$casts`; add computed attributes for talent fee totals
- [ ] **1.9** Run migrations and regenerate IDE helper (`php artisan ide-helper:models`)

**Validates:** `php artisan migrate` succeeds; models can be instantiated in Tinker; relationships return correct types.

---

## Phase 2: Translation Files

- [ ] **2.1** Create `lang/en/event_plan_talent.php` with all labels, placeholders, and status strings
- [ ] **2.2** Create `lang/en/event_plan_rundown.php` with all labels, placeholders, and type strings
- [ ] **2.3** Create `lang/en/sync_to_live.php` with all action labels, diff section titles, and confirmation messages
- [ ] **2.4** Update `lang/en/event_plan.php` (if exists) or create it with concept section labels (`theme`, `tagline`, `narrative_summary`, `concept_status`)
- [ ] **2.5** Create Indonesian translations: `lang/id/event_plan_talent.php`, `lang/id/event_plan_rundown.php`, `lang/id/sync_to_live.php`

**Validates:** `__('event_plan_talent.planned_fee')` returns correct strings in both locales.

---

## Phase 3: Talent Management — Filament UI (depends on Phase 1, 2)

- [ ] **3.1** Create `EventPlanTalentsRelationManager` with table columns (performer name + photo, planned_fee, actual_fee, slot_time, duration, contract_status badge, budget linked icon)
- [ ] **3.2** Add create modal form (performer select searchable, planned_fee money IDR, slot_time, slot_duration_minutes, performance_order, contract_status select, rider_notes, notes)
- [ ] **3.3** Add edit modal form (same fields as create, pre-populated)
- [ ] **3.4** Add delete action with confirmation
- [ ] **3.5** Add "Link to Budget" table action that auto-creates `EventPlanLineItem` and links it
- [ ] **3.6** Register `EventPlanTalentsRelationManager` on `EditEventPlan` page
- [ ] **3.7** Implement fee-change propagation: when `planned_fee` or `actual_fee` is updated and line item is linked, update the line item's `planned_amount` / `actual_amount`

**Validates:** Can add/edit/remove talents from a plan; fees sync to budget line items; duplicate performers are blocked.

---

## Phase 4: Rundown Management — Filament UI (depends on Phase 1, 2)

- [ ] **4.1** Create `EventPlanRundownRelationManager` with table columns (sort_order, title, start_time–end_time, duration calculated, type badge, linked talent name)
- [ ] **4.2** Add create modal form (title, start_time, end_time, type select, linked talent select, description, notes)
- [ ] **4.3** Add edit modal form (same fields, pre-populated)
- [ ] **4.4** Add delete action with confirmation
- [ ] **4.5** Add reorder functionality via sort_order
- [ ] **4.6** Implement time-overlap warning validation (non-blocking)
- [ ] **4.7** Register `EventPlanRundownRelationManager` on `EditEventPlan` page

**Validates:** Can create/edit/delete rundown items; talent link shows performer name; time overlap warning fires.

---

## Phase 5: Concept & Narrative Enhancement (depends on Phase 1)

- [ ] **5.1** Add "Concept & Narrative" collapsible section to `EditEventPlan` form with fields: concept_status badge, theme, tagline, narrative_summary textarea, AI concept preview (HTML view)
- [ ] **5.2** Update AI Concept Builder action to parse response and populate theme/tagline/narrative_summary; set `concept_status = drafted`
- [ ] **5.3** Add "Mark as Finalized" action button (sets `concept_status = finalized`)
- [ ] **5.4** Update `mutateFormDataBeforeSave` to include new concept fields

**Validates:** Concept fields save/load correctly; AI generation populates structured fields; status transitions work.

---

## Phase 6: Multi-Scenario Pricing (depends on existing pricing infrastructure)

- [ ] **6.1** Update `AiService::suggestPricingStrategy()` prompt to request three scenarios (pessimistic/realistic/optimistic) in JSON
- [ ] **6.2** Update `AiService::getMockedPricingStrategy()` to return three-scenario structure
- [ ] **6.3** Update `PricingStrategyService::suggest()` to handle multi-scenario JSON
- [ ] **6.4** Update "AI Pricing Strategy" action in `EditEventPlan` to render tabbed scenario view
- [ ] **6.5** Add "Select This Scenario" action per tab; store `selected_scenario` key in JSON
- [ ] **6.6** Update "Apply to Event Tickets" action to use only the selected scenario

**Validates:** AI returns 3 scenarios; tabs render correctly; "Apply" creates tickets from selected scenario only.

---

## Phase 7: AI Rundown Generator (depends on Phase 1, 4)

- [ ] **7.1** Add `AiService::generateRundown()` method with prompt template for event agenda generation
- [ ] **7.2** Add `AiService::getMockedRundown()` fallback
- [ ] **7.3** Create `RundownGeneratorService` — validates plan, calls AI, returns parsed items
- [ ] **7.4** Add "AI Rundown Generator" header action on `EditEventPlan` (preview modal + "Apply to Rundown" button)
- [ ] **7.5** Implement rundown item creation from AI output (append to existing, no replacement)

**Validates:** AI generates sensible rundown; items are created in `event_plan_rundowns`; existing items preserved.

---

## Phase 8: Sync to Live Service (depends on Phase 1, 3, 4, 5, 6)

- [ ] **8.1** Create `SyncToLiveService` class with methods: `validateSync()`, `generateDiff()`, `executeSync()`, `createEventFromPlan()`
- [ ] **8.2** Implement `generateDiff()` — compares plan data vs live event; returns structured diff array per section
- [ ] **8.3** Implement `executeSync()` — processes selected sections in DB transaction; logs activity with log_name "sync_to_live"
- [ ] **8.4** Implement concept sync: updates `events.description`, sets `concept_status = synced`, `concept_synced_at`
- [ ] **8.5** Implement performer sync: creates `event_performer` for confirmed talents not yet linked
- [ ] **8.6** Implement ticket sync: creates `ticket_type` records from selected pricing scenario
- [ ] **8.7** Implement metadata sync: updates selected event fields from plan
- [ ] **8.8** Implement `createEventFromPlan()` — creates draft event, links to plan
- [ ] **8.9** Add "Sync to Live" header action on `EditEventPlan` — diff preview modal with section checkboxes, confirm button
- [ ] **8.10** Add "Create Event from Plan" action (visible when `event_id = NULL`)

**Validates:** Diff preview shows correct comparisons; selective sync creates/updates correct records; activity log entries recorded; standalone plans can create events.

---

## Phase 9: Monitoring Dashboard Widgets (depends on Phase 1, 3, 4)

- [ ] **9.1** Create `TalentStatusBoardWidget` — kanban-style with 4 status columns, performer cards, fee summaries
- [ ] **9.2** Create `RundownTimelineWidget` — vertical timeline cards, color-coded by type, talent names
- [ ] **9.3** Create `SalesPhaseTrackerWidget` — ticket type rows with progress bars, phase status badges, fill rates
- [ ] **9.4** Enhance `PlanningVsRealizationWidget` — add Talent Confirmation Rate, Rundown Completeness, Days Until Event stats; add alert badges
- [ ] **9.5** Register all new widgets on `ViewEventPlan` page with correct layout order
- [ ] **9.6** Implement empty states for all widgets

**Validates:** All widgets render with data; empty states display correctly; alert thresholds trigger appropriate badges.

---

## Phase 10: Integration & Polish

- [ ] **10.1** Run `php artisan ide-helper:models` and `php artisan ide-helper:generate`
- [ ] **10.2** Verify all translations work in both EN and ID locales
- [ ] **10.3** Run PHPStan (`composer phpstan`) — fix any level 5 errors
- [ ] **10.4** Manual smoke test: create plan → add talents → add rundown → generate AI content → sync to live → verify monitoring dashboard
- [ ] **10.5** Update `AGENTS.md` with new models, services, and PFA workflow documentation

**Validates:** No PHPStan errors; full workflow completes without errors; documentation is current.

# Design: Enhance Event Planner — PFA (Plan → Action → Monitor) Workflow

## 1. Overview

This enhancement transforms the existing Event Planner from a flat AI-assisted planning workspace into a **full PFA (Plan → Action → Monitor) operational pipeline**. The Event Planner becomes the "Event Architect" — a single command center where creative concepts, talent management, financial strategies, and event rundowns are systematically planned, executed through "Sync to Live," and monitored in real-time dashboards.

### Current State

The Event Planner today provides:
- `EventPlan` / `EventPlanLineItem` CRUD
- AI Concept Builder, Budget Forecaster, Pricing Strategy, Risk Assessment
- Planning vs Realization dashboard (revenue from `orders`, expenses from `actual_amount`)
- One-way "Apply to Event" actions for concept and pricing

### Target State

The PFA-enhanced Event Planner adds:
1. **Talent & Performer Management within Plans** — linking performers to plans with fees, slots, and contract status
2. **Concept & Narrative lifecycle** — structured stages from brainstorm → finalized → synced to live event
3. **Ticket & Financial Strategy enhancements** — multi-scenario modelling, sales-phase tracking, real-time revenue sync
4. **Budget & Rundown integration** — event timeline/agenda items, budget correlation with talent fees, real-time variance monitoring
5. **Sync to Live mechanism** — explicit data push from plan to live event with diff preview and audit trail
6. **Monitoring dashboard** — unified command center with KPIs, timeline, sales velocity, and alert system

---

## 2. PFA Stage Model

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        PLAN (Creative Strategy Phase)                       │
├─────────────────────────────────────────────────────────────────────────────┤
│  Concept & Narrative    │  Talent Lineup Planning   │  Financial Modelling  │
│  - AI Concept Builder   │  - Performer selection     │  - AI Budget Forecast │
│  - Theme / tagline      │  - Fee negotiation tracker │  - AI Pricing Tiers   │
│  - Target audience def  │  - Slot/time assignment    │  - Multi-scenario sim │
│  - Narrative arcs       │  - Contract status track   │  - Risk Assessment    │
│                         │  - Rider/requirements      │                       │
├─────────────────────────┼───────────────────────────┼───────────────────────┤
│  Budget & Rundown Planning                                                  │
│  - Line item budget (existing)                                              │
│  - Rundown/agenda items with time blocks                                    │
│  - Talent-budget correlation (fee → line item link)                         │
│  - AI Rundown Generator                                                     │
└─────────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                       ACTION (Sync to Live Phase)                           │
├─────────────────────────────────────────────────────────────────────────────┤
│  Sync to Live                                                               │
│  - Diff preview (plan vs current event state)                               │
│  - Selective sync: concept, performers, tickets, rundown                    │
│  - Conflict resolution for already-published data                           │
│  - Audit trail: who synced what, when                                       │
│  - Creates/updates Event, TicketTypes, event_performer, rundown entries     │
└─────────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                       MONITOR (Real-Time Tracking Phase)                    │
├─────────────────────────────────────────────────────────────────────────────┤
│  Unified Dashboard                                                          │
│  - Sales velocity & phase tracking (Early Bird → Presale → GA)              │
│  - Budget vs Realization (existing, enhanced with talent fees)              │
│  - Talent status board (confirmed / pending / cancelled)                    │
│  - Rundown progress tracker (pre-event, day-of)                            │
│  - Revenue KPIs with trend indicators                                       │
│  - Alert system for budget overruns, low sales, pending contracts           │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Key Architectural Decisions

### Decision 1: `event_plan_talents` Pivot Table (NEW)

**Problem:** Performers exist in `performers` table and are linked to events via `event_performer`, but the planning phase needs to track fees, negotiation status, time slots, and rider requirements *before* the performer is committed to the live event.

**Decision:** Create `event_plan_talents` as a dedicated pivot table between `event_plans` and `performers`.

**Schema:**
```
event_plan_talents
├── id (PK)
├── event_plan_id (FK → event_plans, cascade)
├── performer_id (FK → performers, cascade)
├── planned_fee (decimal 15,2, nullable)
├── actual_fee (decimal 15,2, nullable)
├── slot_time (time, nullable)
├── slot_duration_minutes (integer, nullable)
├── performance_order (integer, default 0)
├── contract_status (enum: draft, negotiating, confirmed, cancelled — default: draft)
├── rider_notes (text, nullable)
├── notes (text, nullable)
├── budget_line_item_id (FK → event_plan_line_items, nullable) — links fee to budget
├── created_at, updated_at
└── UNIQUE(event_plan_id, performer_id)
```

**Rationale:** Separating plan-stage talent data from live event data (`event_performer`) allows:
- Fee tracking without polluting the performer master data
- Contract status workflow independent of event publication
- Budget correlation (planned_fee ↔ line item)
- "Sync to Live" can selectively push confirmed performers to `event_performer`

### Decision 2: `event_plan_rundowns` Table (NEW)

**Problem:** Events need an agenda/timeline, but this is a planning concern first, then pushed to the live event.

**Decision:** Create `event_plan_rundowns` for time-blocked agenda items within a plan.

**Schema:**
```
event_plan_rundowns
├── id (PK)
├── event_plan_id (FK → event_plans, cascade)
├── title (string)
├── description (text, nullable)
├── start_time (time)
├── end_time (time)
├── type (enum: ceremony, performance, break, setup, networking, other)
├── event_plan_talent_id (FK → event_plan_talents, nullable) — links to talent slot
├── notes (text, nullable)
├── sort_order (integer, default 0)
├── created_at, updated_at
└── INDEX(event_plan_id, sort_order)
```

**Rationale:** Rundown items link to talent slots (when applicable), creating a coherent timeline. AI can generate initial rundown from plan parameters.

### Decision 3: Sync to Live — Service-Orchestrated with Diff Preview

**Problem:** Current "Apply to Event" actions are one-shot, fire-and-forget. No preview, no selective sync, no audit trail.

**Decision:** Introduce `SyncToLiveService` with diff-based preview and selective sync.

**Flow:**
1. User clicks "Sync to Live" → Service generates a diff report
2. Diff shows: what will be created, updated, or skipped
3. User selects which sections to sync (concept, performers, tickets, rundown)
4. Service executes selected syncs in a DB transaction
5. Activity log records: `"Synced [sections] from Plan #{id} to Event #{id}"`

**Sync Mappings:**
| Plan Data | Live Event Target | Sync Behavior |
|---|---|---|
| `ai_concept_result` | `events.description` | Overwrite with confirmation |
| `event_plan_talents` (confirmed) | `event_performer` pivot | Append, skip existing |
| `ai_pricing_result` tiers | `ticket_types` records | Append, skip duplicate names |
| `event_plan_rundowns` | Future: `event_rundowns` table | Create/update |
| Plan metadata (title, date, location) | `events.*` fields | Selective field-by-field |

### Decision 4: Enhanced Monitoring — Extend Existing Widgets

**Problem:** Current dashboard has 3 widgets (KPI stats, revenue chart, expense chart). Need talent status, rundown progress, sales phase tracking, and alerts.

**Decision:** Add 3 new widgets to `ViewEventPlan` page:
- `TalentStatusBoardWidget` — Kanban-style talent pipeline (draft → negotiating → confirmed → cancelled)
- `RundownTimelineWidget` — Visual timeline of agenda items
- `SalesPhaseTrackerWidget` — Phase-by-phase sales progress (Early Bird fill rate → Presale → GA)

Keep existing widgets. Total: 6 widgets on the View page.

### Decision 5: AI Rundown Generator (NEW AiService Method)

**Problem:** Creating event rundowns from scratch is tedious. AI can generate a sensible default.

**Decision:** Add `AiService::generateRundown()` that takes plan parameters + confirmed talents and produces a time-blocked agenda.

**Input:** Event category, date, duration, confirmed talents with slot durations, audience size.
**Output:** JSON array of rundown items with suggested times, types, and descriptions.

### Decision 6: Multi-Scenario Financial Modelling

**Problem:** Current pricing strategy generates one scenario. Organizers need pessimistic/realistic/optimistic views.

**Decision:** Extend `ai_pricing_result` JSON structure to include `scenarios` array with three variants. UI shows tabbed view of each scenario. "Apply to Event Tickets" applies the selected scenario only.

---

## 4. Data Flow: Sync to Live

```
EventPlan (PLAN phase)
    │
    ├── ai_concept_result ──────────┐
    ├── event_plan_talents ─────────┤
    ├── ai_pricing_result ──────────┤     SyncToLiveService
    ├── event_plan_rundowns ────────┤  ─────────────────────►  Event (LIVE)
    └── metadata (title/date/loc) ──┘     │ diff preview      ├── description
                                          │ selective sync     ├── event_performer
                                          │ DB transaction     ├── ticket_types
                                          │ activity log       ├── (future) event_rundowns
                                          └────────────────    └── title, date, location
                                                │
                                                ▼
                                         MONITOR phase
                                    (dashboards auto-refresh)
```

---

## 5. Permission Model

No new permission *categories*. All new functionality falls under existing `event-plans.*` permissions (Super Admin only). Specific access:

| Feature | Required Permission |
|---|---|
| Manage plan talents | `event-plans.edit` |
| Manage rundown items | `event-plans.edit` |
| Trigger AI generation | `event-plans.edit` |
| Sync to Live | `event-plans.edit` + `events.edit` (must also have event edit rights) |
| View monitoring dashboard | `event-plans.view` |

---

## 6. Risks & Mitigations

| Risk | Impact | Mitigation |
|---|---|---|
| Sync to Live overwrites manual event edits | High | Diff preview with field-level granularity; skip fields edited after last sync |
| Talent fee totals drift from budget line items | Medium | Auto-recalculate budget line item when talent fee changes (if linked) |
| Rundown table adds schema complexity | Low | Keep rundown as optional; AI generates defaults only when requested |
| Multi-scenario pricing inflates AI costs | Medium | Cache all scenarios in single JSON column; regenerate only on explicit user action |
| Dashboard widget performance on large plans | Low | Eager load relationships; limit chart data to plan scope only |

---

## 7. Integration Points

### Existing Systems Affected
- **`EventPlanResource` (Edit page):** Add Talent and Rundown relation managers; add "Sync to Live" header action
- **`EventPlanResource` (View page):** Add 3 new widgets
- **`AiService`:** Add `generateRundown()` method + multi-scenario variant for `suggestPricingStrategy()`
- **`Event` model:** No schema changes needed; uses existing relationships
- **`Performer` model:** Add `eventPlanTalents()` relationship

### New Components
- `EventPlanTalent` model + migration
- `EventPlanRundown` model + migration
- `SyncToLiveService`
- `EventPlanTalentsRelationManager` (Filament)
- `EventPlanRundownRelationManager` (Filament)
- `TalentStatusBoardWidget`
- `RundownTimelineWidget`
- `SalesPhaseTrackerWidget`
- Translation files: `lang/en/event_plan_talent.php`, `lang/en/event_plan_rundown.php`, `lang/en/sync_to_live.php`

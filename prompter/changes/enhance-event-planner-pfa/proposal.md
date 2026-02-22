# Proposal: enhance-event-planner-pfa

## Summary

Transform the Event Planner module from a flat AI-assisted planning workspace into a **PFA (Plan → Action → Monitor) operational pipeline** — positioning it as the "Event Architect" command center. This enhancement adds four major capabilities to the existing EPIC-011 foundation:

1. **Talent & Performer Management** — manage artist lineups, fees, contract status, time slots, and rider requirements within the plan; correlate talent fees with the budget
2. **Budget & Rundown Integration** — time-blocked event agenda/rundown with AI generation, linked to talent slots and budget line items
3. **Sync to Live** — controlled, diff-previewed data push from the planning workspace to the live event entity (concept, performers, tickets, metadata) with audit trail
4. **Enhanced Monitoring Dashboard** — talent pipeline status board, rundown timeline visualization, sales phase tracker, and alert-driven KPI enhancements

Additionally, the existing Concept & Narrative and Pricing Strategy features are upgraded:
- **Concept & Narrative** gains structured fields (theme, tagline, status workflow: brainstorm → drafted → finalized → synced)
- **Pricing Strategy** gains multi-scenario modelling (pessimistic/realistic/optimistic) with scenario selection

## Motivation

The current Event Planner (EPIC-011) provides excellent AI tools for concept generation, budget forecasting, pricing, and risk assessment. However, the workflow stops at "Apply to Event" — a one-shot push with no preview, no talent management, no rundown planning, and no post-sync monitoring. Event organizers need a complete lifecycle:

- **PLAN**: Holistically design the event — concept, talent lineup, financial model, and agenda — in one workspace
- **ACTION**: Push finalized plans to the live event system with visibility into what changes, conflict awareness for published events, and selective sync
- **MONITOR**: Track execution against the plan — are tickets selling? Are performers confirmed? Is the budget on track?

## Capabilities

| Capability | Spec Delta | Type |
|---|---|---|
| Talent Management in Plans | `pfa-talent-management` | ADDED |
| Concept & Narrative Lifecycle | `pfa-concept-narrative` | MODIFIED |
| Multi-Scenario Pricing & Sales Phase Tracking | `pfa-ticket-financial-strategy` | MODIFIED |
| Rundown/Agenda Management + AI Generation | `pfa-budget-rundown` | ADDED |
| Sync to Live Pipeline | `pfa-sync-to-live` | ADDED |
| Monitoring Dashboard Enhancements | `pfa-monitoring-dashboard` | ADDED |

## Scope & Boundaries

### In Scope
- New `event_plan_talents` and `event_plan_rundowns` tables
- New columns on `event_plans` (concept_status, theme, tagline, narrative_summary, concept_synced_at)
- 2 new Filament RelationManagers (Talents, Rundown)
- 3 new dashboard widgets (TalentStatusBoard, RundownTimeline, SalesPhaseTracker)
- `SyncToLiveService` with diff preview, selective sync, and audit
- `AiService::generateRundown()` new AI method
- Multi-scenario pricing in `AiService::suggestPricingStrategy()`
- Translation files (EN primary, ID secondary)

### Out of Scope
- Live `event_rundowns` table on the Event model (future — currently rundown data only lives in plan context)
- Visitor-facing rundown display (future — requires public event detail page enhancement)
- Real-time WebSocket dashboard updates (uses page-refresh model, same as current)
- Automated contract/document generation for talent
- Integration with external talent booking platforms

## Breaking Changes

**None.** This is purely additive:
- New tables and columns via migrations
- New models that don't affect existing models
- Existing `EventPlanResource` is extended, not replaced
- All existing AI methods retain backward compatibility
- `ai_pricing_result` JSON structure gains a wrapper `scenarios` key, but the `PricingStrategyService` handles both old (flat) and new (multi-scenario) formats gracefully

## Access Control

All new features operate under existing `event-plans.*` permissions (Super Admin only). The "Sync to Live" action additionally requires `events.edit` permission on the target event.

## Estimated Effort

| Phase | Tasks | Complexity |
|---|---|---|
| Database & Models | 9 | Medium |
| Translations | 5 | Low |
| Talent Management UI | 7 | Medium |
| Rundown Management UI | 7 | Medium |
| Concept Enhancement | 4 | Low |
| Multi-Scenario Pricing | 6 | Medium |
| AI Rundown Generator | 5 | Medium |
| Sync to Live Service | 10 | High |
| Monitoring Widgets | 6 | Medium |
| Integration & Polish | 5 | Low |
| **Total** | **64** | |

## Dependencies

- Phases 3-9 depend on Phase 1 (Database & Models)
- Phase 8 (Sync to Live) depends on Phases 3, 4, 5, 6 for complete feature coverage
- Phase 9 (Dashboard) depends on Phases 3, 4 for talent and rundown data
- Phases 3-7 are parallelizable after Phase 1+2 complete

## Related Specs

- `event-planner` (existing) — base EventPlan CRUD
- `ai-planning-tools` (existing) — base AI capabilities
- `planning-vs-realization` (existing) — base dashboard widgets
- `performer-management` (existing) — Performer model and master data
- `event-management` (existing) — Event model and lifecycle
- `ticket-type-management` (existing) — TicketType model

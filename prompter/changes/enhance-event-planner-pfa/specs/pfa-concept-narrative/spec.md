# pfa-concept-narrative Specification

## Purpose
Enhance the Concept & Narrative stage of the Event Planner to support structured creative development with stage tracking, theme/tagline management, and AI-assisted narrative refinement — moving from a single AI-generated text blob to a structured creative brief.

## MODIFIED Requirements

### Requirement: Structured Concept Data (extends AI Concept Builder)
The system SHALL store concept data as structured fields in addition to the free-form `ai_concept_result`, enabling granular editing and "Sync to Live" support.

#### Scenario: Concept fields on event plan
- **GIVEN** the `event_plans` table
- **THEN** the following columns are added:
  - `concept_status` (string, default 'brainstorm') — values: brainstorm, drafted, finalized, synced
  - `theme` (string, nullable) — core event theme
  - `tagline` (string, nullable) — short marketing tagline
  - `narrative_summary` (text, nullable) — structured narrative overview
  - `concept_synced_at` (timestamp, nullable) — last time concept was synced to live event

#### Scenario: Concept status workflow
- **GIVEN** an event plan concept
- **WHEN** the concept_status is updated
- **THEN** valid transitions are:
  - `brainstorm` → `drafted` (after AI generation or manual editing)
  - `drafted` → `finalized` (organizer marks concept as ready)
  - `finalized` → `synced` (after Sync to Live pushes concept to event)
  - `synced` → `drafted` (if organizer re-edits after sync)
- **AND** status badges are displayed: brainstorm=gray, drafted=info, finalized=warning, synced=success

#### Scenario: AI Concept Builder populates structured fields
- **GIVEN** an event plan with title, category, and target audience
- **WHEN** the user triggers AI Concept Builder
- **THEN** the AI response is parsed to populate:
  - `ai_concept_result` (full HTML — existing behavior)
  - `theme` (extracted or generated theme phrase)
  - `tagline` (extracted or generated tagline)
  - `narrative_summary` (plain-text summary)
- **AND** `concept_status` is set to `drafted`

#### Scenario: Manual concept editing
- **GIVEN** an event plan with concept fields
- **WHEN** the organizer manually edits theme, tagline, or narrative_summary
- **THEN** the changes are saved without triggering AI
- **AND** `concept_status` remains as-is (organizer controls the flow)

### Requirement: Concept Section in Edit Form
The system SHALL present concept fields in a dedicated collapsible section within the Event Plan edit form.

#### Scenario: Concept section layout
- **GIVEN** the Event Plan edit page
- **THEN** a "Concept & Narrative" section displays:
  - Concept Status (badge, read-only)
  - Theme (text input with placeholder)
  - Tagline (text input with placeholder)
  - Narrative Summary (textarea)
  - AI Concept Preview (rendered HTML from `ai_concept_result`, read-only)
- **AND** a "Mark as Finalized" action button appears when status is `drafted`
- **AND** all labels use `__()` translations

# ai-planning-tools Specification

## Purpose
Provide AI-driven planning assistance for event organizers, leveraging the existing Gemini/OpenAI infrastructure to generate event concepts, forecast budgets, suggest pricing strategies, and assess risks.

## ADDED Requirements

### Requirement: AI Concept Builder
The system SHALL transform rough event drafts into polished, professional event descriptions using AI.

#### Scenario: Generate concept from rough notes
- **GIVEN** an authenticated user editing an event plan
- **AND** the plan has a title, event_category, target_audience_description, and notes
- **WHEN** they click the "AI Concept Builder" action
- **THEN** the system sends the plan data to the AI provider (Gemini → OpenAI → mock fallback)
- **AND** the AI generates a polished event description with headline, introduction, key highlights, and call-to-action
- **AND** the result is stored in `event_plans.ai_concept_result`
- **AND** the result is displayed in a preview panel

#### Scenario: Regenerate concept
- **GIVEN** an event plan with an existing `ai_concept_result`
- **WHEN** the user clicks "Regenerate Concept"
- **THEN** a new AI call is made with the current plan data
- **AND** the previous result is overwritten
- **AND** the user is warned before regeneration: "This will replace the current AI concept."

#### Scenario: Apply concept to linked event
- **GIVEN** an event plan linked to an event (`event_id` is set)
- **AND** the plan has a generated `ai_concept_result`
- **WHEN** the user clicks "Apply to Event"
- **THEN** the AI-generated description is copied to the linked event's `description` field
- **AND** the user is asked for confirmation before overwriting

#### Scenario: AI unavailable fallback
- **GIVEN** no AI API key is configured (neither Gemini nor OpenAI)
- **WHEN** the user triggers any AI action
- **THEN** a mock/demo result is generated
- **AND** a warning badge displays "Demo Mode — Configure AI keys for real results"

### Requirement: AI Budget Forecaster
The system SHALL estimate operational costs based on event attributes using AI.

#### Scenario: Generate budget forecast
- **GIVEN** an event plan with event_category, target_audience_size, location, and event_date
- **WHEN** the user clicks "AI Budget Forecast"
- **THEN** the AI generates cost estimates for key categories:
  - Venue rental
  - Talent / performers
  - Security & safety
  - Marketing & promotion
  - Logistics & equipment
  - Staffing
  - Insurance
  - Contingency (typically 10-15% of total)
- **AND** the result is stored in `event_plans.ai_budget_result` as JSON
- **AND** the result is displayed in a structured table

#### Scenario: Auto-populate line items from forecast
- **GIVEN** a generated AI budget forecast
- **WHEN** the user clicks "Apply to Budget"
- **THEN** expense line items are created in `event_plan_line_items` for each forecast category
- **AND** existing expense line items are NOT replaced (new items are appended)
- **AND** a notification confirms "X line items added from AI forecast"

#### Scenario: Budget forecast with insufficient data
- **GIVEN** an event plan missing `event_category` or `target_audience_size`
- **WHEN** the user triggers "AI Budget Forecast"
- **THEN** a validation error is shown: "Please fill in Event Category and Target Audience Size first."

### Requirement: Dynamic Pricing Strategy
The system SHALL suggest ticket pricing structures to meet revenue targets using AI.

#### Scenario: Generate pricing strategy
- **GIVEN** an event plan with revenue_target, target_audience_size, event_category, and budget_target
- **WHEN** the user clicks "AI Pricing Strategy"
- **THEN** the AI suggests ticket tiers with:
  - Tier name (e.g., Early Bird, Presale, General Admission, VIP, VVIP)
  - Suggested price per tier
  - Suggested quantity allocation per tier
  - Recommended sales window per tier
  - Projected revenue per tier
  - Total projected revenue
- **AND** the result is stored in `event_plans.ai_pricing_result` as JSON
- **AND** the result is displayed in a structured card layout

#### Scenario: Pricing strategy meets revenue target
- **GIVEN** a generated pricing strategy
- **WHEN** the total projected revenue equals or exceeds the revenue_target
- **THEN** the display shows a green "Target Met" indicator
- **AND** the surplus/deficit amount is shown

#### Scenario: Pricing strategy falls short of revenue target
- **GIVEN** a generated pricing strategy
- **WHEN** the total projected revenue is below the revenue_target
- **THEN** the display shows an amber "Target Not Met" indicator
- **AND** the deficit amount is shown
- **AND** a suggestion to adjust audience size or pricing is displayed

#### Scenario: Apply pricing strategy to event ticket types
- **GIVEN** an event plan linked to an event (`event_id` is set)
- **AND** the plan has a generated `ai_pricing_result`
- **WHEN** the user clicks "Apply to Event Tickets"
- **THEN** a confirmation dialog is shown listing each ticket tier to be created (name, price, quantity, sales window)
- **AND** upon confirmation, new `TicketType` records are created on the linked event for each AI-suggested tier
- **AND** existing ticket types on the event are NOT deleted or modified (new tiers are appended)
- **AND** a success notification confirms "X ticket types created from AI pricing strategy"

#### Scenario: Apply pricing strategy without linked event
- **GIVEN** an event plan with `event_id = NULL`
- **AND** a generated `ai_pricing_result`
- **WHEN** the user clicks "Apply to Event Tickets"
- **THEN** the action is blocked with message "Link an event to this plan first to create ticket types."

### Requirement: Risk Assessment Tool
The system SHALL evaluate the event plan for potential risks and rate their severity.

#### Scenario: Generate risk assessment
- **GIVEN** an event plan with event_category, event_date, location, target_audience_size, and budget data
- **WHEN** the user clicks "AI Risk Assessment"
- **THEN** the AI evaluates risks across dimensions:
  - Weather & environmental risks (based on location + date)
  - Audience target mismatch (category vs audience size)
  - Budget adequacy (expenses vs revenue target)
  - Timeline feasibility (event_date vs current date)
  - Regulatory / compliance considerations
- **AND** each risk is rated: Low, Medium, High, or Critical
- **AND** mitigation suggestions are provided for Medium+ risks
- **AND** the result is stored in `event_plans.ai_risk_result` as JSON

#### Scenario: Overall risk score
- **GIVEN** a generated risk assessment with individual risk ratings
- **WHEN** the result is displayed
- **THEN** an overall risk score is calculated (e.g., weighted average)
- **AND** the score is displayed with a color-coded indicator (green/amber/red)
- **AND** critical risks are highlighted at the top with prominent styling

#### Scenario: Risk assessment with minimal data
- **GIVEN** an event plan with only title and event_category filled
- **WHEN** the user triggers risk assessment
- **THEN** the assessment runs with available data
- **AND** missing-data risks are flagged (e.g., "No budget data — financial risk cannot be assessed")

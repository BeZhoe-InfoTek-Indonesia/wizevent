# revenue-calculator Specification

## Purpose
TBD - created by archiving change add-revenue-simulator. Update Purpose after archive.
## Requirements
### Requirement: Interactive Revenue Calculator

The calculator MUST provide editable inputs that default to the existing event data but allow "what-if" scenarios without changing the actual event data.

#### Scenario: Default Initalization
- **Given** an event with Title "Summer Fest" and Ticket Types "General" (100 qty, $50) and "VIP" (20 qty, $100)
- **When** the admin triggers the "Revenue Simulation" action
- **Then** the modal should display the inputs pre-filled with these values:
    - Ticket "General": 100 qty, $50
    - Ticket "VIP": 20 qty, $100
    - Tax Rate: 10% (default)
    - Platform Fee: 0% (default)

#### Scenario: Scenario Toggle
- **Given** the calculator is open with "Optimistic" (100%) scenario selected
- **When** the admin toggles to "Pessimistic" (50%)
- **Then** the "Projected Revenue" should update to reflect 50% of sold capacity (e.g., $3,500 instead of $7,000).

#### Scenario: Custom Costs
- **Given** the calculator is open
- **When** the admin adds a "Sound System" cost of $1,000
- **Then** the "Total Expenses" should increase by $1,000 + applicable taxes (none on this line item usually).
- **And** the "Net Profit" should decrease by $1,000.

### Requirement: Real-time Output Calculation

The calculator MUST display key financial metrics in real-time.

#### Scenario: Break-Even Calculation
- **Given** total fixed expenses of $5,000 and average ticket price of $50
- **When** the calculator updates
- **Then** the "Break-Even Point" should show "100 Tickets".

#### Scenario: Merch Revenue
- **Given** 20 VIP tickets sold (Optimistic)
- **When** the admin sets "VIP Merch Conversion" to 50% and "Revenue per Unit" to $20
- **Then** "Merchandise Revenue" should be (20 * 0.5 * $20) = $200
- **And** "Gross Revenue" should include this amount or show it separately as "Additional Revenue".


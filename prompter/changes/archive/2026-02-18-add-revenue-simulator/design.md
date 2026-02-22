# Revenue Simulator Design

## Overview
The Revenue Simulator is a utility tool embedded within the `EventResource` in Filament. It provides a non-intrusive modal for financial forecasting without affecting the live event data.

## Architecture

### Component Structure
-   **Action Location**: `App\Filament\Resources\EventResource\Pages\ListEvents`.
-   **Modal Content**: The action will render a custom Livewire component `App\Livewire\Admin\Events\RevenueCalculator`.
-   **State Management**: The Livewire component will initialize with data from the `Event` model (capacity, ticket types, etc.) but manage simulation state independently. Changes in the calculator do *not* persist to the database.

### Data Flow
1.  **Initialize**:
    -   Fetch `Event` details: ID, title.
    -   Fetch `TicketType` details: Name, Quantity, Price.
    -   Initialize default costs (Venue, Production, Marketing - empty by default or fetched if available).
    -   Set default Tax (10%) and Fee (0% or configurable).
2.  **Input Handling**:
    -   User modifies inputs (e.g., changes ticket price for simulation).
    -   Livewire updates component state.
    -   Scenario toggle (Pessimistic/Optimistic) updates the effective `sold_quantity` multiplier (0.5 or 1.0).
3.  **Calculation Logic**:
    -   `Gross Revenue` = Sum(Ticket Price * (Quantity * Scenario Multiplier))
    -   `Total Expenses` = Sum(Production Costs) + (Tax Rate * Gross Revenue) + (Fee Rate * Gross Revenue)
    -   `Net Profit` = Gross Revenue - Total Expenses + Merch Revenue
    -   `Break-Even Point` = Total Fixed Expenses / Average Contribution Margin per Ticket (simplified as Total Expenses / Avg Ticket Price for initial version, exact BEP formula: Fixed Costs / (Price - Variable Cost)).
        -   *Note*: Since we don't separate fixed vs variable costs per ticket easily, we'll use a simplified BEP: Total Expenses / Weighted Average Ticket Price.

### Calculator Logic Details
-   **Merchandise Revenue**:
    -   `VipTicketCount` = Quantity of tickets in categories marked as VIP (or manually selected).
    -   `MerchRevenue` = (`VipTicketCount` * `ScenarioMultiplier` * `ConversionRate`) * `RevenuePerUnit`.

## UI/UX
-   **Modal**: Large or Extra Large size to accommodate the split view.
-   **Layout**: `Grid` with 2 columns.
    -   **Left Column**: Filament Forms schema or custom Blade inputs.
        -   *Section 1*: Ticket Sales (Table/Repeater style for ticket types).
        -   *Section 2*: Costs (Repeater for adding line items).
        -   *Section 3*: Parameters (Tax %, Fee %, Merch settings).
    -   **Right Column**: Sticky sidebar with large stat cards (Gross, Expenses, Net, Break-Even).
-   **Theme**: Use Filament's existing design system (Tailwind classes) but add "modern" touches like gradients or card shadows for the stats.

## Considerations
-   **Responsiveness**: On mobile, the right panel should stack below the inputs.
-   **Performance**: Livewire roundtrips on every keystroke might be slow. Use `.live(debounce: 500ms)` or Alpine.js for instant client-side calculation if possible. Given the complexity, Livewire with debounce is the standard approach for Filament. Alpine.js would be faster but harder to maintain with complex PHP logic duplication. We will stick to Livewire with `lazy` or `debounce`.

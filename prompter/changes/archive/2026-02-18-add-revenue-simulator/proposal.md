# Add Revenue Simulation Calculator

## Summary
Implement a "Revenue Simulation" calculator as a table action on the Event List page in the Filament Admin panel. This tool will allow admins to project event profitability by adjusting variables like ticket sales scenarios (Pessimistic vs Optimistic), costs, and potential merchandise revenue.

## Problem
Event organizers currently lack a tool within the admin panel to quickly estimate financial outcomes based on different sales scenarios and cost structures. They must rely on external spreadsheets or mental math, which is inefficient and prone to error.

## Solution
Introduce a `RevenueCalculator` Livewire component launched via a modal action on the `ListEvents` resource page. This component will feature a split-pane layout:
- **Left Panel (Inputs)**: Capacity, ticket pricing per category, production costs, and merchandise simulation parameters.
- **Right Panel (Outputs)**: Real-time statistics for Gross Revenue, Expenses, Net Profit, and Break-Even Point.

## Key Features
1.  **Scenario Toggle**: Switch between "Pessimistic" (50% sales) and "Optimistic" (100% sales) projections.
2.  **Detailed Inputs**: Editable ticket prices and quantities per category, plus addable production costs.
3.  **Tax & Fees**: Automatic calculation of entertainment tax and platform fees.
4.  **Merch Simulation**: Project additional revenue based on VIP ticket holder conversion rates.
5.  **Live Updates**: All metrics update instantly as inputs change.

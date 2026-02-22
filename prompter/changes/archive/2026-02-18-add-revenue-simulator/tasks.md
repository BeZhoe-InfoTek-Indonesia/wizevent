# Add Revenue Simulation Calculator

## Backend
- [x] Create `App\Livewire\Admin\Events\RevenueCalculator` component. <!-- id: 0 -->
- [x] Register `RevenueCalculator` in Filament admin panel layout or `ListEvents` page resource. <!-- id: 1 -->
- [x] Implement `mount()` to load `Event` data (TicketTypes, Capacity). <!-- id: 2 -->
- [x] Implement calculation logic for Gross Revenue, Expenses, Net Profit, and Break-Even Point. <!-- id: 3 -->
- [x] Add `scenario` toggle (0.5 vs 1.0 multiplier). <!-- id: 4 -->
- [x] Add merchandising estimation logic. <!-- id: 5 -->

## Frontend
- [x] Design modal view using Blade and Filament components (`x-filament::card`, `x-filament::input`, etc.). <!-- id: 6 -->
- [x] Implement split-pane layout (CSS Grid). <!-- id: 7 -->
- [x] Add input fields for ticket prices/quantities (use `wire:model` or Filament Forms). <!-- id: 8 -->
- [x] Add input fields for production costs (Repeater style). <!-- id: 9 -->
- [x] Create "Right Panel" sticky stats sidebar with large numbers and visual indicators (green for profit, red for loss). <!-- id: 10 -->
- [x] Integrate Action into `ListEvents` table. <!-- id: 11 -->

## Validation
- [x] Verify calculations match manual spreadsheet formulas. <!-- id: 12 -->
- [x] Ensure mobile responsiveness. <!-- id: 13 -->

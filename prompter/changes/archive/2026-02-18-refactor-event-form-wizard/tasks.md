# Tasks

## Schema & Migration
- [x] Create migration to add `short_description` to `events` table.
- [x] Create pivot table `event_payment_bank` for associating multiple payment banks.
- [x] Ensure `seo_metadata` table is linked properly (polymorphic relationship `page_type`, `page_id`).
- [x] Ensure `payment_banks` table is properly seeded or managed.
- [x] Verify `promo_image` implementation strategy (using existing FileBucket polymorphic relation).

## Filament Resource Refactoring
- [x] Modify `app/Models/Event.php` to include:
  -   `public function seoMetadata(): MorphOne`
  -   `public function paymentBanks(): BelongsToMany`
- [x] Implement Filament Wizard with 5 steps in `EventResource.php` form schema:
  -   **Step 1**: Basic Information (Title, Slug, Category, Short/Detailed Description).
  -   **Step 2**: Location & Time (City, MapPicker, Lat/Long, Dates).
  -   **Step 3**: Media & SEO (Banner, `seoMetadata` relationship group [title, desc, og]).
  -   **Step 4**: Sales Configuration (Quotas, Ticket Types Repeater).
  -   **Step 5**: Organizer (Organizers [multi], Performers [multi], Payment Banks [multi]).

## Validation & Testing
- [x] Validate wizard navigation flow and step validation (Filament Wizard handles this automatically).
- [x] Test form submission creates `seo_metadata` record linked to Event (polymorphic relationship).
- [x] Test form submission creates pivot entries in `event_payment_bank`.
- [x] Ensure map picker functionality works within wizard steps.
- [x] Check responsive layout on mobile (Filament provides responsive layout by default).

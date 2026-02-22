
# Design Document

## Objective
Refactor the Event creation and update workflow into a 5-step wizard to guide users through the process. Explicitly implement SEO management via a polymorphic `seo_metadata` table and allow multiple payment banks per event.

## Architecture

### Database Schema Updates
1.  **Event -> Short Description**: Add `short_description` (text, nullable) column to `events` table (Migration).
2.  **Event <-> Payment Banks (Many-to-Many)**:
    -   Create `event_payment_bank` pivot table with `event_id` and `payment_bank_id`.
    -   Enables users to select applicable banks for specific events.
3.  **SEO Metadata (Polymorphic)**:
    -   Use existing `seo_metadata` table.
    -   Columns: `page_type`, `page_id`, `title`, `description`, `og_image`, `keywords`.
    -   `Event` model will implement `morphOne` to this table.

### Model Relationships
**`App\Models\Event`**:
```php
public function seoMetadata(): MorphOne
{
    return $this->morphOne(SeoMetadata::class, 'page');
}

public function paymentBanks(): BelongsToMany
{
    return $this->belongsToMany(PaymentBank::class, 'event_payment_bank')
                ->withTimestamps();
}
```

### Form Structure (Filament Resource)
The `EventResource::form()` schema uses `Wizard::make()`:

**Step 3: Media & SEO**
-   **Banner**: Existing `FileBucket` `morphOne` or `morphMany`.
-   **Promo Image**: New field/relation, likely `FileBucket` or direct column.
-   **SEO Section**:
    -   Uses `Group` or `Section` with relationship capability to `seoMetadata`.
    -   Fields: `seoMetadata.title`, `seoMetadata.description`, `seoMetadata.og_image` (FileUpload).
    -   *Crucial*: If the record doesn't exist yet, Filament handles the creation of the polymorphic relation on save.

**Step 5: Organizer & Payment**
-   **Payment Banks**:
    -   `Select::make('paymentBanks')`
    -   `relationship('paymentBanks', 'bank_name')`
    -   `multiple()`
    -   `preload()`

## UI/UX Considerations
-   **Wizard Navigation**: Allow skipping steps (`skippable()`) to prevent user frustration if they don't have all data immediately.
-   **Validation**: Validate steps on navigation or final submission? Filament wizards validate the current step before proceeding by default.

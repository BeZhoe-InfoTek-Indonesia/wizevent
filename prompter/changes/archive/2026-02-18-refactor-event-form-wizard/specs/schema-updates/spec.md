
# Schema Updates

## ADDED Requirements

### Requirement: Event Short Description Field
The events table SHALL include a nullable `short_description` column for use in event listings or cards where full description is too long.

#### Scenario: Supporting Short Description
- When viewing the events table schema
- There should be a `short_description` column of type `text` or `string` that is nullable
- To be used in event listings or cards where full description is too long

### Requirement: Event Promo Image Storage
The system SHALL support storing a promotional image distinct from the event banner using the `FileBucket` polymorphic relationship.

#### Scenario: Supporting Event Promo Image
- When managing event images
- There should be a mechanism to store a `promo_image` distinct from the banner
- Using the `FileBucket` polymorphic relationship with `bucket_type = 'event-promos'` is the preferred approach effectively.

### Requirement: Event Payment Bank Relationship
The system SHALL support linking multiple payment banks to a single event through a pivot table.

#### Scenario: Linking Payment Banks to Events (Multiple)
- There should be a pivot table `event_payment_bank` where
    - `event_id` references `events`.`id`
    - `payment_bank_id` references `payment_banks`.`id`
- This allows multiple banks to be associated with a single event.

### Requirement: Event SEO Metadata Relationship
The system SHALL support SEO metadata for events through a polymorphic relationship to the `seo_metadata` table.

#### Scenario: Linking SEO Metadata to Events (Polymorph)
- The events table should implement a `morphOne` or polymorphic relationship to the `seo_metadata` table.
- When creating or editing an event, the SEO title, description, and OG image should be stored in the `seo_metadata` table, keyed by `page_type` (Event::class) and `page_id` (Event->id).

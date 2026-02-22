# Refactor Event Form to Wizard

**Status**: [Draft]
**Owner**: [User]

## Summary
Refactor the Event creation and edit form into a 5-step wizard to improve user experience and logical flow. Introduce new fields and relationships to complete the event management capabilities.

## Motivation
The current event form is monolithic and can be overwhelming. Breaking it down into logical steps (Basic Info, Location/Time, Media/SEO, Sales, Organizer) guides the user through the process more effectively. New requirements like SEO metadata, promotional images, and bank information need to be integrated.

## Proposed Solution
Convert the `EventResource` form schema to use `Filament\Forms\Components\Wizard`. Update the database schema to support new fields (short description, promo image, bank info) and implementing the necessary relationships.

### Wizard Steps
1. **Basic Information**: Title, Slug, Category, Short/Detailed Description.
2. **Location & Time**: City, Map, Lat/Long, Start/End Time.
3. **Media & SEO**: Banner, Promo Image, SEO Metadata.
4. **Sales Configuration**: Sales Time, Ticket Types, Quota, Seating.
5. **Organizer**: Organizers, Performers, Payment Bank Info.

## Dependencies
- `Filament\Forms\Components\Wizard`
- `Spatie\Tags` (existing)
- `Laravolt\Indonesia` (existing)
- New migrations for `events` table and pivot tables.

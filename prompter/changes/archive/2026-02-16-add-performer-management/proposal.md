# Change: Add Performer Management

## Why
Events need to showcase performers (artists, musicians, speakers, etc.) who are participating in the event. This will enhance event promotion and provide visitors with detailed information about the performers they can expect to see.

## What Changes
- **NEW**: Add Performer model with fields: name, description, phone, photo, type/genre, profession/title, is_active
- **NEW**: Create database migration for performers table with soft deletes and audit fields (created_by, updated_by)
- **NEW**: Add many-to-many relationship between Performers and Events (event_performer pivot table)
- **NEW**: Create Filament admin resource for managing performers in "Master Data" navigation group
- **NEW**: Add internationalization support (English and Indonesian) for performer-related labels and messages
- **NEW**: Add performer seeder for initial test data
- **BREAKING**: No breaking changes - this is a new feature

## Impact
- Affected specs: NEW capability `performer-management`
- Affected code:
  - New model: `app/Models/Performer.php`
  - New Filament resource: `app/Filament/Resources/Performers/`
  - New migration: `database/migrations/*_create_performers_table.php`
  - New migration: `database/migrations/*_create_event_performer_table.php`
  - Update Event model: Add `performers()` relationship
  - New seeder: `database/seeders/PerformerSeeder.php`
  - New language files: `lang/en/performer.php`, `lang/id/performer.php`

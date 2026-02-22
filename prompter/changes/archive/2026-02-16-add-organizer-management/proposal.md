# Change: Add Organizer Master Data Management

## Why
The system needs to manage event organizers with detailed profiles including contact information, logos, social media, and addresses. Events should be able to associate with multiple organizers (co-hosting scenarios), similar to how events currently have categories and tags.

## What Changes
- Create new `organizers` table with full profile fields (name, description, email, phone, website, social media, address, logo)
- Create `event_organizer` pivot table for many-to-many relationship between events and organizers
- Create `Organizer` Eloquent model with relationships
- Add Filament Resource for organizer management in "Master Data" group with modal create/edit
- Add relationship to Event model for organizers
- Add internationalization support for organizer-related labels (en/id)
- Update existing Event Filament resource to include organizer relationship manager
- Add seeder for initial organizer data

## Impact
- Affected specs: `organizer-management` (new), `event-management` (modified)
- Affected code:
  - New: `app/Models/Organizer.php`
  - New: `app/Filament/Resources/OrganizerResource.php`
  - Modified: `app/Models/Event.php` (add organizers relationship)
  - Modified: `app/Filament/Resources/EventResource.php` (add organizer relation manager)
  - New: Database migrations for organizers and event_organizer tables
  - New: Translation files for organizer labels
  - New: Seeder for initial organizer data

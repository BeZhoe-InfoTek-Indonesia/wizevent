## 1. Database Schema
- [x] 1.1 Create migration for `organizers` table with all required fields
- [x] 1.2 Create migration for `event_organizer` pivot table
- [x] 1.3 Run migrations to create tables

## 2. Model & Relationships
- [x] 2.1 Create `Organizer` model with fillable fields and casts
- [x] 2.2 Add relationship methods to Organizer model (events, logo)
- [x] 2.3 Add `organizers()` relationship to Event model
- [x] 2.4 Add `syncOrganizers()` helper method to Event model

## 3. Filament Admin Interface
- [x] 3.1 Create `OrganizerResource` in Filament/Resources directory
- [x] 3.2 Configure form schema with all fields (name, description, email, phone, website, social media, address, logo)
- [x] 3.3 Configure table columns with proper sorting and search
- [x] 3.4 Add to "Master Data" navigation group
- [x] 3.5 Implement modal for create and edit actions
- [x] 3.6 Add logo upload with FileBucket polymorphic relationship
- [x] 3.7 Add `OrganizersRelationManager` to EventResource

## 4. Internationalization
- [x] 4.1 Create `lang/en/organizer.php` with English translations
- [x] 4.2 Create `lang/id/organizer.php` with Indonesian translations
- [x] 4.3 Apply translations to Filament resource fields and labels

## 5. Data Seeding
- [x] 5.1 Create `OrganizerSeeder` with sample organizer data
- [x] 5.2 Register seeder in DatabaseSeeder
- [x] 5.3 Run seeder to populate initial organizers

## Post-Implementation
- [x] Update AGENTS.md in the project root for new changes (Organizer model, Event relationship)
- [ ] Test organizer CRUD operations in Filament admin
- [ ] Test event-organizer association in EventResource
- [ ] Verify modal create/edit functionality
- [ ] Test logo upload functionality
- [ ] Verify translations for both languages

## 1. Database & Model Implementation
- [x] 1.1 Create migration for `performers` table with all required fields (name, description, phone, photo_file_bucket_id, type, profession, is_active, created_by, updated_by, soft_deletes, timestamps)
- [x] 1.2 Create migration for `event_performer` pivot table (event_id, performer_id) for many-to-many relationship
- [x] 1.3 Create `Performer` model in `app/Models/Performer.php` with proper relationships (belongsTo User for audit fields, belongsTo FileBucket for photo, belongsToMany Event)
- [x] 1.4 Add `performers()` relationship method to `Event` model
- [x] 1.5 Add `syncPerformers()` method to `Event` model for bulk sync
- [x] 1.6 Run migrations and verify database schema

## 2. Filament Admin Resource
- [x] 2.1 Create `PerformerResource` class in `app/Filament/Resources/Performers/PerformerResource.php`
- [x] 2.2 Create `PerformerForm` schema in `app/Filament/Resources/Performers/Schemas/PerformerForm.php` with all form fields (name, description, phone, photo, type, profession, is_active toggle)
- [x] 2.3 Create `PerformersTable` class in `app/Filament/Resources/Performers/Tables/PerformersTable.php` with columns (photo, name, phone, type, profession, is_active, events_count, created_at)
- [x] 2.4 Create `ListPerformers` page in `app/Filament/Resources/Performers/Pages/ListPerformers.php`
- [x] 2.5 Configure resource to use modal for create/edit actions (not separate pages)
- [x] 2.6 Set navigation group to "Master Data" with appropriate icon and sorting
- [x] 2.7 Add soft delete support (TrashedFilter, Restore actions)

## 3. Event Resource Integration
- [x] 3.1 Create `PerformersRelationManager` in `app/Filament/Resources/EventResource/RelationManagers/PerformersRelationManager.php`
- [x] 3.2 Add `PerformersRelationManager` to EventResource's `getRelations()` method
- [x] 3.3 Test attaching/detaching performers from events in admin panel

## 4. Internationalization
- [x] 4.1 Create `lang/en/performer.php` with all labels, placeholders, and messages in English
- [x] 4.2 Create `lang/id/performer.php` with all labels, placeholders, and messages in Indonesian
- [x] 4.3 Test language switching in admin panel for performer pages

## 5. Data Seeding
- [x] 5.1 Create `PerformerSeeder` in `database/seeders/PerformerSeeder.php` with sample performer data
- [x] 5.2 Add seeder to `DatabaseSeeder`
- [x] 5.3 Run seeder and verify data is populated correctly

## 6. Testing & Validation
- [x] 6.1 Manually test CRUD operations (create, read, update, delete, restore) in Filament admin panel
- [x] 6.2 Test soft delete and restore functionality
- [x] 6.3 Test many-to-many relationship with events (attach, detach, sync performers)
- [x] 6.4 Test photo upload and display
- [x] 6.5 Test filtering and searching in performers table
- [x] 6.6 Test pagination with large datasets
- [x] 6.7 Verify internationalization works correctly for both languages

## 7. Documentation & Code Quality
- [x] 7.1 Add PHPDoc blocks to Performer model (using Laravel IDE Helper format)
- [x] 7.2 Run `php artisan ide-helper:generate` and `php artisan ide-helper:models` to update IDE helper files
- [x] 7.3 Run PHPStan to verify code quality and type safety
- [x] 7.4 Ensure all code follows PSR-12 standards and project conventions

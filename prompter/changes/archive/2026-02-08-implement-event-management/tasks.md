# Tasks: Implement Event Management

## Phase 1: Database & Models (Foundation)

- [x] **Task 1.1**: Create events table migration
   - Add all columns as per design.md (including latitude, longitude, google_place_id)
   - Add indexes for performance (including composite index on latitude/longitude)
   - Add foreign keys for user tracking
   - **Validation**: Run migration successfully, verify schema with `php artisan migrate:status`

- [x] **Task 1.2**: Create ticket_types table migration
   - Add all columns as per design.md
   - Add foreign key to events table with CASCADE delete
   - Add indexes
   - **Validation**: Run migration successfully

- [x] **Task 1.3**: Implement Dynamic Settings System (Master Data)
   - Create `settings` and `setting_components` table migrations
   - Implement `Setting` and `SettingComponent` models
   - Replaces static `event_categories` and `event_tags` tables
   - **Validation**: Run migrations successfully, verify models can store hierarchical data

- [x] **Task 1.4**: Implement Polymorphic File Storage (File Buckets)
   - Create `file_buckets` table migration
   - Implement `FileBucket` model with polymorphic relationships
   - **Validation**: Run migration successfully, verify model can be linked to any entity

- [x] **Task 1.5**: Create Event model
   - Add fillable fields, casts, relationships (with Settings and FileBuckets)
   - Add accessors for computed properties
   - Add scopes (published, draft, upcoming, past)
   - Add business logic methods (canBePublished, canBeCancelled, canBeDeleted)
   - Add activity logging trait
   - **Validation**: Model can be instantiated, relationships work

- [x] **Task 1.6**: Create TicketType model
   - Add fillable fields, casts, relationships
   - Add accessors for available_count, is_sold_out, is_available_for_sale
   - Add business logic methods (canPurchase, reserveTickets)
   - Add soft deletes
   - **Validation**: Model can be instantiated, relationships work

- [x] **Task 1.7**: Create Setting and SettingComponent models
   - Add fillable fields, casts, and hierarchical logic
   - **Validation**: Models can be instantiated and linked to events

- [x] **Task 1.8**: Create Default Master Data Seeders
   - Seed default categories and tags via Settings system
   - **Validation**: Run seeder, verify data in database

## Phase 2: Services & Business Logic

- [x] **Task 2.1**: Create EventService
   - Implement createEvent() with slug generation
   - Implement updateEvent() with slug update logic
   - Implement publishEvent() with validation
   - Implement cancelEvent() with reason logging
   - Implement validateForPublishing()
   - Implement calculateTotalCapacity()
   - **Validation**: Unit tests pass for all methods

- [x] **Task 2.2**: Create TicketTypeService
   - Implement createTicketType() with capacity update
   - Implement updateTicketType() with validation
   - Implement deleteTicketType() with sold check
   - Implement reserveTickets() with database locking
   - **Validation**: Unit tests pass, concurrent reservation test passes

- [x] **Task 2.3**: Create FileBucketService (Image & File Processing)
   - Implement upload() and uploadMultiple() for polymorphic entities
   - Implement processImage() with thumbnail generation using Intervention Image
   - Implement delete() and deleteAll() cleanup logic
   - **Validation**: Upload image, verify original + thumbnails created in bucket path

- [x] **Task 2.4**: Add event permissions to PermissionSeeder
   - Add 6 event permissions (view, create, edit, publish, delete, cancel)
   - Add settings and file_bucket permissions
   - Update role assignments
   - **Validation**: Run seeder, verify permissions in database

## Phase 3: Global Enhancements & Filament Admin

- [x] **Task 3.1**: Implement Multi-language Support (EN/ID)
   - Create global `SetLocale` middleware and `LanguageController`
   - Implement language switcher for Visitors (Welcome page)
   - Implement language switcher for Admin (User Menu items)
   - Create translation files:
     - `lang/en/welcome.php`, `lang/id/welcome.php`
     - `lang/en/user.php`, `lang/id/user.php`
     - `lang/en/role.php`, `lang/id/role.php`
     - `lang/en/permission.php`, `lang/id/permission.php`
     - `lang/en/event.php`, `lang/id/event.php`
   - **Validation**: Language switching works across all platforms

- [x] **Task 3.2**: Implement Modal-based CRUD pattern
   - Refactor `UserResource`, `RoleResource`, and `PermissionResource`
   - Enable `->modal()` or `->slideOver()` for Create and Edit actions
   - Remove standalone Create/Edit pages for better UX
   - **Validation**: Forms open in modals, keeping user context on the list page

- [x] **Task 3.3**: Create SettingResource (Master Data Management)
   - Implement `SettingResource` for managing global configurations
   - Implement `SettingComponentsRelationManager` for sub-items
   - Add support for different types (string, integer, boolean)
   - **Validation**: Master data can be managed dynamically

- [x] **Task 3.4**: Implement EventResource structure
   - Create EventResource.php with navigation group and ordering
   - Create Schemas/EventsForm.php with specialized components
   - Create Tables/EventsTable.php with status badges
   - **Validation**: Resource appears in Filament navigation

- [x] **Task 3.5**: Implement Advanced Form Components
   - **Add TipTap rich text editor for description** (Filament's RichEditor component)
   - **Add Google Maps location picker** with Places Autocomplete
     - Custom Filament field component created
     - Hidden fields for latitude, longitude, google_place_id
   - Integrate `FileBucketService` for Banner Image uploads
   - **Validation**: Advanced fields render and persist data correctly

- [x] **Task 3.6**: Implement EventsTable configuration
   - Add columns with translations (title, category, event_date, status, tickets)
   - Add filters (status, category)
   - Add actions (edit, publish, cancel)
   - **Validation**: Table displays correctly, filters work

- [x] **Task 3.7**: Create TicketTypesRelationManager
   - Create relation manager for ticket types nested in EventResource
   - Implement form with price, quantity, and sales limits
   - **Validation**: Can manage ticket tiers within the event form

## Phase 4: Policies & Authorization

- [x] **Task 4.1**: Create EventPolicy
   - Implement viewAny, view, create, update, delete methods
   - Add ownership check for Event Managers
   - Add restore, forceDelete, and workflow-specific permissions
   - **Validation**: Policy tests pass

- [x] **Task 4.2**: Create TicketTypePolicy
   - Implement methods based on parent event ownership
   - **Validation**: Policy tests pass

- [x] **Task 4.3**: Register policies & Create Factories
   - Laravel auto-discovers policies
   - Create Factories for Event, TicketType, and Settings for testing
   - **Validation**: Seeders and tests have access to required mock data

## Phase 5: Testing

- [ ] **Task 5.1**: Write Event model tests
  - Test state transitions
  - Test accessors and scopes
  - Test business logic methods
  - **Validation**: All model tests pass

- [ ] **Task 5.2**: Write TicketType model tests
  - Test inventory calculations
  - Test validation rules
  - **Validation**: All model tests pass

- [ ] **Task 5.3**: Write EventService tests
  - Test createEvent with slug generation
  - Test publishEvent with validation
  - Test cancelEvent
  - Test validateForPublishing
  - **Validation**: All service tests pass

- [ ] **Task 5.4**: Write TicketTypeService tests
  - Test reserveTickets with locking
  - Test concurrent reservation scenario
  - Test capacity calculations
  - **Validation**: All service tests pass, concurrent test passes

- [ ] **Task 5.5**: Write ImageService tests
  - Test image processing
  - Test thumbnail generation
  - Test image deletion
  - **Validation**: All image tests pass

- [ ] **Task 5.6**: Write feature tests for event CRUD
  - Test event creation flow
  - Test event editing flow
  - Test publishing workflow
  - Test cancellation flow
  - Test deletion restrictions
  - **Validation**: All feature tests pass

- [ ] **Task 5.7**: Write feature tests for ticket types
  - Test ticket type CRUD
  - Test inventory tracking
  - Test validation rules
  - **Validation**: All ticket type tests pass

- [ ] **Task 5.8**: Write policy tests
  - Test Event Manager can only edit own events
  - Test Super Admin can edit all events
  - Test permission checks
  - **Validation**: All policy tests pass

## Phase 6: Documentation & Finalization

- [ ] **Task 6.1**: Update AGENTS.md
  - Document Event and TicketType models
  - Document EventService and TicketTypeService
  - Document event lifecycle states
  - **Validation**: Documentation is complete and accurate

- [ ] **Task 6.2**: Create event management user guide
  - Document how to create events
  - Document publishing workflow
  - Document ticket type management
  - Add screenshots
  - **Validation**: Guide is clear and comprehensive

- [ ] **Task 6.3**: Run full test suite
  - Run all unit tests
  - Run all feature tests
  - Verify code coverage
  - **Validation**: All tests pass, coverage > 80%

- [ ] **Task 6.4**: Manual QA testing
  - Test complete event creation flow
  - Test image upload with various formats/sizes
  - **Test Google Maps location picker** (autocomplete, coordinates, map preview)
  - Test TipTap rich text editor functionality
  - Test publishing validation
  - Test state transitions
  - Test ticket type management
  - Test concurrent inventory updates
  - Test permissions for different roles
  - **Validation**: All manual tests pass

- [ ] **Task 6.5**: Performance testing
  - Test event list page with 1000+ events
  - Test concurrent ticket reservations
  - Verify database indexes are used
  - **Validation**: Performance is acceptable

## Dependencies

### Task Dependencies
- Tasks 2.x depend on Tasks 1.x (models must exist before services)
- Tasks 3.x depend on Tasks 1.x and 2.x (UI depends on models and services)
- Tasks 4.x depend on Tasks 1.x (policies depend on models)
- Tasks 5.x can run in parallel after their dependencies are met
- Tasks 6.x depend on all previous tasks

### Parallelizable Work
- Task 1.1-1.4 (migrations) can be done in parallel
- Task 1.5-1.7 (models) can be done in parallel after migrations
- Task 3.2-3.9 (Filament resources) can be done in parallel after services
- Task 5.1-5.8 (tests) can be done in parallel after implementation

## Estimated Effort
- Phase 1: 8 hours
- Phase 2: 12 hours
- Phase 3: 16 hours
- Phase 4: 4 hours
- Phase 5: 16 hours
- Phase 6: 8 hours
- **Total**: ~64 hours (~8 working days)

## Success Metrics
- [ ] All migrations run successfully
- [ ] All models have proper relationships
- [ ] All services implement business logic correctly
- [ ] All Filament resources are functional
- [ ] All tests pass (unit, feature, policy)
- [ ] Code coverage > 80%
- [ ] Manual QA passes
- [ ] Documentation is complete
- [ ] Performance is acceptable

# performer-management Specification

## Purpose
Manage event performers (artists, musicians, speakers, etc.) with full profile information including photos, contact details, and categorization. Enable many-to-many relationships with events for flexible performer assignment.

## ADDED Requirements

### Requirement: Performer Model
The system SHALL provide a Performer model to manage event performers with comprehensive profile information and status management.

#### Scenario: Create performer with all fields
- **WHEN** an administrator creates a new performer
- **THEN** the performer is saved with all provided fields (name, description, phone, photo, type, profession, is_active)
- **AND** the `created_by` field is set to the current user's ID
- **AND** the performer is successfully persisted to the database

#### Scenario: Update performer profile
- **WHEN** an administrator edits an existing performer
- **THEN** the performer fields are updated with the new values
- **AND** the `updated_by` field is set to the current user's ID
- **AND** the changes are persisted to the database

#### Scenario: Soft delete performer
- **WHEN** an administrator deletes a performer
- **THEN** the `deleted_at` timestamp is set (soft delete)
- **AND** the performer is no longer visible in standard queries
- **AND** the performer data remains in the database for recovery

#### Scenario: Toggle performer active status
- **WHEN** an administrator toggles the `is_active` field
- **THEN** the performer's active status is updated
- **AND** inactive performers are excluded from frontend displays (if applicable)

### Requirement: Performer Photo Management
The system SHALL support uploading and associating a profile photo with each performer.

#### Scenario: Upload performer photo
- **WHEN** an administrator uploads a photo image for a performer
- **THEN** the image is stored via FileBucket polymorphic relationship
- **AND** the `photo_file_bucket_id` is set on the performer record
- **AND** the photo is accessible through the `photo` relationship

#### Scenario: Remove performer photo
- **WHEN** an administrator removes or replaces a performer photo
- **THEN** the `photo_file_bucket_id` is set to null
- **AND** the previous photo file may remain in storage (or be deleted based on policy)

#### Scenario: Display performer photo
- **WHEN** the system retrieves a performer
- **THEN** the photo is accessible through the `photo` relationship
- **AND** the photo URL is available for display in the admin interface

### Requirement: Performer Filament Resource
The system SHALL provide a Filament admin resource for managing performers in the "Master Data" navigation group.

#### Scenario: List performers in admin panel
- **WHEN** an administrator with `performers.view` permission accesses the performers page
- **THEN** a table displays all performers with columns for photo, name, phone, type, profession, is_active, and events_count
- **AND** the table supports sorting and searching
- **AND** pagination is applied for large datasets

#### Scenario: Create performer via modal
- **WHEN** an administrator with `performers.create` permission clicks the create button
- **THEN** a modal dialog appears with form fields for all performer properties
- **AND** the form includes validation for required fields (name, type, profession)
- **AND** upon submission, the performer is created and the modal closes

#### Scenario: Edit performer via modal
- **WHEN** an administrator with `performers.edit` permission clicks the edit action on a performer
- **THEN** a modal dialog appears with the performer's current data pre-filled
- **AND** the administrator can modify any field
- **AND** upon submission, the performer is updated and the modal closes

#### Scenario: Delete performer
- **WHEN** an administrator with `performers.delete` permission clicks the delete action on a performer
- **THEN** a confirmation dialog appears
- **AND** upon confirmation, the performer is soft deleted
- **AND** the performer is removed from the list view

#### Scenario: Permission-based access
- **WHEN** a user without `performers.view` permission attempts to access the performers page
- **THEN** access is denied with an error message
- **AND** the user cannot view or manage performers

### Requirement: Event-Performer Relationship
The system SHALL support many-to-many relationships between events and performers, allowing multiple performers per event and multiple events per performer.

#### Scenario: Attach performer to event
- **WHEN** an administrator associates one or more performers with an event
- **THEN** records are created in the `event_performer` pivot table
- **AND** the relationship is stored with `event_id` and `performer_id`
- **AND** the performers are accessible through the `performers()` relationship on the event

#### Scenario: Detach performer from event
- **WHEN** an administrator removes a performer from an event
- **THEN** the corresponding record is deleted from the `event_performer` pivot table
- **AND** the performer is no longer associated with the event

#### Scenario: Sync performers for event
- **WHEN** an administrator uses the `syncPerformers()` method on an event
- **THEN** the performer associations are updated to match the provided array
- **AND** performers not in the array are detached
- **AND** performers in the array but not currently associated are attached
- **AND** existing associations are maintained

#### Scenario: Query events by performer
- **WHEN** the system queries events for a specific performer
- **THEN** the query returns all events associated with that performer
- **AND** the query uses the many-to-many relationship

### Requirement: Performer Management in Event Resource
The system SHALL provide a relation manager in the Event Filament resource for managing event-performer associations.

#### Scenario: View event performers
- **WHEN** an administrator views an event in the admin panel
- **THEN** a "Performers" section displays all associated performers
- **AND** the performers are listed with their key details (name, type, profession, phone)

#### Scenario: Add performer to event
- **WHEN** an administrator clicks "Add Performer" in the event relation manager
- **THEN** a list of available performers is displayed
- **AND** the administrator can select one or more performers
- **AND** upon selection, the performers are attached to the event

#### Scenario: Remove performer from event
- **WHEN** an administrator clicks the detach action on a performer in the event relation manager
- **THEN** the performer association is removed from the event
- **AND** the performer is removed from the list

### Requirement: Performer Internationalization
The system SHALL provide internationalization support for performer-related labels and messages in English and Indonesian.

#### Scenario: English labels
- **WHEN** the application locale is set to 'en'
- **THEN** all performer-related labels and messages are displayed in English
- **AND** translation keys from `lang/en/performer.php` are used

#### Scenario: Indonesian labels
- **WHEN** the application locale is set to 'id'
- **THEN** all performer-related labels and messages are displayed in Indonesian
- **AND** translation keys from `lang/id/performer.php` are used

#### Scenario: Missing translation fallback
- **WHEN** a translation key is missing for the current locale
- **THEN** the system falls back to the English translation
- **AND** no error is displayed

### Requirement: Performer Data Seeding
The system SHALL provide a seeder to populate initial performer data for testing and demonstration purposes.

#### Scenario: Run performer seeder
- **WHEN** the performer seeder is executed
- **THEN** multiple sample performers are created with realistic data
- **AND** each performer has a name, description, phone, type, profession, and is_active status
- **AND** some performers may have photos

#### Scenario: Seeder integration
- **WHEN** the database is seeded with all seeders
- **THEN** the performer seeder is included and executed
- **AND** the initial performer data is available for use

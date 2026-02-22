# organizer-management Specification

## Purpose
TBD - created by archiving change add-organizer-management. Update Purpose after archive.
## Requirements
### Requirement: Organizer Model
The system SHALL provide an Organizer model to manage event organizers with full profile information.

#### Scenario: Create organizer with all fields
- **WHEN** an administrator creates a new organizer
- **THEN** the organizer is saved with all provided fields (name, description, email, phone, website, social_media, address, logo)
- **AND** the `created_by` field is set to the current user's ID
- **AND** the organizer is successfully persisted to the database

#### Scenario: Update organizer profile
- **WHEN** an administrator edits an existing organizer
- **THEN** the organizer fields are updated with the new values
- **AND** the `updated_by` field is set to the current user's ID
- **AND** the changes are persisted to the database

#### Scenario: Soft delete organizer
- **WHEN** an administrator deletes an organizer
- **THEN** the `deleted_at` timestamp is set (soft delete)
- **AND** the organizer is no longer visible in standard queries
- **AND** the organizer data remains in the database for recovery

#### Scenario: Social media field stores JSON
- **WHEN** an administrator sets social media links
- **THEN** the data is stored as JSON in the `social_media` column
- **AND** the JSON structure supports multiple platforms (facebook, twitter, instagram, linkedin, etc.)
- **AND** the data is properly cast when retrieved from the database

### Requirement: Organizer Logo Management
The system SHALL support uploading and associating a logo image with each organizer.

#### Scenario: Upload organizer logo
- **WHEN** an administrator uploads a logo image for an organizer
- **THEN** the image is stored via FileBucket polymorphic relationship
- **AND** the `logo_file_bucket_id` is set on the organizer record
- **AND** the logo is accessible through the `logo` relationship

#### Scenario: Remove organizer logo
- **WHEN** an administrator removes or replaces an organizer logo
- **THEN** the `logo_file_bucket_id` is set to null
- **AND** the previous logo file may remain in storage (or be deleted based on policy)

#### Scenario: Display organizer logo
- **WHEN** the system retrieves an organizer
- **THEN** the logo is accessible through the `logo` relationship
- **AND** the logo URL is available for display in the admin interface

### Requirement: Organizer Filament Resource
The system SHALL provide a Filament admin resource for managing organizers in the "Master Data" navigation group.

#### Scenario: List organizers in admin panel
- **WHEN** an administrator with `organizers.view` permission accesses the organizers page
- **THEN** a table displays all organizers with columns for name, email, phone, website, and created_at
- **AND** the table supports sorting and searching
- **AND** pagination is applied for large datasets

#### Scenario: Create organizer via modal
- **WHEN** an administrator with `organizers.create` permission clicks the create button
- **THEN** a modal dialog appears with form fields for all organizer properties
- **AND** the form includes validation for required fields (name)
- **AND** upon submission, the organizer is created and the modal closes

#### Scenario: Edit organizer via modal
- **WHEN** an administrator with `organizers.edit` permission clicks the edit action on an organizer
- **THEN** a modal dialog appears with the organizer's current data pre-filled
- **AND** the administrator can modify any field
- **AND** upon submission, the organizer is updated and the modal closes

#### Scenario: Delete organizer
- **WHEN** an administrator with `organizers.delete` permission clicks the delete action on an organizer
- **THEN** a confirmation dialog appears
- **AND** upon confirmation, the organizer is soft deleted
- **AND** the organizer is removed from the list view

#### Scenario: Permission-based access
- **WHEN** a user without `organizers.view` permission attempts to access the organizers page
- **THEN** access is denied with an error message
- **AND** the user cannot view or manage organizers

### Requirement: Event-Organizer Relationship
The system SHALL support many-to-many relationships between events and organizers, allowing multiple organizers per event.

#### Scenario: Attach organizer to event
- **WHEN** an administrator associates one or more organizers with an event
- **THEN** records are created in the `event_organizer` pivot table
- **AND** the relationship is stored with `event_id` and `organizer_id`
- **AND** the organizers are accessible through the `organizers()` relationship on the event

#### Scenario: Detach organizer from event
- **WHEN** an administrator removes an organizer from an event
- **THEN** the corresponding record is deleted from the `event_organizer` pivot table
- **AND** the organizer is no longer associated with the event

#### Scenario: Sync organizers for event
- **WHEN** an administrator uses the `syncOrganizers()` method on an event
- **THEN** the organizer associations are updated to match the provided array
- **AND** organizers not in the array are detached
- **AND** organizers in the array but not currently associated are attached
- **AND** existing associations are maintained

#### Scenario: Query events by organizer
- **WHEN** the system queries events for a specific organizer
- **THEN** the query returns all events associated with that organizer
- **AND** the query uses the many-to-many relationship

### Requirement: Organizer Management in Event Resource
The system SHALL provide a relation manager in the Event Filament resource for managing event-organizer associations.

#### Scenario: View event organizers
- **WHEN** an administrator views an event in the admin panel
- **THEN** an "Organizers" section displays all associated organizers
- **AND** the organizers are listed with their key details (name, email, phone)

#### Scenario: Add organizer to event
- **WHEN** an administrator clicks "Add Organizer" in the event relation manager
- **THEN** a list of available organizers is displayed
- **AND** the administrator can select one or more organizers
- **AND** upon selection, the organizers are attached to the event

#### Scenario: Remove organizer from event
- **WHEN** an administrator clicks the detach action on an organizer in the event relation manager
- **THEN** the organizer association is removed from the event
- **AND** the organizer is removed from the list

### Requirement: Organizer Internationalization
The system SHALL provide internationalization support for organizer-related labels and messages in English and Indonesian.

#### Scenario: English labels
- **WHEN** the application locale is set to 'en'
- **THEN** all organizer-related labels and messages are displayed in English
- **AND** translation keys from `lang/en/organizer.php` are used

#### Scenario: Indonesian labels
- **WHEN** the application locale is set to 'id'
- **THEN** all organizer-related labels and messages are displayed in Indonesian
- **AND** translation keys from `lang/id/organizer.php` are used

#### Scenario: Missing translation fallback
- **WHEN** a translation key is missing for the current locale
- **THEN** the system falls back to the English translation
- **AND** no error is displayed

### Requirement: Organizer Data Seeding
The system SHALL provide a seeder to populate initial organizer data for testing and demonstration purposes.

#### Scenario: Run organizer seeder
- **WHEN** the organizer seeder is executed
- **THEN** multiple sample organizers are created with realistic data
- **AND** each organizer has a name, description, email, phone, website, and address
- **AND** some organizers may have social media links and logos

#### Scenario: Seeder integration
- **WHEN** the database is seeded with all seeders
- **THEN** the organizer seeder is included and executed
- **AND** the initial organizer data is available for use


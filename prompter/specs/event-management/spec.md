# event-management Specification

## Purpose
TBD - created by archiving change implement-event-management. Update Purpose after archive.
## Requirements
### Requirement: Event Creation
Event organizers must be able to create new events with all required information.

#### Scenario: Create event with basic information
**Given** an authenticated user with `events.create` permission  
**When** they create a new event with title, description, date, and location  
**Then** the event is created with status "draft"  
**And** a unique slug is generated from the title  
**And** the creator is recorded in `created_by`  
**And** an activity log entry is created

#### Scenario: Create event with banner image
**Given** an authenticated user with `events.create` permission  
**When** they create an event and upload a banner image (JPG/PNG/WebP, ≤5MB)  
**Then** the image is stored in `storage/app/public/events/banners/{event-id}/`  
**And** three thumbnails are generated (400x300, 800x600, 1200x900)  
**And** the image paths are stored in the event record

#### Scenario: Create event with invalid image
**Given** an authenticated user with `events.create` permission  
**When** they attempt to upload an image larger than 5MB or invalid format  
**Then** the upload is rejected with a validation error  
**And** the event is not created

### Requirement: Event Editing
Event organizers must be able to edit their events.

#### Scenario: Edit draft event
**Given** an authenticated user with `events.edit` permission  
**And** an event in "draft" status  
**When** they update any event field  
**Then** the changes are saved  
**And** the `updated_by` field is updated  
**And** an activity log entry is created

#### Scenario: Edit published event
**Given** an authenticated user with `events.edit` permission  
**And** an event in "published" status  
**When** they update non-critical fields (description, location, etc.)  
**Then** the changes are saved  
**And** the event remains published

#### Scenario: Event Manager can only edit own events
**Given** an authenticated user with role "Event Manager"  
**And** an event created by another user  
**When** they attempt to edit the event  
**Then** access is denied with a 403 error

### Requirement: Event Publishing
Events must go through a validation workflow before being published.

#### Scenario: Publish valid event
**Given** an authenticated user with `events.publish` permission  
**And** a draft event with all required fields (title, description, date, location, banner, at least one active ticket type)  
**When** they publish the event  
**Then** the status changes to "published"  
**And** `published_at` is set to current timestamp  
**And** the event becomes visible in public search  
**And** an activity log entry is created

#### Scenario: Attempt to publish incomplete event
**Given** an authenticated user with `events.publish` permission  
**And** a draft event missing required fields  
**When** they attempt to publish the event  
**Then** publishing is blocked with validation errors  
**And** the event remains in "draft" status

#### Scenario: Attempt to publish event without ticket types
**Given** an authenticated user with `events.publish` permission  
**And** a draft event with no active ticket types  
**When** they attempt to publish the event  
**Then** publishing is blocked with error "At least one active ticket type is required"  
**And** the event remains in "draft" status

### Requirement: Event Cancellation
Published events must be cancellable with a reason.

#### Scenario: Cancel published event
**Given** an authenticated user with `events.cancel` permission  
**And** a published event  
**When** they cancel the event with a reason  
**Then** the status changes to "cancelled"  
**And** the cancellation reason is stored  
**And** an activity log entry is created

#### Scenario: Cannot un-cancel event
**Given** a cancelled event  
**When** any user attempts to change status back to published  
**Then** the operation is blocked  
**And** an error is returned

### Requirement: Event Deletion
Only draft events can be deleted.

#### Scenario: Delete draft event
**Given** an authenticated user with `events.delete` permission  
**And** an event in "draft" status  
**When** they delete the event  
**Then** the event is soft-deleted  
**And** all associated ticket types are soft-deleted (cascade)  
**And** an activity log entry is created

#### Scenario: Cannot delete published event
**Given** an authenticated user with `events.delete` permission  
**And** an event in "published" status  
**When** they attempt to delete the event  
**Then** the operation is blocked with error "Published events cannot be deleted. Cancel instead."

### Requirement: Event Categorization
Events MUST support assignment to categories, tags, and organizers for organization and filtering purposes.

#### Scenario: Assign category to event
**Given** an authenticated user with `events.edit` permission
**When** they assign a category to an event
**Then** category relationship is established
**And** event can be filtered by category

#### Scenario: Assign tags to event
**Given** an authenticated user with `events.edit` permission
**When** they assign multiple tags to an event
**Then** tag relationships are established
**And** event can be filtered by tags

#### Scenario: Assign organizers to event
**Given** an authenticated user with `events.edit` permission
**When** they assign one or more organizers to an event
**Then** organizer relationships are established via `event_organizer` pivot table
**And** event can be filtered by organizer
**And** multiple organizers can be associated with a single event (co-hosting)

#### Scenario: Remove organizer from event
**Given** an authenticated user with `events.edit` permission
**And** an event with associated organizers
**When** they remove an organizer from the event
**Then** organizer relationship is detached
**And** other organizer associations remain intact

#### Scenario: Sync organizers for event
**Given** an authenticated user with `events.edit` permission
**And** an event with existing organizer associations
**When** they use the syncOrganizers() method with a new array of organizer IDs
**Then** organizers not in the array are detached
**And** organizers in the array but not associated are attached
**And** existing associations are maintained
**And** all changes are persisted to the database

### Requirement: Event Visibility
Draft events must only be visible to creators and admins.

#### Scenario: Draft event visibility in admin
**Given** an Event Manager viewing the events list  
**When** they filter by status "draft"  
**Then** they see only their own draft events  
**And** they do not see other users' draft events

#### Scenario: Published event visibility
**Given** any authenticated user  
**When** they view published events  
**Then** they see all published events regardless of creator

### Requirement: Slug Generation
Events must have unique, SEO-friendly slugs.

#### Scenario: Auto-generate slug from title
**Given** an authenticated user creating an event  
**When** they enter title "Summer Music Festival 2026"  
**Then** the slug is auto-generated as "summer-music-festival-2026"  
**And** the slug is unique (append number if duplicate)

#### Scenario: Update slug when title changes
**Given** an existing event with slug "old-title"  
**When** the title is changed to "New Title"  
**Then** the slug is updated to "new-title"  
**And** uniqueness is maintained


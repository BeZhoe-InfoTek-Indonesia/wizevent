## Context
The Event Ticket Management System currently manages events with categories and tags (using Setting/SettingComponent pattern). Event organizers need to be managed as separate entities with detailed profiles including contact information, logos, social media, and addresses. Events should be able to associate with multiple organizers to support co-hosting scenarios.

### Current State
- Events have `categories()` and `tags()` relationships to SettingComponents
- Setting pattern works well for simple lists but not complex entities
- Master Data navigation group exists in Filament with Settings, Users, Roles, Permissions
- FileBucket polymorphic relationship is used for event banners

### Requirements
- Organizer profiles with: name, description, email, phone, website, social media, address, logo
- Many-to-many relationship between events and organizers
- Filament admin interface in "Master Data" group
- Modal-based create/edit (following project conventions)
- Internationalization support (English primary, Indonesian secondary)

## Goals / Non-Goals
- Goals:
  - Provide full-featured organizer management in admin panel
  - Enable multi-organizer events (co-hosting)
  - Maintain consistency with existing UI patterns (modal, Master Data group)
  - Support both English and Indonesian languages
- Non-Goals:
  - Public-facing organizer pages (admin only initially)
  - Organizer-specific analytics or reporting
  - Organizer user roles or permissions

## Decisions

### Decision: Dedicated Organizer Model vs Setting Pattern
**Choice:** Create dedicated `Organizer` model instead of using Setting/SettingComponent pattern.

**Reasoning:**
- Organizers have 8+ fields (name, description, email, phone, website, social_media, address, logo)
- Setting pattern is designed for simple key-value lists (categories, tags)
- Complex entities need their own models for clarity and maintainability
- Direct model relationships are cleaner than polymorphic SettingComponent approach

**Alternatives considered:**
1. **Setting pattern** - Rejected: Too complex for SettingComponent structure, would require typecasting and validation overhead
2. **Polymorphic relation to User** - Rejected: Users have different purpose (authentication), organizers are business entities
3. **Dedicated model** - **Selected**: Clear separation, proper relationships, easier to extend

### Decision: Many-to-Many Event Relationship
**Choice:** Events can have multiple organizers.

**Reasoning:**
- Supports co-hosting scenarios (e.g., "Music Festival Inc." + "Local Venue LLC")
- Consistent with existing categories/tags pattern
- Flexible for various event partnership models

**Implementation:**
- Use `event_organizer` pivot table with `event_id` and `organizer_id`
- Add `organizers()` BelongsToMany relationship to Event model
- Add `syncOrganizers()` helper method for easy updating

### Decision: Logo Storage
**Choice:** Use FileBucket polymorphic relationship for organizer logos.

**Reasoning:**
- Consistent with existing Event banner implementation
- Polymorphic design allows reuse across multiple entities
- Already integrated with Filament file upload

## Database Schema

### organizers Table
```sql
- id (primary key)
- name (string, required)
- description (text, nullable)
- email (string, nullable)
- phone (string, nullable)
- website (string, nullable)
- social_media (json, nullable) - stores links to FB, Twitter, IG, etc.
- address (text, nullable)
- logo_file_bucket_id (foreign key to file_buckets, nullable)
- created_by (foreign key to users, nullable)
- updated_by (foreign key to users, nullable)
- deleted_at (soft delete)
- timestamps
```

### event_organizer Pivot Table
```sql
- id (primary key)
- event_id (foreign key to events, cascade on delete)
- organizer_id (foreign key to organizers, cascade on delete)
- created_at
```

## Filament Resource Structure

### OrganizerResource
- **Navigation Group:** "Master Data"
- **Navigation Sort:** 5 (after Settings)
- **Icon:** Heroicon user-group icon
- **Form:** Modal-based create/edit with all fields
- **Table:** Columns for name, email, phone, website, created_at
- **Actions:** View, Edit (modal), Delete (soft delete)

### Event Resource Addition
- **Relation Manager:** OrganizersRelationManager
- **Allows:** Adding/removing organizers from events
- **UI:** Checkbox list or select with search

## Internationalization

### Translation Files
- `lang/en/organizer.php` - Primary language (English)
- `lang/id/organizer.php` - Secondary language (Indonesian)

### Translation Keys
- Labels for all form fields
- Navigation labels
- Table column headings
- Success/error messages
- Validation messages

## Security & Permissions

### Required Permissions
- `organizers.view` - View organizer list
- `organizers.create` - Create new organizers
- `organizers.edit` - Edit organizer details
- `organizers.delete` - Delete organizers

### Access Control
- Use Filament Shield for permission-based access
- Only users with relevant permissions can manage organizers
- Soft delete ensures data recovery

## Migration Plan

### Steps
1. Create and run migrations for `organizers` and `event_organizer` tables
2. Create Organizer model with relationships
3. Add organizer relationship to Event model
4. Create Filament Resource for organizer management
5. Add relation manager to EventResource
6. Create translation files
7. Create seeder with initial data
8. Test all functionality

### Rollback
- Drop migrations (will reverse table creation)
- Remove Organizer model
- Remove relationships from Event model
- Remove Filament Resource
- Remove translation files
- Remove seeder registration

## Risks / Trade-offs

### Risks
1. **Complex social_media JSON field** - Mitigation: Use proper validation and casting, consider separate fields if expansion needed
2. **Large logo files** - Mitigation: Use existing image processing (intervention/image), implement file size limits
3. **Performance with many organizers** - Mitigation: Proper indexing, pagination in Filament table

### Trade-offs
1. **Simple JSON for social_media** vs **separate tables** - Chose JSON for simplicity; can refactor if complex querying needed
2. **Modal forms** vs **separate pages** - Chose modal for consistency with project conventions and better UX
3. **Soft delete** vs **hard delete** - Chose soft delete for data safety and recovery

## Open Questions
None at this time. Requirements are clear.

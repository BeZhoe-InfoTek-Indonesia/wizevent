# Proposal: Implement Event Management (EPIC-003)

## Why
The platform currently lacks event management capabilities, which is a critical blocker for all downstream features (ticket sales, seat selection, check-in, etc.). Implementing comprehensive event management with multi-tier ticket configuration, lifecycle state management, and publishing workflow will enable the complete event lifecycle from draft to publication.

## What Changes
- Create complete event management system with CRUD operations
- Implement multi-tier ticket type configuration with inventory tracking
- Add event lifecycle state machine (draft → published → sold_out/cancelled)
- Create publishing workflow with validation
- Build Filament admin interface for event management
- Implement authorization policies for event operations
- Add database seeders for roles, permissions, and default data

## Change ID
`implement-event-management`

## Overview
Implement comprehensive event management capabilities including CRUD operations, multi-tier ticket type configuration, event lifecycle state management (draft, published, sold out, cancelled), and event publishing workflow with validation. This delivers the core value proposition of the platform by enabling the complete event lifecycle from draft to publication.

## Source
- **EPIC**: EPIC-003: Event Management & Publishing
- **User Stories**: US-01 (Event creation), US-02 (Seating layout - Phase 2), US-03 (Event publishing)
- **Priority**: P0 (Critical Path)

## Problem Statement
Currently, the platform has no event management capabilities. Event organizers cannot create, configure, or publish events. This is a critical blocker for all downstream features (ticket sales, seat selection, check-in, etc.).

## Proposed Solution

### Phase 1: Core Event Management (This Proposal)
Implement the foundational event management system with:

1. **Database Schema**
   - `events` table with all core fields
   - `ticket_types` table for multi-tier pricing
   - `event_categories` and `event_tags` for organization
   - `file_buckets` table for polymorphic file storage (centralized file management)
   - Proper relationships and constraints

2. **Models & Business Logic**
   - `Event` model with lifecycle state management
   - `TicketType` model with inventory tracking
   - `EventCategory` and `EventTag` models
   - `FileBucket` model for polymorphic file storage
   - Service layer for business logic (`EventService`, `TicketTypeService`, `FileBucketService`)

3. **Filament Admin Interface**
   - Event Resource with CRUD operations
   - **TipTap rich text editor** for event descriptions (Filament's built-in RichEditor)
   - **Google Maps location picker** with autocomplete and coordinates
   - Image upload for event banners (via FileBucketService)
   - Ticket type management (nested in Event form)
   - Event publishing workflow with validation
   - Category and tag management

4. **Event Lifecycle Management**
   - Draft → Published → Sold Out → Cancelled state transitions
   - Validation before publishing
   - Inventory tracking and overselling prevention

### Out of Scope (Future Phases)
- SVG-based seating layout designer (Phase 2 - separate proposal)
- Discount code management (Phase 2)
- Event duplication/cloning (Phase 2)
- Real-time sales analytics dashboard (EPIC-011)
- Recurring events (Future)

## Capabilities Affected

### New Capabilities
1. **event-management** - Core event CRUD and lifecycle management
2. **ticket-type-management** - Multi-tier ticket configuration
3. **event-categorization** - Category and tag system for events
4. **file-bucket-management** - Polymorphic file storage system for all uploads

## Design Considerations

### Event Lifecycle State Machine
```
[Draft] ──publish──► [Published] ──sold_out──► [Sold Out]
   │                      │                         │
   │                      │                         │
   └──────delete──────────┴────────cancel───────────┴──► [Cancelled]
```

**Business Rules:**
- Only Draft events can be deleted
- Published events can be cancelled but not deleted
- Publishing requires validation (title, description, date, location, banner, at least one ticket type)
- Sold Out status is automatically set when all ticket types are sold out
- Cancelled events cannot be un-cancelled

### Database Design Decisions

**Events Table:**
- `status` enum: draft, published, sold_out, cancelled
- `published_at` timestamp for audit trail
- `sales_start_at` and `sales_end_at` for sales window control
- `seating_enabled` boolean for future seating layout feature
- Soft deletes for audit trail

**Ticket Types:**
- `quantity` for total inventory
- `sold_count` for tracking sales (updated atomically)
- `available_count` computed property
- Row-level locking for inventory management

### Polymorphic File Storage Strategy (File Buckets)
- **Centralized Storage**: All files stored in `file_buckets` table with polymorphic relationships
- **Supported Types**: Images (JPG, PNG, WebP), Documents (PDF), Videos (MP4) - extensible
- **File Organization**: 
  - Path: `storage/app/public/file-buckets/{bucket_type}/{fileable_type}/{fileable_id}/{filename}`
  - Example: `storage/app/public/file-buckets/event-banners/events/123/banner.jpg`
- **Bucket Types**:
  - `event-banners` - Event banner images
  - `event-galleries` - Event gallery images (future)
  - `category-icons` - Category icon images
  - `ticket-type-images` - Ticket type promotional images (future)
  - `user-avatars` - User profile pictures (future)
- **Automatic Processing**:
  - Images: Generate multiple sizes (thumbnail, medium, large)
  - Validation: File type, size, dimensions
  - Metadata: Store original filename, mime type, size, dimensions
- **Benefits**:
  - Single source of truth for all file uploads
  - Easy to query files by type or owner
  - Consistent file management across the application
  - Supports multiple files per entity
  - Audit trail for file uploads/deletions

### Permission Structure
- `events.view` - View events list
- `events.create` - Create new events
- `events.edit` - Edit existing events
- `events.publish` - Publish events (separate from edit)
- `events.delete` - Delete draft events
- `events.cancel` - Cancel published events

## Technical Approach

### Technology Stack
- **Backend**: Laravel 11.x with Eloquent ORM
- **Admin UI**: Filament v4.7 with modal forms
- **Rich Text**: Filament's RichEditor component (TipTap)
- **Location**: Google Maps JavaScript API with Places Autocomplete
- **Image Upload**: Filament's FileUpload component with FileBucketService
- **Image Processing**: Intervention Image for thumbnails
- **Validation**: Laravel Form Requests
- **Authorization**: Spatie Laravel Permission

### Service Layer Pattern
```php
class EventService
{
    public function createEvent(array $data): Event
    public function updateEvent(Event $event, array $data): Event
    public function publishEvent(Event $event): Event
    public function cancelEvent(Event $event, string $reason): Event
    public function validateForPublishing(Event $event): array
}

class FileBucketService
{
    public function upload(Model $fileable, UploadedFile $file, string $bucketType, array $options = []): FileBucket
    public function uploadMultiple(Model $fileable, array $files, string $bucketType, array $options = []): Collection
    public function retrieve(Model $fileable, string $bucketType, ?string $size = null): ?FileBucket
    public function retrieveAll(Model $fileable, string $bucketType): Collection
    public function delete(FileBucket $fileBucket): bool
    public function deleteAll(Model $fileable, string $bucketType): int
    public function getUrl(FileBucket $fileBucket, ?string $size = null): string
    public function processImage(FileBucket $fileBucket, array $sizes = []): void
}
```

## Impact Analysis

### Database Changes
- **New Tables**: events, ticket_types, event_categories, event_tags, event_tag_pivot, file_buckets
- **Migrations**: 6 new migration files
- **Seeders**: EventCategorySeeder for default categories

### Code Changes
- **New Models**: 5 models (Event, TicketType, EventCategory, EventTag, FileBucket)
- **New Services**: 3 services (EventService, TicketTypeService, FileBucketService)
- **New Resources**: 3 Filament resources (EventResource, EventCategoryResource, EventTagResource)
- **New Permissions**: 6 new permissions
- **Translation Files**: event.php (en/id)

### User Impact
- Event Managers can now create and publish events
- Super Admins can manage all events
- Foundation for all ticket sales features

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Concurrent ticket purchases cause overselling | High | Use database row-level locking (`lockForUpdate()`) |
| Large image uploads slow down event creation | Medium | Client-side compression, async processing via FileBucketService |
| Rich text editor allows XSS attacks | High | Use Filament's built-in sanitization, validate HTML |
| Complex validation rules cause UX friction | Medium | Progressive disclosure, clear error messages |
| Google Maps API quota exceeded | Medium | Implement caching for geocoded locations, monitor usage |
| Google Maps API key exposed | High | Store in `.env`, never commit to repository, use HTTP referrer restrictions |

## Dependencies

### Prerequisites
- ✅ EPIC-001: Platform Foundation (completed)
- ✅ EPIC-002: Authentication & Authorization (completed)
- ✅ Filament v4.7 installed and configured
- ✅ Spatie Permission configured

### External Dependencies
- Intervention Image (already in composer.json)
- Filament FileUpload component (built-in)
- Filament RichEditor component (built-in, TipTap-powered)
- **Google Maps JavaScript API** (requires API key in `.env` as `GOOGLE_MAPS_API_KEY`)
  - Places API enabled for autocomplete
  - Geocoding API for coordinate lookups

## Testing Strategy

### Unit Tests
- Event model state transitions
- TicketType inventory calculations
- EventService business logic
- Validation rules

### Feature Tests
- Event CRUD operations
- Image upload and validation
- Publishing workflow
- Permission checks
- Inventory locking under concurrent load

### Manual Testing
- Create event with all fields
- Upload various image formats and sizes
- Test publishing validation
- Test state transitions
- Test ticket type management

## Success Criteria
- [ ] Event Managers can create events with all required fields
- [ ] Rich text editor works for event descriptions
- [ ] Image upload validates and stores correctly
- [ ] Multiple ticket types can be configured per event
- [ ] Publishing workflow validates required fields
- [ ] Published events have correct status
- [ ] Draft events are not publicly visible
- [ ] Inventory tracking prevents overselling
- [ ] All permissions work correctly
- [ ] Multi-language support (EN/ID) works
- [x] All tests pass

## Rollout Plan

### Phase 1: Database & Models (Tasks 1-4)
- Create migrations
- Create models with relationships
- Create seeders

### Phase 2: Services & Business Logic (Tasks 5-7)
- Implement EventService
- Implement TicketTypeService
- Add validation logic

### Phase 3: Admin Interface (Tasks 8-12)
- Create Filament resources
- Implement forms with translations
- Add image upload
- Add rich text editor
- Implement publishing workflow

### Phase 4: Testing & Documentation (Tasks 13-15)
- Write tests
- Update documentation
- Manual QA

## Open Questions
1. Should we implement event duplication in this phase or defer to Phase 2?
   - **Recommendation**: Defer to Phase 2 to keep scope manageable
2. Do we need event approval workflow (draft → pending approval → published)?
   - **Recommendation**: Not for MVP, add if requested
3. Should sales window (sales_start_at/sales_end_at) be required or optional?
   - **Recommendation**: Optional, default to event creation time and event date

## Related Changes
- **Blocks**: All ticket sales features, seat selection, check-in
- **Enables**: EPIC-004 (Event Discovery), EPIC-005 (Seat Selection), EPIC-006 (Payment)

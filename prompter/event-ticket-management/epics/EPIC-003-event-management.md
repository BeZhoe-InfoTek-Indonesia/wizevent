# EPIC-003: Event Management & Publishing

## Business Value Statement

Empower event organizers to create, configure, and publish events with rich media, multi-tier ticketing, seating layouts, and promotional tools—all through an intuitive admin interface. This EPIC delivers the core value proposition of the platform by enabling the complete event lifecycle from draft to post-event analytics.

## Description

Implement comprehensive event management capabilities including CRUD operations, rich text editing, image upload, multi-tier ticket type configuration, SVG-based seating layout designer, discount code management, event lifecycle state management (draft, published, sold out, cancelled), and event publishing workflow with validation.

## Source Traceability

| Document | Reference | Section/Page |
|----------|-----------|--------------|
| PRD | US-01 | Event creation with all details |
| PRD | US-02 | Seating layout designer |
| PRD | US-03 | Event publishing |
| PRD | US-19 | Real-time sales analytics |
| PRD | Scope → Event Management | All event management features |

## Scope Definition

| In Scope | Out of Scope |
|----------|--------------|
| Event CRUD (Create, Read, Update, Delete) | Recurring events / event series |
| Rich text editor for event description | AI-powered event description generation |
| Banner image upload with validation | Image editing/cropping tools |
| Multi-tier ticket type configuration | Dynamic pricing rules engine |
| SVG-based seating layout designer | 3D venue visualization |
| Discount code creation and management | Advanced promo code analytics |
| Event lifecycle states (draft, published, sold out, cancelled) | Automated event archiving |
| Event publishing workflow with validation | Scheduled publishing |
| Event duplication/cloning | Event templates library |
| Event category and tag management | Multi-language event descriptions |
| Inventory management and overselling prevention | Waitlist management |
| Event search and filtering (admin view) | Advanced event recommendation engine |

## High-Level Acceptance Criteria

- [ ] Admin can create new event with title, description, date, location, banner image
- [ ] Rich text editor supports formatting, links, and lists for event description
- [ ] Banner image upload validates file type (JPG/PNG/WebP) and size (≤5MB)
- [ ] Admin can configure unlimited ticket types with name, price, quantity per event
- [ ] Seating layout designer allows creating sections, rows, and seats via SVG interface
- [ ] Seats can be mapped to ticket types with pricing
- [ ] Admin can create discount codes with percentage/fixed amount, usage limits, expiration
- [ ] Event status can be changed: Draft → Published → Sold Out → Cancelled
- [ ] Publishing workflow validates required fields before allowing publish
- [ ] Published events appear in public search results
- [ ] Draft events are only visible to event creator and admins
- [ ] Event inventory is tracked in real-time (tickets sold vs available)
- [ ] Overselling prevention via database row-level locking
- [ ] Admin can duplicate existing events to create new ones
- [ ] Event categories and tags can be assigned for search/filtering

## Dependencies

- **Prerequisite EPICs:** EPIC-001 (Platform Foundation), EPIC-002 (Authentication)
- **External Dependencies:**
  - Image processing library (Intervention Image)
  - Rich text editor (TinyMCE or Quill.js)
  - SVG manipulation library for seating designer
- **Technical Prerequisites:**
  - Events, ticket_types, venues, sections, seats tables from EPIC-001
  - File storage configuration (local or S3)
  - Image optimization pipeline

## Complexity Assessment

- **Size:** XL (Extra Large)
- **Technical Complexity:** High
  - SVG seating layout designer
  - Real-time inventory management
  - Image upload and processing
  - Complex validation rules
- **Integration Complexity:** Medium
  - File storage integration
  - Rich text editor integration
  - Discount code validation logic
- **Estimated Story Count:** 15-18 stories

## Technical Details

### Event Lifecycle State Machine

```
[Draft] ──publish──► [Published] ──sold_out──► [Sold Out]
   │                      │                         │
   │                      │                         │
   └──────delete──────────┴────────cancel───────────┴──► [Cancelled]
```

### Seating Layout Data Structure

```json
{
  "venue_id": 123,
  "sections": [
    {
      "id": "vip-section",
      "name": "VIP",
      "capacity": 50,
      "rows": [
        {
          "row": "A",
          "seats": [
            {"number": 1, "type": "standard", "price": 150.00},
            {"number": 2, "type": "accessible", "price": 150.00}
          ]
        }
      ]
    }
  ]
}
```

### Inventory Management Logic

```php
// Prevent overselling with database locking
DB::transaction(function () use ($ticketTypeId, $quantity) {
    $ticketType = TicketType::where('id', $ticketTypeId)
        ->lockForUpdate()
        ->first();
    
    if ($ticketType->quantity - $ticketType->sold_count < $quantity) {
        throw new InsufficientInventoryException();
    }
    
    $ticketType->increment('sold_count', $quantity);
});
```

## Risks & Assumptions

**Assumptions:**
- Event organizers are familiar with basic event management concepts
- SVG-based 2D seating maps are sufficient (no 3D visualization needed)
- Image upload size limit of 5MB is acceptable
- Database-backed inventory tracking is sufficient (no Redis required for MVP)

**Risks:**
- **Risk:** SVG seating designer UX complexity leads to user frustration
  - **Mitigation:** Provide venue templates and drag-and-drop interface
- **Risk:** Concurrent ticket purchases cause overselling
  - **Mitigation:** Database row-level locking and atomic operations
- **Risk:** Large image uploads slow down event creation
  - **Mitigation:** Client-side image compression before upload
- **Risk:** Rich text editor allows XSS attacks
  - **Mitigation:** Sanitize HTML output, use trusted editor library

## Related EPICs

- **Depends On:** EPIC-001 (Platform Foundation), EPIC-002 (Authentication)
- **Blocks:** EPIC-004 (Event Discovery), EPIC-005 (Seat Selection), EPIC-006 (Payment), EPIC-011 (Analytics)
- **Related:** EPIC-009 (Social features for events), EPIC-010 (Event update notifications)

## User Stories Covered

- **US-01:** As an event organizer, I want to create a new event with all details
- **US-02:** As an event organizer, I want to design a seating layout
- **US-03:** As an event organizer, I want to publish my event
- **US-19:** As an event organizer, I want to view real-time sales analytics (partial - event data)

## Definition of Done

- [ ] All acceptance criteria met and verified
- [ ] Unit tests for EventService business logic
- [ ] Feature tests for event CRUD operations
- [ ] Seating designer tested with various venue configurations
- [ ] Image upload tested with different file types and sizes
- [ ] Inventory locking tested under concurrent load
- [ ] Discount code validation tested with edge cases
- [ ] Event publishing workflow tested with incomplete data
- [ ] Admin UI responsive and mobile-friendly
- [ ] Documentation for event management workflow
- [ ] Code reviewed and approved

---

**EPIC Owner:** Product Owner + Tech Lead  
**Estimated Effort:** 3-4 sprints (6-8 weeks)  
**Priority:** P0 (Critical Path)

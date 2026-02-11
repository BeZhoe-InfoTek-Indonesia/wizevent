# ticket-type-management Specification

## Purpose
TBD - created by archiving change implement-event-management. Update Purpose after archive.
## Requirements
### Requirement: Ticket Type Creation
Event organizers must be able to create multiple ticket types per event.

#### Scenario: Create basic ticket type
**Given** an authenticated user with `events.edit` permission  
**And** an existing event  
**When** they create a ticket type with name, price, and quantity  
**Then** the ticket type is created and associated with the event  
**And** `sold_count` is initialized to 0  
**And** the event's `total_capacity` is updated

#### Scenario: Create ticket type with purchase limits
**Given** an authenticated user with `events.edit` permission  
**And** an existing event  
**When** they create a ticket type with min_purchase=2 and max_purchase=10  
**Then** the ticket type is created with the specified limits  
**And** future purchases must respect these limits

#### Scenario: Create ticket type with sales window
**Given** an authenticated user with `events.edit` permission  
**And** an existing event  
**When** they create a ticket type with sales_start_at and sales_end_at  
**Then** the ticket type is created  
**And** it will only be available for purchase within the specified window

### Requirement: Ticket Type Editing
Ticket types can be edited with restrictions.

#### Scenario: Edit ticket type before any sales
**Given** an authenticated user with `events.edit` permission  
**And** a ticket type with `sold_count` = 0  
**When** they update any field (name, price, quantity, etc.)  
**Then** the changes are saved  
**And** the event's `total_capacity` is recalculated

#### Scenario: Edit ticket type quantity after sales
**Given** an authenticated user with `events.edit` permission  
**And** a ticket type with `sold_count` > 0  
**When** they attempt to reduce quantity below `sold_count`  
**Then** the operation is blocked with error "Cannot reduce quantity below sold count"

#### Scenario: Edit ticket type price after sales
**Given** an authenticated user with `events.edit` permission  
**And** a ticket type with `sold_count` > 0  
**When** they update the price  
**Then** the price is updated  
**And** existing sold tickets retain their original price

### Requirement: Ticket Type Deletion
Ticket types can only be deleted if no tickets have been sold.

#### Scenario: Delete unused ticket type
**Given** an authenticated user with `events.edit` permission  
**And** a ticket type with `sold_count` = 0  
**When** they delete the ticket type  
**Then** the ticket type is soft-deleted  
**And** the event's `total_capacity` is recalculated

#### Scenario: Cannot delete ticket type with sales
**Given** an authenticated user with `events.edit` permission  
**And** a ticket type with `sold_count` > 0  
**When** they attempt to delete the ticket type  
**Then** the operation is blocked with error "Cannot delete ticket type with sold tickets"

### Requirement: Inventory Tracking
Ticket inventory must be tracked in real-time to prevent overselling.

#### Scenario: Calculate available tickets
**Given** a ticket type with quantity=100 and sold_count=30  
**When** the available count is requested  
**Then** it returns 70 (quantity - sold_count)

#### Scenario: Mark event as sold out
**Given** an event with multiple ticket types  
**When** all ticket types reach sold_count = quantity  
**Then** the event status is automatically updated to "sold_out"

#### Scenario: Prevent overselling with concurrent purchases
**Given** a ticket type with 5 tickets remaining  
**When** two users simultaneously attempt to purchase 4 tickets each  
**Then** only the first purchase succeeds  
**And** the second purchase is blocked with error "Insufficient inventory"  
**And** database row-level locking prevents race conditions

### Requirement: Ticket Type Activation
Ticket types can be temporarily disabled without deletion.

#### Scenario: Deactivate ticket type
**Given** an authenticated user with `events.edit` permission  
**And** an active ticket type  
**When** they set `is_active` to false  
**Then** the ticket type is no longer available for purchase  
**And** it remains visible in the admin interface

#### Scenario: Reactivate ticket type
**Given** an authenticated user with `events.edit` permission  
**And** an inactive ticket type  
**When** they set `is_active` to true  
**Then** the ticket type becomes available for purchase again

### Requirement: Ticket Type Ordering
Ticket types can be ordered for display purposes.

#### Scenario: Set display order
**Given** an authenticated user with `events.edit` permission  
**And** multiple ticket types for an event  
**When** they set `sort_order` values  
**Then** ticket types are displayed in ascending sort_order  
**And** lower numbers appear first

### Requirement: Ticket Type Validation
Ticket type data must be validated.

#### Scenario: Validate price is non-negative
**Given** an authenticated user creating a ticket type  
**When** they enter a negative price  
**Then** validation fails with error "Price must be 0 or greater"

#### Scenario: Validate quantity is positive
**Given** an authenticated user creating a ticket type  
**When** they enter quantity = 0  
**Then** validation fails with error "Quantity must be at least 1"

#### Scenario: Validate max_purchase >= min_purchase
**Given** an authenticated user creating a ticket type  
**When** they set min_purchase=5 and max_purchase=3  
**Then** validation fails with error "Max purchase must be greater than or equal to min purchase"

#### Scenario: Validate sales window
**Given** an authenticated user creating a ticket type  
**When** they set sales_end_at before sales_start_at  
**Then** validation fails with error "Sales end date must be after sales start date"


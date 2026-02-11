# order-management Specification

## Purpose
The system SHALL manage the lifecycle of ticket orders, from initial guest selection to final payment and fulfillment.

## ADDED Requirements

### Requirement: Order Creation
The system SHALL allow authenticated users or guests to create orders by selecting ticket types and quantities.

#### Scenario: Successful order initialization
- **GIVEN** an active event with available tickets
- **WHEN** a user selects a ticket type and quantity and clicks "Checkout"
- **THEN** an `Order` record is created with status `pending_payment`
- **AND** the requested quantity is reserved in the `TicketType` inventory

#### Scenario: Multi-type ticket selection
- **GIVEN** an event with "VIP" and "Standard" ticket types
- **WHEN** a user selects 2 "VIP" tickets and 3 "Standard" tickets
- **THEN** a single `Order` is created containing two `OrderItems`
- **AND** inventory is reserved for both ticket types respectively

### Requirement: Order Expiration
The system SHALL automatically expire orders that have not been paid within a configurable timeout period.

#### Scenario: Order timeout
- **GIVEN** an order in `pending_payment` status created 24 hours ago
- **WHEN** the scheduled cleanup task runs
- **THEN** the order status is updated to `expired`
- **AND** the reserved inventory is released back to the `TicketType`

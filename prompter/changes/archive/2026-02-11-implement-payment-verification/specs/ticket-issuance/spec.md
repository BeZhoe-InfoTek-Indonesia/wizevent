# ticket-issuance Specification

## Purpose
The system SHALL handle the generation and delivery of digital tickets upon successful payment verification.

## ADDED Requirements

### Requirement: Automated Ticket Generation
The system SHALL automatically generate `Ticket` records once an order reaches `completed` status.

#### Scenario: Generation after payment
- **GIVEN** an order that just transitioned to `completed`
- **WHEN** the issuance service runs
- **THEN** one `Ticket` record is created for each item in the order
- **AND** each ticket has a unique `ticket_number`

### Requirement: Email Delivery with Tickets
The system SHALL send an email to the customer containing their order details and digital tickets.

#### Scenario: Success email delivery
- **GIVEN** a `completed` order with generated tickets
- **WHEN** the notification task executes
- **THEN** an email is sent to the user's registered address
- **AND** the email includes a summary of the purchase and links to download tickets

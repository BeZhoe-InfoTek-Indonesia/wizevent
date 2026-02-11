# file-generation Specification

## Purpose
TBD - created by archiving change implement-successful-payment-email. Update Purpose after archive.
## Requirements
### Requirement: Generate Invoice PDF
The system MUST generate a PDF invoice for every completed order.

#### Scenario: Successful Invoice Generation
- **Given** an order is marked as "Completed"
- **Then** the system must generate a PDF invoice containing:
    - Order Number
    - Customer Name
    - Event Details (Title, Date, Venue)
    - Itemized list of tickets
    - Financial breakdown (Subtotal, Tax, Discount, Total)
- **And** store it in a secure location.

### Requirement: Generate Ticket PDF
The system MUST generate a PDF ticket for each purchased ticket.

#### Scenario: Successful Ticket Generation
- **Given** an order is marked as "Completed"
- **Then** the system must generate a PDF ticket for each ticket item containing:
    - Event Title and Poster
    - Ticket Type
    - Ticket Number
    - QR Code (scannable)
    - Attendee Name (if available)

### Requirement: Download Links
The system MUST provide secure download links for invoices and tickets.

#### Scenario: Secure Downloads
- **Given** a user has received the order confirmation email
- **When** they click the "Download Invoice" or "Download Ticket" link
- **Then** they should be authenticated (or use a signed URL) to view/download the file.


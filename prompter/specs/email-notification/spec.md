# email-notification Specification

## Purpose
TBD - created by archiving change implement-successful-payment-email. Update Purpose after archive.
## Requirements
### Requirement: Payment Verification Email
The system MUST send an email notification upon successful payment verification.

#### Scenario: Payment Verification Approved
- **Given** an admin approves a payment proof OR manually approves an order
- **Then** the `PaymentVerificationApproved` email is sent.
- **And** the email includes:
    - "Download Invoice" button/link.
    - "Download Ticket" button/link for each ticket (or a "View All Tickets" link).
    - A QR code image embedded for the primary ticket (or all if feasible).
    - Event Date, Time, and Venue details.

#### Scenario: Fallback Mechanism
- **Given** PDF generation fails
- **Then** the system should log the error
- **And** retry the job (standard queue behavior)
- **And** NOT send a broken email.

